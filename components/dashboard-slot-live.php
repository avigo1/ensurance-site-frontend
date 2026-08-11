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
 *     box, not one shared background split by 1px lines.
 *
 * NO ACCEPT / PASS YET. The step is explicit that the decision controls are
 * Step 6, so the card ends at the tiles. The design's own 22px below the grid
 * belongs to those buttons and is not reproduced here — the card's bottom
 * padding closes it instead.
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
			?>
			Expires in <time datetime="<?php echo esc_attr( wp_date( 'c', $request['expires_at'] ) ); ?>"><?php echo esc_html( $countdown ); ?></time>
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
