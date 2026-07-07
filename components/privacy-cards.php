<?php
/**
 * Privacy cards — centered intro + 3 cards with icon tiles + footnote.
 * Used by /for-shoppers ("Your information is never broadcast").
 *
 * Expects $privacy (array) with: eyebrow, headline, lead, cards[], footnote.
 * Each card: title, body.
 */

if ( ! isset( $privacy ) || ! is_array( $privacy ) ) {
    return;
}
?>

<section class="section section--privacy">
    <div class="container intro-centered">
        <span class="eyebrow-pill"><?php echo esc_html( $privacy['eyebrow'] ); ?></span>
        <h2 class="intro-centered__headline"><?php echo esc_html( $privacy['headline'] ); ?></h2>
        <p class="intro-centered__lead"><?php echo esc_html( $privacy['lead'] ); ?></p>
    </div>

    <div class="container cards-row cards-row--3">
        <?php foreach ( $privacy['cards'] as $card ) : ?>
            <article class="privacy-card">
                <span class="privacy-card__icon" aria-hidden="true"></span>
                <h3 class="privacy-card__title"><?php echo esc_html( $card['title'] ); ?></h3>
                <p class="privacy-card__body"><?php echo esc_html( $card['body'] ); ?></p>
            </article>
        <?php endforeach; ?>
    </div>

    <?php if ( ! empty( $privacy['footnote'] ) ) : ?>
        <p class="container privacy-footnote"><?php echo esc_html( $privacy['footnote'] ); ?></p>
    <?php endif; ?>
</section>
