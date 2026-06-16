<?php
/**
 * Template Name: How It Works (Marketing)
 *
 * /how-it-works — rebuilt to match the "Calm Intelligence" standalone redesign
 * ("Ensurance How It Works"). Uses the homepage chrome (get_header('home') /
 * get_footer('home')) and shares assets/home.css + assets/home.js for tokens,
 * chrome and base components. Page-specific sections and the scroll-reveal
 * transitions live in assets/how-it-works.css + assets/how-it-works.js, loaded
 * and isolated from the shared marketing bundle in functions.php.
 *
 * SEO: title / meta description / canonical / robots are owned by Yoast and
 * emitted through wp_head(); this template outputs none of them. The FAQPage
 * JSON-LD below is shipped here (Yoast does not emit it for this page) and
 * mirrors the visible FAQ accordion verbatim.
 */

// --- Per-page FAQPage schema — mirrors the visible FAQ accordion verbatim. ---
$hiw_schema = json_decode( <<<'JSON'
{
  "@context": "https://schema.org",
  "@type": "FAQPage",
  "@id": "https://www.ensurance.com/how-it-works#faq",
  "mainEntity": [
    {
      "@type": "Question",
      "name": "What happens after I start a request?",
      "acceptedAnswer": {
        "@type": "Answer",
        "text": "Your details are organized into one structured request. From there, licensed agents, agencies, or approved insurance partners can review available carriers and follow up with quote options where available."
      }
    },
    {
      "@type": "Question",
      "name": "Who reviews my request?",
      "acceptedAnswer": {
        "@type": "Answer",
        "text": "Licensed agents, agencies, or approved insurance partners review your structured request. A licensed professional, not an automated list, decides which available carrier options may fit."
      }
    },
    {
      "@type": "Question",
      "name": "Can one request help me access multiple carrier options?",
      "acceptedAnswer": {
        "@type": "Answer",
        "text": "Often, yes. A licensed professional can review multiple available carriers from your single structured request, so you do not have to repeat the same details across separate forms."
      }
    },
    {
      "@type": "Question",
      "name": "Are quote options guaranteed?",
      "acceptedAnswer": {
        "@type": "Answer",
        "text": "No. Availability, eligibility, carrier participation, and licensed professional review determine which quote options may be available to you."
      }
    },
    {
      "@type": "Question",
      "name": "Is Ensurance a quote-comparison site?",
      "acceptedAnswer": {
        "@type": "Answer",
        "text": "No. Ensurance is not an instant quote-comparison engine. It structures one request for licensed review, rather than auctioning your details for automated price lists."
      }
    },
    {
      "@type": "Question",
      "name": "Will my information be sent everywhere?",
      "acceptedAnswer": {
        "@type": "Answer",
        "text": "No. Your structured request is shared for licensed review where appropriate. It is not blasted out to a long list of companies."
      }
    }
  ]
}
JSON, true );

add_action( 'wp_head', function () use ( $hiw_schema ) {
    if ( $hiw_schema ) {
        $flags = JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE | JSON_PRETTY_PRINT;
        echo '<script type="application/ld+json">' . "\n";
        echo wp_json_encode( $hiw_schema, $flags ) . "\n";
        echo "</script>\n";
    }
}, 20 );

/**
 * Inline Lucide icon renderer (shared with page-home.php via function_exists
 * guard). Paths copied from the v1 design system (Lucide, ISC license).
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
            'file-text'    => '<path d="M15 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V7Z"/><path d="M14 2v4a2 2 0 0 0 2 2h4"/><path d="M10 9H8"/><path d="M16 13H8"/><path d="M16 17H8"/>',
            'sparkles'     => '<path d="M9.937 15.5A2 2 0 0 0 8.5 14.063l-6.135-1.582a.5.5 0 0 1 0-.962L8.5 9.936A2 2 0 0 0 9.937 8.5l1.582-6.135a.5.5 0 0 1 .963 0L14.063 8.5A2 2 0 0 0 15.5 9.937l6.135 1.581a.5.5 0 0 1 0 .964L15.5 14.063a2 2 0 0 0-1.437 1.437l-1.582 6.135a.5.5 0 0 1-.963 0z"/><path d="M20 3v4"/><path d="M22 5h-4"/>',
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

$start_url = esc_url( home_url( '/auto-insurance-quote-request' ) );

// Timeline steps — verbatim from the design's PROCESS array.
$hiw_steps = array(
    array( 'icon' => 'user',         'title' => 'Tell us what you need',                              'body' => "Answer a few short, guided questions about the coverage you're looking for. Minimal typing, one step at a time." ),
    array( 'icon' => 'file-text',    'title' => 'Ensurance structures your request',                 'body' => 'Your details are organized into one clear, structured request — so nothing is repeated and nothing important is missing.' ),
    array( 'icon' => 'shield-check', 'title' => 'Licensed agents or agencies review available carriers', 'body' => 'Licensed agents, agencies, or approved insurance partners review your request against the carriers available to them.' ),
    array( 'icon' => 'sparkles',     'title' => 'You receive quote options where available',         'body' => 'Where coverage and eligibility allow, you receive quote options and a clearer next step — no pressure to decide.' ),
);

// "After you start" spotlight rows.
$hiw_after = array(
    array( 'icon' => 'file-text',    'title' => 'Your details stay organized',     'body' => "Everything you shared is kept together as one structured request, so you don't re-enter the same information." ),
    array( 'icon' => 'shield-check', 'title' => 'A licensed professional reviews it', 'body' => 'Licensed agents, agencies, or approved partners review available carriers — a person, not a price-list robot.' ),
    array( 'icon' => 'message',      'title' => 'You get a clearer next step',     'body' => "Where options are available, you'll see quote options and what to do next — at your own pace." ),
);

// "Why licensed review matters" tiles.
$hiw_why = array(
    array( 'icon' => 'user',         'title' => 'A person reviews your request', 'body' => 'Licensed agents and agencies understand coverage, eligibility, and the carriers available to them.' ),
    array( 'icon' => 'file-text',    'title' => 'One request, multiple carriers', 'body' => 'A licensed professional can review several available carriers from your single structured request.' ),
    array( 'icon' => 'shield-check', 'title' => 'Built around eligibility',       'body' => 'Review considers what you actually qualify for, so the options you see are grounded in reality.' ),
    array( 'icon' => 'ban',          'title' => 'No detail blasting',            'body' => 'Your request is reviewed where appropriate — not auctioned to a long list of companies.' ),
);

// "Quote options" dark-panel cards.
$hiw_quote = array(
    array( 'icon' => 'check',        'title' => 'Options where available', 'body' => 'You see quote options for the carriers a licensed professional can offer you.' ),
    array( 'icon' => 'clock',        'title' => 'At your own pace',        'body' => 'Review what comes back when you\'re ready. No countdowns, no pressure.' ),
    array( 'icon' => 'shield-check', 'title' => 'Grounded in review',      'body' => 'Options reflect eligibility and licensed review — not an instant automated guess.' ),
);

get_header( 'home' );
?>
<main id="main" class="page-how-it-works">

  <!-- ── Hero ─────────────────────────────────────────────────────── -->
  <section class="hiw-hero" aria-label="How Ensurance works">
    <span class="hiw-hero__glow" aria-hidden="true"></span>
    <div class="hiw-hero__inner">
      <nav class="hiw-breadcrumb" aria-label="Breadcrumb">
        <a href="<?php echo esc_url( home_url( '/' ) ); ?>">Home</a>
        <span aria-hidden="true">/</span>
        <span class="hiw-breadcrumb__current">How it works</span>
      </nav>
      <div class="hiw-hero__center">
        <p class="eyebrow">How it works</p>
        <h1 class="hiw-hero__title">How Ensurance Works.</h1>
        <p class="hiw-hero__sub">Start one guided request. Ensurance organizes your details so licensed agents, agencies, or approved insurance partners can review available carriers and provide quote options where available.</p>
        <div class="hero-actions">
          <a class="btn btn-primary btn--lg" href="<?php echo $start_url; ?>" data-track="cta_click_start_auto_quote_request" data-cta-text="Start your request" data-page-type="how_it_works">Start your request <?php echo wp_kses( ensurance_home_icon( 'arrow-right', 18 ), $ensurance_svg_allowed ); ?></a>
          <a class="btn btn-ghost btn--lg" href="#process">See the process</a>
        </div>
      </div>
    </div>
  </section>

  <!-- ── Timeline (the centerpiece, scroll-revealed) ──────────────── -->
  <section class="section" id="process" aria-label="Start with one guided request">
    <div class="section-head section-head--left">
      <p class="eyebrow">Start here</p>
      <h2>Start with one guided request.</h2>
      <p>A controlled process from start to review — four clear steps, so you always know where you are and what happens next.</p>
    </div>
    <div class="timeline">
      <?php foreach ( $hiw_steps as $i => $step ) : $right = ( $i % 2 === 0 ); ?>
      <div class="t-row">
        <div class="t-cell">
          <?php if ( ! $right ) : ?>
          <article class="card t-card">
            <span class="t-card__icon"><?php echo wp_kses( ensurance_home_icon( $step['icon'], 20 ), $ensurance_svg_allowed ); ?></span>
            <h3><?php echo esc_html( $step['title'] ); ?></h3>
            <p><?php echo esc_html( $step['body'] ); ?></p>
          </article>
          <?php endif; ?>
        </div>
        <div class="t-node"><span><?php echo esc_html( sprintf( '%02d', $i + 1 ) ); ?></span></div>
        <div class="t-cell">
          <?php if ( $right ) : ?>
          <article class="card t-card">
            <span class="t-card__icon"><?php echo wp_kses( ensurance_home_icon( $step['icon'], 20 ), $ensurance_svg_allowed ); ?></span>
            <h3><?php echo esc_html( $step['title'] ); ?></h3>
            <p><?php echo esc_html( $step['body'] ); ?></p>
          </article>
          <?php endif; ?>
        </div>
      </div>
      <?php endforeach; ?>
    </div>
    <div class="callout" role="note">
      <span class="callout__icon"><?php echo wp_kses( ensurance_home_icon( 'clock', 20 ), $ensurance_svg_allowed ); ?></span>
      <div>
        <p class="callout__title">Availability varies</p>
        <p class="callout__body">Availability, eligibility, carrier participation, and licensed professional review determine which quote options may be available.</p>
      </div>
    </div>
  </section>

  <!-- ── After you start ──────────────────────────────────────────── -->
  <section class="section reveal" id="after" aria-label="What happens after you start">
    <div class="hiw-two-col">
      <div>
        <div class="section-head section-head--left" style="max-width:420px;">
          <p class="eyebrow">After you start</p>
          <h2>What happens after you start.</h2>
          <p>Once your request is structured, it moves into licensed review — not into an automated list.</p>
        </div>
        <a class="hiw-textlink" href="<?php echo esc_url( home_url( '/trust-center' ) ); ?>" data-track="trust_privacy_click" data-cta-text="Trust and privacy" data-page-type="how_it_works">Trust and privacy <?php echo wp_kses( ensurance_home_icon( 'arrow-right', 16 ), $ensurance_svg_allowed ); ?></a>
      </div>
      <div class="hiw-spots">
        <?php foreach ( $hiw_after as $row ) : ?>
        <div class="hiw-spot">
          <span class="hiw-spot__icon"><?php echo wp_kses( ensurance_home_icon( $row['icon'], 17 ), $ensurance_svg_allowed ); ?></span>
          <div>
            <p class="hiw-spot__title"><?php echo esc_html( $row['title'] ); ?></p>
            <p class="hiw-spot__body"><?php echo esc_html( $row['body'] ); ?></p>
          </div>
        </div>
        <?php endforeach; ?>
      </div>
    </div>
  </section>

  <!-- ── Why licensed review matters ──────────────────────────────── -->
  <section class="section reveal" id="why-review" aria-label="Why licensed review matters">
    <div class="section-head section-head--left">
      <p class="eyebrow">Licensed review</p>
      <h2>Why licensed review matters.</h2>
      <p>Real coverage decisions deserve a real, licensed professional — not an instant guess.</p>
    </div>
    <div class="hiw-why-grid">
      <?php foreach ( $hiw_why as $tile ) : ?>
      <article class="card">
        <div class="hiw-why">
          <span class="hiw-why__icon"><?php echo wp_kses( ensurance_home_icon( $tile['icon'], 20 ), $ensurance_svg_allowed ); ?></span>
          <div>
            <h3><?php echo esc_html( $tile['title'] ); ?></h3>
            <p><?php echo esc_html( $tile['body'] ); ?></p>
          </div>
        </div>
      </article>
      <?php endforeach; ?>
    </div>
  </section>

  <!-- ── Quote options (dark panel) ───────────────────────────────── -->
  <section class="section reveal" id="quote-options" aria-label="What to expect from quote options">
    <div class="qo__panel">
      <span class="qo__glow" aria-hidden="true"></span>
      <div class="qo__head">
        <span class="qo__label">Quote options</span>
        <h2>What to expect from quote options.</h2>
        <p>Quote options are presented where coverage, eligibility, and carrier participation allow — and after licensed review. They are not guaranteed, and you're never pushed to decide.</p>
      </div>
      <div class="qo__grid">
        <?php foreach ( $hiw_quote as $card ) : ?>
        <div class="qo__card">
          <span class="qo__card-icon"><?php echo wp_kses( ensurance_home_icon( $card['icon'], 17 ), $ensurance_svg_allowed ); ?></span>
          <p class="qo__card-title"><?php echo esc_html( $card['title'] ); ?></p>
          <p class="qo__card-body"><?php echo esc_html( $card['body'] ); ?></p>
        </div>
        <?php endforeach; ?>
      </div>
    </div>
  </section>

  <!-- ── FAQ ──────────────────────────────────────────────────────── -->
  <section class="faq reveal" id="faq" aria-label="Questions before you begin">
    <div class="faq__head">
      <p class="eyebrow">Questions before you begin</p>
      <h2>Questions before you begin.</h2>
    </div>
    <div class="faq-list">
      <details open>
        <summary>What happens after I start a request?</summary>
        <p>Your details are organized into one structured request. From there, licensed agents, agencies, or approved insurance partners can review available carriers and follow up with quote options where available.</p>
      </details>
      <details>
        <summary>Who reviews my request?</summary>
        <p>Licensed agents, agencies, or approved insurance partners review your structured request. A licensed professional — not an automated list — decides which available carrier options may fit.</p>
      </details>
      <details>
        <summary>Can one request help me access multiple carrier options?</summary>
        <p>Often, yes. A licensed professional can review multiple available carriers from your single structured request, so you don't have to repeat the same details across separate forms.</p>
      </details>
      <details>
        <summary>Are quote options guaranteed?</summary>
        <p>No. Availability, eligibility, carrier participation, and licensed professional review determine which quote options may be available to you.</p>
      </details>
      <details>
        <summary>Is Ensurance a quote-comparison site?</summary>
        <p>No. Ensurance isn't an instant quote-comparison engine. It structures one request for licensed review, rather than auctioning your details for automated price lists.</p>
      </details>
      <details>
        <summary>Will my information be sent everywhere?</summary>
        <p>No. Your structured request is shared for licensed review where appropriate. It isn't blasted out to a long list of companies.</p>
      </details>
    </div>
  </section>

  <!-- ── Final CTA ────────────────────────────────────────────────── -->
  <section class="final-cta reveal" id="start" aria-label="Start your request">
    <div class="final-card">
      <h2>Start your request with clearer steps.</h2>
      <p>One guided request. A structured path. Licensed review of available carrier options — with quote options where available.</p>
      <div class="hero-actions">
        <a class="btn btn-reversed btn--lg" href="<?php echo $start_url; ?>" data-track="cta_click_start_auto_quote_request" data-cta-text="Start your request" data-page-type="how_it_works">Start your request <?php echo wp_kses( ensurance_home_icon( 'arrow-right', 18 ), $ensurance_svg_allowed ); ?></a>
      </div>
    </div>
  </section>

</main>
<?php get_footer( 'home' ); ?>
