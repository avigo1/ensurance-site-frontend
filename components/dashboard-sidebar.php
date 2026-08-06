<?php
/**
 * Agent Dashboard — left sidebar (dark navy rail).
 *
 * Iteration 1 of the dashboard build: the rail itself plus the brand logo.
 * Mirrors the `templates/agent-dashboard/AgentDashboard.dc.html` design in the
 * Ensurance Design System — a 264px navy-800 column, sticky at full viewport
 * height, holding the inverse (white) logo at the top.
 *
 * The nav items (Dashboard / Access Status / Agency Profile / Eligible Requests
 * / Subscription / Account & Access Settings / Agent Support) and the agent
 * identity chip that pins to the bottom of the rail are DELIBERATELY NOT here
 * yet — they land in a later iteration. Keep the `.dash-sidebar` element and its
 * flex column so those slot in below the brand without reshaping the rail.
 *
 * The logo is a link back to the homepage (same target as the site header's
 * brand link). Because this rail carries the logo, page-dashboard.php hides the
 * header-agent.php bar on this page — see assets/dashboard.css.
 *
 * Styling lives in assets/dashboard.css; tokens come from assets/home.css.
 */
?>
<aside class="dash-sidebar" aria-label="Dashboard">

	<a class="dash-sidebar__brand" href="<?php echo esc_url( home_url( '/' ) ); ?>" aria-label="Ensurance.com homepage">
		<img
			src="<?php echo esc_url( get_stylesheet_directory_uri() . '/assets/images/logo-white.png' ); ?>"
			alt="Ensurance.com"
			class="dash-sidebar__logo"
			width="2153"
			height="159"
		/>
	</a>

	<?php /* Nav + agent chip: next iteration. */ ?>

</aside>
