<?php
/**
 * Header — private investor brief.
 *
 * Minimal chrome: wordmark + "Private investor brief" badge + request CTA.
 * No site nav on purpose; the page is private, noindex/nofollow.
 */

$ib_header = array(
    'badge' => 'Private investor brief',
    'cta'   => 'Request materials',
);
?>
<!DOCTYPE html>
<html <?php language_attributes(); ?>>
<head>
    <meta charset="<?php bloginfo('charset'); ?>">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="robots" content="noindex, nofollow">
    <meta name="googlebot" content="noindex, nofollow">
    <?php wp_head(); ?>
</head>
<body <?php body_class(); ?>>
<?php wp_body_open(); ?>

<a class="skip-link" href="#main">Skip to content</a>

<header class="ib-header" role="banner">
    <div class="ib-shell ib-header__inner">
        <div class="ib-header__brand">
            <a class="ib-logo" href="/" aria-label="Ensurance home">
                <img src="<?php echo esc_url( get_stylesheet_directory_uri() . '/assets/images/logo-colored.png' ); ?>" alt="Ensurance.com">
            </a>
            <span class="ib-badge ib-badge--status"><span class="ib-badge__dot" aria-hidden="true"></span><?php echo esc_html( $ib_header['badge'] ); ?></span>
        </div>
        <button type="button" class="ib-btn ib-btn--primary ib-btn--sm" data-scroll-to="request-materials" data-event="header_request_materials_click">
            <?php echo esc_html( $ib_header['cta'] ); ?>
        </button>
    </div>
</header>
