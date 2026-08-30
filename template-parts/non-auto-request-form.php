<?php
/**
 * Shared Ensurance non-auto request form.
 *
 * Expected optional $ensurance_request_context keys:
 * - coverage_lock: '', life, home, health, renters
 * - partner_id
 * - partner_label
 * - referral_code
 * - eyebrow
 * - title
 * - subcopy
 */
$ctx = isset( $args ) && is_array( $args )
    ? $args
    : ( isset( $ensurance_request_context ) && is_array( $ensurance_request_context ) ? $ensurance_request_context : array() );

$coverage_lock = isset( $ctx['coverage_lock'] ) ? sanitize_key( $ctx['coverage_lock'] ) : '';
$partner_id    = isset( $ctx['partner_id'] ) ? sanitize_key( $ctx['partner_id'] ) : '';
$partner_label = isset( $ctx['partner_label'] ) ? sanitize_text_field( $ctx['partner_label'] ) : '';
$referral_code = isset( $ctx['referral_code'] ) ? sanitize_key( $ctx['referral_code'] ) : '';
$eyebrow       = isset( $ctx['eyebrow'] ) ? sanitize_text_field( $ctx['eyebrow'] ) : 'Start your insurance request';
$title         = isset( $ctx['title'] ) ? sanitize_text_field( $ctx['title'] ) : 'One request. A clearer path to insurance options.';
$subcopy       = isset( $ctx['subcopy'] ) ? sanitize_text_field( $ctx['subcopy'] ) : 'Tell us what you need. Ensurance keeps your request organized and your contact information protected while it moves toward licensed review where available.';
?>
<section class="sq-request nar-shell" aria-label="Insurance request">
  <span class="sq-request__glow sq-request__glow--a" aria-hidden="true"></span>
  <span class="sq-request__glow sq-request__glow--b" aria-hidden="true"></span>

  <div class="sq-request__inner nar-wrap">
    <header class="sq-request__intro nar-intro">
      <span class="eyebrow"><?php echo esc_html( $eyebrow ); ?></span>
      <?php if ( $partner_label ) : ?>
        <p class="nar-referral">Referred to Ensurance by <?php echo esc_html( $partner_label ); ?></p>
      <?php endif; ?>
      <h1 class="sq-request__title"><?php echo esc_html( $title ); ?></h1>
      <p class="sq-request__sub"><?php echo esc_html( $subcopy ); ?></p>
    </header>

    <div class="sq-formslot nar-card">
      <div class="nar-progress" aria-label="Request progress">
        <div class="nar-progress__top">
          <span id="nar-progress-label">Request</span>
          <span id="nar-progress-count">Step 1 of 4</span>
        </div>
        <div class="nar-progress__track" aria-hidden="true"><span id="nar-progress-fill"></span></div>
      </div>

      <form id="ens-non-auto-request-form"
            class="nar-form"
            novalidate
            data-coverage-lock="<?php echo esc_attr( $coverage_lock ); ?>"
            data-partner-id="<?php echo esc_attr( $partner_id ); ?>"
            data-referral-code="<?php echo esc_attr( $referral_code ); ?>">

        <input type="text" name="website" id="nar-website" class="nar-hp" tabindex="-1" autocomplete="off" aria-hidden="true">

        <section class="nar-step is-active" data-step="1">
          <div class="nar-step__heading">
            <span class="nar-kicker">Start here</span>
            <h2><?php echo $coverage_lock ? 'Where should we start your request?' : 'What kind of insurance help do you need?'; ?></h2>
            <p>We only ask for the information needed to organize your request and route it appropriately.</p>
          </div>

          <?php if ( $coverage_lock ) : ?>
            <input type="hidden" id="nar-coverage-type" name="coverage_type" value="<?php echo esc_attr( $coverage_lock ); ?>">
            <div class="nar-locked-product"><span><?php echo esc_html( strtoupper( $coverage_lock ) ); ?></span> Insurance Request</div>
          <?php else : ?>
            <div class="nar-field">
              <label for="nar-coverage-type">Insurance type</label>
              <select id="nar-coverage-type" name="coverage_type" class="nar-control" required>
                <option value="">Choose one</option>
                <option value="life">Life Insurance</option>
                <option value="home">Home Insurance</option>
                <option value="health">Health Insurance</option>
                <option value="renters">Renters Insurance</option>
              </select>
              <p class="nar-error" data-error-for="coverage_type"></p>
            </div>
          <?php endif; ?>

          <div class="nar-grid nar-grid--2">
            <div class="nar-field">
              <label for="nar-state">State</label>
              <select id="nar-state" name="state" class="nar-control" required>
                <option value="">Select state</option>
                <option value="AL">Alabama</option><option value="AK">Alaska</option><option value="AZ">Arizona</option><option value="AR">Arkansas</option>
                <option value="CA">California</option><option value="CO">Colorado</option><option value="CT">Connecticut</option><option value="DE">Delaware</option>
                <option value="DC">District of Columbia</option><option value="FL">Florida</option><option value="GA">Georgia</option><option value="HI">Hawaii</option>
                <option value="ID">Idaho</option><option value="IL">Illinois</option><option value="IN">Indiana</option><option value="IA">Iowa</option>
                <option value="KS">Kansas</option><option value="KY">Kentucky</option><option value="LA">Louisiana</option><option value="ME">Maine</option>
                <option value="MD">Maryland</option><option value="MA">Massachusetts</option><option value="MI">Michigan</option><option value="MN">Minnesota</option>
                <option value="MS">Mississippi</option><option value="MO">Missouri</option><option value="MT">Montana</option><option value="NE">Nebraska</option>
                <option value="NV">Nevada</option><option value="NH">New Hampshire</option><option value="NJ">New Jersey</option><option value="NM">New Mexico</option>
                <option value="NY">New York</option><option value="NC">North Carolina</option><option value="ND">North Dakota</option><option value="OH">Ohio</option>
                <option value="OK">Oklahoma</option><option value="OR">Oregon</option><option value="PA">Pennsylvania</option><option value="RI">Rhode Island</option>
                <option value="SC">South Carolina</option><option value="SD">South Dakota</option><option value="TN">Tennessee</option><option value="TX">Texas</option>
                <option value="UT">Utah</option><option value="VT">Vermont</option><option value="VA">Virginia</option><option value="WA">Washington</option>
                <option value="WV">West Virginia</option><option value="WI">Wisconsin</option><option value="WY">Wyoming</option>
              </select>
              <p class="nar-error" data-error-for="state"></p>
            </div>
            <div class="nar-field">
              <label for="nar-zip">ZIP code</label>
              <input id="nar-zip" name="zip" class="nar-control" type="text" inputmode="numeric" maxlength="5" autocomplete="postal-code" placeholder="e.g. 92648" required>
              <p class="nar-error" data-error-for="zip"></p>
            </div>
          </div>

          <div class="nar-nav nar-nav--end"><button type="button" class="nar-next">Continue</button></div>
        </section>

        <section class="nar-step" data-step="2">
          <div class="nar-step__heading">
            <span class="nar-kicker">Your request</span>
            <h2 id="nar-product-heading">A few details help us route this correctly.</h2>
            <p>These are request-level details, not a carrier underwriting application.</p>
          </div>

          <div class="nar-product" data-product="life">
            <div class="nar-grid nar-grid--2">
              <div class="nar-field"><label for="nar-age">Age</label><input id="nar-age" name="age" class="nar-control" type="number" min="18" max="100" inputmode="numeric"><p class="nar-error" data-error-for="age"></p></div>
              <div class="nar-field"><label for="nar-life-type">Life insurance type</label><select id="nar-life-type" name="life_type" class="nar-control"><option value="">Choose one</option><option value="term-life">Term Life</option><option value="whole-life">Whole Life</option><option value="unsure">Not sure yet</option></select><p class="nar-error" data-error-for="life_type"></p></div>
            </div>
            <div class="nar-grid nar-grid--2">
              <div class="nar-field"><label for="nar-coverage-amount">Coverage amount</label><select id="nar-coverage-amount" name="coverage_amount" class="nar-control"><option value="">Choose one</option><option value="100000">$100,000</option><option value="250000">$250,000</option><option value="500000">$500,000</option><option value="1000000">$1,000,000</option><option value="not-sure">Not sure</option></select><p class="nar-error" data-error-for="coverage_amount"></p></div>
              <div class="nar-field"><label for="nar-term-length">Term length</label><select id="nar-term-length" name="term_length" class="nar-control"><option value="">Choose one</option><option value="10">10 years</option><option value="15">15 years</option><option value="20">20 years</option><option value="30">30 years</option><option value="unsure">Not sure / not applicable</option></select></div>
            </div>
            <div class="nar-grid nar-grid--2">
              <div class="nar-field"><label for="nar-tobacco">Tobacco or nicotine use</label><select id="nar-tobacco" name="tobacco_use" class="nar-control"><option value="">Choose one</option><option value="no">No</option><option value="yes">Yes</option><option value="prefer-not-to-say">Prefer not to say</option></select><p class="nar-error" data-error-for="tobacco_use"></p></div>
              <div class="nar-field"><label for="nar-health-band">Overall health</label><select id="nar-health-band" name="health_band" class="nar-control"><option value="">Choose one</option><option value="excellent">Excellent</option><option value="good">Good</option><option value="average">Average</option><option value="below-average">Below average</option><option value="prefer-not-to-say">Prefer not to say</option></select></div>
            </div>
            <div class="nar-field"><label for="nar-life-timing">When would you like coverage to begin?</label><select id="nar-life-timing" name="coverage_timing" class="nar-control"><option value="">Choose one</option><option value="asap">As soon as possible</option><option value="30-days">Within 30 days</option><option value="60-days">Within 60 days</option><option value="researching">Just researching</option></select><p class="nar-error" data-error-for="coverage_timing"></p></div>
          </div>

          <div class="nar-product" data-product="home">
            <div class="nar-grid nar-grid--2">
              <div class="nar-field"><label for="nar-property-type">Property type</label><select id="nar-property-type" name="property_type" class="nar-control"><option value="">Choose one</option><option value="single-family">Single-family home</option><option value="condo">Condo</option><option value="townhome">Townhome</option><option value="other">Other</option></select><p class="nar-error" data-error-for="property_type"></p></div>
              <div class="nar-field"><label for="nar-ownership">Property status</label><select id="nar-ownership" name="ownership_status" class="nar-control"><option value="">Choose one</option><option value="own">I own it</option><option value="purchasing">I am purchasing it</option><option value="other">Other</option></select><p class="nar-error" data-error-for="ownership_status"></p></div>
            </div>
            <div class="nar-grid nar-grid--2">
              <div class="nar-field"><label for="nar-year-built">Approximate year built <span class="nar-optional">Optional</span></label><input id="nar-year-built" name="year_built" class="nar-control" type="number" min="1700" max="2030" inputmode="numeric"></div>
              <div class="nar-field"><label for="nar-home-current">Currently insured?</label><select id="nar-home-current" name="current_insurance" class="nar-control"><option value="">Choose one</option><option value="yes">Yes</option><option value="no">No</option><option value="not-sure">Not sure</option></select><p class="nar-error" data-error-for="current_insurance"></p></div>
            </div>
            <div class="nar-grid nar-grid--2">
              <div class="nar-field"><label for="nar-claims">Property claims in the past 5 years?</label><select id="nar-claims" name="claims_context" class="nar-control"><option value="">Choose one</option><option value="none">No</option><option value="yes">Yes</option><option value="not-sure">Not sure</option></select></div>
              <div class="nar-field"><label for="nar-home-timing">When do you need coverage?</label><select id="nar-home-timing" name="home_coverage_timing" class="nar-control"><option value="">Choose one</option><option value="asap">As soon as possible</option><option value="30-days">Within 30 days</option><option value="60-days">Within 60 days</option><option value="researching">Just researching</option></select><p class="nar-error" data-error-for="home_coverage_timing"></p></div>
            </div>
          </div>

          <div class="nar-product" data-product="renters">
            <div class="nar-field"><label for="nar-renting-status">Rental status</label><select id="nar-renting-status" name="renting_status" class="nar-control"><option value="">Choose one</option><option value="yes">Currently renting</option><option value="moving">Moving into a rental</option><option value="not-yet">Not renting yet</option></select><p class="nar-error" data-error-for="renting_status"></p></div>
            <div class="nar-grid nar-grid--2">
              <div class="nar-field"><label for="nar-renters-current">Currently have renters insurance?</label><select id="nar-renters-current" name="renters_current_insurance" class="nar-control"><option value="">Choose one</option><option value="yes">Yes</option><option value="no">No</option><option value="not-sure">Not sure</option></select></div>
              <div class="nar-field"><label for="nar-renters-timing">When do you need coverage?</label><select id="nar-renters-timing" name="renters_start_timing" class="nar-control"><option value="">Choose one</option><option value="asap">As soon as possible</option><option value="30-days">Within 30 days</option><option value="later">Later</option><option value="researching">Just researching</option></select><p class="nar-error" data-error-for="renters_start_timing"></p></div>
            </div>
          </div>

          <div class="nar-product" data-product="health">
            <div class="nar-grid nar-grid--2">
              <div class="nar-field"><label for="nar-health-type">What kind of help do you need?</label><select id="nar-health-type" name="health_request_type" class="nar-control"><option value="">Choose one</option><option value="individual">Individual coverage</option><option value="family">Family coverage</option><option value="unsure">Not sure</option></select><p class="nar-error" data-error-for="health_request_type"></p></div>
              <div class="nar-field"><label for="nar-household-size">People who may need coverage</label><input id="nar-household-size" name="health_household_size" class="nar-control" type="number" min="1" max="20" inputmode="numeric"><p class="nar-error" data-error-for="health_household_size"></p></div>
            </div>
            <div class="nar-field"><label for="nar-health-timing">When do you need coverage?</label><select id="nar-health-timing" name="health_coverage_timing" class="nar-control"><option value="">Choose one</option><option value="asap">As soon as possible</option><option value="30-days">Within 30 days</option><option value="later">Later</option><option value="researching">Just researching</option></select><p class="nar-error" data-error-for="health_coverage_timing"></p></div>
            <div class="nar-privacy-note">We do not ask for detailed medical history on this request form.</div>
          </div>

          <div class="nar-field">
            <label for="nar-notes">Anything else an insurance professional should know? <span class="nar-optional">Optional</span></label>
            <textarea id="nar-notes" name="notes" class="nar-control nar-textarea" rows="4" maxlength="1200"></textarea>
          </div>

          <div class="nar-nav"><button type="button" class="nar-back">Back</button><button type="button" class="nar-next">Continue</button></div>
        </section>

        <section class="nar-step" data-step="3">
          <div class="nar-step__heading">
            <span class="nar-kicker">Contact</span>
            <h2>How should the next step reach you?</h2>
            <p>Your contact information stays protected from the agent pool until the request reaches the appropriate acceptance step.</p>
          </div>

          <div class="nar-grid nar-grid--2">
            <div class="nar-field"><label for="nar-first-name">First name</label><input id="nar-first-name" name="first_name" class="nar-control" type="text" autocomplete="given-name" required><p class="nar-error" data-error-for="first_name"></p></div>
            <div class="nar-field"><label for="nar-last-name">Last name</label><input id="nar-last-name" name="last_name" class="nar-control" type="text" autocomplete="family-name" required><p class="nar-error" data-error-for="last_name"></p></div>
          </div>
          <div class="nar-grid nar-grid--2">
            <div class="nar-field"><label for="nar-email">Email</label><input id="nar-email" name="email" class="nar-control" type="email" autocomplete="email" required><p class="nar-error" data-error-for="email"></p></div>
            <div class="nar-field"><label for="nar-phone">Phone</label><input id="nar-phone" name="phone" class="nar-control" type="tel" autocomplete="tel" inputmode="tel" required><p class="nar-error" data-error-for="phone"></p></div>
          </div>
          <div class="nar-field"><label for="nar-contact-method">Preferred contact method</label><select id="nar-contact-method" name="preferred_contact" class="nar-control" required><option value="">Choose one</option><option value="phone">Phone</option><option value="text">Text</option><option value="email">Email</option></select><p class="nar-error" data-error-for="preferred_contact"></p></div>

          <div class="nar-nav"><button type="button" class="nar-back">Back</button><button type="button" class="nar-next">Review request</button></div>
        </section>

        <section class="nar-step" data-step="4">
          <div class="nar-step__heading">
            <span class="nar-kicker">Review</span>
            <h2>Review your request before sending.</h2>
            <p>You are starting an insurance request, not buying a policy or agreeing to purchase coverage.</p>
          </div>

          <div id="nar-review" class="nar-review"></div>

          <label class="nar-consent" for="nar-consent">
            <input id="nar-consent" name="consent" type="checkbox" value="1">
            <span>I am at least 18 years old and authorize Ensurance to use the information I provide to facilitate review of my insurance request and contact by an appropriate licensed insurance professional where available. I agree to the Ensurance Privacy Policy and Terms of Use.</span>
          </label>
          <p class="nar-error" data-error-for="consent"></p>

          <div class="nar-submit-state" id="nar-submit-state" role="status" aria-live="polite"></div>
          <div class="nar-nav"><button type="button" class="nar-back">Back</button><button type="submit" class="nar-submit">Send my request</button></div>
        </section>
      </form>

      <div id="nar-success" class="nar-success" hidden>
        <span class="nar-success__icon" aria-hidden="true">✓</span>
        <h2>Your request is in.</h2>
        <p>Ensurance has received your request. We will keep it organized and move it toward the appropriate next step.</p>
        <p class="nar-success__id">Request ID: <strong id="nar-success-id"></strong></p>
      </div>
    </div>

    <div class="sq-cues nar-trust-row" aria-label="Ensurance request protections">
      <span class="trust-cue">Protected request</span>
      <span class="trust-cue">One organized intake</span>
      <span class="trust-cue">No obligation</span>
      <span class="trust-cue">Licensed review where available</span>
    </div>

    <div class="sq-control nar-control-wrap">
      <div class="sq-callout nar-control-note" role="note">
        <span class="sq-callout__icon" aria-hidden="true">✓</span>
        <div class="nar-control-note__body">
          <p class="sq-callout__title">Your information stays under a controlled process.</p>
          <p class="sq-callout__body">We do not ask you to restart the same request company after company. Contact information is not included in broad agent notifications.</p>
          <div class="nar-control-note__links">
            <a href="<?php echo esc_url( home_url( '/trust-center/' ) ); ?>">Trust Center</a>
            <a href="<?php echo esc_url( home_url( '/privacy-policy/' ) ); ?>">Privacy Policy</a>
          </div>
        </div>
      </div>
    </div>
  </div>
</section>
