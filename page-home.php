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

    <div class="hello-world-demo">
        <button class="btn btn--primary hello-world-demo__btn" id="hello-world-btn">
            Press Me
        </button>
        <p class="hello-world-demo__label" id="hello-world-label">Hello World</p>
    </div>

</main>

<?php get_footer('marketing'); ?>
