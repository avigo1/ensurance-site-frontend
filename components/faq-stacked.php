<?php
/**
 * FAQ stacked — centered intro + vertically stacked Q/A cards.
 * Used by /for-shoppers ("Questions, answered plainly").
 *
 * Uses native <details>/<summary> for accessible expand/collapse without JS.
 * Tracking on toggle is wired in marketing.js via [data-faq] attribute.
 *
 * Expects $faq_section (array) with: eyebrow, headline, items[].
 * Each item: key, question, answer.
 */

if ( ! isset( $faq_section ) || ! is_array( $faq_section ) ) {
    return;
}
?>

<section class="section section--faq-stacked" id="faq">
    <div class="container intro-centered">
        <span class="eyebrow-pill"><?php echo esc_html( $faq_section['eyebrow'] ); ?></span>
        <h2 class="intro-centered__headline"><?php echo esc_html( $faq_section['headline'] ); ?></h2>
    </div>

    <div class="container faq-stacked">
        <?php foreach ( $faq_section['items'] as $item ) : ?>
            <details class="faq-stacked__item" data-faq="<?php echo esc_attr( $item['key'] ); ?>">
                <summary class="faq-stacked__question">
                    <span><?php echo esc_html( $item['question'] ); ?></span>
                    <span class="faq-stacked__toggle" aria-hidden="true"></span>
                </summary>
                <p class="faq-stacked__answer"><?php echo esc_html( $item['answer'] ); ?></p>
            </details>
        <?php endforeach; ?>
    </div>
</section>
