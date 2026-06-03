<?php
/**
 * Template Name: Start Request (Marketing)
 *
 * /start — Guided shopper intake entry point.
 *
 * Composition:
 *   1. Welcome screen (components/start-intro.php)
 *   2. Guided wizard (components/start-wizard.php) — renders the eight
 *      required intake steps (Coverage type → Contact details → Review)
 *      plus the inline Confirmation card shown after submit.
 *
 * Copy lives in components/_start-data.php (single source of truth).
 */

$start = require __DIR__ . '/components/_start-data.php';

// Flip the root class so the no-JS CSS fallback only shows when JS is unavailable.
add_action( 'wp_head', function () { ?>
    <script>document.documentElement.classList.add('no-js');</script>
<?php }, 1 );

get_header( 'marketing' );
?>

<main class="page-start" id="main">

    <?php
    // 1 — Welcome screen.
    $intro = $start['intro'];
    include __DIR__ . '/components/start-intro.php';

    // 2 — Guided wizard (Coverage → Review) + Confirmation card.
    $wizard = $start['wizard'];
    include __DIR__ . '/components/start-wizard.php';
    ?>

</main>

<?php get_footer( 'marketing' ); ?>
