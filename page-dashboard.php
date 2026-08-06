<?php
/**
 * Agent Dashboard (/dashboard) — PLACEHOLDER.
 *
 * The post-signup destination for the Founding Agent onboarding funnel. A new
 * agent who completes /create-account?plan=60-day (free "Start 60 Day Access")
 * is auto-logged-in and redirected here (see ensurance_founding_plans() in
 * functions.php, whose `60-day` destination points at /dashboard/). This is the
 * FIRST of three onboarding plans — right now the page is intentionally a blank
 * holding state (a slowly spinning gear + "Setting up your dashboard…"). Plan 3
 * replaces the body below with the real subscription + lead-management dashboard;
 * agents do NOT manage their own profiles here (see the product direction note in
 * plans/agent-onboarding-1-free-agent.md).
 *
 * LAYOUT: the page is a two-column shell (.dashboard-shell) — the dark navy left
 * rail (components/dashboard-sidebar.php) plus the main content column. This is
 * iteration 1 of the real dashboard build: the rail and its homepage-linked logo
 * only, no nav items yet (those come in a later iteration), with the "coming
 * soon" placeholder still filling the content column.
 *
 * CHROME: agent side of the site. get_header('agent') renders header-agent.php
 * (logo only, no nav, no buttons) paired with the global get_footer('home') /
 * footer-home.php, the same pairing page-publish-your-agency.php uses. Both are
 * styled by assets/home.css, which this page loads (via
 * ensurance_dashboard_assets()); assets/dashboard.css layers the dashboard on
 * top. Header and footer must always be swapped together — footer-home.php closes
 * </body></html> itself. Both BARS are hidden on this page in
 * assets/dashboard.css — the sidebar carries the logo, and the design is a
 * full-height app shell with no marketing footer — but get_header('agent') and
 * get_footer('home') still have to RUN: they open and close the document and
 * fire wp_head() / wp_footer().
 *
 * ACCESS: this is a signed-in surface, so logged-out visitors are bounced to
 * /login before any chrome renders. This is the minimal guard the placeholder
 * needs; Plan 3 hardens it (e.g. capability / subscription checks).
 *
 * This template renders via the page-{slug}.php hierarchy for the /dashboard/
 * page (slug `dashboard`) — no assigned Template meta needed. That means
 * body_class() emits `page-template-default`, so the background override in
 * assets/dashboard.css hooks the .dashboard-page wrapper rather than a
 * `page-template-…` body class (same approach as pricing-plans / publish-your-
 * agency).
 *
 * SEO: title / meta description / canonical / robots stay owned by Yoast and are
 * emitted through wp_head(). This template outputs none of them.
 */

// ── Access guard ────────────────────────────────────────────────────
// Signed-in surface: bounce logged-out visitors to /login, remembering where
// they were headed so the login flow can send them back. Must run before any
// output (get_header) so the redirect headers are still sendable.
if ( ! is_user_logged_in() ) {
	$dashboard_login = add_query_arg(
		'redirect_to',
		rawurlencode( home_url( '/dashboard/' ) ),
		home_url( '/login/' )
	);
	wp_safe_redirect( $dashboard_login );
	exit;
}

get_header( 'agent' );
?>

<div class="dashboard-shell">

<?php get_template_part( 'components/dashboard-sidebar' ); ?>

<main id="main" class="dashboard-page">

	<section class="dashboard-setup" aria-live="polite">

		<span class="dashboard-setup__gear" aria-hidden="true">
			<svg width="56" height="56" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.75" stroke-linecap="round" stroke-linejoin="round" focusable="false">
				<path d="M12.22 2h-.44a2 2 0 0 0-2 2v.18a2 2 0 0 1-1 1.73l-.43.25a2 2 0 0 1-2 0l-.15-.08a2 2 0 0 0-2.73.73l-.22.38a2 2 0 0 0 .73 2.73l.15.1a2 2 0 0 1 1 1.72v.51a2 2 0 0 1-1 1.74l-.15.09a2 2 0 0 0-.73 2.73l.22.38a2 2 0 0 0 2.73.73l.15-.08a2 2 0 0 1 2 0l.43.25a2 2 0 0 1 1 1.73V20a2 2 0 0 0 2 2h.44a2 2 0 0 0 2-2v-.18a2 2 0 0 1 1-1.73l.43-.25a2 2 0 0 1 2 0l.15.08a2 2 0 0 0 2.73-.73l.22-.39a2 2 0 0 0-.73-2.73l-.15-.08a2 2 0 0 1-1-1.74v-.5a2 2 0 0 1 1-1.74l.15-.09a2 2 0 0 0 .73-2.73l-.22-.38a2 2 0 0 0-2.73-.73l-.15.08a2 2 0 0 1-2 0l-.43-.25a2 2 0 0 1-1-1.73V4a2 2 0 0 0-2-2z"/>
				<circle cx="12" cy="12" r="3"/>
			</svg>
		</span>

		<h1 class="dashboard-setup__title">Your Dashboard Is Coming Soon</h1>

		<p class="dashboard-setup__note">
			Stay Tuned, great things are afoot.
		</p>

	</section>

</main>

</div><!-- /.dashboard-shell -->

<?php get_footer( 'home' ); ?>
