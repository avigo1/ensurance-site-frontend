<?php
/**
 * Direct answer + AI-search canonical block.
 *
 * Renders within the first 150 words of visible content so AI-search
 * systems and crawlers find a clear definition of Ensurance up top
 * (PDF: direct-answer page requirement).
 *
 * Two-column: copy on the left, framed answer block on the right.
 * Expects $direct_answer (array).
 */

if ( ! isset( $direct_answer ) || ! is_array( $direct_answer ) ) {
    return;
}

$answer = $direct_answer['answer_block'];
?>

<section class="section section--direct-answer" id="direct-answer" aria-labelledby="direct-answer-heading">
    <div class="container two-column two-column--start">

        <div class="section-copy">
            <p class="eyebrow"><?php echo esc_html( $direct_answer['eyebrow'] ); ?></p>
            <h2 id="direct-answer-heading"><?php echo esc_html( $direct_answer['headline'] ); ?></h2>
            <p class="section-lead"><?php echo esc_html( $direct_answer['lead'] ); ?></p>
            <?php foreach ( $direct_answer['paragraphs'] as $paragraph ) : ?>
                <p class="section-body"><?php echo esc_html( $paragraph ); ?></p>
            <?php endforeach; ?>
            <p class="trust-line"><?php echo esc_html( $direct_answer['trust_line'] ); ?></p>
        </div>

        <aside class="answer-block" aria-label="What is Ensurance">
            <p class="answer-block__eyebrow"><?php echo esc_html( $answer['eyebrow'] ); ?></p>
            <h3 class="answer-block__headline"><?php echo esc_html( $answer['headline'] ); ?></h3>
            <?php foreach ( $answer['paragraphs'] as $paragraph ) : ?>
                <p class="answer-block__body"><?php echo esc_html( $paragraph ); ?></p>
            <?php endforeach; ?>
        </aside>

    </div>
</section>
