<?php
/**
 * Steps row — centered intro + 4 steps in a horizontal row.
 * Used by /for-shoppers ("What happens after you start your request").
 *
 * Each step has a circular brand-colored number badge, title, and body.
 *
 * Expects $steps_section (array) with: eyebrow, headline, lead, steps[].
 * Each step: number, title, body.
 */

if ( ! isset( $steps_section ) || ! is_array( $steps_section ) ) {
    return;
}
?>

<section class="section section--steps" id="how-it-works">
    <div class="container intro-centered">
        <span class="eyebrow-pill"><?php echo esc_html( $steps_section['eyebrow'] ); ?></span>
        <h2 class="intro-centered__headline"><?php echo esc_html( $steps_section['headline'] ); ?></h2>
        <p class="intro-centered__lead"><?php echo esc_html( $steps_section['lead'] ); ?></p>
    </div>

    <ol class="container steps-row">
        <?php foreach ( $steps_section['steps'] as $step ) : ?>
            <li class="step-item">
                <span class="step-item__badge" aria-hidden="true"><?php echo esc_html( $step['number'] ); ?></span>
                <h3 class="step-item__title"><?php echo esc_html( $step['title'] ); ?></h3>
                <p class="step-item__body"><?php echo esc_html( $step['body'] ); ?></p>
            </li>
        <?php endforeach; ?>
    </ol>
</section>
