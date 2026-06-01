<?php
/**
 * Protected insurance shopping — dark navy band, two-column.
 * Copy + CTA + disclosure on the left, trust-point panel on the right.
 * Expects $protected (array).
 */

if ( ! isset( $protected ) || ! is_array( $protected ) ) {
    return;
}

$action = $protected['action'];
?>

<section class="section section--protected">
    <div class="container two-column two-column--start">

        <div class="section-copy section-copy--light">
            <p class="eyebrow eyebrow--light"><?php echo esc_html( $protected['eyebrow'] ); ?></p>
            <h2><?php echo esc_html( $protected['headline'] ); ?></h2>
            <p class="section-lead"><?php echo esc_html( $protected['lead'] ); ?></p>

            <div class="section-actions">
                <a
                    class="btn btn--<?php echo esc_attr( $action['variant'] ); ?>"
                    href="<?php echo esc_url( $action['href'] ); ?>"
                    data-event="<?php echo esc_attr( $action['event'] ); ?>"
                ><?php echo esc_html( $action['label'] ); ?></a>
            </div>

            <p class="ad-disclosure"><?php echo esc_html( $protected['disclosure'] ); ?></p>
        </div>

        <ul class="trust-panel">
            <?php foreach ( $protected['trust_points'] as $point ) : ?>
                <li class="trust-panel__item">
                    <span class="trust-panel__dot" aria-hidden="true"></span>
                    <p><?php echo esc_html( $point ); ?></p>
                </li>
            <?php endforeach; ?>
        </ul>

    </div>
</section>
