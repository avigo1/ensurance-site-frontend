<?php
/**
 * Start — Welcome screen.
 *
 * Required PDF section: "Welcome screen."
 * Trust-first introduction shown above the guided intake wizard.
 *
 * Expects $intro (array) set by the caller (page-start.php).
 */

if ( ! isset( $intro ) || ! is_array( $intro ) ) {
    return;
}
?>

<section class="start-intro" aria-labelledby="start-intro-headline">
    <div class="container start-intro__inner">

        <div class="start-intro__copy">
            <p class="eyebrow"><?php echo esc_html( $intro['eyebrow'] ); ?></p>
            <h1 id="start-intro-headline" class="start-intro__headline">
                <?php echo esc_html( $intro['headline'] ); ?>
            </h1>
            <p class="start-intro__subtitle"><?php echo esc_html( $intro['subtitle'] ); ?></p>
            <p class="start-intro__support"><?php echo esc_html( $intro['support'] ); ?></p>

            <div class="next-module" aria-label="<?php echo esc_attr( $intro['next_module']['label'] ); ?>">
                <p class="next-module__label"><?php echo esc_html( $intro['next_module']['label'] ); ?></p>
                <ol class="next-module__steps">
                    <?php foreach ( $intro['next_module']['steps'] as $step ) : ?>
                        <li><?php echo esc_html( $step ); ?></li>
                    <?php endforeach; ?>
                </ol>
            </div>

            <?php if ( ! empty( $intro['actions'] ) ) : ?>
                <div class="start-intro__actions">
                    <a class="btn btn--primary"
                       href="#start-wizard"
                       data-event="start_begin_wizard_click">Start my request</a>
                    <?php foreach ( $intro['actions'] as $action ) : ?>
                        <a class="btn btn--<?php echo esc_attr( $action['variant'] ); ?>"
                           href="<?php echo esc_url( $action['href'] ); ?>"
                           data-event="<?php echo esc_attr( $action['event'] ); ?>">
                            <?php echo esc_html( $action['label'] ); ?>
                        </a>
                    <?php endforeach; ?>
                </div>
            <?php endif; ?>

            <p class="start-intro__trust-line"><?php echo esc_html( $intro['trust_line'] ); ?></p>
        </div>

        <aside class="start-intro__panel" aria-label="Privacy and trust notes">
            <p class="start-intro__panel-label">Privacy and trust</p>
            <ul class="start-intro__panel-list">
                <?php foreach ( $intro['privacy_points'] as $point ) : ?>
                    <li>
                        <span class="start-intro__panel-dot" aria-hidden="true"></span>
                        <span><?php echo esc_html( $point ); ?></span>
                    </li>
                <?php endforeach; ?>
            </ul>
        </aside>

    </div>
</section>
