<?php
/**
 * Agent Dashboard — the `setup` priority-slot surface: first run, the agent is
 * not matchable yet.
 *
 * STEP 9 of templates/agent-dashboard/build-steps.md. The last of the four slot
 * states to get its real surface, and the one an agent actually sees first: an
 * account exists, nothing else does, and no request can reach them until it
 * does. That is the action of the moment, so the card takes THE SAME WEIGHT as a
 * waiting request — dark navy, same radius, same inset. The two dark states are
 * the two things that can be waiting on an agent; nothing else on Today inverts.
 *
 * WHAT IT RENDERS, and nothing more:
 *   - a pulsing dot and a mono uppercase "Matching is off" eyebrow — the state
 *     the card is reporting, not a position in a sequence;
 *   - a headline naming the ONE blocking thing, and one sentence on why it
 *     blocks matching;
 *   - the checklist, one row per item, with done / current / upcoming each
 *     reading differently;
 *   - two tiles, to the two views worth checking before matching turns on.
 *
 * ONE THING AT A TIME. The headline is about the current step alone, and the
 * checklist rows are not work the agent can pick up: THE ROWS ARE NOT ACTIONABLE,
 * and nothing here makes them look like they are. No links, no buttons, no
 * per-step affordance of any kind. The only controls on the card are the two
 * tiles at the bottom.
 *
 * THE CHECKLIST IS ONE ROW TODAY. The gate is states alone
 * (ensurance_dashboard_states_only_checklist), so there is one condition to report
 * and the done / upcoming styles below are the states this row takes on its way
 * through rather than three rows shown at once. They are kept because the
 * checklist is driven by whatever the resolver returns, not by a fixed list — a
 * condition added later renders here with no change to this file.
 *
 * THE TILES GO WHERE THE WORK IS. States are the one thing an agent sets
 * themselves and Agency Profile is the only place they can, so the first tile
 * goes there; the second goes to Account, where the agent confirms the inbox a
 * matched request would land in. See ensurance_dashboard_setup_tiles().
 *
 * ARGS — the array ensurance_dashboard_setup_panel() returns; see its docblock
 * for the shape, and for how each step's status is derived from the resolver
 * that owns its data. Rendered by dashboard-view-today.php, which resolves the
 * panel for its own guard and hands it over rather than making this file resolve
 * it a second time. A finished checklist leaves no blocking step, so there is no
 * headline to write and the view drops the slot before reaching here; the guard
 * below is repeated anyway because a caller could always pass its own array.
 *
 * Source: the `isSetup` branch of the Today view in
 * templates/agent-dashboard/AgentDashboard.dc.html (Ensurance Design System).
 * Styling lives in assets/dashboard.css (`.dash-setup__*`).
 */

$panel = wp_parse_args(
	$args ?? array(),
	array(
		'eyebrow' => '',
		'title'   => '',
		'body'    => '',
		'steps'   => array(),
		'tiles'   => array(),
	)
);

// The headline IS the blocking thing. With nothing blocking, the card has
// nothing to ask for — same guard the live card and the decided panel apply to
// the request and the decision they are about.
if ( '' === $panel['title'] ) {
	return;
}

// The status word each line is prefixed with for screen readers. The visual
// distinction between the three is carried by color and by the marker glyph,
// neither of which survives being read aloud — and "Coverage types" on its own
// gives no hint that it is a step nobody is working on yet.
$status_words = array(
	'done'     => 'Done',
	'current'  => 'Current step',
	'upcoming' => 'Not started',
);
?>
<?php
// THE STATUS LINE, and the mirror of the quiet panel's: same mono uppercase
// kicker, same 8px dot, same slow pulse — the same claim about the same
// machinery with the answer flipped (ensurance_dashboard_setup_panel_eyebrow).
// The dot is decoration and the words beside it say the whole thing, so it is
// hidden from assistive tech; the pulse is dropped under prefers-reduced-motion.
?>
<p class="dash-setup__eyebrow">
	<span class="dash-setup__pulse" aria-hidden="true"></span>
	<span><?php echo esc_html( $panel['eyebrow'] ); ?></span>
</p>

<?php
// An <h2> for the same reason the other three states' headlines are: it sits
// under Today's greeting <h1>. The white is forced in CSS — see the note on
// .dash-setup__title in assets/dashboard.css.
?>
<h2 class="dash-setup__title"><?php echo esc_html( $panel['title'] ); ?></h2>

<?php if ( '' !== $panel['body'] ) : ?>
	<p class="dash-setup__body"><?php echo esc_html( $panel['body'] ); ?></p>
<?php endif; ?>

<?php
/*
 * THE CHECKLIST. A <ul>, because it is a list and a screen reader should say so
 * — "list of 3 items" is most of what the eyebrow is telling everyone else.
 *
 * Static text, deliberately. The design draws these as rows and it would be easy
 * to read them as three things to click; they are three things being done FOR
 * the agent, in order, by support. Marking them up as anything interactive would
 * be a dead affordance on every one of them.
 *
 * The marker is decoration: the line's own words and the hidden status prefix
 * both say where the step stands, so the glyph carries nothing of its own.
 */
if ( ! empty( $panel['steps'] ) ) :
	?>
	<ul class="dash-setup__steps">
		<?php
		foreach ( $panel['steps'] as $step ) :
			$status = isset( $status_words[ $step['status'] ] ) ? $step['status'] : 'upcoming';
			?>
			<li class="dash-setup__step dash-setup__step--<?php echo esc_attr( $status ); ?>">

				<?php if ( 'done' === $status ) : ?>
					<?php // Icon `circle-check`, copied path-for-path from components/icons/Icon.jsx in the design system (Lucide, stroke 2, round caps/joins) at the design's 18px. ?>
					<svg class="dash-setup__marker" viewBox="0 0 24 24" width="18" height="18" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true" focusable="false"><circle cx="12" cy="12" r="10"/><path d="m9 12 2 2 4-4"/></svg>
				<?php else : ?>
					<?php // An empty ring for the two states that are not done — the design's own 18px circle, drawn in CSS rather than as a glyph because it has no shape to it. ?>
					<span class="dash-setup__marker dash-setup__ring" aria-hidden="true"></span>
				<?php endif; ?>

				<?php
				// One <span> around both halves: the row is a flex container, so
				// splitting them would make the label a second flex item and put
				// the row's 11px gap inside the sentence. Same reason the live
				// card wraps its countdown.
				?>
				<span>
					<span class="sr-only"><?php echo esc_html( $status_words[ $status ] ); ?> — </span><?php echo esc_html( $step['label'] ); ?>
				</span>

			</li>
		<?php endforeach; ?>
	</ul>
	<?php
endif;

/*
 * THE TILES. Links, not a form: they navigate to views that already exist and
 * change nothing on the way — the opposite of the live card's Accept / Pass and
 * the decided panel's Undo, all of which post because they record something.
 *
 * REAL ANCHORS WITH REAL HREFS, which is the whole requirement: middle-click,
 * cmd-click and "open in new tab" all have to work, so nothing here is a <div>
 * with a click handler, and nothing redirects on its own — the agent chooses to
 * go. Landing on Agency Profile traps them in nothing: the rail still works and
 * the back button comes straight back to Today.
 *
 * There is still no "skip for now". The agent cannot be matched until states are
 * set, and offering a way past that would be offering a way to a dashboard that
 * never does anything.
 *
 * A <ul>, because it is a list of destinations and "list of 2 items" is most of
 * what the row conveys visually — the same construction the checklist above uses.
 */
if ( ! empty( $panel['tiles'] ) ) :
	?>
	<ul class="dash-setup__tiles">
		<?php
		foreach ( $panel['tiles'] as $tile ) :
			// A tile missing either half of its label, or its destination, is
			// not a tile — the same rule the live card's fact tiles follow.
			if ( empty( $tile['title'] ) || empty( $tile['url'] ) ) {
				continue;
			}
			?>
			<li class="dash-setup__tile-item">
				<a class="dash-setup__tile" href="<?php echo esc_url( $tile['url'] ); ?>">

					<span class="dash-setup__tile-title"><?php echo esc_html( $tile['title'] ); ?></span>

					<?php if ( ! empty( $tile['sub'] ) ) : ?>
						<span class="dash-setup__tile-sub"><?php echo esc_html( $tile['sub'] ); ?></span>
					<?php endif; ?>

				</a>
			</li>
		<?php endforeach; ?>
	</ul>
	<?php
endif;
