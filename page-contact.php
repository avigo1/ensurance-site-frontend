<?php
/**
 * Template Name: Contact (Marketing)
 *
 * /contact — built to match the "Ensurance Contact" standalone redesign
 * (Calm Intelligence). A calm way to reach a real person: a centered intro
 * with trust cues, a single message-form card, a "no phone tree" callout,
 * and a short FAQ.
 *
 * Follows the same self-contained pattern as the quote-request pages: it
 * uses the homepage chrome (get_header('home') / get_footer('home')) and
 * shares assets/home.css + assets/home.js for tokens, chrome and base
 * components (buttons, the eyebrow, trust cues, the FAQ accordion). The
 * page-specific layout — the intro, the form card, the callout — lives in
 * assets/contact.css (all classes prefixed `.ct-`), with the scroll-reveal
 * motion in assets/contact.js. Both are enqueued and isolated from the
 * shared marketing bundle in functions.php (ensurance_contact_assets),
 * scoped to this template only, so no other page is affected.
 *
 * The message form submits via fetch() (assets/contact.js) to the REST
 * endpoint POST /wp-json/ensurance/v1/contact (ensurance_contact_handle in
 * functions.php), which stores every message as a private Contact Message
 * post and emails support@ensurance.com through WP Mail SMTP. On success the
 * card swaps to the design's inline confirmation state. Spam is handled by
 * the visually-hidden honeypot below plus a time trap and per-IP rate limit
 * in the handler — no nonce, because SG Optimizer full-page caching would
 * serve stale nonces to logged-out visitors.
 *
 * SEO: title / meta description / canonical / robots are owned by Yoast and
 * emitted through wp_head(); this template outputs none of them. The
 * ContactPage + BreadcrumbList and FAQPage JSON-LD below are shipped here
 * (Yoast does not emit them for this page); the FAQPage schema is built from
 * the same $ct_faq array that renders the visible FAQ accordion, so the two
 * can never drift.
 */

/**
 * Inline Lucide icon renderer (shared with the other Calm Intelligence page
 * templates via the function_exists guard). Only one page template renders
 * per request, so this copy carries only the glyphs the Contact page needs.
 * Paths from Lucide (ISC license).
 */
if ( ! function_exists( 'ensurance_home_icon' ) ) {
    function ensurance_home_icon( $name, $size = 20 ) {
        $icons = array(
            'user'        => '<path d="M19 21v-2a4 4 0 0 0-4-4H9a4 4 0 0 0-4 4v2"/><circle cx="12" cy="7" r="4"/>',
            'clock'       => '<circle cx="12" cy="12" r="10"/><polyline points="12 6 12 12 16 14"/>',
            'lock'        => '<rect width="18" height="11" x="3" y="11" rx="2"/><path d="M7 11V7a5 5 0 0 1 10 0v4"/>',
            'arrow-right' => '<path d="M5 12h14"/><path d="m12 5 7 7-7 7"/>',
            'check'       => '<path d="M20 6 9 17l-5-5"/>',
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

// §Intro — trust cues (icon / label).
$ct_cues = array(
    array( 'user',  'Reviewed by our team' ),
    array( 'clock', '1 to 2 business day reply' ),
    array( 'lock',  'Not added to a marketing list from this form' ),
);

// §Form — "What's this about?" topics (value / label).
$ct_topics = array(
    array( '',        'A general question' ),
    array( 'request', 'About a request I started' ),
    array( 'agent',   'I am an agent or agency' ),
    array( 'press',   'Press or media' ),
    array( 'privacy', 'A privacy request' ),
);

// §FAQ — also feeds the FAQPage JSON-LD below.
$ct_faq = array(
    array( 'How fast will I hear back?', 'Our team reviews messages and usually replies within one to two business days. Messages sent over a weekend are typically answered the next business day.' ),
    array( 'Can I ask about a request I already started?', 'Yes. Choose “About a request I started” and tell us what you need. We can help you understand the next step or pick up where you left off.' ),
    array( 'I am an agent or agency. Is this the right place?', 'Yes. Select “I am an agent or agency” and we can route your message to the right team. You can also review the For Agents page first.' ),
    array( 'Will contacting Ensurance sign me up for anything?', 'No. This form is used to respond to your message. It does not add you to a marketing list or start a quote request.' ),
    array( 'How do I make a privacy request?', 'Choose “A privacy request” and tell us what you would like to access, update, or delete. You can also review the full details in the Privacy Policy.' ),
);

// --- Per-page schema: ContactPage + BreadcrumbList, and FAQPage built from
// --- $ct_faq so it mirrors the visible FAQ.
add_action( 'wp_head', function () use ( $ct_faq ) {
    $url   = home_url( '/contact' );
    $flags = JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE;

    $graph = array(
        '@context' => 'https://schema.org',
        '@graph'   => array(
            array(
                '@type'       => 'ContactPage',
                '@id'         => $url,
                'url'         => $url,
                'name'        => 'Contact Ensurance | Insurance Quote Request Help',
                'description' => 'Send Ensurance a message about a guided insurance request, agent access, press, or privacy. Our team reviews every message. Contacting Ensurance does not start a quote request.',
            ),
            array(
                '@type'           => 'BreadcrumbList',
                'itemListElement' => array(
                    array( '@type' => 'ListItem', 'position' => 1, 'name' => 'Home', 'item' => home_url( '/' ) ),
                    array( '@type' => 'ListItem', 'position' => 2, 'name' => 'Contact', 'item' => $url ),
                ),
            ),
        ),
    );
    echo '<script type="application/ld+json">' . wp_json_encode( $graph, $flags ) . '</script>' . "\n";

    $entities = array();
    foreach ( $ct_faq as $f ) {
        $entities[] = array(
            '@type'          => 'Question',
            'name'           => $f[0],
            'acceptedAnswer' => array( '@type' => 'Answer', 'text' => $f[1] ),
        );
    }
    $schema = array(
        '@context'   => 'https://schema.org',
        '@type'      => 'FAQPage',
        '@id'        => $url . '#faq',
        'mainEntity' => $entities,
    );
    echo '<script type="application/ld+json">' . wp_json_encode( $schema, $flags ) . '</script>' . "\n";
}, 20 );

get_header( 'home' );
?>
<main id="main" class="page-contact">

  <!-- ── Intro (centered, trust cues) ─────────────────────────────── -->
  <section class="ct-intro reveal" aria-label="Contact Ensurance">
    <span class="ct-intro__glow ct-intro__glow--a" aria-hidden="true"></span>
    <span class="ct-intro__glow ct-intro__glow--b" aria-hidden="true"></span>
    <div class="ct-intro__inner">
      <span class="eyebrow">Contact</span>
      <h1 class="ct-intro__title">Contact <span class="ct-accent">Ensurance</span>.</h1>
      <p class="ct-intro__sub">Questions about a guided request, agent access, press, or privacy? Send a message and our team will review it. We usually reply within one to two business days.</p>
      <div class="ct-cues">
        <?php foreach ( $ct_cues as $cue ) : ?>
        <span class="trust-cue"><?php echo wp_kses( ensurance_home_icon( $cue[0], 16 ), $ensurance_svg_allowed ); ?><?php echo esc_html( $cue[1] ); ?></span>
        <?php endforeach; ?>
      </div>
    </div>
  </section>

  <!-- ── Message form (fetch() → /wp-json/ensurance/v1/contact) ───── -->
  <section class="ct-main reveal" aria-label="Send us a message">
    <form class="ct-form" method="post" novalidate data-endpoint="<?php echo esc_url( rest_url( 'ensurance/v1/contact' ) ); ?>">
      <h2 class="ct-form__title">Send us a message</h2>
      <p class="ct-form__sub">Tell us what you need so we can route your message to the right team.</p>
      <div class="ct-fields">
        <div class="ct-field" data-field="ct_name">
          <label class="ct-label" for="ct-name">Your name</label>
          <input class="ct-input" type="text" id="ct-name" name="ct_name" placeholder="e.g. Jordan Ellis" autocomplete="name" aria-describedby="ct-name-error">
          <p class="ct-error" id="ct-name-error" hidden>Add your name so we know who we are replying to.</p>
        </div>
        <div class="ct-field" data-field="ct_email">
          <label class="ct-label" for="ct-email">Email</label>
          <input class="ct-input" type="email" inputmode="email" id="ct-email" name="ct_email" placeholder="e.g. you@email.com" autocomplete="email" aria-describedby="ct-email-help ct-email-error">
          <p class="ct-help" id="ct-email-help">We use this to reply to your message. This form does not add you to a marketing list.</p>
          <p class="ct-error" id="ct-email-error" hidden>That email does not look right. Please check it and try again.</p>
        </div>
        <div class="ct-field" data-field="ct_topic">
          <label class="ct-label" for="ct-topic">What is this about?</label>
          <div class="ct-select-wrap">
            <select class="ct-select" id="ct-topic" name="ct_topic" aria-describedby="ct-topic-help">
              <?php foreach ( $ct_topics as $topic ) : ?>
              <option value="<?php echo esc_attr( $topic[0] ); ?>"><?php echo esc_html( $topic[1] ); ?></option>
              <?php endforeach; ?>
            </select>
          </div>
          <p class="ct-help" id="ct-topic-help">Helps us route your message to the right team.</p>
        </div>
        <div class="ct-field" data-field="ct_message">
          <label class="ct-label" for="ct-message">Your message</label>
          <textarea class="ct-textarea" id="ct-message" name="ct_message" placeholder="What can we help you with?" aria-describedby="ct-message-error"></textarea>
          <p class="ct-error" id="ct-message-error" hidden>Add enough detail so we can understand your question and respond clearly.</p>
        </div>
        <!-- Honeypot: hidden from people (and screen readers), filled by bots. -->
        <div class="ct-hp" aria-hidden="true">
          <label for="ct-company">Company</label>
          <input type="text" id="ct-company" name="ct_company" tabindex="-1" autocomplete="off">
        </div>
        <input type="hidden" name="ct_elapsed" value="">
        <p class="ct-form__fail" role="alert" hidden></p>
        <div class="ct-actions">
          <button class="btn btn-primary btn--lg" type="submit" data-track="contact_submit_click" data-cta-text="Send message" data-page-type="contact"><span data-ct-label>Send message</span> <?php echo wp_kses( ensurance_home_icon( 'arrow-right', 18 ), $ensurance_svg_allowed ); ?></button>
          <span class="ct-privacy"><?php echo wp_kses( ensurance_home_icon( 'lock', 14 ), $ensurance_svg_allowed ); ?> Your message is used to respond to your request.</span>
        </div>
      </div>
    </form>

    <!-- Success state (design parity) — contact.js reveals this and fills in
         the first name + email after a successful submission. -->
    <div class="ct-success" hidden>
      <span class="ct-success__badge"><?php echo wp_kses( ensurance_home_icon( 'check', 26 ), $ensurance_svg_allowed ); ?></span>
      <h2 class="ct-success__title">Thank you. Your message has been received.</h2>
      <p class="ct-success__body">Our team will review your message and reply by email, usually within one to two business days. This form does not add you to a marketing list.</p>
      <div class="ct-success__actions">
        <button class="btn btn-ghost" type="button" data-ct-reset>Send another message</button>
      </div>
    </div>

    <div class="ct-callout" role="note">
      <span class="ct-callout__icon"><?php echo wp_kses( ensurance_home_icon( 'user', 18 ), $ensurance_svg_allowed ); ?></span>
      <div>
        <p class="ct-callout__title">Online first, with human support when needed.</p>
        <p class="ct-callout__body">Ensurance is online first. When something needs support, our team can step in and respond through the contact details you provide.</p>
      </div>
    </div>
  </section>

  <!-- ── FAQ (reuses .faq / .faq-list from home.css) ──────────────── -->
  <section class="faq ct-faq reveal" id="faq" aria-label="Questions before you write">
    <div class="faq__head">
      <span class="eyebrow">Before you write</span>
      <h2>A few quick answers.</h2>
    </div>
    <div class="faq-list">
      <?php foreach ( $ct_faq as $i => $f ) : ?>
      <details<?php echo 0 === $i ? ' open' : ''; ?>>
        <summary><?php echo esc_html( $f[0] ); ?></summary>
        <p><?php echo esc_html( $f[1] ); ?></p>
      </details>
      <?php endforeach; ?>
    </div>
  </section>

</main>
<?php get_footer( 'home' ); ?>
