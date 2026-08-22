<?php
/**
 * Agent Dashboard — the sign-out control beneath the agency user card.
 *
 * NOT IN THE DESIGN. `templates/agent-dashboard/AgentDashboard.dc.html` in the
 * Ensurance Design System ends its sidebar at the identity chip — the prototype
 * has no session to end, so it never needed a way out. A real signed-in surface
 * does, so this is the site's own button component wearing the rail's colors
 * (see `.dash-signout` in assets/dashboard.css) rather than an invented shape:
 * .btn / .btn--sm from assets/home.css supply the height, pill radius, weight
 * and icon gap, so it reads as an Ensurance button and not as an eighth nav row.
 *
 * WHY AN <a> AND NOT A <button>: WordPress's logout is a URL, not a form
 * endpoint — wp_logout_url() returns wp-login.php?action=logout with a
 * `log-out` nonce, and wp-login.php verifies that nonce before ending the
 * session. So the control is a real link to the real route: no JavaScript, no
 * custom handler, and it works with JS off. It is keyboard-operable and
 * announced as a control either way; `rel="nofollow"` keeps crawlers and link
 * prefetchers from walking into it.
 *
 * The label is visible text, not a bare glyph — this is the one destructive
 * action on the rail and it should never be a guess. The aria-label adds the
 * context the two words leave out ("of your Ensurance account") and CONTAINS
 * the visible text, so voice control still matches "click Sign out"
 * (WCAG 2.5.3 Label in Name).
 *
 * AFTER SIGN-OUT the agent lands on the homepage — the same target as the
 * rail's brand link — rather than WordPress's default wp-login.php
 * ?loggedout=true, which is un-themed and dead-ends an agent who only meant to
 * step away.
 *
 * Rendered by components/dashboard-sidebar.php, inside the same
 * `.dash-rail-foot` group as components/dashboard-user-card.php.
 */

// page-dashboard.php already bounces logged-out visitors, so this is a
// belt-and-braces guard: it keeps the component honest if it is ever pulled
// into a template without that redirect, where a sign-out button would be
// offering to end a session that does not exist.
if ( ! is_user_logged_in() ) {
	return;
}
?>
<a
	class="btn btn--sm dash-signout"
	href="<?php echo esc_url( wp_logout_url( home_url( '/' ) ) ); ?>"
	rel="nofollow"
	aria-label="Sign out of your Ensurance account"
>
	<?php // Lucide `log-out`, authored to match the design system's icon set (24-box, stroke 2, round caps/joins). ?>
	<svg viewBox="0 0 24 24" width="18" height="18" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true" focusable="false"><path d="M9 21H5a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h4"/><path d="m16 17 5-5-5-5"/><path d="M21 12H9"/></svg>

	<span class="dash-signout__label">Sign out</span>
</a>
