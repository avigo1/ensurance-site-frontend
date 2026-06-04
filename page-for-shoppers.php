<?php
/**
 * Template Name: For Shoppers (Marketing)
 *
 * /for-shoppers — Shopper-facing overview. Explains the controlled request
 * experience, what makes Ensurance different from a quote-comparison or
 * lead-marketplace site, and how shopper control and privacy work in practice.
 *
 * Reuses the marketing component set. Section copy is the single source of
 * truth in components/_for-shoppers-data.php.
 *
 * Workbook compliance:
 *   - Direct-answer (AI-search) block renders within the first 150 words
 *     of visible content (right after the hero).
 *   - WebPage, BreadcrumbList, and FAQPage JSON-LD emitted in wp_head.
 *   - All CTAs carry data-event attributes for tracking via marketing.js.
 *   - Approved positioning language; no instant-quote or guarantee claims.
 */

$for_shoppers = require __DIR__ . '/components/_for-shoppers-data.php';

// Page-specific <title> and metadata. Filters bail on non-target pages.
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
    foreach ( $for_shoppers['faq'] as $item ) {
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
    // 1 — Hero (two-column with shopper-journey flow card)
    $hero = $for_shoppers['hero'];
    include __DIR__ . '/components/hero.php';

    // 2 — Direct answer / AI-search canonical block (within first 150 words)
    $direct_answer = $for_shoppers['direct_answer'];
    include __DIR__ . '/components/direct-answer.php';

    // 3 — The problem (3 reframed cards under a centered section header)
    $proof_header = $for_shoppers['problem'];
    $proof        = $for_shoppers['problem']['cards'];
    include __DIR__ . '/components/proof-strip.php';

    // 4 — What Ensurance does (two-column copy + numbered cards on right)
    $one_request = $for_shoppers['what_we_do'];
    include __DIR__ . '/components/one-request.php';

    // 5 — What happens next (4-step process grid)
    $process = $for_shoppers['steps'];
    include __DIR__ . '/components/process.php';

    // 6 — Shopper control (copy + bullet card)
    $human_help = $for_shoppers['shopper_control'];
    include __DIR__ . '/components/human-help.php';

    // 7 — Real, licensed humans (navy band, copy + trust panel)
    $protected = $for_shoppers['protected'];
    include __DIR__ . '/components/protected.php';

    // 8 — No quote chaos (what Ensurance is not + instead reframe)
    $not_section = $for_shoppers['not_section'];
    include __DIR__ . '/components/what-ensurance-is-not.php';

    // 9 — Privacy (3 trust cards under a centered section header + footnote)
    $proof_header = $for_shoppers['privacy'];
    $proof        = $for_shoppers['privacy']['cards'];
    include __DIR__ . '/components/proof-strip.php';

    // 10 — FAQ
    $faq_intro = $for_shoppers['faq_intro'];
    $faq       = $for_shoppers['faq'];
    include __DIR__ . '/components/faq.php';

    // 11 — Final CTA
    $final_cta = $for_shoppers['final_cta'];
    include __DIR__ . '/components/final-cta.php';
    ?>

</main>

<?php get_footer( 'marketing' ); ?>
