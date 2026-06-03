<?php
/**
 * /coverage content — single source of truth for the Coverage Types hub.
 *
 * Edit text here; markup lives in section partials under /components/.
 * Returned to page-coverage.php, which composes the partials in display order.
 *
 * Audience: shoppers choosing a guided request path by coverage type.
 * CTA hierarchy: primary "Start your request" → secondary "How Ensurance works".
 *
 * Routing notes:
 *   Coverage card hrefs follow the canonical site map already established in
 *   _homepage-data.php. Auto uses /auto-insurance-quote (not /auto-insurance)
 *   to match the homepage and existing carrier-quote intent.
 */

return array(

    'meta' => array(
        'title'       => 'Coverage Types | Guided Insurance Requests | Ensurance',
        'description' => 'Choose a guided insurance request path for auto, home, renters, life, business, or health. Ensurance structures your request for licensed review.',
        'canonical'   => '/coverage',
    ),

    'hero' => array(
        'eyebrow'  => 'Coverage types',
        'headline' => 'Choose your coverage type. Start one guided request.',
        'subtitle' => 'Ensurance organizes your insurance details so licensed agents, agencies, or approved insurance partners can review available carriers and provide quote options where available.',
        'support'  => 'Choose a coverage type, then begin a structured request for licensed review.',
        'next_module' => array(
            'label' => 'What happens next',
            'steps' => array(
                'Choose your coverage type',
                'Share guided details',
                'Licensed review begins',
                'Quote options where available',
            ),
        ),
        'actions' => array(
            array( 'label' => 'Start your request',  'href' => '/start',        'variant' => 'primary',   'event' => 'coverage_hero_start_request_click' ),
            array( 'label' => 'How Ensurance works', 'href' => '/how-it-works', 'variant' => 'secondary', 'event' => 'coverage_hero_how_it_works_click' ),
        ),
        'trust_line' => 'Structured request flow. Licensed review. Quote options where available.',
        'flow' => array(
            'label'       => 'Find your request path',
            'start_label' => 'Choose coverage type',
            'end_label'   => 'Licensed review of available options',
            'steps' => array(
                array( 'number' => '1', 'title' => 'Choose your coverage type', 'state' => 'active' ),
                array( 'number' => '2', 'title' => 'Share guided details',      'state' => 'default' ),
                array( 'number' => '3', 'title' => 'Licensed review',           'state' => 'ready' ),
                array( 'number' => '4', 'title' => 'Options where available',   'state' => 'complete' ),
            ),
        ),
    ),

    'proof' => array(
        array( 'title' => 'Choose the coverage path',       'body' => 'Choose auto, home, renters, life, business, or health.' ),
        array( 'title' => 'Structured before review',       'body' => 'Your request is organized for licensed review.' ),
        array( 'title' => 'Quote options where available',  'body' => 'Available carriers can be reviewed where available.' ),
    ),

    'coverage' => array(
        'eyebrow'  => 'Coverage request paths',
        'headline' => 'Choose the insurance type that fits your situation.',
        'cards' => array(
            array( 'title' => 'Auto insurance',     'body' => 'Start a guided auto request for licensed review of available carrier options.',                'href' => '/auto-insurance-quote', 'label' => 'Start auto request',     'event' => 'coverage_path_auto_click' ),
            array( 'title' => 'Home insurance',     'body' => 'Structure your home details for licensed review of available carrier options.',                'href' => '/home-insurance',       'label' => 'Start home request',     'event' => 'coverage_path_home_click' ),
            array( 'title' => 'Renters insurance',  'body' => 'Begin a renters request built to clarify your coverage needs before licensed review.',         'href' => '/renters-insurance',    'label' => 'Start renters request',  'event' => 'coverage_path_renters_click' ),
            array( 'title' => 'Life insurance',     'body' => 'Share life insurance goals through a guided request for licensed review where available.',     'href' => '/life-insurance',       'label' => 'Start life request',     'event' => 'coverage_path_life_click' ),
            array( 'title' => 'Business insurance', 'body' => 'Organize your business details for licensed review of available carrier options.',              'href' => '/business-insurance',   'label' => 'Start business request', 'event' => 'coverage_path_business_click' ),
            array( 'title' => 'Health insurance',   'body' => 'Begin a guided health request for available options through licensed review where applicable.', 'href' => '/health-insurance',     'label' => 'Start health request',   'event' => 'coverage_path_health_click' ),
        ),
    ),

    // "Start with what you know" — reuses the one-request two-column pattern.
    'start_with_what_you_know' => array(
        'eyebrow'  => 'Not sure where to start',
        'headline' => 'Start with what you know.',
        'lead'     => 'Choose the closest path. Guided questions help clarify your request before licensed review.',
        'body'     => 'You do not need a final answer to begin. Pick the closest coverage type and Ensurance structures the rest before review.',
        'action'   => array( 'label' => 'Start your request', 'href' => '/start', 'variant' => 'primary', 'event' => 'coverage_start_with_what_you_know_click' ),
        'cards' => array(
            array( 'number' => '01', 'title' => 'Start with what you know',                  'body' => 'Choose the closest coverage type and answer guided questions.' ),
            array( 'number' => '02', 'title' => 'Your details are structured',               'body' => 'Your information is organized into a clearer request.' ),
            array( 'number' => '03', 'title' => 'Licensed review helps clarify next steps',  'body' => 'Licensed professionals can explain next steps where available.' ),
        ),
    ),

    'process' => array(
        'eyebrow'  => 'How it works',
        'headline' => 'One path. A clearer review process.',
        'steps' => array(
            array( 'number' => '1', 'title' => 'Choose your coverage type',                            'body' => 'Choose the category that fits.' ),
            array( 'number' => '2', 'title' => 'Share guided details',                                 'body' => 'Answer guided questions.' ),
            array( 'number' => '3', 'title' => 'Licensed professionals review available carriers',     'body' => 'Available carriers can be reviewed based on your request.' ),
            array( 'number' => '4', 'title' => 'Quote options where available',                        'body' => 'When options are available, a licensed professional can explain next steps.' ),
        ),
        'action'     => array( 'label' => 'Start your request', 'href' => '/start', 'variant' => 'primary', 'event' => 'coverage_process_start_request_click' ),
        'trust_line' => 'Availability, eligibility, carrier participation, location, and licensed review determine quote options.',
    ),

    'difference' => array(
        'eyebrow'  => 'A more organized way to begin',
        'headline' => 'Do not start with scattered forms.',
        'lead'     => 'Ensurance gives shoppers a structured way to begin across common coverage types.',
        'cards' => array(
            array( 'title' => 'Guided by coverage type',     'body' => 'Each path is built around your coverage type.' ),
            array( 'title' => 'Structured before review',    'body' => 'Your details are organized before review.' ),
            array( 'title' => 'Built for clear expectations', 'body' => 'Quote options depend on availability, eligibility, and licensed review.' ),
            array( 'title' => 'Designed for shopper control', 'body' => 'Reduce confusion, repeated forms, and unclear next steps.' ),
        ),
    ),

    'protected' => array(
        'eyebrow'  => 'Trust and privacy',
        'headline' => 'A controlled flow for insurance details.',
        'lead'     => 'Insurance requests can involve personal details. Ensurance keeps the starting point structured and clear.',
        'action'   => array( 'label' => 'Read Trust and Privacy', 'href' => '/trust-and-privacy', 'variant' => 'primary', 'event' => 'coverage_trust_privacy_click' ),
        'disclosure' => 'Your information is used to structure your request and support licensed review. Availability and quote options vary by coverage type, location, eligibility, and carrier appetite.',
        'trust_points' => array(
            'Your request is guided from the start.',
            'Your details are organized before review.',
            'Licensed professionals can review available carrier options.',
            'Quote options vary by coverage type, location, eligibility, and carrier availability.',
            'Trust and Privacy explains the request experience.',
        ),
    ),

    'agent_review' => array(
        'eyebrow'  => 'Licensed review',
        'headline' => 'Prepared for licensed review.',
        'body'     => 'Ensurance prepares requests for licensed agents, agencies, or approved insurance partners to review available carriers and explain quote options where available.',
        'note'     => 'Not every carrier or quote option is available for every request. Availability depends on coverage type, location, eligibility, carrier appetite, and licensed professional review.',
    ),

    'faq_intro' => array(
        'eyebrow'  => 'Coverage questions',
        'headline' => 'Clear answers before you choose a path.',
        'lead'     => 'Choose a path with realistic expectations.',
        'action'   => array( 'label' => 'Start your request', 'href' => '/start', 'variant' => 'primary', 'event' => 'coverage_faq_start_request_click' ),
    ),

    'faq' => array(
        array(
            'key'      => 'coverage_types_supported',
            'question' => 'What insurance coverage types can I start a request for?',
            'answer'   => 'Ensurance supports guided paths for auto, home, renters, life, business, and health insurance where applicable.',
        ),
        array(
            'key'      => 'multiple_carrier_options',
            'question' => 'Can one request help me access multiple carrier options?',
            'answer'   => 'Yes. One guided request can create a clearer path to available carrier options through licensed agents, agencies, or approved insurance partners. Quote options depend on your request, location, eligibility, carrier availability, and licensed review.',
        ),
        array(
            'key'      => 'know_exact_coverage',
            'question' => 'Do I need to know exactly what coverage I need?',
            'answer'   => 'No. Choose the closest coverage type. Guided questions help structure your request before licensed review.',
        ),
        array(
            'key'      => 'who_reviews_my_request',
            'question' => 'Who reviews my coverage request?',
            'answer'   => 'Licensed agents, agencies, or approved insurance partners may review available carriers and provide quote options where available.',
        ),
        array(
            'key'      => 'quote_options_every_type',
            'question' => 'Will I receive quotes for every coverage type?',
            'answer'   => 'Quote options are not available for every request. Availability depends on coverage type, location, eligibility, carrier participation, and licensed review.',
        ),
        array(
            'key'      => 'is_quote_comparison',
            'question' => 'Is Ensurance a quote comparison site?',
            'answer'   => 'Ensurance is built around guided requests. It organizes your details for licensed review of available carrier options.',
        ),
        array(
            'key'      => 'information_sent_everywhere',
            'question' => 'Will my information be sent everywhere?',
            'answer'   => 'Ensurance uses a controlled request flow. Your information is structured for licensed insurance evaluation. Review Trust and Privacy and the Privacy Policy for details.',
        ),
        array(
            'key'      => 'after_choosing_coverage',
            'question' => 'What happens after I choose a coverage type?',
            'answer'   => 'Answer guided questions for that coverage type. Ensurance structures the request for licensed review of available options where available.',
        ),
    ),

    'final_cta' => array(
        'eyebrow'  => 'Choose your coverage path',
        'headline' => 'Start with one guided request.',
        'body'     => 'Choose your insurance type. Ensurance organizes your details for a clearer path to available quote options through licensed agents, agencies, or approved insurance partners.',
        'actions' => array(
            array( 'label' => 'Start your request',  'href' => '/start',        'variant' => 'primary',   'event' => 'coverage_final_start_request_click' ),
            array( 'label' => 'How Ensurance works', 'href' => '/how-it-works', 'variant' => 'secondary', 'event' => 'coverage_final_how_it_works_click' ),
        ),
        'trust_line' => 'Guided request. Licensed review. Quote options where available.',
    ),

);
