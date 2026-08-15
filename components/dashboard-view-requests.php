<?php
/**
 * Agent Dashboard — the Requests view's list.
 *
 * STEP 12 of templates/agent-dashboard/build-steps.md, restyled to the
 * "Requests page" design handoff (design_handoff_requests_page/README.md).
 * The handoff is a VISUAL restyle: the data behind this file is unchanged —
 * ensurance_dashboard_request_rows() is still the whole input, still resolved
 * the same way, still newest-first — and only the markup and its styling move.
 * Steps 1 (row grid + list chrome) and 2 (the expanded panel) of its working
 * order are in; steps 3 and 4 are not.
 *
 * THE HEADER IS NOT HERE. The title and the one line of scope above this list
 * come from the view's registry entry in ensurance_dashboard_views() and are
 * rendered by page-dashboard.php, the same shared header Agency Profile and
 * Account use. This file is only what sits under it. The handoff's h1 and intro
 * specs (Albert Sans 900 / 30px / -0.03em, 14.5px muted) are already what
 * .dash-view__title and .dash-view__intro render, so nothing here touches them.
 *
 * A LIST, NOT A <table>. There is no second axis: every row is one request and
 * the "columns" are facts about it, not values compared across rows. What the
 * handoff adds is that the columns are now a FIXED grid, identical on every row
 * — including a 132px status track that must not be `auto`, or each row sizes
 * that track to its own text and the column beside it stair-steps down the list.
 *
 * THE FOUR COLUMNS, and what this app has to put in them. The design's row is
 * status gutter / who / what / status, drawn from a request record with a
 * requester name, a location, a vehicle and adverse cues. This app's rows carry
 * two strings — `title` ("Auto — Santa Barbara County") and `detail` ("2
 * drivers, 2 vehicles · ZIP 93013") — so they map onto the design's shape
 * without inventing fields:
 *
 *   gutter  → the accent dot, filled only while the row is awaiting a decision
 *   who     → `title`, the row's subject
 *   what    → `detail`, the line that qualifies it
 *   status  → `label` over `when`, right-aligned in the fixed track
 *
 * The design's second line under the name (location) and its single adverse cue
 * have no field here, so they are simply not printed — the handoff already says
 * the cue only appears when there is one.
 *
 * ONLY A ROW WITH SOMETHING BEHIND IT OPENS. The design expands every row,
 * because every row in its sample data carries a full household / vehicle /
 * coverage record. This app has exactly one source of per-request detail —
 * the `facts` on ensurance_dashboard_live_request(), up to four label/value
 * pairs — and only the live row has it. So the disclosure is conditional: a row
 * with facts becomes a <button> with a caret and a panel, and a row without
 * stays the inert <div> it was, with no caret and no hover surface. A row that
 * looked expandable and opened onto nothing would be worse than a row that does
 * not open. (Step 3 of the handoff gives passed and expired rows a panel of
 * their own — a locked note, which needs no request data — so after it lands
 * the only inert rows are accepted ones with no facts.)
 *
 * READ FROM THE RESOLVER, NOT THROUGH THE ROW. ensurance_dashboard_request_rows()
 * does not carry `facts`, and widening it would mean editing an existing
 * function in functions.php — which CLAUDE.md rules out. So the panel calls
 * ensurance_dashboard_live_request() directly and matches it to the one row
 * built from it, which the row shaper keys `live`. Same resolver, same
 * capability gate, no new data and no change to what any of it returns.
 *
 * WHAT IS DELIBERATELY MISSING FROM THE PANEL. The handoff's panel also carries
 * a contact strip — masked phone and email on awaiting rows, real contact plus
 * Call / Email links on accepted ones. None of it is built here, and the reason
 * is not that this app masks contact details differently: it is that nothing in
 * the product holds a shopper's phone or email at all, there is no masking
 * helper to reuse, and nothing yet releases contact details on accept.
 * ensurance_dashboard_record_decision() writes one user-meta flag and fires
 * `ensurance_dashboard_decision_recorded`, which has no listener — releasing the
 * details is named there as the queue's job, unwritten.
 *
 * So the strip is not a layout this file can adapt; it is a record, a permission
 * rule and a release step that do not exist. Per the handoff's own instruction —
 * enforce masking with the app's existing rule, ask rather than guess — it waits
 * for the team. No strip, no mask, no fabricated phone number.
 *
 * THE CONTROLS ARE UI STATE, NOT A QUERY. The filter pills and the sort toggle
 * act on the rows this file has already rendered: no request, no `?` arg, no
 * change to what ensurance_dashboard_request_rows() returns or the order it
 * returns it in. They are marked `hidden` here and revealed by
 * assets/dashboard.js, so a browser without JS gets the full unfiltered list
 * rather than controls that do nothing — the same progressive-enhancement rule
 * the rail's view switching follows. The panels are the exception: they are
 * plain `hidden` markup a <button> toggles, so they work with the same JS and
 * are simply closed without it.
 *
 * A PILL PER STATE THAT HAS ROWS, plus All. The design shows all five because
 * its sample data has every state in it; this app's real table is usually one
 * row or none, where four pills reading "0" would be four dead controls. So a
 * state pill prints only when something is in that state — which reproduces the
 * design's five pills exactly on the design's own data, and degrades quietly on
 * the data an agent actually has.
 *
 * PREVIEWING: /dashboard/?view=requests&slot=live shows the design's five-row
 * table; ?slot=quiet shows the four closed rows with no awaiting row above them.
 * Nothing produces real rows yet, so an agent sees the empty line below.
 *
 * Source: design_handoff_requests_page/ (Requests list view). Styling lives in
 * assets/dashboard.css (`.dash-requests*`).
 */

$request_rows = ensurance_dashboard_request_rows();

if ( empty( $request_rows ) ) {
	/*
	 * NO ROWS IS A NORMAL STATE, not an error and not a placeholder — a founding
	 * agent's first weeks look exactly like this, and so does any week nothing
	 * matched. So it gets one plain sentence in the intro's own voice rather
	 * than an empty-state card, an illustration or a call to action: the header
	 * above has already said what this view holds, and this only says it is
	 * empty. No controls and no count line print either — there is nothing to
	 * filter, sort or count, and a rule over nothing is the stray divider Step
	 * 15 asks about.
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

/*
 * The counts behind the pills, tallied off the rows above rather than queried.
 * Labels and order come from ensurance_dashboard_request_statuses() — the same
 * list the rows' own labels come from — so a pill can never name a state
 * differently from the rows it filters to, and a fifth state added there gets
 * its pill for free.
 */
$request_statuses = ensurance_dashboard_request_statuses();
$request_counts   = array_fill_keys( array_keys( $request_statuses ), 0 );
$request_total    = count( $request_rows );

foreach ( $request_rows as $row ) {
	++$request_counts[ $row['status'] ];
}

/*
 * The one request with detail behind it — see "READ FROM THE RESOLVER" above.
 * Resolved once here rather than per row, because the loop below asks about it
 * on every iteration and it is the same request every time.
 */
$request_live  = ensurance_dashboard_live_request();
$request_facts = ! empty( $request_live['facts'] ) ? $request_live['facts'] : array();
?>

<div class="dash-requests-controls" hidden>

	<?php
	/*
	 * role="group" rather than a toolbar or a radio group: these are five
	 * independent toggles in the markup, and the one-at-a-time behavior is
	 * dashboard.js's. aria-pressed on each is what reports which one is on.
	 */
	?>
	<div class="dash-requests-controls__filters" role="group" aria-label="Filter requests">

		<button type="button" class="dash-requests-pill is-active" data-filter="all" aria-pressed="true">
			All <span class="dash-requests-pill__count"><?php echo esc_html( number_format_i18n( $request_total ) ); ?></span>
		</button>

		<?php
		foreach ( $request_statuses as $status_key => $status ) :

			// A state nothing is in gets no pill — see the note at the top.
			if ( 0 === $request_counts[ $status_key ] ) {
				continue;
			}
			?>
			<button type="button" class="dash-requests-pill" data-filter="<?php echo esc_attr( $status_key ); ?>" aria-pressed="false">
				<?php echo esc_html( $status['label'] ); ?> <span class="dash-requests-pill__count"><?php echo esc_html( number_format_i18n( $request_counts[ $status_key ] ) ); ?></span>
			</button>
			<?php
		endforeach;
		?>

	</div>

	<?php
	/*
	 * "Sort" is a label for the button beside it, not a control — hence the
	 * <span> plus aria-describedby rather than a <label>, which has nothing
	 * form-ish to point at. The button's own text is the current order.
	 *
	 * ONE OF THE DESIGN'S TWO ORDERS IS NOT AVAILABLE HERE. The handoff toggles
	 * "Newest first" / "Renewal soonest"; no request in this app carries a
	 * renewal date, so the second order would sort on a field that does not
	 * exist. Oldest first stands in — it is the same control, over the ordering
	 * this app can actually express — and it is done by reversing the rendered
	 * order rather than re-sorting on a timestamp, because rows may legitimately
	 * arrive with no timestamp at all (see ensurance_dashboard_request_rows()).
	 */
	?>
	<div class="dash-requests-controls__sort">
		<span class="dash-requests-controls__sort-label" id="dash-requests-sort">Sort</span>
		<button type="button" class="dash-requests-controls__sort-toggle" data-sort="newest" aria-describedby="dash-requests-sort">Newest first</button>
	</div>

</div>

<ul class="dash-requests">

	<?php
	foreach ( $request_rows as $row_index => $row ) :

		// See "ONLY A ROW WITH SOMETHING BEHIND IT OPENS" above. `live` is the
		// key ensurance_dashboard_request_rows() gives the row it builds from
		// ensurance_dashboard_live_request(), and the only tie between the two.
		$row_opens = ( 'live' === $row['key'] && ! empty( $request_facts ) );
		$row_panel = 'dash-request-panel-' . (int) $row_index;
		?>

		<li class="dash-requests__row" data-status="<?php echo esc_attr( $row['status'] ); ?>">

			<?php
			/*
			 * THE LINE IS THE DISCLOSURE, and it is a real <button> rather than
			 * the design's clickable <div> — the handoff's accessibility note,
			 * and what gets it Enter/Space, a focus ring and an announced
			 * expanded state for free. A row with nothing to open stays a <div>,
			 * so the two branches below differ only in the element and its
			 * attributes; the four columns between them are written once.
			 *
			 * Everything inside is phrasing content (<span>, <time>) because a
			 * <button> may not contain a <div> — the columns get their block and
			 * grid behaviour from CSS instead.
			 */
			?>
			<?php if ( $row_opens ) : ?>
			<button type="button" class="dash-requests__line" aria-expanded="false" aria-controls="<?php echo esc_attr( $row_panel ); ?>">
			<?php else : ?>
			<div class="dash-requests__line">
			<?php endif; ?>

				<?php
				/*
				 * THE PAGE'S ONLY "LOOK HERE" SIGNAL, and the reason the status
				 * badges are gone: one filled dot on the one row still asking
				 * for an answer says more than four colored chips saying what
				 * every row already says in words. Decorative — the status
				 * column states the same thing in text — so it is hidden from
				 * assistive tech rather than labeled twice.
				 */
				?>
				<span class="dash-requests__dot" aria-hidden="true"></span>

				<span class="dash-requests__who">
					<span class="dash-requests__title"><?php echo esc_html( $row['title'] ); ?></span>
				</span>

				<span class="dash-requests__what">
					<?php if ( '' !== $row['detail'] ) : ?>
						<span class="dash-requests__detail"><?php echo esc_html( $row['detail'] ); ?></span>
					<?php endif; ?>
				</span>

				<span class="dash-requests__state">

					<span class="dash-requests__state-text">

						<span class="dash-requests__status"><?php echo esc_html( $row['label'] ); ?></span>

						<?php
						/*
						 * Same treatment as the Recent column on Today: <time>
						 * when the row carries the moment its stamp came from,
						 * because "2h ago" is relative to this render and
						 * "Aug 6" has no year in it, so the machine-readable
						 * datetime is the only place the actual moment
						 * survives. A row with no stamp prints no element
						 * rather than an empty cell.
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

					</span>

					<?php
					/*
					 * The caret's 12px is printed on EVERY row, chevron or not.
					 * Without it the rows that do not open would give their
					 * status text the caret's width back and sit 24px further
					 * right than the rows that do — which is the same column
					 * misalignment the fixed 132px track exists to prevent.
					 */
					?>
					<span class="dash-requests__caret" aria-hidden="true">
						<?php if ( $row_opens ) : ?>
							<svg viewBox="0 0 24 24" width="12" height="12" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round" focusable="false"><path d="m6 9 6 6 6-6"/></svg>
						<?php endif; ?>
					</span>

				</span>

			<?php if ( $row_opens ) : ?>
			</button>
			<?php else : ?>
			</div>
			<?php endif; ?>

			<?php
			/*
			 * THE PANEL. Ships `hidden` so a JS-less browser gets a closed row
			 * rather than every panel open at once, and so the button's
			 * aria-expanded="false" is true of the DOM on first paint.
			 *
			 * The fields are the request's own `facts` — up to four label/value
			 * pairs the resolver already validated (half-filled pairs are
			 * dropped there, so nothing here has to check). The design's eight
			 * named fields are its data model, not this one's; these are the
			 * same object in the same grid.
			 */
			if ( $row_opens ) :
				?>
				<div class="dash-requests__panel" id="<?php echo esc_attr( $row_panel ); ?>" hidden>

					<div class="dash-requests__fields">

						<?php foreach ( $request_facts as $fact ) : ?>
							<div class="dash-requests__field">
								<div class="dash-requests__field-label"><?php echo esc_html( $fact['label'] ); ?></div>
								<div class="dash-requests__field-value"><?php echo esc_html( $fact['value'] ); ?></div>
							</div>
						<?php endforeach; ?>

					</div>

				</div>
				<?php
			endif;
			?>

		</li>

		<?php
	endforeach;
	?>

</ul>

<?php
/*
 * How much of the list is in view. Static until the pills are live — with no
 * filter applied the two numbers are the same, which is exactly what it should
 * read on first paint. dashboard.js rewrites only the first number, so the
 * sentence itself is never assembled in JavaScript.
 */
?>
<p class="dash-requests__note">
	<span class="dash-requests__note-shown"><?php echo esc_html( number_format_i18n( $request_total ) ); ?></span>
	of <?php echo esc_html( number_format_i18n( $request_total ) ); ?> requests shown
</p>
