<?php
/**
 * Template Name: Coverage Types (Marketing)
 *
 * /coverage — Hub for shoppers to choose a guided request path by coverage
 * type. Reuses the marketing component set; no page-specific markup beyond
 * the JSON-LD blocks emitted in wp_head.
 *
 * Section copy lives in components/_coverage-data.php (single source of truth).
 */

$coverage_page = require __DIR__ . '/components/_coverage-data.php';

// Page-specific <title> and meta description.
add_filter( 'pre_get_document_title', function ( $title ) use ( $coverage_page ) {
    return $coverage_page['meta']['title'];
} );

add_action( 'wp_head', function () use ( $coverage_page ) {
    echo '<meta name="description" content="' . esc_attr( $coverage_page['meta']['description'] ) . '">' . "\n";
    echo '<link rel="canonical" href="' . esc_url( home_url( $coverage_page['meta']['canonical'] ) ) . '">' . "\n";

    // JSON-LD: FAQPage, BreadcrumbList, ItemList (coverage types).
    // Mirrors the schema plan from the reference build so AI search and
    // rich-result eligibility match the rest of the site.
    $faq_entities = array();
    foreach ( $coverage_page['faq'] as $item ) {
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
                'name'     => 'Coverage Types',
                'item'     => home_url( '/coverage' ),
            ),
        ),
    );

    $coverage_items = array();
    foreach ( $coverage_page['coverage']['cards'] as $i => $card ) {
        $coverage_items[] = array(
            '@type'    => 'ListItem',
            'position' => $i + 1,
            'name'     => $card['title'],
            'url'      => home_url( $card['href'] ),
        );
    }
    $itemlist_schema = array(
        '@context'        => 'https://schema.org',
        '@type'           => 'ItemList',
        'name'            => 'Ensurance Coverage Types',
        'itemListElement' => $coverage_items,
    );

    $json_flags = JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE;
    echo '<script type="application/ld+json">' . wp_json_encode( $faq_schema, $json_flags ) . '</script>' . "\n";
    echo '<script type="application/ld+json">' . wp_json_encode( $breadcrumb_schema, $json_flags ) . '</script>' . "\n";
    echo '<script type="application/ld+json">' . wp_json_encode( $itemlist_schema, $json_flags ) . '</script>' . "\n";
}, 2 );

get_header( 'marketing' );
?>

<main class="page-coverage" id="main">

    <?php
    // 1 — Hero (left copy + right flow-card preview)
    $hero = $coverage_page['hero'];
    include __DIR__ . '/components/hero.php';

    // 2 — Proof strip (three-card value summary)
    $proof = $coverage_page['proof'];
    include __DIR__ . '/components/proof-strip.php';

    // 3 — Coverage request paths (6 coverage cards)
    $coverage = $coverage_page['coverage'];
    include __DIR__ . '/components/coverage.php';

    // 4 — Start with what you know (two-column, sticky copy + stacked cards)
    $one_request = $coverage_page['start_with_what_you_know'];
    include __DIR__ . '/components/one-request.php';

    // 5 — How it works (4-step process grid)
    $process = $coverage_page['process'];
    include __DIR__ . '/components/process.php';

    // 6 — A more organized way to begin (2x2 trust card grid)
    $difference = $coverage_page['difference'];
    include __DIR__ . '/components/difference.php';

    // 7 — Trust and privacy (navy band)
    $protected = $coverage_page['protected'];
    include __DIR__ . '/components/protected.php';

    // 8 — Prepared for licensed review (agent card)
    $agent_review = $coverage_page['agent_review'];
    include __DIR__ . '/components/agent-review.php';

    // 9 — FAQ
    $faq_intro = $coverage_page['faq_intro'];
    $faq       = $coverage_page['faq'];
    include __DIR__ . '/components/faq.php';

    // 10 — Final CTA
    $final_cta = $coverage_page['final_cta'];
    include __DIR__ . '/components/final-cta.php';
    ?>

</main>

<?php get_footer( 'marketing' ); ?>
