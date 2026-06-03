<?php
/**
 * Trust-boundary section: "What Ensurance is not" + "Instead" reframe.
 * Two-column. Negative list on left, positive reframe on right.
 * Expects $not_section (array).
 */

if ( ! isset( $not_section ) || ! is_array( $not_section ) ) {
    return;
}

$instead = $not_section['instead'];
?>

<section class="section section--not" id="what-ensurance-is-not">
    <div class="container two-column two-column--start">

        <article class="not-card">
            <p class="eyebrow"><?php echo esc_html( $not_section['eyebrow'] ); ?></p>
            <h2><?php echo esc_html( $not_section['headline'] ); ?></h2>
            <?php foreach ( $not_section['paragraphs'] as $paragraph ) : ?>
                <p class="section-body"><?php echo esc_html( $paragraph ); ?></p>
            <?php endforeach; ?>
            <ul class="not-card__list" aria-label="What Ensurance is not">
                <?php foreach ( $not_section['not_items'] as $item ) : ?>
                    <li><?php echo esc_html( $item ); ?></li>
                <?php endforeach; ?>
            </ul>
        </article>

        <aside class="instead-card">
            <p class="instead-card__eyebrow"><?php echo esc_html( $instead['headline'] ); ?></p>
            <?php foreach ( $instead['paragraphs'] as $paragraph ) : ?>
                <p class="instead-card__body"><?php echo esc_html( $paragraph ); ?></p>
            <?php endforeach; ?>
        </aside>

    </div>
</section>
