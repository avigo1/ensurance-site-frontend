<?php
/**
 * Answer-ready card — centered bordered card for the "In short" block.
 * Doubles as the AI-search direct-answer block.
 *
 * Expects $answer (array) with: eyebrow, headline, body.
 */

if ( ! isset( $answer ) || ! is_array( $answer ) ) {
    return;
}
?>

<section class="section section--answer section--alt" aria-labelledby="answer-card-heading">
    <div class="container">
        <article class="answer-card">
            <span class="eyebrow-pill"><?php echo esc_html( $answer['eyebrow'] ); ?></span>
            <h2 id="answer-card-heading" class="answer-card__headline"><?php echo esc_html( $answer['headline'] ); ?></h2>
            <p class="answer-card__body"><?php echo esc_html( $answer['body'] ); ?></p>
        </article>
    </div>
</section>
