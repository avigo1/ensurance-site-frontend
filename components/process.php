<?php
/**
 * Process grid — "How Ensurance works".
 * Centered header + 4-step numbered grid on soft background.
 * Expects $process (array).
 */

if ( ! isset( $process ) || ! is_array( $process ) ) {
    return;
}

$action = $process['action'];
?>

<section class="section section--soft section--process" id="how-it-works">

    <div class="container section-header section-header--centered">
        <p class="eyebrow"><?php echo esc_html( $process['eyebrow'] ); ?></p>
        <h2><?php echo esc_html( $process['headline'] ); ?></h2>
    </div>

    <div class="container process-grid">
        <?php foreach ( $process['steps'] as $step ) : ?>
            <article class="process-card">
                <span class="process-card__number"><?php echo esc_html( $step['number'] ); ?></span>
                <h3 class="process-card__title"><?php echo esc_html( $step['title'] ); ?></h3>
                <p class="process-card__body"><?php echo esc_html( $step['body'] ); ?></p>
            </article>
        <?php endforeach; ?>
    </div>

    <div class="container action-row">
        <a
            class="btn btn--<?php echo esc_attr( $action['variant'] ); ?>"
            href="<?php echo esc_url( $action['href'] ); ?>"
            data-event="<?php echo esc_attr( $action['event'] ); ?>"
        ><?php echo esc_html( $action['label'] ); ?></a>
        <p class="trust-line trust-line--compact"><?php echo esc_html( $process['trust_line'] ); ?></p>
    </div>

</section>
