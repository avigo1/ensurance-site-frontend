<?php
/**
 * Template Name: Auto Insurance Quote (Marketing)
 *
 * /auto-insurance-quote — the request/intake page the whole site funnels into
 * (homepage hero, header nav CTA, mobile sticky CTA, coverage cards and the
 * /auto-insurance-quote-request landing all point here). Rebuilt to match the
 * "Ensurance Start Your Request" standalone redesign (Calm Intelligence): a
 * focused, form-first page — centered intro, the framed guided-request form
 * surface, a trust-cue row, the "you're in control" callout, the three-step
 * "what happens next" row, and the closing trust band.
 *
 * Uses the homepage chrome (get_header('home') / get_footer('home')) and shares
 * assets/home.css + assets/home.js for tokens, chrome and base components
 * (buttons, the eyebrow, icon boxes, trust cues). The page-specific layout lives
 * in assets/auto-insurance-quote.css, with the scroll-reveal motion in
 * assets/auto-insurance-quote.js. Both are enqueued and isolated from the shared
 * marketing bundle in functions.php (ensurance_auto_insurance_quote_assets),
 * scoped to this template only.
 *
 * FORM SLOT: the design ships a framed placeholder where the guided request form
 * belongs. The actual form is NOT wired in yet — see the `.sq-formslot` block
 * below for the single spot to drop it (e.g. echo do_shortcode('[lead_page]') or
 * the /start wizard) without touching the surrounding chrome.
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
    array( 'lock',  'Not blasted to a list' ),
    array( 'user',  'Controlled follow-up' ),
);

// §What happens next — three steps (icon / title / body).
$sq_next = array(
    array( 'sparkles', 'A few guided questions', 'Answer short questions about your location, vehicle, coverage needs, and contact preferences.' ),
    array( 'user',     'Your request is organized', 'Ensurance organizes your details so your auto quote request is clearer before review.' ),
    array( 'message',  'Move toward quote options', 'Where available, a licensed agent, agency, or approved partner may follow up based on your request and contact preference.' ),
);

// §Trust band — three protections (icon / title / body).
$sq_trust = array(
    array( 'shield-check', 'Prepared for licensed review', 'Your request is organized so it can be reviewed by a licensed agent, agency, or approved partner where available.' ),
    array( 'lock',         'Your details are handled carefully', 'Your request is handled through a more controlled process designed to reduce unnecessary exposure and unwanted contact.' ),
    array( 'file-text',    'No commitment to start',       'Starting a request is free and does not require you to buy coverage. You review your next step and decide.' ),
);

get_header( 'home' );
?>
<main id="main" class="page-auto-quote">

  <!-- ── Request (centered intro + framed form slot + trust cues) ──── -->
  <section class="sq-request reveal" aria-label="Start your request">
    <span class="sq-request__glow sq-request__glow--a" aria-hidden="true"></span>
    <span class="sq-request__glow sq-request__glow--b" aria-hidden="true"></span>
    <div class="sq-request__inner">

      <div class="sq-request__intro">
        <span class="eyebrow">Start your auto quote request</span>
        <h1 class="sq-request__title">Start your auto quote request with more clarity.</h1>
        <p class="sq-request__sub">Answer a few guided questions, organize your details, and move toward quote options with less confusion and less unwanted contact. About three minutes.</p>
      </div>

      <!-- ── FORM SLOT ────────────────────────────────────────────────
           The 10-step "Auto Insurance Quote Request" form lives in this
           page's editor content as a self-contained Custom HTML block (it
           ships its own inline CSS/JS and posts to make.com). We render the
           page content here so the form stays the single source of truth in
           the WordPress editor — this template only frames it. The editor
           page should contain ONLY that form block: the intro copy and the
           "Secure Auto Insurance Quote Request" heading it used to carry are
           now provided by the hero above, so remove them in the editor to
           avoid duplication. -->
      <div class="sq-formslot">
        <?php
        while ( have_posts() ) :
            the_post();

            /* Render the page's editor content (the self-contained quote form
               Custom HTML block). The editor also carries trailing blocks after
               the form — a "Built for trust" line, an "A more trusted system…"
               heading and a paragraph — that we don't want inside the card. Trim
               everything after the form's own quote-form.js <script> so only the
               form (and its script) renders. We anchor on the LAST 'quote-form.js'
               (the real script tag, which sits after the form) — the form's
               leading documentation comment also mentions the filename, so a
               first-match search would cut the form off. Defensive: if the marker
               isn't found, the full content renders unchanged. */
            $aq_content = apply_filters( 'the_content', get_the_content() );
            $aq_marker  = strrpos( $aq_content, 'quote-form.js' );
            if ( false !== $aq_marker ) {
                $aq_end = strpos( $aq_content, '</script>', $aq_marker );
                if ( false !== $aq_end ) {
                    $aq_content = substr( $aq_content, 0, $aq_end + strlen( '</script>' ) );
                }
            }
            echo $aq_content; // Trusted, admin-authored page content (same as the_content()).
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
        <p class="sq-callout__title">You stay in control</p>
        <p class="sq-callout__body">Starting a request does not commit you to buy. Your details are handled through a controlled request process designed to reduce broad sharing, pressure, and unwanted contact.</p>
      </div>
    </div>
  </section>

  <!-- ── What happens next (three steps) ──────────────────────────── -->
  <section class="sq-next reveal" aria-label="What happens next">
    <div class="sq-next__head">
      <span class="eyebrow">What happens next</span>
      <h2>Three simple steps. Less quote chaos.</h2>
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
