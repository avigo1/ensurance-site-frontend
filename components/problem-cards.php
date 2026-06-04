<?php
/**
 * Problem cards — centered intro + 3 plain cards on a section.
 * Used by /for-shoppers ("Why insurance shopping feels overwhelming").
 *
 * Expects $problem (array) with: eyebrow, headline, lead, cards[].
 * Each card: title, body.
 */

if ( ! isset( $problem ) || ! is_array( $problem ) ) {
    return;
}
?>

<section class="section section--problem">
    <div class="container intro-centered">
        <span class="eyebrow-pill eyebrow-pill--muted"><?php echo esc_html( $problem['eyebrow'] ); ?></span>
        <h2 class="intro-centered__headline"><?php echo esc_html( $problem['headline'] ); ?></h2>
        <p class="intro-centered__lead"><?php echo esc_html( $problem['lead'] ); ?></p>
    </div>

    <div class="container cards-row cards-row--3">
        <?php foreach ( $problem['cards'] as $card ) : ?>
            <article class="plain-card">
                <h3 class="plain-card__title"><?php echo esc_html( $card['title'] ); ?></h3>
                <p class="plain-card__body"><?php echo esc_html( $card['body'] ); ?></p>
            </article>
        <?php endforeach; ?>
    </div>
</section>
