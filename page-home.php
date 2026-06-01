<?php
/**
 * Template Name: Home Page (Marketing)
 *
 * Homepage entry template. Uses the marketing header/footer (bypassing Kadence).
 * Section partials live in /components/ and consume copy from
 * components/_homepage-data.php (single source of truth for homepage content).
 */

$home = require __DIR__ . '/components/_homepage-data.php';

get_header( 'marketing' );
?>

<main class="page-home" id="main">

    <?php
    // SECTION 1 — Hero
    $hero = $home['hero'];
    include __DIR__ . '/components/hero.php';
    ?>

</main>

<?php get_footer( 'marketing' ); ?>
