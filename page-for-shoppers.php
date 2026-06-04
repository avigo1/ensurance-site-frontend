<?php
/**
 * Template Name: For Shoppers (Marketing)
 *
 * /for-shoppers — Shopper-facing overview. Mirrors the Figma layout:
 * centered hero, problem framing, In-short answer card, value cards,
 * what-happens-next step row, shopper control checklist, real-help quote
 * callout, comparison (✕ vs ✓), privacy cards, FAQ list, dark CTA band.
 *
 * Each section is its own partial under /components/. Copy lives in
 * components/_for-shoppers-data.php (single source of truth).
 *
 * Workbook compliance:
 *   - "In short" answer card renders within the first 150 words of visible
 *     content (third section).
 *   - WebPage, BreadcrumbList, and FAQPage JSON-LD emitted in wp_head.
 *   - All CTAs carry data-event attributes for tracking via marketing.js.
 *   - Approved positioning language only; no instant-quote / guarantee claims.
 */

$for_shoppers = require __DIR__ . '/components/_for-shoppers-data.php';

add_filter( 'pre_get_document_title', function ( $title ) use ( $for_shoppers ) {
    return $for_shoppers['meta']['title'];
} );

add_action( 'wp_head', function () use ( $for_shoppers ) {
    $meta      = $for_shoppers['meta'];
    $canonical = home_url( $meta['canonical'] );

    echo '<meta name="description" content="' . esc_attr( $meta['description'] ) . '">' . "\n";
    echo '<meta name="robots" content="index, follow">' . "\n";
    echo '<link rel="canonical" href="' . esc_url( $canonical ) . '">' . "\n";

    // Open Graph
    echo '<meta property="og:title" content="' . esc_attr( $meta['og_title'] ) . '">' . "\n";
    echo '<meta property="og:description" content="' . esc_attr( $meta['og_desc'] ) . '">' . "\n";
    echo '<meta property="og:type" content="website">' . "\n";
    echo '<meta property="og:url" content="' . esc_url( $canonical ) . '">' . "\n";

    // Twitter
    echo '<meta name="twitter:card" content="summary_large_image">' . "\n";
    echo '<meta name="twitter:title" content="' . esc_attr( $meta['og_title'] ) . '">' . "\n";
    echo '<meta name="twitter:description" content="' . esc_attr( $meta['og_desc'] ) . '">' . "\n";

    // JSON-LD: WebPage, BreadcrumbList, FAQPage. Schema content mirrors
    // visible page content exactly so claims stay consistent across surfaces.
    $webpage_schema = array(
        '@context'    => 'https://schema.org',
        '@type'       => 'WebPage',
        '@id'         => $canonical . '#webpage',
        'url'         => $canonical,
        'name'        => $meta['title'],
        'description' => $meta['description'],
        'inLanguage'  => 'en-US',
        'isPartOf'    => array(
            '@type' => 'WebSite',
            'name'  => 'Ensurance',
            'url'   => home_url( '/' ),
        ),
    );

    $breadcrumb_schema = array(
        '@context'        => 'https://schema.org',
        '@type'           => 'BreadcrumbList',
        'itemListElement' => array(
            array(
                '@type'    => 'ListItem',
                'position' => 1,
                'name'     => 'Home',
                'item'     => home_url( '/' ),
            ),
            array(
                '@type'    => 'ListItem',
                'position' => 2,
                'name'     => 'For Shoppers',
                'item'     => $canonical,
            ),
        ),
    );

    $faq_entities = array();
    foreach ( $for_shoppers['faq_section']['items'] as $item ) {
        $faq_entities[] = array(
            '@type'          => 'Question',
            'name'           => $item['question'],
            'acceptedAnswer' => array(
                '@type' => 'Answer',
                'text'  => $item['answer'],
            ),
        );
    }
    $faq_schema = array(
        '@context'   => 'https://schema.org',
        '@type'      => 'FAQPage',
        'mainEntity' => $faq_entities,
    );

    $json_flags = JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE;
    echo '<script type="application/ld+json">' . wp_json_encode( $webpage_schema, $json_flags ) . '</script>' . "\n";
    echo '<script type="application/ld+json">' . wp_json_encode( $breadcrumb_schema, $json_flags ) . '</script>' . "\n";
    echo '<script type="application/ld+json">' . wp_json_encode( $faq_schema, $json_flags ) . '</script>' . "\n";
}, 2 );

get_header( 'marketing' );
?>

<main class="page-for-shoppers" id="main">

    <?php
    // 1 — Hero (centered, pill eyebrow, dual CTAs, trust dots)
    $hero = $for_shoppers['hero'];
    include __DIR__ . '/components/hero-centered.php';

    // 2 — The problem (centered intro + 3 plain cards)
    $problem = $for_shoppers['problem'];
    include __DIR__ . '/components/problem-cards.php';

    // 3 — In short (bordered answer card — AI-search direct answer)
    $answer = $for_shoppers['answer'];
    include __DIR__ . '/components/answer-card.php';

    // 4 — What Ensurance does (centered intro + 3 numbered value cards)
    $value = $for_shoppers['value'];
    include __DIR__ . '/components/value-cards.php';

    // 5 — What happens next (centered intro + 4-step row)
    $steps_section = $for_shoppers['steps'];
    include __DIR__ . '/components/steps-row.php';

    // 6 — Shopper control (copy + checklist card, two-col)
    $control = $for_shoppers['control'];
    include __DIR__ . '/components/control-checklist.php';

    // 7 — Real, licensed humans (quote card + copy, two-col)
    $callout = $for_shoppers['callout'];
    include __DIR__ . '/components/quote-callout.php';

    // 8 — No quote chaos (centered intro + ✕/✓ comparison)
    $comparison = $for_shoppers['comparison'];
    include __DIR__ . '/components/comparison.php';

    // 9 — Privacy (centered intro + 3 icon cards + footnote)
    $privacy = $for_shoppers['privacy'];
    include __DIR__ . '/components/privacy-cards.php';

    // 10 — FAQ (centered intro + stacked Q/A list)
    $faq_section = $for_shoppers['faq_section'];
    include __DIR__ . '/components/faq-stacked.php';

    // 11 — Final CTA (full-bleed dark band)
    $cta_band = $for_shoppers['cta_band'];
    include __DIR__ . '/components/final-cta-band.php';
    ?>

</main>

<?php get_footer( 'marketing' ); ?>
