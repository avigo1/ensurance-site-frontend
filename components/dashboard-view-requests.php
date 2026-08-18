<?php
/**
 * Agent Dashboard — the History view's list.
 *
 * STEP 12 of templates/agent-dashboard/build-steps.md, restyled to the
 * "Requests page" design handoff (design_handoff_requests_page/README.md).
 * The handoff is a VISUAL restyle: the data behind this file is unchanged —
 * ensurance_dashboard_request_rows() is still the whole input, still resolved
 * the same way, still newest-first — and only the markup and its styling move.
 *
 * THE SECTION IS CALLED HISTORY. The rail row, the <h1> and the subhead all say
 * so; the individual things in it are still "requests" in body copy, because
 * that is what they are. The `requests` URL slug is unchanged, so every link
 * ever shared or bookmarked still lands here — see the note on the registry
 * entry in ensurance_dashboard_views().
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
 * TWO STATES REACH THIS LIST, not four. `expired` is gone from the product
 * (see ensurance_dashboard_request_statuses), and `passed` never arrives —
 * ensurance_dashboard_hide_passed_rows() removes it, because History is what
 * the agent KEPT and passing removes the request from it entirely rather than
 * retiring it into a greyed-out row. So every row here is either "Awaiting you"
 * or "Accepted", and the closed treatment the handoff's step 3 described (the
 * "Detail closed" stand-in, the locked note) has nothing left to describe and is
 * gone with it.
 *
 * WHICH ROWS OPEN, and both reasons are about what is behind the row:
 *
 *   - it is AWAITING a decision, where the panel says so and points at Today.
 *     This is the whole read-only rule in one place: History never decides
 *     anything, so a row that still needs deciding sends the agent to the one
 *     surface that can;
 *   - it is the live request, whose `facts` on
 *     ensurance_dashboard_live_request() are this app's only per-request
 *     detail — up to four label/value pairs, and no other row has any.
 *
 * The two overlap on today's row and are separate everywhere else. An accepted
 * row with no facts behind it stays the inert <div> it was, with no caret and no
 * hover surface: a row that looked expandable and opened onto nothing would be
 * worse than a row that does not open.
 *
 * NO ACCEPT / PASS ANYWHERE ON THIS VIEW, which is step 4 of the handoff
 * DELIBERATELY not built rather than merely unbuilt. Decisions live on Today and
 * only on Today — one surface owns the queue, so an agent is never answering the
 * same request from two places and the two can never disagree about what was
 * answered. An awaiting row here says that in words and links across.
 *
 * (It also could not have been built as written. The handoff asks for the
 * buttons to be wired to the EXISTING handler, and that is
 * ensurance_dashboard_record_decision(), which stores one flag against the USER
 * — update_user_meta( $user_id, ENSURANCE_DASHBOARD_DECISION_META, $decision ) —
 * with no request identifier in it. "Accept row 3" is not expressible against
 * it. Per-request decision state is a schema change rather than a restyle. But
 * the reason the buttons are absent is the rule above, not the schema.)
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
 * A PILL PER STATE THAT HAS ROWS, plus All — so All / Awaiting you / Accepted,
 * which is now the design's own set. The zero-count skip below is what keeps it
 * to that without naming the states twice: `passed` is still a state in
 * ensurance_dashboard_request_statuses() (a request passes THROUGH it on its way
 * out of the list), but no passed row can reach this file, so its count is
 * always 0 and its pill can never print. The same rule also spares an agent a
 * dead "Awaiting you 0" pill in the ordinary week where nothing is waiting.
 *
 * PREVIEWING: /dashboard/?view=requests&slot=live shows the awaiting row above
 * the design's kept history; ?slot=quiet shows the history with no awaiting row
 * above it. The sample's passed row is in neither — it is there to prove it is
 * removed. Nothing produces real rows yet, so an agent sees the empty line
 * below.
 *
 * Source: design_handoff_requests_page/ and the `isReq` view of
 * templates/agent-dashboard/AgentDashboard.dc.html. Styling lives in
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
	 *
	 * IT NO LONGER SAYS NOTHING HAS BEEN MATCHED, which it used to. Now that a
	 * passed request leaves the list entirely, an agent CAN empty this view by
	 * answering — pass the only request that ever reached them and the old line
	 * ("No requests have been matched to your service areas yet") would be
	 * flatly untrue about a request they had just read. So it states what is
	 * true in both cases and claims nothing about matching either way.
	 */
	?>
	<p class="dash-requests__empty">No requests to show here yet.</p>
	<?php
	return;
}

/*
 * The counts behind the pills, tallied off the rows above rather than queried.
 * Labels and order come from ensurance_dashboard_request_statuses() — the same
 * list the rows' own labels come from — so a pill can never name a state
 * differently from the rows it filters to, and a state added there gets its pill
 * for free.
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

// Where an awaiting row sends the agent to answer it. Resolved once, for the
// same reason: it is the same destination on every row.
$request_today = ensurance_dashboard_today_url();
?>

<div class="dash-requests-controls" hidden>

	<?php
	/*
	 * role="group" rather than a toolbar or a radio group: these are
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

			// A state nothing is in gets no pill — see the note at the top,
			// and note this is what keeps `passed` off the control strip.
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

		// The row still asking for an answer. It gets the filled dot, the accent
		// status, and a panel that sends the agent to Today rather than deciding
		// anything here — see "NO ACCEPT / PASS" above.
		$row_awaiting = ( 'awaiting' === $row['status'] );

		// This row's per-request detail, which only the live request has. `live`
		// is the key ensurance_dashboard_request_rows() gives the row it builds
		// from ensurance_dashboard_live_request(), and the only tie between the
		// two.
		$row_facts = ( 'live' === $row['key'] ) ? $request_facts : array();

		// See "WHICH ROWS OPEN" above. An awaiting row opens even with no facts
		// behind it, because the note inside is itself the reason to open it.
		$row_opens = $row_awaiting || ! empty( $row_facts );
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
				 * for an answer says more than a row of colored chips saying
				 * what every row already says in words. Decorative — the status
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

					<?php if ( ! empty( $row_facts ) ) : ?>

						<div class="dash-requests__fields">

							<?php foreach ( $row_facts as $fact ) : ?>
								<div class="dash-requests__field">
									<div class="dash-requests__field-label"><?php echo esc_html( $fact['label'] ); ?></div>
									<div class="dash-requests__field-value"><?php echo esc_html( $fact['value'] ); ?></div>
								</div>
							<?php endforeach; ?>

						</div>

					<?php endif; ?>

					<?php
					/*
					 * THE UNDECIDED NOTE, and the only thing on this view that
					 * points anywhere. It replaces the handoff's in-row Accept /
					 * Pass with the sentence that explains why they are not
					 * here, and the link that goes where they are.
					 *
					 * A REAL <a href>, not a button and not a scripted jump:
					 * Today is a URL (ensurance_dashboard_today_url(), resolved
					 * from the rail registry), so this works with JavaScript
					 * off, opens in a new tab on a middle-click, and can be
					 * copied like any other link. assets/dashboard.js sees the
					 * `dash-view-link` class and swaps the view in place instead
					 * of navigating — the same interception the rail's own rows
					 * get — so with JS it behaves like the design's
					 * setState({ view: 'desk' }) and without it, it is a page
					 * load to the right place.
					 *
					 * No aria-label: the link's own text names the surface it
					 * opens, which is what a screen reader needs out of context
					 * and is already unambiguous. There is at most one of these
					 * on the page — only one row can be awaiting a decision.
					 */
					if ( $row_awaiting ) :
						?>
						<div class="dash-requests__awaiting">
							<p class="dash-requests__awaiting-note">This one is still waiting on your decision. Accept or pass it from Today.</p>
							<a class="dash-requests__awaiting-link dash-view-link" href="<?php echo esc_url( $request_today ); ?>">Open in Today</a>
						</div>
						<?php
					endif;
					?>

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
