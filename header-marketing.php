<!DOCTYPE html>
<html <?php language_attributes(); ?>>
<head>
    <meta charset="<?php bloginfo('charset'); ?>">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <?php wp_head(); ?>
</head>
<body <?php body_class(); ?>>
<?php wp_body_open(); ?>

<?php
/**
 * Primary nav items.
 *
 * Routes match the canonical homepage routes in components/_homepage-data.php.
 * TODO: pages for /how-it-works, /coverage, /trust-and-privacy, /for-agents,
 * /start do not exist yet (Phase 3 — Homepage Build). Links will resolve to
 * 404 until those templates ship.
 */
$primary_nav = array(
    array( 'label' => 'How it works',      'href' => '/how-it-works' ),
    array( 'label' => 'Coverage types',    'href' => '/coverage' ),
    array( 'label' => 'Trust and privacy', 'href' => '/trust-and-privacy' ),
    array( 'label' => 'For agents',        'href' => '/for-agents' ),
);
?>

<header class="site-header" role="banner">
    <div class="site-header__inner">

        <a class="site-header__brand" href="<?php echo esc_url( home_url( '/' ) ); ?>" aria-label="Ensurance home">
            <span class="site-header__brand-mark" aria-hidden="true">E</span>
            <span class="site-header__brand-text">Ensurance</span>
        </a>

        <button type="button"
                class="site-header__mobile-toggle"
                aria-controls="primary-nav"
                aria-expanded="false"
                aria-label="Open navigation">
            <span class="site-header__mobile-toggle-bar" aria-hidden="true"></span>
            <span class="site-header__mobile-toggle-bar" aria-hidden="true"></span>
            <span class="site-header__mobile-toggle-bar" aria-hidden="true"></span>
        </button>

        <nav id="primary-nav" class="site-header__nav" aria-label="Primary navigation">
            <?php foreach ( $primary_nav as $item ) : ?>
                <a class="site-header__nav-link" href="<?php echo esc_url( home_url( $item['href'] ) ); ?>">
                    <?php echo esc_html( $item['label'] ); ?>
                </a>
            <?php endforeach; ?>

            <a class="site-header__cta"
               href="<?php echo esc_url( home_url( '/start' ) ); ?>"
               data-event="nav_start_request_click">Start your request</a>
        </nav>

    </div>
</header>
