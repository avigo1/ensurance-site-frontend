<?php
/**
 * Investor brief — page copy.
 *
 * Single source of truth for every string rendered by
 * page-investor-brief.php (Calm Intelligence redesign). Markup lives in the
 * template; edit copy here. The Traction / "Proof to date" section from the
 * design is intentionally omitted — there is no verified data to populate it
 * yet.
 *
 * This is a private, noindex page. It is intentionally not linked from the
 * public marketing header/footer and is not added to the sitemap.
 */

return array(

    'meta' => array(
        'title'       => 'Private Investor Brief | Ensurance',
        'description' => 'Private investor brief for Ensurance, a trust-first insurance demand infrastructure company turning consumer insurance intent into structured, protected, agent-ready action.',
        'og_url'      => 'https://www.ensurance.com/investor-brief/',
        'og_title'    => 'Private Investor Brief | Ensurance',
        'og_desc'     => 'Private investor brief for Ensurance.',
    ),

    'header' => array(
        'badge' => 'Private investor brief',
        'cta'   => 'Request materials',
    ),

    'hero' => array(
        'eyebrow'  => 'Private investor brief',
        'headline' => 'Trust-first insurance demand infrastructure for the next generation of insurance distribution.',
        'body'     => 'Ensurance turns consumer insurance intent into structured, protected, agent-ready action through guided workflows, controlled access, and monetizable engagement.',
        'cta_primary'   => 'Request investor materials',
        'cta_secondary' => 'Review CATE',
        'support'  => 'Detailed investor materials are shared only after qualification and founder review.',
        'badges'   => array(
            'Guided request workflow',
            'Controlled access',
            'Patent pending, if confirmed',
            'Investor materials by review',
        ),
        'thesis' => array(
            'eyebrow'  => 'Core thesis',
            'headline' => 'Trust is not a soft promise. It is the operating layer.',
            'points'   => array(
                'Consumer intent becomes structured request context.',
                'Access is governed before deeper engagement.',
                'Agents review better-context opportunities.',
                'Monetization happens around accepted engagement.',
            ),
        ),
    ),

    'problem' => array(
        'eyebrow'  => 'The problem',
        'headline' => 'Insurance demand is valuable, but the online handoff is broken.',
        'body'     => 'Insurance shoppers are still pushed through fragmented quote paths, referral systems, and volume-first demand flows that often prioritize acquisition over trust, context, and quality.',
        'cards'    => array(
            array( 'kicker' => 'Shopper', 'title' => 'Shopper friction', 'body' => 'People ask for help online, then often experience pressure, repeated entry, and unclear next steps.' ),
            array( 'kicker' => 'Agent', 'title' => 'Agent inefficiency', 'body' => 'Agents need better-context opportunities, not low-context volume that wastes time and follow-up capacity.' ),
            array( 'kicker' => 'System', 'title' => 'Volume-first acquisition systems', 'body' => 'The market still rewards traffic capture more than trust-preserving demand conversion.' ),
        ),
    ),

    'shift' => array(
        'eyebrow'  => 'Category shift',
        'headline' => 'Insurance distribution is moving from traffic acquisition to trust-first demand infrastructure.',
        'body'     => 'Shoppers want digital control. Agents need better-context opportunities. Insurance distribution needs infrastructure that can capture, structure, protect, and convert consumer intent without breaking trust.',
        // Index of the step pill highlighted in the design ("Controlled handoff").
        'steps'     => array( 'Consumer intent', 'Protected request', 'Controlled handoff', 'Accepted engagement', 'Quote execution support' ),
        'highlight' => 2,
    ),

    'building' => array(
        'eyebrow'  => 'Product',
        'headline' => 'What Ensurance is building',
        'body'     => 'A guided request workflow and controlled access system that structures insurance intent, supports agent engagement, and improves the transition from shopper need to quote execution.',
        'cards'    => array(
            array( 'kicker' => '01', 'title' => 'Intent capture', 'body' => 'Guided quote request experience designed for clarity, control, and usable request context.' ),
            array( 'kicker' => '02', 'title' => 'Request intelligence', 'body' => 'Structured, protected request data that supports readiness and better downstream review.' ),
            array( 'kicker' => '03', 'title' => 'Agent review', 'body' => 'Agent review, accept or pass behavior, and monetizable engagement around accepted opportunities.' ),
            array( 'kicker' => '04', 'title' => 'Execution support', 'body' => 'Workflow designed to support quote-readiness, accepted opportunities, and downstream action.' ),
        ),
    ),

    'cate' => array(
        'eyebrow'  => 'Defensibility layer',
        'headline' => 'CATE: Controlled Access Trust Engine',
        'paragraphs' => array(
            'CATE is the Controlled Access Trust Engine behind Ensurance. It structures consumer insurance intent, protects shopper control, governs agent access, and turns messy demand into trust-preserving, monetizable action.',
            'CATE is not a shopper-facing brand, chatbot, quote engine, CRM, rater, or carrier system. It is the controlled access engine behind the trust experience.',
        ),
        'callout'  => 'Ensurance is the public trust brand. CATE is the controlled access engine behind it. CATE controls access. Ensurance owns trust.',
    ),

    'model' => array(
        'eyebrow'  => 'Business model',
        'headline' => 'Ensurance monetizes the controlled handoff between online insurance intent and qualified agent engagement.',
        'cards'    => array(
            array( 'kicker' => 'Participation', 'title' => 'Agent participation', 'body' => 'Agents participate in a system built around better request context and trust-preserving access.' ),
            array( 'kicker' => 'Review', 'title' => 'Better-context request review', 'body' => 'Agents should be able to review enough context to decide whether an opportunity fits.' ),
            array( 'kicker' => 'Engagement', 'title' => 'Accepted opportunities', 'body' => 'The model centers on accepted engagement, not volume-first distribution.' ),
            array( 'kicker' => 'Expansion', 'title' => 'Future partner and infrastructure layers', 'body' => 'Future layers can support partners, integrations, and infrastructure use cases as proof matures.' ),
        ),
    ),

    'raise' => array(
        'eyebrow'  => 'Fundraising status',
        'headline' => 'Current raise',
        'paragraphs' => array(
            'Ensurance is currently raising pre-seed capital to prove the trust-first insurance demand infrastructure model.',
            'The raise is intended to support product and engineering, shopper request growth, agent and partner activation, trust controls, security, platform tooling, and operating runway.',
        ),
        'funds_label' => 'Use of funds',
        'funds' => array(
            'Product and engineering',
            'Shopper request growth',
            'Agent and partner activation',
            'Trust controls, security, and tooling',
            'Operating runway',
        ),
    ),

    'milestones' => array(
        'eyebrow'  => 'Capital plan',
        'headline' => 'What the raise is designed to prove',
        'body'     => 'The capital plan is built around disciplined proof milestones, not vague growth claims.',
        'cards'    => array(
            array( 'kicker' => 'Milestone', 'title' => 'Request flow quality', 'body' => 'Prove that guided requests can produce clearer, more usable demand context.' ),
            array( 'kicker' => 'Milestone', 'title' => 'Agent monetization', 'body' => 'Prove agents will participate and pay around better-context engagement.' ),
            array( 'kicker' => 'Milestone', 'title' => 'CATE trust-system behavior', 'body' => 'Prove controlled access can preserve trust while creating monetizable action.' ),
            array( 'kicker' => 'Milestone', 'title' => 'Repeat participation', 'body' => 'Track whether agents and shoppers repeat the behavior the system is designed to support.' ),
            array( 'kicker' => 'Milestone', 'title' => 'Controlled handoff model', 'body' => 'Validate the handoff between online intent, request structure, agent review, and accepted engagement.' ),
        ),
    ),

    'form' => array(
        'eyebrow'  => 'Founder review',
        'headline' => 'Request investor materials',
        'paragraphs' => array(
            'Ensurance is selectively sharing materials with aligned investors, insurance operators, strategic angels, and capital partners with relevant experience in insurance, fintech infrastructure, vertical SaaS, data infrastructure, consumer demand, or distribution technology.',
            'Submit your background and investment relevance. Ensurance reviews qualified requests before sharing confidential materials or scheduling a founder conversation.',
        ),
        'callout' => 'If there is alignment, Ensurance may follow up with additional materials or a founder conversation.',
        'investor_types' => array(
            'Strategic angel',
            'Fund or capital partner',
            'Insurance operator',
            'Strategic partner',
            'Other aligned investor',
        ),
        'background_help' => 'Insurance, fintech infrastructure, vertical SaaS, data, consumer demand, or distribution technology.',
        'consent'      => 'I understand this page is informational only and does not constitute an offer to sell or a solicitation of an offer to buy securities.',
        'submit_label' => 'Submit request',
        'success' => array(
            'badge'    => 'Request received',
            'headline' => 'Thank you for your interest.',
            'body'     => 'Your request will be reviewed. If there is alignment, Ensurance will follow up directly.',
        ),
    ),

    'disclaimer' => 'This page is for informational purposes only and does not constitute an offer to sell or a solicitation of an offer to buy securities. Any offering, if made, will be made only through appropriate offering documents and only to qualified parties in compliance with applicable securities laws.',

    'footer' => array(
        'tagline' => 'Insurance quote help without the quote chaos.',
        'links'   => array(
            array( 'label' => 'Privacy Policy', 'href' => '/privacy-policy' ),
            array( 'label' => 'Terms of Use', 'href' => '/terms' ),
            array( 'label' => 'Contact', 'href' => '/contact' ),
        ),
    ),
);
