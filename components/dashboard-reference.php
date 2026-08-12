<?php
/**
 * Agent Dashboard — Today's two reference columns: "What shoppers see" and
 * "Recent".
 *
 * STEP 11 of templates/agent-dashboard/build-steps.md, and the last piece of the
 * Today view. Everything above it is about right now — the one thing needing a
 * decision, and the term running underneath it. This band is the opposite: it is
 * the page's footnotes, below the fold, answering the two questions an agent
 * checks rather than acts on. What is being shown on my behalf, and what has
 * happened lately.
 *
 * NO CARD, ON PURPOSE. Both columns are a mono uppercase label over hairline-
 * ruled rows and nothing more — no border, no fill, no radius. Every bordered
 * surface on Today is something asking for the agent's attention (the two dark
 * slot states, the light quiet card, the decided panel); wrapping reference
 * material in the same frame would put it in the same conversation. The rules
 * hold the rows together and the label names them, which is all this needs.
 *
 * BOTH COLUMNS ARE READ-ONLY. The design puts an Edit link on each shopper-facing
 * row; Step 11 removes them, and the scope note at the top of build-steps.md is
 * why — agency data is not editable in v1 and every "change this" path ends at
 * agent support, so an Edit link here would be a dead affordance on the one
 * surface an agent is most likely to reach for. Nothing in this file is clickable.
 *
 * NEITHER COLUMN REPEATS THE RAIL. Step 11 says not to, and Step 15 generalizes
 * it to the whole page: no status, count or date twice. The rail carries identity
 * and the awaiting-request badge; the slot carries what is waiting, and the date
 * it arrived; the timeline carries the term's dates. So this band states none of
 * them — the displayed name is here as OUTWARD-FACING data (what a shopper
 * reads), which is a different claim from the rail's identity chip, and the
 * activity rows are events rather than a count of them.
 *
 * Recent is also where Step 15's pass found the one date that WAS on Today twice:
 * a "request matched" row stamps the same moment the live card's Submitted tile
 * and the quiet panel's "Last match" stat already carry. Matches are no longer
 * listed here — see ensurance_dashboard_activity(), which owns that rule.
 *
 * NO ARGS, like the quiet panel: the two resolvers below are the whole input, and
 * they are the ones that already own these values elsewhere on the page (see
 * ensurance_dashboard_shopper_rows).
 *
 * EACH COLUMN STANDS ALONE. A column with no rows is not rendered, and with
 * neither there is no band at all — no label over an empty rule, and no empty
 * grid track beside the surviving column (the track count follows the content;
 * see .dash-reference in assets/dashboard.css). That matters today, because
 * activity is not recorded yet and two of the four shopper rows have nothing to
 * resolve outside the admin preview.
 *
 * PREVIEWING: any /dashboard/?slot= state shows the sample activity rows, since
 * this band is not part of the slot. /dashboard/?slot=quiet is the one that also
 * fills in service areas and coverage types — those come from the matching
 * resolvers, whose sample data belongs to the quiet panel.
 *
 * Source: the "What shoppers see" / "Recent" blocks of the Today view in
 * templates/agent-dashboard/AgentDashboard.dc.html (Ensurance Design System).
 * Styling lives in assets/dashboard.css (`.dash-reference*`).
 */

$shopper_rows = ensurance_dashboard_shopper_rows();
$activity     = ensurance_dashboard_activity();

if ( empty( $shopper_rows ) && empty( $activity ) ) {
	return;
}
?>
<div class="dash-reference">

	<?php if ( ! empty( $shopper_rows ) ) : ?>

		<?php
		/*
		 * A <section> named by its own visible label, like the timeline above it
		 * — announced once, with the words on the screen. The label is a <p> and
		 * not a heading for the same reason: it names a band of reference
		 * material, not a level in the document, and a heading here would sit
		 * below the <h2> inside the priority slot while being less important
		 * than it.
		 */
		?>
		<section class="dash-reference__col" aria-labelledby="dash-reference-shoppers">

			<p class="dash-reference__label" id="dash-reference-shoppers">What shoppers see</p>

			<?php
			/*
			 * A description list: every row is a value and the caption naming
			 * what it is, which is exactly a term and its description. The
			 * DESIGN puts the value on top and the caption beneath — the values
			 * are what an agent scans, and the captions only explain them — so
			 * the pair is flipped visually in CSS (column-reverse) while the
			 * markup keeps <dt> before <dd>. A screen reader therefore hears
			 * "Displayed name: Coastline Insurance Group" rather than a loose
			 * value followed by a label it has already moved past.
			 */
			?>
			<dl class="dash-reference__rows">
				<?php foreach ( $shopper_rows as $row ) : ?>
					<div class="dash-reference__row dash-reference__row--fact">
						<dt class="dash-reference__caption"><?php echo esc_html( $row['caption'] ); ?></dt>
						<dd class="dash-reference__value"><?php echo esc_html( $row['value'] ); ?></dd>
					</div>
				<?php endforeach; ?>
			</dl>

		</section>

	<?php endif; ?>

	<?php if ( ! empty( $activity ) ) : ?>

		<section class="dash-reference__col" aria-labelledby="dash-reference-recent">

			<p class="dash-reference__label" id="dash-reference-recent">Recent</p>

			<?php
			/*
			 * An unordered list rather than the timeline's <ol>: these are
			 * newest-first entries in a log, not steps in a sequence, and
			 * nothing about the fourth row follows from the third.
			 */
			?>
			<ul class="dash-reference__rows">
				<?php foreach ( $activity as $entry ) : ?>
					<li class="dash-reference__row dash-reference__row--event">

						<span class="dash-reference__event"><?php echo esc_html( $entry['what'] ); ?></span>

						<?php
						/*
						 * <time> when the entry carries the moment its stamp was
						 * derived from: "2h ago" is relative to the render and
						 * "Aug 6" has no year in it, so the machine-readable
						 * datetime is the only place the actual moment survives.
						 *
						 * An entry with no stamp prints no element at all — the
						 * row is still a record of something that happened, and
						 * a blank column beside it says less than nothing.
						 */
						if ( '' !== $entry['when'] ) :
							?>
							<?php if ( $entry['at'] ) : ?>
								<time class="dash-reference__when" datetime="<?php echo esc_attr( wp_date( 'c', $entry['at'] ) ); ?>"><?php echo esc_html( $entry['when'] ); ?></time>
							<?php else : ?>
								<span class="dash-reference__when"><?php echo esc_html( $entry['when'] ); ?></span>
							<?php endif; ?>
							<?php
						endif;
						?>

					</li>
				<?php endforeach; ?>
			</ul>

		</section>

	<?php endif; ?>

</div>
