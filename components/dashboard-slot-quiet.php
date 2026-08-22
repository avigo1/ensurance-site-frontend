<?php
/**
 * Agent Dashboard — the `quiet` priority-slot surface: matching is on and
 * nothing is waiting.
 *
 * STEP 8 of templates/agent-dashboard/build-steps.md. The state an active agent
 * is in most days, which is exactly why it is built as a real card and not as a
 * dashed empty-state placeholder: nothing has gone wrong, nothing is missing,
 * and the agent has nothing to do. A dotted outline would frame an ordinary
 * Tuesday as a hole in the page.
 *
 * A LIGHT CARD — white on the page's tint, with the same 20px radius every slot
 * state takes. It sits between the two dark states in weight on purpose: `live`
 * and `setup` invert because something is waiting on the agent, and here nothing
 * is.
 *
 * WHAT IT RENDERS, and nothing more:
 *   - a pulsing dot and "Matching is on" — the system is running, said once;
 *   - the headline, and ONE sentence naming the counties, the coverage types and
 *     the inbox the notification goes to (see ensurance_dashboard_quiet_panel);
 *   - a ruled row of three stats — last match, matched this month, typical pace;
 *   - a closing line routing volume changes to agent support.
 *
 * NO "CHECK BACK LATER", anywhere. The panel says the agent is emailed the
 * moment a request lands, so asking them to come back and look would contradict
 * the promise the sentence just made. And NO add-a-county / add-a-coverage
 * links: the agency profile is read-only in v1, so the only honest path to more
 * volume is the one the closing line names.
 *
 * NO ARGS. Unlike the live card and the decided panel — neither of which renders
 * without the request or decision it is about — nothing here is conditional on
 * data: "nothing is waiting on you" is true whether or not the counties, the
 * stats or the inbox are known. The pieces that ARE missing degrade inside the
 * resolver instead; the one part that cannot degrade, the stat row, is dropped
 * below rather than painted empty.
 *
 * Source: the `isQuiet` branch of the Today view in
 * templates/agent-dashboard/AgentDashboard.dc.html (Ensurance Design System).
 * Styling lives in assets/dashboard.css (`.dash-quiet__*`).
 */

$panel = wp_parse_args(
	ensurance_dashboard_quiet_panel(),
	array(
		'status' => '',
		'title'  => '',
		'body'   => '',
		'stats'  => array(),
		'note'   => '',
	)
);

// The headline IS the state. A panel that cannot say nothing is waiting is not
// saying anything — same guard the decided panel applies to its outcome.
if ( '' === $panel['title'] ) {
	return;
}
?>
<?php
/*
 * THE STATUS LINE. The dot pulses because the claim is about something
 * happening right now; a static dot beside "Matching is on" is a label, a
 * pulsing one is a signal. It is decoration either way — the words carry the
 * meaning — so it is aria-hidden and the animation is dropped under
 * prefers-reduced-motion in assets/dashboard.css.
 *
 * Not a live region and not role="status": nothing here updates after load, and
 * announcing it would interrupt a screen reader to say that nothing happened.
 */
?>
<?php if ( '' !== $panel['status'] ) : ?>
	<p class="dash-quiet__status">
		<span class="dash-quiet__pulse" aria-hidden="true"></span>
		<span><?php echo esc_html( $panel['status'] ); ?></span>
	</p>
<?php endif; ?>

<?php
// An <h2> for the same reason the live card's and the decided panel's headlines
// are: it sits under Today's greeting <h1>. The color is forced in CSS — see the
// note on .dash-quiet__title in assets/dashboard.css.
?>
<h2 class="dash-quiet__title"><?php echo esc_html( $panel['title'] ); ?></h2>

<?php if ( '' !== $panel['body'] ) : ?>
	<p class="dash-quiet__body"><?php echo esc_html( $panel['body'] ); ?></p>
<?php endif; ?>

<?php
/*
 * THE STAT ROW. A description list, like the live card's fact tiles and for the
 * same reason — "Last match" / "2 days ago" is a term and its value, and a
 * screen reader announces the pair rather than six loose strings.
 *
 * Ruled top and bottom rather than boxed: these are a reading of the panel's own
 * claim, not three cards of their own, and the hairlines are what hold them to
 * the sentence above.
 *
 * Nothing renders with nothing to show — ensurance_dashboard_match_stats()
 * returns an empty array until matches are actually recorded, and an empty pair
 * of rules would read as a card that failed to load.
 */
if ( ! empty( $panel['stats'] ) ) :
	?>
	<dl class="dash-quiet__stats">
		<?php foreach ( $panel['stats'] as $stat ) : ?>
			<div class="dash-quiet__stat">
				<dt class="dash-quiet__stat-label"><?php echo esc_html( $stat['label'] ); ?></dt>
				<dd class="dash-quiet__stat-value"><?php echo esc_html( $stat['value'] ); ?></dd>
			</div>
		<?php endforeach; ?>
	</dl>
	<?php
endif;
?>

<?php if ( '' !== $panel['note'] ) : ?>
	<p class="dash-quiet__note"><?php echo esc_html( $panel['note'] ); ?></p>
<?php endif; ?>
