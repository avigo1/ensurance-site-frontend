<?php
/**
 * FAQ — two-column. Sticky copy + CTA on left, accordion list on right.
 * Uses native <details>/<summary> for accessible expand/collapse without JS.
 * Tracking on toggle is wired in marketing.js via [data-faq] attribute.
 *
 * Expects $faq_intro (array) and $faq (array of items).
 */

if ( ! isset( $faq_intro ) || ! is_array( $faq_intro ) || ! isset( $faq ) || ! is_array( $faq ) ) {
    return;
}

$action = $faq_intro['action'];
?>

<section class="section section--faq">
    <div class="container two-column two-column--start">

        <div class="section-copy section-copy--sticky">
            <p class="eyebrow"><?php echo esc_html( $faq_intro['eyebrow'] ); ?></p>
            <h2><?php echo esc_html( $faq_intro['headline'] ); ?></h2>
            <p class="section-lead"><?php echo esc_html( $faq_intro['lead'] ); ?></p>

            <div class="section-actions">
                <a
                    class="btn btn--<?php echo esc_attr( $action['variant'] ); ?>"
                    href="<?php echo esc_url( $action['href'] ); ?>"
                    data-event="<?php echo esc_attr( $action['event'] ); ?>"
                ><?php echo esc_html( $action['label'] ); ?></a>
            </div>
        </div>

        <div class="faq-list">
            <?php foreach ( $faq as $item ) : ?>
                <details class="faq-item" data-faq="<?php echo esc_attr( $item['key'] ); ?>">
                    <summary class="faq-item__question">
                        <span><?php echo esc_html( $item['question'] ); ?></span>
                        <span class="faq-item__toggle" aria-hidden="true"></span>
                    </summary>
                    <p class="faq-item__answer"><?php echo esc_html( $item['answer'] ); ?></p>
                </details>
            <?php endforeach; ?>
        </div>

    </div>
</section>
