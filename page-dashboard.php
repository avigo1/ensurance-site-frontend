<?php
/**
 * Agent Dashboard (/dashboard).
 *
 * The post-signup destination for the Founding Agent onboarding funnel. A new
 * agent who completes /create-account?plan=60-day (free "Start 60 Day Access")
 * is auto-logged-in and redirected here (see ensurance_founding_plans() in
 * functions.php, whose `60-day` destination points at /dashboard/). This is the
 * FIRST of three onboarding plans; the page is being built out iteratively
 * against the AgentDashboard template in the design system. Plan 3 brings the
 * real subscription + lead-management behaviour behind it; agents do NOT manage
 * their own profiles here (see the product direction note in
 * plans/agent-onboarding-1-free-agent.md).
 *
 * LAYOUT: the page is a two-column shell (.dashboard-shell) — the dark navy left
 * rail (components/dashboard-sidebar.php) plus the main content column. Both
 * are generated from ensurance_dashboard_views() in functions.php — the single
 * ordered list of rail rows — so the content column always holds exactly one
 * .dash-view container per rail item, of which one is visible at a time. Adding
 * a row means adding one entry there and nothing else.
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
	// Carry the page's own query args through the login round-trip, so a deep
	// link survives it: /dashboard/?view=requests comes back to Requests, and
	// /dashboard/?slot=live comes back to the state a reviewer was looking at.
	// Sending everyone to the bare /dashboard/ instead made a shared link
	// silently land on Today, which reads as the link having been ignored.
	//
	// Rebuilt from the two args this page understands rather than echoed from
	// REQUEST_URI: sanitize_key() on each means nothing from the request can
	// reach the URL unfiltered, and an arg the page does not use cannot ride
	// along. page-login.php validates the whole thing again with
	// wp_validate_redirect() before it becomes the post-login destination.
	$dashboard_args = array();

	foreach ( array( 'view', 'slot' ) as $dashboard_arg ) {
		if ( ! empty( $_GET[ $dashboard_arg ] ) && is_string( $_GET[ $dashboard_arg ] ) ) {
			$dashboard_args[ $dashboard_arg ] = sanitize_key( wp_unslash( $_GET[ $dashboard_arg ] ) );
		}
	}

	$dashboard_target = home_url( '/dashboard/' );

	if ( ! empty( $dashboard_args ) ) {
		$dashboard_target = add_query_arg( $dashboard_args, $dashboard_target );
	}

	$dashboard_login = add_query_arg(
		'redirect_to',
		rawurlencode( $dashboard_target ),
		home_url( '/login/' )
	);
	wp_safe_redirect( $dashboard_login );
	exit;
}

get_header( 'agent' );
?>

<?php
// The slug assets/dashboard.js falls back to when the URL names a view that
// has no container. Published as an attribute rather than hardcoded in the
// script so PHP stays the single source of truth for "where an agent lands"
// — see ensurance_dashboard_default_view().
?>
<div class="dashboard-shell" data-default-view="<?php echo esc_attr( ensurance_dashboard_default_view() ); ?>">

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
 * (/dashboard/?view=requests), a refresh, or a JS-less browser all paint the
 * right view immediately with no flash of the wrong one. dashboard.js takes
 * over from there; without it the rail's links simply navigate as before.
 *
 * ADDING A VIEW: append ONE entry to ensurance_dashboard_views() in
 * functions.php. The container below and the rail item in
 * components/dashboard-sidebar.php are both generated from that array, so a
 * row can no longer ship without its view — which used to mean a silent full
 * page load instead of the in-place fade. Everything (nav highlighting, the
 * fade, history, deep links) keys off the entry's `view` slug.
 *
 * A view whose content is more than eyebrow / title / intro names a `part`
 * in that array, which renders after the header — Today
 * (components/dashboard-view-today.php, where Phase 2 of
 * templates/agent-dashboard/build-steps.md landed), Requests
 * (components/dashboard-view-requests.php, Step 12) and Agency Profile
 * (components/dashboard-view-profile.php, Step 13). Account still carries no
 * part and no title, so it renders an EMPTY container until Step 14 fills it
 * in.
 *
 * tabindex="-1" lets dashboard.js move focus here after a click without
 * putting the container in the tab order.
 */
$dashboard_items = ensurance_dashboard_views();
$dashboard_views = wp_list_pluck( $dashboard_items, 'view' );
$dashboard_view  = ensurance_dashboard_current_view();

// ensurance_dashboard_current_view() passes unknown slugs straight through
// (it sanitizes, it does not validate). Harmless when nothing consumed the
// value, but an unrecognized `?view=` would match no container and leave the
// column blank — so fall back to the default view here. Both the list and the
// fallback come from the registry, so neither can fall out of step with the
// containers. assets/dashboard.js applies the same fallback client-side.
if ( ! in_array( $dashboard_view, $dashboard_views, true ) ) {
	$dashboard_view = ensurance_dashboard_default_view();
}

foreach ( $dashboard_items as $dash_item ) :

	$dash_classes = array( 'dash-view' );

	if ( '' !== $dash_item['modifier'] ) {
		$dash_classes[] = $dash_item['modifier'];
	}

	if ( $dash_item['view'] === $dashboard_view ) {
		$dash_classes[] = 'is-active';
	}

	// A `part` that has not been written yet simply does not render, so a view
	// can be listed before its real markup exists — it falls back to whatever
	// header its registry entry sets.
	$dash_has_part = ( '' !== $dash_item['part'] && locate_template( $dash_item['part'] . '.php' ) );

	// …and a view with neither a part nor a title renders an EMPTY container.
	// That is the state Step 1 of templates/agent-dashboard/build-steps.md
	// asks for — the rail is rebuilt, the main column stays empty — and it is
	// what keeps the containers (and therefore the in-place view switching)
	// in place while the four views are built out one step at a time.
	$dash_has_header = ( '' !== $dash_item['title'] || '' !== $dash_item['intro'] || '' !== $dash_item['eyebrow'] );

	// A view may be titled independently of its rail row; the title is the
	// better label, but a part-rendered or not-yet-built view may not set one.
	$dash_label = ( '' !== $dash_item['title'] ) ? $dash_item['title'] : $dash_item['label'];
	?>

	<section
		class="<?php echo esc_attr( implode( ' ', $dash_classes ) ); ?>"
		data-view="<?php echo esc_attr( $dash_item['view'] ); ?>"
		tabindex="-1"
		aria-label="<?php echo esc_attr( $dash_label ); ?>"
	>
		<?php
		/*
		 * HEADER THEN PART, and a view may have either or both. The header is
		 * the same object on Requests, Agency Profile and Account — a title
		 * over one line of scope — so it is described once in the registry and
		 * rendered here, and each of those views' parts carries only the
		 * content below it. Today is the view with a part and no header: its
		 * <h1> is the greeting, which the design gives no eyebrow or intro, so
		 * nothing here prints and the part renders alone.
		 */
		?>
		<?php if ( $dash_has_header ) : ?>

			<?php if ( '' !== $dash_item['eyebrow'] ) : ?>
				<div class="dash-view__eyebrow"><?php echo esc_html( $dash_item['eyebrow'] ); ?></div>
			<?php endif; ?>

			<?php if ( '' !== $dash_item['title'] ) : ?>
				<h1 class="dash-view__title"><?php echo esc_html( $dash_item['title'] ); ?></h1>
			<?php endif; ?>

			<?php if ( '' !== $dash_item['intro'] ) : ?>
				<p class="dash-view__intro"><?php echo esc_html( $dash_item['intro'] ); ?></p>
			<?php endif; ?>

		<?php endif; ?>

		<?php if ( $dash_has_part ) : ?>

			<?php get_template_part( $dash_item['part'] ); ?>

		<?php endif; ?>
	</section>

	<?php
endforeach;
?>
</main>

</div><!-- /.dashboard-shell -->

<?php get_footer( 'home' ); ?>
