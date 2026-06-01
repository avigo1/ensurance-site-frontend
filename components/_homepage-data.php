<?php
/**
 * Homepage content — single source of truth for all section copy.
 *
 * Edit text here; markup stays in the section partials under /components/.
 * Returns an associative array consumed by page-home.php.
 *
 * Route placeholders below are intentional. None of these pages exist yet —
 * each link is annotated with a TODO at the point of use.
 */

return array(

    'hero' => array(
        'eyebrow'  => 'Guided insurance request',
        'headline' => 'One guided request. A clearer path to insurance quote options.',
        'subtitle' => 'Ensurance organizes your details so licensed agents, agencies, or approved insurance partners can review available carriers and provide quote options where available.',
        'support'  => 'Instead of starting over with every carrier, begin with one structured request designed for licensed review.',
        'next_module' => array(
            'label' => 'What happens next',
            'steps' => array(
                'Start your guided request',
                'Your details are structured',
                'Licensed professionals review available carriers',
                'Quote options may be provided where available',
            ),
        ),
        'actions' => array(
            // TODO: Missing route — "/start" (guided request flow)
            array( 'label' => 'Start your request',   'href' => '/start',         'variant' => 'primary',   'event' => 'hero_start_request_click' ),
            // TODO: Missing route — "/how-it-works"
            array( 'label' => 'How Ensurance works',  'href' => '/how-it-works',  'variant' => 'secondary', 'event' => 'hero_how_it_works_click' ),
        ),
        'trust_line' => 'Structured request flow. Licensed review. Quote options where available.',
        'flow' => array(
            'label'       => 'Request pathway',
            'start_label' => 'One guided request',
            'end_label'   => 'Multiple carrier options where available',
            'steps' => array(
                array( 'number' => '1', 'title' => 'Start your request',       'state' => 'active' ),
                array( 'number' => '2', 'title' => 'Details are structured',   'state' => 'default' ),
                array( 'number' => '3', 'title' => 'Licensed review',          'state' => 'ready' ),
                array( 'number' => '4', 'title' => 'Options where available',  'state' => 'complete' ),
            ),
        ),
    ),

);
