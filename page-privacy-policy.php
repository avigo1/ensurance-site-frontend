<?php
/**
 * Template Name: Privacy Policy (Marketing)
 *
 * /privacy-policy — the formal Privacy Policy rebuilt as a Calm Intelligence
 * legal document (same document pattern as /trust-center): a light hero with
 * breadcrumb, badge and effective/updated dates, a sticky table-of-contents
 * with scroll-spy, and the thirteen numbered policy sections. The legal text
 * is ported verbatim from the previous Gutenberg page (page ID 3) — this
 * template is the single source of truth for the policy copy now; the block
 * content left in the database is ignored.
 *
 * Follows the same self-contained pattern as the other Calm Intelligence
 * pages: homepage chrome (get_header('home') / get_footer('home')) and shared
 * assets/home.css + home.js for tokens, chrome and buttons. The page-specific
 * document layout lives in assets/privacy-policy.css (all classes prefixed
 * `.pp-`), with the TOC scroll-spy in assets/privacy-policy.js. Both are
 * enqueued and isolated from the shared marketing bundle in functions.php
 * (ensurance_privacy_policy_assets), scoped to this template only.
 *
 * SEO: title / meta description / canonical / robots are owned by Yoast and
 * emitted through wp_head(); this template outputs none of them. The
 * WebPage + BreadcrumbList JSON-LD below is shipped here.
 */

/**
 * Inline Lucide icon renderer (shared with the other Calm Intelligence page
 * templates via the function_exists guard). Only one page template renders
 * per request, so this copy carries only the glyphs the policy page needs.
 * Paths from Lucide (ISC license).
 */
if ( ! function_exists( 'ensurance_home_icon' ) ) {
    function ensurance_home_icon( $name, $size = 20 ) {
        $icons = array(
            'lock'        => '<rect width="18" height="11" x="3" y="11" rx="2"/><path d="M7 11V7a5 5 0 0 1 10 0v4"/>',
            'file-text'   => '<path d="M15 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V7Z"/><path d="M14 2v4a2 2 0 0 0 2 2h4"/><path d="M10 9H8"/><path d="M16 13H8"/><path d="M16 17H8"/>',
            'arrow-right' => '<path d="M5 12h14"/><path d="m12 5 7 7-7 7"/>',
            'mail'        => '<rect width="20" height="16" x="2" y="4" rx="2"/><path d="m22 7-8.97 5.7a1.94 1.94 0 0 1-2.06 0L2 7"/>',
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

// --- Per-page schema: WebPage + BreadcrumbList. ---
add_action( 'wp_head', function () {
    $url   = home_url( '/privacy-policy' );
    $flags = JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE;

    $graph = array(
        '@context' => 'https://schema.org',
        '@graph'   => array(
            array(
                '@type'       => 'WebPage',
                '@id'         => $url,
                'url'         => $url,
                'name'        => 'Privacy Policy | Ensurance',
                'description' => 'How Ensurance.com collects, uses, shares, and protects your information — for consumers and licensed agents using our marketplace.',
            ),
            array(
                '@type'           => 'BreadcrumbList',
                'itemListElement' => array(
                    array( '@type' => 'ListItem', 'position' => 1, 'name' => 'Home', 'item' => home_url( '/' ) ),
                    array( '@type' => 'ListItem', 'position' => 2, 'name' => 'Privacy Policy', 'item' => $url ),
                ),
            ),
        ),
    );
    echo '<script type="application/ld+json">' . wp_json_encode( $graph, $flags ) . '</script>' . "\n";
}, 20 );

// Resolved destinations.
$pp_contact_url = esc_url( home_url( '/contact' ) );
$pp_trust_url   = esc_url( home_url( '/trust-center' ) );

// Table of contents — order mirrors the sections below.
$pp_toc = array(
    array( 'n' => '1',  'id' => 'definitions',        'label' => 'Definitions' ),
    array( 'n' => '2',  'id' => 'information',        'label' => 'Information we collect' ),
    array( 'n' => '3',  'id' => 'use-and-share',      'label' => 'How we use &amp; share information' ),
    array( 'n' => '4',  'id' => 'legal-risk',         'label' => 'Anticipating legal risk' ),
    array( 'n' => '5',  'id' => 'data-practices',     'label' => 'Specific data practices' ),
    array( 'n' => '6',  'id' => 'state-notices',      'label' => 'State-specific privacy notices' ),
    array( 'n' => '7',  'id' => 'cookies',            'label' => 'Cookies &amp; tracking technologies' ),
    array( 'n' => '8',  'id' => 'trust-positioning',  'label' => 'Competitive &amp; trust positioning' ),
    array( 'n' => '9',  'id' => 'security-retention', 'label' => 'Data security &amp; retention' ),
    array( 'n' => '10', 'id' => 'agent-accounts',     'label' => 'Account management for agents' ),
    array( 'n' => '11', 'id' => 'third-party-links',  'label' => 'Links to other websites' ),
    array( 'n' => '12', 'id' => 'changes',            'label' => 'Changes to this policy' ),
    array( 'n' => '13', 'id' => 'contact-us',         'label' => 'Contact us' ),
);

get_header( 'home' );
?>
<main id="main" class="page-privacy-policy">

  <!-- ── Hero (light document head) ───────────────────────────────── -->
  <section class="pp-hero" aria-label="Privacy Policy">
    <span class="pp-hero__glow" aria-hidden="true"></span>
    <div class="pp-hero__inner">
      <nav class="pp-breadcrumb" aria-label="Breadcrumb">
        <a href="<?php echo esc_url( home_url( '/' ) ); ?>">Home</a>
        <span aria-hidden="true">/</span>
        <span class="pp-breadcrumb__current">Privacy Policy</span>
      </nav>
      <div class="pp-hero__body">
        <span class="pp-hero__badge"><?php echo wp_kses( ensurance_home_icon( 'lock', 13 ), $ensurance_svg_allowed ); ?> Legal &middot; Privacy</span>
        <h1 class="pp-hero__title">Privacy Policy</h1>
        <p class="pp-hero__sub">Welcome to Ensurance.com (the &ldquo;Site&rdquo;), owned and operated by Ensurance Incorporated (&ldquo;Ensurance.com,&rdquo; &ldquo;we,&rdquo; &ldquo;us,&rdquo; or &ldquo;our&rdquo;). Ensurance.com operates a nationwide online insurance marketplace designed to connect Consumers (individuals seeking insurance information or assistance) with Agents (licensed insurance professionals or agencies).</p>
        <div class="pp-hero__meta">
          <span class="pp-meta-pill">Effective date: Aug 18, 2025</span>
          <span class="pp-meta-pill">Last updated: May 07, 2019</span>
        </div>
        <p class="pp-hero__fine">Prefer the plain-English overview first? <a href="<?php echo $pp_trust_url; ?>">Visit the Trust Center</a>.</p>
      </div>
    </div>
  </section>

  <!-- ── Document: sticky TOC + numbered sections ─────────────────── -->
  <div class="pp-doc">
    <nav class="pp-toc" aria-label="Contents">
      <p class="pp-toc__label">On this page</p>
      <?php foreach ( $pp_toc as $i => $t ) : ?>
      <a class="pp-toc__link<?php echo 0 === $i ? ' is-active' : ''; ?>" href="#<?php echo esc_attr( $t['id'] ); ?>" data-pp-toc="<?php echo esc_attr( $t['id'] ); ?>">
        <span class="pp-toc__n"><?php echo esc_html( $t['n'] ); ?></span>
        <span><?php echo wp_kses( $t['label'], array() ); ?></span>
      </a>
      <?php endforeach; ?>
    </nav>

    <div class="pp-doc__main">

      <!-- Intro -->
      <div class="pp-intro">
        <p class="pp-p">This Privacy Policy explains how we collect, use, share, and protect your information when you use our website, marketplace, and related services. It applies to both <strong>Consumers</strong> (individuals seeking insurance information or assistance) and <strong>Agents</strong> (licensed insurance professionals or agencies participating in our platform).</p>
        <p class="pp-p">By accessing or using Ensurance.com, you agree to this Privacy Policy. If you do not agree, please do not use our services.</p>
        <div class="pp-note" role="note">
          <span class="pp-note__icon"><?php echo wp_kses( ensurance_home_icon( 'file-text', 18 ), $ensurance_svg_allowed ); ?></span>
          <p class="pp-note__text">This is the formal Privacy Policy. For a plain-English explanation of how Ensurance approaches your insurance request, see the <a href="<?php echo $pp_trust_url; ?>">Trust Center</a>. To make a privacy request, <a href="<?php echo $pp_contact_url; ?>">contact us</a> and choose &ldquo;A privacy request.&rdquo;</p>
        </div>
      </div>

      <!-- §1 -->
      <section class="pp-section" id="definitions">
        <div class="pp-section__head">
          <span class="pp-section__n">1</span>
          <h2>Definitions</h2>
        </div>
        <ul class="pp-list">
          <li><strong>Consumer</strong> &ndash; An individual who visits Ensurance.com or submits information to be connected with licensed insurance agents.</li>
          <li><strong>Agent</strong> &ndash; A licensed insurance professional or agency listed on Ensurance.com and participating in our platform.</li>
          <li><strong>Lead Distribution</strong> &ndash; The process by which we share Consumer information with one or more Agents to help respond to an insurance inquiry.</li>
          <li><strong>Service Providers</strong> &ndash; Vendors who help operate our platform, store data, provide analytics, or support communications.</li>
        </ul>
      </section>

      <!-- §2 -->
      <section class="pp-section" id="information">
        <div class="pp-section__head">
          <span class="pp-section__n">2</span>
          <h2>Information We Collect</h2>
        </div>
        <p class="pp-p">We may collect the following categories of information, directly from you, from publicly available sources, or from third parties:</p>
        <h3 class="pp-sub">A. From Consumers</h3>
        <ul class="pp-list">
          <li>Name, mailing address, email address, and phone number</li>
          <li>Insurance interests or needs</li>
          <li>Demographic information, such as age or marital status (if provided)</li>
          <li>Any other details voluntarily submitted through forms, chat, or communications</li>
          <li>Technical data such as IP address, device information, browser type, pages visited, and referral links</li>
          <li>Information collected via cookies, web beacons, and other tracking technologies</li>
        </ul>
        <h3 class="pp-sub">B. From Agents</h3>
        <ul class="pp-list">
          <li>Name, agency name, mailing address, email address, and phone number</li>
          <li>Licensing and carrier appointment details</li>
          <li>Services offered, business description, and service areas</li>
          <li>Payment and billing information for subscription services</li>
          <li>Account login and profile details</li>
          <li>Technical and usage data similar to Consumer collection</li>
        </ul>
      </section>

      <!-- §3 -->
      <section class="pp-section" id="use-and-share">
        <div class="pp-section__head">
          <span class="pp-section__n">3</span>
          <h2>How We Use and Share Information</h2>
        </div>
        <p class="pp-p">Ensurance.com operates as a marketplace that connects <strong>Consumers</strong> (individuals seeking insurance information or assistance) with <strong>Agents</strong> (licensed insurance professionals and agencies). This matching process &mdash; referred to in this Privacy Policy as <strong>&ldquo;Lead Distribution&rdquo;</strong> &mdash; is a core function of our platform.</p>
        <p class="pp-p">When you submit your information through our website, you authorize us to use and share that information in the following ways:</p>
        <ul class="pp-list pp-list--titled">
          <li><strong>Connecting You With Agents</strong> We share the information you provide with one or more licensed insurance agents who may be able to assist you. This may include independent agents, agencies, or representatives of insurance carriers. The purpose of this sharing is to respond to your inquiry and help you explore coverage options &mdash; not for unrelated marketing.</li>
          <li><strong>Multiple Agent Referrals</strong> If the first agent we connect you with does not respond, or if we determine your needs may be better served by additional options, we may share your information with other qualified agents so you still receive assistance.</li>
          <li><strong>Independent Third Parties</strong> Agents are independent businesses with their own privacy policies and legal obligations. Once we share your information with an agent, their handling of your information is governed by their own privacy practices. Ensurance.com does not control and is not responsible for how any independent agent uses, stores, or shares your information once it has been provided to them.</li>
          <li><strong>Service Providers</strong> We may share your information with trusted vendors who help us operate our website, verify licensing, process requests, store data, provide analytics, or deliver communications. These vendors are only permitted to use your information for the specific services they provide to us.</li>
          <li><strong>Legal and Compliance Requirements</strong> We may disclose your information if required by law, regulation, legal process, or to protect our rights, property, or safety, or that of our users, agents, or the public.</li>
          <li><strong>Business Transfers</strong> In the event of a merger, acquisition, reorganization, sale of assets, or bankruptcy, your information may be transferred to a successor entity as part of that transaction.</li>
        </ul>
        <h3 class="pp-sub">Important Disclaimers</h3>
        <ul class="pp-list">
          <li>Ensurance.com <strong>does not sell your personal information for unrelated marketing purposes</strong> outside the scope of connecting you with insurance agents.</li>
          <li>By submitting your information, you acknowledge that <strong>Lead Distribution</strong> is central to our service, and you consent to this sharing as described.</li>
          <li>We <strong>do not guarantee or endorse</strong> any agent&rsquo;s services, the accuracy of their statements, or the outcome of your interactions with them.</li>
        </ul>
        <p class="pp-p">You are encouraged to review an agent&rsquo;s own privacy policy before sharing sensitive information directly with them.</p>
      </section>

      <!-- §4 -->
      <section class="pp-section" id="legal-risk">
        <div class="pp-section__head">
          <span class="pp-section__n">4</span>
          <h2>Anticipating Legal Risk</h2>
        </div>
        <p class="pp-p">Ensurance.com recognizes the importance of transparency to reduce legal exposure. Our platform:</p>
        <ul class="pp-list">
          <li>Hosts <strong>public, user-generated content</strong>, such as agency profiles, reviews, and uploaded media that may contain personal information.</li>
          <li>May collect <strong>behavioral and technical data</strong>, including search activity, location information, device identifiers, and engagement metrics.</li>
          <li>Provides <strong>advertising opportunities</strong> and may share data with third-party partners in compliance with applicable privacy laws.</li>
        </ul>
        <p class="pp-p">We maintain a broad and detailed privacy framework to:</p>
        <ul class="pp-list">
          <li>Meet legal obligations in multiple jurisdictions.</li>
          <li>Limit potential disputes or claims.</li>
          <li>Reduce the risk of regulatory penalties by providing full disclosure of collection, use, and sharing practices.</li>
        </ul>
      </section>

      <!-- §5 -->
      <section class="pp-section" id="data-practices">
        <div class="pp-section__head">
          <span class="pp-section__n">5</span>
          <h2>Specific Data Practices</h2>
        </div>
        <p class="pp-p">We collect and process:</p>
        <ul class="pp-list">
          <li><strong>Account Information</strong> &ndash; names, contact details, licensing details, agency information.</li>
          <li><strong>Public Content</strong> &ndash; reviews, ratings, uploaded media, and profile content visible to the public.</li>
          <li><strong>Contact Information</strong> &ndash; data used for invitations or referrals.</li>
          <li><strong>Communications</strong> &ndash; messages sent through the platform, inquiries, and support interactions.</li>
          <li><strong>Transactional Data</strong> &ndash; lead requests, subscription purchases, payment details.</li>
          <li><strong>Location &amp; Device Information</strong> &ndash; IP address, geolocation (if enabled), browser type, device identifiers, operating system.</li>
          <li><strong>Professional Information</strong> &ndash; agency affiliation, licensing status, professional certifications.</li>
          <li><strong>Sensitive Personal Information</strong> &ndash; voluntarily provided and relevant to platform use.</li>
          <li><strong>Reviews &amp; Moderation Data</strong> &ndash; information related to review submissions and safety checks (see <strong>Section 5.1</strong> for details).</li>
        </ul>
        <p class="pp-p">We use this data for service delivery, personalization, advertising, analytics, fraud prevention, and compliance. We may share it with trusted third parties such as advertisers, content partners, service providers, and government authorities when lawful.</p>

        <h3 class="pp-sub">5.1 Reviews &amp; Moderation Data</h3>
        <p class="pp-p"><strong>What we collect when you submit a review.</strong> We collect the information you provide (e.g., name, email, rating, review text, photos) and technical data associated with the submission (e.g., IP address, approximate location derived from IP, device/browser details, timestamp, referral URL). We also create moderation records such as rule flags (spam/bot/phishing/PII), redaction notes, publish/decline decisions, and dispute correspondence.</p>
        <p class="pp-p"><strong>How do we use this information?</strong></p>
        <ul class="pp-list">
          <li>Operate and display the review feature</li>
          <li>Detect and prevent spam, bots, phishing, impersonation, and other abuse.</li>
          <li>Enforce our Review Policy (including redacting private information and tagging rule violations).</li>
          <li>Communicate with you about your submission (e.g., requests for clarification).</li>
          <li>Maintain security, improve moderation accuracy, and generate de-identified or aggregated insights about feature usage.</li>
        </ul>
        <p class="pp-p"><strong>Public nature of reviews.</strong> Published reviews are visible to anyone who visits the Site (including Agents). Please do not include private information such as policy/claim numbers, phone/email, addresses, SSN/DOB, or payment data. We may redact such data and mark changes as <strong>[moderator redaction]</strong>.</p>
        <p class="pp-p"><strong>Sources and categories.</strong> Sources include you (directly), your device/browser (automatically), and our hosting/anti-abuse providers (as service providers). Categories include identifiers (name, email, IP), internet/network activity (device, user agent, referral), coarse geolocation (from IP), audio/visual content (if uploaded), and inferences generated solely for abuse detection (e.g., spam risk scores).</p>
        <p class="pp-p"><strong>Sharing and service providers.</strong> We may disclose review and moderation data to vetted <strong>service providers</strong> (hosting, storage, anti-spam/moderation, email delivery) under contracts limiting their use to our instructions. We may also disclose information as required by law, to enforce our policies, or to protect rights and safety. We <strong>do not sell</strong> review or moderation data and <strong>do not share it for cross-context behavioral advertising</strong>.</p>
        <p class="pp-p"><strong>Automated tools &amp; human review.</strong> We use automated tools to flag likely spam/bot/phishing or policy issues; <strong>human moderators make the final decision</strong> in most cases. We do not make adverse decisions about you based solely on automated processing of review data.</p>
        <p class="pp-p"><strong>Retention.</strong> We retain published reviews while the related profile or page is live. Moderation records and audit logs may be kept <strong>up to 24 months after removal</strong> (or longer if required by law, to prevent abuse, or to resolve disputes). De-identified/aggregated data may be retained without a time limit.</p>
        <p class="pp-p"><strong>Your choices &amp; rights.</strong> You may request correction or removal of your review, or dispute a moderation decision, by contacting <strong>support@ensurance.com</strong> (include the page URL and details). State-specific rights can be exercised using the instructions in <strong>Section 6</strong> of this Privacy Policy.</p>
      </section>

      <!-- §6 -->
      <section class="pp-section" id="state-notices">
        <div class="pp-section__head">
          <span class="pp-section__n">6</span>
          <h2>State-Specific Privacy Notices</h2>
        </div>
        <p class="pp-p">If you are a resident of California, Colorado, Connecticut, Utah, Virginia, or other states with privacy laws, you may have additional rights under CCPA, CPRA, CPA, VCDPA, CTDPA, and UCPA, including:</p>
        <ul class="pp-list">
          <li>The right to know what personal information is collected, used, and shared.</li>
          <li>The right to request deletion of personal information.</li>
          <li>The right to opt out of certain data sharing.</li>
          <li>The right to non-discrimination for exercising your privacy rights.</li>
        </ul>
        <p class="pp-p">To exercise these rights, contact <strong><a href="mailto:privacy@ensurance.com">privacy@ensurance.com</a></strong> with &ldquo;Privacy Request&rdquo; in the subject line. We will verify your identity before processing requests.</p>
      </section>

      <!-- §7 -->
      <section class="pp-section" id="cookies">
        <div class="pp-section__head">
          <span class="pp-section__n">7</span>
          <h2>Cookies and Tracking Technologies</h2>
        </div>
        <p class="pp-p">We use cookies, pixels, and other tracking technologies to:</p>
        <ul class="pp-list">
          <li>Enable core site functionality.</li>
          <li>Understand user behavior and improve our platform.</li>
          <li>Provide relevant content and advertising.</li>
        </ul>
        <p class="pp-p">You can control cookies through your browser settings, though disabling them may limit some site functionality.</p>
      </section>

      <!-- §8 -->
      <section class="pp-section" id="trust-positioning">
        <div class="pp-section__head">
          <span class="pp-section__n">8</span>
          <h2>Competitive &amp; Trust Positioning</h2>
        </div>
        <p class="pp-p">We believe trust is earned through transparency and clear communication. Our privacy commitments help us:</p>
        <ul class="pp-list">
          <li>Compete fairly in the insurance marketplace.</li>
          <li>Provide clarity about how your data is collected, used, and shared.</li>
          <li>Maintain a policy that is defensible if reviewed by regulators or courts.</li>
          <li>Reinforce our reputation as a trusted, compliant, and user-focused marketplace.</li>
        </ul>
      </section>

      <!-- §9 -->
      <section class="pp-section" id="security-retention">
        <div class="pp-section__head">
          <span class="pp-section__n">9</span>
          <h2>Data Security &amp; Retention</h2>
        </div>
        <p class="pp-p">We maintain administrative, technical, and physical safeguards, including:</p>
        <ul class="pp-list">
          <li>Access controls.</li>
          <li>Secure servers and encryption where appropriate.</li>
          <li>Locked office facilities and secure physical storage.</li>
        </ul>
        <p class="pp-p">We retain personal information only as long as necessary to fulfill the purposes for which it was collected or as required by law.</p>
      </section>

      <!-- §10 -->
      <section class="pp-section" id="agent-accounts">
        <div class="pp-section__head">
          <span class="pp-section__n">10</span>
          <h2>Account Management for Agents</h2>
        </div>
        <p class="pp-p">Agents can log in to their account at any time to update or delete their profile. Agents may also request account deletion by contacting us directly at <a href="mailto:support@ensurance.com">support@ensurance.com</a>.</p>
      </section>

      <!-- §11 -->
      <section class="pp-section" id="third-party-links">
        <div class="pp-section__head">
          <span class="pp-section__n">11</span>
          <h2>Links to Other Websites</h2>
        </div>
        <p class="pp-p">Ensurance.com may link to third-party sites. We are not responsible for their privacy practices and encourage you to review their policies.</p>
      </section>

      <!-- §12 -->
      <section class="pp-section" id="changes">
        <div class="pp-section__head">
          <span class="pp-section__n">12</span>
          <h2>Changes to This Policy</h2>
        </div>
        <p class="pp-p">We may update this Privacy Policy from time to time. The &ldquo;Effective Date&rdquo; above reflects the latest version. Continued use of our services after changes indicates acceptance.</p>
      </section>

      <!-- §13 -->
      <section class="pp-section" id="contact-us">
        <div class="pp-section__head">
          <span class="pp-section__n">13</span>
          <h2>Contact Us</h2>
        </div>
        <p class="pp-p">If you have questions about this Privacy Policy, email us at <strong><a href="mailto:privacy@ensurance.com">privacy@ensurance.com</a></strong>.</p>
        <div class="pp-contact">
          <div class="pp-contact__body">
            <p class="pp-contact__title">Have a privacy question or request?</p>
            <p class="pp-contact__text">A real person reads every message. Send us a note and choose &ldquo;A privacy request&rdquo; &mdash; we usually reply within one to two business days.</p>
          </div>
          <div class="pp-contact__actions">
            <a class="btn btn-primary" href="<?php echo $pp_contact_url; ?>" data-track="privacy_contact_click" data-cta-text="Contact us" data-page-type="privacy_policy">Contact us <?php echo wp_kses( ensurance_home_icon( 'arrow-right', 16 ), $ensurance_svg_allowed ); ?></a>
            <a class="btn btn-ghost" href="mailto:privacy@ensurance.com"><?php echo wp_kses( ensurance_home_icon( 'mail', 16 ), $ensurance_svg_allowed ); ?> privacy@ensurance.com</a>
          </div>
        </div>
      </section>

    </div>
  </div>

</main>
<?php get_footer( 'home' ); ?>
