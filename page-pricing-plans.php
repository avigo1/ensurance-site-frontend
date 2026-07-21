<?php
/**
 * Template Name: Founding Agent Access (/pricing-plans)
 *
 * /pricing-plans/ repositioned as "Founding Agent Access" — Calm Intelligence
 * redesign built from the approved Claude design (Ensurance Founding Agent
 * Access, standalone). Uses the shared homepage chrome (get_header('home') /
 * get_footer('home')) and assets/home.css + assets/home.js for tokens/base,
 * layering assets/pricing-plans.css + assets/pricing-plans.js on top. Loaded
 * and isolated from the shared marketing bundle in functions.php.
 *
 * GeoDirectory coupling — the ONLY backend dependency:
 *   The two plan CTAs point at the EXISTING GeoDirectory Pricing Manager
 *   packages via ?package_id=. Do NOT rebuild checkout; do NOT change these ids.
 *     - 60 Day Founding Agent Access  → package_id=14  (currently free tier)
 *     - Founding Agent Access $29/mo  → package_id=16
 *   NOTE (unresolved, see handoff doc §14): package 14 is a perpetual-free
 *   GeoDirectory package today, not a 60-day-trial-to-$29. The visible copy
 *   promises "$0 for 60 days, then $29/mo". Confirm the GeoDirectory package
 *   behavior matches this copy before promoting to production, or adjust one.
 *
 * SEO: title / meta description / canonical / robots are owned by Yoast and
 * emitted through wp_head(). This template outputs only FAQPage JSON-LD, which
 * Yoast does not emit for this page and which mirrors the visible FAQ verbatim.
 *
 * This template renders via the page-{slug}.php hierarchy for the /pricing-plans/
 * page, so it auto-overrides the previous Kadence block content with no DB edit.
 */

// GeoDirectory Pricing Manager checkout entry points (existing packages).
$fa_geo_60day   = home_url( '/publish-your-agency/insurance-agencies/?package_id=14' );
$fa_geo_monthly = home_url( '/publish-your-agency/insurance-agencies/?package_id=16' );

/**
 * Inline Lucide glyphs (stroke 2, round caps) used on this page.
 */
if ( ! function_exists( 'ensurance_fa_icon' ) ) {
	function ensurance_fa_icon( $name, $size = 20, $class = '' ) {
		$paths = array(
			'arrow-right'  => '<path d="M5 12h14"/><path d="m12 5 7 7-7 7"/>',
			'check'        => '<path d="M20 6 9 17l-5-5"/>',
			'x'            => '<path d="M18 6 6 18"/><path d="m6 6 12 12"/>',
			'shield-check' => '<path d="M20 13c0 5-3.5 7.5-7.66 8.95a1 1 0 0 1-.67-.01C7.5 20.5 4 18 4 13V6a1 1 0 0 1 1-1c2 0 4.5-1.2 6.24-2.72a1.17 1.17 0 0 1 1.52 0C14.51 3.81 17 5 19 5a1 1 0 0 1 1 1z"/><path d="m9 12 2 2 4-4"/>',
			'lock'         => '<rect width="18" height="11" x="3" y="11" rx="2" ry="2"/><path d="M7 11V7a5 5 0 0 1 10 0v4"/>',
			'clock'        => '<circle cx="12" cy="12" r="10"/><polyline points="12 6 12 12 16 14"/>',
			'user'         => '<path d="M19 21v-2a4 4 0 0 0-4-4H9a4 4 0 0 0-4 4v2"/><circle cx="12" cy="7" r="4"/>',
			'file-text'    => '<path d="M15 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V7Z"/><path d="M14 2v4a2 2 0 0 0 2 2h4"/><path d="M16 13H8"/><path d="M16 17H8"/><path d="M10 9H8"/>',
			'ban'          => '<circle cx="12" cy="12" r="10"/><path d="m4.9 4.9 14.2 14.2"/>',
			'sparkles'     => '<path d="m12 3-1.9 5.8a2 2 0 0 1-1.3 1.3L3 12l5.8 1.9a2 2 0 0 1 1.3 1.3L12 21l1.9-5.8a2 2 0 0 1 1.3-1.3L21 12l-5.8-1.9a2 2 0 0 1-1.3-1.3z"/>',
			'message'      => '<path d="M21 15a2 2 0 0 1-2 2H7l-4 4V5a2 2 0 0 1 2-2h14a2 2 0 0 1 2 2z"/>',
			'circle-check' => '<circle cx="12" cy="12" r="10"/><path d="m9 12 2 2 4-4"/>',
			'chevron-down' => '<path d="m6 9 6 6 6-6"/>',
		);
		if ( ! isset( $paths[ $name ] ) ) {
			return '';
		}
		return '<svg class="' . esc_attr( $class ) . '" width="' . (int) $size . '" height="' . (int) $size . '" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">' . $paths[ $name ] . '</svg>';
	}
}

// Icons are assembled from a fixed whitelist above; allow their markup through.
$fa_svg_allowed = array(
	'svg'      => array( 'class' => true, 'width' => true, 'height' => true, 'viewbox' => true, 'fill' => true, 'stroke' => true, 'stroke-width' => true, 'stroke-linecap' => true, 'stroke-linejoin' => true, 'aria-hidden' => true ),
	'path'     => array( 'd' => true ),
	'rect'     => array( 'width' => true, 'height' => true, 'x' => true, 'y' => true, 'rx' => true, 'ry' => true ),
	'circle'   => array( 'cx' => true, 'cy' => true, 'r' => true ),
	'polyline' => array( 'points' => true ),
);

// --- Per-page FAQPage schema — mirrors the visible FAQ below verbatim. ---
$fa_faq = array(
	array( 'What is Founding Agent Access?', 'Founding Agent Access is an early agent access option for licensed insurance agents who want a more organized way to review shopper opportunities through Ensurance. Agents can create an agency profile, review eligible request details when available, and accept or pass before deciding whether to engage.' ),
	array( 'Is Ensurance a lead buying platform?', 'No. Ensurance is not a bulk lead seller or quote-comparison site. Ensurance is building a more structured way for insurance shoppers and agents to connect around organized insurance quote requests.' ),
	array( 'What does the 60 Day Founding Agent Access include?', 'The 60 day access option includes an agency profile, access to eligible request previews when available, and the ability to accept or pass before engaging. This option may be available for selected agents while Ensurance opens access in selected states.' ),
	array( 'What happens after the 60 day access period?', 'After the 60 day access period, Founding Agent Access may continue at $29 per month unless canceled before the subscription begins.' ),
	array( 'What does the $29 per month plan include?', 'The $29 per month Founding Agent Access plan includes an agency profile, continued access to eligible shopper request previews when available, and the ability to accept or pass before engaging.' ),
	array( 'Are shopper requests guaranteed?', 'No. Availability of shopper requests may vary by state, coverage type, shopper activity, and agent eligibility. Founding Agent Access does not guarantee request volume.' ),
	array( 'Can I accept or pass on requests?', 'Yes. Ensurance is designed to give agents more control. Agents may review eligible request details and decide whether to accept or pass before engaging.' ),
	array( 'How is this different from buying bulk leads?', 'Bulk lead programs often send shared names to multiple agents with limited context and little control. Ensurance is building a more organized process where agents can review eligible request details and choose whether the opportunity fits their agency.' ),
	array( 'Do I need to sign a long-term contract?', 'No. Founding Agent Access is designed to be low commitment. Agents can cancel anytime.' ),
	array( 'Is Founding Agent Access available in every state?', 'Availability may vary by state and agent eligibility. Ensurance is opening access in selected states as shopper activity and agent coverage expand.' ),
);

$fa_faq_schema = array(
	'@context'   => 'https://schema.org',
	'@type'      => 'FAQPage',
	'@id'        => home_url( '/pricing-plans/#faq' ),
	'mainEntity' => array(),
);
foreach ( $fa_faq as $qa ) {
	$fa_faq_schema['mainEntity'][] = array(
		'@type'          => 'Question',
		'name'           => $qa[0],
		'acceptedAnswer' => array( '@type' => 'Answer', 'text' => $qa[1] ),
	);
}

add_action( 'wp_head', function () use ( $fa_faq_schema ) {
	echo '<script type="application/ld+json">' . wp_json_encode( $fa_faq_schema, JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE ) . '</script>' . "\n";
}, 20 );

/**
 * Keep /pricing-plans/ out of search engines. Yoast owns the robots meta on
 * this site (see SEO note above), so force its output to noindex,nofollow
 * rather than emitting a second competing tag. Fallback meta covers the case
 * where Yoast is inactive, so the directive is present either way.
 */
add_filter( 'wpseo_robots', function () {
	return 'noindex, nofollow';
}, 999 );
// Newer Yoast builds a robots array before stringifying it — cover that too.
add_filter( 'wpseo_robots_array', function ( $robots ) {
	$robots['index']  = 'noindex';
	$robots['follow'] = 'nofollow';
	return $robots;
}, 999 );
// Fallback for when Yoast is not emitting a robots tag on this request.
add_action( 'wp_head', function () {
	if ( ! defined( 'WPSEO_VERSION' ) ) {
		echo '<meta name="robots" content="noindex, nofollow">' . "\n";
	}
}, 1 );

$fa_disclaimer = 'Availability of shopper requests may vary by state, coverage type, shopper activity, and agent eligibility. Founding Agent Access does not guarantee request volume.';

get_header( 'home' );
?>

<main id="main" class="fa-page">

	<!-- ── HERO ─────────────────────────────────────────────────────── -->
	<section class="fa-hero" aria-labelledby="fa-hero-title">
		<span class="fa-hero__glow fa-hero__glow--a" aria-hidden="true"></span>
		<span class="fa-hero__glow fa-hero__glow--b" aria-hidden="true"></span>
		<div class="fa-container fa-hero__inner">
			<nav class="fa-crumbs" aria-label="Breadcrumb">
				<a href="<?php echo esc_url( home_url( '/' ) ); ?>">Home</a>
				<span aria-hidden="true">/</span>
				<span class="fa-crumbs__current">Founding Agent Access</span>
			</nav>
			<div class="fa-hero__grid">
				<div class="fa-hero__copy">
					<span class="fa-eyebrow fa-eyebrow--accent"><span class="fa-eyebrow__dot" aria-hidden="true"></span> Early access · Selected states</span>
					<h1 id="fa-hero-title" class="fa-hero__title">Founding Agent Access</h1>
					<p class="fa-hero__lede">Join early in your state and review organized shopper requests without bulk lead buying.</p>
					<p class="fa-hero__body">Ensurance is opening Founding Agent Access in selected states as more shoppers begin auto insurance quote requests through our platform. Create an agency profile, review eligible request details when available, accept or pass, and decide when an opportunity fits your agency.</p>
					<div class="fa-hero__actions">
						<a class="fa-btn fa-btn--solid" href="#plans" data-event="hero_start_60_day_click">Start 60 day access <?php echo wp_kses( ensurance_fa_icon( 'arrow-right', 18 ), $fa_svg_allowed ); ?></a>
						<a class="fa-btn fa-btn--ghost" href="#plans" data-event="hero_join_founding_click">Join as a Founding Agent</a>
					</div>
					<a class="fa-linkarrow" href="#how" data-event="hero_review_how_click">Review how it works <?php echo wp_kses( ensurance_fa_icon( 'arrow-right', 16 ), $fa_svg_allowed ); ?></a>
					<p class="fa-hero__fine"><?php echo esc_html( $fa_disclaimer ); ?></p>
				</div>
				<div class="fa-hero__aside">
					<div class="fa-summary">
						<div class="fa-summary__head">
							<span class="fa-summary__label">Founding Access · Priority states</span>
							<span class="fa-badge fa-badge--status"><span class="fa-badge__dot" aria-hidden="true"></span> Now opening</span>
						</div>
						<div class="fa-summary__body">
							<div class="fa-summary__price"><span class="fa-summary__amount">$0</span><span class="fa-summary__unit">for 60 days</span></div>
							<dl class="fa-summary__rows">
								<div><dt>Agency profile</dt><dd>On Ensurance</dd></div>
								<div><dt>Eligible requests</dt><dd>Review when available</dd></div>
								<div><dt>Accept or pass</dt><dd>You stay in control</dd></div>
								<div><dt>Bulk lead buying</dt><dd>None</dd></div>
							</dl>
						</div>
						<div class="fa-summary__foot">
							<?php echo wp_kses( ensurance_fa_icon( 'shield-check', 15, 'fa-summary__foot-icon' ), $fa_svg_allowed ); ?>
							<span>No long-term contract — cancel anytime</span>
						</div>
					</div>
				</div>
			</div>
		</div>
	</section>

	<!-- ── INTRO ────────────────────────────────────────────────────── -->
	<section class="fa-section fa-intro" aria-labelledby="fa-intro-title">
		<div class="fa-container">
			<div class="fa-intro__wrap">
				<span class="fa-eyebrow">Why Founding Agent Access exists</span>
				<h2 id="fa-intro-title" class="fa-h2">A better, more trusted way for shoppers and agents to connect.</h2>
				<p class="fa-lede">Ensurance is building a better, more trusted way for insurance shoppers and agents to connect around structured insurance quote requests. For shoppers, that means insurance quote help without the quote chaos. For agents, it means a better path to serious, organized shopper opportunities without bulk lead buying.</p>
				<p class="fa-lede">Founding Agent Access is designed for licensed agents who want an early position as Ensurance opens access in selected states.</p>
			</div>
		</div>
	</section>

	<!-- ── PLANS ────────────────────────────────────────────────────── -->
	<section id="plans" class="fa-section fa-plans" aria-labelledby="fa-plans-title">
		<div class="fa-container">
			<div class="fa-head fa-head--center">
				<span class="fa-eyebrow">Choose your access</span>
				<h2 id="fa-plans-title" class="fa-h2">Two ways to join as a Founding Agent.</h2>
				<p class="fa-head__sub">Start with 60 day access while Ensurance opens your state, or join monthly for continued access. You stay in control of which opportunities you pursue.</p>
			</div>

			<div class="fa-plans__grid">

				<!-- Card 1 — 60 Day (featured) → package_id=14 -->
				<article class="fa-plan fa-plan--featured">
					<span class="fa-plan__bar" aria-hidden="true"></span>
					<div class="fa-plan__top">
						<span class="fa-plan__tag">60 Day Founding Agent Access</span>
						<span class="fa-badge fa-badge--accent">Start here</span>
					</div>
					<h3 class="fa-plan__title">60 Day Founding Agent Access</h3>
					<div class="fa-plan__price"><span class="fa-plan__amount">$0</span><span class="fa-plan__unit">for 60 days</span></div>
					<p class="fa-plan__supporting">Available for selected agents in priority states while Ensurance opens access in selected states.</p>
					<ul class="fa-plan__bullets">
						<?php foreach ( array( 'Agency profile on Ensurance', 'Review eligible shopper request details when available', 'Accept or pass before deciding whether to engage', 'No bulk lead buying', 'No long-term contract', 'Early position in your state' ) as $b ) : ?>
							<li><span class="fa-tick"><?php echo wp_kses( ensurance_fa_icon( 'check', 13 ), $fa_svg_allowed ); ?></span><?php echo esc_html( $b ); ?></li>
						<?php endforeach; ?>
					</ul>
					<a class="fa-btn fa-btn--solid fa-btn--block" href="<?php echo esc_url( $fa_geo_60day ); ?>" data-event="plan_60_day_checkout_click">Start 60 day access <?php echo wp_kses( ensurance_fa_icon( 'arrow-right', 17 ), $fa_svg_allowed ); ?></a>
					<p class="fa-plan__note">After the 60 day access period, Founding Agent Access may continue at $29 per month unless canceled before the subscription begins.</p>
					<div class="fa-consent">
						<?php echo wp_kses( ensurance_fa_icon( 'lock', 13, 'fa-consent__icon' ), $fa_svg_allowed ); ?>
						<span>By continuing, I understand this access is free for 60 days. After the 60 day access period, Founding Agent Access may continue at $29 per month unless canceled before the subscription begins.</span>
					</div>
				</article>

				<!-- Card 2 — $29/mo → package_id=16 -->
				<article class="fa-plan">
					<div class="fa-plan__top">
						<span class="fa-plan__tag">Founding Agent Access</span>
					</div>
					<h3 class="fa-plan__title">Founding Agent Access</h3>
					<div class="fa-plan__price"><span class="fa-plan__amount">$29</span><span class="fa-plan__unit">per month</span></div>
					<p class="fa-plan__supporting">For agents who want continued access to organized shopper request previews and agency profile placement.</p>
					<ul class="fa-plan__bullets">
						<?php foreach ( array( 'Agency profile on Ensurance', 'Review eligible shopper request details when available', 'Accept or pass control', 'Access to shopper-authorized opportunities when available', 'No bulk lead buying', 'Cancel anytime' ) as $b ) : ?>
							<li><span class="fa-tick"><?php echo wp_kses( ensurance_fa_icon( 'check', 13 ), $fa_svg_allowed ); ?></span><?php echo esc_html( $b ); ?></li>
						<?php endforeach; ?>
					</ul>
					<a class="fa-btn fa-btn--outline fa-btn--block" href="<?php echo esc_url( $fa_geo_monthly ); ?>" data-event="plan_monthly_checkout_click">Join as a Founding Agent <?php echo wp_kses( ensurance_fa_icon( 'arrow-right', 17 ), $fa_svg_allowed ); ?></a>
					<p class="fa-plan__note">You stay in control of which opportunities you choose to pursue.</p>
					<div class="fa-consent">
						<?php echo wp_kses( ensurance_fa_icon( 'lock', 13, 'fa-consent__icon' ), $fa_svg_allowed ); ?>
						<span>By continuing, I agree to subscribe to Founding Agent Access for $29 per month and authorize Ensurance to charge the payment method provided on a recurring monthly basis until canceled.</span>
					</div>
				</article>
			</div>

			<div class="fa-callout fa-callout--warn" role="note">
				<?php echo wp_kses( ensurance_fa_icon( 'clock', 18, 'fa-callout__icon' ), $fa_svg_allowed ); ?>
				<div>
					<p class="fa-callout__title">Availability varies</p>
					<p class="fa-callout__body"><?php echo esc_html( $fa_disclaimer ); ?></p>
				</div>
			</div>
		</div>
	</section>

	<!-- ── WHAT YOU GET ─────────────────────────────────────────────── -->
	<section class="fa-section" aria-labelledby="fa-get-title">
		<div class="fa-container">
			<div class="fa-head fa-head--center">
				<span class="fa-eyebrow">What you get</span>
				<h2 id="fa-get-title" class="fa-h2">What you get with Founding Agent Access.</h2>
				<p class="fa-head__sub">Founding Agent Access gives agents a more controlled way to review shopper opportunities without bulk lead buying.</p>
			</div>
			<div class="fa-get__grid">
				<?php
				$fa_get = array(
					array( 'user', 'Agency profile on Ensurance', 'A profile that represents your agency, with visibility in eligible service areas.' ),
					array( 'file-text', 'Review eligible request details', 'See the details of eligible shopper requests when they are available in your area.' ),
					array( 'check', 'Accept or pass', 'Decide whether an opportunity fits your agency before you engage.' ),
					array( 'shield-check', 'Shopper-authorized opportunities', 'Access shopper-authorized opportunities when they are available to you.' ),
					array( 'ban', 'A lower-pressure alternative', 'A more controlled path than bulk lead programs — no chasing every name.' ),
					array( 'sparkles', 'Early position', 'An early position as Ensurance opens access state by state. No long-term contract, cancel anytime.' ),
				);
				foreach ( $fa_get as $g ) : ?>
					<div class="fa-getcard">
						<span class="fa-getcard__icon"><?php echo wp_kses( ensurance_fa_icon( $g[0], 20 ), $fa_svg_allowed ); ?></span>
						<h3 class="fa-getcard__title"><?php echo esc_html( $g[1] ); ?></h3>
						<p class="fa-getcard__body"><?php echo esc_html( $g[2] ); ?></p>
					</div>
				<?php endforeach; ?>
			</div>
		</div>
	</section>

	<!-- ── BETTER PATH ──────────────────────────────────────────────── -->
	<section class="fa-section" aria-labelledby="fa-path-title">
		<div class="fa-container">
			<div class="fa-head fa-head--center">
				<span class="fa-eyebrow">A better path</span>
				<h2 id="fa-path-title" class="fa-h2">A better path than bulk lead buying.</h2>
				<p class="fa-head__sub">Most agents have seen the downside of bulk internet leads. Shared names. Repeated calls. Low intent. Poor timing. Little control. Ensurance is building a different path.</p>
			</div>
			<div class="fa-compare">
				<div class="fa-compare__col fa-compare__col--bad">
					<span class="fa-compare__label"><?php echo wp_kses( ensurance_fa_icon( 'ban', 14 ), $fa_svg_allowed ); ?> Bulk lead buying</span>
					<h3 class="fa-compare__title">Demand without control</h3>
					<ul class="fa-compare__list">
						<?php foreach ( array( 'Shared names sold across a long list of buyers.', 'Repeated calls and low-intent contacts.', 'Poor timing and little context.', 'Paying just to compete in a crowded lead pool.' ) as $it ) : ?>
							<li><span class="fa-cross"><?php echo wp_kses( ensurance_fa_icon( 'x', 13 ), $fa_svg_allowed ); ?></span><?php echo esc_html( $it ); ?></li>
						<?php endforeach; ?>
					</ul>
				</div>
				<div class="fa-compare__col fa-compare__col--good">
					<span class="fa-compare__label"><?php echo wp_kses( ensurance_fa_icon( 'shield-check', 14 ), $fa_svg_allowed ); ?> Founding Agent Access</span>
					<h3 class="fa-compare__title">Review, then decide</h3>
					<ul class="fa-compare__list">
						<?php foreach ( array( 'Review eligible shopper request details when available.', 'Decide whether the opportunity fits before engaging.', 'Accept or pass — no chasing every name.', 'No bulk lead buying, no paying to compete in a pool.' ) as $it ) : ?>
							<li><span class="fa-tick"><?php echo wp_kses( ensurance_fa_icon( 'check', 13 ), $fa_svg_allowed ); ?></span><?php echo esc_html( $it ); ?></li>
						<?php endforeach; ?>
					</ul>
				</div>
			</div>
		</div>
	</section>

	<!-- ── HOW IT WORKS ─────────────────────────────────────────────── -->
	<section id="how" class="fa-section fa-how" aria-labelledby="fa-how-title">
		<div class="fa-container">
			<div class="fa-head fa-head--center">
				<span class="fa-eyebrow">How it works</span>
				<h2 id="fa-how-title" class="fa-h2">How Founding Agent Access works.</h2>
				<p class="fa-head__sub">A controlled path, the same every time — so you always know what a request is and what happens next.</p>
			</div>
			<ol class="fa-steps">
				<?php
				$fa_steps = array(
					array( 'user', 'Create your agency profile', 'Add your agency information, service areas, and contact details.' ),
					array( 'file-text', 'Review eligible request details', 'When eligible shopper requests are available in your state or service area, you may be able to review request details before deciding whether to engage.' ),
					array( 'check', 'Accept or pass', 'You choose whether the opportunity fits your agency.' ),
					array( 'message', 'Engage when it makes sense', 'If you accept, you can move forward with the shopper-authorized opportunity.' ),
					array( 'clock', 'Continue or cancel', 'After the 60 day access period, continue at $29 per month, or cancel before the subscription begins.' ),
				);
				foreach ( $fa_steps as $i => $s ) : ?>
					<li class="fa-step">
						<div class="fa-step__node">
							<?php echo wp_kses( ensurance_fa_icon( $s[0], 26 ), $fa_svg_allowed ); ?>
							<span class="fa-step__num"><?php echo esc_html( sprintf( '%02d', $i + 1 ) ); ?></span>
						</div>
						<div class="fa-step__body">
							<h3 class="fa-step__title"><?php echo esc_html( $s[1] ); ?></h3>
							<p class="fa-step__text"><?php echo esc_html( $s[2] ); ?></p>
						</div>
					</li>
				<?php endforeach; ?>
			</ol>
		</div>
	</section>

	<!-- ── WHY EARLY + WHO (dark band) ──────────────────────────────── -->
	<section class="fa-section" aria-labelledby="fa-why-title">
		<div class="fa-container">
			<div class="fa-band">
				<span class="fa-band__bar" aria-hidden="true"></span>
				<span class="fa-band__glow" aria-hidden="true"></span>
				<div class="fa-band__grid">
					<div>
						<span class="fa-eyebrow fa-eyebrow--accent">Why join early</span>
						<h2 id="fa-why-title" class="fa-h2 fa-h2--inverse">An early position as Ensurance opens your state.</h2>
						<p class="fa-band__body">Founding Agent Access gives selected agents an early position as Ensurance opens access in selected states. If your agency wants a more organized way to connect with serious insurance shoppers, this is the right time to get in early while Ensurance is building state-by-state coverage.</p>
					</div>
					<div class="fa-who">
						<p class="fa-who__title">Who this is for</p>
						<p class="fa-who__intro">Founding Agent Access is for licensed insurance agents and agencies that want a better way to review shopper opportunities. It may be a fit if you want:</p>
						<ul class="fa-who__list">
							<?php foreach ( array( 'More organized shopper requests', 'More control over what you pursue', 'A way to accept or pass before engaging', 'Early access in selected states', 'A profile on Ensurance', 'A lower-pressure alternative to bulk lead programs' ) as $w ) : ?>
								<li><span class="fa-tick fa-tick--dark"><?php echo wp_kses( ensurance_fa_icon( 'check', 13 ), $fa_svg_allowed ); ?></span><?php echo esc_html( $w ); ?></li>
							<?php endforeach; ?>
						</ul>
					</div>
				</div>
			</div>
		</div>
	</section>

	<!-- ── SUBSCRIPTION TERMS ───────────────────────────────────────── -->
	<section class="fa-section" aria-labelledby="fa-terms-title">
		<div class="fa-container">
			<div class="fa-head fa-head--center">
				<span class="fa-eyebrow">Subscription terms</span>
				<h2 id="fa-terms-title" class="fa-h2">Clear terms, visible before you continue.</h2>
				<p class="fa-head__sub">You will see these terms at plan selection. They will not be hidden or replaced with vague subscription language.</p>
			</div>
			<div class="fa-terms">
				<div class="fa-terms__card">
					<span class="fa-terms__tag">60 Day Founding Agent Access</span>
					<p class="fa-terms__text">By continuing, I understand this access is free for 60 days. After the 60 day access period, Founding Agent Access may continue at $29 per month unless canceled before the subscription begins.</p>
				</div>
				<div class="fa-terms__card">
					<span class="fa-terms__tag">Founding Agent Access · $29 per month</span>
					<p class="fa-terms__text">By continuing, I agree to subscribe to Founding Agent Access for $29 per month and authorize Ensurance to charge the payment method provided on a recurring monthly basis until canceled.</p>
				</div>
			</div>
			<p class="fa-terms__cancel"><?php echo wp_kses( ensurance_fa_icon( 'circle-check', 16, 'fa-terms__cancel-icon' ), $fa_svg_allowed ); ?> Cancel anytime. Access continues through the current billing period.</p>
		</div>
	</section>

	<!-- ── FAQ ──────────────────────────────────────────────────────── -->
	<section id="faq" class="fa-section fa-faq" aria-labelledby="fa-faq-title">
		<div class="fa-faq__inner">
			<div class="fa-head fa-head--center">
				<span class="fa-eyebrow">Questions</span>
				<h2 id="fa-faq-title" class="fa-h2">Founding Agent Access questions.</h2>
			</div>
			<div class="fa-accordion">
				<?php foreach ( $fa_faq as $i => $qa ) : ?>
					<div class="fa-acc__item<?php echo 0 === $i ? ' is-open' : ''; ?>">
						<button type="button" class="fa-acc__q" aria-expanded="<?php echo 0 === $i ? 'true' : 'false'; ?>" aria-controls="fa-acc-a-<?php echo (int) $i; ?>" id="fa-acc-q-<?php echo (int) $i; ?>">
							<span><?php echo esc_html( $qa[0] ); ?></span>
							<?php echo wp_kses( ensurance_fa_icon( 'chevron-down', 20, 'fa-acc__chevron' ), $fa_svg_allowed ); ?>
						</button>
						<div class="fa-acc__a" id="fa-acc-a-<?php echo (int) $i; ?>" role="region" aria-labelledby="fa-acc-q-<?php echo (int) $i; ?>">
							<p><?php echo esc_html( $qa[1] ); ?></p>
						</div>
					</div>
				<?php endforeach; ?>
			</div>
			<p class="fa-faq__fine"><?php echo esc_html( $fa_disclaimer ); ?></p>
		</div>
	</section>

	<!-- ── FINAL CTA ────────────────────────────────────────────────── -->
	<section class="fa-section fa-final" aria-labelledby="fa-final-title">
		<div class="fa-container">
			<div class="fa-final__panel">
				<span class="fa-final__bar" aria-hidden="true"></span>
				<h2 id="fa-final-title" class="fa-h2 fa-h2--inverse">Join early in your state as a Founding Agent.</h2>
				<p class="fa-final__body">Review organized shopper requests without bulk lead buying. <?php echo esc_html( $fa_disclaimer ); ?></p>
				<div class="fa-final__actions">
					<a class="fa-btn fa-btn--solid" href="<?php echo esc_url( $fa_geo_60day ); ?>" data-event="final_start_60_day_click">Start 60 day access <?php echo wp_kses( ensurance_fa_icon( 'arrow-right', 18 ), $fa_svg_allowed ); ?></a>
					<a class="fa-btn fa-btn--ghost" href="<?php echo esc_url( $fa_geo_monthly ); ?>" data-event="final_join_founding_click">Join as a Founding Agent</a>
				</div>
				<p class="fa-final__contact">Questions first? <a href="<?php echo esc_url( home_url( '/contact' ) ); ?>" data-event="agent_contact_click">Contact Ensurance</a>.</p>
			</div>
		</div>
	</section>

</main>

<?php get_footer( 'home' ); ?>
