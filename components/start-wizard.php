<?php
/**
 * Start — Guided intake wizard.
 *
 * Required PDF sections rendered here:
 *   Coverage type, Service area, Request reason, Request details, Timing,
 *   Human support preference, Contact details, Review request, Confirmation.
 *
 * Markup is one semantic <form> with a <fieldset> per step. JavaScript reveals
 * one step at a time. With JS disabled, all fieldsets are visible and the form
 * still submits via native HTML5 validation.
 *
 * Expects $wizard (array) set by the caller (page-start.php).
 */

if ( ! isset( $wizard ) || ! is_array( $wizard ) ) {
    return;
}

/**
 * Render a single field row.
 *
 * @param array $field
 */
function ensurance_start_render_field( $field ) {
    $id           = esc_attr( $field['id'] );
    $name         = esc_attr( $field['name'] );
    $type         = $field['type'];
    $required     = ! empty( $field['required'] );
    $autocomplete = isset( $field['autocomplete'] ) ? esc_attr( $field['autocomplete'] ) : null;
    $inputmode    = isset( $field['inputmode'] ) ? esc_attr( $field['inputmode'] ) : null;
    $pattern      = isset( $field['pattern'] ) ? esc_attr( $field['pattern'] ) : null;
    $help         = isset( $field['help'] ) ? $field['help'] : null;
    $help_id      = $help ? $id . '-help' : null;
    ?>
    <div class="start-field start-field--<?php echo esc_attr( $type ); ?>">
        <?php if ( $type !== 'radio' ) : ?>
            <label class="start-field__label" for="<?php echo $id; ?>">
                <?php echo esc_html( $field['label'] ); ?>
                <?php if ( ! $required ) : ?>
                    <span class="start-field__optional">Optional</span>
                <?php endif; ?>
            </label>
        <?php endif; ?>

        <?php if ( $type === 'select' ) : ?>
            <select id="<?php echo $id; ?>"
                    name="<?php echo $name; ?>"
                    <?php if ( $required ) echo 'required'; ?>
                    <?php if ( $help_id ) echo 'aria-describedby="' . esc_attr( $help_id ) . '"'; ?>>
                <?php if ( ! empty( $field['placeholder'] ) ) : ?>
                    <option value=""><?php echo esc_html( $field['placeholder'] ); ?></option>
                <?php endif; ?>
                <?php foreach ( $field['options'] as $option ) : ?>
                    <option value="<?php echo esc_attr( $option ); ?>">
                        <?php echo esc_html( $option ); ?>
                    </option>
                <?php endforeach; ?>
            </select>

        <?php elseif ( $type === 'textarea' ) : ?>
            <textarea id="<?php echo $id; ?>"
                      name="<?php echo $name; ?>"
                      rows="6"
                      <?php if ( $required ) echo 'required'; ?>
                      <?php if ( $help_id ) echo 'aria-describedby="' . esc_attr( $help_id ) . '"'; ?>></textarea>

        <?php elseif ( $type === 'radio' ) : ?>
            <fieldset class="start-radio-group" <?php if ( $help_id ) echo 'aria-describedby="' . esc_attr( $help_id ) . '"'; ?>>
                <legend class="start-field__label">
                    <?php echo esc_html( $field['label'] ); ?>
                </legend>
                <div class="start-radio-group__options">
                    <?php foreach ( $field['options'] as $value => $label ) : ?>
                        <?php $option_id = $id . '-' . sanitize_title( $value ); ?>
                        <label class="start-radio" for="<?php echo esc_attr( $option_id ); ?>">
                            <input id="<?php echo esc_attr( $option_id ); ?>"
                                   type="radio"
                                   name="<?php echo $name; ?>"
                                   value="<?php echo esc_attr( $value ); ?>"
                                   <?php if ( $required ) echo 'required'; ?>>
                            <span class="start-radio__dot" aria-hidden="true"></span>
                            <span class="start-radio__label"><?php echo esc_html( $label ); ?></span>
                        </label>
                    <?php endforeach; ?>
                </div>
            </fieldset>

        <?php else : // text, email, tel ?>
            <input id="<?php echo $id; ?>"
                   name="<?php echo $name; ?>"
                   type="<?php echo esc_attr( $type ); ?>"
                   <?php if ( $required )     echo 'required'; ?>
                   <?php if ( $autocomplete ) echo 'autocomplete="' . $autocomplete . '"'; ?>
                   <?php if ( $inputmode )    echo 'inputmode="' . $inputmode . '"'; ?>
                   <?php if ( $pattern )      echo 'pattern="' . $pattern . '"'; ?>
                   <?php if ( $help_id )      echo 'aria-describedby="' . esc_attr( $help_id ) . '"'; ?>>
        <?php endif; ?>

        <?php if ( $help ) : ?>
            <p id="<?php echo esc_attr( $help_id ); ?>" class="start-field__help">
                <?php echo esc_html( $help ); ?>
            </p>
        <?php endif; ?>
    </div>
    <?php
}

$total_steps = count( $wizard['steps'] );
?>

<section id="start-wizard" class="start-wizard" aria-labelledby="start-wizard-heading">
    <div class="container">

        <div class="start-wizard__shell">

            <div class="start-wizard__progress" data-progress aria-hidden="true">
                <div class="start-wizard__progress-track">
                    <div class="start-wizard__progress-fill" data-progress-fill style="width: <?php echo esc_attr( round( 100 / $total_steps ) ); ?>%"></div>
                </div>
                <p class="start-wizard__progress-label">
                    <span data-progress-current>1</span> of <?php echo esc_html( $total_steps ); ?>
                </p>
            </div>

            <p class="start-wizard__notice" role="note">
                <?php echo esc_html( $wizard['notice'] ); ?>
            </p>

            <form class="start-wizard__form"
                  data-start-form
                  data-redirect="<?php echo esc_url( $wizard['redirect'] ); ?>"
                  novalidate>

                <h2 id="start-wizard-heading" class="sr-only">Guided insurance request</h2>

                <?php foreach ( $wizard['steps'] as $index => $step ) : ?>
                    <fieldset
                        class="start-step <?php if ( $index === 0 ) echo 'is-active'; ?>"
                        data-step
                        data-step-id="<?php echo esc_attr( $step['id'] ); ?>"
                        data-step-index="<?php echo esc_attr( $index ); ?>"
                        <?php if ( $index !== 0 ) echo 'hidden'; ?>>

                        <legend class="sr-only"><?php echo esc_html( $step['title'] ); ?></legend>

                        <p class="start-step__eyebrow"><?php echo esc_html( $step['eyebrow'] ); ?></p>
                        <h3 class="start-step__title"><?php echo esc_html( $step['title'] ); ?></h3>
                        <p class="start-step__description"><?php echo esc_html( $step['description'] ); ?></p>

                        <?php if ( ! empty( $step['review'] ) ) : ?>
                            <div class="start-review" data-review aria-live="polite">
                                <p class="start-review__empty">Your answers will appear here when you reach the review step.</p>
                            </div>
                        <?php else : ?>
                            <div class="start-step__fields">
                                <?php foreach ( $step['fields'] as $field ) : ?>
                                    <?php ensurance_start_render_field( $field ); ?>
                                <?php endforeach; ?>
                            </div>
                        <?php endif; ?>

                        <div class="start-step__actions">
                            <?php if ( $index > 0 ) : ?>
                                <button type="button"
                                        class="btn btn--secondary"
                                        data-step-back
                                        data-event="start_step_back_click">Back</button>
                            <?php endif; ?>

                            <?php if ( $index < $total_steps - 1 ) : ?>
                                <button type="button"
                                        class="btn btn--primary"
                                        data-step-next
                                        data-event="start_step_next_click">Continue</button>
                            <?php else : ?>
                                <button type="submit"
                                        class="btn btn--primary"
                                        data-event="start_request_submit_click">
                                    <?php echo esc_html( $wizard['submit_label'] ); ?>
                                </button>
                            <?php endif; ?>
                        </div>

                        <p class="start-step__error" data-step-error hidden role="alert"></p>

                    </fieldset>
                <?php endforeach; ?>

            </form>

            <div class="start-confirmation"
                 data-confirmation
                 hidden
                 aria-live="polite"
                 aria-labelledby="start-confirmation-title">
                <p class="eyebrow"><?php echo esc_html( $wizard['confirmation']['eyebrow'] ); ?></p>
                <h3 id="start-confirmation-title" class="start-confirmation__title">
                    <?php echo esc_html( $wizard['confirmation']['title'] ); ?>
                </h3>
                <p class="start-confirmation__body"><?php echo esc_html( $wizard['confirmation']['body'] ); ?></p>
                <p class="start-confirmation__reference">
                    <span class="start-confirmation__reference-label">
                        <?php echo esc_html( $wizard['confirmation']['reference'] ); ?>
                    </span>
                    <span class="start-confirmation__reference-value" data-confirmation-reference></span>
                </p>
                <a class="btn btn--primary"
                   href="<?php echo esc_url( $wizard['confirmation']['fallback']['href'] ); ?>"
                   data-confirmation-link
                   data-event="start_confirmation_continue_click">
                    <?php echo esc_html( $wizard['confirmation']['fallback']['label'] ); ?>
                </a>
            </div>

        </div>
    </div>
</section>
