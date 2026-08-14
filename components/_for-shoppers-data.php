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
        'description' => 'Ensurance helps shoppers start a guided insurance request, organize their details, and move toward controlled licensed review and available coverage options.',
        'canonical'   => '/for-shoppers',
        'og_title'    => 'For Shoppers | Guided Insurance Requests | Ensurance',
        'og_desc'     => 'One guided insurance request, prepared for licensed review. Stay in control. Real human help when you need it.',
    ),

    'hero' => array(
        'eyebrow'   => 'For shoppers',
        // Pipe splits the headline into two lines; matches the Figma's stacked layout.
        'headline'  => 'Insurance help online, | without the quote chaos.',
        'body'      => 'Ensurance turns what you need into a clear, protected request and prepares it for controlled licensed review where available. Less noise, less unwanted contact, and a clearer next step.',
        'actions'   => array(
            array( 'label' => 'Start your request',  'href' => '/start',        'variant' => 'primary',   'event' => 'for_shoppers_hero_start_request_click' ),
            array( 'label' => 'How Ensurance works', 'href' => '/how-it-works', 'variant' => 'secondary', 'event' => 'for_shoppers_hero_how_it_works_click' ),
        ),
        'microcopy' => 'Takes about 2 minutes · Your request is handled through a controlled process',
        'trust_items' => array(
            'You stay in control',
            'Not broadly distributed',
            'Controlled licensed review',
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
        'body'     => 'Ensurance helps you start one clear, protected insurance request. We organize your details and move the request through a controlled process toward licensed review where available, without broadly distributing it.',
    ),

    'value' => array(
        'eyebrow'  => 'What Ensurance does',
        'headline' => 'We turn what you need into one clear, protected request',
        'lead'     => 'Ensurance is not a quote-comparison site built around broad distribution. It structures what you need into a clear request, protects your information, and prepares it for licensed review where available.',
        'cards' => array(
            array( 'number' => '01', 'title' => 'Structured, not scattered', 'body' => 'Your needs are captured once, clearly — so nothing gets lost, repeated, or misunderstood along the way.' ),
            array( 'number' => '02', 'title' => 'Protected by default',      'body' => 'Your information is handled through a controlled process designed to reduce unnecessary exposure and unwanted contact.' ),
            array( 'number' => '03', 'title' => 'Ready for real help',       'body' => 'When licensed review is appropriate and available, the professional reviewing your request receives the context needed to understand your situation.' ),
        ),
    ),

    'steps' => array(
        'eyebrow'  => 'What happens next',
        'headline' => 'What happens after you start your request',
        'lead'     => 'No surprises. Here is what to expect as your request moves from guided intake to structured review.',
        'steps' => array(
            array( 'number' => '1', 'title' => 'You describe what you need', 'body' => 'A few clear questions, in plain language. No jargon, no endless forms.' ),
            array( 'number' => '2', 'title' => 'We structure your request', 'body' => 'Ensurance organizes your needs into one clear, complete picture — privately.' ),
            array( 'number' => '3', 'title' => 'You choose your next step', 'body' => 'You can continue, pause, or stop. Your request remains within a controlled process.' ),
            array( 'number' => '4', 'title' => 'Licensed review where available', 'body' => 'Where appropriate, a licensed agent, agency, or approved insurance partner may review your request with the context needed to help.' ),
        ),
    ),

    'control' => array(
        'eyebrow'  => 'Your control',
        'headline' => 'You stay in control at every step',
        'body'     => 'Ensurance is built around clear choices and controlled request handling. You choose whether to continue, how you prefer to be contacted, and whether to pause or stop the process.',
        'items' => array(
            'Choose whether to continue your request',
            'Set your preferred contact method',
            'Pause or stop the process anytime',
            'Update or remove your details on request',
        ),
    ),

    'callout' => array(
        'eyebrow'  => 'Real help',
        'headline' => 'Real licensed help when available',
        'body'     => array(
            'Software can organize your request. It cannot reassure you, answer the question behind your question, or tailor coverage to your life. That is a person\'s job.',
            'When licensed review is available, a licensed agent, agency, or approved insurance partner can review your request with useful context before follow-up.',
        ),
        'quote'       => '“The goal is a calmer handoff: useful context, controlled access, and a clearer conversation when licensed help is available.”',
        'attribution' => 'How every Ensurance handoff is designed to feel',
    ),

    'comparison' => array(
        'eyebrow'  => 'No quote chaos',
        'headline' => 'Help without the quote chaos',
        'lead'     => 'Ensurance is deliberately not a quote-comparison site built around broad distribution. The difference is the whole point.',
        'not_label' => 'What Ensurance is not',
        'not_items' => array(
            'A wall of instant quotes to compare',
            'Your information broadly distributed',
            'A flood of cold calls and emails',
            'Pressure to decide before you are ready',
        ),
        'is_label' => 'What Ensurance is',
        'is_items' => array(
            'One structured, protected request',
            'Your information protected by default',
            'Controlled licensed review where available',
            'Time and space to decide for yourself',
        ),
    ),

    'privacy' => array(
        'eyebrow'  => 'Privacy',
        'headline' => 'Your information is handled through a controlled process',
        'lead'     => 'Privacy is built into the process. Your details are handled through controlled workflows designed to reduce unnecessary exposure and unwanted contact.',
        'cards' => array(
            array( 'title' => 'Controlled handling', 'body' => 'Your information is handled through controlled workflows rather than broadly distributed.' ),
            array( 'title' => 'Clear choices throughout', 'body' => 'You can choose whether to continue and how you prefer to be contacted as your request moves forward.' ),
            array( 'title' => 'Controlled licensed access', 'body' => 'When licensed review is appropriate, request access is controlled rather than broadly distributed.' ),
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
                'answer'   => 'No. Ensurance does not show you a wall of quotes or pit carriers against each other. It helps you create one clear, protected request that can move toward controlled licensed review and available coverage options.',
            ),
            array(
                'key'      => 'will_my_info_be_sold',
                'question' => 'Will my information be sold or shared with lots of companies?',
                'answer'   => 'No. Ensurance does not sell your information or broadly distribute your request to a crowd of companies. Your information is handled through a controlled process and may move into licensed review where available.',
            ),
            array(
                'key'      => 'what_does_it_cost',
                'question' => 'What does it cost to use Ensurance?',
                'answer'   => 'Starting a request is free. Ensurance helps you create a clearer path toward licensed review where available. There is no charge to start or describe what you need.',
            ),
            array(
                'key'      => 'will_i_get_flood_of_calls',
                'question' => 'Will I get a flood of calls and emails?',
                'answer'   => 'No. Ensurance is designed to reduce broad sharing and unwanted contact. Follow-up, where available, happens through a controlled process based on your request and contact preference.',
            ),
            array(
                'key'      => 'do_i_have_to_talk_right_away',
                'question' => 'Do I have to talk to someone right away?',
                'answer'   => 'No. You set the pace. You can continue, pause, or stop, and choose your preferred contact method if follow-up becomes available.',
            ),
            array(
                'key'      => 'can_i_remove_my_info',
                'question' => 'Can I remove my information later?',
                'answer'   => 'Yes. You can request that your details be updated or removed at any time.',
            ),
        ),
    ),

    'cta_band' => array(
        'headline' => 'Get insurance help — calmly, and on your terms',
        'body'     => 'Start your request in about two minutes. Your details are handled through a controlled process designed to reduce broad sharing and unwanted contact.',
        'actions'  => array(
            array( 'label' => 'Start your request', 'href' => '/start', 'variant' => 'on-dark-primary', 'event' => 'for_shoppers_final_start_request_click' ),
            array( 'label' => 'How Ensurance works', 'href' => '/how-it-works', 'variant' => 'on-dark-secondary', 'event' => 'for_shoppers_final_how_it_works_click' ),
        ),
        'microcopy' => 'Less unwanted contact · Controlled request access · Licensed review where available',
    ),

);