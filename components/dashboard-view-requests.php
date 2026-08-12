<?php
/**
 * Agent Dashboard — the Requests view's table.
 *
 * STEP 12 of templates/agent-dashboard/build-steps.md, and the first of the
 * three views outside Today. Today answers "what needs me right now"; this
 * answers "what has come my way at all" — every request matched to this agency
 * since access started, newest first, each with where it ended up.
 *
 * THE HEADER IS NOT HERE. The title and the one line of scope above this table
 * come from the view's registry entry in ensurance_dashboard_views() and are
 * rendered by page-dashboard.php, the same shared header Agency Profile and
 * Account will use. This file is only what sits under it.
 *
 * A TABLE, NOT CARDS — the step says so outright, and the reason is what the
 * view is for. A card per request would make every row look like something to
 * act on, which is exactly what this view is not: the one request that needs an
 * answer is on Today, in the priority slot, on its own dark surface. Here the
 * rows are a record, so they are hairline-ruled lines an agent scans down a
 * column of — title and detail left, when it arrived, how it ended.
 *
 * NOT A <table>, EITHER. There is no second axis: every row is one request and
 * the "columns" are three facts about it, not values compared across rows. So
 * this is a list of rows, which is also what lets the stamp and badge stack
 * under the title on a narrow column instead of forcing a horizontal scroll.
 *
 * NOTHING IS CLICKABLE. Step 12 is explicit for v1, and there is nowhere to go:
 * a request detail page does not exist, and the contact details behind an
 * accepted request are emailed rather than shown. A row that looked clickable
 * and did nothing would be worse than a row that does not.
 *
 * THE TOP ROW IS TODAY'S. When a request is waiting, the first row here IS the
 * one in Today's priority slot — same resolver, same request — and its badge
 * follows the decision the agent made there. See
 * ensurance_dashboard_request_rows(), which is the whole input to this file.
 *
 * PREVIEWING: /dashboard/?view=requests&slot=live shows the design's five-row
 * table; ?slot=quiet shows the four closed rows with no awaiting row above them.
 * Nothing produces real rows yet, so an agent sees the empty line below.
 *
 * Source: the `isReq` view of templates/agent-dashboard/AgentDashboard.dc.html
 * (Ensurance Design System). Styling lives in assets/dashboard.css
 * (`.dash-requests*`).
 */

$request_rows = ensurance_dashboard_request_rows();

if ( empty( $request_rows ) ) {
	/*
	 * NO ROWS IS A NORMAL STATE, not an error and not a placeholder — a founding
	 * agent's first weeks look exactly like this, and so does any week nothing
	 * matched. So it gets one plain sentence in the intro's own voice rather
	 * than an empty-state card, an illustration or a call to action: the header
	 * above has already said what this view holds, and this only says it is
	 * empty. The table's own top rule is not printed either, since a rule over
	 * nothing is the stray divider Step 15 asks about.
	 *
	 * It deliberately does NOT restate what the agent is matched on, when the
	 * next request might land, or how to widen their reach — Today's quiet panel
	 * owns all three, and Step 15 forbids saying them twice.
	 */
	?>
	<p class="dash-requests__empty">No requests have been matched to your service areas yet.</p>
	<?php
	return;
}
?>
<ul class="dash-requests">

	<?php foreach ( $request_rows as $row ) : ?>

		<li class="dash-requests__row">

			<div class="dash-requests__what">
				<span class="dash-requests__title"><?php echo esc_html( $row['title'] ); ?></span>

				<?php if ( '' !== $row['detail'] ) : ?>
					<span class="dash-requests__detail"><?php echo esc_html( $row['detail'] ); ?></span>
				<?php endif; ?>
			</div>

			<?php
			/*
			 * Same treatment as the Recent column on Today: <time> when the row
			 * carries the moment its stamp came from, because "2h ago" is
			 * relative to this render and "Aug 6" has no year in it, so the
			 * machine-readable datetime is the only place the actual moment
			 * survives. A row with no stamp prints no element rather than an
			 * empty cell.
			 */
			if ( '' !== $row['when'] ) :
				?>
				<?php if ( $row['at'] ) : ?>
					<time class="dash-requests__when" datetime="<?php echo esc_attr( wp_date( 'c', $row['at'] ) ); ?>"><?php echo esc_html( $row['when'] ); ?></time>
				<?php else : ?>
					<span class="dash-requests__when"><?php echo esc_html( $row['when'] ); ?></span>
				<?php endif; ?>
				<?php
			endif;
			?>

			<?php
			/*
			 * The status badge. The tone is a data attribute rather than a
			 * modifier class because it is a value from
			 * ensurance_dashboard_request_statuses(), not a variant chosen
			 * here — the same reason the priority slot hangs its states off
			 * [data-slot].
			 *
			 * The word is the whole message: the color is a second, coarser
			 * reading of it (two of the four states share a tone), so nothing
			 * about a row depends on telling green from blue.
			 */
			?>
			<span class="dash-requests__status" data-tone="<?php echo esc_attr( $row['tone'] ); ?>"><?php echo esc_html( $row['label'] ); ?></span>

		</li>

	<?php endforeach; ?>

</ul>
