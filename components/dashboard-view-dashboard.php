<?php
/**
 * Agent Dashboard — the "Dashboard" view's contents.
 *
 * ITERATION 1: the view header only — eyebrow, welcome line, H1, intro. The
 * design's overview continues below this with a badge row, four stat tiles,
 * three action cards and the "Accept or Pass" panel
 * (templates/agent-dashboard/AgentDashboard.dc.html); those land in a later
 * iteration and append INSIDE this part, after the header element.
 *
 * Rendered inside the view's .dash-view container by page-dashboard.php,
 * because this entry in ensurance_dashboard_views() names it as its `part`.
 * That is the escape hatch for views whose content is more than the generic
 * eyebrow / title / intro — the container, its 40px/48px/72px inset and 1120px
 * measure, the active state and the fade are all still handled for us; only
 * what goes inside is ours.
 *
 * The header is its own block (.dash-home__*) rather than the shared
 * .dash-view__* one because the design sizes the overview larger than the other
 * six views: a 40px/900 H1 against their 32px, plus the greeting line those do
 * not have. The eyebrow is identical, so that one class is shared.
 *
 * Styling lives in assets/dashboard.css (`.dash-home`).
 */

// The design gates its greeting on a `returning` prop (its "new" agent state
// hides it). Nothing on the user record distinguishes a first visit from a
// return, and every arrival here is post-authentication, so the line shows
// whenever there is a name to use — see ensurance_dashboard_first_name(), which
// returns '' when there is not.
$dash_first_name = ensurance_dashboard_first_name();
?>
<header class="dash-home__header">

	<div class="dash-view__eyebrow">Founding Agent Access</div>

	<?php if ( '' !== $dash_first_name ) : ?>
		<p class="dash-home__welcome">Welcome back, <?php echo esc_html( $dash_first_name ); ?></p>
	<?php endif; ?>

	<h1 class="dash-home__title">Agent Dashboard</h1>

	<p class="dash-home__intro">
		Manage your agency profile, access status, and eligible shopper request
		details in one place. Keep your information current, review your access,
		and check eligible request details when available in your state or
		service area.
	</p>

</header>
