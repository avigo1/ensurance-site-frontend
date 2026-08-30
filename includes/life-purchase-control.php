<?php
/**
 * Ensurance Life V2 purchase-control adapter.
 * Mirrors Auto V2 exactly for transaction mechanics while keeping Life request
 * IDs namespaced as life:<request_id>. Regular Life and Referral Life share
 * this adapter; referral attribution remains request metadata only.
 */
if ( ! defined( 'ABSPATH' ) ) { exit; }

function ensurance_life_purchase_request_id( $request_id ) {
    $request_id = sanitize_text_field( (string) $request_id );
    return '' === $request_id ? '' : 'life:' . $request_id;
}

function ensurance_life_purchase_shared_request( WP_REST_Request $request, $route ) {
    $request_id = sanitize_text_field( (string) $request->get_param( 'request_id' ) );
    if ( '' === $request_id ) {
        return new WP_Error( 'invalid_request', 'A valid Life request is required.', array( 'status' => 400 ) );
    }
    $shared = new WP_REST_Request( 'POST', $route );
    $secret = (string) $request->get_header( 'x-ensurance-claim-secret' );
    if ( '' !== $secret ) { $shared->set_header( 'x-ensurance-claim-secret', $secret ); }
    $params = $request->get_params();
    $params['request_id'] = ensurance_life_purchase_request_id( $request_id );
    $params['max_checkout_windows'] = 5;
    $params['max_access_grants'] = 2;
    $shared->set_body_params( $params );
    return $shared;
}

function ensurance_life_purchase_start( WP_REST_Request $request ) {
    if ( ! function_exists( 'ensurance_non_auto_purchase_start' ) ) return new WP_Error( 'purchase_control_unavailable', 'Purchase control is unavailable.', array( 'status' => 503 ) );
    $shared = ensurance_life_purchase_shared_request( $request, '/ensurance/v1/non-auto/checkout/start' );
    return is_wp_error( $shared ) ? $shared : ensurance_non_auto_purchase_start( $shared );
}
function ensurance_life_purchase_register_session( WP_REST_Request $request ) {
    if ( ! function_exists( 'ensurance_non_auto_purchase_register_session' ) ) return new WP_Error( 'purchase_control_unavailable', 'Purchase control is unavailable.', array( 'status' => 503 ) );
    $shared = ensurance_life_purchase_shared_request( $request, '/ensurance/v1/non-auto/checkout/register' );
    return is_wp_error( $shared ) ? $shared : ensurance_non_auto_purchase_register_session( $shared );
}
function ensurance_life_purchase_payment_start( WP_REST_Request $request ) {
    if ( ! function_exists( 'ensurance_non_auto_purchase_payment_start' ) ) return new WP_Error( 'purchase_control_unavailable', 'Purchase control is unavailable.', array( 'status' => 503 ) );
    $shared = ensurance_life_purchase_shared_request( $request, '/ensurance/v1/non-auto/checkout/payment-start' );
    return is_wp_error( $shared ) ? $shared : ensurance_non_auto_purchase_payment_start( $shared );
}
function ensurance_life_purchase_release( WP_REST_Request $request ) {
    if ( ! function_exists( 'ensurance_non_auto_purchase_release' ) ) return new WP_Error( 'purchase_control_unavailable', 'Purchase control is unavailable.', array( 'status' => 503 ) );
    $shared = ensurance_life_purchase_shared_request( $request, '/ensurance/v1/non-auto/checkout/release' );
    return is_wp_error( $shared ) ? $shared : ensurance_non_auto_purchase_release( $shared );
}
function ensurance_life_purchase_claim( WP_REST_Request $request ) {
    if ( ! function_exists( 'ensurance_non_auto_purchase_claim' ) ) return new WP_Error( 'purchase_control_unavailable', 'Purchase control is unavailable.', array( 'status' => 503 ) );
    $shared = ensurance_life_purchase_shared_request( $request, '/ensurance/v1/non-auto/purchase/claim' );
    return is_wp_error( $shared ) ? $shared : ensurance_non_auto_purchase_claim( $shared );
}
function ensurance_life_purchase_finalize( WP_REST_Request $request ) {
    if ( ! function_exists( 'ensurance_non_auto_purchase_finalize' ) ) return new WP_Error( 'purchase_control_unavailable', 'Purchase control is unavailable.', array( 'status' => 503 ) );
    $shared = ensurance_life_purchase_shared_request( $request, '/ensurance/v1/non-auto/purchase/finalize' );
    return is_wp_error( $shared ) ? $shared : ensurance_non_auto_purchase_finalize( $shared );
}
function ensurance_life_purchase_get_access_grant( $request_id, $agent_email ) {
    if ( ! function_exists( 'ensurance_non_auto_purchase_get_access_grant' ) ) return array();
    $transaction_id = ensurance_life_purchase_request_id( $request_id );
    return '' === $transaction_id ? array() : ensurance_non_auto_purchase_get_access_grant( $transaction_id, $agent_email );
}
function ensurance_life_purchase_get_active_checkout( $request_id, $agent_email ) {
    if ( ! function_exists( 'ensurance_non_auto_purchase_table_name' ) || ! function_exists( 'ensurance_non_auto_purchase_lazy_expire' ) ) return array();
    $transaction_id = ensurance_life_purchase_request_id( $request_id );
    $agent_email = sanitize_email( (string) $agent_email );
    if ( '' === $transaction_id || ! is_email( $agent_email ) ) return array();
    $now = current_time( 'mysql', true );
    ensurance_non_auto_purchase_lazy_expire( $transaction_id, $now );
    global $wpdb;
    $table = ensurance_non_auto_purchase_table_name();
    $slot_key = ensurance_non_auto_purchase_slot_key( $transaction_id, $agent_email );
    $row = $wpdb->get_row( $wpdb->prepare( "SELECT status, expires_at, stripe_session_id FROM {$table} WHERE slot_key = %s AND status IN ('checkout','payment','claiming') AND expires_at > %s LIMIT 1", $slot_key, $now ), ARRAY_A );
    return is_array( $row ) ? $row : array();
}
function ensurance_life_purchase_access_check( WP_REST_Request $request ) {
    $request_id = sanitize_text_field( (string) $request->get_param( 'request_id' ) );
    $agent_email = sanitize_email( (string) $request->get_param( 'agent_email' ) );
    if ( '' === $request_id || ! is_email( $agent_email ) ) return new WP_REST_Response( array( 'granted' => false ), 200 );
    $grant = ensurance_life_purchase_get_access_grant( $request_id, $agent_email );
    return new WP_REST_Response( array( 'granted' => ! empty( $grant ) ), 200 );
}

add_action( 'rest_api_init', static function () {
    $permission = static function ( WP_REST_Request $request ) {
        return function_exists( 'ensurance_non_auto_purchase_authorized' ) && ensurance_non_auto_purchase_authorized( $request );
    };
    register_rest_route( 'ensurance/v1', '/life/checkout/start', array( 'methods' => 'POST', 'callback' => 'ensurance_life_purchase_start', 'permission_callback' => $permission ) );
    register_rest_route( 'ensurance/v1', '/life/checkout/register', array( 'methods' => 'POST', 'callback' => 'ensurance_life_purchase_register_session', 'permission_callback' => $permission ) );
    register_rest_route( 'ensurance/v1', '/life/checkout/payment-start', array( 'methods' => 'POST', 'callback' => 'ensurance_life_purchase_payment_start', 'permission_callback' => $permission ) );
    register_rest_route( 'ensurance/v1', '/life/checkout/release', array( 'methods' => 'POST', 'callback' => 'ensurance_life_purchase_release', 'permission_callback' => $permission ) );
    register_rest_route( 'ensurance/v1', '/life/purchase/claim', array( 'methods' => 'POST', 'callback' => 'ensurance_life_purchase_claim', 'permission_callback' => $permission ) );
    register_rest_route( 'ensurance/v1', '/life/purchase/finalize', array( 'methods' => 'POST', 'callback' => 'ensurance_life_purchase_finalize', 'permission_callback' => $permission ) );
    register_rest_route( 'ensurance/v1', '/life/access/check', array( 'methods' => 'POST', 'callback' => 'ensurance_life_purchase_access_check', 'permission_callback' => $permission ) );
} );
