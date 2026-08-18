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
 * that is a normal condition rather than an empty state.
 *
 * STEP 9 finishes the set with the state an agent meets FIRST: `setup` is now
 * the second dark card, in components/dashboard-slot-setup.php — the agent is
 * not matchable yet, one thing is blocking it, and the card asks for that one
 * thing. Step 4's labeled box is gone, and with it the last placeholder branch
 * in this file.
 *
 * STEP 10 adds the first thing on Today that is NOT the slot: the founding access
 * timeline, under it and separated by a rule. Four moments of the 60-day term,
 * drawn by the deliberately generic components/dashboard-timeline.php from the
 * segments ensurance_dashboard_founding_timeline() resolves. It is reference
 * rather than an action, it renders in every slot state, and it is the only place
 * billing dates appear on this view — see the note above the call itself.
 *
 * STEP 11 closes the view with the two reference columns —
 * components/dashboard-reference.php, "What shoppers see" and "Recent" — the
 * page's footnotes, ruled rather than boxed and read-only throughout.
 *
 * NOTHING renders when the state is somehow not one of the four. The step is
 * explicit that there is no fallback branch — no "unknown state" box, and never
 * two states at once. The data-backed states extend that rule to their data: a
 * slot that is live with no request to show, decided with no decision to
 * confirm, or setup with nothing left blocking, renders nothing rather than an
 * empty frame — no surface can appear without the thing it is about.
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

// …and the setup card's copy and checklist, resolved here rather than inside the
// part because the slot's own guard needs to know whether anything is still
// blocking (an empty title means nothing is). Same rule as the two above: no
// blocking step, no card.
$dash_setup = ( 'setup' === $dash_slot ) ? ensurance_dashboard_setup_panel() : array();
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
 * value driving the slot, so CSS hooks it as [data-slot="live"] and
 * [data-slot="setup"] to paint the two dark cards, [data-slot="decided"] for the
 * light accent panel and [data-slot="quiet"] for the light card. Nothing needs a
 * class that only mirrors the state.
 *
 * Three of the states additionally need their data: `live` its request,
 * `decided` the decision it confirms, `setup` a step that is still blocking.
 * Without them there is nothing to show, so the slot is skipped entirely rather
 * than painted empty — which is why the condition below tests the data and not
 * just the state.
 */
$dash_has_slot = ( '' !== $dash_slot_label )
	&& ( 'live' !== $dash_slot || ! empty( $dash_request ) )
	&& ( 'decided' !== $dash_slot || '' !== $dash_decision )
	&& ( 'setup' !== $dash_slot || ! empty( $dash_setup['title'] ) );

if ( $dash_has_slot ) :
	?>
	<?php
	/*
	 * data-depends-on-states marks this section as a rendering of the served
	 * states — which is what it is: the slot is `setup` until they are set and
	 * `quiet` (or `live`) once they are. assets/dashboard.js reads the marker
	 * when a state is saved on Agency Profile, so the view holding it is known
	 * to be out of date and is fetched from the server the next time the agent
	 * goes there, rather than the script trying to work out what this slot
	 * should now say. Nothing about the slot's copy leaves PHP.
	 */
	?>
	<section class="dash-slot" data-slot="<?php echo esc_attr( $dash_slot ); ?>" data-depends-on-states aria-label="What needs your attention">
		<?php if ( 'live' === $dash_slot ) : ?>

			<?php get_template_part( 'components/dashboard-slot-live', null, $dash_request ); ?>

		<?php elseif ( 'decided' === $dash_slot ) : ?>

			<?php get_template_part( 'components/dashboard-slot-decided', null, array( 'decision' => $dash_decision ) ); ?>

		<?php elseif ( 'setup' === $dash_slot ) : ?>

			<?php get_template_part( 'components/dashboard-slot-setup', null, $dash_setup ); ?>

		<?php elseif ( 'quiet' === $dash_slot ) : ?>

			<?php
			// No args: alone among the four, this surface is not about a
			// request, a decision or an outstanding step — it reads its own
			// copy. See the part's docblock.
			get_template_part( 'components/dashboard-slot-quiet' );
			?>

		<?php endif; ?>
	</section>
	<?php
endif;

/*
 * FOUNDING ACCESS TIMELINE. Step 10 — the four moments of the 60-day term, under
 * the slot and separated from it by a rule rather than boxed into a card of its
 * own. It is reference, not an action: the slot above is the one thing on Today
 * asking anything of the agent, and a second bordered surface would read as a
 * competing one.
 *
 * IT RENDERS IN EVERY SLOT STATE, and outside the slot's guard entirely — the
 * term is running whether or not a request is waiting, and it is the same four
 * dates on the day an agent is still in setup as on the day they accept
 * something. That is also why it sits outside the <section> above: nothing here
 * is about what needs the agent's attention.
 *
 * THE ONLY PLACE BILLING DATES APPEAR ON TODAY. Step 10 says so outright, and
 * Step 15 generalizes it — no date may show up twice on the same page. The
 * greeting row's stamp is today's date and clock, not a billing date; the
 * sidebar's founding-access card, which would have restated the day count, is the
 * piece Step 1 deliberately left out. Anything added later that wants to say
 * "day 18" or "Sep 23" belongs here instead.
 *
 * The part itself is generic (see its docblock) — the founding-access meaning is
 * all in ensurance_dashboard_founding_timeline(), which is also where a fifth
 * milestone would be added.
 */
get_template_part(
	'components/dashboard-timeline',
	null,
	array(
		'label'    => 'Founding access timeline',
		'segments' => ensurance_dashboard_founding_timeline(),
	)
);

/*
 * REFERENCE COLUMNS. Step 11 — "What shoppers see" and "Recent", the two
 * below-the-fold columns in components/dashboard-reference.php, and the end of
 * the Today view.
 *
 * LAST BECAUSE THEY ARE THE FOOTNOTES. Today reads top to bottom in order of
 * what it asks of the agent: the greeting, the one thing needing a decision, the
 * term running underneath it, and then this — two columns that ask nothing and
 * exist to be checked. Neither restates the rail, the slot or the timeline; see
 * the part's docblock, which is also where the read-only rule lives.
 *
 * Outside the slot's guard, like the timeline: what a shopper sees and what
 * happened last week are true in every slot state.
 */
get_template_part( 'components/dashboard-reference' );
