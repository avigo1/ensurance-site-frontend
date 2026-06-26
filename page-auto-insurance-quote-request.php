<?php
/**
 * Template Name: Auto Insurance Quote Request (Marketing)
 *
 * /auto-insurance-quote-request — rebuilt to match the "Ensurance Auto Insurance"
 * standalone redesign (Calm Intelligence). This is the primary auto landing page
 * the site funnels into: the homepage nav CTA, mobile sticky CTA, and the main
 * CTAs on /, /how-it-works and /trust-center all point here.
 *
 * Uses the homepage chrome (get_header('home') / get_footer('home')) and shares
 * assets/home.css + assets/home.js for tokens, chrome and base components
 * (buttons, the FAQ accordion, the final CTA, the eyebrow, icon boxes). The
 * page-specific layout — the light hero with the guided-request stage track, the
 * scattered-vs-structured compare, the four request-area cards, the four-step
 * process, the dark "licensed review" panel — lives in
 * assets/auto-insurance-quote-request.css, with the scroll-reveal motion in
 * assets/auto-insurance-quote-request.js. Both are enqueued and isolated from the
 * shared marketing bundle in functions.php
 * (ensurance_auto_insurance_quote_request_assets), scoped to this template only.
 *
 * SEO: title / meta description / canonical / robots are owned by Yoast and
 * emitted through wp_head(); this template outputs none of them. The FAQPage
 * JSON-LD below is shipped here (Yoast does not emit it for this page) and is
 * built from the same $aq_faq array that renders the visible FAQ accordion, so
 * the two can never drift.
 */

/**
 * Inline Lucide icon renderer (shared with the other Calm Intelligence page
 * templates via the function_exists guard). Only one page template renders per
 * request, so this copy carries the full glyph set the Auto Insurance page needs.
 * Paths from Lucide (ISC license).
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
            'clock'        => '<circle cx="12" cy="12" r="10"/><polyline points="12 6 12 12 16 14"/>',
            'car'          => '<path d="M19 17h2c.6 0 1-.4 1-1v-3c0-.9-.7-1.7-1.5-1.9C18.7 10.6 16 10 16 10s-1.3-1.4-2.2-2.3c-.5-.4-1.1-.7-1.8-.7H5c-.6 0-1.1.4-1.4.9l-1.4 2.9A3.7 3.7 0 0 0 2 12v4c0 .6.4 1 1 1h2"/><circle cx="7" cy="17" r="2"/><path d="M9 17h6"/><circle cx="17" cy="17" r="2"/>',
            'home'         => '<path d="m3 9 9-7 9 7v11a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2z"/><path d="M9 22V12h6v10"/>',
            'layers'       => '<path d="M12.83 2.18a2 2 0 0 0-1.66 0L2.6 6.08a1 1 0 0 0 0 1.83l8.58 3.91a2 2 0 0 0 1.66 0l8.58-3.9a1 1 0 0 0 0-1.83Z"/><path d="M2 12a1 1 0 0 0 .58.91l8.6 3.91a2 2 0 0 0 1.65 0l8.58-3.9A1 1 0 0 0 22 12"/><path d="M2 17a1 1 0 0 0 .58.91l8.6 3.91a2 2 0 0 0 1.65 0l8.58-3.9A1 1 0 0 0 22 17"/>',
            'sparkles'     => '<path d="M9.937 15.5A2 2 0 0 0 8.5 14.063l-6.135-1.582a.5.5 0 0 1 0-.962L8.5 9.936A2 2 0 0 0 9.937 8.5l1.582-6.135a.5.5 0 0 1 .962 0L14.063 8.5A2 2 0 0 0 15.5 9.937l6.135 1.581a.5.5 0 0 1 0 .964L15.5 14.063a2 2 0 0 0-1.437 1.437l-1.582 6.135a.5.5 0 0 1-.962 0z"/><path d="M20 3v4"/><path d="M22 5h-4"/><path d="M4 17v2"/><path d="M5 18H3"/>',
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
$aq_hiw_url      = esc_url( home_url( '/how-it-works' ) );
$aq_coverage_url = esc_url( home_url( '/coverage' ) );
$aq_trust_url    = esc_url( home_url( '/trust-center' ) );
// "Start auto request" routes to the existing auto insurance quote page.
// Swap if the intake URL changes.
$aq_start_url    = esc_url( home_url( '/auto-insurance-quote' ) );

// §Hero — guided-request stage track (icon / number / title / subtitle).
$aq_stages = array(
    array( 'file-text',    '01', 'Guided questions', 'Vehicles & drivers' ),
    array( 'layers',       '02', 'One request',      'Structured & organized' ),
    array( 'shield-check', '03', 'Licensed review',  'A person, not a robot' ),
    array( 'sparkles',     '04', 'Quote options',    'Where available' ),
);

// §A clearer way — scattered vs. structured compare.
$aq_compare_bad = array(
    'Re-entering vehicle and driver details across separate sites.',
    'Personal information requested before anything is explained.',
    'Details blasted to a long list of companies.',
    'Pressure to decide before you understand your options.',
);
$aq_compare_good = array(
    'Answer guided questions about your vehicles and drivers once.',
    'A structured request, organized before review.',
    'Licensed agents, agencies, or approved partners review available carriers.',
    'Auto quote options where available — at your own pace.',
);

// §What your auto request may include — cards (icon / title / body / points).
$aq_include = array(
    array( 'car',          'Vehicle details',         "The cars on your policy and how they're used.", array( 'Year, make, and model', 'Primary use and annual mileage', 'Multiple vehicles on one request' ) ),
    array( 'user',         'Driver details',          'Who drives, and a little about their history.',  array( 'Drivers on the policy', 'Licensing and driving history', 'Teen and added drivers' ) ),
    array( 'shield-check', 'Coverage needs',          "The protection you're looking for.",            array( 'Liability limits', 'Collision and comprehensive', 'Add-ons and bundling options' ) ),
    array( 'home',         'Location and eligibility', 'Where the vehicle lives and is parked.',        array( 'Garaging address', 'Where the car is kept overnight', 'Availability varies by location' ) ),
);

// §How the process works — steps (icon / title / body).
$aq_steps = array(
    array( 'file-text',    'Answer a few guided questions',                'Tell Ensurance about your vehicles and drivers. Minimal typing, one step at a time, all in one place.' ),
    array( 'layers',       'Your details become one request',              'Ensurance structures everything into a single, organized auto request — so nothing important is missing or repeated.' ),
    array( 'shield-check', 'Licensed professionals review available carriers', 'Licensed agents, agencies, or approved insurance partners review your request against the carriers available to them.' ),
    array( 'sparkles',     'Receive auto quote options where available',   'Where coverage and eligibility allow, you receive auto quote options and a clearer next step — no pressure to decide.' ),
);

// §Why licensed review matters — dark-panel glass cards (icon / title / body).
$aq_review = array(
    array( 'user',      'A licensed professional, not a robot', 'Licensed agents, agencies, or approved partners review available carriers — a person decides what may fit your situation.' ),
    array( 'file-text', 'One organized request',                'Your vehicle and driver details stay together as a single request, so nothing important is missing or repeated.' ),
    array( 'lock',      'Shared only where appropriate',        'Your structured auto request is reviewed where appropriate — not auctioned to a long list of companies.' ),
);

// §FAQ — also feeds the FAQPage JSON-LD below.
$aq_faq = array(
    array( 'What is a guided auto insurance request?', 'A guided auto insurance request is a short, structured set of questions about your vehicles, drivers, and coverage needs. Ensurance organizes your answers into one clear request so licensed agents, agencies, or approved insurance partners can review available carriers — instead of you re-entering the same details across separate forms.' ),
    array( 'Can one auto request help me access multiple carrier options?', "Often, yes. A licensed professional can review multiple available carriers from your single structured auto request, so you don't have to repeat the same vehicle and driver details across separate sites." ),
    array( 'What details may be needed for an auto insurance request?', "Typically your vehicle details, the drivers on the policy, the coverage you're looking for, and your location for eligibility. You don't need to have everything ready before you begin — the guided questions handle it one step at a time." ),
    array( 'Who reviews my auto insurance request?', 'Licensed agents, agencies, or approved insurance partners review your structured auto request. A licensed professional — not an automated list — decides which available carrier options may fit.' ),
    array( 'Are auto quote options guaranteed?', "No. Availability, eligibility, carrier participation, and licensed professional review determine which auto quote options may be available. Ensurance doesn't promise instant quotes or guaranteed savings." ),
    array( 'What happens after I start?', 'You answer a few guided questions about your vehicles and drivers. Ensurance structures your details and moves them into licensed review, where you may receive auto quote options where available — at your own pace, with no pressure to decide.' ),
);

// --- Per-page FAQPage schema — built from $aq_faq so it mirrors the visible FAQ. ---
add_action( 'wp_head', function () use ( $aq_faq ) {
    $entities = array();
    foreach ( $aq_faq as $f ) {
        $entities[] = array(
            '@type'          => 'Question',
            'name'           => $f[0],
            'acceptedAnswer' => array( '@type' => 'Answer', 'text' => $f[1] ),
        );
    }
    $schema = array(
        '@context'   => 'https://schema.org',
        '@type'      => 'FAQPage',
        '@id'        => home_url( '/auto-insurance-quote-request' ) . '#faq',
        'mainEntity' => $entities,
    );
    $flags = JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE;
    echo '<script type="application/ld+json">' . wp_json_encode( $schema, $flags ) . '</script>' . "\n";
}, 20 );

get_header( 'home' );
?>
<main id="main" class="page-auto-insurance">

  <!-- ── Hero (light, guided-request stage track) ─────────────────── -->
  <section class="aq-hero reveal" aria-label="Auto insurance">
    <span class="aq-hero__glow aq-hero__glow--a" aria-hidden="true"></span>
    <span class="aq-hero__glow aq-hero__glow--b" aria-hidden="true"></span>
    <div class="aq-hero__inner">
      <span class="aq-pill"><?php echo wp_kses( ensurance_home_icon( 'car', 14 ), $ensurance_svg_allowed ); ?> Auto insurance</span>
      <h1 class="aq-hero__title">Find your way to the <span class="aq-accent">right auto coverage</span>.</h1>
      <p class="aq-hero__sub">Answer a few guided questions once. Ensurance organizes your vehicle and driver details so licensed agents can review available carriers and surface quote options where available.</p>
      <div class="aq-hero__actions">
        <a class="btn btn-primary btn--lg" href="<?php echo $aq_start_url; ?>" data-track="cta_click_start_auto_quote_request" data-cta-text="Start auto request" data-page-type="auto_insurance">Start auto request <?php echo wp_kses( ensurance_home_icon( 'arrow-right', 18 ), $ensurance_svg_allowed ); ?></a>
        <a class="btn btn-ghost btn--lg" href="<?php echo $aq_hiw_url; ?>" data-track="hero_how_it_works_click" data-cta-text="How Ensurance works" data-page-type="auto_insurance">How Ensurance works</a>
      </div>
      <div class="aq-track">
        <span class="aq-track__line" aria-hidden="true"></span>
        <?php foreach ( $aq_stages as $i => $stage ) : ?>
        <?php $is_last = ( count( $aq_stages ) - 1 === $i ); ?>
        <div class="aq-track__item">
          <span class="aq-track__node<?php echo $is_last ? ' aq-track__node--last' : ''; ?>"><?php echo wp_kses( ensurance_home_icon( $stage[0], 22 ), $ensurance_svg_allowed ); ?></span>
          <span class="aq-track__num"><?php echo esc_html( $stage[1] ); ?></span>
          <p class="aq-track__title"><?php echo esc_html( $stage[2] ); ?></p>
          <p class="aq-track__sub"><?php echo esc_html( $stage[3] ); ?></p>
        </div>
        <?php endforeach; ?>
      </div>
    </div>
  </section>

  <!-- ── A clearer way (scattered vs. structured compare) ─────────── -->
  <section class="aq-section aq-section--lead reveal" aria-label="A clearer way to begin">
    <div class="aq-head">
      <span class="eyebrow">Why start here</span>
      <h2>A clearer way to begin auto insurance shopping.</h2>
      <p>Auto insurance shopping shouldn't start with the same details typed into form after form. Ensurance structures one auto request, prepared for licensed review of available carrier options.</p>
    </div>
    <div class="aq-compare">
      <div class="aq-compare__col aq-compare__col--bad">
        <span class="aq-compare__label"><?php echo wp_kses( ensurance_home_icon( 'ban', 14 ), $ensurance_svg_allowed ); ?> Scattered</span>
        <h3 class="aq-compare__title">Auto shopping the hard way</h3>
        <div class="aq-compare__items">
          <?php foreach ( $aq_compare_bad as $item ) : ?>
          <div class="aq-compare__item">
            <span class="aq-compare__mark"><?php echo wp_kses( ensurance_home_icon( 'x', 13 ), $ensurance_svg_allowed ); ?></span>
            <span><?php echo esc_html( $item ); ?></span>
          </div>
          <?php endforeach; ?>
        </div>
      </div>
      <div class="aq-compare__col aq-compare__col--good">
        <span class="aq-compare__label"><?php echo wp_kses( ensurance_home_icon( 'shield-check', 14 ), $ensurance_svg_allowed ); ?> Ensurance</span>
        <h3 class="aq-compare__title">One guided auto request</h3>
        <div class="aq-compare__items">
          <?php foreach ( $aq_compare_good as $item ) : ?>
          <div class="aq-compare__item">
            <span class="aq-compare__mark aq-compare__mark--good"><?php echo wp_kses( ensurance_home_icon( 'check', 13 ), $ensurance_svg_allowed ); ?></span>
            <span><?php echo esc_html( $item ); ?></span>
          </div>
          <?php endforeach; ?>
        </div>
      </div>
    </div>
  </section>

  <!-- ── What your auto request may include (cards) ───────────────── -->
  <section class="aq-section reveal" aria-label="What your auto request may include">
    <div class="aq-head">
      <span class="eyebrow">What's covered</span>
      <h2>What your auto request may include.</h2>
      <p>A few guided questions, grouped into four simple areas. You don't need everything ready to begin — the request fills in one step at a time.</p>
    </div>
    <div class="aq-include">
      <?php foreach ( $aq_include as $card ) : ?>
      <div class="aq-include__card card">
        <span class="icon-box icon-box--navy"><?php echo wp_kses( ensurance_home_icon( $card[0], 22 ), $ensurance_svg_allowed ); ?></span>
        <div class="aq-include__text">
          <h3><?php echo esc_html( $card[1] ); ?></h3>
          <p><?php echo esc_html( $card[2] ); ?></p>
        </div>
        <div class="aq-include__points">
          <?php foreach ( $card[3] as $point ) : ?>
          <div class="aq-include__point">
            <span class="aq-include__check"><?php echo wp_kses( ensurance_home_icon( 'check', 12 ), $ensurance_svg_allowed ); ?></span>
            <span><?php echo esc_html( $point ); ?></span>
          </div>
          <?php endforeach; ?>
        </div>
      </div>
      <?php endforeach; ?>
    </div>
    <div class="aq-callout aq-callout--neutral" role="note">
      <span class="aq-callout__icon"><?php echo wp_kses( ensurance_home_icon( 'user', 18 ), $ensurance_svg_allowed ); ?></span>
      <div>
        <p class="aq-callout__title">You don't need to know every detail before you begin.</p>
        <p class="aq-callout__body">Answer a few guided questions about your vehicles and drivers. A licensed professional reviews the details with you — you don't have to know exactly what coverage you need to start.</p>
      </div>
    </div>
  </section>

  <!-- ── How the process works (steps) ────────────────────────────── -->
  <section class="aq-section reveal" id="process" aria-label="How the auto insurance request process works">
    <div class="aq-head">
      <span class="eyebrow">How it works</span>
      <h2>How the auto insurance request process works.</h2>
      <p>Four clear steps, so you always know where you are and what happens next.</p>
    </div>
    <div class="aq-steps">
      <?php foreach ( $aq_steps as $i => $step ) : ?>
      <div class="aq-step card">
        <div class="aq-step__top">
          <span class="icon-box icon-box--navy"><?php echo wp_kses( ensurance_home_icon( $step[0], 19 ), $ensurance_svg_allowed ); ?></span>
          <span class="aq-step__num"><?php echo esc_html( sprintf( '%02d', $i + 1 ) ); ?></span>
        </div>
        <h3><?php echo esc_html( $step[1] ); ?></h3>
        <p><?php echo esc_html( $step[2] ); ?></p>
      </div>
      <?php endforeach; ?>
    </div>
    <div class="aq-callout aq-callout--warn" role="note">
      <span class="aq-callout__icon"><?php echo wp_kses( ensurance_home_icon( 'clock', 18 ), $ensurance_svg_allowed ); ?></span>
      <div>
        <p class="aq-callout__title">Availability varies</p>
        <p class="aq-callout__body">Availability, eligibility, carrier participation, and licensed professional review determine which auto quote options may be available — and quotes are never guaranteed.</p>
      </div>
    </div>
  </section>

  <!-- ── Why licensed review matters (dark panel) ─────────────────── -->
  <section class="aq-review reveal" aria-label="Why licensed review matters">
    <div class="aq-panel">
      <span class="aq-panel__bar" aria-hidden="true"></span>
      <span class="aq-panel__glow" aria-hidden="true"></span>
      <div class="aq-panel__head">
        <span class="aq-kicker">Reviewed by a person</span>
        <h2 class="aq-panel__title">Why licensed review matters.</h2>
        <p class="aq-panel__sub">Real auto coverage takes a real conversation — not an automated price list.</p>
      </div>
      <div class="aq-panel__cards">
        <?php foreach ( $aq_review as $card ) : ?>
        <div class="aq-glass">
          <span class="aq-glass__icon"><?php echo wp_kses( ensurance_home_icon( $card[0], 17 ), $ensurance_svg_allowed ); ?></span>
          <p class="aq-glass__title"><?php echo esc_html( $card[1] ); ?></p>
          <p class="aq-glass__body"><?php echo esc_html( $card[2] ); ?></p>
        </div>
        <?php endforeach; ?>
      </div>
      <div class="aq-panel__links">
        <a class="aq-link aq-link--light" href="<?php echo $aq_hiw_url; ?>">How Ensurance works <?php echo wp_kses( ensurance_home_icon( 'arrow-right', 16 ), $ensurance_svg_allowed ); ?></a>
        <a class="aq-link aq-link--accent" href="<?php echo $aq_trust_url; ?>">Read Trust and privacy <?php echo wp_kses( ensurance_home_icon( 'arrow-right', 16 ), $ensurance_svg_allowed ); ?></a>
        <a class="aq-link aq-link--muted" href="<?php echo $aq_coverage_url; ?>">Coverage types <?php echo wp_kses( ensurance_home_icon( 'arrow-right', 16 ), $ensurance_svg_allowed ); ?></a>
      </div>
    </div>
  </section>

  <!-- ── FAQ (reuses .faq / .faq-list from home.css) ──────────────── -->
  <section class="faq reveal" id="faq" aria-label="Questions before starting an auto request">
    <div class="faq__head">
      <span class="eyebrow">Before you start</span>
      <h2>Questions before starting an auto request.</h2>
    </div>
    <div class="faq-list">
      <?php foreach ( $aq_faq as $i => $f ) : ?>
      <details<?php echo 0 === $i ? ' open' : ''; ?>>
        <summary><?php echo esc_html( $f[0] ); ?></summary>
        <p><?php echo esc_html( $f[1] ); ?></p>
      </details>
      <?php endforeach; ?>
    </div>
  </section>

  <!-- ── Final CTA (reuses .final-cta / .final-card from home.css) ─── -->
  <section class="final-cta aq-final reveal" aria-label="Start your auto insurance request">
    <div class="final-card">
      <h2>Start your auto insurance request.</h2>
      <p>Begin one guided auto request — structured for licensed review of available carrier options, with quote options where available.</p>
      <div class="hero-actions">
        <a class="btn btn-reversed btn--lg" href="<?php echo $aq_start_url; ?>" data-track="cta_click_start_auto_quote_request" data-cta-text="Start auto request" data-page-type="auto_insurance">Start auto request <?php echo wp_kses( ensurance_home_icon( 'arrow-right', 18 ), $ensurance_svg_allowed ); ?></a>
        <a class="btn btn--lg aq-ghost-dark" href="<?php echo $aq_hiw_url; ?>" data-track="hero_how_it_works_click" data-cta-text="How Ensurance works" data-page-type="auto_insurance">How Ensurance works</a>
      </div>
    </div>
  </section>

</main>
<?php get_footer( 'home' ); ?>
