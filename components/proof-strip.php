<?php
/**
 * Proof strip — three-card value summary directly below the hero.
 * Expects $proof (array of items with title + body).
 */

if ( ! isset( $proof ) || ! is_array( $proof ) ) {
    return;
}
?>

<section class="proof-strip" aria-label="Ensurance value summary">
    <div class="container proof-strip__grid">
        <?php foreach ( $proof as $item ) : ?>
            <article class="proof-card">
                <p class="proof-card__title"><?php echo esc_html( $item['title'] ); ?></p>
                <p class="proof-card__body"><?php echo esc_html( $item['body'] ); ?></p>
            </article>
        <?php endforeach; ?>
    </div>
</section>
