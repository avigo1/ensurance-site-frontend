<?php
/**
 * Agent Dashboard — the `decided` priority-slot surface: the request has just
 * been answered.
 *
 * STEP 7 of templates/agent-dashboard/build-steps.md. What the dark live card
 * turns into the moment Accept or Pass is pressed, in the same slot and in the
 * same place on the page — the agent's eye does not have to go looking for the
 * result of the button it just pressed.
 *
 * A LIGHT ACCENT PANEL, not another dark card. The dark surface means "this is
 * waiting on you"; nothing is waiting on the agent now, so the weight drops.
 * Tinted rather than plain white so the change registers as an event that just
 * happened rather than as the page having quietly emptied.
 *
 * WHAT IT RENDERS, and nothing more:
 *   - a check glyph and the outcome headline, on one row;
 *   - ONE sentence of what happens next — see ensurance_dashboard_decided_panel();
 *   - a single Undo, which hands the slot back to `live`.
 *
 * THE FACT TILES DO NOT COME BACK. Shopper ZIP, household, carrier, submitted —
 * all of it is gone the moment the decision is made. They existed to be decided
 * on; re-showing them after the fact invites second-guessing a decision the
 * agent has already made, and on an accepted request the real details are in
 * the inbox the panel names, not on this card.
 *
 * ONE UNDO, AND NOTHING ELSE. No "view request", no "accept another", no
 * dismiss. The panel states an outcome and offers the single control that
 * reverses it.
 *
 * ARGS — ['decision' => 'accept'|'pass']. Rendered by dashboard-view-today.php,
 * which does not render the slot at all without a decision, so this file can
 * assume it has a real one and simply returns if it somehow does not.
 *
 * Source: the `decided` branch of the Today view in
 * templates/agent-dashboard/AgentDashboard.dc.html (Ensurance Design System).
 * Styling lives in assets/dashboard.css (`.dash-decided__*`).
 */

$decision = isset( $args['decision'] ) ? sanitize_key( $args['decision'] ) : '';

if ( ! in_array( $decision, ensurance_dashboard_decisions(), true ) ) {
	return;
}

$panel = wp_parse_args(
	ensurance_dashboard_decided_panel( $decision ),
	array(
		'title' => '',
		'body'  => '',
	)
);

// The headline IS the outcome — a panel that cannot say which way the request
// went is not a confirmation of anything.
if ( '' === $panel['title'] ) {
	return;
}
?>
<div class="dash-decided__head">

	<?php // Icon `circle-check`, copied path-for-path from components/icons/Icon.jsx in the design system (Lucide, stroke 2, round caps/joins) at the design's 20px. Decorative: the headline beside it already says the outcome. ?>
	<svg class="dash-decided__icon" viewBox="0 0 24 24" width="20" height="20" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true" focusable="false"><circle cx="12" cy="12" r="10"/><path d="m9 12 2 2 4-4"/></svg>

	<?php
	// An <h2> for the same reason the live card's headline is one: it sits
	// under Today's greeting <h1>. The color is forced in CSS — see the note on
	// .dash-decided__title in assets/dashboard.css.
	?>
	<h2 class="dash-decided__title"><?php echo esc_html( $panel['title'] ); ?></h2>

</div>

<?php if ( '' !== $panel['body'] ) : ?>
	<p class="dash-decided__body"><?php echo esc_html( $panel['body'] ); ?></p>
<?php endif; ?>

<?php
/*
 * UNDO. A form post, not a link — it changes recorded state, exactly like the
 * Accept and Pass it reverses, so it gets the same treatment: it works with
 * JavaScript off, is announced and operated as a button, and cannot be fired by
 * anything that merely follows a URL (a prefetch, a crawler, a link in an
 * email). ensurance_dashboard_handle_undo() takes it from there.
 *
 * It LOOKS like the design's quiet text link because that is the right weight
 * for it — the decision stands, and this is the small way back — but nothing
 * about the markup is a link.
 *
 * The accessible name says what is being undone; "Undo" alone is what a screen
 * reader would read out of context, and the headline above it is a separate
 * element.
 */
?>
<form class="dash-decided__undo" method="post" action="<?php echo esc_url( ensurance_dashboard_undo_action() ); ?>">

	<?php wp_nonce_field( 'ensurance_dashboard_undo', 'dash_undo_nonce' ); ?>

	<button type="submit" class="dash-decided__undo-button" name="dash_undo" value="1" aria-label="<?php echo esc_attr( sprintf( 'Undo — %s', $panel['title'] ) ); ?>">Undo</button>

</form>
