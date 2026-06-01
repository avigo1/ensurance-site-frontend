<?php
/**
 * "What makes Ensurance different" — two-column with 2x2 trust card grid.
 * Expects $difference (array).
 */

if ( ! isset( $difference ) || ! is_array( $difference ) ) {
    return;
}
?>

<section class="section section--difference">
    <div class="container two-column two-column--start">

        <div class="section-copy">
            <p class="eyebrow"><?php echo esc_html( $difference['eyebrow'] ); ?></p>
            <h2><?php echo esc_html( $difference['headline'] ); ?></h2>
            <p class="section-lead"><?php echo esc_html( $difference['lead'] ); ?></p>
        </div>

        <div class="difference-grid">
            <?php foreach ( $difference['cards'] as $card ) : ?>
                <article class="trust-card">
                    <h3 class="trust-card__title"><?php echo esc_html( $card['title'] ); ?></h3>
                    <p class="trust-card__body"><?php echo esc_html( $card['body'] ); ?></p>
                </article>
            <?php endforeach; ?>
        </div>

    </div>
</section>
