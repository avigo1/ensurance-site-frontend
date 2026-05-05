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
                 width="160">
        </a>

        <nav class="site-header__nav" aria-label="Main navigation">
            <ul>
                <li><a href="<?php echo esc_url(home_url('/')); ?>">Home</a></li>
                <li><a href="<?php echo esc_url(home_url('/insurance-agencies/')); ?>">Find an Agent</a></li>
                <li><a href="<?php echo esc_url(home_url('/blog/')); ?>">Blog</a></li>
            </ul>
        </nav>

        <div class="site-header__cta">
            <a href="<?php echo esc_url(home_url('/get-a-quote/')); ?>" class="btn btn--primary">
                Get a Free Quote
            </a>
        </div>

        <button class="site-header__mobile-toggle" aria-label="Open menu" aria-expanded="false">
            <span></span><span></span><span></span>
        </button>

    </div>
</header>
