<?php
/**
 * Non-auto request feed adapter for the existing Ensurance agent dashboard.
 *
 * The dashboard UI remains shared. Auto keeps its existing backend. This adapter
 * adds Home, Life, Health and Renters records through the dashboard's existing
 * filter seams without changing the Auto lead fetch or Auto purchase history.
 */

if ( ! defined( 'ABSPATH' ) ) {
    exit;
}

function ensurance_non_auto_dashboard_webhook_url() {
    return esc_url_raw( (string) get_option( 'ensurance_non_auto_dashboard_webhook_url', '' ) );
}

function ensurance_non_auto_decision_webhook_url() {
    return esc_url_raw( (string) get_option( 'ensurance_non_auto_decision_webhook_url', '' ) );
}

function ensurance_non_auto_mark_request_viewed( $request_id, $user_id = 0 ) {
    static $marked = array();

    $request_id = sanitize_text_field( (string) $request_id );
    $user_id    = $user_id ? (int) $user_id : get_current_user_id();

    if ( '' === $request_id || ! $user_id || isset( $marked[ $request_id ] ) ) {
        return;
    }

    $user  = get_userdata( $user_id );
    $email = ( $user instanceof WP_User ) ? sanitize_email( $user->user_email ) : '';
    $url   = ensurance_non_auto_decision_webhook_url();

    if ( ! is_email( $email ) || '' === $url ) {
        return;
    }

    $marked[ $request_id ] = true;

    wp_remote_post(
        $url,
        array(
            'timeout'  => 1,
            'blocking' => false,
            'headers'  => array( 'Content-Type' => 'application/json' ),
            'body'     => wp_json_encode(
                array(
                    'request_id'  => $request_id,
                    'decision'    => 'view',
                    'agent_email' => strtolower( trim( $email ) ),
                )
            ),
        )
    );
}

/**
 * Resolve one request that is currently offered and available to the signed-in
 * professional. This keeps purchase policy selection on the Ensurance server,
 * not in browser parameters.
 */
function ensurance_non_auto_dashboard_offered_request( $request_id, $user_id = 0 ) {
    $request_id = sanitize_text_field( (string) $request_id );
    $user_id    = $user_id ? (int) $user_id : get_current_user_id();

    if ( '' === $request_id || ! $user_id ) {
        return array();
    }

    foreach ( ensurance_non_auto_dashboard_requests( $user_id ) as $request ) {
        if ( isset( $request['request_id'], $request['status'] ) && $request_id === $request['request_id'] && 'available' === $request['status'] ) {
            return $request;
        }
    }

    return array();
}

/**
 * Handle decisions for a real non-auto request before the legacy dashboard
 * decision handler runs. Auto requests do not carry dash_request_id and never
 * enter this callback.
 */
function ensurance_non_auto_handle_dashboard_decision() {
    if ( empty( $_POST['dash_request_id'] ) || empty( $_POST['dash_decision'] ) || ! is_page( 'dashboard' ) || ! is_user_logged_in() ) {
        return;
    }

    $request_id = sanitize_text_field( wp_unslash( $_POST['dash_request_id'] ) );
    $decision   = sanitize_key( wp_unslash( $_POST['dash_decision'] ) );
    $nonce      = isset( $_POST['dash_decide_nonce'] ) ? sanitize_text_field( wp_unslash( $_POST['dash_decide_nonce'] ) ) : '';

    if ( '' === $request_id || ! in_array( $decision, array( 'accept', 'pass' ), true ) || ! wp_verify_nonce( $nonce, 'ensurance_dashboard_decide' ) ) {
        return;
    }

    $user  = wp_get_current_user();
    $email = ( $user instanceof WP_User ) ? sanitize_email( $user->user_email ) : '';
    $url   = ensurance_non_auto_decision_webhook_url();

    if ( ! is_email( $email ) || '' === $url ) {
        wp_safe_redirect( add_query_arg( 'nonauto_status', 'error', home_url( '/for-agents/dashboard/' ) ) );
        exit;
    }

    /*
     * Ensurance purchase control: many eligible professionals may review a
     * protected request, up to five may begin checkout, and up to two successful
     * purchases may ultimately unlock the shopper.
     */
    if ( 'accept' === $decision && function_exists( 'ensurance_non_auto_purchase_start' ) ) {
        $offered_request = ensurance_non_auto_dashboard_offered_request( $request_id, get_current_user_id() );
        if ( empty( $offered_request ) ) {
            wp_safe_redirect( add_query_arg( array( 'nonauto_status' => 'unavailable', 'request_id' => $request_id ), home_url( '/for-agents/dashboard/' ) ) );
            exit;
        }

        $coverage     = isset( $offered_request['coverage_type'] ) ? sanitize_key( $offered_request['coverage_type'] ) : '';
        $max_checkout = 'life' === $coverage ? 3 : 5;
        $max_grants   = 'life' === $coverage ? 1 : 2;
        $claim_secret = (string) get_option( 'ensurance_make_claim_secret', '' );
        $slot_request = new WP_REST_Request( 'POST', '/ensurance/v1/non-auto/checkout/start' );
        $slot_request->set_header( 'x-ensurance-claim-secret', $claim_secret );
        $slot_request->set_body_params(
            array(
                'request_id'           => $request_id,
                'agent_email'          => strtolower( trim( $email ) ),
                'max_checkout_windows' => $max_checkout,
                'max_access_grants'    => $max_grants,
            )
        );

        $slot = ensurance_non_auto_purchase_start( $slot_request );
        if ( is_wp_error( $slot ) ) {
            $slot_code = $slot->get_error_code();
            $status    = 'checkout_capacity' === $slot_code ? 'checkout_capacity' : ( in_array( $slot_code, array( 'request_closed', 'already_purchased' ), true ) ? 'unavailable' : 'error' );
            wp_safe_redirect( add_query_arg( array( 'nonauto_status' => $status, 'request_id' => $request_id ), home_url( '/for-agents/dashboard/' ) ) );
            exit;
        }
    }

    $response = wp_remote_post(
        $url,
        array(
            'timeout' => 12,
            'headers' => array( 'Content-Type' => 'application/json' ),
            'body'    => wp_json_encode(
                array(
                    'request_id'  => $request_id,
                    'decision'    => $decision,
                    'agent_email' => strtolower( trim( $email ) ),
                )
            ),
        )
    );

    if ( is_wp_error( $response ) ) {
        error_log( 'Ensurance non-auto decision: ' . $response->get_error_message() );
        wp_safe_redirect( add_query_arg( 'nonauto_status', 'error', home_url( '/for-agents/dashboard/' ) ) );
        exit;
    }

    $code = (int) wp_remote_retrieve_response_code( $response );
    $body = json_decode( wp_remote_retrieve_body( $response ), true );

    if ( 'pass' === $decision && 200 === $code && is_array( $body ) && ! empty( $body['ok'] ) ) {
        delete_user_meta( get_current_user_id(), ENSURANCE_DASHBOARD_DECISION_META );
        wp_safe_redirect( add_query_arg( 'nonauto_status', 'passed', home_url( '/for-agents/dashboard/' ) ) );
        exit;
    }

    if ( 'accept' === $decision && 200 === $code && is_array( $body ) && ! empty( $body['url'] ) ) {
        $checkout_url = esc_url_raw( (string) $body['url'] );
        $host         = wp_parse_url( $checkout_url, PHP_URL_HOST );
        $scheme       = wp_parse_url( $checkout_url, PHP_URL_SCHEME );

        $host           = is_string( $host ) ? strtolower( $host ) : '';
        $is_stripe_host = ( 'checkout.stripe.com' === $host ) || ( strlen( $host ) > 11 && '.stripe.com' === substr( $host, -11 ) );

        if ( 'https' === $scheme && $is_stripe_host ) {
            wp_redirect( $checkout_url, 303 );
            exit;
        }
    }

    $status = ( 409 === $code ) ? 'unavailable' : 'error';
    wp_safe_redirect( add_query_arg( 'nonauto_status', $status, home_url( '/for-agents/dashboard/' ) ) );
    exit;
}
add_action( 'template_redirect', 'ensurance_non_auto_handle_dashboard_decision', 1 );

/** Release a non-auto checkout window when Stripe sends the professional back via cancel_url. */
function ensurance_non_auto_handle_checkout_cancel() {
    if ( ! is_page( 'dashboard' ) || ! is_user_logged_in() || empty( $_GET['nonauto_checkout'] ) || 'cancelled' !== sanitize_key( wp_unslash( $_GET['nonauto_checkout'] ) ) || empty( $_GET['request_id'] ) ) {
        return;
    }

    $request_id = sanitize_text_field( wp_unslash( $_GET['request_id'] ) );
    $user       = wp_get_current_user();
    $email      = ( $user instanceof WP_User ) ? sanitize_email( $user->user_email ) : '';
    $url        = ensurance_non_auto_decision_webhook_url();

    if ( '' !== $request_id && is_email( $email ) && function_exists( 'ensurance_non_auto_purchase_release' ) ) {
        $claim_secret    = (string) get_option( 'ensurance_make_claim_secret', '' );
        $release_request = new WP_REST_Request( 'POST', '/ensurance/v1/non-auto/checkout/release' );
        $release_request->set_header( 'x-ensurance-claim-secret', $claim_secret );
        $release_request->set_body_params(
            array(
                'request_id'  => $request_id,
                'agent_email' => strtolower( trim( $email ) ),
            )
        );
        ensurance_non_auto_purchase_release( $release_request );
    }

    if ( '' !== $request_id && is_email( $email ) && '' !== $url ) {
        $response = wp_remote_post(
            $url,
            array(
                'timeout' => 10,
                'headers' => array( 'Content-Type' => 'application/json' ),
                'body'    => wp_json_encode(
                    array(
                        'request_id'  => $request_id,
                        'decision'    => 'cancel',
                        'agent_email' => strtolower( trim( $email ) ),
                    )
                ),
            )
        );

        if ( is_wp_error( $response ) ) {
            error_log( 'Ensurance non-auto checkout cancel release: ' . $response->get_error_message() );
        }
    }

    wp_safe_redirect( add_query_arg( 'nonauto_status', 'checkout_cancelled', home_url( '/for-agents/dashboard/' ) ) );
    exit;
}
add_action( 'template_redirect', 'ensurance_non_auto_handle_checkout_cancel', 2 );

/**
 * Read one value from either a named Make/Sheets row or its stable positional key.
 */
function ensurance_non_auto_dashboard_row_value( $row, $name, $index ) {
    if ( isset( $row[ $name ] ) ) {
        return $row[ $name ];
    }

    $index = (string) $index;
    return isset( $row[ $index ] ) ? $row[ $index ] : '';
}

/**
 * Read the signed-in professional's finalized access grant from the first-party
 * purchase-control table. This is the authority for PII unlock and accepted
 * history, including the first purchaser while the request remains available to
 * a second purchaser.
 */
function ensurance_non_auto_dashboard_access_grant( $request_id, $agent_email ) {
    if ( ! function_exists( 'ensurance_non_auto_purchase_get_access_grant' ) ) {
        return array();
    }

    return ensurance_non_auto_purchase_get_access_grant( $request_id, $agent_email );
}

/**
 * Fetch all non-auto request rows that were offered to this signed-in agent.
 * One server-side request per dashboard render, memoized for the request lifetime.
 */
function ensurance_non_auto_dashboard_requests( $user_id = 0 ) {
    static $cache = array();

    $user_id = $user_id ? (int) $user_id : get_current_user_id();
    if ( ! $user_id ) {
        return array();
    }

    if ( isset( $cache[ $user_id ] ) ) {
        return $cache[ $user_id ];
    }

    $cache[ $user_id ] = array();

    // Keep administrator design previews deterministic and sample-only.
    if ( function_exists( 'ensurance_dashboard_priority_preview' ) && '' !== ensurance_dashboard_priority_preview() ) {
        return $cache[ $user_id ];
    }

    $email = function_exists( 'ensurance_dashboard_signin_email' )
        ? ensurance_dashboard_signin_email( $user_id )
        : '';

    if ( ! is_email( $email ) ) {
        return $cache[ $user_id ];
    }

    $url = ensurance_non_auto_dashboard_webhook_url();
    if ( '' === $url ) {
        return $cache[ $user_id ];
    }

    $response = wp_remote_post(
        $url,
        array(
            'timeout' => 8,
            'headers' => array( 'Content-Type' => 'application/json' ),
            'body'    => wp_json_encode(
                array(
                    'event'       => 'non_auto_dashboard_fetch',
                    'wp_user_id'  => $user_id,
                    'agent_email' => strtolower( trim( $email ) ),
                )
            ),
        )
    );

    if ( is_wp_error( $response ) ) {
        error_log( 'Ensurance non-auto dashboard feed: ' . $response->get_error_message() );
        return $cache[ $user_id ];
    }

    if ( 200 !== (int) wp_remote_retrieve_response_code( $response ) ) {
        error_log( 'Ensurance non-auto dashboard feed: HTTP ' . (int) wp_remote_retrieve_response_code( $response ) );
        return $cache[ $user_id ];
    }

    $body = json_decode( wp_remote_retrieve_body( $response ), true );

    // Tolerate the one common Make wrapper as well as a bare JSON array.
    if ( is_array( $body ) && isset( $body['array'] ) && is_array( $body['array'] ) ) {
        $body = $body['array'];
    }

    if ( ! is_array( $body ) ) {
        error_log( 'Ensurance non-auto dashboard feed: response was not a JSON array.' );
        return $cache[ $user_id ];
    }

    $allowed_coverages = array( 'life', 'home', 'health', 'renters' );
    $agent_email       = strtolower( trim( $email ) );
    $requests          = array();

    foreach ( $body as $row ) {
        if ( ! is_array( $row ) ) {
            continue;
        }

        $request_id = sanitize_text_field( (string) ensurance_non_auto_dashboard_row_value( $row, 'request_id', 0 ) );
        $coverage   = sanitize_key( (string) ensurance_non_auto_dashboard_row_value( $row, 'coverage_type', 1 ) );
        $status     = sanitize_key( (string) ensurance_non_auto_dashboard_row_value( $row, 'status', 2 ) );

        if ( '' === $request_id || ! in_array( $coverage, $allowed_coverages, true ) ) {
            continue;
        }

        $access_grant = ensurance_non_auto_dashboard_access_grant( $request_id, $agent_email );
        $accepted     = ! empty( $access_grant );
        $available    = 'available' === $status && ! $accepted;

        // A closed request is hidden from non-purchasers. A finalized purchaser
        // keeps the request in History and may see the shopper contact details.
        if ( ! $available && ! $accepted ) {
            continue;
        }

        $created_raw = sanitize_text_field( (string) ensurance_non_auto_dashboard_row_value( $row, 'created_at', 17 ) );
        $created_at  = $created_raw ? strtotime( $created_raw ) : 0;

        if ( $accepted && ! empty( $access_grant['updated_at'] ) ) {
            $purchased_raw = sanitize_text_field( (string) $access_grant['updated_at'] );
        } else {
            $purchased_raw = sanitize_text_field( (string) ensurance_non_auto_dashboard_row_value( $row, 'purchased_at', 21 ) );
        }
        $purchased_at = $purchased_raw ? strtotime( $purchased_raw ) : 0;

        $requests[] = array(
            'request_id'        => $request_id,
            'coverage_type'     => $coverage,
            'status'            => $accepted ? 'accepted' : 'available',
            'state'             => strtoupper( sanitize_text_field( (string) ensurance_non_auto_dashboard_row_value( $row, 'state_normalized', 4 ) ) ),
            'zip'               => sanitize_text_field( (string) ensurance_non_auto_dashboard_row_value( $row, 'zip', 5 ) ),
            'age'               => absint( ensurance_non_auto_dashboard_row_value( $row, 'age', 6 ) ),
            'first_name'        => sanitize_text_field( (string) ensurance_non_auto_dashboard_row_value( $row, 'first_name', 7 ) ),
            'last_name'         => sanitize_text_field( (string) ensurance_non_auto_dashboard_row_value( $row, 'last_name', 8 ) ),
            'email'             => sanitize_email( (string) ensurance_non_auto_dashboard_row_value( $row, 'email_normalized', 10 ) ),
            'phone'             => sanitize_text_field( (string) ensurance_non_auto_dashboard_row_value( $row, 'phone', 11 ) ),
            'created_at'        => $created_at ? (int) $created_at : 0,
            'purchased_at'      => $purchased_at ? (int) $purchased_at : 0,
            'accepted_price'    => sanitize_text_field( (string) ensurance_non_auto_dashboard_row_value( $row, 'accepted_price', 22 ) ),
            'preferred_contact' => sanitize_key( (string) ensurance_non_auto_dashboard_row_value( $row, 'preferred_contact', 12 ) ),
            'notes'             => sanitize_textarea_field( (string) ensurance_non_auto_dashboard_row_value( $row, 'notes', 24 ) ),
            'life_type'         => sanitize_key( (string) ensurance_non_auto_dashboard_row_value( $row, 'life_type', 25 ) ),
            'coverage_amount'   => sanitize_text_field( (string) ensurance_non_auto_dashboard_row_value( $row, 'coverage_amount', 26 ) ),
            'term_length'       => sanitize_text_field( (string) ensurance_non_auto_dashboard_row_value( $row, 'term_length', 27 ) ),
            'tobacco_use'       => sanitize_key( (string) ensurance_non_auto_dashboard_row_value( $row, 'tobacco_use', 28 ) ),
            'health_band'       => sanitize_key( (string) ensurance_non_auto_dashboard_row_value( $row, 'health_band', 29 ) ),
            'coverage_timing'   => sanitize_key( (string) ensurance_non_auto_dashboard_row_value( $row, 'coverage_timing', 31 ) ),
            'property_type'     => sanitize_key( (string) ensurance_non_auto_dashboard_row_value( $row, 'property_type', 32 ) ),
            'ownership_status'  => sanitize_key( (string) ensurance_non_auto_dashboard_row_value( $row, 'ownership_status', 33 ) ),
            'current_insurance' => sanitize_key( (string) ensurance_non_auto_dashboard_row_value( $row, 'current_insurance', 35 ) ),
            'renting_status'    => sanitize_key( (string) ensurance_non_auto_dashboard_row_value( $row, 'renting_status', 37 ) ),
            'renters_timing'    => sanitize_key( (string) ensurance_non_auto_dashboard_row_value( $row, 'renters_start_timing', 38 ) ),
            'health_type'       => sanitize_key( (string) ensurance_non_auto_dashboard_row_value( $row, 'health_request_type', 39 ) ),
            'household_size'    => absint( ensurance_non_auto_dashboard_row_value( $row, 'health_household_size', 40 ) ),
            'health_timing'     => sanitize_key( (string) ensurance_non_auto_dashboard_row_value( $row, 'health_coverage_timing', 41 ) ),
        );
    }

    usort(
        $requests,
        static function ( $a, $b ) {
            $a_at = 'accepted' === $a['status'] ? $a['purchased_at'] : $a['created_at'];
            $b_at = 'accepted' === $b['status'] ? $b['purchased_at'] : $b['created_at'];
            return $b_at <=> $a_at;
        }
    );

    $cache[ $user_id ] = $requests;
    return $cache[ $user_id ];
}

function ensurance_non_auto_coverage_label( $coverage ) {
    $labels = array(
        'life'    => 'Life',
        'home'    => 'Home',
        'health'  => 'Health',
        'renters' => 'Renters',
    );

    return isset( $labels[ $coverage ] ) ? $labels[ $coverage ] : ucfirst( $coverage );
}

function ensurance_non_auto_request_location( $request ) {
    $parts = array();
    if ( ! empty( $request['state'] ) ) {
        $parts[] = $request['state'];
    }
    if ( ! empty( $request['zip'] ) ) {
        $parts[] = 'ZIP ' . $request['zip'];
    }
    return implode( ' · ', $parts );
}

function ensurance_non_auto_request_facts( $request ) {
    $facts    = array();
    $coverage = isset( $request['coverage_type'] ) ? $request['coverage_type'] : '';

    if ( 'life' === $coverage ) {
        if ( ! empty( $request['age'] ) ) {
            $facts[] = array( 'label' => 'Age', 'value' => (string) $request['age'] );
        }
        if ( ! empty( $request['coverage_amount'] ) ) {
            $amount  = is_numeric( $request['coverage_amount'] ) ? '$' . number_format( (float) $request['coverage_amount'] ) : str_replace( '-', ' ', $request['coverage_amount'] );
            $facts[] = array( 'label' => 'Coverage', 'value' => $amount );
        }
        if ( ! empty( $request['life_type'] ) ) {
            $facts[] = array( 'label' => 'Type', 'value' => ucwords( str_replace( '-', ' ', $request['life_type'] ) ) );
        }
        if ( ! empty( $request['term_length'] ) ) {
            $facts[] = array( 'label' => 'Term', 'value' => ucwords( str_replace( '-', ' ', $request['term_length'] ) ) );
        }
        if ( ! empty( $request['tobacco_use'] ) ) {
            $facts[] = array( 'label' => 'Tobacco', 'value' => ucwords( str_replace( '-', ' ', $request['tobacco_use'] ) ) );
        }
        if ( ! empty( $request['coverage_timing'] ) ) {
            $facts[] = array( 'label' => 'Timing', 'value' => ucwords( str_replace( '-', ' ', $request['coverage_timing'] ) ) );
        }
    } elseif ( 'home' === $coverage ) {
        if ( ! empty( $request['property_type'] ) ) {
            $facts[] = array( 'label' => 'Property', 'value' => ucwords( str_replace( '-', ' ', $request['property_type'] ) ) );
        }
        if ( ! empty( $request['ownership_status'] ) ) {
            $facts[] = array( 'label' => 'Property status', 'value' => ucwords( str_replace( '-', ' ', $request['ownership_status'] ) ) );
        }
        if ( ! empty( $request['current_insurance'] ) ) {
            $facts[] = array( 'label' => 'Currently insured', 'value' => ucwords( str_replace( '-', ' ', $request['current_insurance'] ) ) );
        }
    } elseif ( 'renters' === $coverage ) {
        if ( ! empty( $request['renting_status'] ) ) {
            $facts[] = array( 'label' => 'Rental status', 'value' => ucwords( str_replace( '-', ' ', $request['renting_status'] ) ) );
        }
        if ( ! empty( $request['renters_timing'] ) ) {
            $facts[] = array( 'label' => 'Timing', 'value' => ucwords( str_replace( '-', ' ', $request['renters_timing'] ) ) );
        }
    } elseif ( 'health' === $coverage ) {
        if ( ! empty( $request['health_type'] ) ) {
            $facts[] = array( 'label' => 'Coverage need', 'value' => ucwords( str_replace( '-', ' ', $request['health_type'] ) ) );
        }
        if ( ! empty( $request['household_size'] ) ) {
            $facts[] = array( 'label' => 'People needing coverage', 'value' => (string) $request['household_size'] );
        }
        if ( ! empty( $request['health_timing'] ) ) {
            $facts[] = array( 'label' => 'Timing', 'value' => ucwords( str_replace( '-', ' ', $request['health_timing'] ) ) );
        }
    }

    return array_slice( $facts, 0, 6 );
}

/** Add non-auto awaiting requests to the shared dashboard request count. */
function ensurance_non_auto_dashboard_request_count( $count, $user_id ) {
    foreach ( ensurance_non_auto_dashboard_requests( $user_id ) as $request ) {
        if ( 'available' === $request['status'] ) {
            $count++;
        }
    }
    return $count;
}
add_filter( 'ensurance_dashboard_request_count', 'ensurance_non_auto_dashboard_request_count', 10, 2 );

/** Feed the newest available non-auto request into Today's existing priority card. */
function ensurance_non_auto_dashboard_live_request( $current, $user_id ) {
    if ( ! empty( $current ) ) {
        return $current;
    }

    $requests  = ensurance_non_auto_dashboard_requests( $user_id );
    $requested = ! empty( $_GET['request_id'] ) ? sanitize_text_field( wp_unslash( $_GET['request_id'] ) ) : '';

    // A notification link may target one exact offered request. Put it first only
    // when it is still available to this signed-in professional; otherwise fall
    // through to the newest available request without revealing whether another
    // ID exists.
    if ( '' !== $requested ) {
        usort(
            $requests,
            static function ( $a, $b ) use ( $requested ) {
                $a_match = ( isset( $a['request_id'] ) && $requested === $a['request_id'] ) ? 1 : 0;
                $b_match = ( isset( $b['request_id'] ) && $requested === $b['request_id'] ) ? 1 : 0;
                return $b_match <=> $a_match;
            }
        );
    }

    foreach ( $requests as $request ) {
        if ( 'available' !== $request['status'] ) {
            continue;
        }

        $label    = ensurance_non_auto_coverage_label( $request['coverage_type'] );
        $location = ensurance_non_auto_request_location( $request );

        ensurance_non_auto_mark_request_viewed( $request['request_id'], $user_id );

        return array(
            'coverage'   => $label,
            'county'     => $location ? $location : $request['state'],
            'request_id' => $request['request_id'],
            'expires_at' => 0,
            'matched_at' => $request['created_at'],
            'detail'     => $location,
            'facts'      => ensurance_non_auto_request_facts( $request ),
        );
    }

    return $current;
}
add_filter( 'ensurance_dashboard_live_request', 'ensurance_non_auto_dashboard_live_request', 10, 2 );

/** Add accepted non-auto purchases to the existing History list. */
function ensurance_non_auto_dashboard_history_rows( $rows, $user_id ) {
    foreach ( ensurance_non_auto_dashboard_requests( $user_id ) as $request ) {
        if ( 'accepted' !== $request['status'] ) {
            continue;
        }

        $name     = trim( preg_replace( '/\s+/', ' ', $request['first_name'] . ' ' . $request['last_name'] ) );
        $coverage = ensurance_non_auto_coverage_label( $request['coverage_type'] );
        $location = ensurance_non_auto_request_location( $request );

        $rows[] = array(
            'key'    => 'non-auto-' . sanitize_key( $request['request_id'] ),
            'title'  => $name ? $name : $coverage . ' Request',
            'detail' => implode( ' · ', array_filter( array( $coverage, $location ), 'strlen' ) ),
            'at'     => $request['purchased_at'],
            'status' => 'accepted',
        );
    }

    return $rows;
}
add_filter( 'ensurance_dashboard_request_rows', 'ensurance_non_auto_dashboard_history_rows', 20, 2 );

/** Accepted non-auto records keyed by the History row key that represents them. */
function ensurance_non_auto_dashboard_accepted_records( $user_id = 0 ) {
    $records = array();

    foreach ( ensurance_non_auto_dashboard_requests( $user_id ) as $request ) {
        if ( 'accepted' !== $request['status'] || empty( $request['request_id'] ) ) {
            continue;
        }

        $records[ 'non-auto-' . sanitize_key( $request['request_id'] ) ] = $request;
    }

    return $records;
}
