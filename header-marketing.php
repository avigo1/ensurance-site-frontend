<!DOCTYPE html>
<html <?php language_attributes(); ?>>
<head>
    <meta charset="<?php bloginfo('charset'); ?>">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <?php wp_head(); ?>
</head>
<body <?php body_class(); ?>>
<?php wp_body_open(); ?>

<header class="site-header" role="banner">
    <div class="site-header__pill">

        <!-- LEFT — Logo + wordmark -->
        <button type="button" class="site-header__logo" aria-label="Ensurance home">
            <span class="site-header__logo-mark" aria-hidden="true"></span>
            <span class="site-header__logo-text">Ensurance</span>
        </button>

        <!-- MIDDLE — Nav links (stretches to fill, matches Figma "Links" frame with horizontal: fill) -->
        <div class="site-header__links">
            <button type="button" class="site-header__link">How it works</button>
            <button type="button" class="site-header__link" aria-haspopup="menu" aria-expanded="false">
                <span>Coverage</span>
                <svg class="site-header__link-chevron" viewBox="0 0 10 10" fill="none" aria-hidden="true">
                    <path d="M2.5 3.75L5 6.25L7.5 3.75" stroke="currentColor" stroke-width="1.25" stroke-linecap="round" stroke-linejoin="round"/>
                </svg>
            </button>
            <button type="button" class="site-header__link">Why Ensurance</button>
            <button type="button" class="site-header__link">For agents</button>
            <button type="button" class="site-header__link">FAQ</button>
        </div>

        <!-- RIGHT — Agent Login + Get Matched CTA -->
        <button type="button" class="site-header__agent-login">Agent Login</button>
        <button type="button" class="site-header__cta">Get Matched</button>

    </div>
</header>
