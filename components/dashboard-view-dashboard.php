<?php
/**
 * Agent Dashboard — the "Dashboard" view's contents.
 *
 * Still the placeholder holding state (a slowly spinning gear + "Your
 * Dashboard Is Coming Soon"); the design's real overview — badges, the three
 * cards, and the "Accept or Pass" panel — lands later.
 *
 * Rendered inside the view's .dash-view container by page-dashboard.php,
 * because this entry in ensurance_dashboard_views() names it as its `part`.
 * That is the escape hatch for views whose content is more than the generic
 * eyebrow / title / intro — the container, the active state and the fade are
 * still handled for us; only what goes inside is ours.
 *
 * Styling lives in assets/dashboard.css (`.dashboard-setup`).
 */
?>
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
