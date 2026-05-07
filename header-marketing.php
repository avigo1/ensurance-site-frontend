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
            <img src="<?php echo esc_url(get_stylesheet_directory_uri()); ?>/assets/images/logo.png"
                 alt="<?php bloginfo('name'); ?>"
                 height="36">
            <span class="site-header__tagline">SERVING NATIONWIDE.</span>
        </a>

        <nav class="site-header__nav" aria-label="Main navigation">
            <ul>
                <li class="has-dropdown">
                    <button class="site-header__dropdown-toggle"
                            aria-expanded="false"
                            aria-controls="dropdown-for-you">
                        For You
                        <svg class="site-header__chevron" width="14" height="14" viewBox="0 0 14 14" fill="none" aria-hidden="true">
                            <path d="M3 5l4 4 4-4" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"/>
                        </svg>
                    </button>
                    <ul class="site-header__dropdown" id="dropdown-for-you" role="menu">
                        <li><a href="<?php echo esc_url(home_url('/auto-insurance/')); ?>" role="menuitem">Auto Insurance</a></li>
                        <li><a href="<?php echo esc_url(home_url('/home-insurance/')); ?>" role="menuitem">Home Insurance</a></li>
                        <li><a href="<?php echo esc_url(home_url('/life-insurance/')); ?>" role="menuitem">Life Insurance</a></li>
                        <li><a href="<?php echo esc_url(home_url('/health-insurance/')); ?>" role="menuitem">Health Insurance</a></li>
                    </ul>
                </li>
                <li><a href="<?php echo esc_url(home_url('/how-it-works/')); ?>">How it works</a></li>
                <li><a href="<?php echo esc_url(home_url('/why-ensurance/')); ?>">Why Ensurance</a></li>
                <li><a href="<?php echo esc_url(home_url('/faq/')); ?>">FAQ</a></li>
            </ul>
        </nav>

        <div class="site-header__cta">
            <a href="<?php echo esc_url(home_url('/agent-login/')); ?>" class="btn btn--dark">
                Agent login
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
            <li class="mobile-section-label"><span>For You</span></li>
            <li class="sub-item"><a href="<?php echo esc_url(home_url('/auto-insurance/')); ?>">Auto Insurance</a></li>
            <li class="sub-item"><a href="<?php echo esc_url(home_url('/home-insurance/')); ?>">Home Insurance</a></li>
            <li class="sub-item"><a href="<?php echo esc_url(home_url('/life-insurance/')); ?>">Life Insurance</a></li>
            <li class="sub-item"><a href="<?php echo esc_url(home_url('/health-insurance/')); ?>">Health Insurance</a></li>
            <li><a href="<?php echo esc_url(home_url('/how-it-works/')); ?>">How it works</a></li>
            <li><a href="<?php echo esc_url(home_url('/why-ensurance/')); ?>">Why Ensurance</a></li>
            <li><a href="<?php echo esc_url(home_url('/faq/')); ?>">FAQ</a></li>
            <li class="mobile-cta">
                <a href="<?php echo esc_url(home_url('/agent-login/')); ?>" class="btn btn--dark">Agent login</a>
            </li>
        </ul>
    </nav>
</header>
