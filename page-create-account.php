<?php
/**
 * /create-account — Create Your Account (Calm Intelligence redesign).
 *
 * A STANDALONE, self-contained auth screen: a single centered card on a
 * full-viewport page, with its own logo — deliberately no site header, no
 * footer, nothing competing with finishing the form (matches the approved
 * design). Because it renders no site chrome, this template opens AND closes
 * the whole HTML document itself (own <!DOCTYPE>, wp_head(), wp_footer()); it
 * does NOT call get_header()/get_footer(). It therefore never includes
 * Kadence's #wrapper / .entry-content-wrap, which is what keeps it clear of the
 * global customizer heading/line-height overrides that the chrome-wrapped pages
 * have to fight.
 *
 * Renders via the page-{slug}.php hierarchy for the "create-account" page, so a
 * WordPress page with slug "create-account" picks it up automatically — no
 * page-template meta needed (same slug-based pattern as page-login.php).
 *
 * Chrome/tokens: assets/home.css supplies the "Calm Intelligence" tokens, fonts
 * and base; assets/create-account.css layers the card, and
 * assets/create-account.js drives the password show/hide. All three are
 * enqueued and isolated in functions.php (ensurance_create_account_assets),
 * scoped to this page only.
 *
 * AUTHENTICATION — the important part.
 *   The sign-up form is re-skinned but registration is NOT rebuilt. The form
 *   posts to itself with the EXACT UsersWP register contract, so UsersWP's
 *   process_register() (hooked on template_redirect) handles it unchanged:
 *   validation, user creation, activation/redirect all keep working. The
 *   designer already named the visible fields to match UsersWP, so they map 1:1.
 *     - first_name / last_name / username / email                → UsersWP fields
 *     - password / confirm_password                              → UsersWP fields
 *     - uwp_register_form_id = 1                                 → default form
 *     - uwp_register_nonce  = wp_create_nonce('uwp-register-nonce-1')
 *     - uwp_register_hash   = substr(hash('SHA256', AUTH_KEY.site_url()),0,25)
 *     - uwp_register_hp     = ""   (honeypot — must stay empty)
 *     - redirect_to         = post-register destination (account page)
 *     - uwp_register_submit = submit button name
 *   Registration errors surface through UsersWP's own notices via
 *   do_action('uwp_template_display_notices','register'). "Sign in" routes to
 *   the existing /login page (page-login.php).
 *
 * SEO: title / meta description / canonical / robots are owned by Yoast and
 * emitted through wp_head(); this template outputs none of them.
 */

// --- Resolved destinations (use the site's real slugs / UsersWP helpers). ---
$ca_home_url    = esc_url( home_url( '/' ) );
$ca_login_url   = esc_url( home_url( '/login' ) );
$ca_terms_url   = esc_url( home_url( '/terms-of-use' ) );
$ca_privacy_url = esc_url( home_url( '/privacy-policy' ) );
$ca_logo        = esc_url( get_stylesheet_directory_uri() . '/assets/images/logo-colored.png' );

// UsersWP register contract values (mirror class-templates.php exactly).
$ca_form_id     = 1;
$ca_action      = esc_url( get_permalink() ); // post to self — process_register() runs on template_redirect
$ca_hash        = substr( hash( 'SHA256', AUTH_KEY . site_url() ), 0, 25 );
$ca_redirect_to = function_exists( 'uwp_get_account_page_url' ) ? esc_url( uwp_get_account_page_url() ) : $ca_home_url;
?>
<!DOCTYPE html>
<html <?php language_attributes(); ?>>
<head>
<meta charset="<?php bloginfo( 'charset' ); ?>">
<meta name="viewport" content="width=device-width, initial-scale=1">
<link rel="preconnect" href="https://fonts.googleapis.com">
<link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
<?php wp_head(); ?>
</head>
<body <?php body_class(); ?>>
<?php wp_body_open(); ?>
<a class="skip-link" href="#main">Skip to main content</a>

<main class="ca-page" id="main">
  <div class="ca-card">

    <div class="ca-card__head">
      <a class="ca-brand" href="<?php echo $ca_home_url; ?>" aria-label="Ensurance home">
        <img src="<?php echo $ca_logo; ?>" alt="Ensurance" class="ca-brand__logo" />
      </a>
      <h1 class="ca-card__title">Create your account</h1>
      <p class="ca-card__sub">Sign up to start and manage your quote requests</p>
    </div>

    <?php
    // UsersWP registration error / status notices (rendered on failed submit).
    if ( function_exists( 'uwp_get_option' ) ) {
        do_action( 'uwp_template_display_notices', 'register' );
    }
    ?>

    <form class="ca-form" method="post" action="<?php echo $ca_action; ?>" enctype="multipart/form-data">

      <div class="ca-form__names">
        <div class="ca-field">
          <label for="first_name">First name</label>
          <input id="first_name" name="first_name" type="text" autocomplete="given-name" placeholder="Jane" required />
        </div>
        <div class="ca-field">
          <label for="last_name">Last name</label>
          <input id="last_name" name="last_name" type="text" autocomplete="family-name" placeholder="Doe" required />
        </div>
      </div>

      <div class="ca-field">
        <label for="username">Username</label>
        <input id="username" name="username" type="text" autocomplete="username" placeholder="janedoe" required />
      </div>

      <div class="ca-field">
        <label for="email">Email</label>
        <input id="email" name="email" type="email" autocomplete="email" placeholder="you@example.com" required />
      </div>

      <div class="ca-field">
        <label for="password">Password</label>
        <div class="ca-field__pw">
          <input id="password" name="password" type="password" autocomplete="new-password" placeholder="Enter your password" required />
          <button type="button" class="ca-pw-toggle" data-targets="password confirm_password" aria-label="Show password" aria-pressed="false">Show</button>
        </div>
      </div>

      <div class="ca-field">
        <label for="confirm_password">Confirm password</label>
        <input id="confirm_password" name="confirm_password" type="password" autocomplete="new-password" placeholder="Re-enter your password" required />
      </div>

      <?php // --- UsersWP register contract: keep these exactly. --- ?>
      <input type="hidden" name="redirect_to" value="<?php echo $ca_redirect_to; ?>" />
      <input type="hidden" name="uwp_register_hash" value="<?php echo esc_attr( $ca_hash ); ?>" style="display:none !important; visibility:hidden !important;" />
      <input type="hidden" name="uwp_register_hp" value="" style="display:none !important; visibility:hidden !important;" size="25" autocomplete="off" />
      <input type="hidden" name="uwp_register_nonce" value="<?php echo esc_attr( wp_create_nonce( 'uwp-register-nonce-' . $ca_form_id ) ); ?>" />
      <input type="hidden" name="uwp_register_form_id" value="<?php echo (int) $ca_form_id; ?>" />

      <button type="submit" name="uwp_register_submit" class="ca-btn ca-submit">Create account</button>
    </form>

    <p class="ca-alt">Already have an account? <a href="<?php echo $ca_login_url; ?>">Sign in</a></p>

    <p class="ca-legal">By creating an account you agree to the
      <a href="<?php echo $ca_terms_url; ?>">Terms of Use</a> and
      <a href="<?php echo $ca_privacy_url; ?>">Privacy Policy</a>.
    </p>

  </div>
</main>

<?php wp_footer(); ?>
</body>
</html>
