<?php
/**
 * Template Name: Home Page (Marketing)
 *
 * Homepage entry template. Uses the marketing header/footer (bypassing Kadence).
 * Section partials live in /components/ and consume copy from
 * components/_homepage-data.php (single source of truth for homepage content).
 *
 * Sections render in display order. To reorder, swap include lines.
 */

$home = require __DIR__ . '/components/_homepage-data.php';

get_header( 'marketing' );
?>

<main class="page-home" id="main">

    <?php
    // 1 — Hero
    $hero = $home['hero'];
    include __DIR__ . '/components/hero.php';

    // 2 — Proof strip
    $proof = $home['proof'];
    include __DIR__ . '/components/proof-strip.php';

    // 3 — One request (two-column with stacked numbered trust cards)
    $one_request = $home['one_request'];
    include __DIR__ . '/components/one-request.php';

    // 4 — How Ensurance works (4-step process grid)
    $process = $home['process'];
    include __DIR__ . '/components/process.php';

    // 5 — What makes Ensurance different (2x2 trust card grid)
    $difference = $home['difference'];
    include __DIR__ . '/components/difference.php';

    // 6 — Protected insurance shopping (navy band, trust-point panel)
    $protected = $home['protected'];
    include __DIR__ . '/components/protected.php';

    // 7 — Coverage grid (6 coverage cards)
    $coverage = $home['coverage'];
    include __DIR__ . '/components/coverage.php';

    // 8 — Licensed review (agent card)
    $agent_review = $home['agent_review'];
    include __DIR__ . '/components/agent-review.php';

    // 9 — FAQ (two-column, sticky copy + accordion)
    $faq_intro = $home['faq_intro'];
    $faq       = $home['faq'];
    include __DIR__ . '/components/faq.php';

    // 10 — Final CTA card
    $final_cta = $home['final_cta'];
    include __DIR__ . '/components/final-cta.php';
    ?>

</main>

<?php get_footer( 'marketing' ); ?>
