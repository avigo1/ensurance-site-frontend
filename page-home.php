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

    <section class="under-construction">
        <div class="under-construction__badge">Under Construction</div>
        <h1 class="under-construction__heading">
            We're building something <span class="under-construction__accent">new</span>.
        </h1>
        <p class="under-construction__text">
            Our website is getting a fresh look. In the meantime, you can still find an insurance agent or get a free quote.
        </p>
        <div class="under-construction__actions">
            <a href="<?php echo esc_url(home_url('/insurance-agencies/')); ?>" class="btn btn--primary">
                Find an Agent
            </a>
            <a href="<?php echo esc_url(home_url('/get-a-quote/')); ?>" class="btn btn--secondary">
                Get a Free Quote
            </a>
        </div>
    </section>

</main>

<?php get_footer('marketing'); ?>
