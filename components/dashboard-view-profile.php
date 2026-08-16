<?php
/**
 * Agent Dashboard — the Agency Profile view's contents.
 *
 * STEP 13 of templates/agent-dashboard/build-steps.md. The reference view for
 * the data matching actually runs on: who the agency is, where a matched request
 * is sent, the counties it is matched in and the coverages it is matched on. An
 * agent comes here to CHECK those, not to change them.
 *
 * IT IS NOT A FORM, AND NOT A DISABLED ONE. The step rules out both in the same
 * breath, and the scope note at the top of build-steps.md is why: agency data is
 * read-only in v1 and every "change this" path in the product ends at agent
 * support. A greyed-out form would promise editing that does not exist, on the
 * one view an agent is most likely to reach for it — so there is no <form>, no
 * <input>, no Save, and nothing on this page is interactive except the support
 * link in the notice at the bottom. Values are printed as sunken chips: framed
 * like a field so the view still reads as a record, but plainly not typeable.
 *
 * THE INTRO IS NOT HERE. The title and the line that says these fields decide
 * which requests reach the agent — and that support changes them — come from the
 * view's registry entry in ensurance_dashboard_views() and are rendered by
 * page-dashboard.php, the same shared header the Requests view uses. This file
 * is only what sits under it.
 *
 * NOTHING IS INVENTED. The four chips this view promises — agent name, agency
 * name, phone, email — always render, and a value nothing resolves says "Not on
 * file" in the faint shade rather than being filled in or quietly dropped. Every
 * other chip and each badge group appears only when it has something to show (see
 * ensurance_dashboard_profile_fields, _service_areas, _coverage_types). Today
 * that means a real founding agent sees the first and last name on their account
 * and, beside it, the same name again as the agency's — nothing captures an
 * agency name separately yet — their email, no phone, no license chip at all,
 * no badge groups, and the locked notice, which is the one thing on
 * this view that always renders, because it is the answer to everything the view
 * is missing.
 *
 * PREVIEWING: /dashboard/?view=profile&slot=quiet shows the full profile — every
 * chip resolved, license included, and both badge groups. That is the same preview toggle
 * the quiet panel uses, because it is the state in which the whole agency record
 * is populated; see ensurance_dashboard_license_number().
 *
 * Source: the `isProf` view of templates/agent-dashboard/AgentDashboard.dc.html
 * (Ensurance Design System). Styling lives in assets/dashboard.css
 * (`.dash-profile*`).
 */

$profile_fields = ensurance_dashboard_profile_fields();
$profile_areas  = ensurance_dashboard_service_areas();
$profile_types  = ensurance_dashboard_coverage_types();

/*
 * THE BADGE GROUPS, as one list so the two render identically — same label, same
 * chips, same rules about being empty. They are the design's Badge at tone
 * `brand`, and they are static text: Step 13 says no add affordances, which
 * means not a "+ Add county" chip, not a dashed placeholder slot, and not an
 * empty group inviting one.
 *
 * SERVICE AREAS CARRY THE WORD "COUNTY". ensurance_dashboard_service_areas()
 * returns bare names ('Coastal') because the surfaces that run the whole list
 * through a sentence say the word once for all of them; here each badge names
 * ONE area on its own, which is the case that carries it — the same rule
 * ensurance_dashboard_sample_request()'s `county` follows. The suffix is skipped
 * when a name already ends in it, so a filter supplying "Coastal County" cannot
 * produce "Coastal County County".
 */
$profile_groups = array();

if ( ! empty( $profile_areas ) ) {
	$area_badges = array();

	foreach ( $profile_areas as $profile_area ) {
		$area_badges[] = preg_match( '/\bcounty$/i', $profile_area )
			? $profile_area
			: sprintf( '%s County', $profile_area );
	}

	$profile_groups[] = array(
		'key'    => 'areas',
		'label'  => 'Service areas',
		'badges' => $area_badges,
	);
}

if ( ! empty( $profile_types ) ) {
	$profile_groups[] = array(
		'key'    => 'coverages',
		'label'  => 'Coverage types',
		'badges' => $profile_types,
	);
}
?>
<div class="dash-profile">

	<?php
	/*
	 * A description list: every chip is a value under the label naming what it
	 * is, which is exactly a term and its description — and it is the markup
	 * that says "label and value" without any of the form semantics this view
	 * must not have. The design puts the label ABOVE the chip, which is the
	 * natural <dt> → <dd> order, so nothing is flipped here (unlike Today's
	 * reference column, whose values sit on top).
	 */
	if ( ! empty( $profile_fields ) ) :
		?>
		<dl class="dash-profile__fields">
			<?php
			foreach ( $profile_fields as $profile_field ) :
				/*
				 * A value we do not have keeps the chip and states it, in the
				 * faint shade the Requests grid uses for a blank answer — the
				 * shade is the whole difference, so a screen reader hears
				 * "Phone, Not on file" and gets the same reading as the eye.
				 * Nothing marks it up as missing beyond that: it is one of the
				 * things the notice at the bottom sends an agent to support
				 * about, not an error on the page.
				 */
				?>
				<div class="dash-profile__field">
					<dt class="dash-profile__label"><?php echo esc_html( $profile_field['label'] ); ?></dt>
					<dd class="dash-profile__value<?php echo ! empty( $profile_field['empty'] ) ? ' dash-profile__value--empty' : ''; ?>"><?php echo esc_html( $profile_field['value'] ); ?></dd>
				</div>
			<?php endforeach; ?>
		</dl>
		<?php
	endif;

	/*
	 * Each group is a <section> named by its own visible label, the same
	 * construction as Today's reference columns — announced once, with the
	 * words that are on the screen. The label is a <p> rather than a heading
	 * for the same reason it is there: it names a group of values, not a level
	 * in the document, and it sits under the view's <h1> without being a
	 * section of the page in any structural sense.
	 *
	 * A <ul>, because a screen reader saying "list of 3 items" is most of what
	 * the badges convey visually.
	 */
	foreach ( $profile_groups as $profile_group ) :
		?>
		<section class="dash-profile__group" aria-labelledby="dash-profile-<?php echo esc_attr( $profile_group['key'] ); ?>">

			<p class="dash-profile__group-label" id="dash-profile-<?php echo esc_attr( $profile_group['key'] ); ?>"><?php echo esc_html( $profile_group['label'] ); ?></p>

			<ul class="dash-profile__badges">
				<?php foreach ( $profile_group['badges'] as $profile_badge ) : ?>
					<li class="dash-profile__badge"><?php echo esc_html( $profile_badge ); ?></li>
				<?php endforeach; ?>
			</ul>

		</section>
		<?php
	endforeach;

	/*
	 * THE LOCKED NOTICE, and the only interactive thing on the view. It states
	 * the fact plainly — editing is coming soon — and then hands over the path
	 * that works today, which is the one destination every "change this" in the
	 * product resolves to (ensurance_dashboard_support_url). Step 13 asks for
	 * exactly one of these, at the bottom, which is why the four chips and the
	 * badge groups above carry no per-field version of it.
	 *
	 * It renders unconditionally: with nothing else resolved on this view, the
	 * notice is the only thing that can tell an agent what to do about it.
	 */
	?>
	<p class="dash-profile__locked">

		<?php // Icon `lock`, copied path-for-path from components/icons/Icon.jsx in the design system (Lucide, stroke 2, round caps/joins) at the design's 16px. It repeats the sentence beside it and nothing more, so it is hidden from assistive tech. ?>
		<svg class="dash-profile__locked-icon" viewBox="0 0 24 24" width="16" height="16" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true" focusable="false"><rect x="3" y="11" width="18" height="11" rx="2"/><path d="M7 11V7a5 5 0 0 1 10 0v4"/></svg>

		<span>Profile editing is coming soon. For now, <a href="<?php echo esc_url( ensurance_dashboard_support_url() ); ?>">message agent support</a> to make a change.</span>

	</p>

</div>
