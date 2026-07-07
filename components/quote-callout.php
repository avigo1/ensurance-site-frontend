<?php
/**
 * Quote callout — two-column: dark quote card on left, copy on right.
 * Used by /for-shoppers ("Real, licensed humans — when you want them").
 *
 * Expects $callout (array) with:
 *   eyebrow, headline, body (string OR array of strings for multi-paragraph),
 *   quote, attribution.
 */

if ( ! isset( $callout ) || ! is_array( $callout ) ) {
    return;
}

$body_paragraphs = is_array( $callout['body'] ) ? $callout['body'] : array( $callout['body'] );
?>

<section class="section section--callout">
    <div class="container two-col two-col--card-copy">

        <figure class="quote-card">
            <blockquote class="quote-card__quote"><?php echo esc_html( $callout['quote'] ); ?></blockquote>
            <figcaption class="quote-card__attribution"><?php echo esc_html( $callout['attribution'] ); ?></figcaption>
        </figure>

        <div class="two-col__copy">
            <span class="eyebrow-pill"><?php echo esc_html( $callout['eyebrow'] ); ?></span>
            <h2 class="two-col__headline"><?php echo esc_html( $callout['headline'] ); ?></h2>
            <?php foreach ( $body_paragraphs as $paragraph ) : ?>
                <p class="two-col__body"><?php echo esc_html( $paragraph ); ?></p>
            <?php endforeach; ?>
        </div>

    </div>
</section>
