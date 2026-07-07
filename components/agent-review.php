<?php
/**
 * Licensed review — wide card with two-column copy + side note.
 * Expects $agent_review (array).
 */

if ( ! isset( $agent_review ) || ! is_array( $agent_review ) ) {
    return;
}
?>

<section class="section section--soft section--agent">
    <div class="container">
        <div class="agent-card">

            <div class="agent-card__copy">
                <p class="eyebrow"><?php echo esc_html( $agent_review['eyebrow'] ); ?></p>
                <h2><?php echo esc_html( $agent_review['headline'] ); ?></h2>
                <p class="agent-card__body"><?php echo esc_html( $agent_review['body'] ); ?></p>
            </div>

            <aside class="agent-card__note">
                <p><?php echo esc_html( $agent_review['note'] ); ?></p>
            </aside>

        </div>
    </div>
</section>
