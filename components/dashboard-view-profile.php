<?php
/**
 * Agent Dashboard — the Agency Profile view's contents.
 *
 * Step 5 of the setup flow, built to design_handoff_agency_profile/README.md
 * (Ensurance Design System), steps 1–5. This is the destination the Today setup
 * card sends a blocked agent to, and the ONE place in the product where states are
 * added.
 *
 * WHAT IS EDITABLE, AND WHY IT IS ONLY THIS. The handoff makes Agency name the one
 * editable control in the identity grid. It is not, here: agency name was taken
 * out of the matching gate entirely and is no longer something the agent fills in,
 * so it stays a read-only box alongside the others and STATES ARE THE ONLY THING
 * ON THIS PAGE THAT CAN BE CHANGED. That is a deliberate departure from the
 * handoff's Step 2, made because the gate it was written against
 * (ensurance_dashboard_can_receive_leads) no longer includes the name — shipping
 * an editable agency name would offer an edit that changes nothing about whether
 * requests arrive.
 *
 * READ-ONLY IS A STYLE, NOT A DISABLED INPUT. Every identity value is a sunken box
 * — framed like a field so the view still reads as a record, but not typeable, not
 * greyed out, and not a control with its cursor crossed out. A <dl>, because a
 * label over a value it does not own is a term and its description; the one real
 * form control on the page (the state picker) gets a real <label for> instead.
 *
 * NOTHING PERSISTS YET. Adding and removing a state updates the page and the
 * hidden CSV field (ensurance_dashboard_served_states_csv), and stops there — the
 * storage behind it is being wired separately. assets/dashboard.js holds the one
 * marked stub where that save goes. An agent's picks therefore do not survive a
 * refresh, which is why nothing here claims they are saved.
 *
 * NOTHING IS INVENTED. The identity chips always render, and a value nothing
 * resolves says "Not on file" in the faint shade rather than being filled in.
 * Coverage badges and the license chip appear only when they have something to
 * show. Today a real founding agent sees their name and email, no agency name, no
 * phone, no coverage badges — and the empty-state line under States you serve.
 *
 * PREVIEWING: /dashboard/?view=profile&slot=quiet resolves the whole agency
 * record — every chip, the license, the coverage badges and three served states.
 *
 * Styling lives in assets/dashboard.css (`.dash-profile*`); the picker's behaviour
 * in assets/dashboard.js.
 */

$profile_fields = ensurance_dashboard_profile_fields();
$profile_states = ensurance_dashboard_served_states();
$profile_types  = ensurance_dashboard_coverage_types();
$state_choices  = ensurance_dashboard_state_choices();

/*
 * The count beside the eyebrow, in the design's own three forms. It is the one
 * place the page states how many there are, so it says "none set" rather than
 * "0 states" — a zero reads as a number that happens to be low, and this is the
 * difference between receiving requests and not.
 */
$state_total = count( $profile_states );

if ( 0 === $state_total ) {
	$state_count_label = 'none set';
} elseif ( 1 === $state_total ) {
	$state_count_label = '1 state';
} else {
	$state_count_label = sprintf( '%d states', $state_total );
}
?>
<div class="dash-profile">

	<?php
	/*
	 * STEP 2 — the identity grid. Values the agent can read and support can
	 * change; see the note at the top for why none of them is an input.
	 */
	if ( ! empty( $profile_fields ) ) :
		?>
		<dl class="dash-profile__fields">
			<?php
			foreach ( $profile_fields as $profile_field ) :
				// The agent's own name is the one field with a stated reason for
				// being locked — it comes off the verified license, so the lock
				// and the helper under it are answering "why can't I fix this".
				$is_agent = ( isset( $profile_field['key'] ) && 'agent' === $profile_field['key'] );
				?>
				<div class="dash-profile__field">

					<dt class="dash-profile__label"><?php echo esc_html( $profile_field['label'] ); ?></dt>

					<dd class="dash-profile__value<?php echo ! empty( $profile_field['empty'] ) ? ' dash-profile__value--empty' : ''; ?>">

						<span><?php echo esc_html( $profile_field['value'] ); ?></span>

						<?php if ( $is_agent ) : ?>
							<?php // Icon `lock`, from components/icons/Icon.jsx in the design system (Lucide, stroke 2, round caps/joins) at the design's 14px. The helper below says the same thing in words. ?>
							<svg class="dash-profile__lock" viewBox="0 0 24 24" width="14" height="14" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true" focusable="false"><rect x="3" y="11" width="18" height="11" rx="2"/><path d="M7 11V7a5 5 0 0 1 10 0v4"/></svg>
						<?php endif; ?>

					</dd>

					<?php if ( $is_agent ) : ?>
						<p class="dash-profile__helper">From your verified license — support can change this</p>
					<?php endif; ?>

				</div>
			<?php endforeach; ?>
		</dl>
		<?php
	endif;
	?>

	<?php
	/*
	 * STEP 3 — States you serve. The only interactive region on the view.
	 *
	 * data-states marks the root assets/dashboard.js works within, so the script
	 * has one container to scope every query to rather than reaching across the
	 * whole dashboard for chips that only exist here.
	 */
	?>
	<section class="dash-profile__states" data-states aria-labelledby="dash-profile-states">

		<div class="dash-profile__section-head">
			<p class="dash-profile__eyebrow" id="dash-profile-states">States you serve</p>
			<?php
			// aria-live so the count is announced when a chip is added or removed
			// — the chips themselves are the visible confirmation, and this is the
			// equivalent for anyone not looking at them.
			?>
			<p class="dash-profile__count" data-state-count aria-live="polite"><?php echo esc_html( $state_count_label ); ?></p>
		</div>

		<p class="dash-profile__section-note">Requests from anywhere in these states reach you.</p>

		<?php
		// The empty line and the list are both always in the DOM — the script
		// shows whichever fits, so it never has to build either from scratch.
		?>
		<p class="dash-profile__empty" data-states-empty<?php echo $state_total ? ' hidden' : ''; ?>>No states yet — add the states you are licensed in to start receiving requests.</p>

		<ul class="dash-profile__chips" role="list" data-states-list<?php echo $state_total ? '' : ' hidden'; ?>>
			<?php
			foreach ( $profile_states as $profile_state ) :
				$state_code = ensurance_dashboard_state_code( $profile_state );
				?>
				<li class="dash-profile__chip" data-state="<?php echo esc_attr( $profile_state ); ?>">

					<?php if ( '' !== $state_code ) : ?>
						<span class="dash-profile__chip-code"><?php echo esc_html( $state_code ); ?></span>
					<?php endif; ?>

					<span class="dash-profile__chip-name"><?php echo esc_html( $profile_state ); ?></span>

					<?php // A real <button>, not a span: it is an action, it takes focus, and it says what it removes rather than announcing itself as an unlabelled ×. ?>
					<button type="button" class="dash-profile__chip-remove" data-state-remove aria-label="<?php echo esc_attr( sprintf( 'Remove %s', $profile_state ) ); ?>">
						<svg viewBox="0 0 24 24" width="12" height="12" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true" focusable="false"><path d="M18 6 6 18"/><path d="m6 6 12 12"/></svg>
					</button>

				</li>
			<?php endforeach; ?>
		</ul>

		<div class="dash-profile__add">

			<?php
			// The select carries a visible label rather than relying on its
			// placeholder option: a placeholder disappears the moment a state is
			// chosen, which would leave the control unnamed exactly when someone
			// tabbing back to it needs to know what it is. Visually hidden because
			// the eyebrow above already names the section on screen.
			?>
			<label class="sr-only" for="dash-profile-state-select">Add a state you are licensed in</label>

			<select class="dash-profile__select" id="dash-profile-state-select" data-state-select>
				<option value="">Add a state you are licensed in…</option>
				<?php foreach ( $state_choices as $choice_code => $choice_name ) : ?>
					<option value="<?php echo esc_attr( $choice_name ); ?>" data-code="<?php echo esc_attr( $choice_code ); ?>"><?php echo esc_html( $choice_name ); ?></option>
				<?php endforeach; ?>
			</select>

			<button type="button" class="dash-profile__add-btn" data-state-add>Add state</button>

		</div>

		<?php
		// THE VALUE STORAGE WILL READ. One comma-separated line, kept in step with
		// the chips by the script, so whatever eventually saves this reads a field
		// instead of walking the DOM. It posts nowhere today.
		?>
		<input type="hidden" name="served_states" value="<?php echo esc_attr( ensurance_dashboard_served_states_csv() ); ?>" data-states-value />

	</section>

	<?php
	/*
	 * STEP 4 — coverage types. Read-only in v1 and given no editor, per the
	 * handoff. The section drops out entirely when there is nothing to show
	 * rather than printing an eyebrow over an empty row.
	 */
	if ( ! empty( $profile_types ) ) :
		?>
		<section class="dash-profile__group" aria-labelledby="dash-profile-coverages">

			<p class="dash-profile__eyebrow" id="dash-profile-coverages">Coverage types</p>

			<ul class="dash-profile__badges" role="list">
				<?php foreach ( $profile_types as $profile_type ) : ?>
					<li class="dash-profile__badge"><?php echo esc_html( $profile_type ); ?></li>
				<?php endforeach; ?>
			</ul>

		</section>
		<?php
	endif;
	?>

	<?php
	/*
	 * STEP 5 — the closing licensing note, and the page's only link. It is the
	 * one thing on the view that renders unconditionally: it is the answer to
	 * what happens after a state is added, which is the question the section
	 * above raises and cannot answer itself.
	 */
	?>
	<p class="dash-profile__note">

		<?php // Icon `lock`, as above, at the design's 16px. It repeats the sentence beside it and nothing more. ?>
		<svg class="dash-profile__note-icon" viewBox="0 0 24 24" width="16" height="16" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true" focusable="false"><rect x="3" y="11" width="18" height="11" rx="2"/><path d="M7 11V7a5 5 0 0 1 10 0v4"/></svg>

		<span>Licensing is verified against your state filings. Adding a state you are not licensed in will pause matching there — <a href="<?php echo esc_url( ensurance_dashboard_support_url() ); ?>">message agent support</a> if that happens.</span>

	</p>

</div>
