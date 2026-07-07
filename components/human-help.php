<?php
/**
 * "Human help when needed" — two-column with copy + bullet card.
 * Expects $human_help (array).
 */

if ( ! isset( $human_help ) || ! is_array( $human_help ) ) {
    return;
}

$card = $human_help['card'];
?>

<section class="section section--soft section--human-help" id="human-help">
    <div class="container two-column two-column--start">

        <div class="section-copy">
            <p class="eyebrow"><?php echo esc_html( $human_help['eyebrow'] ); ?></p>
            <h2><?php echo esc_html( $human_help['headline'] ); ?></h2>
            <p class="section-lead"><?php echo esc_html( $human_help['lead'] ); ?></p>
        </div>

        <aside class="info-card">
            <h3 class="info-card__headline"><?php echo esc_html( $card['headline'] ); ?></h3>
            <?php foreach ( $card['paragraphs'] as $paragraph ) : ?>
                <p class="info-card__body"><?php echo esc_html( $paragraph ); ?></p>
            <?php endforeach; ?>
            <ul class="info-card__list">
                <?php foreach ( $card['items'] as $item ) : ?>
                    <li><?php echo esc_html( $item ); ?></li>
                <?php endforeach; ?>
            </ul>
        </aside>

    </div>
</section>
