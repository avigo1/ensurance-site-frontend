<?php
/**
 * Agent Dashboard — left sidebar (dark navy rail).
 *
 * Iteration 2 of the dashboard build: the rail, the brand logo, and the nav
 * STACK — the reusable item pattern only, with no items in it yet. Mirrors the
 * `templates/agent-dashboard/AgentDashboard.dc.html` design in the Ensurance
 * Design System — a 264px navy-800 column, sticky at full viewport height,
 * holding the inverse (white) logo at the top.
 *
 * The nav items themselves (Dashboard / Access Status / Agency Profile /
 * Eligible Requests / Subscription / Account & Access Settings / Agent Support)
 * and the agent identity chip that pins to the bottom of the rail are
 * DELIBERATELY NOT here yet — they land in a later iteration, as
 * get_template_part() calls dropped into the empty <nav> below. Nothing else
 * has to change to add one: components/dashboard-nav-item.php carries the
 * markup, assets/dashboard.css carries the styling, and the active state
 * resolves itself off ensurance_dashboard_current_view().
 *
 *   get_template_part( 'components/dashboard-nav-item', null, array(
 *       'view'  => 'access',
 *       'label' => 'Access Status',
 *       'href'  => add_query_arg( 'view', 'access', home_url( '/dashboard/' ) ),
 *       'icon'  => '<svg …></svg>',
 *   ) );
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

	<nav class="dash-nav" aria-label="Dashboard sections">
		<?php /* Nav items: next iteration — see the usage note above. */ ?>
	</nav>

	<?php /* Agent identity chip (pins to the rail's bottom): next iteration. */ ?>

</aside>
