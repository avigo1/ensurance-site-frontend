<?php
/**
 * Agent Dashboard — a horizontal milestone timeline.
 *
 * STEP 10 of templates/agent-dashboard/build-steps.md, where it draws founding
 * access: access started → today (day N) → the 60-day mark → the first charge.
 *
 * DELIBERATELY GENERIC. Step 10 asks for a piece that is open to reiteration, so
 * this part knows nothing about founding access — it takes a list of segments and
 * draws them, and the founding-access meaning lives entirely in
 * ensurance_dashboard_founding_timeline(). Give it three segments and it draws
 * three; give it six and it draws six, each one splitting the width evenly and
 * taking its rule from its own status. Anything with ordered moments — an
 * onboarding sequence, a claim, a renewal cycle — can render through it.
 *
 * ARGS (all optional):
 *   segments  array   Segments to draw. Defaults to the founding access timeline.
 *   label     string  The mono section label above the track.
 *   id        string  Base id for the label, so two timelines on one page can
 *                     each name their own region.
 *
 * A SEGMENT is ['key' => …, 'label' => …, 'date' => …, 'note' => …, 'at' => …,
 * 'status' => 'done'|'current'|'upcoming'] — see the shape documented on
 * ensurance_dashboard_founding_timeline(). Only `label` is required; a segment
 * without one is dropped, since a rule over a blank column is not a milestone.
 *
 * ELAPSED VS AHEAD is the only distinction the design draws, and it draws it
 * twice: `done` and `current` take the accent top rule, `upcoming` the neutral
 * border, with `current` additionally coloring its label. The status is derived
 * upstream from each segment's timestamp, never authored — see the resolver.
 *
 * A RULE, NOT A CARD. The section is separated from the priority slot above it by
 * a single hairline and nothing else: these are dates, not another surface
 * competing with the one thing the slot is asking the agent to do.
 *
 * NO SEGMENTS, NO SECTION — including no label and no separating rule. An agent
 * whose access start is unknown gets no timeline rather than an empty frame, the
 * same rule every other data-backed surface on Today follows.
 *
 * Source: the founding-access block of the Today view in
 * templates/agent-dashboard/AgentDashboard.dc.html (Ensurance Design System).
 * Styling lives in assets/dashboard.css (`.dash-timeline*`).
 */

$timeline = wp_parse_args(
	isset( $args ) && is_array( $args ) ? $args : array(),
	array(
		'segments' => null,
		'label'    => 'Founding access timeline',
		'id'       => 'dash-timeline',
	)
);

// Null rather than an empty array as the default, so a caller can legitimately
// pass an empty list (nothing to show) without the part quietly refilling it.
$segments = is_array( $timeline['segments'] ) ? $timeline['segments'] : ensurance_dashboard_founding_timeline();

// Drop anything with nothing to say before deciding whether the section exists —
// otherwise a list of unlabeled entries would still draw the rule and the label.
$segments = array_values(
	array_filter(
		$segments,
		function ( $segment ) {
			return is_array( $segment ) && ! empty( $segment['label'] );
		}
	)
);

if ( empty( $segments ) ) {
	return;
}

// The status word each segment is prefixed with for screen readers. Elapsed and
// ahead are carried visually by the rule's color alone, which survives neither
// being read aloud nor being looked at without color vision.
$status_words = array(
	'done'     => 'Completed',
	'current'  => 'Current',
	'upcoming' => 'Upcoming',
);

$label_id = sanitize_html_class( $timeline['id'] ) . '-label';
?>
<?php
/*
 * A <section> named by its own visible label rather than by a duplicate
 * aria-label, so the region is announced once, with the words on the screen.
 *
 * The label is a <p> and not a heading, like every other mono kicker in the
 * dashboard (.dash-view__eyebrow, .dash-setup__eyebrow): it names a band of
 * reference material, not a level in the document, and promoting it would put a
 * heading between Today's <h1> and the <h2> inside the priority slot above it.
 */
?>
<section class="dash-timeline" aria-labelledby="<?php echo esc_attr( $label_id ); ?>">

	<p class="dash-timeline__label" id="<?php echo esc_attr( $label_id ); ?>"><?php echo esc_html( $timeline['label'] ); ?></p>

	<?php
	/*
	 * An ordered list, because the order IS the content — these are four moments
	 * in sequence, and a screen reader saying "list of 4 items" is most of what
	 * the visual left-to-right run conveys.
	 *
	 * Static text throughout. Nothing here is actionable in v1: cancelling goes
	 * through agent support (Step 14's Account view), so a link on "cancel by
	 * this date" would be a dead affordance on the one line an agent is most
	 * likely to reach for.
	 */
	?>
	<ol class="dash-timeline__track">
		<?php
		foreach ( $segments as $segment ) :
			$status = ( isset( $segment['status'] ) && isset( $status_words[ $segment['status'] ] ) ) ? $segment['status'] : 'upcoming';
			$date   = isset( $segment['date'] ) ? (string) $segment['date'] : '';
			$note   = isset( $segment['note'] ) ? (string) $segment['note'] : '';
			$at     = isset( $segment['at'] ) ? (int) $segment['at'] : 0;
			?>
			<li class="dash-timeline__seg dash-timeline__seg--<?php echo esc_attr( $status ); ?>">

				<span class="dash-timeline__seg-label">
					<span class="sr-only"><?php echo esc_html( $status_words[ $status ] ); ?> — </span><?php echo esc_html( $segment['label'] ); ?>
				</span>

				<?php
				/*
				 * The second line: the date, then whatever the segment says about
				 * it, joined by an em dash the way the design writes them
				 * ("Sep 22 — cancel by this date"). Either half can be missing —
				 * the start date has no note, today has no date — so the dash is
				 * printed only when there are two halves to separate.
				 *
				 * The date is a <time> when the segment carries the moment it was
				 * formatted from: "Sep 22" alone has no year in it, and this is
				 * the one line on Today where the exact day matters.
				 */
				if ( '' !== $date || '' !== $note ) :
					?>
					<span class="dash-timeline__seg-detail">
						<?php if ( '' !== $date ) : ?>
							<?php if ( $at ) : ?>
								<time datetime="<?php echo esc_attr( wp_date( 'Y-m-d', $at ) ); ?>"><?php echo esc_html( $date ); ?></time>
							<?php else : ?>
								<?php echo esc_html( $date ); ?>
							<?php endif; ?>
						<?php endif; ?>
						<?php
						if ( '' !== $date && '' !== $note ) {
							echo ' — ';
						}

						echo esc_html( $note );
						?>
					</span>
					<?php
				endif;
				?>

			</li>
		<?php endforeach; ?>
	</ol>

</section>
