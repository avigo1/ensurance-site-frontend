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
 * WHICH ROWS OPEN, and every reason is about what is behind the row:
 *
 *   - it is AWAITING a decision, where the panel says so and points at Today.
 *     This is the whole read-only rule in one place: History never decides
 *     anything, so a row that still needs deciding sends the agent to the one
 *     surface that can;
 *   - it is a PURCHASED LEAD, where the panel is the whole record — see below;
 *   - it is the live request, whose `facts` on
 *     ensurance_dashboard_live_request() are this app's only other per-request
 *     detail — up to four label/value pairs.
 *
 * The first and last overlap on today's row and are separate everywhere else. An
 * accepted row with nothing behind it stays the inert <div> it was, with no caret
 * and no hover surface: a row that looked expandable and opened onto nothing
 * would be worse than a row that does not open.
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
 * A PURCHASED ROW OPENS ONTO THE WHOLE RECORD, which is the one thing on this
 * view that is not a summary. The agent accepted the lead and paid for it, so
 * there is nothing left to withhold and no reason to make them go somewhere else
 * to read what they bought: the panel prints every field
 * ensurance_dashboard_purchased_leads() holds — driver, household, vehicle and
 * its use, driving record, current carrier and renewal, bundle interest, the full
 * address, the reference, when it was accepted and what it cost — in the SAME
 * grid, at the same type scale, with the same tokens the four-fact panel above
 * already uses. Nothing about the row, the list, the filters or the sort changes;
 * only what is inside the panel does.
 *
 * CONTACT IS LIVE ON THOSE ROWS AND ONLY THOSE ROWS. Phone and email are printed
 * unmasked, as a `tel:` and a `mailto:`, beside a button that copies the record as
 * plain text for pasting into an agency system. The copy button ships `hidden` and
 * assets/dashboard.js reveals it only where the clipboard API exists — a button
 * that silently does nothing is worse than no button.
 *
 * AWAITING ROWS ARE UNCHANGED, and that is the masking rule holding rather than a
 * gap. Nothing in the product releases a shopper's contact details before a
 * decision — the live request carries no phone or email at all — so an awaiting
 * row opens onto exactly what it opened onto before: its four facts and the note
 * pointing at Today. No contact strip, no fields to edit, nothing fabricated. A
 * passed row never reaches this list at all.
 *
 * THE ONE EDITABLE THING ON THE VIEW, on purchased rows: a status
 * (ensurance_dashboard_lead_statuses — contacted / quoted / written / no answer)
 * and a private note, saved against the lead's reference in the agent's own
 * record by ensurance_dashboard_handle_lead_note(). This does NOT break the
 * read-only rule stated above, because that rule is about DECISIONS: accepting and
 * passing still happen on Today and nowhere else, and nothing here can change a
 * shopper's answers. What an agent writes here is their own work on top of a
 * record they already own.
 *
 * It is a real form posting to the page, with a real Save button, so it works with
 * JavaScript off — one reload, landing back on History with the row reopened
 * (`?saved=lead&lead=…`, read by ensurance_dashboard_lead_note_saved). With the
 * script it is the same post sent by fetch and the panel never closes. A Save
 * button rather than the agency name's blur-commit: a note is a paragraph an agent
 * stops in the middle of, and there has to be a moment where they decide they are
 * done.
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

/*
 * THE PURCHASED LEADS BEHIND THE ROWS, keyed by the row key the adapter stamped
 * on each one (ensurance_dashboard_lead_records). Resolved once here rather than
 * per row: the leads are one memoized fetch, and the loop below only ever needs
 * to ask whether this row is one of them.
 *
 * The agent's own log is read the same way — one meta row for the whole list, so
 * a panel does not query per lead.
 */
$request_leads  = ensurance_dashboard_lead_records();
$request_log    = ensurance_dashboard_lead_log();
$request_states = ensurance_dashboard_lead_statuses();

// Where the note form posts. Same view, so a no-script save lands back on the
// list it was written in.
$request_action = ensurance_dashboard_requests_url();

// The lead a no-script save just wrote, so its row can be reopened with the
// stored note in it. '' on every other request, including every fetch save.
$request_saved = ensurance_dashboard_lead_note_saved();

/*
 * ONE CELL OF THE PANEL'S GRID. A closure rather than a function because this is
 * a template — a template that declared a function would fatal the second time
 * anything included it.
 *
 * AN EMPTY VALUE KEEPS ITS SLOT and says "Not on file" in the faint shade, which
 * is the rule the rest of the product follows (the Agency Profile's read-only
 * boxes say the same words in the same shade): a missing answer is itself worth
 * knowing to an agent about to quote the risk, and a grid that silently drops
 * fields makes two leads impossible to compare down the column.
 *
 * `sub` is the qualifier that belongs TO the value above it — the usage under a
 * vehicle, the renewal under a carrier, the city under a street — and prints
 * nothing when there is none. It is never a field of its own.
 */
$request_field = static function ( $label, $value, $sub = '' ) {
	$value = trim( (string) $value );
	$sub   = trim( (string) $sub );
	?>
	<div class="dash-requests__field">
		<div class="dash-requests__field-label"><?php echo esc_html( $label ); ?></div>

		<?php if ( '' !== $value ) : ?>
			<div class="dash-requests__field-value"><?php echo esc_html( $value ); ?></div>
		<?php else : ?>
			<div class="dash-requests__field-value dash-requests__field-value--empty">Not on file</div>
		<?php endif; ?>

		<?php if ( '' !== $sub ) : ?>
			<div class="dash-requests__field-sub"><?php echo esc_html( $sub ); ?></div>
		<?php endif; ?>
	</div>
	<?php
};
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

		// The purchased lead behind this row, when it is one. Matched on the row
		// key the adapter stamped — see ensurance_dashboard_lead_records().
		$row_lead = isset( $request_leads[ $row['key'] ] ) ? $request_leads[ $row['key'] ] : array();

		/*
		 * A NOTE IS FILED AGAINST A REFERENCE, so a lead that arrived without one
		 * gets the record and the contact strip but no form. Its row key would be
		 * its position in the list, which changes the next time a lead is bought —
		 * and a note that silently re-attaches itself to a different shopper is
		 * worse than no note at all.
		 */
		$row_ref   = ! empty( $row_lead['ref'] ) ? $row_lead['ref'] : '';
		$row_entry = ( '' !== $row_ref && isset( $request_log[ $row_ref ] ) )
			? $request_log[ $row_ref ]
			: array( 'status' => '', 'note' => '', 'at' => 0 );

		// See "WHICH ROWS OPEN" above. An awaiting row opens even with no facts
		// behind it, because the note inside is itself the reason to open it.
		$row_opens = $row_awaiting || ! empty( $row_facts ) || ! empty( $row_lead );
		$row_panel = 'dash-request-panel-' . (int) $row_index;

		/*
		 * OPEN ON ARRIVAL, for exactly one row and only on the no-script save
		 * path: the row whose note was just written. Rendered open server-side
		 * rather than opened by script, so it is already open on first paint and
		 * the toggle's aria-expanded is true of the DOM before any JS runs.
		 */
		$row_open = ( '' !== $row_ref && $row_ref === $request_saved );
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
			<button type="button" class="dash-requests__line" aria-expanded="<?php echo $row_open ? 'true' : 'false'; ?>" aria-controls="<?php echo esc_attr( $row_panel ); ?>">
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
			 * aria-expanded is true of the DOM on first paint. The one exception
			 * is the row a no-script save just landed on, which renders open —
			 * both halves of the pair move together ($row_open), so the button
			 * and the panel can never disagree about which it is.
			 *
			 * TWO KINDS OF CONTENT, and a row is only ever one of them. A
			 * purchased lead gets the whole record, the live contact strip and
			 * the agent's own status and note. Everything else gets what it
			 * always got: the request's `facts` — up to four label/value pairs
			 * the resolver already validated, so nothing here has to check.
			 *
			 * The awaiting note below is common to both and is the last thing in
			 * either, because it is the only thing in the panel that points
			 * somewhere else.
			 */
			if ( $row_opens ) :
				?>
				<div class="dash-requests__panel" id="<?php echo esc_attr( $row_panel ); ?>"<?php echo $row_open ? '' : ' hidden'; ?>>

					<?php if ( ! empty( $row_lead ) ) : ?>

						<?php
						/*
						 * THE WHOLE RECORD, in the panel's own grid. Every value
						 * is composed once in
						 * ensurance_dashboard_normalize_leads() and printed here
						 * as it was composed — this file assembles no sentences
						 * of its own, so the same lead reads identically on any
						 * surface that ever shows it.
						 *
						 * THE ORDER IS THE ORDER AN AGENT WORKS IN: who they
						 * are, then the risk, then where they live, then the
						 * transaction. Contact is not in the grid — it is the
						 * strip below, where it is something to act on rather
						 * than something to read.
						 */
						$lead_at     = (int) $row_lead['purchased_at'];
						$lead_charge = ensurance_dashboard_lead_charge( $row_lead );
						?>

						<div class="dash-requests__fields">

							<?php
							$request_field( 'Driver', $row_lead['driver'] );
							$request_field( 'Household', $row_lead['household'] );

							// The record holds the PRIMARY vehicle and a count of
							// the rest (which is on the household line above), so
							// this is every vehicle the webhook returns rather
							// than every vehicle the shopper owns.
							$request_field( 'Vehicle', $row_lead['vehicle'], $row_lead['vehicle_use'] );

							$request_field( 'Driving record', $row_lead['record'] );
							$request_field( 'Current carrier', $row_lead['carrier'], $row_lead['carrier_note'] );
							$request_field( 'Bundle interest', $row_lead['bundle'] );

							// Street over "City, ST ZIP" — the same pairing the
							// row's own detail line splits across two columns.
							$request_field( 'Address', $row_lead['address'], $row_lead['location'] );

							$request_field( 'Reference', $row_ref );
							?>

							<?php
							/*
							 * WHEN IT WAS ACCEPTED, as <time> for the same reason
							 * the row's stamp is one: the panel prints a date a
							 * human reads and the attribute keeps the moment it
							 * was. Written out here rather than through the field
							 * closure, which prints text.
							 */
							?>
							<div class="dash-requests__field">
								<div class="dash-requests__field-label">Accepted</div>

								<?php if ( $lead_at ) : ?>
									<div class="dash-requests__field-value">
										<time datetime="<?php echo esc_attr( wp_date( 'c', $lead_at ) ); ?>"><?php echo esc_html( wp_date( 'M j, Y', $lead_at ) ); ?></time>
									</div>
									<div class="dash-requests__field-sub"><?php echo esc_html( wp_date( 'g:i a', $lead_at ) ); ?></div>
								<?php else : ?>
									<div class="dash-requests__field-value dash-requests__field-value--empty">Not on file</div>
								<?php endif; ?>
							</div>

							<?php
							/*
							 * WHAT IT COST. Resolved, not read — no price rides on
							 * a lead record today, so this is the standing lead
							 * price until the billing side supplies a real amount
							 * (see ensurance_dashboard_lead_charge). Unconfigured,
							 * it says so rather than showing a figure nobody was
							 * charged.
							 */
							$request_field( 'Charged', $lead_charge );
							?>

						</div>

						<?php
						/*
						 * THE CONTACT STRIP. Real links rather than buttons, so
						 * they work with no JavaScript, open in whatever the
						 * agent's machine uses for calls and mail, and can be
						 * copied like any other link. The number and the address
						 * are the link TEXT — an agent reading a record wants to
						 * see them, not just be able to press them.
						 */
						$lead_name = trim( wp_strip_all_tags( $row['title'] ) );
						$lead_tel  = preg_replace( '/[^0-9+]/', '', (string) $row_lead['phone'] );

						/*
						 * WHAT "COPY DETAILS" PUTS ON THE CLIPBOARD, assembled
						 * here rather than in the script: the panel is where the
						 * record's shape is already known, and a client that
						 * scraped the DOM for it would produce something
						 * different the moment the layout changed.
						 */
						$lead_copy = array( $lead_name );

						/*
						 * Pairs rather than a map, because the first few lines
						 * carry no label at all — a name over an address over a
						 * city is an address block, and labelling its parts would
						 * be worse to paste than it is to read. Everything after
						 * it is labelled, and a value the record does not hold
						 * drops its line rather than pasting a blank one. The
						 * carrier's note (the renewal) is unlabelled for the same
						 * reason the panel makes it a sub-line: it is the tail of
						 * the value above it, not a fact of its own.
						 */
						foreach ( array(
							array( '', $row_lead['address'] ),
							array( '', $row_lead['location'] ),
							array( 'Phone', $row_lead['phone'] ),
							array( 'Email', $row_lead['email'] ),
							array( 'Driver', $row_lead['driver'] ),
							array( 'Household', $row_lead['household'] ),
							array( 'Vehicle', $row_lead['vehicle'] ),
							array( 'Vehicle use', $row_lead['vehicle_use'] ),
							array( 'Driving record', $row_lead['record'] ),
							array( 'Current carrier', $row_lead['carrier'] ),
							array( '', $row_lead['carrier_note'] ),
							array( 'Bundle interest', $row_lead['bundle'] ),
							array( 'Reference', $row_ref ),
						) as $copy_line ) {
							list( $copy_label, $copy_value ) = $copy_line;

							$copy_value = trim( (string) $copy_value );

							if ( '' === $copy_value ) {
								continue;
							}

							$lead_copy[] = ( '' === $copy_label ) ? $copy_value : $copy_label . ': ' . $copy_value;
						}

						$lead_copy = implode( "\n", $lead_copy );
						?>

						<div class="dash-requests__contact">

							<?php if ( '' !== $lead_tel ) : ?>
								<a class="dash-requests__contact-action" href="tel:<?php echo esc_attr( $lead_tel ); ?>">
									<?php // Icon `phone`, Lucide, stroke 2, round caps/joins — the set components/icons/Icon.jsx draws from. ?>
									<svg viewBox="0 0 24 24" width="14" height="14" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true" focusable="false"><path d="M22 16.92v3a2 2 0 0 1-2.18 2 19.79 19.79 0 0 1-8.63-3.07 19.5 19.5 0 0 1-6-6 19.79 19.79 0 0 1-3.07-8.67A2 2 0 0 1 4.11 2h3a2 2 0 0 1 2 1.72c.13.96.36 1.9.7 2.81a2 2 0 0 1-.45 2.11L8.09 9.91a16 16 0 0 0 6 6l1.27-1.27a2 2 0 0 1 2.11-.45c.9.34 1.85.57 2.81.7A2 2 0 0 1 22 16.92z"/></svg>
									<?php echo esc_html( $row_lead['phone'] ); ?>
								</a>
							<?php endif; ?>

							<?php if ( '' !== $row_lead['email'] ) : ?>
								<a class="dash-requests__contact-action" href="mailto:<?php echo esc_attr( $row_lead['email'] ); ?>">
									<?php // Icon `mail`, same set. ?>
									<svg viewBox="0 0 24 24" width="14" height="14" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true" focusable="false"><rect x="2" y="4" width="20" height="16" rx="2"/><path d="m22 7-8.97 5.7a1.94 1.94 0 0 1-2.06 0L2 7"/></svg>
									<?php echo esc_html( $row_lead['email'] ); ?>
								</a>
							<?php endif; ?>

							<?php
							/*
							 * SHIPS HIDDEN, revealed by assets/dashboard.js only
							 * where navigator.clipboard exists. The live region is
							 * the button's own confirmation line beside it.
							 */
							?>
							<button
								type="button"
								class="dash-requests__contact-action dash-requests__contact-copy"
								data-lead-copy="<?php echo esc_attr( $lead_copy ); ?>"
								hidden
							>
								<?php // Icon `copy`, same set. ?>
								<svg viewBox="0 0 24 24" width="14" height="14" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true" focusable="false"><rect x="9" y="9" width="13" height="13" rx="2"/><path d="M5 15H4a2 2 0 0 1-2-2V4a2 2 0 0 1 2-2h9a2 2 0 0 1 2 2v1"/></svg>
								Copy details
							</button>

							<?php
							// Announced rather than merely shown: the copy leaves
							// no visible trace on the page, so the confirmation is
							// the only evidence it happened.
							?>
							<span class="dash-requests__contact-said" data-lead-copied role="status" aria-live="polite"></span>

							<?php if ( '' === $lead_tel && '' === $row_lead['email'] ) : ?>
								<p class="dash-requests__contact-none">No contact details came through on this lead — message support and we will chase them.</p>
							<?php endif; ?>

						</div>

						<?php if ( '' !== $row_ref ) : ?>

							<?php
							/*
							 * THE AGENT'S OWN LINE ON THE LEAD. A real form to the
							 * page (ensurance_dashboard_handle_lead_note), so it
							 * saves with JavaScript off; the script posts the same
							 * body with fetch so the panel stays open.
							 *
							 * The stamp under it is the record's, not the
							 * client's — `at` is written server-side, so this
							 * line can never claim a save that did not happen.
							 */
							$log_id   = $row_panel . '-log';
							$log_said = $row_entry['at'] ? ensurance_dashboard_relative_time( $row_entry['at'] ) : '';
							$log_said = ( '' !== $log_said ) ? sprintf( 'Saved %s', lcfirst( $log_said ) ) : '';
							?>

							<form class="dash-requests__log" method="post" action="<?php echo esc_url( $request_action ); ?>" data-lead-form>

								<?php wp_nonce_field( 'ensurance_dashboard_lead_note', 'dash_lead_nonce' ); ?>
								<input type="hidden" name="dash_lead_ref" value="<?php echo esc_attr( $row_ref ); ?>" />

								<div class="dash-requests__log-head">
									<label class="dash-requests__log-label" for="<?php echo esc_attr( $log_id ); ?>-status">Status</label>

									<select class="dash-requests__log-select" id="<?php echo esc_attr( $log_id ); ?>-status" name="dash_lead_status">
										<?php
										// '' is a real choice, not a placeholder:
										// it is how a status is cleared again.
										?>
										<option value=""<?php selected( '', $row_entry['status'] ); ?>>No status yet</option>

										<?php foreach ( $request_states as $state_key => $state_label ) : ?>
											<option value="<?php echo esc_attr( $state_key ); ?>"<?php selected( $state_key, $row_entry['status'] ); ?>><?php echo esc_html( $state_label ); ?></option>
										<?php endforeach; ?>
									</select>
								</div>

								<label class="dash-requests__log-label" for="<?php echo esc_attr( $log_id ); ?>-note">Private note</label>

								<textarea
									class="dash-requests__log-note"
									id="<?php echo esc_attr( $log_id ); ?>-note"
									name="dash_lead_note"
									rows="3"
									maxlength="<?php echo esc_attr( ENSURANCE_DASHBOARD_LEAD_NOTE_MAX ); ?>"
									placeholder="What happened when you called. Only you can see this."
								><?php echo esc_textarea( $row_entry['note'] ); ?></textarea>

								<div class="dash-requests__log-actions">
									<button type="submit" class="dash-requests__log-save">Save note</button>

									<p class="dash-requests__log-said" data-lead-said role="status" aria-live="polite"<?php echo ( '' !== $log_said ) ? '' : ' hidden'; ?>><?php echo esc_html( $log_said ); ?></p>

									<p class="dash-requests__log-error" data-lead-error hidden>That note was not saved — the box above is what you typed, not what we hold. Try again.</p>
								</div>

							</form>

						<?php else : ?>

							<p class="dash-requests__log-none">This lead came through without a reference number, so there is nowhere to file a note against it yet. Message support and we will connect it.</p>

						<?php endif; ?>

					<?php elseif ( ! empty( $row_facts ) ) : ?>

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
