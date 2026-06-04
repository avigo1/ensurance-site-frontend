<?php
/**
 * Value cards — centered intro + 3 numbered-pill cards.
 * Used by /for-shoppers ("We turn what you need into one clear, protected request").
 *
 * Expects $value (array) with: eyebrow, headline, lead, cards[].
 * Each card: number, title, body.
 */

if ( ! isset( $value ) || ! is_array( $value ) ) {
    return;
}
?>

<section class="section section--value section--alt">
    <div class="container intro-centered">
        <span class="eyebrow-pill"><?php echo esc_html( $value['eyebrow'] ); ?></span>
        <h2 class="intro-centered__headline"><?php echo esc_html( $value['headline'] ); ?></h2>
        <p class="intro-centered__lead"><?php echo esc_html( $value['lead'] ); ?></p>
    </div>

    <div class="container cards-row cards-row--3">
        <?php foreach ( $value['cards'] as $card ) : ?>
            <article class="value-card">
                <span class="value-card__number"><?php echo esc_html( $card['number'] ); ?></span>
                <h3 class="value-card__title"><?php echo esc_html( $card['title'] ); ?></h3>
                <p class="value-card__body"><?php echo esc_html( $card['body'] ); ?></p>
            </article>
        <?php endforeach; ?>
    </div>
</section>
