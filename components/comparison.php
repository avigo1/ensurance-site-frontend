<?php
/**
 * Comparison — centered intro + two side-by-side cards (✕ vs ✓).
 * Used by /for-shoppers ("Help without the quote chaos").
 *
 * Expects $comparison (array) with: eyebrow, headline, lead, not_label,
 * not_items[], is_label, is_items[].
 */

if ( ! isset( $comparison ) || ! is_array( $comparison ) ) {
    return;
}
?>

<section class="section section--comparison section--alt" id="what-ensurance-is-not">
    <div class="container intro-centered">
        <span class="eyebrow-pill"><?php echo esc_html( $comparison['eyebrow'] ); ?></span>
        <h2 class="intro-centered__headline"><?php echo esc_html( $comparison['headline'] ); ?></h2>
        <p class="intro-centered__lead"><?php echo esc_html( $comparison['lead'] ); ?></p>
    </div>

    <div class="container comparison-grid">

        <article class="compare-card compare-card--not">
            <p class="compare-card__label"><?php echo esc_html( $comparison['not_label'] ); ?></p>
            <ul class="compare-card__list">
                <?php foreach ( $comparison['not_items'] as $item ) : ?>
                    <li class="compare-card__row">
                        <span class="compare-card__icon compare-card__icon--not" aria-hidden="true">&#10005;</span>
                        <span class="compare-card__text"><?php echo esc_html( $item ); ?></span>
                    </li>
                <?php endforeach; ?>
            </ul>
        </article>

        <article class="compare-card compare-card--is">
            <p class="compare-card__label compare-card__label--is"><?php echo esc_html( $comparison['is_label'] ); ?></p>
            <ul class="compare-card__list">
                <?php foreach ( $comparison['is_items'] as $item ) : ?>
                    <li class="compare-card__row">
                        <span class="compare-card__icon compare-card__icon--is" aria-hidden="true">&#10003;</span>
                        <span class="compare-card__text"><?php echo esc_html( $item ); ?></span>
                    </li>
                <?php endforeach; ?>
            </ul>
        </article>

    </div>
</section>
