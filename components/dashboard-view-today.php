<?php
/**
 * Agent Dashboard — the Today view's contents.
 *
 * Rendered inside Today's .dash-view container by page-dashboard.php, which
 * finds this file through the `part` key on the `today` entry of
 * ensurance_dashboard_views() in functions.php.
 *
 * STEP 3 of templates/agent-dashboard/build-steps.md — the greeting row, and
 * only the greeting row: a time-aware greeting with the agent's first name on
 * the left, the timestamp in mono uppercase on the right, baseline-aligned.
 * The step is explicit that nothing else belongs here yet — no tagline, no
 * description of the product, no explanation of how Ensurance works. An agent
 * who is signed in already knows what this is.
 *
 * Today does NOT use the shared .dash-view__eyebrow / __title / __intro header
 * the other three views get: its <h1> IS the greeting, and the design gives it
 * neither eyebrow nor intro. Hence its own .dash-today__* block in
 * assets/dashboard.css, sized off the design's Today header rather than the
 * generic view header.
 *
 * NEXT (Step 4): the one-surface-at-a-time priority slot goes directly below
 * this header, inside the same file.
 *
 * Source: the header block of the Today view in
 * templates/agent-dashboard/AgentDashboard.dc.html (Ensurance Design System).
 */

// One timestamp for both halves of the row, so the greeting's hour and the
// stamp's clock can never disagree — even across a minute or hour boundary
// mid-render.
$dash_now      = time();
$dash_greeting = ensurance_dashboard_greeting( 0, $dash_now );
$dash_stamp    = ensurance_dashboard_timestamp( $dash_now );
?>
<div class="dash-today__header">

	<h1 class="dash-today__greeting"><?php echo esc_html( $dash_greeting ); ?></h1>

	<?php
	// <time> rather than a plain <span>: the visible string is abbreviated and
	// uppercased, so the machine-readable datetime carries the full moment.
	// wp_date's 'c' emits it in the site's timezone with the offset attached,
	// which is exactly what the attribute wants.
	?>
	<time class="dash-today__stamp" datetime="<?php echo esc_attr( wp_date( 'c', $dash_now ) ); ?>"><?php echo esc_html( $dash_stamp ); ?></time>

</div>
