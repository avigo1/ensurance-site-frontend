<?php
/**
 * Template Name: Trust Center (Marketing)
 *
 * /trust-center — rebuilt to match the "Trust and Privacy" standalone redesign
 * (Calm Intelligence). Uses the homepage chrome (get_header('home') /
 * get_footer('home')) and shares assets/home.css + assets/home.js for tokens,
 * chrome and base components (buttons, FAQ accordion, final CTA). The
 * page-specific document layout — light hero, sticky table-of-contents, the
 * numbered explainer sections, the noisy-vs-controlled compare and the neutral
 * "trust page vs. Privacy Policy" note — lives in assets/trust-center.css, with
 * the TOC scroll-spy in assets/trust-center.js. Both are enqueued and isolated
 * from the shared marketing bundle in functions.php (ensurance_trust_center_assets).
 *
 * SEO: title / meta description / canonical / robots are owned by Yoast and
 * emitted through wp_head(); this template outputs none of them. The FAQPage
 * JSON-LD below is shipped here (Yoast does not emit it for this page) and
 * mirrors the visible FAQ accordion.
 */

// --- Per-page FAQPage schema — mirrors the visible FAQ accordion. ---
$tc_schema = json_decode( <<<'JSON'
{
  "@context": "https://schema.org",
  "@type": "FAQPage",
  "@id": "https://www.ensurance.com/trust-center#faq",
  "mainEntity": [
    {
      "@type": "Question",
      "name": "What happens to my information when I start a request?",
      "acceptedAnswer": {
        "@type": "Answer",
        "text": "Your details are organized into one request so licensed review can happen with clearer context where available. The Privacy Policy explains the formal details of how your information is handled."
      }
    },
    {
      "@type": "Question",
      "name": "Who may review my request?",
      "acceptedAnswer": {
        "@type": "Answer",
        "text": "Where available, licensed agents, agencies, or approved partners may review your organized request and help identify quote options or a clearer next step."
      }
    },
    {
      "@type": "Question",
      "name": "Will my information be sent everywhere?",
      "acceptedAnswer": {
        "@type": "Answer",
        "text": "No. Your request is handled through a more controlled process designed to reduce unnecessary exposure, broad sharing, and unwanted contact."
      }
    },
    {
      "@type": "Question",
      "name": "How does Ensurance help me move toward quote options?",
      "acceptedAnswer": {
        "@type": "Answer",
        "text": "Ensurance helps you start one guided quote request, organize your details, and move toward quote options where available. Quote options may come through licensed agents, agencies, or approved partners."
      }
    },
    {
      "@type": "Question",
      "name": "Can one request help me move toward multiple carrier options?",
      "acceptedAnswer": {
        "@type": "Answer",
        "text": "Yes, where available. Your organized request can give licensed professionals clearer context to review available carrier options without making you repeat the same details across separate forms."
      }
    },
    {
      "@type": "Question",
      "name": "Is starting a request a commitment to buy insurance?",
      "acceptedAnswer": {
        "@type": "Answer",
        "text": "No. Starting a request is free and does not require you to buy coverage. You can review available options or next steps at your own pace."
      }
    },
    {
      "@type": "Question",
      "name": "Why does Ensurance ask for personal information?",
      "acceptedAnswer": {
        "@type": "Answer",
        "text": "The details you share help organize your request so licensed review can happen with better context where available. The Privacy Policy explains how that information is used."
      }
    },
    {
      "@type": "Question",
      "name": "Where can I read the full Privacy Policy?",
      "acceptedAnswer": {
        "@type": "Answer",
        "text": "The full Privacy Policy is available at any time. This Trust Center explains the experience in plain English. The Privacy Policy explains the formal details of how information is handled."
      }
    }
  ]
}
JSON, true );

add_action( 'wp_head', function () use ( $tc_schema ) {
    if ( $tc_schema ) {
        $flags = JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE | JSON_PRETTY_PRINT;
        echo '<script type="application/ld+json">' . "\n";
        echo wp_json_encode( $tc_schema, $flags ) . "\n";
        echo "</script>\n";
    }
}, 20 );

/**
 * Inline Lucide icon renderer (shared with page-home.php / page-how-it-works.php
 * / page-coverage.php via function_exists guard). This copy carries the glyphs
 * the trust page needs. Paths from Lucide (ISC license).
 */
if ( ! function_exists( 'ensurance_home_icon' ) ) {
    function ensurance_home_icon( $name, $size = 20 ) {
        $icons = array(
            'shield-check' => '<path d="M20 13c0 5-3.5 7.5-7.66 8.95a1 1 0 0 1-.67-.01C7.5 20.5 4 18 4 13V6a1 1 0 0 1 1-1c2 0 4.5-1.2 6.24-2.72a1.17 1.17 0 0 1 1.52 0C14.51 3.81 17 5 19 5a1 1 0 0 1 1 1z"/><path d="m9 12 2 2 4-4"/>',
            'check'        => '<path d="M20 6 9 17l-5-5"/>',
            'x'            => '<path d="M18 6 6 18"/><path d="m6 6 12 12"/>',
            'arrow-right'  => '<path d="M5 12h14"/><path d="m12 5 7 7-7 7"/>',
            'ban'          => '<circle cx="12" cy="12" r="10"/><path d="m4.9 4.9 14.2 14.2"/>',
            'lock'         => '<rect width="18" height="11" x="3" y="11" rx="2"/><path d="M7 11V7a5 5 0 0 1 10 0v4"/>',
            'file-text'    => '<path d="M15 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V7Z"/><path d="M14 2v4a2 2 0 0 0 2 2h4"/><path d="M10 9H8"/><path d="M16 13H8"/><path d="M16 17H8"/>',
        );
        $inner = isset( $icons[ $name ] ) ? $icons[ $name ] : '';
        $s     = (int) $size;
        return '<svg width="' . $s . '" height="' . $s . '" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">' . $inner . '</svg>';
    }
}

// SVG allowlist for wp_kses on the icon helper output.
$ensurance_svg_allowed = array(
    'svg'    => array( 'width' => true, 'height' => true, 'viewbox' => true, 'fill' => true, 'stroke' => true, 'stroke-width' => true, 'stroke-linecap' => true, 'stroke-linejoin' => true, 'aria-hidden' => true ),
    'path'   => array( 'd' => true ),
    'circle' => array( 'cx' => true, 'cy' => true, 'r' => true ),
    'rect'   => array( 'x' => true, 'y' => true, 'width' => true, 'height' => true, 'rx' => true ),
);

// Resolved destinations.
$privacy_url  = esc_url( home_url( '/privacy' ) );
$hiw_url      = esc_url( home_url( '/how-it-works' ) );
$coverage_url = esc_url( home_url( '/coverage' ) );
$coverage_paths_url = esc_url( home_url( '/coverage/' ) ) . '#paths'; // Second section (coverage request paths) of the coverage page.

// Table of contents — order mirrors the sections below.
$tc_toc = array(
    array( 'n' => '1', 'id' => 'clarity',         'label' => 'Clarity, control, and review' ),
    array( 'n' => '2', 'id' => 'information',      'label' => 'How information is used' ),
    array( 'n' => '3', 'id' => 'licensed-review',  'label' => 'Licensed review' ),
    array( 'n' => '4', 'id' => 'less-exposure',    'label' => 'Reducing exposure' ),
    array( 'n' => '5', 'id' => 'guided',           'label' => 'A guided experience' ),
    array( 'n' => '6', 'id' => 'trust-vs-policy',  'label' => 'Trust Center vs. Privacy Policy' ),
    array( 'n' => '7', 'id' => 'faq',              'label' => 'Questions about trust' ),
);

// Hero feature cues.
$tc_cues = array( 'Controlled request process', 'Licensed review where available', 'Move toward quote options' );

// §1 — Clarity, control & licensed review.
$tc_clarity_lead = array(
    array( 'Clarity',         'You start with clear, plain-English steps and realistic expectations, not a wall of forms or a noisy quote marketplace.' ),
    array( 'Control',         'Your details move through a controlled request process designed to reduce unnecessary exposure, repeated entry, and confusion.' ),
    array( 'Licensed review', 'Your organized request may be reviewed by licensed agents, agencies, or approved partners where available.' ),
);

// §2 — How information is used.
$tc_info_lead = array(
    array( 'Used to organize one request',           'Your answers are kept together in one organized request, helping reduce repeated entry across separate forms.' ),
    array( 'Prepared for licensed review',           'Information is organized so licensed agents, agencies, or approved partners can review your request where available.' ),
    array( 'Handled through a controlled process',   'Your request is handled through a controlled process designed to reduce unnecessary exposure and unwanted contact. The Privacy Policy describes how information may be shared.' ),
);

// §3 — Licensed review.
$tc_review_lead = array(
    array( 'A licensed professional may review your request', 'Licensed agents, agencies, or approved partners may review your organized request where available.' ),
    array( 'One request, clearer context',                    'Your organized request gives licensed professionals clearer context before the next step.' ),
    array( 'Grounded in eligibility',                         'Review may consider location, coverage type, eligibility, carrier participation, and other relevant details.' ),
    array( 'Not broadly shared',                              'Your request is handled through a more controlled process, not broadly posted or sent everywhere without context.' ),
);

// §4 — Reducing exposure (compare).
$tc_compare_bad  = array(
    'Re-entering the same details across separate forms.',
    'Personal information requested before the process is clear.',
    'Details sent broadly without clear context.',
    'Pressure to decide before you understand your next step.',
);
$tc_compare_good = array(
    'Answer guided questions once, in one organized request.',
    'Clear, plain-English steps before your request moves forward.',
    'Reviewed by licensed professionals where available.',
    'Move toward quote options where available, at your own pace.',
);

// §5 — A guided experience.
$tc_guided_lead = array(
    array( 'Controlled by design',  'Your organized request is handled through a more controlled process designed to reduce broad sharing and unwanted contact.' ),
    array( 'One organized request', 'Everything you share stays together in one request, helping reduce repeated entry and confusion.' ),
    array( 'At your own pace',      'Starting a request is not a commitment to buy. Review available options or next steps when you are ready.' ),
);

// §7 — FAQ (mirrors the FAQPage schema above).
$tc_faq = array(
    array( 'What happens to my information when I start a request?', 'Your details are organized into one request so licensed review can happen with clearer context where available. The Privacy Policy explains the formal details of how your information is handled.' ),
    array( 'Who may review my request?', 'Where available, licensed agents, agencies, or approved partners may review your organized request and help identify quote options or a clearer next step.' ),
    array( 'Will my information be sent everywhere?', 'No. Your request is handled through a more controlled process designed to reduce unnecessary exposure, broad sharing, and unwanted contact.' ),
    array( 'How does Ensurance help me move toward quote options?', 'Ensurance helps you start one guided quote request, organize your details, and move toward quote options where available. Quote options may come through licensed agents, agencies, or approved partners.' ),
    array( 'Can one request help me move toward multiple carrier options?', 'Yes, where available. Your organized request can give licensed professionals clearer context to review available carrier options without making you repeat the same details across separate forms.' ),
    array( 'Is starting a request a commitment to buy insurance?', 'No. Starting a request is free and does not require you to buy coverage. You can review available options or next steps at your own pace.' ),
    array( 'Why does Ensurance ask for personal information?', 'The details you share help organize your request so licensed review can happen with better context where available. The Privacy Policy explains how that information is used.' ),
    array( 'Where can I read the full Privacy Policy?', 'The full Privacy Policy is available at any time. This Trust Center explains the experience in plain English. The Privacy Policy explains the formal details of how information is handled.' ),
);

// Reusable renderer for a "lead" list (left-bordered title + body rows).
$tc_render_lead = function ( $items ) {
    echo '<div class="tc-lead">';
    foreach ( $items as $it ) {
        echo '<div class="tc-lead__item">';
        echo '<p class="tc-lead__title">' . esc_html( $it[0] ) . '</p>';
        echo '<p class="tc-lead__body">' . esc_html( $it[1] ) . '</p>';
        echo '</div>';
    }
    echo '</div>';
};

get_header( 'home' );
?>
<main id="main" class="page-trust-center">

  <!-- ── Hero ─────────────────────────────────────────────────────── -->
  <section class="tc-hero" aria-label="Trust and privacy">
    <span class="tc-hero__glow" aria-hidden="true"></span>
    <div class="tc-hero__inner">
      <nav class="tc-breadcrumb" aria-label="Breadcrumb">
        <a href="<?php echo esc_url( home_url( '/' ) ); ?>">Home</a>
        <span aria-hidden="true">/</span>
        <span class="tc-breadcrumb__current">Trust Center</span>
      </nav>
      <div class="tc-hero__body">
        <span class="tc-hero__badge"><?php echo wp_kses( ensurance_home_icon( 'lock', 13 ), $ensurance_svg_allowed ); ?> Trust Center</span>
        <h1 class="tc-hero__title">A clearer, more controlled way to start your insurance request.</h1>
        <p class="tc-hero__sub">Ensurance helps organize your insurance request so licensed agents, agencies, or approved partners can review it and help you move toward quote options where available.</p>
        <div class="tc-hero__cues">
          <?php foreach ( $tc_cues as $cue ) : ?>
          <span class="tc-cue"><?php echo wp_kses( ensurance_home_icon( 'check', 12 ), $ensurance_svg_allowed ); ?> <?php echo esc_html( $cue ); ?></span>
          <?php endforeach; ?>
        </div>
        <div class="hero-actions tc-hero__actions">
          <a class="btn btn-primary btn--lg" href="<?php echo $coverage_paths_url; ?>" data-track="cta_click_start_auto_quote_request" data-cta-text="Start your request" data-page-type="trust_center">Start your request <?php echo wp_kses( ensurance_home_icon( 'arrow-right', 18 ), $ensurance_svg_allowed ); ?></a>
          <a class="btn btn-ghost btn--lg" href="<?php echo $hiw_url; ?>">How Ensurance works</a>
        </div>
        <p class="tc-hero__fine">Want the formal details? <a href="<?php echo $privacy_url; ?>" data-track="privacy_policy_click" data-cta-text="Read the Privacy Policy" data-page-type="trust_center">Read the Privacy Policy</a>.</p>
      </div>
    </div>
  </section>

  <!-- ── Document: sticky TOC + numbered sections ─────────────────── -->
  <div class="tc-doc">
    <nav class="tc-toc" aria-label="Contents">
      <p class="tc-toc__label">On this page</p>
      <?php foreach ( $tc_toc as $i => $t ) : ?>
      <a class="tc-toc__link<?php echo 0 === $i ? ' is-active' : ''; ?>" href="#<?php echo esc_attr( $t['id'] ); ?>" data-tc-toc="<?php echo esc_attr( $t['id'] ); ?>">
        <span class="tc-toc__n"><?php echo esc_html( $t['n'] ); ?></span>
        <span><?php echo wp_kses( $t['label'], array() ); ?></span>
      </a>
      <?php endforeach; ?>
      <a class="tc-toc__link tc-toc__link--cta" href="#start" data-tc-toc="start">
        <span class="tc-toc__n" aria-hidden="true">&rarr;</span>
        <span>Begin your request</span>
      </a>
    </nav>

    <div class="tc-doc__main">

      <!-- §1 -->
      <section class="tc-section" id="clarity">
        <div class="tc-section__head">
          <span class="tc-section__n">1</span>
          <h2>Built around clarity, control, and licensed review.</h2>
        </div>
        <p class="tc-p">Starting an insurance request should feel calm, clear, and understandable. Ensurance is built to help you know what you are sharing, why it matters, and what happens next.</p>
        <?php $tc_render_lead( $tc_clarity_lead ); ?>
      </section>

      <!-- §2 -->
      <section class="tc-section" id="information">
        <div class="tc-section__head">
          <span class="tc-section__n">2</span>
          <h2>Your information is used to organize your request.</h2>
        </div>
        <p class="tc-p">The details you share are used to organize one clear request, so licensed review can happen with better context where available. The full details are described in the Privacy Policy.</p>
        <?php $tc_render_lead( $tc_info_lead ); ?>
        <div class="tc-links">
          <a class="tc-arrowlink" href="<?php echo $privacy_url; ?>" data-track="privacy_policy_click" data-cta-text="Read the Privacy Policy" data-page-type="trust_center">Read the Privacy Policy <?php echo wp_kses( ensurance_home_icon( 'arrow-right', 16 ), $ensurance_svg_allowed ); ?></a>
        </div>
      </section>

      <!-- §3 -->
      <section class="tc-section" id="licensed-review">
        <div class="tc-section__head">
          <span class="tc-section__n">3</span>
          <h2>Your request may be reviewed by licensed insurance professionals.</h2>
        </div>
        <p class="tc-p">Insurance decisions deserve context. Where available, a licensed professional may review your request and help identify available next steps.</p>
        <?php $tc_render_lead( $tc_review_lead ); ?>
      </section>

      <!-- §4 -->
      <section class="tc-section" id="less-exposure">
        <div class="tc-section__head">
          <span class="tc-section__n">4</span>
          <h2>Designed to reduce unnecessary exposure and repeated entry.</h2>
        </div>
        <p class="tc-p">A controlled request process helps keep your details organized and your next steps clear, instead of scattering your information across repeated forms.</p>
        <div class="tc-compare">
          <div class="tc-compare__col tc-compare__col--bad">
            <span class="tc-compare__label"><?php echo wp_kses( ensurance_home_icon( 'ban', 14 ), $ensurance_svg_allowed ); ?> The noisy way</span>
            <div class="tc-compare__items">
              <?php foreach ( $tc_compare_bad as $item ) : ?>
              <div class="tc-compare__item">
                <span class="tc-compare__mark"><?php echo wp_kses( ensurance_home_icon( 'x', 12 ), $ensurance_svg_allowed ); ?></span>
                <span><?php echo esc_html( $item ); ?></span>
              </div>
              <?php endforeach; ?>
            </div>
          </div>
          <div class="tc-compare__col tc-compare__col--good">
            <span class="tc-compare__label"><?php echo wp_kses( ensurance_home_icon( 'shield-check', 14 ), $ensurance_svg_allowed ); ?> A controlled request process</span>
            <div class="tc-compare__items">
              <?php foreach ( $tc_compare_good as $item ) : ?>
              <div class="tc-compare__item">
                <span class="tc-compare__mark tc-compare__mark--good"><?php echo wp_kses( ensurance_home_icon( 'check', 12 ), $ensurance_svg_allowed ); ?></span>
                <span><?php echo esc_html( $item ); ?></span>
              </div>
              <?php endforeach; ?>
            </div>
          </div>
        </div>
      </section>

      <!-- §5 -->
      <section class="tc-section" id="guided">
        <div class="tc-section__head">
          <span class="tc-section__n">5</span>
          <h2>A guided request experience, not a noisy quote marketplace.</h2>
        </div>
        <p class="tc-p">Start with clarity before moving forward. Your details move through a controlled request process built around organization, licensed review where available, and clearer next steps.</p>
        <?php $tc_render_lead( $tc_guided_lead ); ?>
        <div class="tc-links">
          <a class="tc-arrowlink" href="<?php echo $hiw_url; ?>">How Ensurance works <?php echo wp_kses( ensurance_home_icon( 'arrow-right', 16 ), $ensurance_svg_allowed ); ?></a>
          <a class="tc-arrowlink" href="<?php echo $coverage_url; ?>">Coverage types <?php echo wp_kses( ensurance_home_icon( 'arrow-right', 16 ), $ensurance_svg_allowed ); ?></a>
        </div>
      </section>

      <!-- §6 -->
      <section class="tc-section" id="trust-vs-policy">
        <div class="tc-section__head">
          <span class="tc-section__n">6</span>
          <h2>This page explains the trust experience. The Privacy Policy explains the formal details.</h2>
        </div>
        <div class="tc-note" role="note">
          <span class="tc-note__icon"><?php echo wp_kses( ensurance_home_icon( 'file-text', 18 ), $ensurance_svg_allowed ); ?></span>
          <p class="tc-note__text">This Trust Center explains how Ensurance approaches your insurance request in plain English. It is not the formal Privacy Policy. For complete details about how information is handled, <a href="<?php echo $privacy_url; ?>" data-track="privacy_policy_click" data-cta-text="Read the Privacy Policy" data-page-type="trust_center">read the Privacy Policy</a>.</p>
        </div>
      </section>

      <!-- §7 -->
      <section class="tc-section" id="faq">
        <div class="tc-section__head">
          <span class="tc-section__n">7</span>
          <h2>Questions about trust and privacy.</h2>
        </div>
        <div class="faq-list tc-faq">
          <?php foreach ( $tc_faq as $i => $f ) : ?>
          <details<?php echo 0 === $i ? ' open' : ''; ?>>
            <summary><?php echo esc_html( $f[0] ); ?></summary>
            <p><?php echo esc_html( $f[1] ); ?></p>
          </details>
          <?php endforeach; ?>
        </div>
      </section>

    </div>
  </div>

  <!-- ── Final CTA ────────────────────────────────────────────────── -->
  <section class="final-cta tc-final" id="start" aria-label="Start your request">
    <div class="final-card">
      <h2>Begin your insurance request through a clearer, more controlled process.</h2>
      <p>Start one guided request. Ensurance helps organize your details so you can move toward quote options or a clearer next step where available.</p>
      <div class="hero-actions">
        <a class="btn btn-reversed btn--lg" href="<?php echo $coverage_paths_url; ?>" data-track="cta_click_start_auto_quote_request" data-cta-text="Start your request" data-page-type="trust_center">Start your request <?php echo wp_kses( ensurance_home_icon( 'arrow-right', 18 ), $ensurance_svg_allowed ); ?></a>
      </div>
      <p class="tc-final__fine"><a href="<?php echo $privacy_url; ?>" data-track="privacy_policy_click" data-cta-text="Read the Privacy Policy" data-page-type="trust_center">Read the Privacy Policy</a></p>
    </div>
  </section>

</main>
<?php get_footer( 'home' ); ?>
