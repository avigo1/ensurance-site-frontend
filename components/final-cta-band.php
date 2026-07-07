<?php
/**
 * Final CTA — full-bleed dark navy band, centered copy + dual CTAs.
 * Used by /for-shoppers.
 *
 * Expects $cta_band (array) with: headline, body, actions[], microcopy.
 */

if ( ! isset( $cta_band ) || ! is_array( $cta_band ) ) {
    return;
}
?>

<section class="section section--cta-band">
    <div class="container cta-band">

        <h2 class="cta-band__headline"><?php echo esc_html( $cta_band['headline'] ); ?></h2>
        <p class="cta-band__body"><?php echo esc_html( $cta_band['body'] ); ?></p>

        <div class="cta-band__actions">
            <?php foreach ( $cta_band['actions'] as $action ) : ?>
                <a
                    class="btn btn--<?php echo esc_attr( $action['variant'] ); ?>"
                    href="<?php echo esc_url( $action['href'] ); ?>"
                    data-event="<?php echo esc_attr( $action['event'] ); ?>"
                ><?php echo esc_html( $action['label'] ); ?></a>
            <?php endforeach; ?>
        </div>

        <?php if ( ! empty( $cta_band['microcopy'] ) ) : ?>
            <p class="cta-band__microcopy"><?php echo esc_html( $cta_band['microcopy'] ); ?></p>
        <?php endif; ?>

    </div>
</section>
