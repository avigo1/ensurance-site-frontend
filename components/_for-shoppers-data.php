<?php
/**
 * /for-shoppers content — single source of truth.
 *
 * Edit text here; markup lives in section partials under /components/.
 * Returned to page-for-shoppers.php, which composes the partials in order.
 *
 * Audience: shoppers evaluating whether to start a guided request.
 * Workbook compliance:
 *   - "In short" answer card renders within the first 150 words of visible
 *     content (third section, immediately after the problem framing).
 *   - All copy uses approved phrases (guided request, licensed review,
 *     approved insurance partners, where available) and avoids restricted
 *     language (no instant quotes, no guarantees, no broad data-sharing).
 *   - "One licensed agent" intent from the Figma is broadened to
 *     "licensed agents, agencies, or approved insurance partners" where
 *     used in metadata / schema / outbound positioning.
 */

return array(

    'meta' => array(
        'title'       => 'For Shoppers | More Insurance Choice, Less Quote Chaos | Ensurance',
        'description' => 'Start one guided insurance request and move toward available insurance options through licensed professionals, without restarting company after company.',
        'canonical'   => '/for-shoppers',
        'og_title'    => 'For Shoppers | More Insurance Choice, Less Quote Chaos | Ensurance',
        'og_desc'     => 'One guided insurance request. More potential insurance choice. Licensed help where available, through a more controlled shopping process.',
    ),

    'hero' => array(
        'eyebrow'   => 'For shoppers',
        // Pipe splits the headline into two lines; matches the Figma's stacked layout.
        'headline'  => 'More insurance choice. | Less quote chaos.',
        'body'      => 'Start one guided insurance request and move toward available insurance options through licensed professionals, without restarting the process company after company.',
        'actions'   => array(
            array( 'label' => 'Start your request',  'href' => '/start',        'variant' => 'primary',   'event' => 'for_shoppers_hero_start_request_click' ),
            array( 'label' => 'How Ensurance works', 'href' => '/how-it-works', 'variant' => 'secondary', 'event' => 'for_shoppers_hero_how_it_works_click' ),
        ),
        'microcopy' => 'Takes about 2 minutes · One guided request through a more controlled process',
        'trust_items' => array(
            'One guided request',
            'More potential insurance choice',
            'Controlled licensed review',
        ),
    ),

    'problem' => array(
        'eyebrow'  => 'The problem',
        'headline' => 'More insurance choice can mean more work and more quote chaos',
        'lead'     => 'Trying to explore more insurance options can mean starting over across separate companies, repeating the same information, navigating unclear handoffs, and dealing with unwanted outreach.',
        'cards' => array(
            array(
                'title' => 'Starting over again and again',
                'body'  => 'Shopping company by company can mean entering the same details repeatedly just to explore different insurance options.',
            ),
            array(
                'title' => 'Options without enough context',
                'body'  => 'Different insurance companies can evaluate the same shopper differently, which makes understanding the available paths more important.',
            ),
            array(
                'title' => 'More effort than expected',
                'body'  => 'Repeated forms, unclear next steps, and unwanted outreach can make a simple insurance search feel harder than it should.',
            ),
        ),
    ),

    // The "What is Ensurance" answer card. Doubles as the workbook's
    // AI-search direct-answer block and renders within the first 150 words.
    'answer' => array(
        'eyebrow'  => 'In short',
        'headline' => 'What is Ensurance?',
        'body'     => 'Ensurance gives shoppers one guided starting point for an insurance request. Where available, that request can move toward insurance options through licensed professionals without requiring the shopper to restart the process company after company.',
    ),

    'value' => array(
        'eyebrow'  => 'What Ensurance does',
        'headline' => 'One guided request can open a clearer path to more insurance options',
        'lead'     => 'Ensurance gives you one place to start, structures the information needed for review, and helps your request move toward available insurance options through licensed professionals where available.',
        'cards' => array(
            array( 'number' => '01', 'title' => 'Start once', 'body' => 'Share what you need through one guided request instead of restarting the same shopping process company after company.' ),
            array( 'number' => '02', 'title' => 'Move toward more options',      'body' => 'Where available, your request can move toward insurance or carrier options that may fit your needs and eligibility.' ),
            array( 'number' => '03', 'title' => 'Get licensed insurance help',       'body' => 'Licensed agents, agencies, or approved insurance partners can review your request with the context needed to help where appropriate.' ),
        ),
    ),

    'steps' => array(
        'eyebrow'  => 'What happens next',
        'headline' => 'What happens after you start your request',
        'lead'     => 'One guided starting point helps your request move toward the appropriate next step without making you restart the process across separate companies.',
        'steps' => array(
            array( 'number' => '1', 'title' => 'You describe what you need', 'body' => 'Answer a few guided questions about the insurance help you are looking for.' ),
            array( 'number' => '2', 'title' => 'We structure your request', 'body' => 'Ensurance organizes the information needed to understand your request and prepare it for the next step.' ),
            array( 'number' => '3', 'title' => 'Your request is prepared', 'body' => 'Your information stays within a more controlled process as the appropriate next step is determined.' ),
            array( 'number' => '4', 'title' => 'Move toward available options', 'body' => 'Where appropriate, a licensed agent, agency, or approved insurance partner may review available paths and help you move forward.' ),
        ),
    ),

    'control' => array(
        'eyebrow'  => 'Your control',
        'headline' => 'A more controlled way to shop for insurance',
        'body'     => 'Ensurance is built around clearer expectations and controlled request handling. You can decide whether to continue, pause, or stop as your request moves through the process.',
        'items' => array(
            'Choose whether to continue your request',
            'Know what happens as your request moves forward',
            'Pause or stop the process anytime',
            'Update or remove your details on request',
        ),
    ),

    'callout' => array(
        'eyebrow'  => 'Real help',
        'headline' => 'Licensed insurance help when available',
        'body'     => array(
            'Technology can organize an insurance request, but licensed human judgment remains important when reviewing available paths, discussing coverage, or helping with an insurance transaction.',
            'When licensed review is available, a licensed agent, agency, or approved insurance partner can review your request with useful context and help determine appropriate next steps.',
        ),
        'quote'       => '“The goal is a better handoff: useful context, controlled access, and a clearer path toward available insurance options.”',
        'attribution' => 'How every Ensurance handoff is designed to feel',
    ),

    'comparison' => array(
        'eyebrow'  => 'Less quote chaos',
        'headline' => 'More choice without making insurance shopping more chaotic',
        'lead'     => 'Ensurance is designed as one guided starting point for moving toward available insurance options, not a wall of instant quotes or a broadly distributed request.',
        'not_label' => 'What Ensurance is not',
        'not_items' => array(
            'A wall of instant quotes to compare',
            'A request broadly distributed for attention',
            'A process built around repeated outreach',
            'Pressure to decide before you are ready',
        ),
        'is_label' => 'What Ensurance is',
        'is_items' => array(
            'One guided insurance request',
            'A path toward more potential insurance choice',
            'Controlled licensed review where available',
            'A more controlled way to move forward',
        ),
    ),

    'privacy' => array(
        'eyebrow'  => 'Privacy',
        'headline' => 'Your information is handled through a controlled process',
        'lead'     => 'Your details are handled through controlled workflows designed to reduce unnecessary exposure and unwanted outreach while supporting the appropriate next step.',
        'cards' => array(
            array( 'title' => 'Controlled handling', 'body' => 'Your information is handled through controlled workflows rather than broadly distributed.' ),
            array( 'title' => 'Clear expectations', 'body' => 'You can understand how your request is moving forward and decide whether you want to continue the process.' ),
            array( 'title' => 'Controlled licensed access', 'body' => 'When licensed review is appropriate, access to your request is handled through a controlled process rather than broad distribution.' ),
        ),
        'footnote' => 'Encrypted in transit  ·  You can request removal of your details at any time',
    ),

    'faq_section' => array(
        'eyebrow'  => 'FAQ',
        'headline' => 'Questions, answered plainly',
        'items' => array(
            array(
                'key'      => 'is_quote_comparison_site',
                'question' => 'Is Ensurance a quote-comparison site?',
                'answer'   => 'No. Ensurance does not show you a wall of instant quotes. It gives you one guided starting point and, where available, helps your request move toward insurance options through licensed professionals.',
            ),
            array(
                'key'      => 'will_my_info_be_sold',
                'question' => 'Will my information be sold or shared with lots of companies?',
                'answer'   => 'Ensurance does not broadly distribute your request to a crowd of companies. Your information is handled through a controlled process and may be made available for licensed review where appropriate.',
            ),
            array(
                'key'      => 'what_does_it_cost',
                'question' => 'What does it cost to use Ensurance?',
                'answer'   => 'Starting a request is free. Ensurance gives you one guided starting point and helps your request move toward the appropriate next step where available. There is no charge to start or describe what you need.',
            ),
            array(
                'key'      => 'will_i_get_flood_of_calls',
                'question' => 'Will I get a flood of calls and emails?',
                'answer'   => 'Ensurance is designed to reduce broad distribution and unwanted outreach. Any follow-up depends on your request, the available path, and whether licensed review is appropriate.',
            ),
            array(
                'key'      => 'do_i_have_to_talk_right_away',
                'question' => 'Do I have to talk to someone right away?',
                'answer'   => 'No. You can start online and move through the request at your pace. Licensed human help becomes part of the process where appropriate and available.',
            ),
            array(
                'key'      => 'can_i_remove_my_info',
                'question' => 'Can I remove my information later?',
                'answer'   => 'Yes. You can request that your details be updated or removed at any time.',
            ),
        ),
    ),

    'cta_band' => array(
        'headline' => 'More insurance choice. Less quote chaos.',
        'body'     => 'Start one guided insurance request and move toward available options through a more controlled process, without restarting company after company.',
        'actions'  => array(
            array( 'label' => 'Start your request', 'href' => '/start', 'variant' => 'on-dark-primary', 'event' => 'for_shoppers_final_start_request_click' ),
            array( 'label' => 'How Ensurance works', 'href' => '/how-it-works', 'variant' => 'on-dark-secondary', 'event' => 'for_shoppers_final_how_it_works_click' ),
        ),
        'microcopy' => 'One guided request · More potential insurance choice · Licensed review where available',
    ),

);