<?php
/**
 * Agent Dashboard — the Agency Profile view's contents.
 *
 * Step 5 of the setup flow, built to design_handoff_agency_profile/README.md
 * (Ensurance Design System), steps 1–5. This is the destination the Today setup
 * card sends a blocked agent to, and the ONE place in the product where states are
 * added.
 *
 * WHAT IS EDITABLE, AND WHY IT IS ONLY THESE TWO. Agency name (Step 6 of the setup
 * flow) and the served states (the handoff's Step 3). Everything else on the view
 * is the record as support holds it. Agency name is editable even though the
 * matching gate no longer includes it (ensurance_dashboard_can_receive_leads is
 * states-only): the name is not what turns requests on, but it IS what an agency
 * is called on every surface that greets it, and it is the one identity value the
 * agent themselves supplied at sign-up.
 *
 * THE NAME SAVES WITH NO SAVE BUTTON, because the design has none: the field is a
 * one-input form that assets/dashboard.js submits on blur and on Enter, and that
 * with JavaScript off still submits on Enter by itself. It posts to the page and
 * ensurance_dashboard_handle_agency_name() writes it to the company-name record
 * the sign-up funnel already keeps, then redirects back here — so the confirmation
 * under the field is a rendered page state, not a toast.
 *
 * READ-ONLY IS A STYLE, NOT A DISABLED INPUT. The values the agent cannot change
 * are sunken boxes — framed like a field so the view still reads as a record, but
 * not typeable, not greyed out, and not a control with its cursor crossed out. A
 * <dl>, because a label over a value it does not own is a term and its
 * description. The two real controls (the name field and the state picker) get
 * real <label for>s; the name's label lives inside its <dt>, which keeps the grid
 * one list rather than splitting it into a form and a record.
 *
 * THE STATES PERSIST TOO, as of Step 7. The picker is a real form: "Add state"
 * and each chip's × are submit buttons carrying the state they act on, so it works
 * with JavaScript off, one reload per change. With the script, the change is made
 * in place and the same intent — "add California", never the whole list — is
 * posted with fetch, so the page stays where it is; a change that does not land is
 * put back and said so. Both paths end in ensurance_dashboard_handle_states(), and
 * because the list resolves through ensurance_dashboard_service_areas(), setting a
 * state here is what turns matching on.
 *
 * NOTHING IS INVENTED. The identity chips always render, and a value nothing
 * resolves says "Not on file" in the faint shade rather than being filled in — the
 * name field says it in its placeholder instead, because an input's job is to be
 * filled in. Coverage badges and the license chip appear only when they have
 * something to show. Today a real founding agent sees their name and email, the
 * agency name they gave at sign-up if they gave one, no phone, no coverage badges
 * — and the empty-state line under States you serve.
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
 * The name field's own value, from the resolver the field list itself reads
 * (ensurance_dashboard_agency_record_name) — so the input and the record can
 * never disagree. It is taken raw rather than off $profile_fields because that
 * list substitutes "Not on file" for an empty value, and a placeholder belongs in
 * the placeholder attribute, not in the box as text the agent has to delete.
 */
$profile_name  = ensurance_dashboard_agency_record_name();
$profile_saved = ensurance_dashboard_agency_name_saved();

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
	 * STEP 2 — the identity grid. The record as support holds it, with the one
	 * value the agent supplied themselves — the agency name — editable in place.
	 * See the note at the top for why that one and nothing else.
	 */
	if ( ! empty( $profile_fields ) ) :
		?>
		<dl class="dash-profile__fields">
			<?php
			foreach ( $profile_fields as $profile_field ) :
				$field_key = isset( $profile_field['key'] ) ? $profile_field['key'] : '';

				// The agent's own name is the one field with a stated reason for
				// being locked — it comes off the verified license, so the lock
				// and the helper under it are answering "why can't I fix this".
				$is_agent = ( 'agent' === $field_key );

				// The agency's name is the one field that is not a value at all
				// but a control. `name` is the key ensurance_dashboard_profile_fields()
				// gives it.
				$is_name = ( 'name' === $field_key );
				?>
				<div class="dash-profile__field">

					<dt class="dash-profile__label">
						<?php
						// A real <label for> only on the field that has something to
						// label. Wrapping the read-only terms in one would name a box
						// that takes no input and no focus.
						if ( $is_name ) :
							?>
							<label for="dash-profile-agency"><?php echo esc_html( $profile_field['label'] ); ?></label>
						<?php else : ?>
							<?php echo esc_html( $profile_field['label'] ); ?>
						<?php endif; ?>
					</dt>

					<?php if ( $is_name ) : ?>

						<dd class="dash-profile__control">

							<?php
							/*
							 * NO SUBMIT BUTTON, by design. dashboard.js commits this
							 * on blur and on Enter; without it, Enter alone still
							 * submits a form with a single text input. Either way it
							 * is an ordinary post to the page, handled by
							 * ensurance_dashboard_handle_agency_name().
							 */
							?>
							<form method="post" action="<?php echo esc_url( ensurance_dashboard_agency_name_action() ); ?>" data-agency-form>

								<?php wp_nonce_field( 'ensurance_dashboard_agency_name', 'dash_agency_nonce' ); ?>

								<?php
								// Under `?slot=quiet` this field is showing the sample
								// agency, so a save from it must not file the sample
								// against a real account — see
								// ensurance_dashboard_handle_agency_name().
								if ( '' !== ensurance_dashboard_priority_preview() ) :
									?>
									<input type="hidden" name="dash_agency_preview" value="1" />
								<?php endif; ?>

								<input
									type="text"
									class="dash-profile__input"
									id="dash-profile-agency"
									name="dash_agency_name"
									value="<?php echo esc_attr( $profile_name ); ?>"
									placeholder="Add your agency name"
									autocomplete="organization"
									data-agency-input
								/>

							</form>

							<?php
							// The confirmation. It is rendered by the page the save
							// redirects to, so it is not a live region and carries no
							// role="status": nothing here updates after load, and the
							// value above it is the confirmation a reader gets first.
							if ( $profile_saved ) :
								?>
								<p class="dash-profile__helper dash-profile__helper--saved">Agency name saved</p>
							<?php endif; ?>

						</dd>

					<?php else : ?>

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

					<?php endif; ?>

				</div>
			<?php endforeach; ?>
		</dl>
		<?php
	endif;
	?>

	<?php
	/*
	 * STEP 3 — States you serve, and Step 7 of the setup flow: the list persists.
	 *
	 * data-states marks the root assets/dashboard.js works within, so the script
	 * has one container to scope every query to rather than reaching across the
	 * whole dashboard for chips that only exist here.
	 *
	 * IT IS A REAL FORM, and every control in it is a real submit button carrying
	 * the state it acts on — "Add state" sends the select's choice, each chip's ×
	 * sends its own name. So the picker works with JavaScript off, one reload per
	 * change, through the same handler
	 * (ensurance_dashboard_handle_states). With the script, nothing here submits:
	 * it updates the list in place and posts the same intent with fetch, so the
	 * page the agent is reading stays where it is.
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

		<form method="post" action="<?php echo esc_url( ensurance_dashboard_profile_url() ); ?>" data-states-form>

		<?php wp_nonce_field( 'ensurance_dashboard_states', 'dash_states_nonce' ); ?>

		<?php
		// Under `?slot=quiet` these chips are the sample agency's, so a change to
		// them must not be filed against a real account — see
		// ensurance_dashboard_handle_states(). The script reads the same marker and
		// skips the request entirely.
		if ( '' !== ensurance_dashboard_priority_preview() ) :
			?>
			<input type="hidden" name="dash_states_preview" value="1" data-states-preview />
		<?php endif; ?>

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

					<?php
					// A real <button>, not a span: it is an action, it takes focus,
					// and it says what it removes rather than announcing itself as
					// an unlabelled ×. It SUBMITS, carrying the state as its value,
					// so the removal works without the script; with the script the
					// click is intercepted before the form ever submits.
					?>
					<button type="submit" class="dash-profile__chip-remove" name="dash_state_remove" value="<?php echo esc_attr( $profile_state ); ?>" data-state-remove aria-label="<?php echo esc_attr( sprintf( 'Remove %s', $profile_state ) ); ?>">
						<svg viewBox="0 0 24 24" width="12" height="12" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true" focusable="false"><path d="M18 6 6 18"/><path d="m6 6 12 12"/></svg>
					</button>

				</li>
			<?php endforeach; ?>
		</ul>

		<?php
		// WHEN A SAVE DOES NOT LAND. Hidden until it happens, and the script puts
		// the list back the way the record has it before showing this — so the line
		// and the chips above it never disagree. There is no retry button: a change
		// the agent can simply make again does not need one.
		?>
		<p class="dash-profile__error" data-states-error hidden>That change was not saved — the list above is the record as we hold it. Try again.</p>

		<div class="dash-profile__add">

			<?php
			// The select carries a visible label rather than relying on its
			// placeholder option: a placeholder disappears the moment a state is
			// chosen, which would leave the control unnamed exactly when someone
			// tabbing back to it needs to know what it is. Visually hidden because
			// the eyebrow above already names the section on screen.
			?>
			<label class="sr-only" for="dash-profile-state-select">Add a state you are licensed in</label>

			<?php
			// `name` is what makes the no-script path work: the select IS the add
			// field, and "Add state" is the plain submit button beside it.
			?>
			<select class="dash-profile__select" id="dash-profile-state-select" name="dash_state_add" data-state-select>
				<option value="">Add a state you are licensed in…</option>
				<?php foreach ( $state_choices as $choice_code => $choice_name ) : ?>
					<option value="<?php echo esc_attr( $choice_name ); ?>" data-code="<?php echo esc_attr( $choice_code ); ?>"><?php echo esc_html( $choice_name ); ?></option>
				<?php endforeach; ?>
			</select>

			<button type="submit" class="dash-profile__add-btn" data-state-add>Add state</button>

		</div>

		<?php
		// THE PUBLISHED LIST. One comma-separated line, kept in step with the chips
		// by the script, so anything reading the current list reads a field instead
		// of walking the DOM. It is not what saves — a change posts the state it
		// changed, not this snapshot (ensurance_dashboard_handle_states).
		?>
		<input type="hidden" name="served_states" value="<?php echo esc_attr( ensurance_dashboard_served_states_csv() ); ?>" data-states-value />

		</form>

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
