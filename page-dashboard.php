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
 * rail (components/dashboard-sidebar.php) plus the main content column. The rail
 * currently carries five nav items; the content column holds one .dash-view
 * container per item, of which exactly one is visible at a time.
 *
 * VIEW SWITCHING: the design is a stateful component — clicking a rail item
 * swaps the main region in place with a short fade, no navigation. That is
 * reproduced WITHOUT giving up URLs: every view is in the DOM, PHP lights the
 * one matching `?view=`, and assets/dashboard.js moves that highlight on click
 * while rewriting the address bar via history.pushState. Deep links, refresh,
 * the back button and "open in new tab" therefore all still work, and with JS
 * off the rail's links degrade to ordinary page loads. See the block comment
 * above the containers below for how to add one.
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

<?php
/*
 * VIEWS. One container per rail destination, ALL rendered here and hidden
 * with CSS rather than rendered conditionally. That is what lets
 * assets/dashboard.js swap them instantly on click, the way the design's
 * setState does — there is nothing to fetch, the content is already in the
 * document.
 *
 * $dashboard_view lights the container matching the URL, so a deep link
 * (/dashboard/?view=access), a refresh, or a JS-less browser all paint the
 * right view immediately with no flash of the wrong one. dashboard.js takes
 * over from there; without it the rail's links simply navigate as before.
 *
 * ADDING A VIEW: give it a container here with the same data-view slug as
 * its rail item's `?view=` arg, and it is wired — nav highlighting, the
 * fade, history and deep links all key off that one string. Rail items whose
 * container does not exist yet keep navigating normally (dashboard.js checks
 * before intercepting), so a nav item and its view can land together.
 *
 * tabindex="-1" lets dashboard.js move focus here after a click without
 * putting the container in the tab order.
 */
$dashboard_views = array( 'dashboard', 'access', 'profile', 'requests', 'subscription' );
$dashboard_view  = ensurance_dashboard_current_view();

// ensurance_dashboard_current_view() passes unknown slugs straight through
// (it sanitizes, it does not validate). Harmless when nothing consumed the
// value, but now an unrecognized `?view=` would match no container and leave
// the column blank — so fall back to the default view here. Keep this list in
// step with the containers below. assets/dashboard.js applies the same
// fallback client-side.
if ( ! in_array( $dashboard_view, $dashboard_views, true ) ) {
	$dashboard_view = 'dashboard';
}

/**
 * Class list for a view container — `.dash-view`, plus `is-active` when it
 * is the current one.
 *
 * @param string $view      This container's slug.
 * @param string $current   The slug currently showing.
 * @param string $modifier  Optional extra class.
 * @return string
 */
$dashboard_view_class = static function ( $view, $current, $modifier = '' ) {
	$classes = array( 'dash-view' );

	if ( '' !== $modifier ) {
		$classes[] = $modifier;
	}

	if ( $view === $current ) {
		$classes[] = 'is-active';
	}

	return implode( ' ', $classes );
};
?>

	<!-- Dashboard — still the placeholder holding state; the design's real
	     overview (badges + three cards + "Accept or Pass") lands later. -->
	<section
		class="<?php echo esc_attr( $dashboard_view_class( 'dashboard', $dashboard_view, 'dash-view--center' ) ); ?>"
		data-view="dashboard"
		tabindex="-1"
		aria-label="Dashboard"
	>

		<div class="dashboard-setup" aria-live="polite">

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

		</div>

	</section>

	<!-- Access Status. Header + intro copy are the design's; the status
	     rows, badge and CTA it specifies come in a later iteration. -->
	<section
		class="<?php echo esc_attr( $dashboard_view_class( 'access', $dashboard_view ) ); ?>"
		data-view="access"
		tabindex="-1"
		aria-label="Access Status"
	>
		<div class="dash-view__eyebrow">Founding Agent Access</div>
		<h1 class="dash-view__title">Access Status</h1>
		<p class="dash-view__intro">
			Your Founding Agent Access shows whether your agency can create or manage a
			profile and review eligible shopper request details when available.
		</p>
	</section>

	<!-- Agency Profile. -->
	<section
		class="<?php echo esc_attr( $dashboard_view_class( 'profile', $dashboard_view ) ); ?>"
		data-view="profile"
		tabindex="-1"
		aria-label="Agency Profile"
	>
		<div class="dash-view__eyebrow">Founding Agent Access</div>
		<h1 class="dash-view__title">Agency Profile</h1>
		<p class="dash-view__intro">
			Complete your agency profile so Ensurance can understand your service areas,
			coverage types, and contact details.
		</p>
	</section>

	<!-- Eligible Requests. The design titles this view "Eligible Request
	     Previews" while its rail row reads "Eligible Requests" — both are
	     kept as designed. -->
	<section
		class="<?php echo esc_attr( $dashboard_view_class( 'requests', $dashboard_view ) ); ?>"
		data-view="requests"
		tabindex="-1"
		aria-label="Eligible Request Previews"
	>
		<div class="dash-view__eyebrow">Founding Agent Access</div>
		<h1 class="dash-view__title">Eligible Request Previews</h1>
		<p class="dash-view__intro">
			Eligible shopper request details may appear here when available in your state
			or service area.
		</p>
	</section>

	<!-- Subscription. The design titles this view "Subscription Status" while
	     its rail row reads "Subscription" — both are kept as designed. -->
	<section
		class="<?php echo esc_attr( $dashboard_view_class( 'subscription', $dashboard_view ) ); ?>"
		data-view="subscription"
		tabindex="-1"
		aria-label="Subscription Status"
	>
		<div class="dash-view__eyebrow">Founding Agent Access</div>
		<h1 class="dash-view__title">Subscription Status</h1>
		<p class="dash-view__intro">
			Review your Founding Agent Access status, current plan, billing information,
			and access period.
		</p>
	</section>

</main>

</div><!-- /.dashboard-shell -->

<?php get_footer( 'home' ); ?>
