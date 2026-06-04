<?php
/**
 * Proof strip — three-card value summary.
 *
 * Expects $proof (array of items with title + body).
 * Optional: $proof_header (array with eyebrow, headline, lead, footnote)
 * renders a centered section header and footnote around the card grid for
 * pages that use proof-strip as a labeled section (e.g. /for-shoppers).
 * When $proof_header is not set, the partial renders the bare card row
 * (legacy behavior used directly below the hero on home/coverage).
 */

if ( ! isset( $proof ) || ! is_array( $proof ) ) {
    return;
}

$proof_header_data = isset( $proof_header ) && is_array( $proof_header ) ? $proof_header : null;

if ( $proof_header_data ) : ?>
<section class="section section--proof" aria-label="<?php echo esc_attr( $proof_header_data['headline'] ); ?>">
    <div class="container section-header section-header--centered">
        <?php if ( ! empty( $proof_header_data['eyebrow'] ) ) : ?>
            <p class="eyebrow"><?php echo esc_html( $proof_header_data['eyebrow'] ); ?></p>
        <?php endif; ?>
        <?php if ( ! empty( $proof_header_data['headline'] ) ) : ?>
            <h2><?php echo esc_html( $proof_header_data['headline'] ); ?></h2>
        <?php endif; ?>
        <?php if ( ! empty( $proof_header_data['lead'] ) ) : ?>
            <p class="section-lead"><?php echo esc_html( $proof_header_data['lead'] ); ?></p>
        <?php endif; ?>
    </div>
    <div class="container proof-strip__grid">
        <?php foreach ( $proof as $item ) : ?>
            <article class="proof-card">
                <p class="proof-card__title"><?php echo esc_html( $item['title'] ); ?></p>
                <p class="proof-card__body"><?php echo esc_html( $item['body'] ); ?></p>
            </article>
        <?php endforeach; ?>
    </div>
    <?php if ( ! empty( $proof_header_data['footnote'] ) ) : ?>
        <div class="container section-header section-header--centered">
            <p class="trust-line trust-line--compact"><?php echo esc_html( $proof_header_data['footnote'] ); ?></p>
        </div>
    <?php endif; ?>
</section>
<?php
// Reset so a subsequent include without a header reverts to legacy mode.
unset( $proof_header );
return;
endif; ?>

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
