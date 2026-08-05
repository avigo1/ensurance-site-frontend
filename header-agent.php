<?php
/**
 * Marketing header — AGENT SIDE.
 *
 * The site has two headers. Keep them separate:
 *
 *   header-home.php  → SHOPPER side. Homepage, coverage pages, quote/insurance
 *                      forms. Carries the shopper nav and the "Start My Auto
 *                      Quote Request" CTA.
 *   header-agent.php → AGENT side (this file). Agency sign-up, login, dashboard
 *                      and account management. Currently used by
 *                      page-publish-your-agency.php.
 *
 * Deliberately logo-only: no nav, no CTA, no buttons. These are focused
 * account/sign-up flows, and nothing in the header should compete with
 * finishing the form. When agent nav is wanted later, add it here — that is the
 * point of this file existing — and it will not touch the shopper header.
 *
 * Pairs with get_footer('home'), which closes </body></html>. This file must
 * therefore open the full document, exactly as header-home.php does.
 *
 * Styling comes from assets/home.css: the .site-header / .container /
 * .header-inner / .brand classes are shared with the shopper header so both
 * bars are the same height and treatment. .site-header--agent is the hook for
 * any agent-only tweaks.
 *
 * assets/home.js is null-guarded around .nav-toggle / #primary-nav, so omitting
 * them here is safe.
 *
 * SEO note: title / meta description / canonical / robots are owned by Yoast
 * and emitted through wp_head(). This file intentionally outputs none of them.
 * GTM is injected site-wide from functions.php via wp_head / wp_body_open.
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

<header class="site-header site-header--agent" aria-label="Ensurance">
  <div class="container header-inner">
    <a class="brand" href="<?php echo esc_url( home_url( '/' ) ); ?>" aria-label="Ensurance.com homepage">
      <img src="<?php echo esc_url( get_stylesheet_directory_uri() . '/assets/images/logo-colored.png' ); ?>" alt="Ensurance.com" class="brand-logo-image" />
    </a>
  </div>
</header>
