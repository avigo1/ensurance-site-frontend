<?php
/**
 * Template Name: Home Page (Marketing)
 *
 * Homepage — auto-forward design, ported verbatim from the bespoke package
 * (index.php). Uses the self-contained homepage chrome (get_header('home') /
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
 * unchanged from the package.
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

get_header( 'home' );
?>
<main id="main">
<section class="hero section-soft">
<div class="container hero-grid">
<div class="hero-copy">
<p class="eyebrow">Guided auto insurance quote request</p>
<h1>Auto insurance quote help without the quote chaos.</h1>
<p class="hero-subtitle">Start an auto insurance quote request online, organize your details, and move toward quote options with more clarity and control.</p>
<p class="hero-support">The experience is designed to reduce confusion, pressure, unwanted contact, and unclear next steps.</p>

<div aria-label="Homepage actions" class="hero-actions">
<a class="btn btn-primary" data-cta-text="Start My Auto Quote Request" data-page-type="homepage" data-track="cta_click_start_auto_quote_request" href="<?php echo esc_url( home_url( '/auto-insurance-quote-request' ) ); ?>">Start My Auto Quote Request</a>
<a class="btn btn-secondary" data-cta-text="See How Ensurance Works" data-page-type="homepage" data-track="cta_click_see_how_ensurance_works" href="<?php echo esc_url( home_url( '/how-it-works' ) ); ?>">See How Ensurance Works</a>
</div>
</div>
<div aria-label="Ensurance auto request preview" class="hero-panel">
<div class="flow-card request-preview-card">
<div class="flow-card-header">
<span aria-hidden="true" class="status-dot"></span>
<span>Choose your coverage path</span>
</div>
<div class="request-question-card">
<p class="path-label">What type of insurance do you need?</p>
<div aria-label="Insurance request options" class="request-option-grid">
<span class="request-option is-selected">Auto insurance</span>
<span class="request-option">Home insurance</span>
<span class="request-option">Life insurance</span>
<span class="request-option">Business insurance</span>
</div>
<p class="request-preview-note">Start with the coverage you need today.</p>
</div>

</div>
</div>
</div>
</section>
<section aria-label="Ensurance value summary" class="section proof-strip">
<div class="container proof-grid">
<article>
<span>One organized auto quote request</span>
<p>Start with one organized auto request instead of repeating forms.</p>
</article>
<article>
<span>Designed to reduce quote chaos</span>
<p>Your request is organized for a clearer, more controlled quote path.</p>
</article>
<article>
<span>Clearer path to quote options</span>
<p>Move toward quote options with more clarity when support is available.</p>
</article>
</div>
</section>
<section aria-label="What is Ensurance" class="section answer-section">
<div class="container two-column align-start">
<div class="section-copy">
<p class="eyebrow">What is Ensurance?</p>
<h2>Start an auto insurance quote request and move toward options.</h2>
</div>
<div class="answer-card">
<p>Ensurance helps shoppers start insurance quote requests online, organize their details, and move toward quote options with more clarity and control. The experience is designed to reduce confusion, pressure, unwanted contact, and quote chaos.</p>
<a class="btn btn-secondary" data-cta-text="See How Ensurance Works" data-page-type="homepage" data-track="cta_click_see_how_ensurance_works" href="<?php echo esc_url( home_url( '/how-it-works' ) ); ?>">See How Ensurance Works</a>
</div>
</div>
</section>
<section class="section how-section section-soft" id="how-it-works">
<div class="container section-header centered">
<p class="eyebrow">How Ensurance works</p>
<h2>A clearer way to start your auto quote request.</h2>
</div>
<div class="container process-grid">
<article class="process-card">
<span class="process-number">1</span>
<h3>Tell us what you need</h3>
<p>Share the key auto details once.</p>
</article>
<article class="process-card">
<span class="process-number">2</span>
<h3>Your request is organized</h3>
<p>Ensurance organizes your information for the next step.</p>
</article>
<article class="process-card">
<span class="process-number">3</span>
<h3>Available quote paths are reviewed</h3>
<p>Licensed support may review available quote paths when appropriate.</p>
</article>
<article class="process-card">
<span class="process-number">4</span>
<h3>Move toward options with more clarity</h3>
<p>Move forward with less confusion and a clearer next step.</p>
</article>
</div>
<div class="container centered action-row">
<a class="btn btn-primary" data-cta-text="Start My Auto Quote Request" data-page-type="homepage" data-track="cta_click_start_auto_quote_request" href="<?php echo esc_url( home_url( '/auto-insurance-quote-request' ) ); ?>">Start My Auto Quote Request</a>
</div>
</section>
<section class="section difference-section">
<div class="container two-column align-start">
<div class="section-copy">
<p class="eyebrow">What makes Ensurance different</p>
<h2>Guided auto requests designed to reduce quote chaos.</h2>
<p class="section-lead">Ensurance organizes your request so the next step is clearer and more controlled.</p>
</div>
<div class="difference-grid">
<article class="content-card">
<h3>Organized from the start</h3>
<p>Start with one organized quote request.</p>
</article>
<article class="content-card">
<h3>Built for quote options</h3>
<p>Your details are organized so you can move toward quote options with more clarity.</p>
</article>
<article class="content-card">
<h3>Licensed review where appropriate</h3>
<p>Licensed support may review available quote paths when appropriate.</p>
</article>
<article class="content-card">
<h3>Focused on clearer next steps</h3>
<p>Move forward with less confusion and a clearer next step.</p>
</article>
</div>
</div>
</section>
<section class="section protected-section navy-band">
<div class="container two-column align-start">
<div class="section-copy light-copy">
<p class="eyebrow eyebrow-light">No quote chaos</p>
<h2>A clearer way to move toward auto insurance quote options.</h2>
<p class="section-lead">Many online quote paths ask for personal details before explaining what comes next. Ensurance helps organize your request from the start.</p>
<div class="section-actions">
<a class="btn btn-primary" data-cta-text="Start My Auto Quote Request" data-page-type="homepage" data-track="cta_click_start_auto_quote_request" href="<?php echo esc_url( home_url( '/auto-insurance-quote-request' ) ); ?>">Start My Auto Quote Request</a>
</div>
<p class="ad-disclosure">Quote options depend on location, coverage type, eligibility, carrier availability, and licensed review.</p>
</div>
<div class="trust-panel">
<article><span></span><p>Your request is organized from the start.</p></article>
<article><span></span><p>Your details are structured before review.</p></article>
<article><span></span><p>Licensed support can review available quote paths when appropriate.</p></article>
<article><span></span><p>The flow is designed to reduce repeated forms, pressure, and unclear next steps.</p></article>
</div>
</div>
</section>
<section class="section coverage-section">
<div class="container section-header centered">
<p class="eyebrow">Coverage types</p>
<h2>Need more than auto insurance?</h2>
<p>Availability varies by state, request type, eligibility, carrier availability, and licensed review.</p>
</div>
<div class="container coverage-grid coverage-grid-four">
<article class="coverage-card">
<h3>Auto insurance</h3>
<p>Start an auto quote request and move toward quote options.</p>
<a data-cta-text="Start My Auto Quote Request" data-page-type="homepage" data-track="coverage_card_click_auto" href="<?php echo esc_url( home_url( '/auto-insurance-quote-request' ) ); ?>">Start My Auto Quote Request</a>
</article>
<article class="coverage-card">
<h3>Home insurance</h3>
<p>Start a home quote request and move toward quote options.</p>
<a data-cta-text="Start My Home Quote Request" data-page-type="homepage" data-track="coverage_card_click_home" href="<?php echo esc_url( home_url( '/home-insurance-quote-request' ) ); ?>">Start My Home Quote Request</a>
</article>
<article class="coverage-card">
<h3>Life insurance</h3>
<p>Start a life insurance request and move toward quote options where supported.</p>
<a data-cta-text="Start My Life Quote Request" data-page-type="homepage" data-track="coverage_card_click_life" href="<?php echo esc_url( home_url( '/life-insurance-quote-request' ) ); ?>">Start My Life Quote Request</a>
</article>
<article class="coverage-card">
<h3>Business insurance</h3>
<p>Start a business insurance request and move toward quote options where supported.</p>
<a data-cta-text="Start My Business Quote Request" data-page-type="homepage" data-track="coverage_card_click_business" href="<?php echo esc_url( home_url( '/business-insurance-quote-request' ) ); ?>">Start My Business Quote Request</a>
</article>
</div>
</section>
<section class="section faq-section">
<div class="container two-column align-start">
<div class="section-copy sticky-copy">
<p class="eyebrow">Questions before you start</p>
<h2>What to know before you start.</h2>
<a class="btn btn-primary" data-cta-text="Start My Auto Quote Request" data-page-type="homepage" data-track="cta_click_start_auto_quote_request" href="<?php echo esc_url( home_url( '/auto-insurance-quote-request' ) ); ?>">Start My Auto Quote Request</a>
</div>
<div class="faq-list">
<details>
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
</div>
</section>
<section class="section final-cta section-soft">
<div class="container final-card">
<p class="eyebrow">Begin with one organized auto quote request</p>
<h2>Move toward auto quote options with more clarity.</h2>
<p>Start online, organize your details, and move toward quote options with more control.</p>
<div class="hero-actions centered-actions">
<a class="btn btn-primary" data-cta-text="Start My Auto Quote Request" data-page-type="homepage" data-track="cta_click_start_auto_quote_request" href="<?php echo esc_url( home_url( '/auto-insurance-quote-request' ) ); ?>">Start My Auto Quote Request</a>
<a class="btn btn-secondary" data-cta-text="See How Ensurance Works" data-page-type="homepage" data-track="cta_click_see_how_ensurance_works" href="<?php echo esc_url( home_url( '/how-it-works' ) ); ?>">See How Ensurance Works</a>
</div>
<p class="trust-line">Quote help without the quote chaos.</p>
</div>
</section>
</main>
<?php get_footer( 'home' ); ?>
