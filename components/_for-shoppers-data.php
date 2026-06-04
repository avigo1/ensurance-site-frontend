<?php
/**
 * /for-shoppers content — single source of truth.
 *
 * Edit text here; markup lives in section partials under /components/.
 * Returned to page-for-shoppers.php, which composes the partials in order.
 *
 * Audience: shoppers evaluating whether to start a guided request.
 * Workbook compliance:
 *   - Direct-answer block renders within the first 150 words of visible
 *     content (right after the hero).
 *   - All copy uses approved phrases (guided request, licensed review,
 *     approved insurance partners, where available) and avoids restricted
 *     language (no instant quotes, no guarantees, no broad data-sharing).
 *   - "One licensed agent" intent from the Figma is broadened to
 *     "licensed agents, agencies, or approved insurance partners" to align
 *     with the global positioning statement.
 */

return array(

    'meta' => array(
        'title'       => 'For Shoppers | Guided Insurance Requests | Ensurance',
        'description' => 'Ensurance helps shoppers start one guided insurance request that licensed agents, agencies, or approved insurance partners can use to review available carriers and provide quote options where available.',
        'canonical'   => '/for-shoppers',
        'og_title'    => 'For Shoppers | Guided Insurance Requests | Ensurance',
        'og_desc'     => 'One guided insurance request, prepared for licensed review. Stay in control. Real human help when you need it.',
    ),

    'hero' => array(
        'eyebrow'  => 'For shoppers',
        'headline' => 'Insurance help online, without the quote chaos.',
        'subtitle' => 'Ensurance turns what you need into a clear, protected request — then prepares it for licensed review when you are ready to move forward.',
        'support'  => 'No spam, no flood of calls, no comparison maze. One guided request, structured for licensed agents, agencies, or approved insurance partners to review available carriers.',
        'next_module' => array(
            'label' => 'What happens next',
            'steps' => array(
                'Start your guided request',
                'Your details are structured privately',
                'Licensed review begins when you choose',
                'A clearer path to next steps',
            ),
        ),
        'actions' => array(
            array( 'label' => 'Start your request',   'href' => '/start',         'variant' => 'primary',   'event' => 'for_shoppers_hero_start_request_click' ),
            array( 'label' => 'How Ensurance works',  'href' => '/how-it-works',  'variant' => 'secondary', 'event' => 'for_shoppers_hero_how_it_works_click' ),
        ),
        'trust_line' => 'Takes about two minutes. Your details stay private until you choose to share them.',
        'flow' => array(
            'label'       => 'Shopper request pathway',
            'start_label' => 'You describe what you need',
            'end_label'   => 'A clearer next step',
            'steps' => array(
                array( 'number' => '1', 'title' => 'Start your request',          'state' => 'active' ),
                array( 'number' => '2', 'title' => 'Details are structured',      'state' => 'default' ),
                array( 'number' => '3', 'title' => 'Licensed review begins',      'state' => 'ready' ),
                array( 'number' => '4', 'title' => 'You decide the next step',    'state' => 'complete' ),
            ),
        ),
    ),

    // AI-search direct-answer block. Workbook requires this within the first
    // 150 words of visible content, so it renders immediately after the hero.
    'direct_answer' => array(
        'eyebrow'  => 'In short',
        'headline' => 'What is Ensurance?',
        'lead'     => 'Ensurance helps shoppers start one guided insurance request that licensed agents, agencies, or approved insurance partners can use to review available carriers and provide quote options where available.',
        'paragraphs' => array(
            'You describe what you need once. Ensurance organizes it into a clear, protected request. When you choose to move forward, a licensed agent, agency, or approved insurance partner receives the context needed to help.',
            'It is not a quote comparison site, a lead marketplace, or a public directory of agents.',
        ),
        'trust_line' => 'Insurance help online without the quote chaos.',
        'answer_block' => array(
            'eyebrow'   => 'What Ensurance does',
            'headline'  => 'Trust-first insurance demand infrastructure.',
            'paragraphs' => array(
                'Ensurance turns consumer insurance intent into a structured, protected request that licensed agents, agencies, or approved insurance partners can review.',
                'You stay in control of what is shared and when. Quote options depend on availability, eligibility, location, carrier participation, and licensed review.',
            ),
        ),
    ),

    // The problem framing — three cards describing what shopping online
    // usually feels like, so the value proposition that follows lands.
    'problem' => array(
        'eyebrow'  => 'The problem',
        'headline' => 'Why insurance shopping feels overwhelming.',
        'lead'     => 'The moment you ask for help online, the noise often starts. One form turns into a flood of calls, emails, and quotes you never asked for — from sources you do not recognize.',
        'cards' => array(
            array(
                'title' => 'The form that never ends',
                'body'  => 'You enter your details once and suddenly everyone has them — the same questions, over and over, from sources you do not recognize.',
            ),
            array(
                'title' => 'Numbers without context',
                'body'  => 'Quotes appear before anyone understands your situation. Comparing them feels like guessing, not deciding.',
            ),
            array(
                'title' => 'Pressure instead of help',
                'body'  => 'Calls at dinner. Emails that will not stop. It starts to feel like being processed, not helped.',
            ),
        ),
    ),

    // What Ensurance does — three numbered value cards beside intro copy.
    'what_we_do' => array(
        'eyebrow'  => 'What Ensurance does',
        'headline' => 'We turn what you need into one clear, protected request.',
        'lead'     => 'Ensurance is the calm layer between you and the insurance world. It translates what you actually need into a structured request and prepares it for licensed review — without the guesswork.',
        'body'     => 'You stay in control. Quote options depend on availability, eligibility, location, carrier participation, and licensed review.',
        'action'   => array( 'label' => 'Start your request', 'href' => '/start', 'variant' => 'primary', 'event' => 'for_shoppers_what_we_do_start_request_click' ),
        'cards' => array(
            array( 'number' => '01', 'title' => 'Structured, not scattered', 'body' => 'Your needs are captured once, clearly — so nothing gets lost, repeated, or misunderstood along the way.' ),
            array( 'number' => '02', 'title' => 'Protected by default',      'body' => 'Your information is not broadcast. It stays private until you decide to move forward.' ),
            array( 'number' => '03', 'title' => 'Ready for real help',       'body' => 'When you choose to move forward, a licensed agent, agency, or approved insurance partner receives the context needed to help.' ),
        ),
    ),

    // What happens next — four guided steps after starting a request.
    'steps' => array(
        'eyebrow'  => 'What happens next',
        'headline' => 'What happens after you start your request.',
        'steps' => array(
            array( 'number' => '1', 'title' => 'You describe what you need',  'body' => 'A few clear questions, in plain language. No jargon, no endless forms.' ),
            array( 'number' => '2', 'title' => 'We structure your request',   'body' => 'Ensurance organizes your needs into one clear, complete picture — privately.' ),
            array( 'number' => '3', 'title' => 'You decide to move forward',  'body' => 'Nothing is shared with an agent until you say so. You are always the one who chooses.' ),
            array( 'number' => '4', 'title' => 'Licensed review can begin',   'body' => 'A licensed agent, agency, or approved insurance partner receives your request with full context.' ),
        ),
        'action'     => array( 'label' => 'Start your request', 'href' => '/start', 'variant' => 'primary', 'event' => 'for_shoppers_steps_start_request_click' ),
        'trust_line' => 'No surprises. Your details stay private until you choose to share them.',
    ),

    // Shopper control — two-column copy + checklist info card.
    'shopper_control' => array(
        'eyebrow'  => 'Your control',
        'headline' => 'You stay in control at every step.',
        'lead'     => 'Ensurance is built around your decisions — not around selling your attention. You choose what to share, when to share it, and who you talk to. You can pause or stop at any point, and your request never becomes a product on a marketplace.',
        'card' => array(
            'headline'   => 'What shopper control looks like in practice.',
            'paragraphs' => array(
                'These are the controls you have at every step. They do not need to be unlocked or upgraded — they are how Ensurance works by default.',
            ),
            'items' => array(
                'Choose if and when your request is shared',
                'Speak with a licensed professional — or no one at all',
                'Pause or stop the process anytime',
                'Update or remove your details on request',
            ),
        ),
    ),

    // Real, licensed humans — navy band, copy + trust panel.
    'protected' => array(
        'eyebrow'  => 'Real help',
        'headline' => 'Real, licensed humans — when you want them.',
        'lead'     => 'Software can organize your request. It cannot reassure you, answer the question behind your question, or tailor coverage to your life. Ensurance prepares your request so a licensed agent, agency, or approved insurance partner can review available carriers with full context — never a cold script.',
        'action'   => array( 'label' => 'Read Trust and Privacy', 'href' => '/trust-and-privacy', 'variant' => 'primary', 'event' => 'for_shoppers_trust_privacy_click' ),
        'disclosure' => 'Quote options depend on availability, eligibility, location, carrier participation, and licensed review. Not every request results in available options.',
        'trust_points' => array(
            'You stay oriented through the process — clarity before action.',
            'Your request is prepared with full context before review.',
            'Licensed professionals can review available carrier options.',
            'You are never passed around a call center — review starts with context.',
            'You decide whether and when to continue.',
        ),
    ),

    // What Ensurance is not — comparison reframe.
    'not_section' => array(
        'eyebrow'  => 'No quote chaos',
        'headline' => 'Help without the quote chaos.',
        'paragraphs' => array(
            'Ensurance is deliberately not a quote-comparison site or a lead marketplace. The difference is the whole point.',
        ),
        'not_items' => array(
            'A wall of instant quotes to compare',
            'Your data sold to many buyers at once',
            'A flood of cold calls and emails',
            'Pressure to decide before you are ready',
            'A public directory of agents',
            'A promise of pricing, approval, coverage, or savings',
        ),
        'instead' => array(
            'headline'   => 'Instead',
            'paragraphs' => array(
                'One structured, private request. Your information protected by default. A licensed agent, agency, or approved insurance partner who reviews available options with full context — only when you choose.',
                'Time and space to decide for yourself. Quote options depend on availability, eligibility, location, carrier participation, and licensed review.',
            ),
        ),
    ),

    // Privacy — three trust cards in a row beneath a centered header.
    'privacy' => array(
        'eyebrow'  => 'Privacy',
        'headline' => 'Your information is never broadcast.',
        'lead'     => 'Privacy is not a setting here — it is the foundation. Your details are protected from the first question, and they are never sold, listed, or distributed to a crowd of buyers.',
        'cards' => array(
            array( 'title' => 'Never sold, never listed',  'body' => 'Your information is not a product. It is never sold to lead buyers or posted to a marketplace.' ),
            array( 'title' => 'Private until you choose',  'body' => 'Nothing reaches an agent until you decide to move forward. That choice is always yours to make.' ),
            array( 'title' => 'Shared with one, not many', 'body' => 'When you proceed, your request goes to a single licensed professional — never broadcast to a crowd.' ),
        ),
        'footnote' => 'Encrypted in transit. You can request removal of your details at any time.',
    ),

    'faq_intro' => array(
        'eyebrow'  => 'FAQ',
        'headline' => 'Questions, answered plainly.',
        'lead'     => 'Clear answers before action. Ensurance organizes your request so licensed insurance professionals can review available options with better context.',
        'action'   => array( 'label' => 'Start your request', 'href' => '/start', 'variant' => 'primary', 'event' => 'for_shoppers_faq_start_request_click' ),
    ),

    'faq' => array(
        array(
            'key'      => 'is_quote_comparison_site',
            'question' => 'Is Ensurance a quote-comparison site?',
            'answer'   => 'No. Ensurance does not show you a wall of quotes or pit carriers against each other. It helps you create one clear, protected request and prepares it for licensed agents, agencies, or approved insurance partners to review available carriers where available.',
        ),
        array(
            'key'      => 'will_my_info_be_sold',
            'question' => 'Will my information be sold or shared with many companies?',
            'answer'   => 'No. Your information is not sold or distributed to a crowd of buyers. It stays private until you choose to move forward — and then it goes to a single licensed professional for review.',
        ),
        array(
            'key'      => 'what_does_it_cost',
            'question' => 'What does it cost to use Ensurance?',
            'answer'   => 'Starting a request is free. Ensurance is here to help you create a clearer path to licensed review. You are never charged to describe what you need or to be prepared for review.',
        ),
        array(
            'key'      => 'will_i_get_flood_of_calls',
            'question' => 'Will I get a flood of calls and emails?',
            'answer'   => 'No. There is no lead marketplace behind Ensurance, so there is no swarm of callers. A licensed professional reviews your request once you have chosen to move forward.',
        ),
        array(
            'key'      => 'do_i_have_to_talk_right_away',
            'question' => 'Do I have to talk to someone right away?',
            'answer'   => 'No. You set the pace. Take your time, pause, or stop — nothing is shared with an agent until you decide.',
        ),
        array(
            'key'      => 'can_i_remove_my_info',
            'question' => 'Can I remove my information later?',
            'answer'   => 'Yes. You can request that your details be updated or removed at any time. Review Trust and Privacy for the full request experience.',
        ),
    ),

    'final_cta' => array(
        'eyebrow'  => 'Start with structure',
        'headline' => 'Get insurance help — calmly, and on your terms.',
        'body'     => 'Start your request in about two minutes. Your details stay private until you choose to share them.',
        'actions' => array(
            array( 'label' => 'Start your request',   'href' => '/start',         'variant' => 'primary',   'event' => 'for_shoppers_final_start_request_click' ),
            array( 'label' => 'How Ensurance works',  'href' => '/how-it-works',  'variant' => 'secondary', 'event' => 'for_shoppers_final_how_it_works_click' ),
        ),
        'trust_line' => 'No spam. No bulk lead selling. One licensed professional, only when you are ready.',
    ),

);
