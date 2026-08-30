<?php
/**
 * Ensurance non-auto purchase control.
 *
 * First-party transaction authority shared by non-auto and the Auto adapter.
 * Allows up to five simultaneous five-minute checkout windows while enforcing
 * a maximum of two finalized professional access grants per protected request.
 *
 * Commercial rules:
 * - one normalized professional may finalize access only once per request
 * - checkout rights expire from the ORIGINAL server-owned expires_at timestamp
 * - moving into payment or claim processing does not restart the five-minute clock
 * - a valid provisional claim may finalize after the checkout clock passes
 * - finalized purchaser #1 leaves the request available
 * - finalized purchaser #2 closes it and invalidates remaining in-flight rights
 *
 * All REST endpoints are server-to-server and require the shared Make secret
 * stored in wp_options.
 */

if ( ! defined( 'ABSPATH' ) ) {
    exit;
}

function ensurance_non_auto_purchase_table_name() {
    global $wpdb;
    return $wpdb->prefix . 'ens_non_auto_purchase_slots';
}

function ensurance_non_auto_purchase_default_max_checkout_windows() {
    return 5;
}

function ensurance_non_auto_purchase_default_max_access_grants() {
    return 2;
}

/**
 * Resolve the immutable server-side purchase policy for a request. The first
 * checkout right establishes the policy. Later claim/finalize calls read it
 * from the first-party table instead of trusting browser or orchestration data.
 */
function ensurance_non_auto_purchase_policy( $request_id ) {
    global $wpdb;
    $table = ensurance_non_auto_purchase_table_name();

    $row = $wpdb->get_row(
        $wpdb->prepare(
            "SELECT max_checkout_windows, max_access_grants FROM {$table} WHERE request_id = %s ORDER BY created_at ASC LIMIT 1",
            $request_id
        ),
        ARRAY_A
    );

    $checkout = ! empty( $row['max_checkout_windows'] ) ? absint( $row['max_checkout_windows'] ) : ensurance_non_auto_purchase_default_max_checkout_windows();
    $grants   = ! empty( $row['max_access_grants'] ) ? absint( $row['max_access_grants'] ) : ensurance_non_auto_purchase_default_max_access_grants();

    return array(
        'max_checkout_windows' => max( 1, $checkout ),
        'max_access_grants'    => max( 1, $grants ),
    );
}

function ensurance_non_auto_purchase_max_checkout_windows( $request_id = '' ) {
    if ( '' === (string) $request_id ) {
        return ensurance_non_auto_purchase_default_max_checkout_windows();
    }
    $policy = ensurance_non_auto_purchase_policy( $request_id );
    return (int) $policy['max_checkout_windows'];
}

function ensurance_non_auto_purchase_max_access_grants( $request_id = '' ) {
    if ( '' === (string) $request_id ) {
        return ensurance_non_auto_purchase_default_max_access_grants();
    }
    $policy = ensurance_non_auto_purchase_policy( $request_id );
    return (int) $policy['max_access_grants'];
}

/**
 * Safety timeout for a provisional claim if orchestration dies after claim but
 * before capture/finalize or release. This is housekeeping only. It does not
 * extend the professional's original five-minute commercial checkout right.
 */
function ensurance_non_auto_purchase_claim_timeout_seconds() {
    return 15 * MINUTE_IN_SECONDS;
}

function ensurance_non_auto_purchase_install() {
    $version = '1.1.0';
    if ( get_option( 'ensurance_non_auto_purchase_schema' ) === $version ) {
        return;
    }

    global $wpdb;
    $table   = ensurance_non_auto_purchase_table_name();
    $charset = $wpdb->get_charset_collate();

    require_once ABSPATH . 'wp-admin/includes/upgrade.php';
    dbDelta(
        "CREATE TABLE {$table} (
            slot_key varchar(255) NOT NULL,
            request_id varchar(120) NOT NULL,
            agent_email varchar(190) NOT NULL,
            status varchar(24) NOT NULL DEFAULT 'checkout',
            stripe_session_id varchar(255) NOT NULL DEFAULT '',
            payment_intent_id varchar(255) NOT NULL DEFAULT '',
            max_checkout_windows smallint unsigned NOT NULL DEFAULT 0,
            max_access_grants smallint unsigned NOT NULL DEFAULT 0,
            expires_at datetime NOT NULL,
            created_at datetime NOT NULL,
            updated_at datetime NOT NULL,
            PRIMARY KEY  (slot_key),
            KEY request_status (request_id,status),
            KEY session_id (stripe_session_id)
        ) {$charset};"
    );

    update_option( 'ensurance_non_auto_purchase_schema', $version, false );
}
add_action( 'init', 'ensurance_non_auto_purchase_install', 1 );

function ensurance_non_auto_purchase_authorized( WP_REST_Request $request ) {
    $stored = (string) get_option( 'ensurance_make_claim_secret', '' );
    $given  = (string) $request->get_header( 'x-ensurance-claim-secret' );
    return '' !== $stored && '' !== $given && hash_equals( $stored, $given );
}

function ensurance_non_auto_purchase_slot_key( $request_id, $agent_email ) {
    return hash( 'sha256', strtolower( trim( $request_id ) ) . '|' . strtolower( trim( $agent_email ) ) );
}

function ensurance_non_auto_purchase_lock_name( $request_id ) {
    return 'ens_nonauto_' . substr( hash( 'sha256', (string) $request_id ), 0, 40 );
}

function ensurance_non_auto_purchase_with_lock( $request_id, $callback ) {
    global $wpdb;
    $lock = ensurance_non_auto_purchase_lock_name( $request_id );
    $got  = (int) $wpdb->get_var( $wpdb->prepare( 'SELECT GET_LOCK(%s, 3)', $lock ) );

    if ( 1 !== $got ) {
        return new WP_Error( 'purchase_busy', 'Request availability is being updated. Please try again.', array( 'status' => 409 ) );
    }

    try {
        return call_user_func( $callback );
    } finally {
        $wpdb->get_var( $wpdb->prepare( 'SELECT RELEASE_LOCK(%s)', $lock ) );
    }
}

/**
 * Lazy commercial expiration. Checkout and payment states remain governed by
 * the original expires_at. Claiming is intentionally not expired here because
 * reaching claiming means authorization arrived while the checkout right was
 * still valid; capture/finalize may finish moments later.
 */
function ensurance_non_auto_purchase_lazy_expire( $request_id, $now = '' ) {
    global $wpdb;
    $table = ensurance_non_auto_purchase_table_name();
    $now   = $now ? $now : current_time( 'mysql', true );

    $wpdb->query(
        $wpdb->prepare(
            "UPDATE {$table}
             SET status = 'expired', updated_at = %s
             WHERE request_id = %s
               AND status IN ('checkout','payment')
               AND expires_at <= %s",
            $now,
            $request_id,
            $now
        )
    );
}

function ensurance_non_auto_purchase_won_count( $request_id ) {
    global $wpdb;
    $table = ensurance_non_auto_purchase_table_name();

    return (int) $wpdb->get_var(
        $wpdb->prepare(
            "SELECT COUNT(*) FROM {$table} WHERE request_id = %s AND status = 'won'",
            $request_id
        )
    );
}

/**
 * Count all still-valid in-flight checkout rights. A row remains part of the
 * five-window cap while it is checkout, payment, or claiming AND its original
 * five-minute expires_at has not passed.
 */
function ensurance_non_auto_purchase_active_window_count( $request_id, $now = '' ) {
    global $wpdb;
    $table = ensurance_non_auto_purchase_table_name();
    $now   = $now ? $now : current_time( 'mysql', true );

    return (int) $wpdb->get_var(
        $wpdb->prepare(
            "SELECT COUNT(*) FROM {$table}
             WHERE request_id = %s
               AND status IN ('checkout','payment','claiming')
               AND expires_at > %s",
            $request_id,
            $now
        )
    );
}

/**
 * Finalized grants plus provisional claims reserve the two access positions.
 * Payment alone does not reserve an access position; claim does.
 */
function ensurance_non_auto_purchase_reserved_access_count( $request_id, $exclude_slot_key = '' ) {
    global $wpdb;
    $table = ensurance_non_auto_purchase_table_name();

    if ( '' !== $exclude_slot_key ) {
        return (int) $wpdb->get_var(
            $wpdb->prepare(
                "SELECT COUNT(*) FROM {$table}
                 WHERE request_id = %s
                   AND slot_key <> %s
                   AND status IN ('won','claiming')",
                $request_id,
                $exclude_slot_key
            )
        );
    }

    return (int) $wpdb->get_var(
        $wpdb->prepare(
            "SELECT COUNT(*) FROM {$table}
             WHERE request_id = %s
               AND status IN ('won','claiming')",
            $request_id
        )
    );
}

function ensurance_non_auto_purchase_get_access_grant( $request_id, $agent_email ) {
    global $wpdb;

    $request_id  = sanitize_text_field( (string) $request_id );
    $agent_email = sanitize_email( (string) $agent_email );

    if ( '' === $request_id || ! is_email( $agent_email ) ) {
        return array();
    }

    $table    = ensurance_non_auto_purchase_table_name();
    $slot_key = ensurance_non_auto_purchase_slot_key( $request_id, $agent_email );
    $row      = $wpdb->get_row(
        $wpdb->prepare(
            "SELECT * FROM {$table} WHERE slot_key = %s AND status = 'won' LIMIT 1",
            $slot_key
        ),
        ARRAY_A
    );

    return is_array( $row ) ? $row : array();
}

function ensurance_non_auto_purchase_start( WP_REST_Request $request ) {
    if ( ! ensurance_non_auto_purchase_authorized( $request ) ) {
        return new WP_Error( 'forbidden', 'Forbidden.', array( 'status' => 403 ) );
    }

    $request_id  = sanitize_text_field( (string) $request->get_param( 'request_id' ) );
    $agent_email = sanitize_email( (string) $request->get_param( 'agent_email' ) );
    $requested_max_checkout = absint( $request->get_param( 'max_checkout_windows' ) );
    $requested_max_grants   = absint( $request->get_param( 'max_access_grants' ) );
    $requested_max_checkout = $requested_max_checkout > 0 ? $requested_max_checkout : ensurance_non_auto_purchase_default_max_checkout_windows();
    $requested_max_grants   = $requested_max_grants > 0 ? $requested_max_grants : ensurance_non_auto_purchase_default_max_access_grants();

    if ( '' === $request_id || ! is_email( $agent_email ) ) {
        return new WP_Error( 'invalid_request', 'A valid request and professional are required.', array( 'status' => 400 ) );
    }

    return ensurance_non_auto_purchase_with_lock(
        $request_id,
        static function () use ( $request_id, $agent_email, $requested_max_checkout, $requested_max_grants ) {
            global $wpdb;
            $table = ensurance_non_auto_purchase_table_name();
            $now   = current_time( 'mysql', true );

            ensurance_non_auto_purchase_lazy_expire( $request_id, $now );

            $policy_row = $wpdb->get_row(
                $wpdb->prepare(
                    "SELECT max_checkout_windows, max_access_grants FROM {$table} WHERE request_id = %s ORDER BY created_at ASC LIMIT 1",
                    $request_id
                ),
                ARRAY_A
            );

            $stored_checkout = $policy_row ? absint( $policy_row['max_checkout_windows'] ) : 0;
            $stored_grants   = $policy_row ? absint( $policy_row['max_access_grants'] ) : 0;

            if ( $stored_checkout > 0 && $stored_grants > 0 ) {
                $max_checkout = $stored_checkout;
                $max_grants   = $stored_grants;
                if ( $max_checkout !== $requested_max_checkout || $max_grants !== $requested_max_grants ) {
                    return new WP_Error( 'policy_conflict', 'Request access policy changed. Please refresh and try again.', array( 'status' => 409 ) );
                }
            } else {
                $max_checkout = $requested_max_checkout;
                $max_grants   = $requested_max_grants;
                if ( $policy_row ) {
                    $wpdb->query(
                        $wpdb->prepare(
                            "UPDATE {$table} SET max_checkout_windows = %d, max_access_grants = %d, updated_at = %s WHERE request_id = %s AND max_checkout_windows = 0 AND max_access_grants = 0",
                            $max_checkout,
                            $max_grants,
                            $now,
                            $request_id
                        )
                    );
                }
            }

            if ( ensurance_non_auto_purchase_won_count( $request_id ) >= $max_grants ) {
                return new WP_Error( 'request_closed', 'This request is no longer available.', array( 'status' => 409 ) );
            }

            $slot_key = ensurance_non_auto_purchase_slot_key( $request_id, $agent_email );
            $existing = $wpdb->get_row( $wpdb->prepare( "SELECT * FROM {$table} WHERE slot_key = %s LIMIT 1", $slot_key ), ARRAY_A );

            if ( $existing && 'won' === $existing['status'] ) {
                return new WP_Error( 'already_purchased', 'You already have access to this request.', array( 'status' => 409 ) );
            }

            if ( $existing && in_array( $existing['status'], array( 'payment', 'claiming' ), true ) && $existing['expires_at'] > $now ) {
                return new WP_Error( 'purchase_in_progress', 'Your purchase is already being processed.', array( 'status' => 409 ) );
            }

            if ( $existing && 'checkout' === $existing['status'] && $existing['expires_at'] > $now ) {
                return array(
                    'ok'                => true,
                    'status'            => 'checkout',
                    'expires_at'        => mysql2date( DATE_ATOM, $existing['expires_at'], false ),
                    'stripe_session_id' => sanitize_text_field( (string) $existing['stripe_session_id'] ),
                    'existing'          => true,
                );
            }

            $active = ensurance_non_auto_purchase_active_window_count( $request_id, $now );

            if ( $active >= $max_checkout ) {
                return new WP_Error(
                    'checkout_capacity',
                    'The current checkout capacity for this request has been reached. Check back shortly.',
                    array( 'status' => 409 )
                );
            }

            $expires = gmdate( 'Y-m-d H:i:s', time() + ( 5 * MINUTE_IN_SECONDS ) );
            $created = $existing ? $existing['created_at'] : $now;

            $wpdb->replace(
                $table,
                array(
                    'slot_key'          => $slot_key,
                    'request_id'        => $request_id,
                    'agent_email'       => strtolower( $agent_email ),
                    'status'            => 'checkout',
                    'stripe_session_id' => '',
                    'payment_intent_id' => '',
                    'max_checkout_windows' => $max_checkout,
                    'max_access_grants'    => $max_grants,
                    'expires_at'        => $expires,
                    'created_at'        => $created,
                    'updated_at'        => $now,
                ),
                array( '%s', '%s', '%s', '%s', '%s', '%s', '%d', '%d', '%s', '%s', '%s' )
            );

            return array(
                'ok'                => true,
                'status'            => 'checkout',
                'expires_at'        => gmdate( DATE_ATOM, strtotime( $expires . ' UTC' ) ),
                'stripe_session_id' => '',
                'existing'          => false,
                'active'            => $active + 1,
            );
        }
    );
}

function ensurance_non_auto_purchase_register_session( WP_REST_Request $request ) {
    if ( ! ensurance_non_auto_purchase_authorized( $request ) ) {
        return new WP_Error( 'forbidden', 'Forbidden.', array( 'status' => 403 ) );
    }

    $request_id        = sanitize_text_field( (string) $request->get_param( 'request_id' ) );
    $agent_email       = sanitize_email( (string) $request->get_param( 'agent_email' ) );
    $stripe_session_id = sanitize_text_field( (string) $request->get_param( 'stripe_session_id' ) );

    if ( '' === $request_id || ! is_email( $agent_email ) || '' === $stripe_session_id ) {
        return new WP_Error( 'invalid_request', 'Missing checkout session information.', array( 'status' => 400 ) );
    }

    return ensurance_non_auto_purchase_with_lock(
        $request_id,
        static function () use ( $request_id, $agent_email, $stripe_session_id ) {
            global $wpdb;
            $table    = ensurance_non_auto_purchase_table_name();
            $slot_key = ensurance_non_auto_purchase_slot_key( $request_id, $agent_email );
            $now      = current_time( 'mysql', true );
            $slot     = $wpdb->get_row( $wpdb->prepare( "SELECT * FROM {$table} WHERE slot_key = %s LIMIT 1", $slot_key ), ARRAY_A );

            if ( ! $slot || 'checkout' !== $slot['status'] || $slot['expires_at'] <= $now ) {
                return new WP_Error( 'slot_expired', 'The checkout window is no longer available.', array( 'status' => 409 ) );
            }

            if ( '' !== (string) $slot['stripe_session_id'] ) {
                if ( hash_equals( (string) $slot['stripe_session_id'], $stripe_session_id ) ) {
                    return array(
                        'ok'                => true,
                        'idempotent'        => true,
                        'stripe_session_id' => $stripe_session_id,
                        'expires_at'        => mysql2date( DATE_ATOM, $slot['expires_at'], false ),
                    );
                }

                return new WP_Error( 'session_conflict', 'A secure checkout session is already active for this checkout window.', array( 'status' => 409 ) );
            }

            $updated = $wpdb->query(
                $wpdb->prepare(
                    "UPDATE {$table}
                     SET stripe_session_id = %s, updated_at = %s
                     WHERE slot_key = %s
                       AND status = 'checkout'
                       AND stripe_session_id = ''
                       AND expires_at > %s",
                    $stripe_session_id,
                    $now,
                    $slot_key,
                    $now
                )
            );

            if ( 1 !== (int) $updated ) {
                return new WP_Error( 'session_conflict', 'A secure checkout session is already active for this checkout window.', array( 'status' => 409 ) );
            }

            return array(
                'ok'                => true,
                'idempotent'        => false,
                'stripe_session_id' => $stripe_session_id,
                'expires_at'        => mysql2date( DATE_ATOM, $slot['expires_at'], false ),
            );
        }
    );
}

/**
 * Release an in-flight right when checkout/session creation or capture fails.
 * `won` is never releasable here. Any Stripe identifiers stay on the row so the
 * housekeeping cleanup can expire/cancel them safely.
 */
function ensurance_non_auto_purchase_release( WP_REST_Request $request ) {
    if ( ! ensurance_non_auto_purchase_authorized( $request ) ) {
        return new WP_Error( 'forbidden', 'Forbidden.', array( 'status' => 403 ) );
    }

    global $wpdb;
    $table       = ensurance_non_auto_purchase_table_name();
    $request_id  = sanitize_text_field( (string) $request->get_param( 'request_id' ) );
    $agent_email = sanitize_email( (string) $request->get_param( 'agent_email' ) );

    if ( '' === $request_id || ! is_email( $agent_email ) ) {
        return new WP_Error( 'invalid_request', 'A valid request and professional are required.', array( 'status' => 400 ) );
    }

    $slot_key = ensurance_non_auto_purchase_slot_key( $request_id, $agent_email );
    $wpdb->query(
        $wpdb->prepare(
            "UPDATE {$table}
             SET status = 'released', updated_at = %s
             WHERE slot_key = %s
               AND status IN ('checkout','payment','claiming')",
            current_time( 'mysql', true ),
            $slot_key
        )
    );

    return array( 'ok' => true );
}

/**
 * Compatibility endpoint for flows that explicitly promote a valid checkout
 * into a payment-processing state. IMPORTANT: this does not reset expires_at.
 */
function ensurance_non_auto_purchase_payment_start( WP_REST_Request $request ) {
    if ( ! ensurance_non_auto_purchase_authorized( $request ) ) {
        return new WP_Error( 'forbidden', 'Forbidden.', array( 'status' => 403 ) );
    }

    $request_id  = sanitize_text_field( (string) $request->get_param( 'request_id' ) );
    $agent_email = sanitize_email( (string) $request->get_param( 'agent_email' ) );

    if ( '' === $request_id || ! is_email( $agent_email ) ) {
        return new WP_Error( 'invalid_request', 'A valid request and professional are required.', array( 'status' => 400 ) );
    }

    return ensurance_non_auto_purchase_with_lock(
        $request_id,
        static function () use ( $request_id, $agent_email ) {
            global $wpdb;
            $table = ensurance_non_auto_purchase_table_name();
            $now   = current_time( 'mysql', true );

            ensurance_non_auto_purchase_lazy_expire( $request_id, $now );

            if ( ensurance_non_auto_purchase_won_count( $request_id ) >= ensurance_non_auto_purchase_max_access_grants( $request_id ) ) {
                return new WP_Error( 'request_closed', 'This request is no longer available.', array( 'status' => 409 ) );
            }

            $slot_key = ensurance_non_auto_purchase_slot_key( $request_id, $agent_email );
            $slot     = $wpdb->get_row( $wpdb->prepare( "SELECT * FROM {$table} WHERE slot_key = %s LIMIT 1", $slot_key ), ARRAY_A );

            if ( ! $slot || ! in_array( $slot['status'], array( 'checkout', 'payment' ), true ) || $slot['expires_at'] <= $now ) {
                return new WP_Error( 'slot_expired', 'Your checkout window has expired. Review the request again if it is still available.', array( 'status' => 409 ) );
            }

            if ( 'payment' === $slot['status'] ) {
                return array(
                    'ok'         => true,
                    'status'     => 'payment',
                    'expires_at' => mysql2date( DATE_ATOM, $slot['expires_at'], false ),
                );
            }

            $updated = $wpdb->query(
                $wpdb->prepare(
                    "UPDATE {$table}
                     SET status = 'payment', updated_at = %s
                     WHERE slot_key = %s
                       AND status = 'checkout'
                       AND expires_at > %s",
                    $now,
                    $slot_key,
                    $now
                )
            );

            if ( 1 !== (int) $updated ) {
                return new WP_Error( 'purchase_busy', 'Request availability changed. Please try again.', array( 'status' => 409 ) );
            }

            return array(
                'ok'         => true,
                'status'     => 'payment',
                'expires_at' => mysql2date( DATE_ATOM, $slot['expires_at'], false ),
            );
        }
    );
}

function ensurance_non_auto_stripe_cleanup_request( $path ) {
    if ( ! defined( 'ENSURANCE_STRIPE_SECRET_KEY' ) || ! ENSURANCE_STRIPE_SECRET_KEY || '' === $path ) {
        return false;
    }

    $response = wp_remote_post(
        'https://api.stripe.com/v1/' . ltrim( $path, '/' ),
        array(
            'timeout' => 10,
            'headers' => array(
                'Authorization' => 'Bearer ' . ENSURANCE_STRIPE_SECRET_KEY,
                'Content-Type'  => 'application/x-www-form-urlencoded',
            ),
            'body'    => '',
        )
    );

    if ( is_wp_error( $response ) ) {
        error_log( 'Ensurance non-auto Stripe cleanup: ' . $response->get_error_message() );
        return false;
    }

    $code = (int) wp_remote_retrieve_response_code( $response );
    if ( $code < 200 || $code >= 300 ) {
        error_log( 'Ensurance non-auto Stripe cleanup HTTP ' . $code . ': ' . wp_remote_retrieve_body( $response ) );
        return false;
    }

    return true;
}

/**
 * Reserve one of the two access positions after Stripe has authorized payment.
 * This is provisional: claiming, not won, until capture succeeds and finalize
 * is called. The ORIGINAL checkout expires_at is preserved. Authorization must
 * arrive before that timestamp; finalize may complete after it.
 */
function ensurance_non_auto_purchase_claim( WP_REST_Request $request ) {
    if ( ! ensurance_non_auto_purchase_authorized( $request ) ) {
        return new WP_Error( 'forbidden', 'Forbidden.', array( 'status' => 403 ) );
    }

    $request_id        = sanitize_text_field( (string) $request->get_param( 'request_id' ) );
    $agent_email       = sanitize_email( (string) $request->get_param( 'agent_email' ) );
    $stripe_session_id = sanitize_text_field( (string) $request->get_param( 'stripe_session_id' ) );
    $payment_intent_id = sanitize_text_field( (string) $request->get_param( 'payment_intent_id' ) );

    if ( '' === $request_id || ! is_email( $agent_email ) || '' === $stripe_session_id || '' === $payment_intent_id ) {
        return new WP_Error( 'invalid_request', 'Missing purchase information.', array( 'status' => 400 ) );
    }

    return ensurance_non_auto_purchase_with_lock(
        $request_id,
        static function () use ( $request_id, $agent_email, $stripe_session_id, $payment_intent_id ) {
            global $wpdb;
            $table = ensurance_non_auto_purchase_table_name();
            $now   = current_time( 'mysql', true );

            ensurance_non_auto_purchase_lazy_expire( $request_id, $now );

            $slot_key = ensurance_non_auto_purchase_slot_key( $request_id, $agent_email );
            $slot     = $wpdb->get_row( $wpdb->prepare( "SELECT * FROM {$table} WHERE slot_key = %s LIMIT 1", $slot_key ), ARRAY_A );

            if ( $slot && 'won' === $slot['status'] ) {
                if ( $slot['stripe_session_id'] === $stripe_session_id && $slot['payment_intent_id'] === $payment_intent_id ) {
                    return array(
                        'ok'         => true,
                        'claimed'    => true,
                        'won'        => true,
                        'idempotent' => true,
                    );
                }

                return new WP_Error( 'already_purchased', 'You already have access to this request.', array( 'status' => 409 ) );
            }

            if ( $slot && 'claiming' === $slot['status'] && $slot['stripe_session_id'] === $stripe_session_id && $slot['payment_intent_id'] === $payment_intent_id ) {
                return array(
                    'ok'         => true,
                    'claimed'    => true,
                    'won'        => false,
                    'idempotent' => true,
                );
            }

            if ( ! $slot || ! in_array( $slot['status'], array( 'checkout', 'payment' ), true ) || $slot['expires_at'] <= $now || $slot['stripe_session_id'] !== $stripe_session_id ) {
                return new WP_Error( 'slot_expired', 'The checkout window is no longer available.', array( 'status' => 409 ) );
            }

            $other_reserved = ensurance_non_auto_purchase_reserved_access_count( $request_id, $slot_key );

            if ( $other_reserved >= ensurance_non_auto_purchase_max_access_grants( $request_id ) ) {
                $wpdb->query(
                    $wpdb->prepare(
                        "UPDATE {$table}
                         SET status = 'lost', payment_intent_id = %s, updated_at = %s
                         WHERE slot_key = %s
                           AND status IN ('checkout','payment')",
                        $payment_intent_id,
                        $now,
                        $slot_key
                    )
                );

                return new WP_Error( 'access_capacity', 'This request has reached its current access capacity.', array( 'status' => 409 ) );
            }

            $updated = $wpdb->query(
                $wpdb->prepare(
                    "UPDATE {$table}
                     SET status = 'claiming', payment_intent_id = %s, updated_at = %s
                     WHERE slot_key = %s
                       AND status IN ('checkout','payment')
                       AND expires_at > %s",
                    $payment_intent_id,
                    $now,
                    $slot_key,
                    $now
                )
            );

            if ( 1 !== (int) $updated ) {
                return new WP_Error( 'purchase_busy', 'Request availability changed. Please try again.', array( 'status' => 409 ) );
            }

            return array(
                'ok'             => true,
                'claimed'        => true,
                'won'            => false,
                'idempotent'     => false,
                'grant_position' => $other_reserved + 1,
                'expires_at'     => mysql2date( DATE_ATOM, $slot['expires_at'], false ),
            );
        }
    );
}

/**
 * Finalize an access grant only after Stripe capture succeeds.
 */
function ensurance_non_auto_purchase_finalize( WP_REST_Request $request ) {
    if ( ! ensurance_non_auto_purchase_authorized( $request ) ) {
        return new WP_Error( 'forbidden', 'Forbidden.', array( 'status' => 403 ) );
    }

    $request_id        = sanitize_text_field( (string) $request->get_param( 'request_id' ) );
    $agent_email       = sanitize_email( (string) $request->get_param( 'agent_email' ) );
    $stripe_session_id = sanitize_text_field( (string) $request->get_param( 'stripe_session_id' ) );
    $payment_intent_id = sanitize_text_field( (string) $request->get_param( 'payment_intent_id' ) );

    if ( '' === $request_id || ! is_email( $agent_email ) || '' === $stripe_session_id || '' === $payment_intent_id ) {
        return new WP_Error( 'invalid_request', 'Missing purchase finalization information.', array( 'status' => 400 ) );
    }

    return ensurance_non_auto_purchase_with_lock(
        $request_id,
        static function () use ( $request_id, $agent_email, $stripe_session_id, $payment_intent_id ) {
            global $wpdb;
            $table    = ensurance_non_auto_purchase_table_name();
            $now      = current_time( 'mysql', true );
            $slot_key = ensurance_non_auto_purchase_slot_key( $request_id, $agent_email );
            $slot     = $wpdb->get_row( $wpdb->prepare( "SELECT * FROM {$table} WHERE slot_key = %s LIMIT 1", $slot_key ), ARRAY_A );

            if ( $slot && 'won' === $slot['status'] && $slot['stripe_session_id'] === $stripe_session_id && $slot['payment_intent_id'] === $payment_intent_id ) {
                $won_count = ensurance_non_auto_purchase_won_count( $request_id );
                return array(
                    'ok'              => true,
                    'won'             => true,
                    'idempotent'      => true,
                    'grant_count'     => $won_count,
                    'closed'          => $won_count >= ensurance_non_auto_purchase_max_access_grants( $request_id ),
                    'losing_sessions' => array(),
                );
            }

            if ( ! $slot || 'claiming' !== $slot['status'] || $slot['stripe_session_id'] !== $stripe_session_id || $slot['payment_intent_id'] !== $payment_intent_id ) {
                return new WP_Error( 'claim_missing', 'The access claim is not available for finalization.', array( 'status' => 409 ) );
            }

            // Defensive guard against legacy/manual data creating more than two
            // finalized grants. Normal claim locking already prevents this.
            $other_won = (int) $wpdb->get_var(
                $wpdb->prepare(
                    "SELECT COUNT(*) FROM {$table}
                     WHERE request_id = %s
                       AND slot_key <> %s
                       AND status = 'won'",
                    $request_id,
                    $slot_key
                )
            );

            if ( $other_won >= ensurance_non_auto_purchase_max_access_grants( $request_id ) ) {
                $wpdb->query(
                    $wpdb->prepare(
                        "UPDATE {$table} SET status = 'lost', updated_at = %s WHERE slot_key = %s AND status = 'claiming'",
                        $now,
                        $slot_key
                    )
                );

                return new WP_Error( 'access_capacity', 'This request has already reached its access limit.', array( 'status' => 409 ) );
            }

            $updated = $wpdb->query(
                $wpdb->prepare(
                    "UPDATE {$table} SET status = 'won', updated_at = %s WHERE slot_key = %s AND status = 'claiming'",
                    $now,
                    $slot_key
                )
            );

            if ( 1 !== (int) $updated ) {
                return new WP_Error( 'finalize_busy', 'Access finalization changed. Please try again.', array( 'status' => 409 ) );
            }

            $won_count = ensurance_non_auto_purchase_won_count( $request_id );
            $closed    = $won_count >= ensurance_non_auto_purchase_max_access_grants( $request_id );
            $losers    = array();

            if ( $closed ) {
                $losers = $wpdb->get_col(
                    $wpdb->prepare(
                        "SELECT stripe_session_id FROM {$table}
                         WHERE request_id = %s
                           AND slot_key <> %s
                           AND status IN ('checkout','payment','claiming')
                           AND stripe_session_id <> ''",
                        $request_id,
                        $slot_key
                    )
                );

                $wpdb->query(
                    $wpdb->prepare(
                        "UPDATE {$table}
                         SET status = 'lost', updated_at = %s
                         WHERE request_id = %s
                           AND slot_key <> %s
                           AND status IN ('checkout','payment','claiming')",
                        $now,
                        $request_id,
                        $slot_key
                    )
                );
            }

            return array(
                'ok'              => true,
                'won'             => true,
                'idempotent'      => false,
                'grant_count'     => $won_count,
                'closed'          => $closed,
                'losing_sessions' => array_values( array_filter( array_map( 'sanitize_text_field', $losers ) ) ),
            );
        }
    );
}

/**
 * Housekeeping only. Commercial availability is always evaluated lazily by the
 * transaction endpoints above. This routine cleans Stripe artifacts and also
 * releases a truly stale provisional claim if orchestration never finished it.
 */
function ensurance_non_auto_purchase_cleanup_rows() {
    global $wpdb;
    $table        = ensurance_non_auto_purchase_table_name();
    $now          = current_time( 'mysql', true );
    $claim_cutoff = gmdate( 'Y-m-d H:i:s', time() - ensurance_non_auto_purchase_claim_timeout_seconds() );

    $wpdb->query(
        $wpdb->prepare(
            "UPDATE {$table}
             SET status = 'expired', updated_at = %s
             WHERE status IN ('checkout','payment')
               AND expires_at <= %s",
            $now,
            $now
        )
    );

    $wpdb->query(
        $wpdb->prepare(
            "UPDATE {$table}
             SET status = 'expired', updated_at = %s
             WHERE status = 'claiming'
               AND updated_at <= %s",
            $now,
            $claim_cutoff
        )
    );

    $rows = $wpdb->get_results(
        "SELECT slot_key, request_id, agent_email, status, stripe_session_id, payment_intent_id
         FROM {$table}
         WHERE status IN ('expired','lost','released')
           AND (stripe_session_id <> '' OR payment_intent_id <> '')",
        ARRAY_A
    );

    $cleaned = 0;
    foreach ( $rows as $row ) {
        $ok = false;
        if ( ! empty( $row['payment_intent_id'] ) ) {
            $ok = ensurance_non_auto_stripe_cleanup_request( 'payment_intents/' . rawurlencode( $row['payment_intent_id'] ) . '/cancel' );
        } elseif ( ! empty( $row['stripe_session_id'] ) ) {
            $ok = ensurance_non_auto_stripe_cleanup_request( 'checkout/sessions/' . rawurlencode( $row['stripe_session_id'] ) . '/expire' );
        }

        if ( $ok ) {
            $wpdb->query(
                $wpdb->prepare(
                    "UPDATE {$table}
                     SET status = 'cleaned', updated_at = %s
                     WHERE slot_key = %s
                       AND status IN ('expired','lost','released')",
                    current_time( 'mysql', true ),
                    $row['slot_key']
                )
            );
            $cleaned++;
        }
    }

    return array( 'checked' => count( $rows ), 'cleaned' => $cleaned );
}

function ensurance_non_auto_purchase_cleanup( WP_REST_Request $request ) {
    if ( ! ensurance_non_auto_purchase_authorized( $request ) ) {
        return new WP_Error( 'forbidden', 'Forbidden.', array( 'status' => 403 ) );
    }

    $result = ensurance_non_auto_purchase_cleanup_rows();
    return array( 'ok' => true, 'checked' => $result['checked'], 'cleaned' => $result['cleaned'] );
}

add_filter(
    'cron_schedules',
    static function ( $schedules ) {
        // Retained for compatibility with the existing scheduled hook. This is
        // housekeeping, not transaction authority.
        $schedules['ensurance_every_three_minutes'] = array(
            'interval' => 3 * MINUTE_IN_SECONDS,
            'display'  => 'Every 3 minutes',
        );
        return $schedules;
    }
);

add_action( 'init', static function () {
    if ( ! wp_next_scheduled( 'ensurance_non_auto_purchase_cleanup_event' ) ) {
        wp_schedule_event( time() + MINUTE_IN_SECONDS, 'ensurance_every_three_minutes', 'ensurance_non_auto_purchase_cleanup_event' );
    }
} );

add_action( 'ensurance_non_auto_purchase_cleanup_event', 'ensurance_non_auto_purchase_cleanup_rows' );

function ensurance_non_auto_purchase_cleanup_complete( WP_REST_Request $request ) {
    if ( ! ensurance_non_auto_purchase_authorized( $request ) ) {
        return new WP_Error( 'forbidden', 'Forbidden.', array( 'status' => 403 ) );
    }

    global $wpdb;
    $table    = ensurance_non_auto_purchase_table_name();
    $slot_key = sanitize_text_field( (string) $request->get_param( 'slot_key' ) );

    if ( '' === $slot_key ) {
        return new WP_Error( 'invalid_request', 'A checkout slot is required.', array( 'status' => 400 ) );
    }

    $wpdb->query(
        $wpdb->prepare(
            "UPDATE {$table}
             SET status = 'cleaned', updated_at = %s
             WHERE slot_key = %s
               AND status IN ('expired','lost','released')",
            current_time( 'mysql', true ),
            $slot_key
        )
    );

    return array( 'ok' => true );
}

add_action(
    'rest_api_init',
    static function () {
        $permission = static function ( WP_REST_Request $request ) {
            return ensurance_non_auto_purchase_authorized( $request );
        };

        register_rest_route( 'ensurance/v1', '/non-auto/checkout/start', array( 'methods' => 'POST', 'callback' => 'ensurance_non_auto_purchase_start', 'permission_callback' => $permission ) );
        register_rest_route( 'ensurance/v1', '/non-auto/checkout/register', array( 'methods' => 'POST', 'callback' => 'ensurance_non_auto_purchase_register_session', 'permission_callback' => $permission ) );
        register_rest_route( 'ensurance/v1', '/non-auto/checkout/payment-start', array( 'methods' => 'POST', 'callback' => 'ensurance_non_auto_purchase_payment_start', 'permission_callback' => $permission ) );
        register_rest_route( 'ensurance/v1', '/non-auto/checkout/release', array( 'methods' => 'POST', 'callback' => 'ensurance_non_auto_purchase_release', 'permission_callback' => $permission ) );
        register_rest_route( 'ensurance/v1', '/non-auto/purchase/claim', array( 'methods' => 'POST', 'callback' => 'ensurance_non_auto_purchase_claim', 'permission_callback' => $permission ) );
        register_rest_route( 'ensurance/v1', '/non-auto/purchase/finalize', array( 'methods' => 'POST', 'callback' => 'ensurance_non_auto_purchase_finalize', 'permission_callback' => $permission ) );
        register_rest_route( 'ensurance/v1', '/non-auto/checkout/cleanup', array( 'methods' => 'POST', 'callback' => 'ensurance_non_auto_purchase_cleanup', 'permission_callback' => $permission ) );
        register_rest_route( 'ensurance/v1', '/non-auto/checkout/cleanup-complete', array( 'methods' => 'POST', 'callback' => 'ensurance_non_auto_purchase_cleanup_complete', 'permission_callback' => $permission ) );
    }
);
