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

    'proof' => array(
        array( 'title' => 'One request to begin',  'body' => 'Start with a guided insurance request instead of repeating details across disconnected forms.' ),
        array( 'title' => 'Structured for review', 'body' => 'Ensurance organizes your information so licensed professionals can better understand your request.' ),
        array( 'title' => 'Pathway to options',    'body' => 'Available carriers can be reviewed, and quote options may be provided where available.' ),
    ),

    'one_request' => array(
        'eyebrow'  => 'One request, available carrier options',
        'headline' => 'A clearer way to begin insurance shopping.',
        'lead'     => 'Insurance shopping often makes people start over with every carrier, form, or website. Ensurance gives you a more organized first step.',
        'body'     => 'Start with one guided request. Ensurance structures your information so licensed professionals can review available carriers and respond with quote options where available.',
        // TODO: Missing route — "/start"
        'action' => array( 'label' => 'Start your request', 'href' => '/start', 'variant' => 'primary', 'event' => 'one_request_start_click' ),
        'cards' => array(
            array( 'number' => '01', 'title' => 'Share your insurance need once',       'body' => 'Capture the details needed for review.' ),
            array( 'number' => '02', 'title' => 'Reduce repeated form filling',         'body' => 'Create one organized starting point.' ),
            array( 'number' => '03', 'title' => 'Move toward available quote options',  'body' => 'Move into licensed review where options may be available.' ),
        ),
    ),

    'process' => array(
        'eyebrow'  => 'How Ensurance works',
        'headline' => 'A controlled request process from start to review.',
        'steps' => array(
            array( 'number' => '1', 'title' => 'Tell us what you need',                            'body' => 'Choose coverage and share key details.' ),
            array( 'number' => '2', 'title' => 'Ensurance structures your request',                'body' => 'Your information becomes review-ready.' ),
            array( 'number' => '3', 'title' => 'Licensed professionals review available carriers', 'body' => 'Available carriers can be evaluated.' ),
            array( 'number' => '4', 'title' => 'You receive quote options where available',        'body' => 'A licensed professional can explain next steps.' ),
        ),
        // TODO: Missing route — "/start"
        'action'     => array( 'label' => 'Start your guided request', 'href' => '/start', 'variant' => 'primary', 'event' => 'process_start_request_click' ),
        'trust_line' => 'Availability, eligibility, carrier participation, and licensed professional review determine which quote options may be available.',
    ),

    'difference' => array(
        'eyebrow'  => 'What makes Ensurance different',
        'headline' => 'Built for guided insurance requests, not quote-site confusion.',
        'lead'     => 'Ensurance is designed for shoppers who want a more controlled way to begin. Your request is structured before review, then prepared for licensed professionals who can evaluate available carrier options.',
        'cards' => array(
            array( 'title' => 'Guided, not scattered',                  'body' => 'Begin with one structured request.' ),
            array( 'title' => 'Structured, not generic',                'body' => 'Your information is organized before review.' ),
            array( 'title' => 'Licensed review, not blind comparison',  'body' => 'Available carriers are reviewed by licensed professionals.' ),
            array( 'title' => 'Focused on available options',           'body' => 'A clearer path to options where available.' ),
        ),
    ),

    'protected' => array(
        'eyebrow'  => 'Protected insurance shopping',
        'headline' => 'A more protected way to start your insurance request.',
        'lead'     => 'Insurance shopping involves personal details. Ensurance is designed to make that process more structured, intentional, and clear.',
        // TODO: Missing route — "/start"
        'action'   => array( 'label' => 'Start your request', 'href' => '/start', 'variant' => 'primary', 'event' => 'protected_start_request_click' ),
        'disclosure' => 'Your information is used to structure your request and support licensed review. Availability and quote options vary.',
        'trust_points' => array(
            'Your request is guided from the start.',
            'Your information is structured before review.',
            'Licensed professionals can review available carrier options.',
            'The flow is designed to reduce repeated forms and unclear next steps.',
            'Available quote options vary by carrier, location, eligibility, and coverage type.',
        ),
    ),

    'coverage' => array(
        'eyebrow'  => 'Coverage types',
        'headline' => 'Start a guided request for the coverage you need.',
        'cards' => array(
            // TODO: Missing route — "/auto-insurance"
            array( 'title' => 'Auto insurance',     'body' => 'Start a guided auto request with a clearer path to available carrier options through licensed review.',     'href' => '/auto-insurance',     'label' => 'Start auto request',     'event' => 'coverage_auto_click' ),
            // TODO: Missing route — "/home-insurance"
            array( 'title' => 'Home insurance',     'body' => 'Start a guided home request with a clearer path to available carrier options through licensed review.',     'href' => '/home-insurance',     'label' => 'Start home request',     'event' => 'coverage_home_click' ),
            // TODO: Missing route — "/renters-insurance"
            array( 'title' => 'Renters insurance',  'body' => 'Start a guided renters request with a clearer path to available carrier options through licensed review.',  'href' => '/renters-insurance',  'label' => 'Start renters request',  'event' => 'coverage_renters_click' ),
            // TODO: Missing route — "/life-insurance"
            array( 'title' => 'Life insurance',     'body' => 'Start a guided life request with a clearer path to available carrier options through licensed review.',     'href' => '/life-insurance',     'label' => 'Start life request',     'event' => 'coverage_life_click' ),
            // TODO: Missing route — "/business-insurance"
            array( 'title' => 'Business insurance', 'body' => 'Start a guided business request with a clearer path to available carrier options through licensed review.', 'href' => '/business-insurance', 'label' => 'Start business request', 'event' => 'coverage_business_click' ),
            // TODO: Missing route — "/health-insurance"
            array( 'title' => 'Health insurance',   'body' => 'Start a guided health request with a clearer path to available carrier options through licensed review.',   'href' => '/health-insurance',   'label' => 'Start health request',   'event' => 'coverage_health_click' ),
        ),
    ),

    'agent_review' => array(
        'eyebrow'  => 'Licensed review',
        'headline' => 'Prepared for licensed insurance review.',
        'body'     => 'Ensurance helps prepare your request for licensed agents, agencies, or approved insurance partners who can review available carriers and help you understand quote options where available.',
        'note'     => 'Carrier availability, quote options, and eligibility can vary by location, coverage type, carrier appetite, and licensed professional review.',
    ),

    'faq_intro' => array(
        'eyebrow'  => 'Questions before you start',
        'headline' => 'Clear answers for a controlled request process.',
        'lead'     => 'Ensurance helps shoppers start with clarity and realistic expectations.',
        // TODO: Missing route — "/start"
        'action'   => array( 'label' => 'Start your request', 'href' => '/start', 'variant' => 'primary', 'event' => 'faq_start_request_click' ),
    ),

    'faq' => array(
        array(
            'key'      => 'multiple_carrier_options',
            'question' => 'Can one request help me access multiple carrier options?',
            'answer'   => 'Yes. One guided Ensurance request can help create a clearer path to multiple carrier options through licensed agents, agencies, or approved insurance partners. Available quote options depend on your request, location, eligibility, carrier availability, and licensed professional review.',
        ),
        array(
            'key'      => 'direct_quote_provider',
            'question' => 'Does Ensurance provide the quote directly?',
            'answer'   => 'Ensurance helps organize your request so licensed agents, agencies, or approved insurance partners can review available carriers and provide quote options where available.',
        ),
        array(
            'key'      => 'licensed_review',
            'question' => 'Who reviews my request?',
            'answer'   => 'Your request may be reviewed by licensed agents, agencies, or approved insurance partners who can evaluate available carrier options based on the information you provide.',
        ),
        array(
            'key'      => 'controlled_information_flow',
            'question' => 'Will my information be sent everywhere?',
            'answer'   => 'Ensurance is designed around a more controlled request flow. Your information is structured to help licensed insurance professionals evaluate available options and reduce unclear next steps.',
        ),
        array(
            'key'      => 'not_quote_comparison',
            'question' => 'Is Ensurance a quote comparison site?',
            'answer'   => 'Ensurance is built around guided insurance requests. Instead of showing a public list of instant quote results, Ensurance organizes your details for licensed review of available carrier options.',
        ),
        array(
            'key'      => 'after_start_request',
            'question' => 'What happens after I start a request?',
            'answer'   => 'You share the details needed for your insurance request. Ensurance structures that information for licensed review. When quote options are available, a licensed professional can help you understand possible next steps.',
        ),
    ),

    'final_cta' => array(
        'eyebrow'  => 'Begin with one guided request',
        'headline' => 'A clearer path to insurance quote options starts here.',
        'body'     => 'Ensurance helps organize your information and create a clearer path to available quote options through licensed agents, agencies, or approved insurance partners.',
        'actions' => array(
            // TODO: Missing route — "/start"
            array( 'label' => 'Start your request',  'href' => '/start',         'variant' => 'primary',   'event' => 'final_start_request_click' ),
            // TODO: Missing route — "/how-it-works"
            array( 'label' => 'See how it works',    'href' => '/how-it-works',  'variant' => 'secondary', 'event' => 'final_how_it_works_click' ),
        ),
        'trust_line' => 'No pressure. Clearer steps. Licensed professional review.',
    ),

);
