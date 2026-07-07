<?php
/**
 * "One request" — two-column section.
 * Sticky copy on the left, stacked numbered trust cards on the right.
 * Expects $one_request (array).
 */

if ( ! isset( $one_request ) || ! is_array( $one_request ) ) {
    return;
}

$action = $one_request['action'];
?>

<section class="section section--one-request">
    <div class="container two-column two-column--start">

        <div class="section-copy section-copy--sticky">
            <p class="eyebrow"><?php echo esc_html( $one_request['eyebrow'] ); ?></p>
            <h2><?php echo esc_html( $one_request['headline'] ); ?></h2>
            <p class="section-lead"><?php echo esc_html( $one_request['lead'] ); ?></p>
            <p class="section-body"><?php echo esc_html( $one_request['body'] ); ?></p>

            <div class="section-actions">
                <a
                    class="btn btn--<?php echo esc_attr( $action['variant'] ); ?>"
                    href="<?php echo esc_url( $action['href'] ); ?>"
                    data-event="<?php echo esc_attr( $action['event'] ); ?>"
                ><?php echo esc_html( $action['label'] ); ?></a>
            </div>
        </div>

        <div class="stacked-cards">
            <?php foreach ( $one_request['cards'] as $card ) : ?>
                <article class="trust-card trust-card--large">
                    <div class="trust-card__icon" aria-hidden="true"><?php echo esc_html( $card['number'] ); ?></div>
                    <h3 class="trust-card__title"><?php echo esc_html( $card['title'] ); ?></h3>
                    <p class="trust-card__body"><?php echo esc_html( $card['body'] ); ?></p>
                </article>
            <?php endforeach; ?>
        </div>

    </div>
</section>
