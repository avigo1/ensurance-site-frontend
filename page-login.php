<?php
/**
 * /login — Agent Login (Calm Intelligence redesign).
 *
 * Code-driven replacement for the old Gutenberg/UsersWP-shortcode login page.
 * Renders via the page-{slug}.php hierarchy for the "login" page, so it
 * auto-overrides the previous DB block content with no admin template change.
 *
 * Chrome: uses the homepage chrome (get_header('home') / get_footer('home')) and
 * assets/home.css + assets/home.js for tokens, base and the shared header/footer
 * — the same pattern as page-for-agents.php. The page-specific layout (hero,
 * login grid, new-agent card, support and footer CTA) lives in assets/login.css,
 * with the scroll-reveal + password toggle in assets/login.js. Both are enqueued
 * and isolated from the shared marketing bundle in functions.php
 * (ensurance_login_assets), scoped to this page only.
 *
 * AUTHENTICATION — the important part.
 *   The login form is re-skinned but authentication is NOT rebuilt. The form
 *   posts to itself with the EXACT UsersWP contract, so UsersWP's process_login()
 *   (hooked on template_redirect) handles it unchanged: WordPress wp_signon(),
 *   sessions, "remember me", redirect and the agent-profile flow all keep working.
 *     - username / password            → the two configured UsersWP login fields
 *     - remember_me = "forever"         → enables persistent login
 *     - redirect_to                     → post-login destination (/dashboard/,
 *                                         or a validated ?redirect_to= target)
 *     - uwp_login_nonce                 → wp_create_nonce('uwp-login-nonce'),
 *                                         verified in UsersWP class-forms.php
 *     - uwp_login_submit                → submit button name
 *   Login errors surface through UsersWP's own notices via
 *   do_action('uwp_template_display_notices','login'). "Create account" and
 *   "Forgot password?" route to the existing UsersWP register/forgot pages.
 *
 * SEO: meta description / canonical / robots are owned by Yoast and emitted
 * through wp_head(); this template outputs none of them. The <title> is the one
 * exception — see the title override below.
 */

/**
 * <title> override.
 *
 * /login is the agent login page, so the tab/SERP title should say so rather
 * than inheriting whatever generic title is stored for the page.
 *
 * Yoast removes core's _wp_render_title_tag and prints its own title, so
 * 'wpseo_title' is the hook that actually decides the output. The
 * 'pre_get_document_title' filter is the fallback for when Yoast is inactive;
 * it runs at 99 so it beats anything hooked at the default priority.
 */
add_filter( 'wpseo_title', function () {
	return 'Agent Login | Ensurance';
} );

add_filter( 'pre_get_document_title', function () {
	return 'Agent Login | Ensurance';
}, 99 );

// --- Inline Lucide icon renderer (shared across templates via function_exists
//     guard; only one page template renders per request). Paths from Lucide. ---
if ( ! function_exists( 'ensurance_home_icon' ) ) {
	function ensurance_home_icon( $name, $size = 20 ) {
		$icons = array(
			'shield-check' => '<path d="M20 13c0 5-3.5 7.5-7.66 8.95a1 1 0 0 1-.67-.01C7.5 20.5 4 18 4 13V6a1 1 0 0 1 1-1c2 0 4.5-1.2 6.24-2.72a1.17 1.17 0 0 1 1.52 0C14.51 3.81 17 5 19 5a1 1 0 0 1 1 1z"/><path d="m9 12 2 2 4-4"/>',
			'check'        => '<path d="M20 6 9 17l-5-5"/>',
			'arrow-right'  => '<path d="M5 12h14"/><path d="m12 5 7 7-7 7"/>',
			'message'      => '<path d="M21 15a2 2 0 0 1-2 2H7l-4 4V5a2 2 0 0 1 2-2h14a2 2 0 0 1 2 2z"/>',
		);
		$inner = isset( $icons[ $name ] ) ? $icons[ $name ] : '';
		$s     = (int) $size;
		return '<svg width="' . $s . '" height="' . $s . '" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">' . $inner . '</svg>';
	}
}

// SVG allowlist for wp_kses on the icon helper output.
$ensurance_svg_allowed = array(
	'svg'      => array( 'width' => true, 'height' => true, 'viewbox' => true, 'fill' => true, 'stroke' => true, 'stroke-width' => true, 'stroke-linecap' => true, 'stroke-linejoin' => true, 'aria-hidden' => true ),
	'path'     => array( 'd' => true ),
	'circle'   => array( 'cx' => true, 'cy' => true, 'r' => true ),
	'rect'     => array( 'x' => true, 'y' => true, 'width' => true, 'height' => true, 'rx' => true, 'ry' => true ),
	'polyline' => array( 'points' => true ),
);

// --- Resolved destinations (use the site's real slugs / UsersWP helpers). ---
$al_home_url     = esc_url( home_url( '/' ) );
$al_hiw_url      = esc_url( home_url( '/how-it-works' ) );
$al_coverage_url = esc_url( home_url( '/coverage' ) );
$al_trust_url    = esc_url( home_url( '/trust-center' ) );
$al_agents_url   = esc_url( home_url( '/for-agents' ) );
$al_contact_url  = esc_url( home_url( '/contact' ) );

// Both Founding Agent paths are self-serve sign-ups (→ /create-account?plan=…):
// the free "Start 60 Day Access" (plan=60-day → dashboard) and the paid "Join as
// a Founding Agent" ($29/mo, plan=monthly → Stripe checkout, then dashboard).
// Registry + funnel: functions.php. Logged-in agents skip sign-up and go straight
// to the dashboard (ensurance_founding_cta_url); logged-out visitors sign up.
$al_cta_60day   = esc_url( ensurance_founding_cta_url( ensurance_create_account_url( '60-day' ) ) );  // Start 60 Day Access → self-serve signup
$al_cta_monthly = esc_url( ensurance_founding_cta_url( ensurance_create_account_url( 'monthly' ) ) ); // Join as a Founding Agent → self-serve signup → Stripe

// UsersWP-resolved auth destinations (fall back gracefully if helpers are gone).
$al_forgot_url   = function_exists( 'uwp_get_forgot_page_url' )  ? esc_url( uwp_get_forgot_page_url() )  : esc_url( wp_lostpassword_url() );
$al_login_action = esc_url( get_permalink() ); // post to self — UsersWP process_login() runs on template_redirect

// Post-login destination.
//
// This hidden field DECIDES where a successful login lands: UsersWP's
// get_login_redirect_url() (class-forms.php) checks $_REQUEST['redirect_to']
// FIRST, ahead of its own per-role settings, so whatever is emitted here wins —
// only the 'uwp_login_redirect' filter runs after it
// (ensurance_founding_plan_login_redirect in functions.php, which fires only for
// users carrying a founding-plan meta). This used to be hardcoded to the legacy
// UsersWP account page, which sent every agent WITHOUT that meta to the old
// dashboard; the new agent dashboard is /dashboard/ (page-dashboard.php), so
// that is the default now.
//
// An incoming ?redirect_to= is honored first so the round-trip works: the
// /dashboard/ access guard bounces logged-out visitors to
// /login/?redirect_to=<destination> and expects to get them back there. Run it
// through wp_validate_redirect() — raw request input — so an off-site value
// falls back to the dashboard instead of becoming an open redirect. Note
// wp_validate_redirect() returns an EMPTY string (not the fallback) for empty
// input, and an empty redirect_to would drop us back into UsersWP's own
// defaults — hence the explicit empty checks on both sides of it.
$al_dashboard_url = home_url( '/dashboard/' );
$al_redirect_raw  = isset( $_GET['redirect_to'] ) && is_string( $_GET['redirect_to'] )
	? trim( wp_unslash( $_GET['redirect_to'] ) )
	: '';
$al_redirect_to   = '' !== $al_redirect_raw
	? wp_validate_redirect( $al_redirect_raw, $al_dashboard_url )
	: $al_dashboard_url;
$al_redirect_to   = esc_url( '' !== $al_redirect_to ? $al_redirect_to : $al_dashboard_url );

get_header( 'home' );
?>

<main class="al-page" id="main">

  <!-- ── Login + New agent ──────────────────────────────────────────── -->
  <section class="al-container al-login reveal" id="login" aria-label="Agent sign in">
    <div class="al-login__grid">

      <!-- Returning agent — re-skinned UsersWP login form -->
      <div class="al-card al-login__box">
        <span class="al-eyebrow al-eyebrow--secondary">Returning agent?</span>
        <h2 class="al-card__title">Log in to your dashboard</h2>
        <p class="al-card__lead">Log in to manage your agency profile, review your access status, and check eligible request details when available in your state or service area.</p>

        <?php
		// UsersWP login error / status notices (rendered on failed submit).
		if ( function_exists( 'uwp_get_option' ) ) {
			do_action( 'uwp_template_display_notices', 'login' );
		}
		?>

        <form class="al-form" method="post" action="<?php echo $al_login_action; ?>">
          <div class="al-field">
            <label for="username">Email or username</label>
            <input id="username" name="username" type="text" autocomplete="username" placeholder="you@youragency.com" required />
          </div>
          <div class="al-field">
            <label for="password">Password</label>
            <div class="al-field__pw">
              <input id="password" name="password" type="password" autocomplete="current-password" placeholder="Enter your password" required />
              <button type="button" class="al-pw-toggle" data-target="password" aria-label="Show password" aria-pressed="false">Show</button>
            </div>
          </div>

          <div class="al-form__row">
            <label class="al-check">
              <input type="checkbox" name="remember_me" value="forever" checked />
              <span>Remember me</span>
            </label>
            <a class="al-link" href="<?php echo $al_forgot_url; ?>" data-event="forgot_password_click">Reset your password</a>
          </div>

          <?php // --- UsersWP auth contract: keep these exactly. --- ?>
          <input type="hidden" name="redirect_to" value="<?php echo $al_redirect_to; ?>" />
          <input type="hidden" name="uwp_login_nonce" value="<?php echo esc_attr( wp_create_nonce( 'uwp-login-nonce' ) ); ?>" />

          <?php
          // Cloudflare Turnstile — the SAME bot check UsersWP's stock login form
          // enforces (verify_uwp on uwp_validate_result, gated by the uwp_login
          // protection which is ON). This hand-rolled form does NOT fire
          // uwp_template_fields, so without this the login submit fails with
          // "Security verification missing." Renders just the placeholder; the
          // ayecode-connect site-wide script hydrates + validates it on submit.
          if ( has_action( 'ayecode_verify_turnstile_form_fields' ) ) {
              do_action( 'ayecode_verify_turnstile_form_fields' );
          }
          ?>

          <button type="submit" name="uwp_login_submit" class="al-btn al-btn--solid al-submit">
            Log In to Agent Dashboard <?php echo wp_kses( ensurance_home_icon( 'arrow-right', 18 ), $ensurance_svg_allowed ); ?>
          </button>
        </form>

        <p class="al-card__foot">Forgot your password? <a href="<?php echo $al_forgot_url; ?>">Reset your password here.</a></p>
      </div>

      <!-- New agent -->
      <div class="al-card al-newagent">
        <span class="al-card__bar" aria-hidden="true"></span>
        <div class="al-newagent__head">
          <span class="al-eyebrow al-eyebrow--accent">New to Ensurance?</span>
          <span class="al-badge">Now opening</span>
        </div>
        <h2 class="al-card__title">Start with Founding Agent Access</h2>
        <p class="al-card__lead">Start with Founding Agent Access and create your agency profile. Selected agents in priority states may be eligible for 60 Day Founding Agent Access while Ensurance opens access in selected states.</p>

        <ul class="al-bullets">
          <li><span class="al-bullets__mark" aria-hidden="true"><?php echo wp_kses( ensurance_home_icon( 'check', 13 ), $ensurance_svg_allowed ); ?></span>Create your agency profile on Ensurance</li>
          <li><span class="al-bullets__mark" aria-hidden="true"><?php echo wp_kses( ensurance_home_icon( 'check', 13 ), $ensurance_svg_allowed ); ?></span>Review eligible shopper request details when available</li>
          <li><span class="al-bullets__mark" aria-hidden="true"><?php echo wp_kses( ensurance_home_icon( 'check', 13 ), $ensurance_svg_allowed ); ?></span>Accept or pass — you stay in control</li>
          <li><span class="al-bullets__mark" aria-hidden="true"><?php echo wp_kses( ensurance_home_icon( 'check', 13 ), $ensurance_svg_allowed ); ?></span>No bulk lead buying, no long-term contract</li>
        </ul>

        <div class="al-newagent__cta">
          <a href="<?php echo $al_cta_60day; ?>" class="al-btn al-btn--solid" data-event="login_start_60_day_click">Start 60 Day Access <?php echo wp_kses( ensurance_home_icon( 'arrow-right', 17 ), $ensurance_svg_allowed ); ?></a>
          <a href="<?php echo $al_cta_monthly; ?>" class="al-btn al-btn--outline" data-event="login_join_founding_click">Join as a Founding Agent</a>
        </div>

        <p class="al-fineprint">Availability of shopper requests may vary by state, coverage type, shopper activity, and agent eligibility. Founding Agent Access does not guarantee request volume.</p>
      </div>

    </div>
  </section>

  <!-- ── Support ────────────────────────────────────────────────────── -->
  <section class="al-container al-support reveal" aria-label="Agent support">
    <div class="al-support__card">
      <div class="al-support__text">
        <span class="al-eyebrow al-eyebrow--secondary"><?php echo wp_kses( ensurance_home_icon( 'message', 14 ), $ensurance_svg_allowed ); ?> Agent support</span>
        <h2 class="al-card__title">Need help accessing your account?</h2>
        <p class="al-card__lead">Contact Agent Support for help with login, account access, agency profile updates, or subscription questions.</p>
      </div>
      <div class="al-support__action">
        <a href="<?php echo $al_contact_url; ?>" class="al-btn al-btn--outline" data-event="contact_agent_support_click">Contact Agent Support <?php echo wp_kses( ensurance_home_icon( 'arrow-right', 17 ), $ensurance_svg_allowed ); ?></a>
      </div>
    </div>
  </section>

  <!-- ── Footer CTA (new agents not ready to log in) ────────────────── -->
  <section class="al-container al-cta reveal" aria-label="Founding Agent Access">
    <div class="al-cta__card">
      <span class="al-cta__bar" aria-hidden="true"></span>
      <span class="al-eyebrow al-eyebrow--accent">Not ready to log in?</span>
      <h2 class="al-cta__title">See how Founding Agent Access works.</h2>
      <p class="al-cta__lead">Review organized shopper requests without bulk lead buying. Create your agency profile, preview eligible requests, and decide when an opportunity fits your agency.</p>
      <div class="al-cta__actions">
        <a href="<?php echo $al_cta_60day; ?>" class="al-btn al-btn--solid" data-event="footer_start_60_day_click">Start 60 Day Access <?php echo wp_kses( ensurance_home_icon( 'arrow-right', 18 ), $ensurance_svg_allowed ); ?></a>
        <a href="<?php echo $al_agents_url; ?>" class="al-btn al-btn--ghost" data-event="footer_learn_more_click">Learn how it works</a>
      </div>
      <p class="al-fineprint al-fineprint--center">Availability of shopper requests may vary by state, coverage type, shopper activity, and agent eligibility. Founding Agent Access does not guarantee request volume.</p>
    </div>
  </section>

  <!-- ── Trust note (final section) ─────────────────────────────────── -->
  <section class="al-container al-trust reveal" aria-label="Trust">
    <div class="al-callout">
      <span class="al-callout__icon" aria-hidden="true"><?php echo wp_kses( ensurance_home_icon( 'shield-check', 20 ), $ensurance_svg_allowed ); ?></span>
      <div>
        <p class="al-callout__title">Not bulk lead buying</p>
        <p class="al-callout__body">Ensurance is not a bulk lead seller or quote comparison site. We are building a more structured way for insurance shoppers and agents to connect around organized quote requests.</p>
      </div>
    </div>
  </section>

</main>

<?php get_footer( 'home' ); ?>
