<?php
/**
 * Marketing header — Homepage (auto-forward design).
 *
 * Self-contained chrome for the homepage only, ported verbatim from the
 * bespoke package's includes/navigation.php + index.php <head>. Called via
 * get_header('home') from page-home.php so it does NOT affect the shared
 * header-marketing.php used by /coverage, /for-shoppers, etc.
 *
 * SEO note: title / meta description / canonical / robots are owned by Yoast
 * (active SEO plugin) and emitted through wp_head(). This file intentionally
 * outputs none of them, to avoid double output. GTM (GTM-5GRHH8LL) is injected
 * site-wide from functions.php via wp_head / wp_body_open.
 */
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

<header class="site-header" aria-label="Primary navigation">
  <div class="container header-inner">
    <a class="brand" href="<?php echo esc_url( home_url( '/' ) ); ?>" aria-label="Ensurance.com homepage">
      <img src="<?php echo esc_url( get_stylesheet_directory_uri() . '/assets/images/simple_navy_logo_with_registered_trademark.png' ); ?>" alt="Ensurance.com" class="brand-logo-image" />
    </a>
    <button class="nav-toggle" type="button" aria-expanded="false" aria-controls="primary-nav">
      <span class="sr-only">Open navigation</span>
      <span></span><span></span><span></span>
    </button>
    <nav id="primary-nav" class="primary-nav" aria-label="Main navigation">
      <a href="<?php echo esc_url( home_url( '/how-it-works' ) ); ?>">How it works</a>
      <a href="<?php echo esc_url( home_url( '/coverage' ) ); ?>">Coverage types</a>
      <a href="<?php echo esc_url( home_url( '/trust-center' ) ); ?>">Trust Center</a>
      <a href="<?php echo esc_url( home_url( '/for-agents' ) ); ?>">For agents</a>
      <a class="nav-cta" href="<?php echo esc_url( home_url( '/auto-insurance-quote' ) ); ?>" data-track="cta_click_start_auto_quote_request" data-cta-text="Start My Auto Quote Request" data-page-type="homepage">Start My Auto Quote Request</a>
    </nav>
  </div>
</header>
