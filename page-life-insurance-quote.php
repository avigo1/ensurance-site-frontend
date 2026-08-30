<?php
/**
 * Template Name: Life Insurance Quote (Marketing)
 *
 * /life-insurance-quote — life insurance intake page. An exact reuse of the
 * "Start Your Request" design built for /auto-insurance-quote (Calm
 * Intelligence): centered intro, the framed guided-request form surface, a
 * trust-cue row, the "you're in control" callout, the three-step "what happens
 * next" row, and the closing trust band. The ONLY difference from the auto
 * template is what fills the form slot — here it's the Ninja Forms
 * "Life Insurance Quote Request" form, placed in the page's editor
 * content (see FORM SLOT below).
 *
 * Uses the homepage chrome (get_header('home') / get_footer('home')) and shares
 * assets/home.css + assets/home.js for tokens, chrome and base components, plus
 * assets/auto-insurance-quote.css/.js for the page layout and scroll-reveal —
 * the same files the auto page loads, so the two pages stay visually identical
 * by construction. Enqueued via ensurance_life_insurance_quote_assets()
 * in functions.php, scoped to this template only.
 *
 * FORM SLOT: the editor content of this WordPress page should contain ONLY the
 * Ninja Forms embed — the shortcode [ninja_form id='11'] (Life Insurance
 * Quote Request) or the equivalent Ninja Forms block. This template renders
 * the page content inside the framed .sq-formslot card, so the form stays
 * swappable from the WordPress editor without touching this file.
 *
 * SEO: meta description / canonical / robots are owned by Yoast and emitted
 * through wp_head(); this template outputs none of them. The <title> is the one
 * exception — see the title override below.
 */

/**
 * <title> override.
 *
 * The Yoast SEO title stored for this page was copied from the auto page and
 * still reads "SECURE Auto Insurance Quotes Online | …", so the life page ships
 * the wrong title. Force the correct one here.
 *
 * Yoast removes core's _wp_render_title_tag and prints its own title, so
 * 'wpseo_title' is the hook that actually decides the output. The
 * 'pre_get_document_title' filter is the fallback for when Yoast is inactive;
 * it runs at 99 so it beats anything hooked at the default priority.
 */
add_filter( 'wpseo_title', function () {
    return 'Life Insurance Quote Help & Coverage Guide | Ensurance';
} );

add_filter( 'pre_get_document_title', function () {
    return 'Life Insurance Quote Help & Coverage Guide | Ensurance';
}, 99 );

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
    array( 'lock',  'Controlled request process' ),
    array( 'user',  'Controlled licensed review' ),
);

// §What happens next — three steps (icon / title / body).
$sq_next = array(
    array( 'sparkles', 'A few guided questions', 'Short steps, one at a time — mostly taps, minimal typing.' ),
    array( 'user',     'Your request is organized',  'Ensurance organizes your details so your life insurance request is clearer before review.' ),
    array( 'message',  'Move toward coverage options', 'Where available, your request may move into controlled licensed review and follow-up based on your contact preference.' ),
);

// §Trust band — three protections (icon / title / body).
$sq_trust = array(
    array( 'shield-check', 'Prepared for licensed review', 'Your request is organized for controlled licensed review where available.' ),
    array( 'lock',         'Your details are handled carefully',    "Your request is handled through a controlled process designed to reduce unnecessary exposure and unwanted contact." ),
    array( 'file-text',    'No commitment to start',       'Starting a request is free and does not require you to buy coverage. You review your next step and decide.' ),
);

get_header( 'home' );
?>
<main id="main" class="page-auto-quote page-life-quote">

  <!-- ── Life request ─────────────────────────────────────────────── -->
  <?php
  $ensurance_life_request_context = array(
      'coverage_lock' => 'life',
      'partner_id'    => '',
      'referral_code' => '',
      'eyebrow'       => 'Life insurance request',
      'title'         => 'Start your life insurance request.',
      'subcopy'       => 'Share a few focused details about the coverage you are looking for. Ensurance keeps your request organized and your contact information protected while it moves toward licensed review where available.',
  );
  get_template_part( 'template-parts/non-auto-request-form', null, $ensurance_life_request_context );
  ?>

  <!-- ── Life insurance basics (consumer education) ───────────────── -->
  <section class="sq-next reveal" aria-labelledby="life-basics-title">
    <div class="sq-next__head">
      <span class="eyebrow">Life insurance basics</span>
      <h2 id="life-basics-title">What should you understand before choosing life insurance?</h2>
      <p>Life insurance can help provide financial support to people who depend on you. The right type and amount depend on your situation, budget, and how long the need for protection may last.</p>
    </div>
    <div class="sq-next__grid">
      <div class="sq-next__item">
        <span class="sq-next__badge"><?php echo wp_kses( ensurance_home_icon( 'file-text', 20 ), $ensurance_svg_allowed ); ?><span class="sq-next__num">01</span></span>
        <div>
          <p class="sq-next__title">Term and permanent policies work differently</p>
          <p class="sq-next__body">Term life insurance covers a defined period and generally does not build cash value. Permanent or cash-value policies can include whole life, universal life, or variable life, with different costs, features, and long-term considerations.</p>
        </div>
      </div>
      <div class="sq-next__item">
        <span class="sq-next__badge"><?php echo wp_kses( ensurance_home_icon( 'user', 20 ), $ensurance_svg_allowed ); ?><span class="sq-next__num">02</span></span>
        <div>
          <p class="sq-next__title">Think about the financial responsibilities you want covered</p>
          <p class="sq-next__body">Common questions include how much income others depend on, debts or a mortgage, education costs, final expenses, existing assets or insurance, and how long financial support may be needed.</p>
        </div>
      </div>
      <div class="sq-next__item">
        <span class="sq-next__badge"><?php echo wp_kses( ensurance_home_icon( 'shield-check', 20 ), $ensurance_svg_allowed ); ?><span class="sq-next__num">03</span></span>
        <div>
          <p class="sq-next__title">Mortgage protection is one possible need, not a separate replacement for broader planning</p>
          <p class="sq-next__body">A mortgage may be one financial obligation you want life insurance to help address. Your broader need may also include income replacement, dependents, debts, education, or final expenses.</p>
        </div>
      </div>
      <div class="sq-next__item">
        <span class="sq-next__badge"><?php echo wp_kses( ensurance_home_icon( 'user', 20 ), $ensurance_svg_allowed ); ?><span class="sq-next__num">04</span></span>
        <div>
          <p class="sq-next__title">Beneficiaries should reflect your current wishes</p>
          <p class="sq-next__body">A beneficiary is the person or entity designated to receive policy proceeds. Life changes such as marriage, divorce, births, adoptions, or deaths can be reasons to review beneficiary designations.</p>
        </div>
      </div>
      <div class="sq-next__item">
        <span class="sq-next__badge"><?php echo wp_kses( ensurance_home_icon( 'shield-check', 20 ), $ensurance_svg_allowed ); ?><span class="sq-next__num">05</span></span>
        <div>
          <p class="sq-next__title">Do not cancel existing coverage before replacement coverage is in force</p>
          <p class="sq-next__body">If you already have life insurance, NAIC advises keeping the current policy until you have received and reviewed the new policy. Replacing coverage can have costs and should be considered carefully.</p>
        </div>
      </div>
    </div>
    <p class="sq-next__source"><a href="https://content.naic.org/consumer/life-insurance.htm" rel="noopener noreferrer">NAIC consumer life insurance guidance</a> &middot; <a href="https://content.naic.org/article/consumer-insight-what-type-life-insurance-right-you" rel="noopener noreferrer">NAIC: What type of life insurance is right for you?</a></p>
  </section>

  <!-- ── You're in control (brand callout) ────────────────────────── -->
  <section class="sq-control reveal" aria-label="You're in control">
    <div class="sq-callout" role="note">
      <span class="sq-callout__icon"><?php echo wp_kses( ensurance_home_icon( 'shield-check', 20 ), $ensurance_svg_allowed ); ?></span>
      <div>
        <p class="sq-callout__title">You're always in control</p>
        <p class="sq-callout__body">Your protected request moves through the applicable controlled-access process supported by CATE™, the Controlled Access Trust Engine, with eligible licensed review where available. Starting a request does not commit you to buy coverage.</p>
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
