<?php
/**
 * Agent Dashboard — the agency user card pinned to the bottom of the left rail.
 *
 * Mirrors the identity chip at the foot of the sidebar in
 * `templates/agent-dashboard/AgentDashboard.dc.html` (Ensurance Design System):
 * an accent-green circle holding the agency's initials, the agency name, and a
 * "Founding Agent" sub-label, all sitting on a subtle translucent-white card
 * against the navy rail.
 *
 * The identity itself comes from ensurance_dashboard_agency_name() /
 * ensurance_dashboard_agency_initials() in functions.php — the same pair the
 * rest of the dashboard should use to greet the agent, so there is one place to
 * repoint when the real agency record exists.
 *
 * THE CARD ITSELF IS NOT INTERACTIVE — it is a plain <div>, not a link or a
 * menu button. Signing out is its own labeled button directly beneath it
 * (components/dashboard-signout.php); the two sit in the same
 * `.dash-rail-foot` group in components/dashboard-sidebar.php.
 *
 * The avatar is aria-hidden: it is the agency name abbreviated, and the name
 * itself is right beside it, so announcing "CI" first would only be noise.
 *
 * Rendered by components/dashboard-sidebar.php; styling lives in
 * assets/dashboard.css (`.dash-user`), on the rail tokens defined there.
 */

$dash_agency   = ensurance_dashboard_agency_name();
$dash_initials = ensurance_dashboard_agency_initials( $dash_agency );

// Signed-out visitors never reach /dashboard (page-dashboard.php redirects
// first), so an empty name means something upstream is wrong — render nothing
// rather than an anonymous chip.
if ( '' === $dash_agency ) {
	return;
}
?>
<div class="dash-user">

	<span class="dash-user__avatar" aria-hidden="true"><?php echo esc_html( $dash_initials ); ?></span>

	<span class="dash-user__meta">
		<span class="dash-user__name" title="<?php echo esc_attr( $dash_agency ); ?>"><?php echo esc_html( $dash_agency ); ?></span>
		<span class="dash-user__role">Founding Agent</span>
	</span>

</div>
