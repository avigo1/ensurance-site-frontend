<?php
/**
 * Template Name: Coverage Types (Marketing)
 *
 * /coverage — rebuilt to match the "Calm Intelligence" standalone redesign
 * ("Ensurance Coverage Types"). Uses the homepage chrome
 * (get_header('home') / get_footer('home')) and shares assets/home.css +
 * assets/home.js for tokens, chrome and base components. Page-specific
 * sections (light hero, the tabbed coverage picker, the scattered-vs-Ensurance
 * compare, the dark "controlled flow" panel) and the scroll-reveal + tab
 * interactions live in assets/coverage.css + assets/coverage.js, loaded and
 * isolated from the shared marketing bundle in functions.php.
 *
 * SEO: title / meta description / canonical / robots are owned by Yoast and
 * emitted through wp_head(); this template outputs none of them. The structured
 * data below (WebPage / BreadcrumbList / ItemList / FAQPage) is shipped here to
 * mirror the visible content, and is built from the same arrays that render the
 * coverage picker and FAQ accordion so the two never drift.
 */

// ── Coverage request paths — single source for the picker + ItemList JSON-LD ──
$cov_paths = array(
    array(
        'key'     => 'auto',
        'icon'    => 'car',
        'title'   => 'Auto insurance',
        'short'   => 'Auto',
        'body'    => 'Start a guided auto insurance quote request for your vehicles, drivers, timing, and coverage needs.',
        'points'  => array( 'Vehicles and drivers on your policy', 'Liability, collision, and comprehensive coverage', 'Multi-car, teen drivers, and bundling details' ),
        'aside'   => 'Answer a few guided questions and organize the details needed to move toward auto quote options where available.',
        'href'    => '/auto-insurance-quote',
        'anchor'  => 'Start auto request',
        'popular' => true,
    ),
    array(
        'key'     => 'home',
        'icon'    => 'home',
        'title'   => 'Home insurance',
        'short'   => 'Home',
        'body'    => 'Start a guided home insurance request for the property you own and organize the details needed for review.',
        'points'  => array( 'The home you own and its structure', 'Belongings and personal liability', 'Dwelling, contents, and available add-ons' ),
        'aside'   => 'Answer guided questions so your home insurance request can be reviewed with clearer context where available.',
        'href'    => '/homeowners-insurance-quote',
        'anchor'  => 'Start home request',
        'popular' => false,
    ),
    array(
        'key'     => 'renters',
        'icon'    => 'key',
        'title'   => 'Renters insurance',
        'short'   => 'Renters',
        'body'    => 'Start a guided renters insurance request and organize the details for your rented home.',
        'points'  => array( 'Belongings in your rented home', 'Personal liability coverage', 'Renter-focused coverage needs' ),
        'aside'   => 'Answer guided questions and organize renters insurance details with less repeated entry.',
        'href'    => '/renters-insurance-quote',
        'anchor'  => 'Start renters request',
        'popular' => false,
    ),
    array(
        'key'     => 'life',
        'icon'    => 'heart',
        'title'   => 'Life insurance',
        'short'   => 'Life',
        'body'    => 'Start a guided life insurance request and share basic details so your needs can be reviewed with more context.',
        'points'  => array( 'Term and permanent life insurance needs', 'Coverage for the people who depend on you', 'Review with a licensed professional where available' ),
        'aside'   => 'Answer guided questions so your life insurance request can move forward with a clearer next step.',
        'href'    => '/life-insurance-quote',
        'anchor'  => 'Start life request',
        'popular' => false,
    ),
    array(
        'key'     => 'business',
        'icon'    => 'briefcase',
        'title'   => 'Business insurance',
        'short'   => 'Business',
        'body'    => 'Start a guided business insurance request and organize details about the coverage your work may need.',
        'points'  => array( 'Liability for your operations', 'Property, equipment, and continuity needs', 'Industry and business details for review' ),
        'aside'   => 'Answer guided questions so your business insurance request can be reviewed with clearer context.',
        'href'    => '/commercial-insurance-quote',
        'anchor'  => 'Start business request',
        'popular' => false,
    ),
    array(
        'key'     => 'health',
        'icon'    => 'heart-pulse',
        'title'   => 'Health insurance',
        'short'   => 'Health',
        'body'    => 'Start a guided health insurance request and create a clearer path toward available help where supported.',
        'points'  => array( 'Individual and family needs where supported', 'Guided questions about plan basics', 'Review by a licensed professional where available' ),
        'aside'   => 'Answer guided questions and move toward available health insurance help where supported.',
        'href'    => '/health-insurance-quote-request/',
        'anchor'  => 'Start health request',
        'popular' => false,
    ),
);

// ── FAQ — single source for the accordion + FAQPage JSON-LD ──
$cov_faq = array(
    array( 'q' => 'What insurance coverage types can I start a request for?', 'a' => 'You can start a guided quote request for auto, home, renters, life, business, or health insurance. Each path helps organize your details so your request can move toward licensed review where available.' ),
    array( 'q' => 'Can one request help me move toward quote options?', 'a' => 'Yes, where available. Ensurance helps organize your request so a licensed agent, agency, or approved partner can review it with clearer context and help identify available next steps.' ),
    array( 'q' => 'Do I need to know exactly what coverage I need?', 'a' => 'No. Choose the insurance type that fits your situation, then answer guided questions one step at a time. You do not need to know every coverage detail before you begin.' ),
    array( 'q' => 'Who reviews my coverage request?', 'a' => 'Where available, licensed agents, agencies, or approved partners may review your organized request and help identify quote options or a clearer next step.' ),
    array( 'q' => 'Will I receive quotes for every coverage type?', 'a' => 'Not always. Quote options depend on location, coverage type, eligibility, carrier participation, and licensed review.' ),
    array( 'q' => 'How is Ensurance different from quote-comparison sites?', 'a' => 'Ensurance is not built around showing a noisy grid of quotes or sending your information broadly. Ensurance helps you start one guided quote request, organize your details, and move toward quote options with more clarity and control.' ),
    array( 'q' => 'Will my information be sent everywhere?', 'a' => 'No. Your request is handled through a more controlled process designed to reduce unnecessary exposure, broad sharing, and unwanted contact.' ),
    array( 'q' => 'What happens after I choose a coverage type?', 'a' => 'You begin one guided request for that coverage type. Ensurance organizes your details and helps move your request toward licensed review or a clearer next step where available.' ),
);

// ── Structured data (WebPage / BreadcrumbList / ItemList / FAQPage) ──
// Mirrors the visible content; built from the arrays above so it never drifts.
add_action( 'wp_head', function () use ( $cov_paths, $cov_faq ) {
    $page_title = 'Coverage Types | Guided Insurance Requests | Ensurance';
    $page_desc  = 'Choose a guided insurance request path for auto, home, renters, life, business, or health insurance. Ensurance structures your request for licensed review of available carrier options.';

    $item_list = array();
    foreach ( $cov_paths as $i => $c ) {
        $item_list[] = array(
            '@type'    => 'ListItem',
            'position' => $i + 1,
            'name'     => $c['title'],
            'url'      => home_url( $c['href'] ),
        );
    }

    $faq_entities = array();
    foreach ( $cov_faq as $item ) {
        $faq_entities[] = array(
            '@type'          => 'Question',
            'name'           => $item['q'],
            'acceptedAnswer' => array( '@type' => 'Answer', 'text' => $item['a'] ),
        );
    }

    $graph = array(
        '@context' => 'https://schema.org',
        '@graph'   => array(
            array(
                '@type'       => 'WebPage',
                '@id'         => home_url( '/coverage' ),
                'url'         => home_url( '/coverage' ),
                'name'        => $page_title,
                'description' => $page_desc,
            ),
            array(
                '@type'           => 'BreadcrumbList',
                'itemListElement' => array(
                    array( '@type' => 'ListItem', 'position' => 1, 'name' => 'Home', 'item' => home_url( '/' ) ),
                    array( '@type' => 'ListItem', 'position' => 2, 'name' => 'Coverage types', 'item' => home_url( '/coverage' ) ),
                ),
            ),
            array(
                '@type'           => 'ItemList',
                'name'            => 'Ensurance coverage types',
                'itemListElement' => $item_list,
            ),
            array(
                '@type'      => 'FAQPage',
                'mainEntity' => $faq_entities,
            ),
        ),
    );

    $flags = JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE;
    echo '<script type="application/ld+json">' . wp_json_encode( $graph, $flags ) . "</script>\n";
}, 20 );

/**
 * Inline Lucide icon renderer (shared with page-home.php / page-how-it-works.php
 * via function_exists guard). This copy carries the full set the coverage page
 * needs, including the coverage-type glyphs. Paths from Lucide (ISC license).
 */
if ( ! function_exists( 'ensurance_home_icon' ) ) {
    function ensurance_home_icon( $name, $size = 20 ) {
        $icons = array(
            'shield-check' => '<path d="M20 13c0 5-3.5 7.5-7.66 8.95a1 1 0 0 1-.67-.01C7.5 20.5 4 18 4 13V6a1 1 0 0 1 1-1c2 0 4.5-1.2 6.24-2.72a1.17 1.17 0 0 1 1.52 0C14.51 3.81 17 5 19 5a1 1 0 0 1 1 1z"/><path d="m9 12 2 2 4-4"/>',
            'user'         => '<circle cx="12" cy="8" r="5"/><path d="M20 21a8 8 0 0 0-16 0"/>',
            'check'        => '<path d="M20 6 9 17l-5-5"/>',
            'x'            => '<path d="M18 6 6 18"/><path d="m6 6 12 12"/>',
            'arrow-right'  => '<path d="M5 12h14"/><path d="m12 5 7 7-7 7"/>',
            'clock'        => '<circle cx="12" cy="12" r="10"/><path d="M12 6v6l4 2"/>',
            'message'      => '<path d="M7.9 20A9 9 0 1 0 4 16.1L2 22Z"/>',
            'ban'          => '<circle cx="12" cy="12" r="10"/><path d="m4.9 4.9 14.2 14.2"/>',
            'lock'         => '<rect width="18" height="11" x="3" y="11" rx="2"/><path d="M7 11V7a5 5 0 0 1 10 0v4"/>',
            'layers'       => '<path d="M12.83 2.18a2 2 0 0 0-1.66 0L2.6 6.08a1 1 0 0 0 0 1.83l8.58 3.91a2 2 0 0 0 1.66 0l8.58-3.9a1 1 0 0 0 0-1.83Z"/><path d="m22 17.65-9.17 4.16a2 2 0 0 1-1.66 0L2 17.65"/><path d="m22 12.65-9.17 4.16a2 2 0 0 1-1.66 0L2 12.65"/>',
            'file-text'    => '<path d="M15 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V7Z"/><path d="M14 2v4a2 2 0 0 0 2 2h4"/><path d="M10 9H8"/><path d="M16 13H8"/><path d="M16 17H8"/>',
            'sparkles'     => '<path d="M9.937 15.5A2 2 0 0 0 8.5 14.063l-6.135-1.582a.5.5 0 0 1 0-.962L8.5 9.936A2 2 0 0 0 9.937 8.5l1.582-6.135a.5.5 0 0 1 .963 0L14.063 8.5A2 2 0 0 0 15.5 9.937l6.135 1.581a.5.5 0 0 1 0 .964L15.5 14.063a2 2 0 0 0-1.437 1.437l-1.582 6.135a.5.5 0 0 1-.963 0z"/><path d="M20 3v4"/><path d="M22 5h-4"/>',
            'car'          => '<path d="M19 17h2c.6 0 1-.4 1-1v-3c0-.9-.7-1.7-1.5-1.9C18.7 10.6 16 10 16 10s-1.3-1.4-2.2-2.3c-.5-.4-1.1-.7-1.8-.7H5c-.6 0-1.1.4-1.4.9l-1.4 2.9A3.7 3.7 0 0 0 2 12v4c0 .6.4 1 1 1h2"/><circle cx="7" cy="17" r="2"/><path d="M9 17h6"/><circle cx="17" cy="17" r="2"/>',
            'home'         => '<path d="m3 9 9-7 9 7v11a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2z"/><path d="M9 22V12h6v10"/>',
            'key'          => '<path d="M2.586 17.414A2 2 0 0 0 2 18.828V21a1 1 0 0 0 1 1h3a1 1 0 0 0 1-1v-1a1 1 0 0 1 1-1h1a1 1 0 0 0 1-1v-1a1 1 0 0 1 1-1h.172a2 2 0 0 0 1.414-.586l.814-.814a6.5 6.5 0 1 0-4-4z"/><circle cx="16.5" cy="7.5" r="1.5"/>',
            'heart'        => '<path d="M19 14c1.49-1.46 3-3.21 3-5.5A5.5 5.5 0 0 0 16.5 3c-1.76 0-3 .5-4.5 2-1.5-1.5-2.74-2-4.5-2A5.5 5.5 0 0 0 2 8.5c0 2.3 1.5 4.05 3 5.5l7 7Z"/>',
            'briefcase'    => '<path d="M16 20V4a2 2 0 0 0-2-2h-4a2 2 0 0 0-2 2v16"/><rect width="20" height="14" x="2" y="6" rx="2"/>',
            'heart-pulse'  => '<path d="M19 14c1.49-1.46 3-3.21 3-5.5A5.5 5.5 0 0 0 16.5 3c-1.76 0-3 .5-4.5 2-1.5-1.5-2.74-2-4.5-2A5.5 5.5 0 0 0 2 8.5c0 2.3 1.5 4.05 3 5.5l7 7Z"/><path d="M3.22 12H9.5l.5-1 2 4.5 2-7 1.5 3.5h5.27"/>',
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

// Hero trust cues — verbatim from the design's HERO_TRUST array.
$cov_hero_trust = array(
    array( 'icon' => 'layers',       'label' => 'Six guided request paths' ),
    array( 'icon' => 'file-text',    'label' => 'One organized request' ),
    array( 'icon' => 'shield-check', 'label' => 'Licensed review where available' ),
    array( 'icon' => 'sparkles',     'label' => 'Move toward quote options' ),
);

// "One request path" compare columns.
$cov_compare_bad = array(
    'Re-entering the same details across separate forms.',
    'Personal information requested before the process is clear.',
    'Details sent broadly without clear context.',
    'Pressure to decide before you understand your next step.',
);
$cov_compare_good = array(
    'Choose one coverage type and answer guided questions in one place.',
    'One organized request prepared before review.',
    'Licensed agents, agencies, or approved partners may review your request where available.',
    'Move toward quote options or a clearer next step where available.',
);

// "Controlled by design" dark-panel cards.
$cov_flow_cards = array(
    array( 'icon' => 'lock',      'title' => 'Handled through a controlled process', 'body' => 'Your request is organized before review and designed to reduce unnecessary exposure and unwanted contact.' ),
    array( 'icon' => 'file-text', 'title' => 'One organized request',                'body' => 'Everything you share stays together in one request, helping reduce repeated entry and confusion.' ),
    array( 'icon' => 'user',      'title' => 'Reviewed where available',             'body' => 'Licensed agents, agencies, or approved partners may review your request and help identify available next steps.' ),
);

// Process steps — verbatim from the design's STEPS array.
$cov_steps = array(
    array( 'icon' => 'layers',       'title' => 'Choose your coverage type',                            'body' => 'Pick the insurance type that fits your situation — auto, home, renters, life, business, or health.' ),
    array( 'icon' => 'file-text',    'title' => 'Answer a few guided questions',                         'body' => 'Ensurance organizes your details into one clear request. Minimal typing, one step at a time.' ),
    array( 'icon' => 'shield-check', 'title' => 'Licensed review happens where available',               'body' => 'Licensed agents, agencies, or approved partners may review your request and help identify available next steps.' ),
    array( 'icon' => 'sparkles',     'title' => 'Move toward quote options',                             'body' => 'Where available, you can receive quote options or a clearer next step without pressure to decide before you are ready.' ),
);

$paths_anchor = '#paths';

get_header( 'home' );
?>
<main id="main" class="page-coverage">

  <!-- ── Hero (light, centered) ───────────────────────────────────── -->
  <section class="cov-hero" aria-label="Coverage types">
    <span class="cov-hero__glow cov-hero__glow--a" aria-hidden="true"></span>
    <span class="cov-hero__glow cov-hero__glow--b" aria-hidden="true"></span>
    <div class="cov-hero__inner">
      <p class="eyebrow">Coverage types</p>
      <h1 class="cov-hero__title">Choose your coverage type <span class="accent">and start one guided request.</span></h1>
      <p class="cov-hero__sub">Ensurance helps shoppers choose a guided insurance quote request path for auto, home, renters, life, business, or health insurance, organized for licensed review where available.</p>
      <div class="hero-actions">
        <a class="btn btn-primary btn--lg" href="<?php echo esc_attr( $paths_anchor ); ?>" data-track="cta_click_choose_coverage" data-cta-text="Choose your coverage type" data-page-type="coverage">Choose your coverage type <?php echo wp_kses( ensurance_home_icon( 'arrow-right', 18 ), $ensurance_svg_allowed ); ?></a>
        <a class="btn btn-ghost btn--lg" href="<?php echo esc_url( home_url( '/how-it-works' ) ); ?>">How Ensurance works</a>
      </div>
      <div class="cov-hero__trust">
        <?php foreach ( $cov_hero_trust as $cue ) : ?>
        <span class="trust-cue"><?php echo wp_kses( ensurance_home_icon( $cue['icon'], 18 ), $ensurance_svg_allowed ); ?> <?php echo esc_html( $cue['label'] ); ?></span>
        <?php endforeach; ?>
      </div>
    </div>
  </section>

  <!-- ── Coverage request paths (tabbed picker) ───────────────────── -->
  <section class="section cov-section" id="paths" aria-label="Coverage request paths">
    <div class="cov-head">
      <p class="eyebrow">Explore request paths</p>
      <h2>Start with the coverage type that fits your situation.</h2>
      <p class="cov-lead">Choose the coverage type that fits your situation, see what details the request may cover, and start one guided request with more clarity and control.</p>
    </div>

    <div class="cov-picker">
      <div class="cov-tabs" role="tablist" aria-label="Coverage types">
        <?php foreach ( $cov_paths as $i => $c ) : $on = ( 0 === $i ); ?>
        <button type="button" class="cov-tab<?php echo $on ? ' is-active' : ''; ?>" role="tab"
                id="cov-tab-<?php echo esc_attr( $c['key'] ); ?>"
                aria-selected="<?php echo $on ? 'true' : 'false'; ?>"
                aria-controls="cov-panel-<?php echo esc_attr( $c['key'] ); ?>"
                tabindex="<?php echo $on ? '0' : '-1'; ?>">
          <span class="cov-tab__icon"><?php echo wp_kses( ensurance_home_icon( $c['icon'], 16 ), $ensurance_svg_allowed ); ?></span>
          <?php echo esc_html( $c['short'] ); ?>
        </button>
        <?php endforeach; ?>
      </div>

      <?php foreach ( $cov_paths as $i => $c ) : $on = ( 0 === $i ); ?>
      <div class="cov-panel<?php echo $on ? ' is-active' : ''; ?>" role="tabpanel"
           id="cov-panel-<?php echo esc_attr( $c['key'] ); ?>"
           aria-labelledby="cov-tab-<?php echo esc_attr( $c['key'] ); ?>"
           <?php echo $on ? '' : 'hidden'; ?>>
        <div class="cov-detail">
          <div class="cov-detail__main">
            <div class="cov-detail__top">
              <span class="icon-box icon-box--navy cov-detail__icon"><?php echo wp_kses( ensurance_home_icon( $c['icon'], 24 ), $ensurance_svg_allowed ); ?></span>
              <?php if ( $c['popular'] ) : ?><span class="badge-status">Most popular</span><?php endif; ?>
            </div>
            <h3><?php echo esc_html( $c['title'] ); ?></h3>
            <p class="cov-detail__body"><?php echo esc_html( $c['body'] ); ?></p>
            <p class="cov-detail__label">What this request may cover</p>
            <ul class="cov-detail__points">
              <?php foreach ( $c['points'] as $point ) : ?>
              <li>
                <span class="cov-detail__check"><?php echo wp_kses( ensurance_home_icon( 'check', 13 ), $ensurance_svg_allowed ); ?></span>
                <span><?php echo esc_html( $point ); ?></span>
              </li>
              <?php endforeach; ?>
            </ul>
          </div>
          <aside class="cov-detail__aside">
            <div>
              <p class="cov-detail__aside-title">Start your <?php echo esc_html( strtolower( $c['short'] ) ); ?> request</p>
              <p class="cov-detail__aside-text"><?php echo esc_html( $c['aside'] ); ?></p>
            </div>
            <a class="btn btn-primary cov-detail__cta" href="<?php echo esc_url( home_url( $c['href'] ) ); ?>" data-track="coverage_path_<?php echo esc_attr( $c['key'] ); ?>_click" data-cta-text="<?php echo esc_attr( $c['anchor'] ); ?>" data-page-type="coverage"><?php echo esc_html( $c['anchor'] ); ?> <?php echo wp_kses( ensurance_home_icon( 'arrow-right', 18 ), $ensurance_svg_allowed ); ?></a>
            <p class="cov-detail__fine"><?php echo wp_kses( ensurance_home_icon( 'lock', 12 ), $ensurance_svg_allowed ); ?> Quote options depend on availability and licensed review.</p>
          </aside>
        </div>
      </div>
      <?php endforeach; ?>
    </div>

    <div class="callout callout--neutral" role="note">
      <span class="callout__icon"><?php echo wp_kses( ensurance_home_icon( 'user', 20 ), $ensurance_svg_allowed ); ?></span>
      <div>
        <p class="callout__title">You do not need to know every detail before you begin.</p>
        <p class="callout__body">Choose the coverage type that fits your situation and answer a few guided questions. Ensurance helps organize your details so the next step is clearer, even if you are not sure exactly what coverage you need yet.</p>
      </div>
    </div>
  </section>

  <!-- ── One request path (compare) ───────────────────────────────── -->
  <section class="section reveal" aria-label="One request path">
    <div class="cov-head">
      <p class="eyebrow">One request path</p>
      <h2>One request path. A clearer way to begin.</h2>
      <p class="cov-subhead">Insurance shopping should not start with scattered forms or quote chaos.</p>
    </div>
    <div class="cov-compare">
      <div class="compare-col compare-col--bad">
        <span class="compare-col__label"><?php echo wp_kses( ensurance_home_icon( 'ban', 14 ), $ensurance_svg_allowed ); ?> Scattered</span>
        <h3>Insurance shopping the hard way</h3>
        <ul class="compare-list">
          <?php foreach ( $cov_compare_bad as $item ) : ?>
          <li><span class="compare-mark compare-mark--x"><?php echo wp_kses( ensurance_home_icon( 'x', 13 ), $ensurance_svg_allowed ); ?></span><span><?php echo esc_html( $item ); ?></span></li>
          <?php endforeach; ?>
        </ul>
      </div>
      <div class="compare-col compare-col--good">
        <span class="compare-col__label"><?php echo wp_kses( ensurance_home_icon( 'shield-check', 14 ), $ensurance_svg_allowed ); ?> Ensurance</span>
        <h3>One guided request path</h3>
        <ul class="compare-list">
          <?php foreach ( $cov_compare_good as $item ) : ?>
          <li><span class="compare-mark compare-mark--check"><?php echo wp_kses( ensurance_home_icon( 'check', 13 ), $ensurance_svg_allowed ); ?></span><span><?php echo esc_html( $item ); ?></span></li>
          <?php endforeach; ?>
        </ul>
      </div>
    </div>
  </section>

  <!-- ── Controlled flow (dark panel) ─────────────────────────────── -->
  <section class="section reveal" aria-label="A controlled request flow">
    <div class="cov-flow">
      <span class="cov-flow__glow" aria-hidden="true"></span>
      <div class="cov-flow__head">
        <span class="cov-flow__label">Controlled by design</span>
        <h2>A more controlled request flow for insurance details.</h2>
        <h2 class="cov-flow__sub">Prepared for review where available.</h2>
      </div>
      <div class="cov-flow__grid">
        <?php foreach ( $cov_flow_cards as $card ) : ?>
        <div class="cov-flow__card">
          <span class="cov-flow__card-icon"><?php echo wp_kses( ensurance_home_icon( $card['icon'], 17 ), $ensurance_svg_allowed ); ?></span>
          <p class="cov-flow__card-title"><?php echo esc_html( $card['title'] ); ?></p>
          <p class="cov-flow__card-body"><?php echo esc_html( $card['body'] ); ?></p>
        </div>
        <?php endforeach; ?>
      </div>
      <div class="cov-flow__links">
        <a href="<?php echo esc_url( home_url( '/how-it-works' ) ); ?>" data-track="how_it_works_click" data-page-type="coverage">How Ensurance works <?php echo wp_kses( ensurance_home_icon( 'arrow-right', 16 ), $ensurance_svg_allowed ); ?></a>
        <a class="cov-flow__link-accent" href="<?php echo esc_url( home_url( '/trust-center' ) ); ?>" data-track="trust_privacy_click" data-page-type="coverage">Read Trust and Privacy <?php echo wp_kses( ensurance_home_icon( 'arrow-right', 16 ), $ensurance_svg_allowed ); ?></a>
      </div>
    </div>
  </section>

  <!-- ── How it works (process steps) ─────────────────────────────── -->
  <section class="section reveal" id="process" aria-label="How it works">
    <div class="cov-head">
      <p class="eyebrow">How it works</p>
      <h2>Start with one guided request.</h2>
      <p class="cov-lead">Whichever coverage type you choose, the path is simple. Choose a request path, answer guided questions, and move toward a clearer next step.</p>
    </div>
    <div class="cov-steps">
      <?php foreach ( $cov_steps as $i => $step ) : ?>
      <article class="card step-card">
        <div class="step-card__top">
          <span class="icon-box icon-box--navy"><?php echo wp_kses( ensurance_home_icon( $step['icon'], 19 ), $ensurance_svg_allowed ); ?></span>
          <span class="step-card__num"><?php echo esc_html( sprintf( '%02d', $i + 1 ) ); ?></span>
        </div>
        <h3><?php echo esc_html( $step['title'] ); ?></h3>
        <p><?php echo esc_html( $step['body'] ); ?></p>
      </article>
      <?php endforeach; ?>
    </div>
    <div class="callout" role="note">
      <span class="callout__icon"><?php echo wp_kses( ensurance_home_icon( 'clock', 20 ), $ensurance_svg_allowed ); ?></span>
      <div>
        <p class="callout__title">Availability varies</p>
        <p class="callout__body">Quote options depend on location, coverage type, eligibility, carrier participation, and licensed review.</p>
      </div>
    </div>
  </section>

  <!-- ── FAQ ──────────────────────────────────────────────────────── -->
  <section class="faq reveal" id="faq" aria-label="Questions before you choose">
    <div class="faq__head">
      <p class="eyebrow">Questions before you choose</p>
      <h2>Questions before you choose.</h2>
    </div>
    <div class="faq-list">
      <?php foreach ( $cov_faq as $i => $item ) : ?>
      <details<?php echo 0 === $i ? ' open' : ''; ?>>
        <summary><?php echo esc_html( $item['q'] ); ?></summary>
        <p><?php echo esc_html( $item['a'] ); ?></p>
      </details>
      <?php endforeach; ?>
    </div>
  </section>

  <!-- ── Final CTA ────────────────────────────────────────────────── -->
  <section class="final-cta reveal" id="start" aria-label="Start your request">
    <div class="final-card">
      <h2>Start with one guided request.</h2>
      <p>Choose your coverage type and begin one guided request. Ensurance helps organize your details so you can move toward quote options or a clearer next step where available.</p>
      <div class="hero-actions">
        <a class="btn btn-reversed btn--lg" href="<?php echo esc_attr( $paths_anchor ); ?>" data-track="cta_click_choose_coverage" data-cta-text="Choose your coverage type" data-page-type="coverage">Choose your coverage type <?php echo wp_kses( ensurance_home_icon( 'arrow-right', 18 ), $ensurance_svg_allowed ); ?></a>
      </div>
    </div>
  </section>

</main>
<?php get_footer( 'home' ); ?>
