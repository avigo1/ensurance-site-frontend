<?php
/**
 * Template Name: How It Works (Marketing)
 *
 * /how-it-works — Explains the controlled request process.
 * Reuses hero/process/faq/final-cta from the homepage component set.
 * New section partials: direct-answer, human-help, what-ensurance-is-not.
 *
 * Section copy lives in components/_how-it-works-data.php.
 */

$how = require __DIR__ . '/components/_how-it-works-data.php';

// Page-specific <title> and meta description. Filters bail on non-target pages.
add_filter( 'pre_get_document_title', function ( $title ) use ( $how ) {
    return $how['meta']['title'];
} );
add_action( 'wp_head', function () use ( $how ) {
    echo '<meta name="description" content="' . esc_attr( $how['meta']['description'] ) . '">' . "\n";
    echo '<link rel="canonical" href="' . esc_url( home_url( $how['meta']['canonical'] ) ) . '">' . "\n";
}, 2 );

get_header( 'marketing' );
?>

<main class="page-how-it-works" id="main">

    <?php
    // 1 — Hero
    $hero = $how['hero'];
    include __DIR__ . '/components/hero.php';

    // 2 — Direct answer (AI-search canonical block within first 150 words)
    $direct_answer = $how['direct_answer'];
    include __DIR__ . '/components/direct-answer.php';

    // 3 — Three guided steps (reuses homepage process partial)
    $process = $how['steps'];
    include __DIR__ . '/components/process.php';

    // 4 — Human help when needed
    $human_help = $how['human_help'];
    include __DIR__ . '/components/human-help.php';

    // 5 — What Ensurance is not (trust boundary)
    $not_section = $how['not_section'];
    include __DIR__ . '/components/what-ensurance-is-not.php';

    // 6 — FAQ
    $faq_intro = $how['faq_intro'];
    $faq       = $how['faq'];
    include __DIR__ . '/components/faq.php';

    // 7 — Final CTA
    $final_cta = $how['final_cta'];
    include __DIR__ . '/components/final-cta.php';
    ?>

</main>

<?php get_footer( 'marketing' ); ?>
