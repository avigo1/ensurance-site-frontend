<?php
/**
 * Agent Dashboard — the `live` priority-slot surface: one matched request
 * awaiting a decision.
 *
 * STEP 5 of templates/agent-dashboard/build-steps.md. The dark navy card under
 * Today's greeting — the ONLY dark surface in the main column, which is what
 * makes "a request is waiting on you" unmissable without a banner or a badge.
 *
 * WHAT IT RENDERS, and nothing more:
 *   - a header row: the eyebrow "Decision needed" left, the expiry countdown
 *     with its clock glyph right;
 *   - a headline naming the coverage type and the county;
 *   - up to four individually bordered fact tiles in a gap grid — each its own
 *     box, not one shared background split by 1px lines;
 *   - the decision row: Accept and Pass as equals, and the one line saying what
 *     each of them does (STEP 6 — see the block above the form below).
 *
 * The card IS the .dash-slot section (see components/dashboard-view-today.php),
 * so this part renders only its contents. That keeps the one-state-at-a-time
 * container in one place and lets every state style itself off [data-slot].
 *
 * ARGS — the array ensurance_dashboard_live_request() returns; see its docblock
 * for the shape. Rendered by dashboard-view-today.php, which does not render the
 * slot at all when that array is empty, so this file can assume it has a
 * request. A missing countdown is normal, though (an unset or past expiry), and
 * simply drops the line.
 *
 * Source: the `hasLive` branch of the Today view in
 * templates/agent-dashboard/AgentDashboard.dc.html (Ensurance Design System).
 * Styling lives in assets/dashboard.css (`.dash-request__*`).
 */

$request = wp_parse_args(
	$args ?? array(),
	array(
		'coverage'   => '',
		'county'     => '',
		'expires_at' => 0,
		'facts'      => array(),
	)
);

// The headline names both, so neither is optional — a card that cannot say what
// the request is for is not worth painting. Same guard the resolver applies;
// repeated here because a caller could always pass its own array.
if ( '' === $request['coverage'] || '' === $request['county'] ) {
	return;
}

$countdown = ensurance_dashboard_countdown( $request['expires_at'] );
?>
<div class="dash-request__top">

	<p class="dash-request__eyebrow">Decision needed</p>

	<?php
	// No countdown, no line: ensurance_dashboard_countdown() returns '' for an
	// unset or already-passed expiry, and an empty clock row would read as a
	// broken card rather than as an open-ended request.
	if ( '' !== $countdown ) :
		?>
		<p class="dash-request__expiry">
			<?php // Icon `clock`, copied path-for-path from components/icons/Icon.jsx in the design system (Lucide, stroke 2, round caps/joins) at the design's 14px. ?>
			<svg viewBox="0 0 24 24" width="14" height="14" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true" focusable="false"><circle cx="12" cy="12" r="10"/><path d="M12 6v6l4 2"/></svg>
			<?php
			// The visible text is a duration; the <time> carries the moment it
			// counts down to, in the site's timezone with the offset attached.
			//
			// The <span> is not decoration: the row is a flex container, so an
			// unwrapped <time> would become a second flex item and take the
			// row's 7px gap between "Expires in" and the countdown. Wrapping
			// keeps the sentence one item, spaced by its own space character.
			?>
			<span>Expires in <time datetime="<?php echo esc_attr( wp_date( 'c', $request['expires_at'] ) ); ?>"><?php echo esc_html( $countdown ); ?></time></span>
		</p>
	<?php endif; ?>

</div>

<?php
// An <h2>: it sits under Today's greeting <h1>, so the card's headline is a
// section heading, not the page's. The white is forced in CSS — see the note on
// .dash-request__title in assets/dashboard.css.
?>
<h2 class="dash-request__title"><?php echo esc_html( sprintf( '%s coverage — %s', $request['coverage'], $request['county'] ) ); ?></h2>

<?php
/*
 * FACT TILES. A description list, because that is exactly what these are —
 * "Shopper ZIP" / "93013" is a term and its value, and a screen reader
 * announces the pair instead of eight loose strings. Each tile is a <div>
 * wrapping its <dt>/<dd> (valid in HTML5 and what makes them grid items).
 *
 * The grid is auto-fit, so a card with fewer than four facts fills the row
 * rather than leaving empty tracks.
 */
if ( ! empty( $request['facts'] ) ) :
	?>
	<dl class="dash-request__facts">
		<?php foreach ( $request['facts'] as $fact ) : ?>
			<div class="dash-request__fact">
				<dt class="dash-request__fact-label"><?php echo esc_html( $fact['label'] ); ?></dt>
				<dd class="dash-request__fact-value"><?php echo esc_html( $fact['value'] ); ?></dd>
			</div>
		<?php endforeach; ?>
	</dl>
	<?php
endif;

/*
 * DECISION CONTROLS — Step 6. Accept and Pass, then one line saying what each
 * one does.
 *
 * THE TWO BUTTONS ARE PEERS. Same size, same weight, same font, same row —
 * Accept is solid because it is the affirmative, not because it is the answer
 * the product wants. Pass is a real button rather than a text link, carries no
 * warning tone and opens no "are you sure?", because passing a request that is
 * wrong for an agency IS a correct answer and deciding so should not cost an
 * agent anything. See .dash-request__pass in assets/dashboard.css, which is
 * where that equality is actually enforced.
 *
 * A FORM, NOT JAVASCRIPT. The design flips a component's state; this posts. So
 * the decision survives JS being off, both controls are announced and operated
 * as buttons, and nothing can decide a request by following a link. The two
 * buttons submit the same field with different values, which is why there is one
 * form here and no client-side code deciding anything.
 * ensurance_dashboard_handle_decision() takes it from there, and BOTH values
 * leave the slot in `decided`.
 *
 * The note is tied to both buttons with aria-describedby, so a screen reader
 * reaches "Name, phone, and email unlock on accept…" as part of the control it
 * describes rather than as a stray line somewhere after it.
 *
 * PENDING STATE. The post is a full page load, and on a slow connection the card
 * sits there looking untouched — which is exactly when an agent presses again,
 * or presses the OTHER button, and cannot tell which decision they just filed.
 * So the pressed button carries a spinner and the row locks after the first
 * press (assets/dashboard.js). Two things make that safe to add here:
 *
 *   - the buttons are NEVER disabled. A disabled submit button drops out of the
 *     form's payload, taking the accept/pass value with it, and drops out of the
 *     tab order under the finger that just pressed it. The lock is the submit
 *     handler refusing the second submit instead;
 *   - the spinner is markup, not something JS injects, so the pending state is
 *     styled in dashboard.css with the rest of the card.
 *
 * The status line beside it is the same message for a screen reader, which sees
 * no spinner. It is empty until the press — an empty live region announces
 * nothing — and role="status" is polite, so it waits its turn.
 *
 * With JS off, none of this exists and the form posts exactly as before.
 */
?>
<form class="dash-request__decide" method="post" action="<?php echo esc_url( ensurance_dashboard_decision_action() ); ?>">

	<?php wp_nonce_field( 'ensurance_dashboard_decide', 'dash_decide_nonce' ); ?>

	<?php // .btn / .btn-primary from assets/home.css — the site's own button, which is what the design's Button component renders at size md (48px, pill radius, medium weight). The spinner is hidden until the press and takes the button's own text color, so it can never drift from the label beside it. ?>
	<button type="submit" class="btn btn-primary dash-request__accept" name="dash_decision" value="accept" aria-describedby="dash-request-note">
		<span class="dash-request__spinner" aria-hidden="true"></span>Accept request
	</button>

	<?php // The same .btn geometry, outlined for the dark card — the one variant home.css does not carry, added in dashboard.css beside .dash-signout's. ?>
	<button type="submit" class="btn dash-request__pass" name="dash_decision" value="pass" aria-describedby="dash-request-note">
		<span class="dash-request__spinner" aria-hidden="true"></span>Pass
	</button>

	<p class="dash-request__note" id="dash-request-note">Name, phone, and email unlock on accept. Passing removes it from your queue.</p>

	<?php // Filled by assets/dashboard.js on the press. .sr-only (home.css) is position:absolute, so it is not a flex item in this row and takes none of its 12px gap. ?>
	<p class="dash-request__status sr-only" role="status"></p>

</form>
