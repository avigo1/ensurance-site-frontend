<?php
/**
 * /how-it-works content — single source of truth.
 *
 * Edit text here; markup lives in section partials under /components/.
 * Returned to page-how-it-works.php, which composes the partials.
 *
 * PDF-mandated section order:
 *   Hero → Direct answer (AI-search block in first 150 words)
 *   → Steps 1/2/3 → Human help when needed
 *   → What Ensurance is not → FAQ → Final CTA.
 */

return array(

    'meta' => array(
        'title'       => 'How Ensurance Works | Guided Insurance Requests',
        'description' => 'See how Ensurance turns one guided insurance request into a clearer path to licensed review. Start online, stay in control, get human help when needed.',
        'canonical'   => '/how-it-works',
    ),

    'hero' => array(
        'eyebrow'  => 'How Ensurance works',
        'headline' => 'One guided request. A clearer path to insurance help.',
        'subtitle' => 'Start online in minutes. Stay in control of your request. Get real human help when you need it.',
        'support'  => 'Ensurance organizes your details so licensed agents, agencies, or approved insurance partners can review available carrier options with better context.',
        'next_module' => array(
            'label' => 'What happens next',
            'steps' => array(
                'Start your guided request',
                'Ensurance organizes the details',
                'Licensed review can begin',
                'Your next step is guided',
            ),
        ),
        'actions' => array(
            array( 'label' => 'Start your request',    'href' => '/start',    'variant' => 'primary',   'event' => 'how_it_works_hero_start_request_click' ),
            array( 'label' => 'See the steps',         'href' => '#how-it-works', 'variant' => 'secondary', 'event' => 'how_it_works_hero_see_steps_click' ),
        ),
        'trust_line' => 'Controlled request process. Licensed review. Available options vary.',
        'flow' => array(
            'label'       => 'Request pathway',
            'start_label' => 'Insurance need',
            'end_label'   => 'Clearer next step',
            'steps' => array(
                array( 'number' => '1', 'title' => 'Start your request',         'state' => 'active' ),
                array( 'number' => '2', 'title' => 'Details are organized',      'state' => 'default' ),
                array( 'number' => '3', 'title' => 'Licensed review begins',     'state' => 'ready' ),
                array( 'number' => '4', 'title' => 'Next step is guided',        'state' => 'complete' ),
            ),
        ),
    ),

    // AI-search direct-answer block. PDF requires this within the first 150
    // words of visible content on a direct-answer page, so it renders
    // immediately after the hero copy.
    'direct_answer' => array(
        'eyebrow'  => 'Direct answer',
        'headline' => 'How Ensurance works',
        'lead'     => 'Ensurance is trust-first insurance demand infrastructure. One guided request becomes a structured, protected starting point for licensed review.',
        'paragraphs' => array(
            'You start one guided request online. Ensurance organizes the details. A licensed agent, agency, or approved insurance partner can review available carrier options and help guide the next step.',
            'The experience is designed to reduce confusion, exposure, and pressure. You stay in control of what is shared and when.',
        ),
        'trust_line' => 'Insurance help online without quote chaos.',
        'answer_block' => array(
            'eyebrow'   => 'What is Ensurance',
            'headline'  => 'Trust-first insurance demand infrastructure.',
            'paragraphs' => array(
                'Ensurance turns consumer insurance intent into a structured, protected request that licensed agents, agencies, or approved insurance partners can review.',
                'It is not a quote comparison site, a lead marketplace, or a public directory of agents.',
            ),
        ),
    ),

    // Three steps. Section names mirror the PDF required-section labels exactly.
    'steps_intro' => array(
        'eyebrow'  => 'The process',
        'headline' => 'A guided path from request to next step.',
    ),

    'steps' => array(
        'eyebrow'  => 'The process',
        'headline' => 'A guided path from request to next step.',
        'steps' => array(
            array(
                'number' => '1',
                'title'  => 'Start your request',
                'body'   => 'Tell Ensurance what kind of insurance help you need and share the basics. The form is guided, not exhausting. You see the path before you move forward.',
            ),
            array(
                'number' => '2',
                'title'  => 'We organize the details',
                'body'   => 'Ensurance turns your request into a clearer, review-ready format. Key details are organized so the next conversation can start with context instead of confusion.',
            ),
            array(
                'number' => '3',
                'title'  => 'The next step is guided',
                'body'   => 'A licensed agent, agency, or approved insurance partner can review available carrier options. Whatever happens next is explained before action is taken.',
            ),
        ),
        // Middle CTA sits below the steps grid — satisfies PDF requirement
        // that a primary CTA appear after value proof.
        'action'     => array( 'label' => 'Start your request', 'href' => '/start', 'variant' => 'primary', 'event' => 'how_it_works_steps_start_request_click' ),
        'trust_line' => 'You stay oriented through the process. The goal is clarity before action.',
    ),

    'human_help' => array(
        'eyebrow'  => 'Human help when needed',
        'headline' => 'Structure first. Real human help when you need it.',
        'lead'     => 'Insurance can get complicated when your situation has details. Ensurance keeps the request structured online and makes room for licensed human support when it actually helps.',
        'card' => array(
            'headline'   => 'A better starting point for the next conversation.',
            'paragraphs' => array(
                'A clearer request helps the next conversation start with context. You are not retelling the same story to four different people.',
            ),
            'items' => array(
                'More structure before a conversation',
                'Less confusion around what to ask',
                'A clearer request for licensed review',
                'Real human support when needed',
            ),
        ),
    ),

    'not_section' => array(
        'eyebrow'  => 'Trust boundary',
        'headline' => 'What Ensurance is not.',
        'paragraphs' => array(
            'Ensurance is not a quote comparison site, a lead marketplace, or a public directory of agents.',
            'It is not designed to make insurance shopping feel chaotic, exposed, or pressured.',
        ),
        'not_items' => array(
            'A crowded listing experience',
            'A race for attention',
            'A pressure-based form path',
            'Unclear next steps',
            'A place where shoppers feel like data',
            'A promise of pricing, approval, coverage, or savings',
        ),
        'instead' => array(
            'headline'   => 'Instead',
            'paragraphs' => array(
                'Ensurance organizes your details so licensed insurance professionals can review available carrier options and guide the next step.',
                'The experience is designed to help you understand the path before you move forward.',
            ),
        ),
    ),

    'faq_intro' => array(
        'eyebrow'  => 'Common questions',
        'headline' => 'Questions before you start.',
        'lead'     => 'Clear answers before action. Ensurance organizes the request so licensed insurance professionals can review available options with better context.',
        'action'   => array( 'label' => 'Start your request', 'href' => '/start', 'variant' => 'primary', 'event' => 'how_it_works_faq_start_request_click' ),
    ),

    'faq' => array(
        array(
            'key'      => 'what_ensurance_does',
            'question' => 'What does Ensurance do?',
            'answer'   => 'Ensurance is the guided gateway between your insurance request and licensed review. You start one request online; licensed agents, agencies, or approved insurance partners can review available carrier options.',
        ),
        array(
            'key'      => 'is_it_a_quote_comparison_site',
            'question' => 'Is Ensurance a quote comparison site?',
            'answer'   => 'No. Ensurance is a guided insurance request platform. It organizes your request and helps create a clearer path to licensed review. Pricing and quote options come from licensed professionals when available.',
        ),
        array(
            'key'      => 'will_my_request_go_to_many_agents',
            'question' => 'Will my request be sent to many agents at once?',
            'answer'   => 'No. Ensurance is designed around controlled request flow. The experience is built to reduce unnecessary exposure and help your request move through a clearer path.',
        ),
        array(
            'key'      => 'who_provides_quote_options',
            'question' => 'Who provides the quote options?',
            'answer'   => 'Licensed agents, agencies, or approved insurance partners. Ensurance structures your request so review can begin with clearer context. Availability depends on your request, location, eligibility, and carrier appetite.',
        ),
        array(
            'key'      => 'do_i_need_to_know_what_coverage',
            'question' => 'Do I need to know exactly what coverage I need?',
            'answer'   => 'No. You can start with the type of help you are looking for. Ensurance helps organize the request so the next step is easier to understand.',
        ),
        array(
            'key'      => 'is_there_human_help',
            'question' => 'Can I talk to a real person?',
            'answer'   => 'Yes. Ensurance keeps room for licensed human support when it actually helps. The online flow is built so the next conversation starts with context, not confusion.',
        ),
        array(
            'key'      => 'is_it_a_lead_marketplace',
            'question' => 'Is Ensurance a lead marketplace?',
            'answer'   => 'No. Ensurance is not a lead marketplace. It is designed around controlled request flow, shopper clarity, and licensed review rather than broad distribution.',
        ),
    ),

    'final_cta' => array(
        'eyebrow'  => 'Start with structure',
        'headline' => 'Start one guided insurance request.',
        'body'     => 'Ensurance organizes your details so a licensed agent, agency, or approved insurance partner can review available carrier options with better context.',
        'actions' => array(
            array( 'label' => 'Start your request', 'href' => '/start', 'variant' => 'primary',   'event' => 'how_it_works_final_start_request_click' ),
            array( 'label' => 'Common questions',   'href' => '#faq',   'variant' => 'secondary', 'event' => 'how_it_works_final_faq_click' ),
        ),
        'trust_line' => 'Start online in minutes. Stay in control. Real human help when you need it.',
    ),

);
