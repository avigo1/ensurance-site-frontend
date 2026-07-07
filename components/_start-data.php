<?php
/**
 * Start Your Request — content source.
 *
 * Single source of truth for /start. Edit copy here; markup stays in the
 * section partials under /components/start-*.php.
 *
 * Returns an associative array consumed by page-start.php.
 *
 * Tone: Calm Intelligence (calm, clear, grounded, premium, modern, human, direct).
 * No em dashes anywhere in shopper-facing copy.
 *
 * Banned language scan (do not introduce these phrases):
 *   compare quotes, get matched, submit your information, send my info,
 *   agents compete, get quotes now, buy leads, lead packages,
 *   exclusive leads, guaranteed volume, flood your pipeline,
 *   cheap leads, instant quotes, public marketplace.
 */

return array(

    /**
     * Section 1 — Welcome screen.
     * Trust-first introduction. Explains what happens next before asking
     * for any action.
     */
    'intro' => array(
        'eyebrow'  => 'Start your request',
        'headline' => 'Start one guided insurance request.',
        'subtitle' => 'Share the details needed to organize your request. Ensurance structures your information so licensed agents, agencies, or approved insurance partners can review available carriers and respond with quote options where available.',
        'support'  => 'You stay in control of who reaches out and when. Your details are not broadcast in bulk and are not posted for open browsing.',
        'actions'  => array(
            // Secondary CTA per PDF (Primary CTA is the wizard submit itself).
            array( 'label' => 'How Ensurance works', 'href' => '/how-it-works', 'variant' => 'secondary', 'event' => 'start_how_it_works_click' ),
        ),
        'next_module' => array(
            'label' => 'What happens next',
            'steps' => array(
                'You share the details needed to organize your request.',
                'Ensurance structures your information for licensed review.',
                'Licensed professionals can review available carriers.',
                'Quote options may be provided where available.',
            ),
        ),
        'trust_line' => 'Structured request flow. Licensed review. Quote options where available.',
        'privacy_points' => array(
            'Your request is not posted for open browsing or sold to lead aggregators.',
            'You decide whether to share contact details or stay anonymous at first.',
            'Sensitive items like Social Security numbers and payment details are never requested here.',
        ),
    ),

    /**
     * Section 2 - 9 — Wizard steps (Coverage → Review).
     * The form is rendered as one semantic <form> with a <fieldset> per step.
     * JavaScript reveals one step at a time; the no-JS fallback shows every
     * step stacked so the page is fully usable without scripting.
     */
    'wizard' => array(
        'submit_label' => 'Start my request',
        'redirect'     => '/request-received',
        'notice'       => 'Please do not include Social Security numbers, payment details, full account numbers, or other sensitive documents in your request.',

        'steps' => array(

            // Step 1 — Coverage type
            array(
                'id'          => 'coverage',
                'eyebrow'     => 'Step 1 of 8',
                'title'       => 'What coverage do you need?',
                'description' => 'Pick the coverage you want to start with. You can adjust this later in the review step.',
                'fields' => array(
                    array(
                        'id'       => 'coverageType',
                        'name'     => 'coverageType',
                        'label'    => 'Coverage type',
                        'type'     => 'select',
                        'required' => true,
                        'options'  => array(
                            'Auto insurance',
                            'Home insurance',
                            'Renters insurance',
                            'Life insurance',
                            'Business insurance',
                            'Health insurance',
                            'Not sure yet',
                        ),
                        'placeholder' => 'Choose a coverage type',
                    ),
                ),
            ),

            // Step 2 — Service area
            array(
                'id'          => 'area',
                'eyebrow'     => 'Step 2 of 8',
                'title'       => 'Where will the coverage be used?',
                'description' => 'Availability, eligibility, and carrier participation vary by location. ZIP is optional.',
                'fields' => array(
                    array(
                        'id'           => 'state',
                        'name'         => 'state',
                        'label'        => 'State',
                        'type'         => 'text',
                        'required'     => true,
                        'autocomplete' => 'address-level1',
                        'help'         => 'Two letter code or full name.',
                    ),
                    array(
                        'id'           => 'zip',
                        'name'         => 'zip',
                        'label'        => 'ZIP code',
                        'type'         => 'text',
                        'required'     => false,
                        'autocomplete' => 'postal-code',
                        'help'         => 'Optional. Helps with location based carrier availability.',
                        'inputmode'    => 'numeric',
                        'pattern'      => '\d{5}(-\d{4})?',
                    ),
                ),
            ),

            // Step 3 — Request reason
            array(
                'id'          => 'reason',
                'eyebrow'     => 'Step 3 of 8',
                'title'       => 'What is bringing you to Ensurance today?',
                'description' => 'A short signal helps licensed professionals prepare for the next step.',
                'fields' => array(
                    array(
                        'id'       => 'reason',
                        'name'     => 'reason',
                        'label'    => 'Reason for this request',
                        'type'     => 'radio',
                        'required' => true,
                        'options'  => array(
                            'New coverage'      => 'I need new coverage.',
                            'Switch carrier'    => 'I am thinking about switching from my current carrier.',
                            'Life change'       => 'A life change is prompting a coverage review.',
                            'Renewal coming up' => 'A renewal is coming up soon.',
                            'Just exploring'    => 'I am just exploring options for now.',
                        ),
                    ),
                ),
            ),

            // Step 4 — Request details
            array(
                'id'          => 'details',
                'eyebrow'     => 'Step 4 of 8',
                'title'       => 'Share the context that helps explain your request.',
                'description' => 'A short paragraph is enough. Skip anything you would rather discuss with a licensed professional later.',
                'fields' => array(
                    array(
                        'id'       => 'details',
                        'name'     => 'details',
                        'label'    => 'Request details',
                        'type'     => 'textarea',
                        'required' => true,
                        'help'     => 'Examples: vehicles or property to cover, household size, current coverage you want to keep or change.',
                    ),
                ),
            ),

            // Step 5 — Timing
            array(
                'id'          => 'timing',
                'eyebrow'     => 'Step 5 of 8',
                'title'       => 'What is the timing on your side?',
                'description' => 'No pressure. Sharing timing helps licensed professionals plan a calmer follow up.',
                'fields' => array(
                    array(
                        'id'       => 'timing',
                        'name'     => 'timing',
                        'label'    => 'Timing',
                        'type'     => 'radio',
                        'required' => true,
                        'options'  => array(
                            'Right away'   => 'Right away.',
                            'Within weeks' => 'Within the next few weeks.',
                            'Within months' => 'Within the next few months.',
                            'Just exploring' => 'I am just exploring for now.',
                        ),
                    ),
                ),
            ),

            // Step 6 — Human support preference
            array(
                'id'          => 'support',
                'eyebrow'     => 'Step 6 of 8',
                'title'       => 'How would you prefer to hear back?',
                'description' => 'Choose the channels you are comfortable with. You decide whether to engage further.',
                'fields' => array(
                    array(
                        'id'       => 'supportChannel',
                        'name'     => 'supportChannel',
                        'label'    => 'Preferred contact method',
                        'type'     => 'radio',
                        'required' => true,
                        'options'  => array(
                            'Email'        => 'Email first.',
                            'Phone call'   => 'A phone call is fine.',
                            'Text message' => 'A text message is fine.',
                            'No preference' => 'No preference. Use whichever is easiest.',
                        ),
                    ),
                    array(
                        'id'       => 'supportTime',
                        'name'     => 'supportTime',
                        'label'    => 'Best time to reach you',
                        'type'     => 'select',
                        'required' => false,
                        'options'  => array(
                            'Mornings',
                            'Afternoons',
                            'Evenings',
                            'Weekends',
                            'Anytime',
                        ),
                        'placeholder' => 'Optional',
                    ),
                ),
            ),

            // Step 7 — Contact details
            array(
                'id'          => 'contact',
                'eyebrow'     => 'Step 7 of 8',
                'title'       => 'How can a licensed professional reach you?',
                'description' => 'Phone is optional. Your contact details are used to follow up about this request only.',
                'fields' => array(
                    array(
                        'id'           => 'name',
                        'name'         => 'name',
                        'label'        => 'Full name',
                        'type'         => 'text',
                        'required'     => true,
                        'autocomplete' => 'name',
                    ),
                    array(
                        'id'           => 'email',
                        'name'         => 'email',
                        'label'        => 'Email address',
                        'type'         => 'email',
                        'required'     => true,
                        'autocomplete' => 'email',
                    ),
                    array(
                        'id'           => 'phone',
                        'name'         => 'phone',
                        'label'        => 'Phone number',
                        'type'         => 'tel',
                        'required'     => false,
                        'autocomplete' => 'tel',
                        'help'         => 'Optional.',
                    ),
                ),
            ),

            // Step 8 — Review
            array(
                'id'          => 'review',
                'eyebrow'     => 'Step 8 of 8',
                'title'       => 'Review your request before sending.',
                'description' => 'Double check the details. You can step back to edit any answer before submitting.',
                'fields'      => array(),
                'review'      => true,
            ),
        ),

        /**
         * Confirmation screen — replaces the wizard on successful submit.
         * Briefly visible before the redirect to /request-received.
         */
        'confirmation' => array(
            'eyebrow'   => 'Request received',
            'title'     => 'Your request is on its way.',
            'body'      => 'Ensurance is organizing your details for licensed review. You will be redirected to a confirmation page in a moment.',
            'reference' => 'Your reference number',
            'fallback'  => array(
                'label' => 'Open confirmation page',
                'href'  => '/request-received',
            ),
        ),
    ),
);
