<?php
/**
 * Coverage grid — 6 coverage cards (auto/home/renters/life/business/health).
 * Each card is wrapped as an anchor for full-card tap target.
 * Expects $coverage (array).
 */

if ( ! isset( $coverage ) || ! is_array( $coverage ) ) {
    return;
}
?>

<section class="section section--coverage">

    <div class="container section-header section-header--centered">
        <p class="eyebrow"><?php echo esc_html( $coverage['eyebrow'] ); ?></p>
        <h2><?php echo esc_html( $coverage['headline'] ); ?></h2>
    </div>

    <div class="container coverage-grid">
        <?php foreach ( $coverage['cards'] as $card ) : ?>
            <a
                class="coverage-card"
                href="<?php echo esc_url( $card['href'] ); ?>"
                data-event="<?php echo esc_attr( $card['event'] ); ?>"
            >
                <h3 class="coverage-card__title"><?php echo esc_html( $card['title'] ); ?></h3>
                <p class="coverage-card__body"><?php echo esc_html( $card['body'] ); ?></p>
                <span class="coverage-card__cta"><?php echo esc_html( $card['label'] ); ?></span>
            </a>
        <?php endforeach; ?>
    </div>

</section>
