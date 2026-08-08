<?php
/**
 * Agent Dashboard — left sidebar (dark navy rail).
 *
 * Iteration 4 of the dashboard build: the rail, the brand logo, and the first
 * TWO nav items (Dashboard, Access Status). Mirrors the
 * `templates/agent-dashboard/AgentDashboard.dc.html` design in the Ensurance
 * Design System — a 264px navy-800 column, sticky at full viewport height,
 * holding the inverse (white) logo at the top.
 *
 * The remaining nav items (Agency Profile / Eligible Requests / Subscription /
 * Account & Access Settings / Agent Support) and the agent identity chip that
 * pins to the bottom of the rail are DELIBERATELY NOT here yet — they land in
 * later iterations, as further get_template_part() calls appended to the <nav>
 * below, in the design's order. Nothing else has to change to add one:
 * components/dashboard-nav-item.php carries the markup, assets/dashboard.css
 * carries the styling, and the active state resolves itself off
 * ensurance_dashboard_current_view().
 *
 *   get_template_part( 'components/dashboard-nav-item', null, array(
 *       'view'  => 'profile',
 *       'label' => 'Agency Profile',
 *       'href'  => add_query_arg( 'view', 'profile', home_url( '/dashboard/' ) ),
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
		<?php
		/*
		 * Dashboard — the rail's first row, and the view the page falls back to.
		 * Its href is the bare /dashboard/ URL rather than ?view=dashboard:
		 * ensurance_dashboard_current_view() already defaults to `dashboard`, so
		 * the clean URL lands on this view and keeps the item lit. Icon is the
		 * design's `home` glyph (Lucide, stroke 2, round caps/joins) at the 18px
		 * the rail draws every glyph at.
		 */
		get_template_part(
			'components/dashboard-nav-item',
			null,
			array(
				'view'  => 'dashboard',
				'label' => 'Dashboard',
				'href'  => home_url( '/dashboard/' ),
				'icon'  => '<svg viewBox="0 0 24 24" width="18" height="18" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true" focusable="false"><path d="m3 9 9-7 9 7v11a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2z"/><path d="M9 22V12h6v10"/></svg>',
			)
		);

		/*
		 * Access Status — the rail's second row. Unlike Dashboard, this one
		 * carries its `?view=` slug in the URL: ensurance_dashboard_current_view()
		 * only reports `access` when the query arg is actually present, so the
		 * bare /dashboard/ URL would leave this item unlit. Icon is the design's
		 * `shield-check` glyph (Lucide, stroke 2, round caps/joins) at the same
		 * 18px the rail draws every glyph at.
		 */
		get_template_part(
			'components/dashboard-nav-item',
			null,
			array(
				'view'  => 'access',
				'label' => 'Access Status',
				'href'  => add_query_arg( 'view', 'access', home_url( '/dashboard/' ) ),
				'icon'  => '<svg viewBox="0 0 24 24" width="18" height="18" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true" focusable="false"><path d="M20 13c0 5-3.5 7.5-7.66 8.95a1 1 0 0 1-.67-.01C7.5 20.5 4 18 4 13V6a1 1 0 0 1 1-1c2 0 4.5-1.2 6.24-2.72a1.17 1.17 0 0 1 1.52 0C14.51 3.81 17 5 19 5a1 1 0 0 1 1 1z"/><path d="m9 12 2 2 4-4"/></svg>',
			)
		);
		?>
		<?php /* Remaining nav items: next iteration — see the usage note above. */ ?>
	</nav>

	<?php /* Agent identity chip (pins to the rail's bottom): next iteration. */ ?>

</aside>
