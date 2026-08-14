<?php
/**
 * /founding-checkout — Stripe Checkout launch route (no visible UI).
 *
 * The paid $29/mo "Join as a Founding Agent" funnel routes a verified, logged-in
 * agent here (it is the `monthly` plan's destination in ensurance_founding_plans(),
 * reached via ensurance_founding_plan_login_redirect at login). This template has
 * NO chrome and renders nothing: it hands straight to
 * ensurance_founding_checkout_start() (functions.php, section 2b-v-a5), which
 * creates a Stripe Checkout Session and redirects the browser to Stripe — or, for
 * anyone who shouldn't be here (logged out, wrong plan, already subscribed, or
 * missing Stripe config), redirects somewhere sane. That handler ALWAYS exits, so
 * the fallback below is only a safety net.
 *
 * Renders via the page-{slug}.php hierarchy for the /founding-checkout/ page
 * (slug `founding-checkout`) — create that WordPress page or the route 404s.
 */

if ( function_exists( 'ensurance_founding_checkout_start' ) ) {
    ensurance_founding_checkout_start(); // creates the Stripe session and exits
}

// Safety net — unreachable in normal operation (the handler always exits).
wp_safe_redirect( home_url( '/dashboard/' ) );
exit;
