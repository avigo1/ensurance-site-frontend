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
 * STEP 4 adds the priority slot directly below that header — the one-surface-at-
 * a-time container that shows the single thing needing the agent's attention.
 * It renders exactly ONE of the four states in
 * ensurance_dashboard_priority_states(), chosen by the single value
 * ensurance_dashboard_priority_state() returns. Each is a plain labeled box for
 * now; Steps 5–9 replace them one at a time with their real surfaces (live
 * request card, decided panel, quiet card, setup card).
 *
 * NOTHING renders when the state is somehow not one of the four. The step is
 * explicit that there is no fallback branch — no "unknown state" box, and never
 * two states at once.
 *
 * PREVIEWING: /dashboard/?slot=quiet (and setup / decided / live) forces a
 * state for administrators, standing in for the design's props panel. See
 * ensurance_dashboard_priority_preview().
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

// The one value the slot below is driven by, and the label that goes with it.
// The lookup is a guard, not a fallback state: the resolver only ever returns a
// key of the registry, and if that ever stops being true the slot renders
// nothing rather than inventing a fifth state.
$dash_slot       = ensurance_dashboard_priority_state();
$dash_slot_label = ensurance_dashboard_priority_states();
$dash_slot_label = isset( $dash_slot_label[ $dash_slot ] ) ? $dash_slot_label[ $dash_slot ] : '';
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

<?php
/*
 * PRIORITY SLOT. One container, one state — the design's `sc-if` chain over
 * deskState, which shows a single card and never two.
 *
 * The state slug rides on data-slot rather than a modifier class: it IS the
 * value driving the slot, so CSS hooks it as [data-slot="live"] when Steps 5–9
 * give each state its own treatment (the live and setup states go dark navy;
 * quiet and decided stay light). Nothing needs a class that only mirrors it.
 */
if ( '' !== $dash_slot_label ) :
	?>
	<section class="dash-slot" data-slot="<?php echo esc_attr( $dash_slot ); ?>" aria-label="What needs your attention">
		<p class="dash-slot__label"><?php echo esc_html( $dash_slot_label ); ?></p>
	</section>
	<?php
endif;
