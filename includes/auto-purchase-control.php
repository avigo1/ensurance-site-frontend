<?php
/**
 * Ensurance Auto purchase-control adapter.
 *
 * Auto reuses the proven first-party non-auto purchase-control engine while
 * namespacing Auto request IDs as auto:<lead_id>. That gives Auto the same
 * atomic request lock, five active checkout windows, five-minute server-owned
 * expiry, provisional claim, two finalized access grants, duplicate-purchaser
 * protection, and cleanup behavior without creating a second transaction table.
 */

if ( ! defined( 'ABSPATH' ) ) {
    exit;
}

/**
 * Convert the public Auto lead ID into the internal transaction request ID.
 */
function ensurance_auto_purchase_request_id( $lead_id ) {
    $lead_id = sanitize_text_field( (string) $lead_id );
    return '' === $lead_id ? '' : 'auto:' . $lead_id;
}

/**
 * Create a copy of an Auto REST request for the shared purchase engine.
 */
function ensurance_auto_purchase_shared_request( WP_REST_Request $request, $route ) {
    $lead_id = sanitize_text_field( (string) $request->get_param( 'lead_id' ) );
    if ( '' === $lead_id ) {
        $lead_id = sanitize_text_field( (string) $request->get_param( 'request_id' ) );
    }

    if ( '' === $lead_id ) {
        return new WP_Error( 'invalid_request', 'A valid Auto request is required.', array( 'status' => 400 ) );
    }

    $shared = new WP_REST_Request( 'POST', $route );

    $secret = (string) $request->get_header( 'x-ensurance-claim-secret' );
    if ( '' !== $secret ) {
        $shared->set_header( 'x-ensurance-claim-secret', $secret );
    }

    $params                         = $request->get_params();
    $params['request_id']           = ensurance_auto_purchase_request_id( $lead_id );
    $params['max_checkout_windows'] = 5;
    $params['max_access_grants']    = 2;
    unset( $params['lead_id'] );
    $shared->set_body_params( $params );

    return $shared;
}

function ensurance_auto_purchase_start( WP_REST_Request $request ) {
    if ( ! function_exists( 'ensurance_non_auto_purchase_start' ) ) {
        return new WP_Error( 'purchase_control_unavailable', 'Purchase control is unavailable.', array( 'status' => 503 ) );
    }
    $shared = ensurance_auto_purchase_shared_request( $request, '/ensurance/v1/non-auto/checkout/start' );
    return is_wp_error( $shared ) ? $shared : ensurance_non_auto_purchase_start( $shared );
}

function ensurance_auto_purchase_register_session( WP_REST_Request $request ) {
    if ( ! function_exists( 'ensurance_non_auto_purchase_register_session' ) ) {
        return new WP_Error( 'purchase_control_unavailable', 'Purchase control is unavailable.', array( 'status' => 503 ) );
    }
    $shared = ensurance_auto_purchase_shared_request( $request, '/ensurance/v1/non-auto/checkout/register' );
    return is_wp_error( $shared ) ? $shared : ensurance_non_auto_purchase_register_session( $shared );
}

function ensurance_auto_purchase_payment_start( WP_REST_Request $request ) {
    if ( ! function_exists( 'ensurance_non_auto_purchase_payment_start' ) ) {
        return new WP_Error( 'purchase_control_unavailable', 'Purchase control is unavailable.', array( 'status' => 503 ) );
    }
    $shared = ensurance_auto_purchase_shared_request( $request, '/ensurance/v1/non-auto/checkout/payment-start' );
    return is_wp_error( $shared ) ? $shared : ensurance_non_auto_purchase_payment_start( $shared );
}

function ensurance_auto_purchase_release( WP_REST_Request $request ) {
    if ( ! function_exists( 'ensurance_non_auto_purchase_release' ) ) {
        return new WP_Error( 'purchase_control_unavailable', 'Purchase control is unavailable.', array( 'status' => 503 ) );
    }
    $shared = ensurance_auto_purchase_shared_request( $request, '/ensurance/v1/non-auto/checkout/release' );
    return is_wp_error( $shared ) ? $shared : ensurance_non_auto_purchase_release( $shared );
}

function ensurance_auto_purchase_claim( WP_REST_Request $request ) {
    if ( ! function_exists( 'ensurance_non_auto_purchase_claim' ) ) {
        return new WP_Error( 'purchase_control_unavailable', 'Purchase control is unavailable.', array( 'status' => 503 ) );
    }
    $shared = ensurance_auto_purchase_shared_request( $request, '/ensurance/v1/non-auto/purchase/claim' );
    return is_wp_error( $shared ) ? $shared : ensurance_non_auto_purchase_claim( $shared );
}

function ensurance_auto_purchase_finalize( WP_REST_Request $request ) {
    if ( ! function_exists( 'ensurance_non_auto_purchase_finalize' ) ) {
        return new WP_Error( 'purchase_control_unavailable', 'Purchase control is unavailable.', array( 'status' => 503 ) );
    }
    $shared = ensurance_auto_purchase_shared_request( $request, '/ensurance/v1/non-auto/purchase/finalize' );
    return is_wp_error( $shared ) ? $shared : ensurance_non_auto_purchase_finalize( $shared );
}

/**
 * Finalized PII-access grant for one signed-in Auto professional.
 */
function ensurance_auto_purchase_get_access_grant( $lead_id, $agent_email ) {
    if ( ! function_exists( 'ensurance_non_auto_purchase_get_access_grant' ) ) {
        return array();
    }

    $request_id = ensurance_auto_purchase_request_id( $lead_id );
    if ( '' === $request_id ) {
        return array();
    }

    return ensurance_non_auto_purchase_get_access_grant( $request_id, $agent_email );
}

/**
 * Return this professional's currently valid Auto checkout window, if any.
 * Used only for dashboard countdown UX; the shared purchase engine remains the
 * authority for whether the window is valid.
 */
function ensurance_auto_purchase_get_active_checkout( $lead_id, $agent_email ) {
    if ( ! function_exists( 'ensurance_non_auto_purchase_table_name' ) || ! function_exists( 'ensurance_non_auto_purchase_lazy_expire' ) ) {
        return array();
    }

    $request_id  = ensurance_auto_purchase_request_id( $lead_id );
    $agent_email = sanitize_email( (string) $agent_email );
    if ( '' === $request_id || ! is_email( $agent_email ) ) {
        return array();
    }

    $now = current_time( 'mysql', true );
    ensurance_non_auto_purchase_lazy_expire( $request_id, $now );

    global $wpdb;
    $table    = ensurance_non_auto_purchase_table_name();
    $slot_key = ensurance_non_auto_purchase_slot_key( $request_id, $agent_email );
    $row      = $wpdb->get_row(
        $wpdb->prepare(
            "SELECT status, expires_at, stripe_session_id FROM {$table} WHERE slot_key = %s AND status IN ('checkout','payment','claiming') AND expires_at > %s LIMIT 1",
            $slot_key,
            $now
        ),
        ARRAY_A
    );

    return is_array( $row ) ? $row : array();
}

/**
 * Internal authorization check for Auto shopper PII.
 *
 * This endpoint is intentionally protected by the same first-party secret as
 * the purchase-control endpoints. It never returns shopper data. It answers
 * only whether the supplied professional owns a finalized access grant for the
 * supplied Auto request.
 */
function ensurance_auto_purchase_access_check( WP_REST_Request $request ) {
    $lead_id     = sanitize_text_field( (string) $request->get_param( 'lead_id' ) );
    $agent_email = sanitize_email( (string) $request->get_param( 'agent_email' ) );

    if ( '' === $lead_id || ! is_email( $agent_email ) ) {
        return new WP_REST_Response( array( 'granted' => false ), 200 );
    }

    $grant = ensurance_auto_purchase_get_access_grant( $lead_id, $agent_email );

    return new WP_REST_Response(
        array(
            'granted' => ! empty( $grant ),
        ),
        200
    );
}

/**
 * Register Auto REST aliases. Make can use raw lead_id values while the adapter
 * keeps the shared transaction table namespaced internally.
 */
add_action(
    'rest_api_init',
    static function () {
        $permission = static function ( WP_REST_Request $request ) {
            return function_exists( 'ensurance_non_auto_purchase_authorized' )
                && ensurance_non_auto_purchase_authorized( $request );
        };

        register_rest_route( 'ensurance/v1', '/auto/checkout/start', array( 'methods' => 'POST', 'callback' => 'ensurance_auto_purchase_start', 'permission_callback' => $permission ) );
        register_rest_route( 'ensurance/v1', '/auto/checkout/register', array( 'methods' => 'POST', 'callback' => 'ensurance_auto_purchase_register_session', 'permission_callback' => $permission ) );
        register_rest_route( 'ensurance/v1', '/auto/checkout/payment-start', array( 'methods' => 'POST', 'callback' => 'ensurance_auto_purchase_payment_start', 'permission_callback' => $permission ) );
        register_rest_route( 'ensurance/v1', '/auto/checkout/release', array( 'methods' => 'POST', 'callback' => 'ensurance_auto_purchase_release', 'permission_callback' => $permission ) );
        register_rest_route( 'ensurance/v1', '/auto/purchase/claim', array( 'methods' => 'POST', 'callback' => 'ensurance_auto_purchase_claim', 'permission_callback' => $permission ) );
        register_rest_route( 'ensurance/v1', '/auto/purchase/finalize', array( 'methods' => 'POST', 'callback' => 'ensurance_auto_purchase_finalize', 'permission_callback' => $permission ) );
        register_rest_route( 'ensurance/v1', '/auto/access/check', array( 'methods' => 'POST', 'callback' => 'ensurance_auto_purchase_access_check', 'permission_callback' => $permission ) );
    }
);
