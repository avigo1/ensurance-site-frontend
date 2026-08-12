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
 * ensurance_dashboard_priority_state() returns. Steps 5–9 replace those states
 * one at a time with their real surfaces; the ones still waiting their turn
 * (setup, quiet, decided) render as the plain labeled box Step 4 left behind.
 *
 * STEP 5 builds the FIRST of them: `live` is now the design's dark navy request
 * card, rendered by components/dashboard-slot-live.php from the request
 * ensurance_dashboard_live_request() returns.
 *
 * STEP 6 gives that card its Accept / Pass controls, which is the first thing in
 * the product that MOVES the slot: either button posts, and the slot comes back
 * `decided` (ensurance_dashboard_decided_slot).
 *
 * STEP 7 builds what it comes back AS: `decided` is now the light accent panel
 * in components/dashboard-slot-decided.php, confirming the decision in the same
 * slot the card was in, with the single Undo that hands the slot back to `live`.
 *
 * STEP 8 takes the slot's ordinary day: `quiet` is now the light card in
 * components/dashboard-slot-quiet.php — matching is on, nothing is waiting, and
 * that is a normal condition rather than an empty state. Only `setup` is still
 * Step 4's labeled box.
 *
 * NOTHING renders when the state is somehow not one of the four. The step is
 * explicit that there is no fallback branch — no "unknown state" box, and never
 * two states at once. The two data-backed states extend that rule to their data:
 * a slot that is live with no request to show, or decided with no decision to
 * confirm, renders nothing rather than an empty frame — neither surface can
 * appear without the thing it is about.
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

// The request the live card is about — empty for every other state, and empty
// in `live` too until something in the product actually produces requests (the
// admin preview toggle aside). An empty one takes the whole slot down with it;
// see the block above the slot below.
$dash_request = ( 'live' === $dash_slot ) ? ensurance_dashboard_live_request() : array();

// …and the decision the decided panel confirms, which is what put the slot in
// that state to begin with (ensurance_dashboard_decided_slot). Same rule: no
// decision, no panel.
$dash_decision = ( 'decided' === $dash_slot ) ? ensurance_dashboard_decision() : '';
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
 * value driving the slot, so CSS hooks it as [data-slot="live"] to paint the
 * dark request card, [data-slot="decided"] for the light accent panel, and the
 * two states still on the placeholder box share the remaining selectors in
 * assets/dashboard.css. Nothing needs a class that only mirrors the state.
 *
 * Two of the states additionally need their data: `live` its request, `decided`
 * the decision it confirms. Without them there is nothing to show, so the slot
 * is skipped entirely rather than painted empty — which is why the condition
 * below tests the data and not just the state.
 */
$dash_has_slot = ( '' !== $dash_slot_label )
	&& ( 'live' !== $dash_slot || ! empty( $dash_request ) )
	&& ( 'decided' !== $dash_slot || '' !== $dash_decision );

if ( $dash_has_slot ) :
	?>
	<section class="dash-slot" data-slot="<?php echo esc_attr( $dash_slot ); ?>" aria-label="What needs your attention">
		<?php if ( 'live' === $dash_slot ) : ?>

			<?php get_template_part( 'components/dashboard-slot-live', null, $dash_request ); ?>

		<?php elseif ( 'decided' === $dash_slot ) : ?>

			<?php get_template_part( 'components/dashboard-slot-decided', null, array( 'decision' => $dash_decision ) ); ?>

		<?php elseif ( 'quiet' === $dash_slot ) : ?>

			<?php
			// No args: unlike the two above, this surface is not about a
			// request or a decision — it reads its own copy. See the part's
			// docblock.
			get_template_part( 'components/dashboard-slot-quiet' );
			?>

		<?php else : ?>

			<?php // setup — still Step 4's plain labeled box until Step 9 builds its surface. ?>
			<p class="dash-slot__label"><?php echo esc_html( $dash_slot_label ); ?></p>

		<?php endif; ?>
	</section>
	<?php
endif;
