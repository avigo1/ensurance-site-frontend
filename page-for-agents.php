<?php
/**
 * Template Name: For Agents (Marketing)
 *
 * /for-agents — rebuilt to match the "Ensurance For Agents" standalone redesign
 * (Calm Intelligence). Uses the homepage chrome (get_header('home') /
 * get_footer('home')) and shares assets/home.css + assets/home.js for tokens,
 * chrome and base components (buttons, FAQ accordion, final CTA, status badge).
 * The page-specific layout — the dark asymmetric hero with the structured-request
 * preview, the bulk-leads vs. structured-request compare, the dark "controlled
 * flow" panel, the connected request stepper, the coverage-line chips, the
 * participation-standards tiles and the agent-access CTA card — lives in
 * assets/for-agents.css, with the scroll-reveal motion in assets/for-agents.js.
 * Both are enqueued and isolated from the shared marketing bundle in
 * functions.php (ensurance_for_agents_assets), scoped to this template only.
 *
 * SEO: title / meta description / canonical / robots are owned by Yoast and
 * emitted through wp_head(); this template outputs none of them. The FAQPage
 * JSON-LD below is shipped here (Yoast does not emit it for this page) and
 * mirrors the visible FAQ accordion.
 */

// --- Per-page FAQPage schema — mirrors the visible FAQ accordion. ---
$fa_schema = json_decode( <<<'JSON'
{
  "@context": "https://schema.org",
  "@type": "FAQPage",
  "@id": "https://www.ensurance.com/for-agents#faq",
  "mainEntity": [
    { "@type": "Question", "name": "What is Ensurance for agents?", "acceptedAnswer": { "@type": "Answer", "text": "Ensurance helps licensed agents and agencies review structured shopper insurance requests. Consumer insurance intent is organized through a controlled request flow and prepared for licensed review of available carrier options." } },
    { "@type": "Question", "name": "How is Ensurance different from buying bulk insurance leads?", "acceptedAnswer": { "@type": "Answer", "text": "Ensurance is a structured request system, not a bulk lead seller. Shopper requests arrive with context, organized for licensed review, rather than as raw volume to be auctioned to a long list of buyers." } },
    { "@type": "Question", "name": "Does Ensurance guarantee request volume?", "acceptedAnswer": { "@type": "Answer", "text": "No. Ensurance does not promise or guarantee request volume. The focus is request quality and context, and availability can vary." } },
    { "@type": "Question", "name": "Does Ensurance guarantee close rates or sales?", "acceptedAnswer": { "@type": "Answer", "text": "No. Ensurance does not guarantee close rates, sales, appointments, or exclusivity. Outcomes depend on your review, shopper fit, carrier appetite, and eligibility." } },
    { "@type": "Question", "name": "What kind of information may be included in a shopper request?", "acceptedAnswer": { "@type": "Answer", "text": "A structured shopper request may include the coverage type a shopper is exploring and the context they shared through guided questions, organized so you can review it before the first conversation." } },
    { "@type": "Question", "name": "Who can request agent access?", "acceptedAnswer": { "@type": "Answer", "text": "Licensed insurance agents and independent insurance agencies can request access. Participation is reviewed against professional standards and is not guaranteed." } },
    { "@type": "Question", "name": "Can agents review multiple coverage types?", "acceptedAnswer": { "@type": "Answer", "text": "Yes. Structured shopper requests span common coverage types, including auto, home, renters, life, business, and health, where available." } },
    { "@type": "Question", "name": "How does Ensurance protect shopper trust?", "acceptedAnswer": { "@type": "Answer", "text": "Shopper requests are organized for licensed review where appropriate, not blasted to a long list of companies. A trust-first request flow is the foundation of the agent experience." } },
    { "@type": "Question", "name": "Does Ensurance provide quotes directly?", "acceptedAnswer": { "@type": "Answer", "text": "No. Ensurance does not provide quotes directly. It structures shopper requests so licensed agents, agencies, or approved insurance partners can review available carrier options." } },
    { "@type": "Question", "name": "How do I get started?", "acceptedAnswer": { "@type": "Answer", "text": "Request agent access using the form on this page. Provide your professional details for review against participation standards." } }
  ]
}
JSON, true );

add_action( 'wp_head', function () use ( $fa_schema ) {
    if ( $fa_schema ) {
        $flags = JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE | JSON_PRETTY_PRINT;
        echo '<script type="application/ld+json">' . "\n";
        echo wp_json_encode( $fa_schema, $flags ) . "\n";
        echo "</script>\n";
    }
}, 20 );

/**
 * Inline Lucide icon renderer (shared with page-home.php / page-how-it-works.php
 * / page-coverage.php / page-trust-center.php via function_exists guard). Only
 * one page template renders per request, so this copy carries the full glyph set
 * the For Agents page needs. Paths from Lucide (ISC license).
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
            'user'         => '<path d="M19 21v-2a4 4 0 0 0-4-4H9a4 4 0 0 0-4 4v2"/><circle cx="12" cy="7" r="4"/>',
            'message'      => '<path d="M21 15a2 2 0 0 1-2 2H7l-4 4V5a2 2 0 0 1 2-2h14a2 2 0 0 1 2 2z"/>',
            'clock'        => '<circle cx="12" cy="12" r="10"/><polyline points="12 6 12 12 16 14"/>',
            'car'          => '<path d="M19 17h2c.6 0 1-.4 1-1v-3c0-.9-.7-1.7-1.5-1.9C18.7 10.6 16 10 16 10s-1.3-1.4-2.2-2.3c-.5-.4-1.1-.7-1.8-.7H5c-.6 0-1.1.4-1.4.9l-1.4 2.9A3.7 3.7 0 0 0 2 12v4c0 .6.4 1 1 1h2"/><circle cx="7" cy="17" r="2"/><path d="M9 17h6"/><circle cx="17" cy="17" r="2"/>',
            'home'         => '<path d="m3 9 9-7 9 7v11a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2z"/><path d="M9 22V12h6v10"/>',
            'key'          => '<circle cx="7.5" cy="15.5" r="5.5"/><path d="m21 2-9.6 9.6"/><path d="m15.5 7.5 3 3L22 7l-3-3"/>',
            'heart'        => '<path d="M19 14c1.49-1.46 3-3.21 3-5.5A5.5 5.5 0 0 0 16.5 3c-1.76 0-3 .5-4.5 2-1.5-1.5-2.74-2-4.5-2A5.5 5.5 0 0 0 2 8.5c0 2.3 1.5 4.05 3 5.5l7 7Z"/>',
            'briefcase'    => '<rect width="20" height="14" x="2" y="7" rx="2" ry="2"/><path d="M16 21V5a2 2 0 0 0-2-2h-4a2 2 0 0 0-2 2v16"/>',
            'heart-pulse'  => '<path d="M19 14c1.49-1.46 3-3.21 3-5.5A5.5 5.5 0 0 0 16.5 3c-1.76 0-3 .5-4.5 2-1.5-1.5-2.74-2-4.5-2A5.5 5.5 0 0 0 2 8.5c0 2.3 1.5 4.05 3 5.5l7 7Z"/><path d="M3.22 12H9.5l.5-1 2 4.5 2-7 1.5 3.5h5.27"/>',
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

// Resolved destinations (use the site's real slugs).
$fa_home_url     = esc_url( home_url( '/' ) );
$fa_hiw_url      = esc_url( home_url( '/how-it-works' ) );
$fa_coverage_url = esc_url( home_url( '/coverage' ) );
$fa_trust_url    = esc_url( home_url( '/trust-center' ) );
$fa_contact_url  = esc_url( home_url( '/contact' ) );
$fa_access_id    = '#agent-access';
// Agent access submit destination. No standalone form exists yet, so route to
// the contact channel referenced throughout the page; swap for the real agent
// access form URL when it is live.
$fa_access_form_url = $fa_contact_url;

// Hero request-preview rows (label / value).
$fa_request_rows = array(
    array( 'Location',       'Austin, TX' ),
    array( 'Household',      '2 vehicles &middot; 2 drivers' ),
    array( 'Looking for',    'Lower rate, multi-car' ),
    array( 'Current status', 'Shopping, no lapse' ),
);

// §Context — bulk-leads vs. structured-request compare.
$fa_compare_bad = array(
    'Raw contact details with little context behind them.',
    'The same record sold across a long list of buyers.',
    'Pressure to chase volume instead of fit.',
    'Shopper trust spent before the first conversation.',
);
$fa_compare_good = array(
    'Shopper intent organized into a structured request.',
    'Context gathered through a guided, trust-first flow.',
    'Prepared for licensed review against available carriers.',
    'Better context before the first conversation.',
);

// §Controlled flow — glass cards (icon / title / body).
$fa_controlled = array(
    array( 'file-text',    'One structured request',      'Shopper details are organized into a single, structured request — context in one place, not scattered across forms.' ),
    array( 'shield-check', 'Prepared for licensed review', 'Each request is prepared for review by licensed agents, agencies, or approved insurance partners against available carriers.' ),
    array( 'lock',         'Shared where appropriate',     'Requests are routed for licensed review where appropriate — not blasted to a long list of companies.' ),
);

// §Flow — connected stepper (icon / title / body).
$fa_flow = array(
    array( 'user',         'Shopper starts a request',     "A consumer answers a few guided questions about the coverage they're exploring, one step at a time." ),
    array( 'file-text',    'Ensurance structures it',      'Their intent is organized into one structured request — context gathered before anyone reaches out.' ),
    array( 'shield-check', 'Licensed review',              'Licensed agents, agencies, or approved partners review the request against the carriers available to them.' ),
    array( 'message',      'A better first conversation',  'You begin with context already in hand, where requests are available — not a cold call from a list.' ),
);

// §Coverage lines — chips (icon / label).
$fa_lines = array(
    array( 'car', 'Auto' ),
    array( 'home', 'Home' ),
    array( 'key', 'Renters' ),
    array( 'heart', 'Life' ),
    array( 'briefcase', 'Business' ),
    array( 'heart-pulse', 'Health' ),
);

// §Standards — participation tiles (icon / title / body).
$fa_standards = array(
    array( 'user',         'Licensed agents and agencies', 'Access is for licensed insurance agents and independent agencies. Professional details are reviewed before access.' ),
    array( 'shield-check', 'Trust-first by design',        'Shopper trust comes first. Requests are reviewed where appropriate — never auctioned to a long list of companies.' ),
    array( 'file-text',    'Request quality over volume',  'The focus is request context and quality, not raw lead counts. There is no volume guarantee.' ),
    array( 'ban',          'No guarantee claims',          'Ensurance does not guarantee close rates, sales, appointments, or exclusivity. Outcomes depend on fit, carrier appetite, and eligibility.' ),
);

// §Agent access — supporting cue rows (icon / label).
$fa_access_cues = array(
    array( 'shield-check', 'Prepared for licensed review' ),
    array( 'file-text',    'Context in one structured request' ),
    array( 'lock',         'Trust-first, never bulk-blasted' ),
);

// §FAQ — mirrors the FAQPage schema above.
$fa_faq = array(
    array( 'What is Ensurance for agents?', 'Ensurance helps licensed agents and agencies review structured shopper insurance requests. Consumer insurance intent is organized through a controlled request flow and prepared for licensed review of available carrier options.' ),
    array( 'How is Ensurance different from buying bulk insurance leads?', 'Ensurance is a structured request system, not a bulk lead seller. Shopper requests arrive with context, organized for licensed review, rather than as raw volume to be auctioned to a long list of buyers.' ),
    array( 'Does Ensurance guarantee request volume?', 'No. Ensurance does not promise or guarantee request volume. The focus is request quality and context, and availability can vary.' ),
    array( 'Does Ensurance guarantee close rates or sales?', 'No. Ensurance does not guarantee close rates, sales, appointments, or exclusivity. Outcomes depend on your review, shopper fit, carrier appetite, and eligibility.' ),
    array( 'What kind of information may be included in a shopper request?', 'A structured shopper request may include the coverage type a shopper is exploring and the context they shared through guided questions, organized so you can review it before the first conversation.' ),
    array( 'Who can request agent access?', 'Licensed insurance agents and independent insurance agencies can request access. Participation is reviewed against professional standards and is not guaranteed.' ),
    array( 'Can agents review multiple coverage types?', 'Yes. Structured shopper requests span common coverage types, including auto, home, renters, life, business, and health, where available.' ),
    array( 'How does Ensurance protect shopper trust?', 'Shopper requests are organized for licensed review where appropriate, not blasted to a long list of companies. A trust-first request flow is the foundation of the agent experience.' ),
    array( 'Does Ensurance provide quotes directly?', 'No. Ensurance does not provide quotes directly. It structures shopper requests so licensed agents, agencies, or approved insurance partners can review available carrier options.' ),
    array( 'How do I get started?', 'Request agent access using the form on this page. Provide your professional details for review against participation standards.' ),
);

get_header( 'home' );
?>
<main id="main" class="page-for-agents">

  <!-- ── Hero (dark, asymmetric) ──────────────────────────────────── -->
  <section class="fa-hero reveal" aria-label="For agents">
    <span class="fa-hero__bar" aria-hidden="true"></span>
    <span class="fa-hero__glow fa-hero__glow--a" aria-hidden="true"></span>
    <span class="fa-hero__glow fa-hero__glow--b" aria-hidden="true"></span>
    <div class="fa-hero__inner">
      <nav class="fa-breadcrumb" aria-label="Breadcrumb">
        <a href="<?php echo $fa_home_url; ?>">Home</a>
        <span aria-hidden="true">/</span>
        <span class="fa-breadcrumb__current">For agents</span>
      </nav>
      <div class="fa-hero__grid">
        <div class="fa-hero__col">
          <span class="fa-kicker fa-kicker--dot"><span class="fa-kicker__dot" aria-hidden="true"></span> For agents</span>
          <h1 class="fa-hero__title">Structured shopper requests, prepared for licensed review.</h1>
          <p class="fa-hero__sub">Ensurance helps organize consumer insurance intent into structured requests that licensed agents, agencies, or approved insurance partners can review against available carrier options.</p>
          <div class="fa-hero__actions">
            <a class="btn btn-primary btn--lg" href="<?php echo $fa_access_id; ?>" data-track="cta_click_agent_access" data-cta-text="Request agent access" data-page-type="for_agents">Request agent access <?php echo wp_kses( ensurance_home_icon( 'arrow-right', 18 ), $ensurance_svg_allowed ); ?></a>
            <a class="fa-hero__ghost" href="<?php echo $fa_hiw_url; ?>">See how shopper requests work</a>
          </div>
          <p class="fa-hero__fine">No volume, close-rate, appointment, exclusivity, or sales guarantees. Request availability and outcomes can vary.</p>
        </div>
        <div class="fa-hero__preview">
          <!-- Structured-request preview card -->
          <div class="fa-request">
            <div class="fa-request__head">
              <span class="fa-request__id">Request &middot; #A-2241</span>
              <span class="badge-status">Prepared for review</span>
            </div>
            <div class="fa-request__body">
              <div class="fa-request__line">
                <span class="icon-box icon-box--accent"><?php echo wp_kses( ensurance_home_icon( 'car', 21 ), $ensurance_svg_allowed ); ?></span>
                <div>
                  <p class="fa-request__type">Auto insurance</p>
                  <p class="fa-request__sub">Guided shopper request</p>
                </div>
              </div>
              <?php foreach ( $fa_request_rows as $i => $row ) : ?>
              <div class="fa-request__row<?php echo ( count( $fa_request_rows ) - 1 === $i ) ? ' fa-request__row--last' : ''; ?>">
                <span class="fa-request__label"><?php echo esc_html( $row[0] ); ?></span>
                <span class="fa-request__value"><?php echo wp_kses( $row[1], array() ); ?></span>
              </div>
              <?php endforeach; ?>
            </div>
            <div class="fa-request__foot">
              <?php echo wp_kses( ensurance_home_icon( 'shield-check', 15 ), $ensurance_svg_allowed ); ?>
              <span>Reviewed by licensed agents — never bulk-blasted</span>
            </div>
          </div>
        </div>
      </div>
    </div>
  </section>

  <!-- ── Why context matters (compare) ────────────────────────────── -->
  <section class="fa-section fa-section--lead reveal" aria-label="Why context matters">
    <div class="fa-head">
      <span class="eyebrow">Why context matters</span>
      <h2>Insurance opportunities should not arrive without context.</h2>
      <p>A better agent experience starts with a better shopper experience — requests organized for review, not raw volume to chase.</p>
    </div>
    <div class="fa-compare">
      <div class="fa-compare__col fa-compare__col--bad">
        <span class="fa-compare__label"><?php echo wp_kses( ensurance_home_icon( 'ban', 14 ), $ensurance_svg_allowed ); ?> Bulk leads</span>
        <h3 class="fa-compare__title">Demand without context</h3>
        <div class="fa-compare__items">
          <?php foreach ( $fa_compare_bad as $item ) : ?>
          <div class="fa-compare__item">
            <span class="fa-compare__mark"><?php echo wp_kses( ensurance_home_icon( 'x', 13 ), $ensurance_svg_allowed ); ?></span>
            <span><?php echo esc_html( $item ); ?></span>
          </div>
          <?php endforeach; ?>
        </div>
      </div>
      <div class="fa-compare__col fa-compare__col--good">
        <span class="fa-compare__label"><?php echo wp_kses( ensurance_home_icon( 'shield-check', 14 ), $ensurance_svg_allowed ); ?> Ensurance</span>
        <h3 class="fa-compare__title">A structured request system</h3>
        <div class="fa-compare__items">
          <?php foreach ( $fa_compare_good as $item ) : ?>
          <div class="fa-compare__item">
            <span class="fa-compare__mark fa-compare__mark--good"><?php echo wp_kses( ensurance_home_icon( 'check', 13 ), $ensurance_svg_allowed ); ?></span>
            <span><?php echo esc_html( $item ); ?></span>
          </div>
          <?php endforeach; ?>
        </div>
      </div>
    </div>
  </section>

  <!-- ── Controlled by design (dark panel) ────────────────────────── -->
  <section class="fa-controlled reveal" aria-label="A controlled request flow">
    <div class="fa-panel">
      <span class="fa-panel__bar" aria-hidden="true"></span>
      <span class="fa-panel__glow" aria-hidden="true"></span>
      <div class="fa-panel__head">
        <span class="fa-kicker">Controlled by design</span>
        <h2 class="fa-panel__title">A controlled request flow built for better review.</h2>
        <p class="fa-panel__sub">A more controlled layer for insurance demand — structured, reviewed, and trust-first.</p>
      </div>
      <div class="fa-panel__cards">
        <?php foreach ( $fa_controlled as $card ) : ?>
        <div class="fa-glass">
          <span class="fa-glass__icon"><?php echo wp_kses( ensurance_home_icon( $card[0], 17 ), $ensurance_svg_allowed ); ?></span>
          <p class="fa-glass__title"><?php echo esc_html( $card[1] ); ?></p>
          <p class="fa-glass__body"><?php echo esc_html( $card[2] ); ?></p>
        </div>
        <?php endforeach; ?>
      </div>
      <div class="fa-panel__links">
        <a class="fa-link fa-link--light" href="<?php echo $fa_hiw_url; ?>">See how shopper requests work <?php echo wp_kses( ensurance_home_icon( 'arrow-right', 16 ), $ensurance_svg_allowed ); ?></a>
        <a class="fa-link fa-link--accent" href="<?php echo $fa_trust_url; ?>">Read Trust and Privacy <?php echo wp_kses( ensurance_home_icon( 'arrow-right', 16 ), $ensurance_svg_allowed ); ?></a>
      </div>
    </div>
  </section>

  <!-- ── From request to review (stepper) ─────────────────────────── -->
  <section class="fa-section reveal" id="flow" aria-label="From request to review">
    <div class="fa-head">
      <span class="eyebrow">From request to review</span>
      <h2>From shopper request to licensed review.</h2>
      <p>A controlled path, the same every time — so you always know what a request is and where it came from.</p>
    </div>
    <div class="fa-steps">
      <?php foreach ( $fa_flow as $i => $step ) : ?>
      <div class="fa-step reveal">
        <div class="fa-step__node">
          <?php echo wp_kses( ensurance_home_icon( $step[0], 26 ), $ensurance_svg_allowed ); ?>
          <span class="fa-step__num"><?php echo esc_html( sprintf( '%02d', $i + 1 ) ); ?></span>
        </div>
        <div class="fa-step__body">
          <h3><?php echo esc_html( $step[1] ); ?></h3>
          <p><?php echo esc_html( $step[2] ); ?></p>
        </div>
      </div>
      <?php endforeach; ?>
    </div>
    <div class="fa-callout" role="note">
      <span class="fa-callout__icon"><?php echo wp_kses( ensurance_home_icon( 'clock', 18 ), $ensurance_svg_allowed ); ?></span>
      <div>
        <p class="fa-callout__title">Availability varies</p>
        <p class="fa-callout__body">Request availability and outcomes can vary. Ensurance does not guarantee request volume, close rates, appointments, exclusivity, or sales.</p>
      </div>
    </div>
  </section>

  <!-- ── Coverage types (chips) ───────────────────────────────────── -->
  <section class="fa-section reveal" aria-label="Coverage types">
    <div class="fa-head">
      <span class="eyebrow">Coverage types</span>
      <h2>Review shopper requests across common coverage types.</h2>
      <p>Structured requests span the lines shoppers ask about most — reviewed where available.</p>
    </div>
    <div class="fa-lines">
      <?php foreach ( $fa_lines as $line ) : ?>
      <div class="fa-line">
        <span class="icon-box icon-box--navy fa-line__icon"><?php echo wp_kses( ensurance_home_icon( $line[0], 22 ), $ensurance_svg_allowed ); ?></span>
        <span class="fa-line__label"><?php echo esc_html( $line[1] ); ?></span>
      </div>
      <?php endforeach; ?>
    </div>
    <div class="fa-section__links">
      <a class="fa-link fa-link--accent" href="<?php echo $fa_coverage_url; ?>">See coverage types <?php echo wp_kses( ensurance_home_icon( 'arrow-right', 16 ), $ensurance_svg_allowed ); ?></a>
    </div>
  </section>

  <!-- ── Participation standards (tiles) ──────────────────────────── -->
  <section class="fa-section fa-section--lead reveal" id="standards" aria-label="Participation standards">
    <div class="fa-head">
      <span class="eyebrow">Participation standards</span>
      <h2>Built for licensed professionals who value trust.</h2>
      <p>Not a bulk-volume promise — a structured request system. Participation is reviewed against professional standards, and access is not guaranteed.</p>
    </div>
    <div class="fa-why">
      <?php foreach ( $fa_standards as $tile ) : ?>
      <div class="card fa-why__tile">
        <span class="icon-box icon-box--navy"><?php echo wp_kses( ensurance_home_icon( $tile[0], 20 ), $ensurance_svg_allowed ); ?></span>
        <div class="fa-why__text">
          <h3><?php echo esc_html( $tile[1] ); ?></h3>
          <p><?php echo esc_html( $tile[2] ); ?></p>
        </div>
      </div>
      <?php endforeach; ?>
    </div>
  </section>

  <!-- ── Agent access (CTA card) ──────────────────────────────────── -->
  <section class="fa-access reveal" id="agent-access" aria-label="Request agent access">
    <div class="fa-access__grid">
      <div class="fa-access__intro">
        <span class="eyebrow">Request access</span>
        <h2>Bring structured shopper requests into your agency workflow.</h2>
        <p class="fa-access__lead">Requests for access are reviewed against participation standards. Requesting access is not a guarantee of access, request volume, or outcomes.</p>
        <div class="fa-access__cues">
          <?php foreach ( $fa_access_cues as $cue ) : ?>
          <div class="fa-access__cue">
            <span class="icon-box icon-box--accent fa-access__cueicon"><?php echo wp_kses( ensurance_home_icon( $cue[0], 15 ), $ensurance_svg_allowed ); ?></span>
            <span><?php echo esc_html( $cue[1] ); ?></span>
          </div>
          <?php endforeach; ?>
        </div>
        <p class="fa-access__contact">Questions first? <a href="<?php echo $fa_contact_url; ?>" data-track="agent_contact_click" data-cta-text="Contact Ensurance" data-page-type="for_agents">Contact Ensurance</a>.</p>
      </div>
      <div class="fa-access__card">
        <span class="fa-access__cardbar" aria-hidden="true"></span>
        <span class="fa-access__cardglow" aria-hidden="true"></span>
        <div class="fa-access__cardbody">
          <span class="fa-access__cardicon"><?php echo wp_kses( ensurance_home_icon( 'shield-check', 24 ), $ensurance_svg_allowed ); ?></span>
          <h3>Request agent access</h3>
          <p>Share your professional details on our access form and our team will review your request against participation standards.</p>
          <a class="btn btn-primary btn--lg fa-access__submit" href="<?php echo esc_url( $fa_access_form_url ); ?>" data-track="agent_access_submit_click" data-cta-text="Request agent access" data-page-type="for_agents">Request agent access <?php echo wp_kses( ensurance_home_icon( 'arrow-right', 18 ), $ensurance_svg_allowed ); ?></a>
          <p class="fa-access__fine"><?php echo wp_kses( ensurance_home_icon( 'lock', 12 ), $ensurance_svg_allowed ); ?> No volume, close-rate, appointment, exclusivity, or sales guarantees.</p>
        </div>
      </div>
    </div>
  </section>

  <!-- ── FAQ ──────────────────────────────────────────────────────── -->
  <section class="faq reveal" id="faq" aria-label="Questions for agents">
    <div class="faq__head">
      <span class="eyebrow">For agents</span>
      <h2>Questions for agents and agencies.</h2>
    </div>
    <div class="faq-list">
      <?php foreach ( $fa_faq as $i => $f ) : ?>
      <details<?php echo 0 === $i ? ' open' : ''; ?>>
        <summary><?php echo esc_html( $f[0] ); ?></summary>
        <p><?php echo esc_html( $f[1] ); ?></p>
      </details>
      <?php endforeach; ?>
    </div>
  </section>

  <!-- ── Final CTA ────────────────────────────────────────────────── -->
  <section class="final-cta fa-final reveal" aria-label="Request agent access">
    <div class="final-card">
      <h2>Review structured shopper requests, prepared for licensed review.</h2>
      <p>A more controlled layer for insurance demand. Request availability and outcomes can vary — there are no volume, close-rate, appointment, exclusivity, or sales guarantees.</p>
      <div class="hero-actions">
        <a class="btn btn-reversed btn--lg" href="<?php echo $fa_access_id; ?>" data-track="cta_click_agent_access" data-cta-text="Request agent access" data-page-type="for_agents">Request agent access <?php echo wp_kses( ensurance_home_icon( 'arrow-right', 18 ), $ensurance_svg_allowed ); ?></a>
        <a class="fa-final__link" href="<?php echo $fa_contact_url; ?>" data-track="agent_contact_click" data-cta-text="Contact Ensurance" data-page-type="for_agents">Contact Ensurance <?php echo wp_kses( ensurance_home_icon( 'arrow-right', 16 ), $ensurance_svg_allowed ); ?></a>
      </div>
    </div>
  </section>

</main>
<?php get_footer( 'home' ); ?>
