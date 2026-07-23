<?php
/**
 * Publish Your Agency (/publish-your-agency) — "Calm Intelligence" form shell.
 *
 * Built from the approved Claude design (Ensurance Publish Your Agency (form
 * shell), standalone). This is the GeoDirectory add-listing route reached from
 * the two /pricing-plans CTAs:
 *   - "Start 60 day access"      → /publish-your-agency/insurance-agencies/?package_id=14
 *   - "Join as a Founding Agent" → /publish-your-agency/insurance-agencies/?package_id=16
 *
 * THE FORM IS NOT REBUILT OR RESTYLED. It is rendered by the exact same
 * shortcodes the previous Kadence block content used, copied verbatim from the
 * `sd_shortcode` attribute of the geodirectory/geodir-widget-* blocks on page
 * 18261 — including show_login='true', which is what renders the Login /
 * Register gate for logged-out agents. This template only supplies the framing
 * around it. Do not add CSS that targets elements inside #pya-form-slot.
 *
 * CHROME: this is the first page on the AGENT side of the site, which now has
 * its own header. get_header('agent') renders header-agent.php (logo only, no
 * nav, no buttons) — see that file for the shopper/agent split. The footer is
 * the global one, get_footer('home') / footer-home.php, shared with the homepage
 * and /pricing-plans. Both are styled by assets/home.css, which this page loads;
 * assets/publish-your-agency.css layers the page-specific sections on top, the
 * same arrangement /pricing-plans uses.
 *
 * Because home.css is loaded, its base element styles (body font, headings,
 * links) now cascade into the GeoDirectory form, so the form picks up the site
 * typography instead of the old Kadence font. That is intentional and approved —
 * the form's markup, fields and behaviour are still untouched, and no selector
 * here targets anything inside it.
 *
 * CONTENT REMOVED: the previous page rendered synced block 20325 below the form
 * (~1,500 words of Active Shopper / Pay-Per-Lead terms + a 9-question Yoast FAQ
 * that emitted FAQPage schema). The design ends after the form card, so that is
 * no longer output. Block 20325 and page 18261's block content are untouched in
 * the database — deleting this file restores the old page exactly. The removed
 * copy, and the caveats for re-placing it, are archived in
 * docs/publish-your-agency-removed-content.md.
 *
 * This template renders via the page-{slug}.php hierarchy for the
 * /publish-your-agency/ page (slug `publish-your-agency`, ID 18261), so it
 * overrides the previous block content with no DB edit. Note that means
 * body_class() emits `page-template-default`, not `page-template-page-…-php` —
 * see the background-override note in assets/publish-your-agency.css.
 *
 * SEO: title / meta description / canonical / robots stay owned by Yoast and are
 * emitted through wp_head(). This template outputs none of them.
 */

/**
 * Resolve the plan the agent arrived with, from the GeoDirectory Pricing Manager
 * package id in the query string. These ids are the existing packages — do not
 * change them (see the GeoDirectory coupling note in page-pricing-plans.php).
 *
 * Anything other than the two known ids (including a missing param, which is how
 * the page is reached from a direct link or the legacy nav) falls back to neutral
 * copy rather than guessing at a plan.
 */
$pya_package_id = isset( $_GET['package_id'] ) ? absint( wp_unslash( $_GET['package_id'] ) ) : 0;

switch ( $pya_package_id ) {
	case 14: // 60 Day Founding Agent Access
		$pya_eyebrow = '60-Day Founding Agent Access';
		$pya_lede    = 'Complete the form below to create your agency profile and start your 60 days of Founding Agent Access.';
		break;
	case 16: // Founding Agent Access $29/mo
		$pya_eyebrow = 'Founding Agent Access · $29/mo';
		$pya_lede    = 'Complete the form below to create your agency profile and join as a Founding Agent.';
		break;
	default:
		$pya_eyebrow = 'Founding Agent Access';
		$pya_lede    = 'Complete the form below to create your agency profile and get started with Founding Agent Access.';
		break;
}

/**
 * Inline Lucide glyphs (stroke 2, round caps) used on this page.
 * Namespaced to this template so it cannot collide with ensurance_fa_icon().
 */
if ( ! function_exists( 'ensurance_pya_icon' ) ) {
	function ensurance_pya_icon( $name, $size = 20 ) {
		$paths = array(
			'file-text' => '<path d="M15 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V7Z"/><path d="M14 2v4a2 2 0 0 0 2 2h4"/><path d="M16 13H8"/><path d="M16 17H8"/><path d="M10 9H8"/>',
			'lock'      => '<rect width="18" height="11" x="3" y="11" rx="2" ry="2"/><path d="M7 11V7a5 5 0 0 1 10 0v4"/>',
		);
		if ( ! isset( $paths[ $name ] ) ) {
			return '';
		}
		return '<svg class="pya-icon" width="' . (int) $size . '" height="' . (int) $size . '" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true" focusable="false">' . $paths[ $name ] . '</svg>';
	}
}

$pya_svg_allowed = array(
	'svg'  => array( 'class' => true, 'width' => true, 'height' => true, 'viewbox' => true, 'fill' => true, 'stroke' => true, 'stroke-width' => true, 'stroke-linecap' => true, 'stroke-linejoin' => true, 'aria-hidden' => true, 'focusable' => true ),
	'path' => array( 'd' => true ),
	'rect' => array( 'width' => true, 'height' => true, 'x' => true, 'y' => true, 'rx' => true, 'ry' => true ),
);

get_header( 'agent' );
?>

<main id="main" class="pya-page">

	<!-- ── Page head ─────────────────────────────────────────────── -->
	<section class="pya-head">
		<div class="pya-head__rule" aria-hidden="true"></div>
		<div class="pya-head__inner">

			<nav class="pya-crumbs" aria-label="Breadcrumb">
				<a href="<?php echo esc_url( home_url( '/' ) ); ?>">Home</a>
				<span class="pya-crumbs__sep" aria-hidden="true">/</span>
				<span class="pya-crumbs__current" aria-current="page">Publish Your Agency</span>
			</nav>

			<span class="pya-eyebrow">
				<span class="pya-eyebrow__dot" aria-hidden="true"></span>
				<?php echo esc_html( $pya_eyebrow ); ?>
			</span>

			<h1 class="pya-title">Publish your agency</h1>

			<p class="pya-lede"><?php echo esc_html( $pya_lede ); ?></p>

		</div>
	</section>

	<!-- ── Form shell ────────────────────────────────────────────── -->
	<section class="pya-form-section">
		<div class="pya-card">

			<div class="pya-card__bar">
				<?php echo wp_kses( ensurance_pya_icon( 'file-text', 16 ), $pya_svg_allowed ); ?>
				<span class="pya-card__label">Agency profile</span>
			</div>

			<div class="pya-card__body" id="pya-form-slot">
				<?php
				/*
				 * ===== EXISTING GEODIRECTORY FORM — DO NOT MODIFY =====
				 * Both shortcodes below are copied verbatim from the `sd_shortcode`
				 * attributes of the blocks that were on page 18261, so the rendered
				 * form is byte-for-byte what agents saw before the redesign.
				 *
				 * [gd_notifications] carries GeoDirectory's submit success/error
				 * messages — it must stay above the form or submission feedback
				 * disappears.
				 */
				echo do_shortcode( '[gd_notifications]' );
				echo do_shortcode( "[gd_add_listing post_type=''  show_login='true'  login_msg=''  container=''  mapzoom='0'  label_type=''  bg=''  mt=''  mr=''  mb='3'  ml=''  pt=''  pr=''  pb=''  pl=''  border=''  rounded=''  rounded_size=''  shadow='' ]" );
				?>
			</div>

		</div>

		<p class="pya-assurance">
			<?php echo wp_kses( ensurance_pya_icon( 'lock', 15 ), $pya_svg_allowed ); ?>
			Your information is submitted securely and only used to verify your agency.
		</p>
	</section>

</main>

<?php get_footer( 'home' ); ?>
