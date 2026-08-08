<?php
/**
 * Agent Dashboard — left sidebar (dark navy rail).
 *
 * The rail, the brand logo, and every nav item. Mirrors the
 * `templates/agent-dashboard/AgentDashboard.dc.html` design in the Ensurance
 * Design System — a 264px navy-800 column, sticky at full viewport height,
 * holding the inverse (white) logo at the top.
 *
 * The nav items are NOT listed here. They come from
 * ensurance_dashboard_views() in functions.php, which page-dashboard.php also
 * loops over for the matching view containers — so a row and its view can
 * never drift apart. To add one, append an entry there; this file needs no
 * change. components/dashboard-nav-item.php still carries the markup for a
 * single row, assets/dashboard.css the styling, and the active state resolves
 * itself off ensurance_dashboard_current_view().
 *
 * The agency user card and the sign-out button below it pin to the bottom of
 * the rail together, as one `.dash-rail-foot` group — that wrapper's
 * `margin-top: auto` in assets/dashboard.css does the pinning, and its own
 * tighter gap keeps the button visually attached to the card instead of
 * floating 32px below it on the rail's gap. See
 * components/dashboard-user-card.php and components/dashboard-signout.php.
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
		<?php
		foreach ( ensurance_dashboard_views() as $dash_item ) {
			get_template_part(
				'components/dashboard-nav-item',
				null,
				array(
					'view'  => $dash_item['view'],
					'label' => $dash_item['label'],
					'href'  => $dash_item['href'],
					'icon'  => $dash_item['icon'],
				)
			);
		}
		?>
	</nav>

	<div class="dash-rail-foot">
		<?php get_template_part( 'components/dashboard-user-card' ); ?>
		<?php get_template_part( 'components/dashboard-signout' ); ?>
	</div>

</aside>
