<?php
/**
 * Agent Dashboard — the Account view's rows.
 *
 * STEP 14 of templates/agent-dashboard/build-steps.md, and the last of the four
 * views. Agency Profile answers "what is matching me"; this answers "what am I
 * signed up for" — the term and the dates it turns into a subscription on, the
 * card behind that, the address the agent signs in with, and the way to reach a
 * human about any of it.
 *
 * FOUR ROWS, ONE ACTION. The step is explicit — no Cancel, no Update, no Change —
 * and the scope note at the top of build-steps.md is why: nothing in the product
 * edits an agent's account yet, so every one of those buttons would open the same
 * contact form under a label promising it did something else. The agent support
 * row carries the only control on the view, and it is the honest version of all
 * three. See ensurance_dashboard_account_rows(), which is the whole input to this
 * file.
 *
 * NOT A SETTINGS SURFACE. The design draws four ruled rows and nothing else — no
 * notification toggles, no plan picker, no danger zone — and Step 14 says not to
 * invent one. So this file has no section it was not given, and a row it has
 * nothing to say in is dropped upstream rather than ruled off around a blank.
 *
 * THE INTRO IS NOT HERE. The title and the line saying self-serve changes are
 * coming and support can do any of it today come from the view's registry entry
 * in ensurance_dashboard_views() and are rendered by page-dashboard.php — the same
 * shared header Requests and Agency Profile use. This file is only what sits
 * under it.
 *
 * ROWS, NOT A LIST OF FACTS. The rules are the design's, and they are doing the
 * same job here as on the Requests table: they group a title with the line
 * explaining it and keep the support row's button on the same baseline as the
 * three rows without one, so nothing on the view reads as a card asking to be
 * clicked.
 *
 * PREVIEWING: /dashboard/?view=account&slot=quiet fills in the card and the
 * password age, the same admin-only toggle the profile's license and phone use
 * (ensurance_dashboard_sample_account). Without it an agent sees their real dates
 * and their real sign-in address, and no payment row at all — nothing in the
 * theme can read the card yet, and the row is dropped rather than describing one
 * it cannot see (ensurance_dashboard_payment_method).
 *
 * Source: the `isAcct` view of templates/agent-dashboard/AgentDashboard.dc.html
 * (Ensurance Design System). Styling lives in assets/dashboard.css
 * (`.dash-account*`).
 */

$account_rows = ensurance_dashboard_account_rows();

if ( empty( $account_rows ) ) {
	return;
}
?>
<ul class="dash-account">

	<?php foreach ( $account_rows as $account_row ) : ?>

		<li class="dash-account__row">

			<div class="dash-account__what">
				<?php
				/*
				 * A <p>, not a heading. The row's title names one line of a
				 * record — it is not a level in the document, and a heading here
				 * would sit under the view's <h1> claiming to open a section
				 * that is one sentence long.
				 */
				?>
				<p class="dash-account__title"><?php echo esc_html( $account_row['title'] ); ?></p>
				<p class="dash-account__detail"><?php echo esc_html( $account_row['detail'] ); ?></p>
			</div>

			<?php
			/*
			 * THE ONE ACTION, on the one row that has one. A link rather than a
			 * button because it goes somewhere — /contact/, by way of
			 * ensurance_dashboard_support_contact_url() — and nothing about it
			 * changes state here.
			 *
			 * The accessible name says what is being messaged: "Message" is
			 * clear enough beside the row's title on screen, but read out of
			 * context in a list of links it names no destination. The visible
			 * word is contained in the label, so speech control still matches
			 * what is on screen.
			 */
			if ( ! empty( $account_row['action'] ) ) :
				?>
				<a
					class="dash-account__action"
					href="<?php echo esc_url( $account_row['action']['url'] ); ?>"
					aria-label="<?php echo esc_attr( sprintf( '%s %s', $account_row['action']['label'], strtolower( $account_row['title'] ) ) ); ?>"
				><?php echo esc_html( $account_row['action']['label'] ); ?></a>
				<?php
			endif;
			?>

		</li>

	<?php endforeach; ?>

</ul>
