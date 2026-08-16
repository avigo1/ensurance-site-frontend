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
 *   - a "Step N of 3" eyebrow — position in a sequence, not a count of what is
 *     missing;
 *   - a headline naming the ONE blocking thing, and one sentence on why it
 *     blocks matching;
 *   - the three-item checklist, with done / current / upcoming each reading
 *     differently;
 *   - one button, to wherever the blocking step is actually resolved.
 *
 * ONE THING AT A TIME. The headline is about the current step alone. The other
 * two lines are there so the agent can see how much is left — not as work they
 * can pick up: FUTURE STEPS ARE NOT ACTIONABLE, and nothing here makes them look
 * like they are. No links, no buttons, no per-step affordance of any kind. The
 * only control on the card is the single support button at the bottom.
 *
 * WHERE THE BUTTON GOES DEPENDS ON WHAT IS BLOCKING. States are the one thing an
 * agent sets themselves, and Agency Profile is the only place they can — so when
 * states are missing the button goes there
 * (ensurance_dashboard_setup_cta_to_profile). Everything else on the agency record
 * is still read-only and still changed by a human, so any other blocking step
 * leaves the button on agent support (ensurance_dashboard_support_url), which is
 * where the card pointed for all of them before states became self-serve.
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
		'cta'     => '',
		'cta_url' => '',
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
<p class="dash-setup__eyebrow"><?php echo esc_html( $panel['eyebrow'] ); ?></p>

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
 * THE ONE BUTTON. A link, not a form: it navigates — to Agency Profile when
 * states are what is missing, to agent support otherwise — and changes nothing on
 * the way, the opposite of the live card's Accept / Pass and the decided panel's
 * Undo, all of which post because they record something.
 *
 * It is the design's primary button because it is the only thing to do on this
 * card. There is no secondary action beside it and no "skip for now": the agent
 * cannot be matched until this is handled, and offering a way past it would be
 * offering a way to a dashboard that never does anything.
 */
if ( '' !== $panel['cta'] && '' !== $panel['cta_url'] ) :
	?>
	<?php // .btn / .btn-primary from assets/home.css — the site's own button, which is what the design's Button component renders at size md. ?>
	<a class="btn btn-primary dash-setup__action" href="<?php echo esc_url( $panel['cta_url'] ); ?>"><?php echo esc_html( $panel['cta'] ); ?></a>
	<?php
endif;
