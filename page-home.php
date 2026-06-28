<?php
/**
 * Template Name: Home Page (Marketing)
 *
 * Homepage — rebuilt to match the "Calm Intelligence" v1 standalone design.
 * Uses the self-contained homepage chrome (get_header('home') /
 * get_footer('home')) and assets/home.css + assets/home.js, which are loaded
 * and isolated from the shared marketing bundle in functions.php.
 *
 * SEO ownership (Yoast is the active SEO plugin):
 *   - Title, meta description, canonical, and robots are entered in Yoast per
 *     page and emitted through wp_head(). This template outputs none of them.
 *   - Homepage is indexable: $page_robots was 'index, follow' in the package.
 *
 * Preserved exactly as per-page data (not shared across the site):
 *   - page_context dataLayer push (verbatim).
 *   - FAQPage JSON-LD, with question/answer text verbatim from the package and
 *     mirroring the visible FAQ section exactly (no hidden FAQ schema).
 *
 * Schema scope decision: the package shipped a full @graph (Organization,
 * WebSite, WebPage, BreadcrumbList, FAQPage). Yoast (active) already emits the
 * Organization/WebSite/WebPage/BreadcrumbList graph, so those four nodes were
 * removed here to avoid duplicate schema. Only FAQPage — which Yoast does not
 * emit for this page — is output by the template. The FAQ Q&A content is
 * unchanged from the package and mirrors the visible accordion below.
 */

// --- Per-page FAQPage schema — Q&A verbatim from package index.php ---
$home_schema = json_decode( <<<'JSON'
{
  "@context": "https://schema.org",
  "@type": "FAQPage",
  "@id": "https://www.ensurance.com/#faq",
  "mainEntity": [
    {
      "@type": "Question",
      "name": "Can I start an auto insurance quote request with Ensurance?",
      "acceptedAnswer": {
        "@type": "Answer",
        "text": "Yes. Start online, share your auto details, and let Ensurance organize your request so you can move toward quote options with more clarity and control."
      }
    },
    {
      "@type": "Question",
      "name": "Is this for car insurance too?",
      "acceptedAnswer": {
        "@type": "Answer",
        "text": "Yes. Auto insurance and car insurance are commonly used to describe the same type of request. Ensurance helps you start a guided auto or car insurance quote request online."
      }
    },
    {
      "@type": "Question",
      "name": "How is Ensurance different from typical quote sites?",
      "acceptedAnswer": {
        "@type": "Answer",
        "text": "Ensurance helps you start an organized quote request and move toward quote options with more clarity and control. The experience is designed to reduce quote chaos, pressure, unwanted contact, and confusion."
      }
    },
    {
      "@type": "Question",
      "name": "Will my information be sent everywhere?",
      "acceptedAnswer": {
        "@type": "Answer",
        "text": "The experience is designed around one organized request, not pushing you into quote chaos. Your request is organized so the next step can be handled more clearly."
      }
    },
    {
      "@type": "Question",
      "name": "Will I get spammed?",
      "acceptedAnswer": {
        "@type": "Answer",
        "text": "Ensurance is designed to reduce pressure, confusion, unwanted contact, and repeated outreach."
      }
    },
    {
      "@type": "Question",
      "name": "Can I move toward auto insurance quote options with Ensurance?",
      "acceptedAnswer": {
        "@type": "Answer",
        "text": "Yes. You can start an auto insurance quote request and move toward quote options. Options depend on location, coverage type, eligibility, carrier availability, and licensed review."
      }
    },
    {
      "@type": "Question",
      "name": "Do I have to commit?",
      "acceptedAnswer": {
        "@type": "Answer",
        "text": "No. Starting a quote request does not commit you to buy a policy."
      }
    },
    {
      "@type": "Question",
      "name": "Can I request home, life, or business insurance too?",
      "acceptedAnswer": {
        "@type": "Answer",
        "text": "Yes, when available. Home, life, and business paths depend on location, request type, eligibility, carrier availability, and licensed review."
      }
    },
    {
      "@type": "Question",
      "name": "What happens after I start?",
      "acceptedAnswer": {
        "@type": "Answer",
        "text": "Your request is organized so the next step becomes clearer. Where appropriate, licensed support may review available paths."
      }
    }
  ]
}
JSON, true );

// --- Per-page page_context dataLayer payload — verbatim from package index.php ---
$home_page_context = array(
    'event'         => 'page_context',
    'page_type'     => 'homepage',
    'audience'      => 'shopper_agent',
    'funnel_stage'  => 'entry',
    'primary_intent' => 'auto_insurance_quote',
    'primary_cta'   => 'start_my_auto_quote_request',
    'secondary_cta' => 'see_how_ensurance_works',
    'agent_cta'     => 'get_agent_access',
);

add_action( 'wp_head', function () use ( $home_schema, $home_page_context ) {
    $flags = JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE;
    // page_context push (GTM is initialised earlier at wp_head priority 1).
    echo "<script>\n";
    echo "window.dataLayer = window.dataLayer || [];\n";
    echo 'window.dataLayer.push(' . wp_json_encode( $home_page_context, $flags ) . ");\n";
    echo "</script>\n";
    // JSON-LD @graph.
    if ( $home_schema ) {
        echo '<script type="application/ld+json">' . "\n";
        echo wp_json_encode( $home_schema, $flags | JSON_PRETTY_PRINT ) . "\n";
        echo "</script>\n";
    }
}, 20 );

/**
 * Inline Lucide icon renderer (scoped to the homepage template).
 *
 * Paths are copied from the v1 design system (Lucide, ISC license). Stroke 2,
 * round caps/joins; color is inherited via currentColor so CSS controls it.
 */
if ( ! function_exists( 'ensurance_home_icon' ) ) {
    function ensurance_home_icon( $name, $size = 20 ) {
        $icons = array(
            'shield-check' => '<path d="M20 13c0 5-3.5 7.5-7.66 8.95a1 1 0 0 1-.67-.01C7.5 20.5 4 18 4 13V6a1 1 0 0 1 1-1c2 0 4.5-1.2 6.24-2.72a1.17 1.17 0 0 1 1.52 0C14.51 3.81 17 5 19 5a1 1 0 0 1 1 1z"/><path d="m9 12 2 2 4-4"/>',
            'user'         => '<circle cx="12" cy="8" r="5"/><path d="M20 21a8 8 0 0 0-16 0"/>',
            'check'        => '<path d="M20 6 9 17l-5-5"/>',
            'arrow-right'  => '<path d="M5 12h14"/><path d="m12 5 7 7-7 7"/>',
            'clock'        => '<circle cx="12" cy="12" r="10"/><path d="M12 6v6l4 2"/>',
            'message'      => '<path d="M7.9 20A9 9 0 1 0 4 16.1L2 22Z"/>',
            'ban'          => '<circle cx="12" cy="12" r="10"/><path d="m4.9 4.9 14.2 14.2"/>',
            'home'         => '<path d="m3 9 9-7 9 7v11a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2z"/><path d="M9 22V12h6v10"/>',
            'car'          => '<path d="M19 17h2c.6 0 1-.4 1-1v-3c0-.9-.7-1.7-1.5-1.9C18.7 10.6 16 10 16 10s-1.3-1.4-2.2-2.3c-.5-.4-1.1-.7-1.8-.7H5c-.6 0-1.1.4-1.4.9l-1.4 2.9A3.7 3.7 0 0 0 2 12v4c0 .6.4 1 1 1h2"/><circle cx="7" cy="17" r="2"/><path d="M9 17h6"/><circle cx="17" cy="17" r="2"/>',
            'key'          => '<path d="M2.586 17.414A2 2 0 0 0 2 18.828V21a1 1 0 0 0 1 1h3a1 1 0 0 0 1-1v-1a1 1 0 0 1 1-1h1a1 1 0 0 0 1-1v-1a1 1 0 0 1 1-1h.172a2 2 0 0 0 1.414-.586l.814-.814a6.5 6.5 0 1 0-4-4z"/><circle cx="16.5" cy="7.5" r=".5"/>',
            'heart'        => '<path d="M19 14c1.49-1.46 3-3.21 3-5.5A5.5 5.5 0 0 0 16.5 3c-1.76 0-3 .5-4.5 2-1.5-1.5-2.74-2-4.5-2A5.5 5.5 0 0 0 2 8.5c0 2.3 1.5 4.05 3 5.5l7 7Z"/>',
            'heart-pulse'  => '<path d="M19 14c1.49-1.46 3-3.21 3-5.5A5.5 5.5 0 0 0 16.5 3c-1.76 0-3 .5-4.5 2-1.5-1.5-2.74-2-4.5-2A5.5 5.5 0 0 0 2 8.5c0 2.3 1.5 4.05 3 5.5l7 7Z"/><path d="M3.22 12H9.5l.5-1 2 4.5 2-7 1.5 3.5h5.27"/>',
            'briefcase'    => '<path d="M16 20V4a2 2 0 0 0-2-2h-4a2 2 0 0 0-2 2v16"/><rect x="2" y="6" width="20" height="14" rx="2"/>',
            'umbrella'     => '<path d="M22 12a10.06 10.06 0 0 0-20 0Z"/><path d="M12 12v8a2 2 0 0 0 4 0"/><path d="M12 2v1"/>',
            'sparkles'     => '<path d="M9.937 15.5A2 2 0 0 0 8.5 14.063l-6.135-1.582a.5.5 0 0 1 0-.962L8.5 9.936A2 2 0 0 0 9.937 8.5l1.582-6.135a.5.5 0 0 1 .963 0L14.063 8.5A2 2 0 0 0 15.5 9.937l6.135 1.581a.5.5 0 0 1 0 .964L15.5 14.063a2 2 0 0 0-1.437 1.437l-1.582 6.135a.5.5 0 0 1-.963 0z"/><path d="M20 3v4"/><path d="M22 5h-4"/>',
            'file-text'    => '<path d="M15 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V7Z"/><path d="M14 2v4a2 2 0 0 0 2 2h4"/><path d="M10 9H8"/><path d="M16 13H8"/><path d="M16 17H8"/>',
        );
        $inner = isset( $icons[ $name ] ) ? $icons[ $name ] : '';
        $s     = (int) $size;
        return '<svg width="' . $s . '" height="' . $s . '" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">' . $inner . '</svg>';
    }
}

// Allow the inline SVG markup through wp_kses for the icon helper output.
$ensurance_svg_allowed = array(
    'svg'    => array( 'width' => true, 'height' => true, 'viewbox' => true, 'fill' => true, 'stroke' => true, 'stroke-width' => true, 'stroke-linecap' => true, 'stroke-linejoin' => true, 'aria-hidden' => true ),
    'path'   => array( 'd' => true ),
    'circle' => array( 'cx' => true, 'cy' => true, 'r' => true ),
    'rect'   => array( 'x' => true, 'y' => true, 'width' => true, 'height' => true, 'rx' => true ),
);

$start_url = esc_url( home_url( '/auto-insurance-quote' ) );

// Coverage lines beyond the featured Auto tile: icon, title, body, request slug.
$coverage_lines = array(
    array( 'icon' => 'home',        'title' => 'Home',      'slug' => 'home-services',                       'track' => 'home',      'body' => 'Start a home insurance quote request and move toward quote options with more clarity.' ),
    array( 'icon' => 'key',         'title' => 'Renters',   'slug' => 'renters-insurance-quote-request',   'track' => 'renters',   'body' => 'Start a renters insurance quote request and move toward quote options with more clarity.' ),
    array( 'icon' => 'heart',       'title' => 'Life',      'slug' => 'life-insurance-quote-request',      'track' => 'life',      'body' => 'Start a life insurance quote request and move toward quote options with more clarity.' ),
    array( 'icon' => 'heart-pulse', 'title' => 'Health',    'slug' => 'health-insurance-quote-request',    'track' => 'health',    'body' => 'Start a health insurance quote request and move toward quote options with more clarity.' ),
    array( 'icon' => 'briefcase',   'title' => 'Business',  'slug' => 'business-insurance-quote-request',  'track' => 'business',  'body' => 'Start a business insurance quote request and move toward quote options where supported.' ),
    array( 'icon' => 'umbrella',    'title' => 'Umbrella',  'slug' => 'umbrella-insurance-quote-request',  'track' => 'umbrella',  'body' => 'Start an umbrella insurance quote request and move toward quote options with more clarity.' ),
    array( 'icon' => 'sparkles',    'title' => 'Specialty', 'slug' => 'specialty-insurance-quote-request', 'track' => 'specialty', 'body' => 'Start a specialty insurance quote request and move toward quote options with more clarity.' ),
);

get_header( 'home' );
?>
<main id="main">

  <!-- ── Hero (video layout) ─────────────────────────────────────── -->
  <section class="hero" aria-label="Auto insurance quote help">
    <div class="hero__media" aria-hidden="true">
      <?php /* Drop in brand footage here:
        <video autoplay muted loop playsinline poster="/path/to/poster.jpg">
          <source src="/path/to/hero.mp4" type="video/mp4">
        </video> */ ?>
      <span class="hero__blob hero__blob--a"></span>
      <span class="hero__blob hero__blob--b"></span>
    </div>
    <div class="hero__scrim" aria-hidden="true"></div>

    <div class="hero__content">
      <div class="hero__inner">
        <span class="hero__eyebrow">Guided auto insurance quote request</span>
        <h1>Auto insurance quote help <span class="accent">without the quote chaos.</span></h1>
        <p class="hero__subtitle">Start an auto insurance quote request online, organize your details, and move toward quote options with more clarity and control.</p>
        <div class="hero-actions" aria-label="Homepage actions">
          <a class="btn btn-reversed btn--lg" data-cta-text="Start My Auto Quote Request" data-page-type="homepage" data-track="cta_click_start_auto_quote_request" href="<?php echo $start_url; ?>">Start My Auto Quote Request <?php echo wp_kses( ensurance_home_icon( 'arrow-right', 18 ), $ensurance_svg_allowed ); ?></a>
          <a class="hero__textlink" data-cta-text="See How Ensurance Works" data-page-type="homepage" data-track="cta_click_see_how_ensurance_works" href="#how-it-works">See How Ensurance Works <?php echo wp_kses( ensurance_home_icon( 'arrow-right', 16 ), $ensurance_svg_allowed ); ?></a>
        </div>
        <p class="hero__finetext">Free to start. No spam calls. One organized request — not a list.</p>
      </div>
    </div>

    <div class="hero__trust">
      <span class="trust-cue"><?php echo wp_kses( ensurance_home_icon( 'clock', 16 ), $ensurance_svg_allowed ); ?> Online first</span>
      <span class="trust-cue"><?php echo wp_kses( ensurance_home_icon( 'shield-check', 16 ), $ensurance_svg_allowed ); ?> Licensed support where appropriate</span>
      <span class="trust-cue"><?php echo wp_kses( ensurance_home_icon( 'user', 16 ), $ensurance_svg_allowed ); ?> Organized requests</span>
      <span class="trust-cue"><?php echo wp_kses( ensurance_home_icon( 'ban', 16 ), $ensurance_svg_allowed ); ?> No quote chaos</span>
    </div>
  </section>

  <!-- ── What is Ensurance ───────────────────────────────────────── -->
  <section class="section section--lg" aria-label="What is Ensurance">
    <div class="whatis">
      <div class="media-panel" aria-hidden="true">
        <span class="media-panel__icon"><?php echo wp_kses( ensurance_home_icon( 'car', 28 ), $ensurance_svg_allowed ); ?></span>
      </div>
      <div>
        <p class="eyebrow">What is Ensurance?</p>
        <h2>Start an auto insurance quote request and move toward options.</h2>
        <p class="whatis__intro">Ensurance helps shoppers start insurance quote requests online, organize their details, and move toward quote options with more clarity and control. The experience is designed to reduce confusion, pressure, unwanted contact, and quote chaos.</p>
        <div class="whatis__rows">
          <div class="spotlight-row"><?php echo wp_kses( ensurance_home_icon( 'shield-check', 18 ), $ensurance_svg_allowed ); ?><span>Start with one organized auto request instead of repeating forms.</span></div>
          <div class="spotlight-row"><?php echo wp_kses( ensurance_home_icon( 'car', 18 ), $ensurance_svg_allowed ); ?><span>Your request is organized for a clearer, more controlled quote path.</span></div>
          <div class="spotlight-row"><?php echo wp_kses( ensurance_home_icon( 'message', 18 ), $ensurance_svg_allowed ); ?><span>Move toward quote options with more clarity when support is available.</span></div>
        </div>
        <div class="whatis__actions">
          <a class="btn btn-secondary" data-cta-text="See How Ensurance Works" data-page-type="homepage" data-track="cta_click_see_how_ensurance_works" href="#how-it-works">See How Ensurance Works <?php echo wp_kses( ensurance_home_icon( 'arrow-right', 16 ), $ensurance_svg_allowed ); ?></a>
        </div>
      </div>
    </div>
  </section>

  <!-- ── How Ensurance works ─────────────────────────────────────── -->
  <section class="section" id="how-it-works" aria-label="How Ensurance works">
    <div class="section-head">
      <p class="eyebrow">How Ensurance works</p>
      <h2>A clearer way to start your auto quote request.</h2>
      <p>From your first detail to a clearer next step — you'll always know where you are.</p>
    </div>
    <div class="steps-grid">
      <article class="card step-card">
        <div class="step-card__top">
          <span class="icon-box icon-box--navy"><?php echo wp_kses( ensurance_home_icon( 'file-text', 19 ), $ensurance_svg_allowed ); ?></span>
          <span class="step-card__num">01</span>
        </div>
        <h3>Tell us what you need</h3>
        <p>Share the key auto details once — short guided questions, minimal typing.</p>
      </article>
      <article class="card step-card">
        <div class="step-card__top">
          <span class="icon-box icon-box--navy"><?php echo wp_kses( ensurance_home_icon( 'check', 19 ), $ensurance_svg_allowed ); ?></span>
          <span class="step-card__num">02</span>
        </div>
        <h3>Your request is organized</h3>
        <p>Ensurance organizes your information for the next step, so nothing gets repeated.</p>
      </article>
      <article class="card step-card">
        <div class="step-card__top">
          <span class="icon-box icon-box--navy"><?php echo wp_kses( ensurance_home_icon( 'user', 19 ), $ensurance_svg_allowed ); ?></span>
          <span class="step-card__num">03</span>
        </div>
        <h3>Move toward options with more clarity</h3>
        <p>Available quote paths are reviewed when appropriate, and you move forward with a clearer next step.</p>
      </article>
    </div>
  </section>

  <!-- ── Coverage types ──────────────────────────────────────────── -->
  <section class="section" id="coverage" aria-label="Coverage types">
    <div class="section-head">
      <p class="eyebrow">Coverage types</p>
      <h2>Need more than auto insurance?</h2>
      <p>Availability varies by state, request type, eligibility, carrier availability, and licensed review.</p>
    </div>
    <div class="coverage-grid">
      <a class="card coverage-featured" data-cta-text="Start My Auto Quote Request" data-page-type="homepage" data-track="coverage_card_click_auto" href="<?php echo $start_url; ?>">
        <div class="coverage-tile">
          <div class="coverage-tile__head">
            <span class="icon-box icon-box--accent"><?php echo wp_kses( ensurance_home_icon( 'car', 24 ), $ensurance_svg_allowed ); ?></span>
            <span class="badge-status">Most requested</span>
          </div>
          <div>
            <h3>Auto insurance</h3>
            <p>Start an organized auto insurance quote request and move toward quote options with more clarity and control — one guided request, a clearer next step.</p>
          </div>
          <div class="coverage-featured__tags">
            <span class="tag-pill">Liability</span>
            <span class="tag-pill">Collision &amp; comprehensive</span>
            <span class="tag-pill">Multi-car</span>
            <span class="tag-pill">Teen drivers</span>
            <span class="tag-pill">Bundling</span>
            <span class="coverage-tile__arrow"><?php echo wp_kses( ensurance_home_icon( 'arrow-right', 20 ), $ensurance_svg_allowed ); ?></span>
          </div>
        </div>
      </a>
      <?php foreach ( $coverage_lines as $line ) : ?>
      <a class="card" data-cta-text="Start My <?php echo esc_attr( $line['title'] ); ?> Quote Request" data-page-type="homepage" data-track="coverage_card_click_<?php echo esc_attr( $line['track'] ); ?>" href="<?php echo esc_url( home_url( '/' . $line['slug'] ) ); ?>">
        <div class="coverage-tile">
          <span class="icon-box icon-box--navy"><?php echo wp_kses( ensurance_home_icon( $line['icon'], 20 ), $ensurance_svg_allowed ); ?></span>
          <div>
            <h3><?php echo esc_html( $line['title'] ); ?></h3>
            <p><?php echo esc_html( $line['body'] ); ?></p>
          </div>
          <span class="coverage-tile__arrow"><?php echo wp_kses( ensurance_home_icon( 'arrow-right', 18 ), $ensurance_svg_allowed ); ?></span>
        </div>
      </a>
      <?php endforeach; ?>
    </div>
  </section>

  <!-- ── What makes Ensurance different ──────────────────────────── -->
  <section class="section" aria-label="What makes Ensurance different">
    <div class="section-head">
      <p class="eyebrow">What makes Ensurance different</p>
      <h2>Guided auto requests designed to reduce quote chaos.</h2>
      <p>Ensurance organizes your request so the next step is clearer and more controlled.</p>
    </div>
    <div class="why-grid">
      <article class="card why-tile">
        <span class="icon-box icon-box--accent"><?php echo wp_kses( ensurance_home_icon( 'file-text', 20 ), $ensurance_svg_allowed ); ?></span>
        <div>
          <h3>Organized from the start</h3>
          <p>Start with one organized quote request.</p>
        </div>
      </article>
      <article class="card why-tile">
        <span class="icon-box icon-box--accent"><?php echo wp_kses( ensurance_home_icon( 'sparkles', 20 ), $ensurance_svg_allowed ); ?></span>
        <div>
          <h3>Built for quote options</h3>
          <p>Your details are organized so you can move toward quote options with more clarity.</p>
        </div>
      </article>
      <article class="card why-tile">
        <span class="icon-box icon-box--accent"><?php echo wp_kses( ensurance_home_icon( 'shield-check', 20 ), $ensurance_svg_allowed ); ?></span>
        <div>
          <h3>Licensed review where appropriate</h3>
          <p>Licensed support may review available quote paths when appropriate.</p>
        </div>
      </article>
      <article class="card why-tile">
        <span class="icon-box icon-box--accent"><?php echo wp_kses( ensurance_home_icon( 'arrow-right', 20 ), $ensurance_svg_allowed ); ?></span>
        <div>
          <h3>Focused on clearer next steps</h3>
          <p>Move forward with less confusion and a clearer next step.</p>
        </div>
      </article>
    </div>
  </section>

  <!-- ── FAQ ─────────────────────────────────────────────────────── -->
  <section class="faq" aria-label="Questions before you start">
    <div class="faq__head">
      <p class="eyebrow">Questions before you start</p>
      <h2>What to know before you start.</h2>
    </div>
    <div class="faq-list">
      <details open>
        <summary>Can I start an auto insurance quote request with Ensurance?</summary>
        <p>Yes. Start online, share your auto details, and let Ensurance organize your request so you can move toward quote options with more clarity and control.</p>
      </details>
      <details>
        <summary>Is this for car insurance too?</summary>
        <p>Yes. Auto insurance and car insurance are commonly used to describe the same type of request. Ensurance helps you start a guided auto or car insurance quote request online.</p>
      </details>
      <details>
        <summary>How is Ensurance different from typical quote sites?</summary>
        <p>Ensurance helps you start an organized quote request and move toward quote options with more clarity and control. The experience is designed to reduce quote chaos, pressure, unwanted contact, and confusion.</p>
      </details>
      <details>
        <summary>Will my information be sent everywhere?</summary>
        <p>The experience is designed around one organized request, not pushing you into quote chaos. Your request is organized so the next step can be handled more clearly.</p>
      </details>
      <details>
        <summary>Will I get spammed?</summary>
        <p>Ensurance is designed to reduce pressure, confusion, unwanted contact, and repeated outreach.</p>
      </details>
      <details>
        <summary>Can I move toward auto insurance quote options with Ensurance?</summary>
        <p>Yes. You can start an auto insurance quote request and move toward quote options. Options depend on location, coverage type, eligibility, carrier availability, and licensed review.</p>
      </details>
      <details>
        <summary>Do I have to commit?</summary>
        <p>No. Starting a quote request does not commit you to buy a policy.</p>
      </details>
      <details>
        <summary>Can I request home, life, or business insurance too?</summary>
        <p>Yes, when available. Home, life, and business paths depend on location, request type, eligibility, carrier availability, and licensed review.</p>
      </details>
      <details>
        <summary>What happens after I start?</summary>
        <p>Your request is organized so the next step becomes clearer. Where appropriate, licensed support may review available paths.</p>
      </details>
    </div>
  </section>

  <!-- ── Final CTA ───────────────────────────────────────────────── -->
  <section class="final-cta" aria-label="Start your auto quote request">
    <div class="final-card">
      <h2>A clearer way to move toward auto insurance quote options.</h2>
      <p>Many online quote paths ask for personal details before explaining what comes next. Ensurance helps organize your request from the start.</p>
      <div class="hero-actions">
        <a class="btn btn-reversed btn--lg" data-cta-text="Start My Auto Quote Request" data-page-type="homepage" data-track="cta_click_start_auto_quote_request" href="<?php echo $start_url; ?>">Start My Auto Quote Request <?php echo wp_kses( ensurance_home_icon( 'arrow-right', 18 ), $ensurance_svg_allowed ); ?></a>
      </div>
    </div>
  </section>

</main>
<?php get_footer( 'home' ); ?>
