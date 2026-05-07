<!DOCTYPE html>
<html <?php language_attributes(); ?>>
<head>
    <meta charset="<?php bloginfo('charset'); ?>">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <?php wp_head(); ?>
</head>
<body <?php body_class(); ?>>
<?php wp_body_open(); ?>

<header class="site-header">
    <div class="site-header__inner">

        <a href="<?php echo esc_url(home_url('/')); ?>" class="site-header__logo">
            <img src="<?php echo esc_url(get_stylesheet_directory_uri()); ?>/assets/images/logo-icon.svg"
                 alt=""
                 class="site-header__logo-icon"
                 width="36"
                 height="36">
            <span class="site-header__logo-text">Ensurance</span>
        </a>

        <div class="site-header__right">
            <nav class="site-header__nav" aria-label="Main navigation">
                <ul>
                    <li><a href="#">How it works</a></li>
                    <li><a href="#">Coverage types</a></li>
                </ul>
            </nav>

            <a href="#" class="site-header__cta-btn">
                Get matched
            </a>
        </div>

        <button class="site-header__mobile-toggle"
                aria-label="Open menu"
                aria-expanded="false"
                aria-controls="mobile-nav">
            <span></span><span></span><span></span>
        </button>

    </div>

    <nav class="site-header__mobile-nav" id="mobile-nav" aria-label="Mobile navigation" aria-hidden="true">
        <ul>
            <li><a href="#">How it works</a></li>
            <li><a href="#">Coverage types</a></li>
            <li class="mobile-cta">
                <a href="#" class="site-header__cta-btn">Get matched</a>
            </li>
        </ul>
    </nav>
</header>
