<?php
/**
 * Investor brief content — single source of truth for all section copy.
 *
 * Edit text here; markup stays in page-investor-brief.php.
 * Returns an associative array consumed by the template.
 *
 * This is a private, noindex page. It is intentionally not linked from
 * the public marketing header/footer and is not added to the sitemap.
 */

return array(

    'meta' => array(
        'title'       => 'Ensurance Investor Brief | Insurance Demand Infrastructure',
        'description' => 'Ensurance is building insurance demand infrastructure that turns consumer insurance intent into structured, qualified, monetizable agent opportunities through guided digital workflows and intelligent engagement technology.',
        'og_title'    => 'Ensurance Investor Brief',
        'og_desc'     => 'A private high-level brief for aligned investors and strategic capital partners interested in insurance demand infrastructure.',
        'og_url'      => 'https://www.ensurance.com/investor-brief',
    ),

    'hero' => array(
        'eyebrow'  => 'Private Investor Brief',
        'headline' => 'Insurance demand infrastructure for the next generation of distribution.',
        'body'     => 'Ensurance turns consumer insurance intent into structured, qualified, monetizable agent opportunities through guided digital workflows and intelligent engagement technology.',
        'cta'      => array(
            'label'   => 'Request investor materials',
            'href'    => '#request-materials',
            'event'   => 'hero_cta_click',
            'support' => 'Detailed financing materials are available only after qualification and founder review.',
        ),
        'workflow' => array(
            'label' => 'Guided demand workflow',
            'badge' => 'Patent pending',
            'steps' => array(
                array( 'title' => 'Intent capture',        'detail' => 'Guided digital request experience.',         'state' => 'active' ),
                array( 'title' => 'Request intelligence',  'detail' => 'Structured, qualified insurance demand.',    'state' => 'active' ),
                array( 'title' => 'Agent engagement',      'detail' => 'Monetizable, agent-ready opportunities.',    'state' => 'muted'  ),
                array( 'title' => 'Execution support',     'detail' => 'Quote-readiness and downstream action.',     'state' => 'muted'  ),
            ),
        ),
        'anchors' => array(
            array( 'label' => 'Problem',              'href' => '#problem'  ),
            array( 'label' => 'Shift',                'href' => '#shift'    ),
            array( 'label' => 'What we are building', 'href' => '#building' ),
        ),
    ),

    'problem' => array(
        'eyebrow'  => 'The problem',
        'headline' => 'Insurance demand is valuable, but the current system is trust-poor.',
        'body'     => 'Insurance consumers are still pushed through fragmented shopping paths, quote-comparison funnels, referral systems, and lead flows that often prioritize acquisition volume over trust, context, and quality. For agents, the result is inconsistent opportunity quality. For consumers, the result can feel like exposure rather than help.',
        'cards'    => array(
            'Consumer friction',
            'Agent inefficiency',
            'Acquisition-heavy systems',
        ),
    ),

    'shift' => array(
        'eyebrow'  => 'The shift',
        'headline' => 'The category is moving from traffic acquisition to demand infrastructure.',
        'body'     => 'Consumers want digital control. Agents need higher-quality demand. Insurance distribution needs better infrastructure for capturing, structuring, protecting, and converting consumer intent. Ensurance is being built for that shift.',
        'rows'     => array(
            'Consumer intent',
            'Structured request',
            'Protected handoff',
        ),
    ),

    'building' => array(
        'eyebrow'  => 'What we are building',
        'headline' => 'What Ensurance is building',
        'body'     => 'Ensurance is a guided app and workflow system that captures insurance intent, structures request data, supports agent engagement, and improves the transition from shopper need to insurance execution.',
        'cards'    => array(
            array( 'title' => 'Intent capture',        'detail' => 'Guided digital request experience.',                                   'state' => 'active' ),
            array( 'title' => 'Request intelligence',  'detail' => 'Structured, qualified insurance demand.',                              'state' => 'active' ),
            array( 'title' => 'Agent engagement',      'detail' => 'Monetizable, agent-ready opportunities.',                              'state' => 'muted'  ),
            array( 'title' => 'Execution support',     'detail' => 'Workflow designed to support quote-readiness and downstream agent action.', 'state' => 'muted'  ),
        ),
    ),

    'difference' => array(
        'eyebrow'   => 'Difference',
        'headline'  => 'Not traditional demand distribution.',
        'body'      => 'Traditional models often monetize traffic, clicks, calls, or broad lead distribution. Ensurance is designed around workflow quality, trust, request intelligence, and agent-ready conversion.',
        'rows'      => array(
            array(
                'label'     => 'Traditional models',
                'detail'    => 'Traffic, clicks, calls, or broad distribution.',
                'preferred' => false,
            ),
            array(
                'label'     => 'Ensurance',
                'detail'    => 'Workflow quality, trust, request intelligence, and agent-ready conversion.',
                'preferred' => true,
            ),
        ),
    ),

    'why' => array(
        'eyebrow'  => 'Why it matters',
        'headline' => 'Why this matters',
        'body'     => 'Insurance distribution is massive, fragmented, and still highly dependent on inefficient demand conversion. Ensurance is building a more trusted layer for how insurance demand becomes actionable.',
        'cards'    => array(
            array( 'title' => 'Large market',                'detail' => 'Infrastructure built for a major distribution category.' ),
            array( 'title' => 'Inefficient conversion',      'detail' => 'A cleaner path from consumer intent to actionable demand.' ),
            array( 'title' => 'Trust-based infrastructure',  'detail' => 'A system designed around control, structure, and quality.' ),
        ),
    ),

    'who' => array(
        'eyebrow'  => 'Who should request access',
        'headline' => 'Who should request access',
        'body'     => 'We are selectively speaking with aligned investors, insurance operators, strategic angels, and capital partners with relevant experience in insurance, fintech infrastructure, vertical SaaS, data infrastructure, consumer demand platforms, or distribution technology.',
        'profiles' => array(
            'Insurance operators',
            'Strategic angels',
            'Capital partners',
            'Fintech infrastructure investors',
            'Vertical SaaS investors',
            'Distribution technology leaders',
        ),
    ),

    'form' => array(
        'eyebrow'    => 'Request access',
        'headline'   => 'Request investor materials',
        'body'       => 'If you are an aligned capital partner or strategic operator, submit the form below. The Ensurance team will review qualified requests before sharing any confidential materials.',
        'investor_types' => array(
            'VC fund',
            'Angel investor',
            'Strategic operator',
            'Family office',
            'Insurance executive',
            'Other',
        ),
        'accredited_options' => array(
            'Yes',
            'No',
            'Prefer to discuss with counsel',
        ),
        'consent_text' => 'I understand this page is informational only and does not constitute an offer to sell or a solicitation of an offer to buy securities.',
        'submit_label' => 'Submit request',
    ),

);
