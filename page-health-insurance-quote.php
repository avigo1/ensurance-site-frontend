<?php
/**
 * Template Name: Health Insurance Quote (Marketing)
 *
 * /health-insurance-quote — health intake page. An exact reuse of the
 * "Start Your Request" design built for /auto-insurance-quote (Calm
 * Intelligence): centered intro, the framed guided-request form surface, a
 * trust-cue row, the "you're in control" callout, the three-step "what happens
 * next" row, and the closing trust band. The ONLY difference from the auto
 * template is what fills the form slot — here it's the Ninja Forms
 * "Health Insurance Quote Request" form, placed in the page's editor
 * content (see FORM SLOT below).
 *
 * Uses the homepage chrome (get_header('home') / get_footer('home')) and shares
 * assets/home.css + assets/home.js for tokens, chrome and base components, plus
 * assets/auto-insurance-quote.css/.js for the page layout and scroll-reveal —
 * the same files the auto page loads, so the two pages stay visually identical
 * by construction. Enqueued via ensurance_health_insurance_quote_assets()
 * in functions.php, scoped to this template only.
 *
 * FORM SLOT: the editor content of this WordPress page should contain ONLY the
 * Ninja Forms embed — the shortcode [ninja_form id='7'] (Health Insurance
 * Quote Request) or the equivalent Ninja Forms block. This template renders
 * the page content inside the framed .sq-formslot card, so the form stays
 * swappable from the WordPress editor without touching this file.
 *
 * SEO: title / meta description / canonical / robots are owned by Yoast and
 * emitted through wp_head(); this template outputs none of them.
 */

/**
 * Inline Lucide icon renderer (shared with the other Calm Intelligence page
 * templates via the function_exists guard). Only one page template renders per
 * request, so this copy carries the full glyph set this page needs.
 * Paths from Lucide (ISC license).
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
            'message'      => '<path d="M7.9 20A9 9 0 1 0 4 16.1L2 22Z"/>',
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

// §Trust-cue row beneath the form.
$sq_cues = array(
    array( 'clock', 'About 3 minutes' ),
    array( 'lock',  'Never sold or blasted to a list' ),
    array( 'user',  'One agent, not a hundred calls' ),
);

// §What happens next — three steps (icon / title / body).
$sq_next = array(
    array( 'sparkles', 'A few guided questions', 'Short steps, one at a time — mostly taps, minimal typing.' ),
    array( 'user',     'One independent agent',  'We pair you with a single licensed agent who shops multiple carriers for you.' ),
    array( 'message',  'They reach out your way', 'By email, text, or call — only the channel you choose, usually within a business day.' ),
);

// §Trust band — three protections (icon / title / body).
$sq_trust = array(
    array( 'shield-check', 'Reviewed by a licensed agent', 'Your request goes to one independent agent — never auctioned to a long list of buyers.' ),
    array( 'lock',         'Your details stay private',    "We don't sell your information or blast it out. You choose how and when you're contacted." ),
    array( 'file-text',    'No commitment to start',       'Starting a request is free and never binds you to anything. You review options and decide.' ),
);

get_header( 'home' );
?>
<main id="main" class="page-auto-quote page-health-quote">

  <!-- ── Request (centered intro + framed form slot + trust cues) ──── -->
  <section class="sq-request reveal" aria-label="Start your request">
    <span class="sq-request__glow sq-request__glow--a" aria-hidden="true"></span>
    <span class="sq-request__glow sq-request__glow--b" aria-hidden="true"></span>
    <div class="sq-request__inner">

      <div class="sq-request__intro">
        <span class="eyebrow">Start your request</span>
        <h1 class="sq-request__title">Tell us what you need. We'll handle the rest.</h1>
        <p class="sq-request__sub">A few guided questions, then we pair you with one independent agent who shops multiple carriers for you. About three minutes.</p>
      </div>

      <!-- ── FORM SLOT ────────────────────────────────────────────────
           Renders this page's editor content, which should contain ONLY
           the Ninja Forms embed for the Health Insurance Quote
           Request form ([ninja_form id='7'] or the Ninja Forms block).
           Unlike the auto template there is no content trimming — keep
           the editor page clean so nothing but the form lands in the
           card. -->
      <div class="sq-formslot">
        <?php
        while ( have_posts() ) :
            the_post();
            the_content();
        endwhile;
        ?>
      </div>

      <div class="sq-cues">
        <?php foreach ( $sq_cues as $cue ) : ?>
        <span class="trust-cue"><?php echo wp_kses( ensurance_home_icon( $cue[0], 16 ), $ensurance_svg_allowed ); ?><?php echo esc_html( $cue[1] ); ?></span>
        <?php endforeach; ?>
      </div>

    </div>
  </section>

  <!-- ── You're in control (brand callout) ────────────────────────── -->
  <section class="sq-control reveal" aria-label="You're in control">
    <div class="sq-callout" role="note">
      <span class="sq-callout__icon"><?php echo wp_kses( ensurance_home_icon( 'shield-check', 20 ), $ensurance_svg_allowed ); ?></span>
      <div>
        <p class="sq-callout__title">You're always in control</p>
        <p class="sq-callout__body">We only share your request with the one agent you approve. Nothing gets blasted to a list, and nothing binds until you say so.</p>
      </div>
    </div>
  </section>

  <!-- ── What happens next (three steps) ──────────────────────────── -->
  <section class="sq-next reveal" aria-label="What happens next">
    <div class="sq-next__head">
      <span class="eyebrow">What happens next</span>
      <h2>Three quiet steps. No spam, no auction.</h2>
    </div>
    <div class="sq-next__grid">
      <?php foreach ( $sq_next as $i => $step ) : ?>
      <div class="sq-next__item">
        <span class="sq-next__badge">
          <?php echo wp_kses( ensurance_home_icon( $step[0], 20 ), $ensurance_svg_allowed ); ?>
          <span class="sq-next__num"><?php echo esc_html( sprintf( '%02d', $i + 1 ) ); ?></span>
        </span>
        <div>
          <p class="sq-next__title"><?php echo esc_html( $step[1] ); ?></p>
          <p class="sq-next__body"><?php echo esc_html( $step[2] ); ?></p>
        </div>
      </div>
      <?php endforeach; ?>
    </div>
  </section>

  <!-- ── Trust band ───────────────────────────────────────────────── -->
  <section class="sq-trust reveal" aria-label="How Ensurance protects your request">
    <div class="sq-trust__inner">
      <div class="sq-trust__grid">
        <?php foreach ( $sq_trust as $cue ) : ?>
        <div class="sq-trust__item">
          <span class="icon-box icon-box--accent"><?php echo wp_kses( ensurance_home_icon( $cue[0], 20 ), $ensurance_svg_allowed ); ?></span>
          <div>
            <p class="sq-trust__title"><?php echo esc_html( $cue[1] ); ?></p>
            <p class="sq-trust__body"><?php echo esc_html( $cue[2] ); ?></p>
          </div>
        </div>
        <?php endforeach; ?>
      </div>
    </div>
  </section>

</main>
<?php get_footer( 'home' ); ?>
