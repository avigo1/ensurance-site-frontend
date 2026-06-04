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
        'title'       => 'For Shoppers | Guided Insurance Requests | Ensurance',
        'description' => 'Ensurance helps shoppers start one guided insurance request that licensed agents, agencies, or approved insurance partners can use to review available carriers and provide quote options where available.',
        'canonical'   => '/for-shoppers',
        'og_title'    => 'For Shoppers | Guided Insurance Requests | Ensurance',
        'og_desc'     => 'One guided insurance request, prepared for licensed review. Stay in control. Real human help when you need it.',
    ),

    'hero' => array(
        'eyebrow'   => 'For shoppers',
        // Pipe splits the headline into two lines; matches the Figma's stacked layout.
        'headline'  => 'Insurance help online, | without the quote chaos.',
        'body'      => 'Ensurance turns what you actually need into a clear, protected request — then prepares it for a licensed agent, agency, or approved insurance partner to review when you are ready. No spam, no flood of calls, no comparison maze.',
        'actions'   => array(
            array( 'label' => 'Start your request',   'href' => '/start',         'variant' => 'primary',   'event' => 'for_shoppers_hero_start_request_click' ),
            array( 'label' => 'How Ensurance works',  'href' => '/how-it-works',  'variant' => 'secondary', 'event' => 'for_shoppers_hero_how_it_works_click' ),
        ),
        'microcopy' => 'Takes about 2 minutes · Your details stay private until you choose to share them',
        'trust_items' => array(
            'You stay in control',
            'Never sold as a bulk lead',
            'One licensed professional, not a call center',
        ),
    ),

    'problem' => array(
        'eyebrow'  => 'The problem',
        'headline' => 'Why insurance shopping feels overwhelming',
        'lead'     => 'The moment you ask for help online, the noise starts. One form turns into a dozen calls, a flood of emails, and quotes you never asked for — from people who bought your information.',
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

    // The "What is Ensurance" answer card. Doubles as the workbook's
    // AI-search direct-answer block and renders within the first 150 words.
    'answer' => array(
        'eyebrow'  => 'In short',
        'headline' => 'What is Ensurance?',
        'body'     => 'Ensurance helps you ask for insurance help without handing your information to a marketplace. You describe what you need once, we organize it into one clear, private request, and only when you choose, a licensed agent, agency, or approved insurance partner receives the context needed to help — never a call center or a list of lead buyers.',
    ),

    'value' => array(
        'eyebrow'  => 'What Ensurance does',
        'headline' => 'We turn what you need into one clear, protected request',
        'lead'     => 'Ensurance is not a quote engine or a lead marketplace. It is the calm layer between you and the insurance world — translating what you actually need into a structured request, protecting your information, and preparing it so a licensed professional can help without the guesswork.',
        'cards' => array(
            array( 'number' => '01', 'title' => 'Structured, not scattered', 'body' => 'Your needs are captured once, clearly — so nothing gets lost, repeated, or misunderstood along the way.' ),
            array( 'number' => '02', 'title' => 'Protected by default',      'body' => 'Your information is never broadcast to buyers. It stays private until you decide to move forward.' ),
            array( 'number' => '03', 'title' => 'Ready for real help',       'body' => 'When you choose to move forward, a licensed agent, agency, or approved insurance partner receives the context needed to help — not a cold script.' ),
        ),
    ),

    'steps' => array(
        'eyebrow'  => 'What happens next',
        'headline' => 'What happens after you start your request',
        'lead'     => 'No surprises. Here is exactly what to expect — and notice that you share nothing with an agent until you choose to.',
        'steps' => array(
            array( 'number' => '1', 'title' => 'You describe what you need',   'body' => 'A few clear questions, in plain language. No jargon, no endless forms.' ),
            array( 'number' => '2', 'title' => 'We structure your request',    'body' => 'Ensurance organizes your needs into one clear, complete picture — privately.' ),
            array( 'number' => '3', 'title' => 'You decide to move forward',   'body' => 'Nothing is shared until you say so. You are always the one who chooses.' ),
            array( 'number' => '4', 'title' => 'A licensed professional reaches out', 'body' => 'A single licensed agent, agency, or approved insurance partner receives your request with full context — not a script.' ),
        ),
    ),

    'control' => array(
        'eyebrow'  => 'Your control',
        'headline' => 'You stay in control at every step',
        'body'     => 'Ensurance is built around your decisions — not around selling your attention. You choose what to share, when to share it, and who you talk to. You can pause or stop at any point, and your request never becomes a product on a marketplace.',
        'items' => array(
            'Choose if and when your request is shared',
            'Speak with a licensed professional — or no one at all',
            'Pause or stop the process anytime',
            'Update or remove your details on request',
        ),
    ),

    'callout' => array(
        'eyebrow'     => 'Real help',
        'headline'    => 'Real, licensed humans — when you want them',
        'body'        => array(
            'Software can organize your request. It cannot reassure you, answer the question behind your question, or tailor coverage to your life. That is a person\'s job.',
            'Ensurance connects you with a licensed agent, agency, or approved insurance partner who sees your full context before they ever reach out — so the conversation starts with understanding, not a pitch.',
        ),
        'quote'       => '“You will not be passed around a call center. One licensed professional, prepared and accountable, ready to help with your actual situation.”',
        'attribution' => 'How every Ensurance handoff is designed to feel',
    ),

    'comparison' => array(
        'eyebrow'  => 'No quote chaos',
        'headline' => 'Help without the quote chaos',
        'lead'     => 'Ensurance is deliberately not a quote-comparison site or a lead marketplace. The difference is the whole point.',
        'not_label' => 'What Ensurance is not',
        'not_items' => array(
            'A wall of instant quotes to compare',
            'Your data sold to many buyers at once',
            'A flood of cold calls and emails',
            'Pressure to decide before you are ready',
        ),
        'is_label' => 'What Ensurance is',
        'is_items' => array(
            'One structured, private request',
            'Your information protected by default',
            'A single, prepared, licensed professional',
            'Time and space to decide for yourself',
        ),
    ),

    'privacy' => array(
        'eyebrow'  => 'Privacy',
        'headline' => 'Your information is never broadcast',
        'lead'     => 'Privacy is not a setting here — it is the foundation. Your details are protected from the very first question, and they are never sold, listed, or distributed to a crowd of buyers.',
        'cards' => array(
            array( 'title' => 'Never sold, never listed',  'body' => 'Your information is not a product. It is never sold to lead buyers or posted to a marketplace.' ),
            array( 'title' => 'Private until you choose',  'body' => 'Nothing reaches an agent until you decide to move forward. That choice is always yours to make.' ),
            array( 'title' => 'Shared with one, not many', 'body' => 'When you proceed, your request goes to a single licensed professional — never broadcast to a crowd.' ),
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
                'answer'   => 'No. Ensurance does not show you a wall of quotes or pit carriers against each other. It helps you create one clear, protected request and connects you with a licensed agent, agency, or approved insurance partner who can review available options for your situation.',
            ),
            array(
                'key'      => 'will_my_info_be_sold',
                'question' => 'Will my information be sold or shared with lots of companies?',
                'answer'   => 'No. Your information is never sold or distributed to a crowd of buyers. It stays private until you choose to move forward — and then it goes to a single licensed professional for review.',
            ),
            array(
                'key'      => 'what_does_it_cost',
                'question' => 'What does it cost to use Ensurance?',
                'answer'   => 'Starting a request is free. Ensurance is here to help you create a clearer path to licensed review. You are never charged to describe what you need or to be connected.',
            ),
            array(
                'key'      => 'will_i_get_flood_of_calls',
                'question' => 'Will I get a flood of calls and emails?',
                'answer'   => 'No. There is no lead marketplace behind Ensurance, so there is no swarm of callers. You hear from one licensed professional, only once you have chosen to move forward.',
            ),
            array(
                'key'      => 'do_i_have_to_talk_right_away',
                'question' => 'Do I have to talk to someone right away?',
                'answer'   => 'No. You set the pace. Take your time, pause, or stop — nothing is shared with an agent until you decide.',
            ),
            array(
                'key'      => 'can_i_remove_my_info',
                'question' => 'Can I remove my information later?',
                'answer'   => 'Yes. You can request that your details be updated or removed at any time.',
            ),
        ),
    ),

    'cta_band' => array(
        'headline'  => 'Get insurance help — calmly, and on your terms',
        'body'      => 'Start your request in about two minutes. Your details stay private until you choose to share them.',
        'actions'   => array(
            array( 'label' => 'Start your request',  'href' => '/start',         'variant' => 'on-dark-primary',   'event' => 'for_shoppers_final_start_request_click' ),
            array( 'label' => 'How Ensurance works', 'href' => '/how-it-works',  'variant' => 'on-dark-secondary', 'event' => 'for_shoppers_final_how_it_works_click' ),
        ),
        'microcopy' => 'No spam  ·  No bulk lead selling  ·  One licensed professional, only when you are ready',
    ),

);
