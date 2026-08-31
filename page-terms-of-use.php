<?php
/**
 * Template Name: Terms of Use (Marketing)
 *
 * /terms-of-use — the formal Terms of Use rebuilt as a Calm Intelligence
 * legal document (same document pattern as /privacy-policy): a light hero
 * with breadcrumb, badge and effective/updated dates, a sticky
 * table-of-contents with scroll-spy, and the twenty-two numbered term
 * sections. The legal text is ported verbatim from the previous Gutenberg
 * page (page ID 8506, effective Aug 28, 2026) — this template is the single
 * source of truth for the terms copy now; the block content left in the
 * database is ignored.
 *
 * WordPress picks this file up automatically: `page-{slug}.php` sits in the
 * template hierarchy and the page's slug is `terms-of-use`, so no template
 * has to be assigned in the admin (the page's _wp_page_template stays
 * "default"). The Template Name header just makes it selectable too.
 *
 * Follows the same self-contained pattern as the other Calm Intelligence
 * pages: homepage chrome (get_header('home') / get_footer('home')) and shared
 * assets/home.css + home.js for tokens, chrome and buttons. The page-specific
 * document layout lives in assets/terms-of-use.css (all classes prefixed
 * `.tou-`), with the TOC scroll-spy in assets/terms-of-use.js. Both are
 * enqueued and isolated from the shared marketing bundle in functions.php
 * (ensurance_terms_of_use_assets), scoped to this template only.
 *
 * SEO: title / meta description / canonical / robots are owned by Yoast and
 * emitted through wp_head(); this template outputs none of them. The
 * WebPage + BreadcrumbList JSON-LD below is shipped here.
 */

/**
 * Inline Lucide icon renderer (shared with the other Calm Intelligence page
 * templates via the function_exists guard). Only one page template renders
 * per request, so this copy carries only the glyphs the terms page needs.
 * Paths from Lucide (ISC license).
 */
if ( ! function_exists( 'ensurance_home_icon' ) ) {
    function ensurance_home_icon( $name, $size = 20 ) {
        $icons = array(
            'file-text'   => '<path d="M15 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V7Z"/><path d="M14 2v4a2 2 0 0 0 2 2h4"/><path d="M10 9H8"/><path d="M16 13H8"/><path d="M16 17H8"/>',
            'info'        => '<circle cx="12" cy="12" r="10"/><path d="M12 16v-4"/><path d="M12 8h.01"/>',
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
    $url   = home_url( '/terms-of-use' );
    $flags = JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE;

    $graph = array(
        '@context' => 'https://schema.org',
        '@graph'   => array(
            array(
                '@type'       => 'WebPage',
                '@id'         => $url,
                'url'         => $url,
                'name'        => 'Terms of Use | Ensurance',
                'description' => 'The terms that govern general use of Ensurance.com and the shopper-facing Ensurance experience, including insurance role boundaries and how insurance requests work.',
            ),
            array(
                '@type'           => 'BreadcrumbList',
                'itemListElement' => array(
                    array( '@type' => 'ListItem', 'position' => 1, 'name' => 'Home', 'item' => home_url( '/' ) ),
                    array( '@type' => 'ListItem', 'position' => 2, 'name' => 'Terms of Use', 'item' => $url ),
                ),
            ),
        ),
    );
    echo '<script type="application/ld+json">' . wp_json_encode( $graph, $flags ) . '</script>' . "\n";
}, 20 );

// Resolved destinations.
$tou_contact_url    = esc_url( home_url( '/contact' ) );
$tou_privacy_url    = esc_url( home_url( '/privacy-policy' ) );
$tou_membership_url = esc_url( home_url( '/ensurance-agence-participating-agent-membership-terms/' ) );

// Table of contents — order mirrors the sections below.
$tou_toc = array(
    array( 'n' => '1',  'id' => 'agreement',        'label' => 'Agreement &amp; scope' ),
    array( 'n' => '2',  'id' => 'what-we-do',       'label' => 'What Ensurance does' ),
    array( 'n' => '3',  'id' => 'role-boundaries',  'label' => 'Insurance role boundaries' ),
    array( 'n' => '4',  'id' => 'requests',         'label' => 'Insurance requests' ),
    array( 'n' => '5',  'id' => 'your-information', 'label' => 'Information you provide' ),
    array( 'n' => '6',  'id' => 'accounts',         'label' => 'Accounts &amp; access' ),
    array( 'n' => '7',  'id' => 'professionals',    'label' => 'Participating professionals' ),
    array( 'n' => '8',  'id' => 'communications',   'label' => 'Communications' ),
    array( 'n' => '9',  'id' => 'site-content',     'label' => 'Site &amp; educational content' ),
    array( 'n' => '10', 'id' => 'third-party',      'label' => 'Third-party services &amp; links' ),
    array( 'n' => '11', 'id' => 'acceptable-use',   'label' => 'Acceptable use' ),
    array( 'n' => '12', 'id' => 'ip',               'label' => 'Intellectual property' ),
    array( 'n' => '13', 'id' => 'privacy',          'label' => 'Privacy' ),
    array( 'n' => '14', 'id' => 'availability',     'label' => 'Availability &amp; outcomes' ),
    array( 'n' => '15', 'id' => 'warranties',       'label' => 'Disclaimer of warranties' ),
    array( 'n' => '16', 'id' => 'liability',        'label' => 'Limitation of liability' ),
    array( 'n' => '17', 'id' => 'indemnification',  'label' => 'Indemnification' ),
    array( 'n' => '18', 'id' => 'termination',      'label' => 'Suspension or termination' ),
    array( 'n' => '19', 'id' => 'changes',          'label' => 'Changes to these Terms' ),
    array( 'n' => '20', 'id' => 'copyright',        'label' => 'Copyright concerns' ),
    array( 'n' => '21', 'id' => 'miscellaneous',    'label' => 'Miscellaneous' ),
    array( 'n' => '22', 'id' => 'contact',          'label' => 'Contact' ),
);

get_header( 'home' );
?>
<main id="main" class="page-terms-of-use">

  <!-- ── Hero (light document head) ───────────────────────────────── -->
  <section class="tou-hero" aria-label="Terms of Use">
    <span class="tou-hero__glow" aria-hidden="true"></span>
    <div class="tou-hero__inner">
      <nav class="tou-breadcrumb" aria-label="Breadcrumb">
        <a href="<?php echo esc_url( home_url( '/' ) ); ?>">Home</a>
        <span aria-hidden="true">/</span>
        <span class="tou-breadcrumb__current">Terms of Use</span>
      </nav>
      <div class="tou-hero__body">
        <span class="tou-hero__badge"><?php echo wp_kses( ensurance_home_icon( 'file-text', 13 ), $ensurance_svg_allowed ); ?> Legal &middot; Terms</span>
        <h1 class="tou-hero__title">Terms of Use</h1>
        <p class="tou-hero__sub">These Terms govern general use of Ensurance.com and the shopper-facing Ensurance experience. Separate terms may apply to participating licensed insurance professionals, paid services, or other specific features.</p>
        <div class="tou-hero__meta">
          <span class="tou-meta-pill">Effective date: Aug 28, 2026</span>
          <span class="tou-meta-pill">Last updated: Aug 28, 2026</span>
        </div>
        <p class="tou-hero__fine">Looking for how we handle your information? <a href="<?php echo $tou_privacy_url; ?>">Read the Privacy Policy</a>.</p>
      </div>
    </div>
  </section>

  <!-- ── Document: sticky TOC + numbered sections ─────────────────── -->
  <div class="tou-doc">
    <nav class="tou-toc" aria-label="Contents">
      <p class="tou-toc__label">On this page</p>
      <?php foreach ( $tou_toc as $i => $t ) : ?>
      <a class="tou-toc__link<?php echo 0 === $i ? ' is-active' : ''; ?>" href="#<?php echo esc_attr( $t['id'] ); ?>" data-tou-toc="<?php echo esc_attr( $t['id'] ); ?>">
        <span class="tou-toc__n"><?php echo esc_html( $t['n'] ); ?></span>
        <span><?php echo wp_kses( $t['label'], array() ); ?></span>
      </a>
      <?php endforeach; ?>
    </nav>

    <div class="tou-doc__main">

      <!-- Intro -->
      <div class="tou-intro">
        <div class="tou-note" role="note">
          <span class="tou-note__icon"><?php echo wp_kses( ensurance_home_icon( 'info', 18 ), $ensurance_svg_allowed ); ?></span>
          <p class="tou-note__text"><strong>Important:</strong> Ensurance helps people start and organize insurance requests online. Ensurance is not an insurance carrier and does not underwrite, bind, issue, or price insurance policies. Insurance advice, quotes, recommendations, underwriting, eligibility decisions, pricing, and policy issuance are handled by appropriately licensed insurance professionals and insurance companies, as applicable.</p>
        </div>
      </div>

      <!-- §1 -->
      <section class="tou-section" id="agreement">
        <div class="tou-section__head">
          <span class="tou-section__n">1</span>
          <h2>Agreement and Scope</h2>
        </div>
        <p class="tou-p">Ensurance.com is owned and operated by Ensurance Incorporated (&ldquo;Ensurance,&rdquo; &ldquo;we,&rdquo; &ldquo;us,&rdquo; or &ldquo;our&rdquo;). By accessing or using Ensurance.com, submitting an insurance request, creating an account, or using an Ensurance feature, you agree to these Terms of Use and to any additional terms that apply to the specific feature you use.</p>
        <p class="tou-p">If you do not agree to these Terms, do not use the Site. You may use the Site only if you are legally able to enter into these Terms.</p>
      </section>

      <!-- §2 -->
      <section class="tou-section" id="what-we-do">
        <div class="tou-section__head">
          <span class="tou-section__n">2</span>
          <h2>What Ensurance Does</h2>
        </div>
        <p class="tou-p">Ensurance helps shoppers begin insurance requests online, organize relevant information, understand the process, and move toward licensed insurance help where available. Different insurance products may use different request, review, access, checkout, contact, or fulfillment workflows.</p>
        <p class="tou-p">Ensurance may organize information submitted by a shopper into a structured or protected request and may use product-specific eligibility, review, access, or workflow controls to support the next step.</p>
        <p class="tou-p">Availability varies by state, insurance type, request details, professional availability, licensing, carrier participation, eligibility, and other factors.</p>
      </section>

      <!-- §3 -->
      <section class="tou-section" id="role-boundaries">
        <div class="tou-section__head">
          <span class="tou-section__n">3</span>
          <h2>Insurance Role Boundaries</h2>
        </div>
        <p class="tou-p">Ensurance does not underwrite, bind, issue, or price insurance policies. Unless expressly stated for a particular service, Ensurance does not provide individualized insurance advice or make carrier underwriting or eligibility decisions.</p>
        <p class="tou-p">Licensed insurance professionals are responsible for the insurance advice, recommendations, communications, or quote information they provide. Insurance carriers and other authorized insurance organizations are responsible for matters within their authority, including underwriting, eligibility, pricing, policy terms, coverage availability, and policy issuance.</p>
        <p class="tou-p">You are responsible for reviewing policy terms, exclusions, limits, deductibles, premiums, carrier information, and other material details directly with an appropriately licensed professional or insurer before purchasing or relying on coverage.</p>
      </section>

      <!-- §4 -->
      <section class="tou-section" id="requests">
        <div class="tou-section__head">
          <span class="tou-section__n">4</span>
          <h2>Insurance Requests</h2>
        </div>
        <p class="tou-p">When you submit an insurance request, you authorize Ensurance to process the information you provide for the applicable request experience in accordance with these Terms, the applicable request disclosures, and our <a href="<?php echo $tou_privacy_url; ?>">Privacy Policy</a>.</p>
        <p class="tou-p">Depending on the product and workflow, a request or protected request preview may be made available to eligible licensed insurance professionals, and additional access or contact may occur only through the applicable workflow and permissions.</p>
        <p class="tou-p">Submitting a request does not guarantee that a licensed professional will review or accept the request, that you will receive a response or quote, that a particular carrier, coverage, price, or policy will be available, that you will qualify for insurance, or that any insurance transaction will be completed.</p>
      </section>

      <!-- §5 -->
      <section class="tou-section" id="your-information">
        <div class="tou-section__head">
          <span class="tou-section__n">5</span>
          <h2>Information You Provide</h2>
        </div>
        <p class="tou-p">You agree to provide information that is accurate to the best of your knowledge and that you have the right to provide. You are responsible for reviewing information before submission and for correcting material errors when a correction method is available.</p>
        <p class="tou-p">Do not submit another person&rsquo;s sensitive information unless you are authorized to do so and the information is reasonably necessary for the applicable insurance request.</p>
      </section>

      <!-- §6 -->
      <section class="tou-section" id="accounts">
        <div class="tou-section__head">
          <span class="tou-section__n">6</span>
          <h2>Accounts and Access</h2>
        </div>
        <p class="tou-p">If a feature requires an account, you are responsible for maintaining the confidentiality of your credentials and for activity conducted through your account. You may not access another person&rsquo;s account without authorization, impersonate another user, or attempt to bypass access controls.</p>
        <p class="tou-p">We may require verification, restrict access, suspend an account, or take other reasonable steps when necessary to protect shoppers, participating professionals, Ensurance, the Site, or applicable legal and security obligations.</p>
      </section>

      <!-- §7 -->
      <section class="tou-section" id="professionals">
        <div class="tou-section__head">
          <span class="tou-section__n">7</span>
          <h2>Participating Insurance Professionals</h2>
        </div>
        <p class="tou-p">Ensurance may make certain request opportunities or professional features available to eligible licensed insurance professionals or agencies. Professional participation does not make a professional an employee, agent, partner, or representative of Ensurance unless a separate written agreement expressly says otherwise.</p>
        <p class="tou-p">Participating professionals remain responsible for their licensing, professional authority, insurance activities, communications, regulatory obligations, and use of shopper information.</p>
        <p class="tou-p">Paid professional participation, membership billing, professional responsibilities, public Participating Professional pages, and permitted use of shopper information are governed by the <a href="<?php echo $tou_membership_url; ?>">Participating Agent Membership Terms</a> and applicable enrollment disclosures.</p>
      </section>

      <!-- §8 -->
      <section class="tou-section" id="communications">
        <div class="tou-section__head">
          <span class="tou-section__n">8</span>
          <h2>Communications</h2>
        </div>
        <p class="tou-p">By providing contact information or selecting communication preferences, you may authorize communications related to your request, account, transaction, or use of the Site as described in the applicable disclosure or consent language presented to you.</p>
        <p class="tou-p">Consent requirements can vary by communication method and purpose. Nothing in these Terms creates consent beyond the consent or authorization actually provided through the applicable request, form, account, or transaction.</p>
      </section>

      <!-- §9 -->
      <section class="tou-section" id="site-content">
        <div class="tou-section__head">
          <span class="tou-section__n">9</span>
          <h2>Site Information and Educational Content</h2>
        </div>
        <p class="tou-p">Ensurance publishes insurance-shopping information, state guides, explanations, FAQs, and other educational content. We work to make that information useful and reasonably current, but laws, insurance markets, carrier practices, programs, rates, and requirements can change.</p>
        <p class="tou-p">Site content is provided for general informational purposes and should not be treated as individualized insurance, legal, tax, or financial advice. When a rule, requirement, deadline, or coverage decision matters to your situation, confirm it with an appropriate licensed professional, insurer, regulator, or other authoritative source.</p>
      </section>

      <!-- §10 -->
      <section class="tou-section" id="third-party">
        <div class="tou-section__head">
          <span class="tou-section__n">10</span>
          <h2>Third-Party Services and Links</h2>
        </div>
        <p class="tou-p">The Site may link to or interact with third-party websites, insurance professionals, insurers, payment processors, service providers, government resources, or other external services. Third-party services are governed by their own terms, privacy practices, and legal obligations.</p>
        <p class="tou-p">Ensurance is not responsible for the content, availability, security, or practices of third-party websites or services that we do not control.</p>
      </section>

      <!-- §11 -->
      <section class="tou-section" id="acceptable-use">
        <div class="tou-section__head">
          <span class="tou-section__n">11</span>
          <h2>Acceptable Use</h2>
        </div>
        <p class="tou-p">You may not use the Site to engage in fraud, deception, impersonation, harassment, or unlawful activity; introduce malware or security threats; scrape or systematically extract non-public shopper or professional information; circumvent authentication, payment, access, eligibility, request, or security controls; gain unauthorized access to accounts, systems, data, or infrastructure; interfere with Site availability; misuse Site content or data; or misrepresent your relationship with Ensurance.</p>
      </section>

      <!-- §12 -->
      <section class="tou-section" id="ip">
        <div class="tou-section__head">
          <span class="tou-section__n">12</span>
          <h2>Intellectual Property</h2>
        </div>
        <p class="tou-p">Except where otherwise stated, the Site, its design, software, workflows, text, graphics, logos, trademarks, and other proprietary materials are owned by Ensurance or its licensors and are protected by applicable intellectual property laws.</p>
        <p class="tou-p">You receive a limited, revocable, non-exclusive right to use the Site for its intended lawful purpose. You may not copy, reproduce, distribute, modify, reverse engineer, create derivative works from, or commercially exploit protected Site materials except as permitted by law or with written authorization.</p>
      </section>

      <!-- §13 -->
      <section class="tou-section" id="privacy">
        <div class="tou-section__head">
          <span class="tou-section__n">13</span>
          <h2>Privacy</h2>
        </div>
        <p class="tou-p">Our collection, use, disclosure, and handling of personal information is described in the <a href="<?php echo $tou_privacy_url; ?>">Privacy Policy</a> and in any additional notices or consent language presented with a particular request or feature.</p>
        <p class="tou-p">If there is a conflict between these Terms and a more specific privacy or consent notice concerning personal information, the more specific notice controls for that issue.</p>
      </section>

      <!-- §14 -->
      <section class="tou-section" id="availability">
        <div class="tou-section__head">
          <span class="tou-section__n">14</span>
          <h2>Availability and Outcomes</h2>
        </div>
        <p class="tou-p">We may add, modify, suspend, or discontinue Site features or request experiences as the service develops. We do not guarantee uninterrupted access, a particular feature, a particular number of participating professionals, a specific response time, or a specific insurance outcome.</p>
      </section>

      <!-- §15 -->
      <section class="tou-section" id="warranties">
        <div class="tou-section__head">
          <span class="tou-section__n">15</span>
          <h2>Disclaimer of Warranties</h2>
        </div>
        <p class="tou-p">To the fullest extent permitted by law, the Site and its content are provided on an &ldquo;as is&rdquo; and &ldquo;as available&rdquo; basis. Ensurance disclaims warranties that are not expressly stated in these Terms, including implied warranties of merchantability, fitness for a particular purpose, and non-infringement, to the extent those warranties may lawfully be disclaimed.</p>
        <p class="tou-p">We do not warrant that the Site will always be uninterrupted, error-free, secure, complete, or current, or that use of the Site will produce a particular insurance or business result.</p>
      </section>

      <!-- §16 -->
      <section class="tou-section" id="liability">
        <div class="tou-section__head">
          <span class="tou-section__n">16</span>
          <h2>Limitation of Liability</h2>
        </div>
        <p class="tou-p">To the fullest extent permitted by applicable law, Ensurance and its officers, directors, employees, affiliates, and service providers will not be liable for indirect, incidental, special, consequential, exemplary, or punitive damages arising out of or related to your use of, or inability to use, the Site, reliance on general Site content, or interactions with third parties.</p>
        <p class="tou-p">Nothing in these Terms excludes or limits liability that cannot lawfully be excluded or limited, and nothing in these Terms limits rights that applicable law does not permit you to waive.</p>
      </section>

      <!-- §17 -->
      <section class="tou-section" id="indemnification">
        <div class="tou-section__head">
          <span class="tou-section__n">17</span>
          <h2>Indemnification</h2>
        </div>
        <p class="tou-p">To the extent permitted by law, you agree to indemnify and hold harmless Ensurance and its officers, directors, employees, and affiliates from third-party claims, liabilities, losses, and reasonable expenses arising from your unlawful misuse of the Site, your violation of these Terms, or your infringement of another person&rsquo;s rights.</p>
      </section>

      <!-- §18 -->
      <section class="tou-section" id="termination">
        <div class="tou-section__head">
          <span class="tou-section__n">18</span>
          <h2>Suspension or Termination</h2>
        </div>
        <p class="tou-p">Ensurance may suspend, restrict, or terminate access to the Site or a feature when reasonably necessary for security, fraud prevention, legal compliance, professional ineligibility, misuse, violation of these Terms, nonpayment of an applicable paid service, or protection of shoppers, participating professionals, Ensurance, or the platform.</p>
      </section>

      <!-- §19 -->
      <section class="tou-section" id="changes">
        <div class="tou-section__head">
          <span class="tou-section__n">19</span>
          <h2>Changes to These Terms</h2>
        </div>
        <p class="tou-p">We may revise these Terms from time to time. The current version and effective date will be posted on this page. When applicable law or the nature of a material change requires additional notice or consent, we will provide it through an appropriate method.</p>
        <p class="tou-p">Your continued use of the Site after an updated version becomes effective constitutes acceptance of the updated Terms, except where additional consent is required by law.</p>
      </section>

      <!-- §20 -->
      <section class="tou-section" id="copyright">
        <div class="tou-section__head">
          <span class="tou-section__n">20</span>
          <h2>Copyright Concerns</h2>
        </div>
        <p class="tou-p">If you believe content on the Site infringes your copyright, you may send a notice that complies with applicable copyright law to <a href="mailto:privacy@ensurance.com">privacy@ensurance.com</a>.</p>
      </section>

      <!-- §21 -->
      <section class="tou-section" id="miscellaneous">
        <div class="tou-section__head">
          <span class="tou-section__n">21</span>
          <h2>Miscellaneous</h2>
        </div>
        <p class="tou-p">These Terms, the <a href="<?php echo $tou_privacy_url; ?>">Privacy Policy</a>, and any applicable supplemental terms or disclosures form the agreement governing the relevant use of the Site. If a supplemental term directly conflicts with these general Terms for a particular feature, the supplemental term controls for that feature.</p>
        <p class="tou-p">If a provision of these Terms is found unenforceable, the remaining provisions remain in effect to the extent permitted by law. A failure to enforce a provision is not a waiver of that provision. You may not assign your rights under these Terms without our consent where consent is legally permitted to be required. Ensurance may assign these Terms in connection with a merger, acquisition, reorganization, sale of assets, or other lawful business transfer.</p>
        <p class="tou-p">These Terms do not create a partnership, joint venture, employment relationship, fiduciary relationship, or agency relationship between a shopper and Ensurance, or between a participating insurance professional and Ensurance, except where a separate written agreement expressly provides otherwise.</p>
      </section>

      <!-- §22 -->
      <section class="tou-section" id="contact">
        <div class="tou-section__head">
          <span class="tou-section__n">22</span>
          <h2>Contact</h2>
        </div>
        <p class="tou-p">Use the <a href="<?php echo $tou_contact_url; ?>">Ensurance Contact page</a> for general questions about these Terms. Privacy-related questions may also be directed to <strong><a href="mailto:privacy@ensurance.com">privacy@ensurance.com</a></strong>.</p>
        <div class="tou-contact">
          <div class="tou-contact__body">
            <p class="tou-contact__title">Have a question about these Terms?</p>
            <p class="tou-contact__text">A real person reads every message. Send us a note and we usually reply within one to two business days.</p>
          </div>
          <div class="tou-contact__actions">
            <a class="btn btn-primary" href="<?php echo $tou_contact_url; ?>" data-track="terms_contact_click" data-cta-text="Contact us" data-page-type="terms_of_use">Contact us <?php echo wp_kses( ensurance_home_icon( 'arrow-right', 16 ), $ensurance_svg_allowed ); ?></a>
            <a class="btn btn-ghost" href="mailto:privacy@ensurance.com"><?php echo wp_kses( ensurance_home_icon( 'mail', 16 ), $ensurance_svg_allowed ); ?> privacy@ensurance.com</a>
          </div>
        </div>
      </section>

    </div>
  </div>

</main>
<?php get_footer( 'home' ); ?>
