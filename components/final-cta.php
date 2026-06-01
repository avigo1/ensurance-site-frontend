<?php
/**
 * Final CTA — centered card on soft background.
 * Eyebrow, heading, body, dual CTAs, trust line.
 * Expects $final_cta (array).
 */

if ( ! isset( $final_cta ) || ! is_array( $final_cta ) ) {
    return;
}
?>

<section class="section section--soft section--final-cta">
    <div class="container">
        <div class="final-card">
            <p class="eyebrow"><?php echo esc_html( $final_cta['eyebrow'] ); ?></p>
            <h2><?php echo esc_html( $final_cta['headline'] ); ?></h2>
            <p class="final-card__body"><?php echo esc_html( $final_cta['body'] ); ?></p>

            <div class="final-card__actions">
                <?php foreach ( $final_cta['actions'] as $action ) : ?>
                    <a
                        class="btn btn--<?php echo esc_attr( $action['variant'] ); ?>"
                        href="<?php echo esc_url( $action['href'] ); ?>"
                        data-event="<?php echo esc_attr( $action['event'] ); ?>"
                    ><?php echo esc_html( $action['label'] ); ?></a>
                <?php endforeach; ?>
            </div>

            <p class="trust-line"><?php echo esc_html( $final_cta['trust_line'] ); ?></p>
        </div>
    </div>
</section>
