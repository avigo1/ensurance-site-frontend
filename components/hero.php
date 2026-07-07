<?php
/**
 * Hero section — homepage.
 *
 * Expects $hero (array) to be set by the caller (page-home.php).
 * Two-column layout: copy + "what happens next" module on the left,
 * flow-card preview on the right. Stacks on tablet/mobile.
 */

if ( ! isset( $hero ) || ! is_array( $hero ) ) {
    return;
}
?>

<section class="hero">
    <div class="container hero__grid">

        <div class="hero__copy">
            <p class="eyebrow"><?php echo esc_html( $hero['eyebrow'] ); ?></p>
            <h1 class="hero__headline"><?php echo esc_html( $hero['headline'] ); ?></h1>
            <p class="hero__subtitle"><?php echo esc_html( $hero['subtitle'] ); ?></p>
            <p class="hero__support"><?php echo esc_html( $hero['support'] ); ?></p>

            <div class="next-module" aria-label="<?php echo esc_attr( $hero['next_module']['label'] ); ?>">
                <p class="next-module__label"><?php echo esc_html( $hero['next_module']['label'] ); ?></p>
                <ol class="next-module__steps">
                    <?php foreach ( $hero['next_module']['steps'] as $step ) : ?>
                        <li><?php echo esc_html( $step ); ?></li>
                    <?php endforeach; ?>
                </ol>
            </div>

            <div class="hero__actions">
                <?php foreach ( $hero['actions'] as $action ) : ?>
                    <a
                        class="btn btn--<?php echo esc_attr( $action['variant'] ); ?>"
                        href="<?php echo esc_url( $action['href'] ); ?>"
                        data-event="<?php echo esc_attr( $action['event'] ); ?>"
                    ><?php echo esc_html( $action['label'] ); ?></a>
                <?php endforeach; ?>
            </div>

            <p class="hero__trust-line"><?php echo esc_html( $hero['trust_line'] ); ?></p>
        </div>

        <aside class="hero__panel" aria-label="Ensurance request flow preview">
            <div class="flow-card">

                <div class="flow-card__header">
                    <span class="flow-card__status-dot" aria-hidden="true"></span>
                    <span><?php echo esc_html( $hero['flow']['label'] ); ?></span>
                </div>

                <div class="flow-card__path">
                    <p class="flow-card__path-label"><?php echo esc_html( $hero['flow']['start_label'] ); ?></p>
                    <div class="flow-card__path-line" aria-hidden="true">
                        <span></span><span></span><span></span>
                    </div>
                    <p class="flow-card__path-label flow-card__path-label--end"><?php echo esc_html( $hero['flow']['end_label'] ); ?></p>
                </div>

                <ol class="flow-card__steps">
                    <?php foreach ( $hero['flow']['steps'] as $step ) : ?>
                        <li class="flow-step flow-step--<?php echo esc_attr( $step['state'] ); ?>">
                            <span class="flow-step__number"><?php echo esc_html( $step['number'] ); ?></span>
                            <p class="flow-step__title"><?php echo esc_html( $step['title'] ); ?></p>
                        </li>
                    <?php endforeach; ?>
                </ol>

            </div>
        </aside>

    </div>
</section>
