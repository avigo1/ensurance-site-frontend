<?php
/**
 * Shopper control — two-column: copy on left, checklist card on right.
 * Used by /for-shoppers ("You stay in control at every step").
 *
 * The checklist card has divided rows, each with a circular brand-colored
 * checkmark badge and a label.
 *
 * Expects $control (array) with: eyebrow, headline, body, items[] (strings).
 */

if ( ! isset( $control ) || ! is_array( $control ) ) {
    return;
}
?>

<section class="section section--control section--alt">
    <div class="container two-col two-col--copy-card">

        <div class="two-col__copy">
            <span class="eyebrow-pill"><?php echo esc_html( $control['eyebrow'] ); ?></span>
            <h2 class="two-col__headline"><?php echo esc_html( $control['headline'] ); ?></h2>
            <p class="two-col__body"><?php echo esc_html( $control['body'] ); ?></p>
        </div>

        <ul class="checklist-card" aria-label="What shopper control looks like">
            <?php foreach ( $control['items'] as $item ) : ?>
                <li class="checklist-card__row">
                    <span class="checklist-card__check" aria-hidden="true">&#10003;</span>
                    <span class="checklist-card__label"><?php echo esc_html( $item ); ?></span>
                </li>
            <?php endforeach; ?>
        </ul>

    </div>
</section>
