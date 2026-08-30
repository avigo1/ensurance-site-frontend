<?php
/**
 * Template Name: Health Insurance Quote Request (Marketing)
 *
 * /health-insurance-quote-request — built to match the "Ensurance Health
 * Insurance" standalone redesign (Calm Intelligence). The homepage coverage
 * grid funnels its "Health" card here (see page-home.php $lines, slug
 * health-insurance-quote-request) and the /coverage grid links the same path.
 *
 * Mirrors the Auto Insurance page exactly: it uses the homepage chrome
 * (get_header('home') / get_footer('home')) and shares assets/home.css +
 * assets/home.js for tokens, chrome and base components (buttons, the FAQ
 * accordion, the final CTA, the eyebrow, icon boxes). The page-specific layout —
 * the light hero with trust cues, the "by the numbers" health-enrollment stat
 * band, the four request-area cards, the four-step process, the dark "licensed
 * review" panel — lives in assets/health-insurance-quote-request.css, with the
 * scroll-reveal motion in assets/health-insurance-quote-request.js. Both are
 * enqueued and isolated from the shared marketing bundle in functions.php
 * (ensurance_health_insurance_quote_request_assets), scoped to this template
 * only, so no other page is affected.
 *
 * SEO: title / meta description / canonical / robots are owned by Yoast and
 * emitted through wp_head(); this template outputs none of them. The FAQPage
 * JSON-LD below is shipped here (Yoast does not emit it for this page) and is
 * built from the same $hq_faq array that renders the visible FAQ accordion, so
 * the two can never drift.
 */

// Separate the Health authority page from the nested transactional request page.
add_filter( 'wpseo_title', function () {
    return 'Health Insurance Affordability & Coverage Guide | Ensurance';
} );
add_filter( 'pre_get_document_title', function () {
    return 'Health Insurance Affordability & Coverage Guide | Ensurance';
}, 99 );
add_filter( 'wpseo_metadesc', function () {
    return 'Learn what can affect health insurance affordability in 2026, how Marketplace savings work, and what to prepare before starting an organized request.';
} );
add_filter( 'wpseo_opengraph_title', function () {
    return 'Health Insurance Affordability & Coverage Guide';
} );
add_filter( 'wpseo_opengraph_desc', function () {
    return 'Learn what can affect health insurance affordability in 2026, how Marketplace savings work, and what to prepare before starting an organized request.';
} );
add_filter( 'wpseo_schema_webpage', function( $data ) {
    if ( is_array( $data ) ) {
        $data['name'] = 'Health Insurance Affordability & Coverage Guide | Ensurance';
        $data['description'] = 'Learn what can affect health insurance affordability in 2026, how Marketplace savings work, and what to prepare before starting an organized request.';
    }
    return $data;
} );

/**
 * Inline Lucide icon renderer (shared with the other Calm Intelligence page
 * templates via the function_exists guard). Only one page template renders per
 * request, so this copy carries the full glyph set the Health Insurance page
 * needs. Paths from Lucide (ISC license).
 */
if ( ! function_exists( 'ensurance_home_icon' ) ) {
    function ensurance_home_icon( $name, $size = 20 ) {
        $icons = array(
            'shield-check' => '<path d="M20 13c0 5-3.5 7.5-7.66 8.95a1 1 0 0 1-.67-.01C7.5 20.5 4 18 4 13V6a1 1 0 0 1 1-1c2 0 4.5-1.2 6.24-2.72a1.17 1.17 0 0 1 1.52 0C14.51 3.81 17 5 19 5a1 1 0 0 1 1 1z"/><path d="m9 12 2 2 4-4"/>',
            'check'        => '<path d="M20 6 9 17l-5-5"/>',
            'arrow-right'  => '<path d="M5 12h14"/><path d="m12 5 7 7-7 7"/>',
            'lock'         => '<rect width="18" height="11" x="3" y="11" rx="2"/><path d="M7 11V7a5 5 0 0 1 10 0v4"/>',
            'file-text'    => '<path d="M15 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V7Z"/><path d="M14 2v4a2 2 0 0 0 2 2h4"/><path d="M10 9H8"/><path d="M16 13H8"/><path d="M16 17H8"/>',
            'user'         => '<path d="M19 21v-2a4 4 0 0 0-4-4H9a4 4 0 0 0-4 4v2"/><circle cx="12" cy="7" r="4"/>',
            'clock'        => '<circle cx="12" cy="12" r="10"/><polyline points="12 6 12 12 16 14"/>',
            'home'         => '<path d="m3 9 9-7 9 7v11a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2z"/><path d="M9 22V12h6v10"/>',
            'layers'       => '<path d="M12.83 2.18a2 2 0 0 0-1.66 0L2.6 6.08a1 1 0 0 0 0 1.83l8.58 3.91a2 2 0 0 0 1.66 0l8.58-3.9a1 1 0 0 0 0-1.83Z"/><path d="M2 12a1 1 0 0 0 .58.91l8.6 3.91a2 2 0 0 0 1.65 0l8.58-3.9A1 1 0 0 0 22 12"/><path d="M2 17a1 1 0 0 0 .58.91l8.6 3.91a2 2 0 0 0 1.65 0l8.58-3.9A1 1 0 0 0 22 17"/>',
            'sparkles'     => '<path d="M9.937 15.5A2 2 0 0 0 8.5 14.063l-6.135-1.582a.5.5 0 0 1 0-.962L8.5 9.936A2 2 0 0 0 9.937 8.5l1.582-6.135a.5.5 0 0 1 .962 0L14.063 8.5A2 2 0 0 0 15.5 9.937l6.135 1.581a.5.5 0 0 1 0 .964L15.5 14.063a2 2 0 0 0-1.437 1.437l-1.582 6.135a.5.5 0 0 1-.962 0z"/><path d="M20 3v4"/><path d="M22 5h-4"/><path d="M4 17v2"/><path d="M5 18H3"/>',
            'heart'        => '<path d="M19 14c1.49-1.46 3-3.21 3-5.5A5.5 5.5 0 0 0 16.5 3c-1.76 0-3 .5-4.5 2-1.5-1.5-2.74-2-4.5-2A5.5 5.5 0 0 0 2 8.5c0 2.3 1.5 4.05 3 5.5l7 7Z"/>',
            'heart-pulse'  => '<path d="M19 14c1.49-1.46 3-3.21 3-5.5A5.5 5.5 0 0 0 16.5 3c-1.76 0-3 .5-4.5 2-1.5-1.5-2.74-2-4.5-2A5.5 5.5 0 0 0 2 8.5c0 2.3 1.5 4.05 3 5.5l7 7Z"/><path d="M3.22 12H9.5l.5-1 2 4.5 2-7 1.5 3.5h5.27"/>',
            'calendar'     => '<path d="M8 2v4"/><path d="M16 2v4"/><rect width="18" height="18" x="3" y="4" rx="2"/><path d="M3 10h18"/>',
        );
        $inner = isset( $icons[ $name ] ) ? $icons[ $name ] : '';
        $s     = (int) $size;
        return '<svg width="' . $s . '" height="' . $s . '" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">' . $inner . '</svg>';
    }
}

// SVG allowlist for wp_kses on the icon helper output.
$ensurance_svg_allowed = array(
    'svg'      => array( 'width' => true, 'height' => true, 'viewbox' => true, 'fill' => true, 'stroke' => true, 'stroke-width' => true, 'stroke-linecap' => true, 'stroke-linejoin' => true, 'aria-hidden' => true ),
    'path'     => array( 'd' => true ),
    'circle'   => array( 'cx' => true, 'cy' => true, 'r' => true ),
    'rect'     => array( 'x' => true, 'y' => true, 'width' => true, 'height' => true, 'rx' => true, 'ry' => true ),
    'polyline' => array( 'points' => true ),
);

// Resolved destinations (use the site's real slugs, like the Auto page — the
// standalone design's /trust-and-privacy placeholder maps to /trust-center here).
$hq_hiw_url      = esc_url( home_url( '/how-ensurance-works/' ) );
$hq_coverage_url = esc_url( home_url( '/insurance-coverage/' ) );
$hq_trust_url    = esc_url( home_url( '/trust-center/' ) );
// "Start health request" routes to the published health request page.
$hq_start_url    = esc_url( home_url( '/insurance-coverage/health-insurance-quote-request/health-insurance-quote-help/' ) );

// §Hero — trust cues (icon / label).
$hq_cues = array(
    array( 'shield-check', 'Licensed review where available' ),
    array( 'layers',       'One organized request' ),
    array( 'user',         'A clearer next step' ),
    array( 'lock',         'Controlled request process' ),
);

// §The bigger picture — health-enrollment stat band.
$hq_stat_lead = array(
    'label' => '2026 Marketplace enrollment',
    'value' => '23.1M',
    'body'  => 'consumers selected or were automatically re-enrolled in Marketplace coverage during the 2026 Open Enrollment Period.',
    'src'   => 'Source: CMS 2026 Open Enrollment Report',
);
$hq_stats = array(
    array( '46%', 'of 2026 Marketplace enrollees had household incomes between 100% and 150% of the federal poverty level.', 'CMS, 2026' ),
    array( '5%', 'fewer consumers enrolled during the 2026 Open Enrollment Period than during the 2025 period.', 'CMS, 2026' ),
);

// §What your health request may include — cards (icon / title / body / points).
$hq_include = array(
    array( 'user',     'Who needs coverage',      "The people you're exploring coverage for.",      array( 'Individual or family coverage', "Who's included on the request", 'Adding dependents' ) ),
    array( 'heart',    'Coverage needs',          "The kind of health coverage you're exploring.",  array( "The coverage you're looking for", 'Priorities and preferences', 'Questions you want answered' ) ),
    array( 'calendar', 'Timing & situation',      'When and why you\'re looking now.',               array( "When you'd like coverage to start", 'Life changes that prompted the search', 'No need to know every detail' ) ),
    array( 'home',     'Location & eligibility',  'Where coverage would apply.',                    array( 'Your location for eligibility', 'Availability varies by area', 'Reviewed where applicable' ) ),
);

// §How the process works — steps (icon / title / body).
$hq_steps = array(
    array( 'file-text',    'Answer a few guided questions',                  "Tell Ensurance who needs coverage and what you're looking for. Minimal typing, one step at a time, all in one place." ),
    array( 'layers',       'Your details become one request',                'Ensurance structures everything into a single, organized health request — so nothing important is missing or repeated.' ),
    array( 'shield-check', 'Licensed professionals review where applicable', 'Licensed professionals review your request against the available options, where applicable and where available.' ),
    array( 'sparkles',     'Receive coverage options where available',       'Where availability and eligibility allow, you receive health coverage options and a clearer next step — no pressure to decide.' ),
);

// §Why licensed review matters — dark-panel glass cards (icon / title / body).
$hq_review = array(
    array( 'user',      'A licensed professional, not a robot', 'Licensed professionals review available options where applicable — a person decides what may fit your situation.' ),
    array( 'file-text', 'One organized request',                'Your coverage details stay together as a single request, so nothing important is missing or repeated.' ),
    array( 'lock',      'CATE™ controlled access',        'Your protected health request moves through the applicable controlled-access process supported by CATE™, the Controlled Access Trust Engine, with review by eligible licensed professionals where available.' ),
);

// §FAQ — also feeds the FAQPage JSON-LD below.
$hq_faq = array(
    array( 'What is a guided health insurance request?', 'A guided health insurance request is a short, structured set of questions about who needs coverage and the health coverage needs you have. Ensurance organizes your answers into one clear request so licensed professionals can review available options where applicable — instead of you re-entering the same details across separate forms.' ),
    array( 'Are health insurance options available everywhere?', "Not always. Availability varies by location and by the options available to licensed professionals. Ensurance helps organize your health insurance request so available options can be reviewed where applicable and where available — it doesn't guarantee that options exist in every area." ),
    array( 'Who reviews my health insurance request?', 'Licensed professionals review your structured health request. A licensed professional — not an automated list — decides which available health coverage options may fit your situation, where applicable.' ),
    array( 'What details may be needed?', "Typically who needs coverage, the coverage needs you're exploring, and your location for eligibility. You don't need to have everything ready before you begin — the guided questions handle it one step at a time." ),
    array( 'Are quote options guaranteed?', "No. Availability, eligibility, and licensed review determine which health quote options may be available where applicable. Ensurance doesn't promise instant quotes, guaranteed eligibility, or guaranteed savings." ),
    array( 'What happens after I start?', 'You answer a few guided questions about your health coverage needs. Ensurance structures your details and moves them into licensed review, where you may receive health coverage options where available — at your own pace, with no pressure to decide.' ),
);

// --- Per-page FAQPage schema — built from $hq_faq so it mirrors the visible FAQ. ---
add_action( 'wp_head', function () use ( $hq_faq ) {
    $entities = array();
    foreach ( $hq_faq as $f ) {
        $entities[] = array(
            '@type'          => 'Question',
            'name'           => $f[0],
            'acceptedAnswer' => array( '@type' => 'Answer', 'text' => $f[1] ),
        );
    }
    $schema = array(
        '@context'   => 'https://schema.org',
        '@type'      => 'FAQPage',
        '@id'        => home_url( '/insurance-coverage/health-insurance-quote-request/' ) . '#faq',
        'mainEntity' => $entities,
    );
    $flags = JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE;
    echo '<script type="application/ld+json">' . wp_json_encode( $schema, $flags ) . '</script>' . "\n";
}, 20 );

get_header( 'home' );
?>
<main id="main" class="page-health-insurance">

  <!-- ── Hero (light, trust cues) ─────────────────────────────────── -->
  <section class="hq-hero reveal" aria-label="Health insurance">
    <span class="hq-hero__glow hq-hero__glow--a" aria-hidden="true"></span>
    <span class="hq-hero__glow hq-hero__glow--b" aria-hidden="true"></span>
    <div class="hq-hero__inner">
      <span class="hq-pill"><?php echo wp_kses( ensurance_home_icon( 'heart-pulse', 14 ), $ensurance_svg_allowed ); ?> Health insurance</span>
      <h1 class="hq-hero__title">Health insurance help, with a <span class="hq-accent">clearer starting point</span>.</h1>
      <p class="hq-hero__sub">Answer a few guided questions once. Ensurance organizes one health request for licensed review where available, so you can understand the next step without repeating the same information across multiple forms.</p>
      <div class="hq-hero__actions">
        <a class="btn btn-primary btn--lg" href="<?php echo $hq_start_url; ?>" data-track="cta_click_start_health_quote_request" data-cta-text="Start health request" data-page-type="health_insurance">Start health request <?php echo wp_kses( ensurance_home_icon( 'arrow-right', 18 ), $ensurance_svg_allowed ); ?></a>
        <a class="btn btn-ghost btn--lg" href="<?php echo $hq_hiw_url; ?>" data-track="hero_how_it_works_click" data-cta-text="How Ensurance works" data-page-type="health_insurance">How Ensurance works</a>
      </div>
      <div class="hq-cues">
        <?php foreach ( $hq_cues as $cue ) : ?>
        <span class="hq-cue"><span class="hq-cue__icon"><?php echo wp_kses( ensurance_home_icon( $cue[0], 16 ), $ensurance_svg_allowed ); ?></span><?php echo esc_html( $cue[1] ); ?></span>
        <?php endforeach; ?>
      </div>
    </div>
  </section>

  <!-- ── The bigger picture (health-enrollment stat band) ─────────── -->
  <section class="hq-section hq-section--lead reveal" aria-label="The bigger picture">
    <div class="hq-head">
      <span class="eyebrow">The bigger picture</span>
      <h2>Marketplace enrollment remains high in 2026.</h2>
      <p>CMS reports that 23.1 million consumers selected or were automatically re-enrolled in Marketplace coverage for 2026. That is about 1.2 million fewer than 2025, but still about 1.7 million more than 2024 and 6.8 million more than 2023. Ensurance helps organize one health insurance request for licensed review where available.</p>
    </div>
    <div class="hq-stats">
      <div class="hq-stat-lead">
        <span class="hq-stat-lead__glow" aria-hidden="true"></span>
        <span class="hq-stat-lead__label"><?php echo esc_html( $hq_stat_lead['label'] ); ?></span>
        <span class="hq-stat-lead__value"><?php echo esc_html( $hq_stat_lead['value'] ); ?></span>
        <p class="hq-stat-lead__body"><?php echo esc_html( $hq_stat_lead['body'] ); ?></p>
        <span class="hq-stat-lead__src"><?php echo esc_html( $hq_stat_lead['src'] ); ?></span>
      </div>
      <div class="hq-stat-col">
        <?php foreach ( $hq_stats as $stat ) : ?>
        <div class="hq-stat-card">
          <span class="hq-stat-card__value"><?php echo esc_html( $stat[0] ); ?></span>
          <p class="hq-stat-card__label"><?php echo esc_html( $stat[1] ); ?></p>
          <span class="hq-stat-card__src">Source: <?php echo esc_html( $stat[2] ); ?></span>
        </div>
        <?php endforeach; ?>
      </div>
    </div>
    <p class="hq-stats__note">Figures reflect national ACA Marketplace data and describe the broader market, not Ensurance results. Availability, eligibility, and licensed review determine which options may be available — quotes and savings are not guaranteed.</p>

    <div class="hq-callout" role="note" aria-label="How affordability works in 2026">
      <h3>What can make health insurance more affordable in 2026?</h3>
      <p>Marketplace premium tax credits are based on household information and expected income. HealthCare.gov currently states that households with income between 100% and 400% of the federal poverty level qualify for the premium tax credit in all states, and that the Marketplace is the only place to receive that credit.</p>
      <p>The additional pandemic-era savings ended on December 31, 2025, so people who still qualify for savings may pay more for a Marketplace plan in 2026 than they did in 2025. Eligibility and the amount of any tax credit depend on the household information submitted through the Marketplace.</p>
      <p><a href="https://www.healthcare.gov/glossary/premium-tax-credit/" rel="noopener noreferrer">HealthCare.gov: Premium tax credit</a> &middot; <a href="https://www.healthcare.gov/lower-costs/save-on-monthly-premiums/" rel="noopener noreferrer">HealthCare.gov: Saving on monthly premiums</a> &middot; <a href="https://www.cms.gov/files/document/health-insurance-exchanges-2026-open-enrollment-report.pdf" rel="noopener noreferrer">CMS: 2026 Open Enrollment Report</a></p>
    </div>
  </section>

  <!-- ── What your health request may include (cards) ─────────────── -->
  <section class="hq-section reveal" aria-label="What your health request may include">
    <div class="hq-head">
      <span class="eyebrow">What's covered</span>
      <h2>What your health request may include.</h2>
      <p>A few guided questions, grouped into four simple areas. You don't need everything ready to begin — the request fills in one step at a time.</p>
    </div>
    <div class="hq-include">
      <?php foreach ( $hq_include as $card ) : ?>
      <div class="hq-include__card card">
        <span class="icon-box icon-box--navy"><?php echo wp_kses( ensurance_home_icon( $card[0], 22 ), $ensurance_svg_allowed ); ?></span>
        <div class="hq-include__text">
          <h3><?php echo esc_html( $card[1] ); ?></h3>
          <p><?php echo esc_html( $card[2] ); ?></p>
        </div>
        <div class="hq-include__points">
          <?php foreach ( $card[3] as $point ) : ?>
          <div class="hq-include__point">
            <span class="hq-include__check"><?php echo wp_kses( ensurance_home_icon( 'check', 12 ), $ensurance_svg_allowed ); ?></span>
            <span><?php echo esc_html( $point ); ?></span>
          </div>
          <?php endforeach; ?>
        </div>
      </div>
      <?php endforeach; ?>
    </div>
    <div class="hq-callout hq-callout--neutral" role="note">
      <span class="hq-callout__icon"><?php echo wp_kses( ensurance_home_icon( 'user', 18 ), $ensurance_svg_allowed ); ?></span>
      <div>
        <p class="hq-callout__title">You don't need to know every detail before you begin.</p>
        <p class="hq-callout__body">Answer a few guided questions about your coverage needs. A licensed professional reviews the details with you where applicable — you don't have to know exactly what coverage you need to start.</p>
      </div>
    </div>
  </section>

  <!-- ── How the process works (steps) ────────────────────────────── -->
  <section class="hq-section reveal" id="process" aria-label="How the health insurance request process works">
    <div class="hq-head">
      <span class="eyebrow">How it works</span>
      <h2>How the health insurance request process works.</h2>
      <p>Four clear steps, so you always know where you are and what happens next.</p>
    </div>
    <div class="hq-steps">
      <?php foreach ( $hq_steps as $i => $step ) : ?>
      <div class="hq-step card">
        <div class="hq-step__top">
          <span class="icon-box icon-box--navy"><?php echo wp_kses( ensurance_home_icon( $step[0], 19 ), $ensurance_svg_allowed ); ?></span>
          <span class="hq-step__num"><?php echo esc_html( sprintf( '%02d', $i + 1 ) ); ?></span>
        </div>
        <h3><?php echo esc_html( $step[1] ); ?></h3>
        <p><?php echo esc_html( $step[2] ); ?></p>
      </div>
      <?php endforeach; ?>
    </div>
    <div class="hq-callout hq-callout--warn" role="note">
      <span class="hq-callout__icon"><?php echo wp_kses( ensurance_home_icon( 'clock', 18 ), $ensurance_svg_allowed ); ?></span>
      <div>
        <p class="hq-callout__title">Availability varies</p>
        <p class="hq-callout__body">Availability, eligibility, and licensed professional review determine which health coverage options may be available where applicable — and quotes are never guaranteed.</p>
      </div>
    </div>
  </section>

  <!-- ── Why licensed review matters (dark panel) ─────────────────── -->
  <section class="hq-review reveal" aria-label="Why licensed review matters">
    <div class="hq-panel">
      <span class="hq-panel__bar" aria-hidden="true"></span>
      <span class="hq-panel__glow" aria-hidden="true"></span>
      <div class="hq-panel__head">
        <span class="hq-kicker">Reviewed by a person</span>
        <h2 class="hq-panel__title">Why licensed review matters.</h2>
        <p class="hq-panel__sub">Health coverage takes a real conversation — not an automated price list.</p>
      </div>
      <div class="hq-panel__cards">
        <?php foreach ( $hq_review as $card ) : ?>
        <div class="hq-glass">
          <span class="hq-glass__icon"><?php echo wp_kses( ensurance_home_icon( $card[0], 17 ), $ensurance_svg_allowed ); ?></span>
          <p class="hq-glass__title"><?php echo esc_html( $card[1] ); ?></p>
          <p class="hq-glass__body"><?php echo esc_html( $card[2] ); ?></p>
        </div>
        <?php endforeach; ?>
      </div>
      <div class="hq-panel__links">
        <a class="hq-link hq-link--light" href="<?php echo $hq_hiw_url; ?>">How Ensurance works <?php echo wp_kses( ensurance_home_icon( 'arrow-right', 16 ), $ensurance_svg_allowed ); ?></a>
        <a class="hq-link hq-link--accent" href="<?php echo $hq_trust_url; ?>">Read Trust and privacy <?php echo wp_kses( ensurance_home_icon( 'arrow-right', 16 ), $ensurance_svg_allowed ); ?></a>
        <a class="hq-link hq-link--muted" href="<?php echo $hq_coverage_url; ?>">Coverage types <?php echo wp_kses( ensurance_home_icon( 'arrow-right', 16 ), $ensurance_svg_allowed ); ?></a>
      </div>
    </div>
  </section>

  <!-- ── FAQ (reuses .faq / .faq-list from home.css) ──────────────── -->
  <section class="faq reveal" id="faq" aria-label="Questions before starting a health request">
    <div class="faq__head">
      <span class="eyebrow">Before you start</span>
      <h2>Questions before starting a health request.</h2>
    </div>
    <div class="faq-list">
      <?php foreach ( $hq_faq as $i => $f ) : ?>
      <details<?php echo 0 === $i ? ' open' : ''; ?>>
        <summary><?php echo esc_html( $f[0] ); ?></summary>
        <p><?php echo esc_html( $f[1] ); ?></p>
      </details>
      <?php endforeach; ?>
    </div>
  </section>

  <!-- ── Final CTA (reuses .final-cta / .final-card from home.css) ─── -->
  <section class="final-cta hq-final reveal" aria-label="Start your health insurance request">
    <div class="final-card">
      <h2>Start your health insurance request.</h2>
      <p>Begin one guided health request — structured for licensed review of available options where applicable, with coverage options where available.</p>
      <div class="hero-actions">
        <a class="btn btn-reversed btn--lg" href="<?php echo $hq_start_url; ?>" data-track="cta_click_start_health_quote_request" data-cta-text="Start health request" data-page-type="health_insurance">Start health request <?php echo wp_kses( ensurance_home_icon( 'arrow-right', 18 ), $ensurance_svg_allowed ); ?></a>
        <a class="btn btn--lg hq-ghost-dark" href="<?php echo $hq_hiw_url; ?>" data-track="hero_how_it_works_click" data-cta-text="How Ensurance works" data-page-type="health_insurance">How Ensurance works</a>
      </div>
    </div>
  </section>

</main>
<?php get_footer( 'home' ); ?>
