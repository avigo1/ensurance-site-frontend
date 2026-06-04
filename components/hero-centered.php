<?php
/**
 * Hero — centered, single-column. Used by /for-shoppers.
 *
 * Mirrors the Figma layout: pill eyebrow + large headline + body + dual CTAs
 * + microcopy + dot-separated trust items, all centered in a ~900px column.
 *
 * Expects $hero (array) with: eyebrow, headline, body, actions[], microcopy,
 * trust_items[].
 */

if ( ! isset( $hero ) || ! is_array( $hero ) ) {
    return;
}
?>

<section class="hero hero--centered">
    <div class="container hero-centered">

        <span class="eyebrow-pill"><?php echo esc_html( $hero['eyebrow'] ); ?></span>

        <h1 class="hero-centered__headline">
            <?php
            // Allow a two-line headline by splitting on a literal pipe in the data.
            $lines = explode( '|', $hero['headline'] );
            foreach ( $lines as $i => $line ) {
                echo ( $i > 0 ? '<br>' : '' ) . esc_html( trim( $line ) );
            }
            ?>
        </h1>

        <p class="hero-centered__body"><?php echo esc_html( $hero['body'] ); ?></p>

        <div class="hero-centered__actions">
            <?php foreach ( $hero['actions'] as $action ) : ?>
                <a
                    class="btn btn--<?php echo esc_attr( $action['variant'] ); ?>"
                    href="<?php echo esc_url( $action['href'] ); ?>"
                    data-event="<?php echo esc_attr( $action['event'] ); ?>"
                ><?php echo esc_html( $action['label'] ); ?></a>
            <?php endforeach; ?>
        </div>

        <?php if ( ! empty( $hero['microcopy'] ) ) : ?>
            <p class="hero-centered__microcopy"><?php echo esc_html( $hero['microcopy'] ); ?></p>
        <?php endif; ?>

        <?php if ( ! empty( $hero['trust_items'] ) ) : ?>
            <ul class="hero-centered__trust" aria-label="Why people start with Ensurance">
                <?php foreach ( $hero['trust_items'] as $item ) : ?>
                    <li>
                        <span class="hero-centered__trust-dot" aria-hidden="true"></span>
                        <?php echo esc_html( $item ); ?>
                    </li>
                <?php endforeach; ?>
            </ul>
        <?php endif; ?>

    </div>
</section>
