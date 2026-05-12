<?php
/**
 * Template Name: Home Page (Marketing)
 *
 * The homepage template. Uses the marketing header and footer,
 * completely bypassing the Kadence theme header/footer.
 * Build sections here using components from /components/.
 */

get_header('marketing');
?>

<main class="page-home">

    <!-- PLACEHOLDER: Replace with actual homepage sections -->
    <!-- Each section should be a component in /components/ -->
    <!-- Example: <?php get_template_part('components/hero'); ?> -->

    <div style="min-height: 60vh; display: flex; align-items: center; justify-content: center; background: var(--color-background-alt);">
        <div style="text-align: center; padding: 4rem 2rem;">
            <h1 style="font-family: var(--font-heading); font-size: 2.5rem; color: var(--color-primary); margin-bottom: 1rem;">
               Travis
            </h1>
        </div>
    </div>

</main>

<?php get_footer('marketing'); ?>
