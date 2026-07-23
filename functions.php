<?php
/**
 * Kadence Child Theme — functions.php
 *
 * STRUCTURE:
 *   1. Kadence child theme style enqueue
 *   2. Marketing pages asset enqueue
 *   3. GeoDirectory customizations
 *   4. UserWP customizations
 *   5. Email, lead & Tawk.to
 *   6. Admin customizations
 *   7. Ninja Forms
 *   8. Lead page shortcode
 *
 * RULES:
 *   - Add new functions below the relevant section
 *   - Never modify or remove existing functions
 *   - Marketing-page functions go in section 2
 */

// ============================================================================
// 1. KADENCE CHILD THEME STYLES
// ============================================================================

function kadence_child_enqueue_styles()
{
    wp_enqueue_style(
        'kadence-parent-style',
        get_template_directory_uri() . '/style.css'
    );
    wp_enqueue_style(
        'kadence-child-style',
        get_stylesheet_directory_uri() . '/style.css',
        array('kadence-parent-style'),
        strtotime('now')
    );
}
add_action('wp_enqueue_scripts', 'kadence_child_enqueue_styles');

// ============================================================================
// 2. MARKETING PAGES — ASSET ENQUEUE
// ============================================================================
// Loads marketing CSS and JS only on marketing pages.
// Add new marketing page conditions here as pages are built.

function ensurance_marketing_assets() {
    if (is_front_page() || is_page_template('page-home.php') || is_page_template('page-how-it-works.php') || is_page_template('page-coverage.php') || is_page_template('page-for-shoppers.php')) {
        wp_enqueue_style(
            'ensurance-marketing',
            get_stylesheet_directory_uri() . '/assets/marketing.css',
            array(),
            filemtime(get_stylesheet_directory() . '/assets/marketing.css')
        );
        wp_enqueue_script(
            'ensurance-marketing',
            get_stylesheet_directory_uri() . '/assets/marketing.js',
            array(),
            filemtime(get_stylesheet_directory() . '/assets/marketing.js'),
            true
        );
    }

    if (is_page_template('page-investor-brief.php')) {
        wp_enqueue_style(
            'ensurance-investor',
            get_stylesheet_directory_uri() . '/assets/investor.css',
            array(),
            filemtime(get_stylesheet_directory() . '/assets/investor.css')
        );
        wp_enqueue_script(
            'ensurance-investor',
            get_stylesheet_directory_uri() . '/assets/investor.js',
            array(),
            filemtime(get_stylesheet_directory() . '/assets/investor.js'),
            true
        );
    }

    // /start — guided intake. Loads the shared marketing CSS/JS so the
    // header, footer, buttons, and design tokens stay consistent, plus
    // page-specific styles and the wizard controller.
    if (is_page_template('page-start.php')) {
        wp_enqueue_style(
            'ensurance-marketing',
            get_stylesheet_directory_uri() . '/assets/marketing.css',
            array(),
            filemtime(get_stylesheet_directory() . '/assets/marketing.css')
        );
        wp_enqueue_script(
            'ensurance-marketing',
            get_stylesheet_directory_uri() . '/assets/marketing.js',
            array(),
            filemtime(get_stylesheet_directory() . '/assets/marketing.js'),
            true
        );
        wp_enqueue_style(
            'ensurance-start',
            get_stylesheet_directory_uri() . '/assets/start.css',
            array('ensurance-marketing'),
            filemtime(get_stylesheet_directory() . '/assets/start.css')
        );
        wp_enqueue_script(
            'ensurance-start',
            get_stylesheet_directory_uri() . '/assets/start.js',
            array(),
            filemtime(get_stylesheet_directory() . '/assets/start.js'),
            true
        );
    }
}
add_action('wp_enqueue_scripts', 'ensurance_marketing_assets');

// Google Fonts for marketing pages — Bricolage Grotesque (display/logo),
// Manrope (UI/headings), Inter (body). Loaded only on marketing pages.
function ensurance_marketing_fonts() {
    if (is_front_page() || is_page_template('page-home.php') || is_page_template('page-how-it-works.php') || is_page_template('page-coverage.php') || is_page_template('page-for-shoppers.php')) {
        wp_enqueue_style(
            'ensurance-marketing-fonts',
            'https://fonts.googleapis.com/css2?family=Bricolage+Grotesque:opsz,wght@12..96,400;12..96,500;12..96,700;12..96,800&family=Inter:wght@400;500;600;700&family=Manrope:wght@400;500;600;700;800&display=swap',
            array(),
            null
        );
    }

    if (is_page_template('page-investor-brief.php')) {
        wp_enqueue_style(
            'ensurance-investor-fonts',
            'https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800&display=swap',
            array(),
            null
        );
    }

    if (is_page_template('page-start.php')) {
        wp_enqueue_style(
            'ensurance-marketing-fonts',
            'https://fonts.googleapis.com/css2?family=Bricolage+Grotesque:opsz,wght@12..96,400;12..96,500;12..96,700;12..96,800&family=Inter:wght@400;500;600;700&family=Manrope:wght@400;500;600;700;800&display=swap',
            array(),
            null
        );
    }
}
add_action('wp_enqueue_scripts', 'ensurance_marketing_fonts');

// ============================================================================
// 2b. HOMEPAGE (AUTO-FORWARD DESIGN) — SELF-CONTAINED ASSETS
// ============================================================================
// The homepage ships a complete, standalone design system (assets/home.css +
// assets/home.js) ported verbatim from the bespoke package. Its generic
// selectors (.hero, .section, .container, .btn-primary) would collide with the
// shared marketing.css, so on the homepage we DEQUEUE the marketing bundle and
// fonts (enqueued at the default priority 10 by the functions above) and load
// the homepage's own assets instead. Runs at priority 20 so the dequeue applies
// after the priority-10 enqueues. New function — existing functions untouched.

function ensurance_home_assets() {
    if ( ! ( is_front_page() || is_page_template('page-home.php') ) ) {
        return;
    }

    // Drop the shared marketing bundle so it cannot fight the homepage design.
    wp_dequeue_style('ensurance-marketing');
    wp_dequeue_script('ensurance-marketing');
    wp_dequeue_style('ensurance-marketing-fonts');

    // Calm Intelligence type system: Albert Sans (display H1–H3), Rubik (body/UI),
    // JetBrains Mono (step numbers, status labels). One stylesheet, all three.
    wp_enqueue_style(
        'ensurance-home-fonts',
        'https://fonts.googleapis.com/css2?family=Albert+Sans:wght@700;800;900&family=Rubik:wght@300;400;500&family=JetBrains+Mono:wght@400;500&display=swap',
        array(),
        null
    );
    wp_enqueue_style(
        'ensurance-home',
        get_stylesheet_directory_uri() . '/assets/home.css',
        array(),
        filemtime(get_stylesheet_directory() . '/assets/home.css')
    );
    wp_enqueue_script(
        'ensurance-home',
        get_stylesheet_directory_uri() . '/assets/home.js',
        array(),
        filemtime(get_stylesheet_directory() . '/assets/home.js'),
        true
    );
}
add_action('wp_enqueue_scripts', 'ensurance_home_assets', 20);

// ============================================================================
// 2b-ii. HOW IT WORKS (CALM INTELLIGENCE REDESIGN) — SELF-CONTAINED ASSETS
// ============================================================================
// /how-it-works ships the same standalone design system as the homepage. It
// reuses assets/home.css + assets/home.js for tokens, chrome and base
// components, and layers assets/how-it-works.css + assets/how-it-works.js on
// top for the page-specific sections (timeline spine, dark quote-options panel)
// and the scroll-reveal transitions. As with the homepage, we DEQUEUE the
// shared marketing bundle (enqueued at priority 10) so its generic selectors
// cannot fight this design. Runs at priority 20 so the dequeue lands after the
// priority-10 enqueues. New function — existing functions untouched.

function ensurance_how_it_works_assets() {
    if ( ! is_page_template('page-how-it-works.php') ) {
        return;
    }

    // Drop the shared marketing bundle so it cannot fight this design.
    wp_dequeue_style('ensurance-marketing');
    wp_dequeue_script('ensurance-marketing');
    wp_dequeue_style('ensurance-marketing-fonts');

    // Shared Calm Intelligence type system + base (same as the homepage).
    wp_enqueue_style(
        'ensurance-home-fonts',
        'https://fonts.googleapis.com/css2?family=Albert+Sans:wght@700;800;900&family=Rubik:wght@300;400;500&family=JetBrains+Mono:wght@400;500&display=swap',
        array(),
        null
    );
    wp_enqueue_style(
        'ensurance-home',
        get_stylesheet_directory_uri() . '/assets/home.css',
        array(),
        filemtime(get_stylesheet_directory() . '/assets/home.css')
    );
    wp_enqueue_script(
        'ensurance-home',
        get_stylesheet_directory_uri() . '/assets/home.js',
        array(),
        filemtime(get_stylesheet_directory() . '/assets/home.js'),
        true
    );

    // Page-specific layer — loaded AFTER home.css/home.js via dependency.
    wp_enqueue_style(
        'ensurance-how-it-works',
        get_stylesheet_directory_uri() . '/assets/how-it-works.css',
        array('ensurance-home'),
        filemtime(get_stylesheet_directory() . '/assets/how-it-works.css')
    );
    wp_enqueue_script(
        'ensurance-how-it-works',
        get_stylesheet_directory_uri() . '/assets/how-it-works.js',
        array('ensurance-home'),
        filemtime(get_stylesheet_directory() . '/assets/how-it-works.js'),
        true
    );
}
add_action('wp_enqueue_scripts', 'ensurance_how_it_works_assets', 20);

// ============================================================================
// 2b-iii. COVERAGE TYPES (CALM INTELLIGENCE REDESIGN) — SELF-CONTAINED ASSETS
// ============================================================================
// /coverage ships the same standalone design system as the homepage. It reuses
// assets/home.css + assets/home.js for tokens, chrome and base components, and
// layers assets/coverage.css + assets/coverage.js on top for the page-specific
// sections (light hero, tabbed coverage picker, dark controlled-flow panel) and
// the tab + scroll-reveal interactions. As with the homepage, we DEQUEUE the
// shared marketing bundle (enqueued at priority 10) so its generic selectors
// cannot fight this design. Runs at priority 20 so the dequeue lands after the
// priority-10 enqueues. New function — existing functions untouched.

function ensurance_coverage_assets() {
    if ( ! is_page_template('page-coverage.php') ) {
        return;
    }

    // Drop the shared marketing bundle so it cannot fight this design.
    wp_dequeue_style('ensurance-marketing');
    wp_dequeue_script('ensurance-marketing');
    wp_dequeue_style('ensurance-marketing-fonts');

    // Shared Calm Intelligence type system + base (same as the homepage).
    wp_enqueue_style(
        'ensurance-home-fonts',
        'https://fonts.googleapis.com/css2?family=Albert+Sans:wght@700;800;900&family=Rubik:wght@300;400;500&family=JetBrains+Mono:wght@400;500&display=swap',
        array(),
        null
    );
    wp_enqueue_style(
        'ensurance-home',
        get_stylesheet_directory_uri() . '/assets/home.css',
        array(),
        filemtime(get_stylesheet_directory() . '/assets/home.css')
    );
    wp_enqueue_script(
        'ensurance-home',
        get_stylesheet_directory_uri() . '/assets/home.js',
        array(),
        filemtime(get_stylesheet_directory() . '/assets/home.js'),
        true
    );

    // Page-specific layer — loaded AFTER home.css/home.js via dependency.
    wp_enqueue_style(
        'ensurance-coverage',
        get_stylesheet_directory_uri() . '/assets/coverage.css',
        array('ensurance-home'),
        filemtime(get_stylesheet_directory() . '/assets/coverage.css')
    );
    wp_enqueue_script(
        'ensurance-coverage',
        get_stylesheet_directory_uri() . '/assets/coverage.js',
        array('ensurance-home'),
        filemtime(get_stylesheet_directory() . '/assets/coverage.js'),
        true
    );
}
add_action('wp_enqueue_scripts', 'ensurance_coverage_assets', 20);

// ============================================================================
// 2b-iv. TRUST CENTER (CALM INTELLIGENCE REDESIGN) — SELF-CONTAINED ASSETS
// ============================================================================
// /trust-center ships the same standalone design system as the homepage. It
// reuses assets/home.css + assets/home.js for tokens, chrome and base
// components, and layers assets/trust-center.css + assets/trust-center.js on
// top for the page-specific document layout (light hero, sticky table of
// contents, numbered explainer sections, noisy-vs-controlled compare) and the
// TOC scroll-spy. As with the homepage, we DEQUEUE the shared marketing bundle
// (if enqueued at priority 10) so its generic selectors cannot fight this
// design. Runs at priority 20 so the dequeue lands after the priority-10
// enqueues. New function — existing functions untouched.

function ensurance_trust_center_assets() {
    if ( ! is_page_template('page-trust-center.php') ) {
        return;
    }

    // Drop the shared marketing bundle so it cannot fight this design.
    wp_dequeue_style('ensurance-marketing');
    wp_dequeue_script('ensurance-marketing');
    wp_dequeue_style('ensurance-marketing-fonts');

    // Shared Calm Intelligence type system + base (same as the homepage).
    wp_enqueue_style(
        'ensurance-home-fonts',
        'https://fonts.googleapis.com/css2?family=Albert+Sans:wght@700;800;900&family=Rubik:wght@300;400;500&family=JetBrains+Mono:wght@400;500&display=swap',
        array(),
        null
    );
    wp_enqueue_style(
        'ensurance-home',
        get_stylesheet_directory_uri() . '/assets/home.css',
        array(),
        filemtime(get_stylesheet_directory() . '/assets/home.css')
    );
    wp_enqueue_script(
        'ensurance-home',
        get_stylesheet_directory_uri() . '/assets/home.js',
        array(),
        filemtime(get_stylesheet_directory() . '/assets/home.js'),
        true
    );

    // Page-specific layer — loaded AFTER home.css/home.js via dependency.
    wp_enqueue_style(
        'ensurance-trust-center',
        get_stylesheet_directory_uri() . '/assets/trust-center.css',
        array('ensurance-home'),
        filemtime(get_stylesheet_directory() . '/assets/trust-center.css')
    );
    wp_enqueue_script(
        'ensurance-trust-center',
        get_stylesheet_directory_uri() . '/assets/trust-center.js',
        array('ensurance-home'),
        filemtime(get_stylesheet_directory() . '/assets/trust-center.js'),
        true
    );
}
add_action('wp_enqueue_scripts', 'ensurance_trust_center_assets', 20);

// ============================================================================
// 2b-v. FOR AGENTS (CALM INTELLIGENCE REDESIGN) — SELF-CONTAINED ASSETS
// ============================================================================
// /for-agents ships the same standalone design system as the homepage. It
// reuses assets/home.css + assets/home.js for tokens, chrome and base
// components, and layers assets/for-agents.css + assets/for-agents.js on top
// for the page-specific sections (dark asymmetric hero with the structured-
// request preview, bulk-leads vs. structured-request compare, dark controlled-
// flow panel, connected request stepper, coverage-line chips, participation
// tiles, agent-access CTA card) and the scroll-reveal motion. As with the
// homepage, we DEQUEUE the shared marketing bundle (if enqueued at priority 10)
// so its generic selectors cannot fight this design. Runs at priority 20 so the
// dequeue lands after the priority-10 enqueues. New function — existing
// functions untouched.

function ensurance_for_agents_assets() {
    if ( ! is_page_template('page-for-agents.php') ) {
        return;
    }

    // Drop the shared marketing bundle so it cannot fight this design.
    wp_dequeue_style('ensurance-marketing');
    wp_dequeue_script('ensurance-marketing');
    wp_dequeue_style('ensurance-marketing-fonts');

    // Shared Calm Intelligence type system + base (same as the homepage).
    wp_enqueue_style(
        'ensurance-home-fonts',
        'https://fonts.googleapis.com/css2?family=Albert+Sans:wght@700;800;900&family=Rubik:wght@300;400;500&family=JetBrains+Mono:wght@400;500&display=swap',
        array(),
        null
    );
    wp_enqueue_style(
        'ensurance-home',
        get_stylesheet_directory_uri() . '/assets/home.css',
        array(),
        filemtime(get_stylesheet_directory() . '/assets/home.css')
    );
    wp_enqueue_script(
        'ensurance-home',
        get_stylesheet_directory_uri() . '/assets/home.js',
        array(),
        filemtime(get_stylesheet_directory() . '/assets/home.js'),
        true
    );

    // Page-specific layer — loaded AFTER home.css/home.js via dependency.
    wp_enqueue_style(
        'ensurance-for-agents',
        get_stylesheet_directory_uri() . '/assets/for-agents.css',
        array('ensurance-home'),
        filemtime(get_stylesheet_directory() . '/assets/for-agents.css')
    );
    wp_enqueue_script(
        'ensurance-for-agents',
        get_stylesheet_directory_uri() . '/assets/for-agents.js',
        array('ensurance-home'),
        filemtime(get_stylesheet_directory() . '/assets/for-agents.js'),
        true
    );
}
add_action('wp_enqueue_scripts', 'ensurance_for_agents_assets', 20);

// ============================================================================
// 2b-v-b. FOUNDING AGENT ACCESS (/pricing-plans) — SELF-CONTAINED ASSETS
// ============================================================================
// /pricing-plans is repositioned as "Founding Agent Access" and ships the same
// standalone Calm Intelligence design system as the homepage. It reuses
// assets/home.css + assets/home.js for tokens, chrome and base, and layers
// assets/pricing-plans.css + assets/pricing-plans.js on top for the page-
// specific sections (dark hero + plan-summary card, two access cards wired to
// the existing GeoDirectory packages, bulk-leads compare, request stepper,
// dark why/who band, subscription-terms cards, FAQ accordion). As with the
// homepage, we DEQUEUE the shared marketing bundle so its generic selectors
// cannot fight this design. Runs at priority 20 so the dequeue lands after the
// priority-10 enqueues. New function — existing functions untouched.
//
// Guard note: this template applies to /pricing-plans/ via the page-{slug}.php
// hierarchy (not an assigned "Template Name"), so is_page_template() is not
// reliable here — is_page('pricing-plans') is. See the is_page_template DB-meta
// gotcha in prior work.

function ensurance_pricing_plans_assets() {
    if ( ! is_page('pricing-plans') ) {
        return;
    }

    // Drop the shared marketing bundle so it cannot fight this design.
    wp_dequeue_style('ensurance-marketing');
    wp_dequeue_script('ensurance-marketing');
    wp_dequeue_style('ensurance-marketing-fonts');

    // Shared Calm Intelligence type system + base (same as the homepage).
    wp_enqueue_style(
        'ensurance-home-fonts',
        'https://fonts.googleapis.com/css2?family=Albert+Sans:wght@700;800;900&family=Rubik:wght@300;400;500&family=JetBrains+Mono:wght@400;500&display=swap',
        array(),
        null
    );
    wp_enqueue_style(
        'ensurance-home',
        get_stylesheet_directory_uri() . '/assets/home.css',
        array(),
        filemtime(get_stylesheet_directory() . '/assets/home.css')
    );
    wp_enqueue_script(
        'ensurance-home',
        get_stylesheet_directory_uri() . '/assets/home.js',
        array(),
        filemtime(get_stylesheet_directory() . '/assets/home.js'),
        true
    );

    // Page-specific layer — loaded AFTER home.css/home.js via dependency.
    wp_enqueue_style(
        'ensurance-pricing-plans',
        get_stylesheet_directory_uri() . '/assets/pricing-plans.css',
        array('ensurance-home'),
        filemtime(get_stylesheet_directory() . '/assets/pricing-plans.css')
    );
    wp_enqueue_script(
        'ensurance-pricing-plans',
        get_stylesheet_directory_uri() . '/assets/pricing-plans.js',
        array('ensurance-home'),
        filemtime(get_stylesheet_directory() . '/assets/pricing-plans.js'),
        true
    );
}
add_action('wp_enqueue_scripts', 'ensurance_pricing_plans_assets', 20);

// ============================================================================
// 2b-v-c. PUBLISH YOUR AGENCY (/publish-your-agency) — SELF-CONTAINED ASSETS
// ============================================================================
// /publish-your-agency is the GeoDirectory add-listing route the two
// /pricing-plans CTAs point at (?package_id=14 and 16). page-publish-your-agency.php
// wraps the EXISTING GeoDirectory form in the approved Calm Intelligence shell.
//
// This is the first page on the AGENT side of the site: header-agent.php (logo
// only) plus the global footer-home.php. Both are styled by assets/home.css, so
// this page loads the same shared base as the homepage and /pricing-plans and
// layers assets/publish-your-agency.css on top. assets/home.js comes along
// because footer-home.php ships the mobile sticky CTA it drives; it is
// null-guarded around the nav toggle, so the nav-less agent header is fine.
//
// Note this means home.css's base element styles cascade into the GeoDirectory
// form, which now inherits the site typography rather than Kadence's. That is
// intentional and approved — see page-publish-your-agency.php.
//
// Nothing is dequeued: the shared marketing bundle does not load on this page
// (see ensurance_marketing_assets above), and GeoDirectory's own add-listing
// assets must be left alone.
//
// Guard note: this template applies via the page-{slug}.php hierarchy, so
// is_page_template() is not reliable here — is_page('publish-your-agency') is.
// See the is_page_template DB-meta gotcha noted on /pricing-plans above. The
// slug check also covers the /publish-your-agency/insurance-agencies/ sub-route
// GeoDirectory rewrites onto this same page. New function — existing functions
// untouched.

function ensurance_publish_your_agency_assets() {
    if ( ! is_page('publish-your-agency') ) {
        return;
    }

    // Shared Calm Intelligence type system + base (same as the homepage).
    wp_enqueue_style(
        'ensurance-home-fonts',
        'https://fonts.googleapis.com/css2?family=Albert+Sans:wght@700;800;900&family=Rubik:wght@300;400;500&family=JetBrains+Mono:wght@400;500&display=swap',
        array(),
        null
    );
    wp_enqueue_style(
        'ensurance-home',
        get_stylesheet_directory_uri() . '/assets/home.css',
        array(),
        filemtime(get_stylesheet_directory() . '/assets/home.css')
    );
    wp_enqueue_script(
        'ensurance-home',
        get_stylesheet_directory_uri() . '/assets/home.js',
        array(),
        filemtime(get_stylesheet_directory() . '/assets/home.js'),
        true
    );

    // Page-specific layer — loaded AFTER home.css via dependency.
    wp_enqueue_style(
        'ensurance-publish-your-agency',
        get_stylesheet_directory_uri() . '/assets/publish-your-agency.css',
        array('ensurance-home'),
        filemtime(get_stylesheet_directory() . '/assets/publish-your-agency.css')
    );
}
add_action('wp_enqueue_scripts', 'ensurance_publish_your_agency_assets', 20);

// Let page-publish-your-agency.php actually win on this route.
//
// /publish-your-agency is a GeoDirectory page (body class `geodir-page-add`), and
// GeoDir_Template_Loader::template_loader filters `template_include` on every GD
// page. Its search list ends with 'geodirectory.php' then 'page.php', and
// locate_template() finds Kadence's page.php — so WordPress's own
// page-{slug}.php hierarchy match is thrown away and the template never renders.
// That is why this needs a filter at all, unlike the other code-driven pages.
//
// Rather than fight template_include after the fact, hook GeoDirectory's own
// extension point and put our template at the front of the list it searches.
// locate_template() then resolves it from the child theme first. If the file is
// ever deleted, locate_template() simply skips it and GD falls through to
// page.php as before, restoring the previous page — no fatal, no white screen.
// New function — existing functions untouched.

function ensurance_publish_your_agency_gd_template( $search_files, $default_file ) {
    if ( is_page('publish-your-agency') ) {
        array_unshift( $search_files, 'page-publish-your-agency.php' );
    }
    return $search_files;
}
add_filter('geodir_template_loader_files', 'ensurance_publish_your_agency_gd_template', 10, 2);

// ============================================================================
// 2b-vi. AUTO INSURANCE (CALM INTELLIGENCE REDESIGN) — SELF-CONTAINED ASSETS
// ============================================================================
// /auto-insurance-quote-request ships the same standalone design system as the
// homepage. It reuses assets/home.css + assets/home.js for tokens, chrome and
// base components, and layers assets/auto-insurance-quote-request.css +
// assets/auto-insurance-quote-request.js on top for the page-specific sections
// (light hero with the guided-request stage track, scattered-vs-structured
// compare, the four request-area cards, the four-step process, the dark licensed-
// review panel) and the scroll-reveal motion. As with the homepage, we DEQUEUE
// the shared marketing bundle (if enqueued at priority 10) so its generic
// selectors cannot fight this design. Runs at priority 20 so the dequeue lands
// after the priority-10 enqueues. The slug check (is_page) is a belt-and-braces
// fallback so the assets load even if the template is matched by the page-{slug}
// hierarchy rather than an explicit Template assignment. New function — existing
// functions untouched.

function ensurance_auto_insurance_quote_request_assets() {
    if ( ! is_page_template('page-auto-insurance-quote-request.php')
        && ! is_page('auto-insurance-quote-request') ) {
        return;
    }

    // Drop the shared marketing bundle so it cannot fight this design.
    wp_dequeue_style('ensurance-marketing');
    wp_dequeue_script('ensurance-marketing');
    wp_dequeue_style('ensurance-marketing-fonts');

    // Shared Calm Intelligence type system + base (same as the homepage).
    wp_enqueue_style(
        'ensurance-home-fonts',
        'https://fonts.googleapis.com/css2?family=Albert+Sans:wght@700;800;900&family=Rubik:wght@300;400;500&family=JetBrains+Mono:wght@400;500&display=swap',
        array(),
        null
    );
    wp_enqueue_style(
        'ensurance-home',
        get_stylesheet_directory_uri() . '/assets/home.css',
        array(),
        filemtime(get_stylesheet_directory() . '/assets/home.css')
    );
    wp_enqueue_script(
        'ensurance-home',
        get_stylesheet_directory_uri() . '/assets/home.js',
        array(),
        filemtime(get_stylesheet_directory() . '/assets/home.js'),
        true
    );

    // Page-specific layer — loaded AFTER home.css/home.js via dependency.
    wp_enqueue_style(
        'ensurance-auto-insurance',
        get_stylesheet_directory_uri() . '/assets/auto-insurance-quote-request.css',
        array('ensurance-home'),
        filemtime(get_stylesheet_directory() . '/assets/auto-insurance-quote-request.css')
    );
    wp_enqueue_script(
        'ensurance-auto-insurance',
        get_stylesheet_directory_uri() . '/assets/auto-insurance-quote-request.js',
        array('ensurance-home'),
        filemtime(get_stylesheet_directory() . '/assets/auto-insurance-quote-request.js'),
        true
    );
}
add_action('wp_enqueue_scripts', 'ensurance_auto_insurance_quote_request_assets', 20);

// ============================================================================
// 2b-vii. HEALTH INSURANCE (CALM INTELLIGENCE REDESIGN) — SELF-CONTAINED ASSETS
// ============================================================================
// /health-insurance-quote-request ships the same standalone design system as the
// homepage and the Auto page. It reuses assets/home.css + assets/home.js for
// tokens, chrome and base components, and layers
// assets/health-insurance-quote-request.css + .js on top for the page-specific
// sections (light hero with a trust-cue row, the "by the numbers" health-
// enrollment stat band, the four request-area cards, the four-step process, the
// dark licensed-review panel) and the scroll-reveal motion. As with the homepage
// and Auto page, we DEQUEUE the shared marketing bundle (if enqueued at priority
// 10) so its generic selectors cannot fight this design. Runs at priority 20 so
// the dequeue lands after the priority-10 enqueues. The slug check (is_page) is a
// belt-and-braces fallback so the assets load even if the template is matched by
// the page-{slug} hierarchy rather than an explicit Template assignment. New
// function — existing functions untouched.

function ensurance_health_insurance_quote_request_assets() {
    if ( ! is_page_template('page-health-insurance-quote-request.php')
        && ! is_page('health-insurance-quote-request') ) {
        return;
    }

    // Drop the shared marketing bundle so it cannot fight this design.
    wp_dequeue_style('ensurance-marketing');
    wp_dequeue_script('ensurance-marketing');
    wp_dequeue_style('ensurance-marketing-fonts');

    // Shared Calm Intelligence type system + base (same as the homepage).
    wp_enqueue_style(
        'ensurance-home-fonts',
        'https://fonts.googleapis.com/css2?family=Albert+Sans:wght@700;800;900&family=Rubik:wght@300;400;500&family=JetBrains+Mono:wght@400;500&display=swap',
        array(),
        null
    );
    wp_enqueue_style(
        'ensurance-home',
        get_stylesheet_directory_uri() . '/assets/home.css',
        array(),
        filemtime(get_stylesheet_directory() . '/assets/home.css')
    );
    wp_enqueue_script(
        'ensurance-home',
        get_stylesheet_directory_uri() . '/assets/home.js',
        array(),
        filemtime(get_stylesheet_directory() . '/assets/home.js'),
        true
    );

    // Page-specific layer — loaded AFTER home.css/home.js via dependency.
    wp_enqueue_style(
        'ensurance-health-insurance',
        get_stylesheet_directory_uri() . '/assets/health-insurance-quote-request.css',
        array('ensurance-home'),
        filemtime(get_stylesheet_directory() . '/assets/health-insurance-quote-request.css')
    );
    wp_enqueue_script(
        'ensurance-health-insurance',
        get_stylesheet_directory_uri() . '/assets/health-insurance-quote-request.js',
        array('ensurance-home'),
        filemtime(get_stylesheet_directory() . '/assets/health-insurance-quote-request.js'),
        true
    );
}
add_action('wp_enqueue_scripts', 'ensurance_health_insurance_quote_request_assets', 20);

// ============================================================================
// 2b-viii. AUTO INSURANCE QUOTE (START YOUR REQUEST REDESIGN) — SELF-CONTAINED
// ============================================================================
// /auto-insurance-quote is the request/intake page the whole site funnels into
// (homepage hero, header nav CTA, mobile sticky CTA, coverage cards and the
// /auto-insurance-quote-request landing all point here). It ships the same
// standalone design system as the homepage and the other quote pages: it reuses
// assets/home.css + assets/home.js for tokens, chrome and base components, and
// layers assets/auto-insurance-quote.css + .js on top for the page-specific
// sections (centered intro with the framed guided-request form slot, trust-cue
// row, the "you're in control" callout, the three-step "what happens next" row
// and the closing trust band) and the scroll-reveal motion. As with the other
// design pages, we DEQUEUE the shared marketing bundle (if enqueued at priority
// 10) so its generic selectors cannot fight this design. Runs at priority 20 so
// the dequeue lands after the priority-10 enqueues. The slug check (is_page) is a
// belt-and-braces fallback so the assets load even if the template is matched by
// the page-{slug} hierarchy rather than an explicit Template assignment. New
// function — existing functions untouched.

function ensurance_auto_insurance_quote_assets() {
    if ( ! is_page_template('page-auto-insurance-quote.php')
        && ! is_page('auto-insurance-quote') ) {
        return;
    }

    // Drop the shared marketing bundle so it cannot fight this design.
    wp_dequeue_style('ensurance-marketing');
    wp_dequeue_script('ensurance-marketing');
    wp_dequeue_style('ensurance-marketing-fonts');

    // Shared Calm Intelligence type system + base (same as the homepage).
    wp_enqueue_style(
        'ensurance-home-fonts',
        'https://fonts.googleapis.com/css2?family=Albert+Sans:wght@700;800;900&family=Rubik:wght@300;400;500&family=JetBrains+Mono:wght@400;500&display=swap',
        array(),
        null
    );
    wp_enqueue_style(
        'ensurance-home',
        get_stylesheet_directory_uri() . '/assets/home.css',
        array(),
        filemtime(get_stylesheet_directory() . '/assets/home.css')
    );
    wp_enqueue_script(
        'ensurance-home',
        get_stylesheet_directory_uri() . '/assets/home.js',
        array(),
        filemtime(get_stylesheet_directory() . '/assets/home.js'),
        true
    );

    // Page-specific layer — loaded AFTER home.css/home.js via dependency.
    wp_enqueue_style(
        'ensurance-auto-insurance-quote',
        get_stylesheet_directory_uri() . '/assets/auto-insurance-quote.css',
        array('ensurance-home'),
        filemtime(get_stylesheet_directory() . '/assets/auto-insurance-quote.css')
    );
    wp_enqueue_script(
        'ensurance-auto-insurance-quote',
        get_stylesheet_directory_uri() . '/assets/auto-insurance-quote.js',
        array('ensurance-home'),
        filemtime(get_stylesheet_directory() . '/assets/auto-insurance-quote.js'),
        true
    );
}
add_action('wp_enqueue_scripts', 'ensurance_auto_insurance_quote_assets', 20);

// ----------------------------------------------------------------------------
// /homeowners-insurance-quote — page-homeowners-insurance-quote.php assets.
// ----------------------------------------------------------------------------
// The homeowners intake page is an exact visual reuse of the auto "Start Your
// Request" design, so it loads the SAME asset stack as the auto page (home
// base layer + auto-insurance-quote.css/.js page layer) rather than a copy —
// the two pages stay identical by construction. Only the form in the slot
// differs (Ninja Forms, embedded via the page's editor content). Same dequeue
// of the shared marketing bundle, same priority-20 timing, same slug fallback.
// New function — existing functions untouched.

function ensurance_homeowners_insurance_quote_assets() {
    if ( ! is_page_template('page-homeowners-insurance-quote.php')
        && ! is_page('homeowners-insurance-quote') ) {
        return;
    }

    // Drop the shared marketing bundle so it cannot fight this design.
    wp_dequeue_style('ensurance-marketing');
    wp_dequeue_script('ensurance-marketing');
    wp_dequeue_style('ensurance-marketing-fonts');

    // Shared Calm Intelligence type system + base (same as the homepage).
    wp_enqueue_style(
        'ensurance-home-fonts',
        'https://fonts.googleapis.com/css2?family=Albert+Sans:wght@700;800;900&family=Rubik:wght@300;400;500&family=JetBrains+Mono:wght@400;500&display=swap',
        array(),
        null
    );
    wp_enqueue_style(
        'ensurance-home',
        get_stylesheet_directory_uri() . '/assets/home.css',
        array(),
        filemtime(get_stylesheet_directory() . '/assets/home.css')
    );
    wp_enqueue_script(
        'ensurance-home',
        get_stylesheet_directory_uri() . '/assets/home.js',
        array(),
        filemtime(get_stylesheet_directory() . '/assets/home.js'),
        true
    );

    // Shared "Start Your Request" page layer (same files as the auto page).
    wp_enqueue_style(
        'ensurance-auto-insurance-quote',
        get_stylesheet_directory_uri() . '/assets/auto-insurance-quote.css',
        array('ensurance-home'),
        filemtime(get_stylesheet_directory() . '/assets/auto-insurance-quote.css')
    );
    wp_enqueue_script(
        'ensurance-auto-insurance-quote',
        get_stylesheet_directory_uri() . '/assets/auto-insurance-quote.js',
        array('ensurance-home'),
        filemtime(get_stylesheet_directory() . '/assets/auto-insurance-quote.js'),
        true
    );
}
add_action('wp_enqueue_scripts', 'ensurance_homeowners_insurance_quote_assets', 20);

// ----------------------------------------------------------------------------
// /renters-insurance-quote — page-renters-insurance-quote.php assets.
// ----------------------------------------------------------------------------
// Same deal as the homeowners page above: an exact visual reuse of the auto
// "Start Your Request" design, loading the SAME asset stack (home base layer +
// auto-insurance-quote.css/.js page layer). Only the form in the slot differs
// (Ninja Forms "Renters Insurance Quote Request", embedded via the page's
// editor content). New function — existing functions untouched.

function ensurance_renters_insurance_quote_assets() {
    if ( ! is_page_template('page-renters-insurance-quote.php')
        && ! is_page('renters-insurance-quote') ) {
        return;
    }

    // Drop the shared marketing bundle so it cannot fight this design.
    wp_dequeue_style('ensurance-marketing');
    wp_dequeue_script('ensurance-marketing');
    wp_dequeue_style('ensurance-marketing-fonts');

    // Shared Calm Intelligence type system + base (same as the homepage).
    wp_enqueue_style(
        'ensurance-home-fonts',
        'https://fonts.googleapis.com/css2?family=Albert+Sans:wght@700;800;900&family=Rubik:wght@300;400;500&family=JetBrains+Mono:wght@400;500&display=swap',
        array(),
        null
    );
    wp_enqueue_style(
        'ensurance-home',
        get_stylesheet_directory_uri() . '/assets/home.css',
        array(),
        filemtime(get_stylesheet_directory() . '/assets/home.css')
    );
    wp_enqueue_script(
        'ensurance-home',
        get_stylesheet_directory_uri() . '/assets/home.js',
        array(),
        filemtime(get_stylesheet_directory() . '/assets/home.js'),
        true
    );

    // Shared "Start Your Request" page layer (same files as the auto page).
    wp_enqueue_style(
        'ensurance-auto-insurance-quote',
        get_stylesheet_directory_uri() . '/assets/auto-insurance-quote.css',
        array('ensurance-home'),
        filemtime(get_stylesheet_directory() . '/assets/auto-insurance-quote.css')
    );
    wp_enqueue_script(
        'ensurance-auto-insurance-quote',
        get_stylesheet_directory_uri() . '/assets/auto-insurance-quote.js',
        array('ensurance-home'),
        filemtime(get_stylesheet_directory() . '/assets/auto-insurance-quote.js'),
        true
    );
}
add_action('wp_enqueue_scripts', 'ensurance_renters_insurance_quote_assets', 20);

// ----------------------------------------------------------------------------
// /life-insurance-quote — page-life-insurance-quote.php assets.
// ----------------------------------------------------------------------------
// Same deal as the homeowners/renters pages above: an exact visual reuse of
// the auto "Start Your Request" design, loading the SAME asset stack (home
// base layer + auto-insurance-quote.css/.js page layer). Only the form in the
// slot differs (Ninja Forms "Life Insurance Quote Request", embedded via the
// page's editor content). New function — existing functions untouched.

function ensurance_life_insurance_quote_assets() {
    if ( ! is_page_template('page-life-insurance-quote.php')
        && ! is_page('life-insurance-quote') ) {
        return;
    }

    // Drop the shared marketing bundle so it cannot fight this design.
    wp_dequeue_style('ensurance-marketing');
    wp_dequeue_script('ensurance-marketing');
    wp_dequeue_style('ensurance-marketing-fonts');

    // Shared Calm Intelligence type system + base (same as the homepage).
    wp_enqueue_style(
        'ensurance-home-fonts',
        'https://fonts.googleapis.com/css2?family=Albert+Sans:wght@700;800;900&family=Rubik:wght@300;400;500&family=JetBrains+Mono:wght@400;500&display=swap',
        array(),
        null
    );
    wp_enqueue_style(
        'ensurance-home',
        get_stylesheet_directory_uri() . '/assets/home.css',
        array(),
        filemtime(get_stylesheet_directory() . '/assets/home.css')
    );
    wp_enqueue_script(
        'ensurance-home',
        get_stylesheet_directory_uri() . '/assets/home.js',
        array(),
        filemtime(get_stylesheet_directory() . '/assets/home.js'),
        true
    );

    // Shared "Start Your Request" page layer (same files as the auto page).
    wp_enqueue_style(
        'ensurance-auto-insurance-quote',
        get_stylesheet_directory_uri() . '/assets/auto-insurance-quote.css',
        array('ensurance-home'),
        filemtime(get_stylesheet_directory() . '/assets/auto-insurance-quote.css')
    );
    wp_enqueue_script(
        'ensurance-auto-insurance-quote',
        get_stylesheet_directory_uri() . '/assets/auto-insurance-quote.js',
        array('ensurance-home'),
        filemtime(get_stylesheet_directory() . '/assets/auto-insurance-quote.js'),
        true
    );
}
add_action('wp_enqueue_scripts', 'ensurance_life_insurance_quote_assets', 20);

// ----------------------------------------------------------------------------
// /commercial-insurance-quote — page-commercial-insurance-quote.php assets.
// ----------------------------------------------------------------------------
// Same deal as the homeowners/renters/life pages above: an exact visual reuse
// of the auto "Start Your Request" design, loading the SAME asset stack (home
// base layer + auto-insurance-quote.css/.js page layer). Only the form in the
// slot differs (Ninja Forms "Commercial Insurance Quote Request", embedded via
// the page's editor content). New function — existing functions untouched.

function ensurance_commercial_insurance_quote_assets() {
    if ( ! is_page_template('page-commercial-insurance-quote.php')
        && ! is_page('commercial-insurance-quote') ) {
        return;
    }

    // Drop the shared marketing bundle so it cannot fight this design.
    wp_dequeue_style('ensurance-marketing');
    wp_dequeue_script('ensurance-marketing');
    wp_dequeue_style('ensurance-marketing-fonts');

    // Shared Calm Intelligence type system + base (same as the homepage).
    wp_enqueue_style(
        'ensurance-home-fonts',
        'https://fonts.googleapis.com/css2?family=Albert+Sans:wght@700;800;900&family=Rubik:wght@300;400;500&family=JetBrains+Mono:wght@400;500&display=swap',
        array(),
        null
    );
    wp_enqueue_style(
        'ensurance-home',
        get_stylesheet_directory_uri() . '/assets/home.css',
        array(),
        filemtime(get_stylesheet_directory() . '/assets/home.css')
    );
    wp_enqueue_script(
        'ensurance-home',
        get_stylesheet_directory_uri() . '/assets/home.js',
        array(),
        filemtime(get_stylesheet_directory() . '/assets/home.js'),
        true
    );

    // Shared "Start Your Request" page layer (same files as the auto page).
    wp_enqueue_style(
        'ensurance-auto-insurance-quote',
        get_stylesheet_directory_uri() . '/assets/auto-insurance-quote.css',
        array('ensurance-home'),
        filemtime(get_stylesheet_directory() . '/assets/auto-insurance-quote.css')
    );
    wp_enqueue_script(
        'ensurance-auto-insurance-quote',
        get_stylesheet_directory_uri() . '/assets/auto-insurance-quote.js',
        array('ensurance-home'),
        filemtime(get_stylesheet_directory() . '/assets/auto-insurance-quote.js'),
        true
    );
}
add_action('wp_enqueue_scripts', 'ensurance_commercial_insurance_quote_assets', 20);

// ----------------------------------------------------------------------------
// /health-insurance-quote — page-health-insurance-quote.php assets.
// ----------------------------------------------------------------------------
// Same deal as the homeowners/renters/life/commercial pages above: an exact
// visual reuse of the auto "Start Your Request" design, loading the SAME asset
// stack (home base layer + auto-insurance-quote.css/.js page layer). Only the
// form in the slot differs (Ninja Forms "Health Insurance Quote Request",
// embedded via the page's editor content). New function — existing functions
// untouched.

function ensurance_health_insurance_quote_assets() {
    if ( ! is_page_template('page-health-insurance-quote.php')
        && ! is_page('health-insurance-quote') ) {
        return;
    }

    // Drop the shared marketing bundle so it cannot fight this design.
    wp_dequeue_style('ensurance-marketing');
    wp_dequeue_script('ensurance-marketing');
    wp_dequeue_style('ensurance-marketing-fonts');

    // Shared Calm Intelligence type system + base (same as the homepage).
    wp_enqueue_style(
        'ensurance-home-fonts',
        'https://fonts.googleapis.com/css2?family=Albert+Sans:wght@700;800;900&family=Rubik:wght@300;400;500&family=JetBrains+Mono:wght@400;500&display=swap',
        array(),
        null
    );
    wp_enqueue_style(
        'ensurance-home',
        get_stylesheet_directory_uri() . '/assets/home.css',
        array(),
        filemtime(get_stylesheet_directory() . '/assets/home.css')
    );
    wp_enqueue_script(
        'ensurance-home',
        get_stylesheet_directory_uri() . '/assets/home.js',
        array(),
        filemtime(get_stylesheet_directory() . '/assets/home.js'),
        true
    );

    // Shared "Start Your Request" page layer (same files as the auto page).
    wp_enqueue_style(
        'ensurance-auto-insurance-quote',
        get_stylesheet_directory_uri() . '/assets/auto-insurance-quote.css',
        array('ensurance-home'),
        filemtime(get_stylesheet_directory() . '/assets/auto-insurance-quote.css')
    );
    wp_enqueue_script(
        'ensurance-auto-insurance-quote',
        get_stylesheet_directory_uri() . '/assets/auto-insurance-quote.js',
        array('ensurance-home'),
        filemtime(get_stylesheet_directory() . '/assets/auto-insurance-quote.js'),
        true
    );
}
add_action('wp_enqueue_scripts', 'ensurance_health_insurance_quote_assets', 20);

/**
 * Contact page (/contact) — Calm Intelligence redesign.
 *
 * Same isolation pattern as the quote-request pages: drop the shared
 * marketing bundle, load the homepage foundation (fonts + home.css/js),
 * then the page-specific layer (assets/contact.css / contact.js) on top.
 * Scoped to this template / slug only, so no other page is affected.
 */
function ensurance_contact_assets() {
    if ( ! is_page_template('page-contact.php')
        && ! is_page('contact') ) {
        return;
    }

    // Drop the shared marketing bundle so it cannot fight this design.
    wp_dequeue_style('ensurance-marketing');
    wp_dequeue_script('ensurance-marketing');
    wp_dequeue_style('ensurance-marketing-fonts');

    // Shared Calm Intelligence type system + base (same as the homepage).
    wp_enqueue_style(
        'ensurance-home-fonts',
        'https://fonts.googleapis.com/css2?family=Albert+Sans:wght@700;800;900&family=Rubik:wght@300;400;500&family=JetBrains+Mono:wght@400;500&display=swap',
        array(),
        null
    );
    wp_enqueue_style(
        'ensurance-home',
        get_stylesheet_directory_uri() . '/assets/home.css',
        array(),
        filemtime(get_stylesheet_directory() . '/assets/home.css')
    );
    wp_enqueue_script(
        'ensurance-home',
        get_stylesheet_directory_uri() . '/assets/home.js',
        array(),
        filemtime(get_stylesheet_directory() . '/assets/home.js'),
        true
    );

    // Page-specific layer — loaded AFTER home.css/home.js via dependency.
    wp_enqueue_style(
        'ensurance-contact',
        get_stylesheet_directory_uri() . '/assets/contact.css',
        array('ensurance-home'),
        filemtime(get_stylesheet_directory() . '/assets/contact.css')
    );
    wp_enqueue_script(
        'ensurance-contact',
        get_stylesheet_directory_uri() . '/assets/contact.js',
        array('ensurance-home'),
        filemtime(get_stylesheet_directory() . '/assets/contact.js'),
        true
    );
}
add_action('wp_enqueue_scripts', 'ensurance_contact_assets', 20);

/**
 * Privacy Policy page (/privacy-policy) — Calm Intelligence legal document.
 *
 * Same isolation pattern as the other Calm Intelligence pages: drop the
 * shared marketing bundle, load the homepage foundation (fonts + home.css/js),
 * then the page-specific layer (assets/privacy-policy.css / privacy-policy.js)
 * on top. Scoped to this template / slug only, so no other page is affected.
 */
function ensurance_privacy_policy_assets() {
    if ( ! is_page_template('page-privacy-policy.php')
        && ! is_page('privacy-policy') ) {
        return;
    }

    // Drop the shared marketing bundle so it cannot fight this design.
    wp_dequeue_style('ensurance-marketing');
    wp_dequeue_script('ensurance-marketing');
    wp_dequeue_style('ensurance-marketing-fonts');

    // Shared Calm Intelligence type system + base (same as the homepage).
    wp_enqueue_style(
        'ensurance-home-fonts',
        'https://fonts.googleapis.com/css2?family=Albert+Sans:wght@700;800;900&family=Rubik:wght@300;400;500&family=JetBrains+Mono:wght@400;500&display=swap',
        array(),
        null
    );
    wp_enqueue_style(
        'ensurance-home',
        get_stylesheet_directory_uri() . '/assets/home.css',
        array(),
        filemtime(get_stylesheet_directory() . '/assets/home.css')
    );
    wp_enqueue_script(
        'ensurance-home',
        get_stylesheet_directory_uri() . '/assets/home.js',
        array(),
        filemtime(get_stylesheet_directory() . '/assets/home.js'),
        true
    );

    // Page-specific layer — loaded AFTER home.css/home.js via dependency.
    wp_enqueue_style(
        'ensurance-privacy-policy',
        get_stylesheet_directory_uri() . '/assets/privacy-policy.css',
        array('ensurance-home'),
        filemtime(get_stylesheet_directory() . '/assets/privacy-policy.css')
    );
    wp_enqueue_script(
        'ensurance-privacy-policy',
        get_stylesheet_directory_uri() . '/assets/privacy-policy.js',
        array('ensurance-home'),
        filemtime(get_stylesheet_directory() . '/assets/privacy-policy.js'),
        true
    );
}
add_action('wp_enqueue_scripts', 'ensurance_privacy_policy_assets', 20);

/**
 * Contact form backend — REST endpoint + wp_mail + stored copy.
 *
 * The /contact form (page-contact.php) submits via fetch() to
 * POST /wp-json/ensurance/v1/contact. The handler:
 *   1. silently drops honeypot hits and sub-2s submissions (bots),
 *   2. rate-limits to 5 messages per IP per 10 minutes,
 *   3. validates name / email / message and returns per-field errors
 *      (assets/contact.js shows them with the design's copy),
 *   4. stores every message as a private "Contact Message" post so it is
 *      visible in wp-admin even if an email is ever missed,
 *   5. emails support@ensurance.com (override via the
 *      `ensurance_contact_recipient` filter) with Reply-To set to the
 *      sender. Delivery rides the configured WP Mail SMTP mailer.
 *
 * No nonce on purpose: the page is served through SG Optimizer full-page
 * caching, so a rendered nonce would go stale and silently break the form
 * for logged-out visitors. The endpoint is unauthenticated and does nothing
 * privileged; honeypot + time-trap + rate limit handle abuse.
 */
function ensurance_contact_register_cpt() {
    register_post_type('ensurance_message', array(
        'labels' => array(
            'name'          => 'Contact Messages',
            'singular_name' => 'Contact Message',
            'menu_name'     => 'Contact Messages',
            'edit_item'     => 'Contact Message',
            'search_items'  => 'Search Messages',
            'not_found'     => 'No messages yet.',
        ),
        'public'       => false,
        'show_ui'      => true,
        'show_in_menu' => true,
        'menu_position' => 26,
        'menu_icon'    => 'dashicons-email-alt',
        'supports'     => array('title', 'editor'),
        // Messages arrive via the form only — no "Add New" in wp-admin.
        'capabilities' => array('create_posts' => 'do_not_allow'),
        'map_meta_cap' => true,
        'show_in_rest' => false,
    ));
}
add_action('init', 'ensurance_contact_register_cpt');

function ensurance_contact_topic_labels() {
    return array(
        ''        => 'A general question',
        'request' => 'About a request I started',
        'agent'   => "I'm an agent or agency",
        'press'   => 'Press or media',
        'privacy' => 'A privacy request',
    );
}

function ensurance_contact_register_rest() {
    register_rest_route('ensurance/v1', '/contact', array(
        'methods'             => 'POST',
        'callback'            => 'ensurance_contact_handle',
        'permission_callback' => '__return_true',
    ));
}
add_action('rest_api_init', 'ensurance_contact_register_rest');

function ensurance_contact_handle( WP_REST_Request $request ) {
    // Honeypot: bots fill every field. Pretend success so they move on.
    if ( '' !== trim( (string) $request->get_param('ct_company') ) ) {
        return array( 'ok' => true );
    }

    // Time trap: contact.js reports ms elapsed since page load. Humans need
    // longer than 2s to fill four fields; scripted posts (or posts made
    // without running our JS at all) do not pass.
    $elapsed = absint( $request->get_param('ct_elapsed') );
    if ( $elapsed < 2000 ) {
        return new WP_Error(
            'ensurance_contact_too_fast',
            'That went through a little too quickly — please try sending again.',
            array( 'status' => 400 )
        );
    }

    // Rate limit: 5 messages per IP per 10 minutes.
    $ip    = isset( $_SERVER['REMOTE_ADDR'] ) ? (string) $_SERVER['REMOTE_ADDR'] : '';
    $rlkey = 'ens_contact_rl_' . md5( $ip );
    $count = (int) get_transient( $rlkey );
    if ( $count >= 5 ) {
        return new WP_Error(
            'ensurance_contact_rate_limited',
            "You've sent a few messages in a row — give us a little while to catch up, then try again.",
            array( 'status' => 429 )
        );
    }

    // Sanitize + validate. Field errors mirror the design's copy and are
    // rendered inline by contact.js.
    $name    = sanitize_text_field( (string) $request->get_param('ct_name') );
    $email   = sanitize_email( (string) $request->get_param('ct_email') );
    $topic   = sanitize_key( (string) $request->get_param('ct_topic') );
    $message = sanitize_textarea_field( (string) $request->get_param('ct_message') );

    $fields = array();
    if ( '' === $name ) {
        $fields['ct_name'] = "Mind adding your name so we know who we're replying to?";
    }
    if ( '' === $email || ! is_email( $email ) ) {
        $fields['ct_email'] = "That email doesn't look quite right — mind checking it?";
    }
    if ( strlen( $message ) <= 4 ) {
        $fields['ct_message'] = 'Add a little more so we can actually help.';
    }
    if ( $fields ) {
        return new WP_Error(
            'ensurance_contact_invalid',
            'A couple of fields need another look.',
            array( 'status' => 400, 'fields' => $fields )
        );
    }

    $labels      = ensurance_contact_topic_labels();
    $topic_label = isset( $labels[ $topic ] ) ? $labels[ $topic ] : $labels[''];

    set_transient( $rlkey, $count + 1, 10 * MINUTE_IN_SECONDS );

    // 1) Stored copy — private Contact Message post, visible in wp-admin.
    $post_id = wp_insert_post( array(
        'post_type'    => 'ensurance_message',
        'post_status'  => 'private',
        'post_title'   => $name . ' — ' . $topic_label,
        'post_content' => "From: {$name} <{$email}>\nTopic: {$topic_label}\n\n{$message}",
        'meta_input'   => array(
            '_ct_email' => $email,
            '_ct_topic' => $topic_label,
        ),
    ), false );

    // 2) Email — rides WP Mail SMTP; Reply-To goes straight to the sender.
    $recipient = apply_filters( 'ensurance_contact_recipient', 'support@ensurance.com' );
    $subject   = 'Contact form: ' . $topic_label . ' — ' . $name;
    $body      = "New message from the ensurance.com contact form.\n\n"
        . "Name:  {$name}\n"
        . "Email: {$email}\n"
        . "Topic: {$topic_label}\n\n"
        . "Message:\n{$message}\n\n"
        . '— Sent ' . wp_date( 'F j, Y g:i a T' ) . ". Reply to this email to respond directly.\n";
    $headers   = array( 'Reply-To: ' . $name . ' <' . $email . '>' );
    $mailed    = wp_mail( $recipient, $subject, $body, $headers );

    if ( ! $mailed && $post_id ) {
        update_post_meta( $post_id, '_ct_mail_failed', '1' );
    }

    // The message got through if we stored it OR delivered it.
    if ( ! $mailed && ! $post_id ) {
        return new WP_Error(
            'ensurance_contact_failed',
            "Something went wrong on our side and your message didn't send. Please try again in a moment, or email support@ensurance.com directly.",
            array( 'status' => 500 )
        );
    }

    return array( 'ok' => true );
}

// ============================================================================
// 2c. GOOGLE TAG MANAGER (GTM-5GRHH8LL) — SITE-WIDE
// ============================================================================
// Ported from the package's includes/tracking-head.php (head script) and
// includes/tracking-body.php (noscript iframe). GTM is a site-wide container,
// so it loads on every page via wp_head + wp_body_open. No GA4 base tag is
// added here (GA4 is configured inside the GTM container).
//
// IMPORTANT: verify GTM is not already injected by a plugin (e.g. GTM4WP,
// Site Kit) before relying on this. If it is, set ENSURANCE_LOAD_GTM to false
// in wp-config.php or here to avoid a duplicate container firing.

if ( ! defined('ENSURANCE_LOAD_GTM') ) {
    define('ENSURANCE_LOAD_GTM', true);
}

function ensurance_gtm_head() {
    if ( ! ENSURANCE_LOAD_GTM ) {
        return;
    }
    ?>
<!-- Google Tag Manager -->
<script>window.dataLayer = window.dataLayer || [];</script>
<script>(function(w,d,s,l,i){w[l]=w[l]||[];w[l].push({'gtm.start':
new Date().getTime(),event:'gtm.js'});var f=d.getElementsByTagName(s)[0],
j=d.createElement(s),dl=l!='dataLayer'?'&l='+l:'';j.async=true;j.src=
'https://www.googletagmanager.com/gtm.js?id='+i+dl;f.parentNode.insertBefore(j,f);
})(window,document,'script','dataLayer','GTM-5GRHH8LL');</script>
<!-- End Google Tag Manager -->
    <?php
}
add_action('wp_head', 'ensurance_gtm_head', 1);

function ensurance_gtm_body() {
    if ( ! ENSURANCE_LOAD_GTM ) {
        return;
    }
    ?>
<!-- Google Tag Manager (noscript) -->
<noscript><iframe src="https://www.googletagmanager.com/ns.html?id=GTM-5GRHH8LL"
height="0" width="0" style="display:none;visibility:hidden"></iframe></noscript>
<!-- End Google Tag Manager (noscript) -->
    <?php
}
add_action('wp_body_open', 'ensurance_gtm_body', 1);

// ============================================================================
// 3. GEODIRECTORY CUSTOMIZATIONS
// ============================================================================

// GeoDirectory Filter Text Cleanup
add_action('wp_footer', function () { ?>
    <script>
        jQuery(document).ready(function($) {
            $('.geodir-filter-cat .form-check .form-check-label').each(function() {
                var text = $(this).text();
                $(this).text(text.replace(/^– /, ''));
            });

            var $lastStar = $('.comment-form .gd-rating-wrap .gd-rating-foreground .fas.fa-star:last');
            if ($lastStar.length) {
                $lastStar.trigger('mouseenter');
                setTimeout(function() {
                    $lastStar.trigger('click');
                }, 100);
            }
        });
    </script>
<?php });

add_filter('gettext', 'custom_change_claim_listing_text', 20, 3);
function custom_change_claim_listing_text($translation, $text, $domain)
{
    if ($domain == 'geodirectory' && $text == 'Claim Listing') {
        $translation = 'Manage Profile';
    }
    return $translation;
}

add_filter('geodir_admin_email', '_my_new_gd_admin_email');
function _my_new_gd_admin_email($admin_email)
{
    return "Leads@ensurance.com";
}

add_filter('geodir_post_images', 'gd_add_default_image_to_empty_listings', 10, 2);

add_action('wp_footer', function () { ?>
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const link = document.getElementById('open-terms-link');
            if (link) {
                link.addEventListener('click', function(e) {
                    e.preventDefault();
                    window.open(
                        'https://staging3.ensurance.com/agent-participation-terms-code-of-conduct/',
                        '_blank'
                    );
                });
            }
        });
    </script>

    <!-- Category accordion for mobile -->
    <script>
        document.addEventListener("DOMContentLoaded", function () {
            buildCategoryAccordion();
        });

        function buildCategoryAccordion() {
            document.querySelectorAll('.gd-subcategories').forEach(wrapper => wrapper.remove());
            const movedChildren = new Set();

            document.querySelectorAll('.geodir-advs-p-0').forEach(function (parentDiv) {
                const parentInput = parentDiv.querySelector('input[type="checkbox"]');
                const parentLabel = parentDiv.querySelector('label');
                const parentVal = parentInput?.value;

                if (parentVal && parentLabel) {
                    parentLabel.classList.add('gd-parent-label');
                    const children = Array.from(document.querySelectorAll('.geodir-advs-p-' + parentVal))
                        .filter(child => !movedChildren.has(child));

                    if (children.length > 0) {
                        const wrapper = document.createElement('div');
                        wrapper.classList.add('gd-subcategories');
                        children.forEach(child => { wrapper.appendChild(child); movedChildren.add(child); });
                        parentDiv.after(wrapper);
                        parentLabel.addEventListener('click', function (e) {
                            e.preventDefault();
                            this.classList.toggle('active');
                            wrapper.classList.toggle('show');
                        });
                    }
                }
            });

            const seen = new Set();
            document.querySelectorAll('[class^="geodir-advs-p-"]').forEach(child => {
                const key = child.className + '|' + child.textContent.trim();
                if (seen.has(key)) { child.style.display = 'none'; } else { seen.add(key); }
            });
        }
    </script>
<?php });

add_action('wp_footer', 'change_subject_to_insurance_type');
function change_subject_to_insurance_type()
{ ?>
    <script type="text/javascript">
        document.addEventListener('DOMContentLoaded', function() {
            document.querySelectorAll('label.form-label').forEach(function(label) {
                if (label.textContent.trim() === 'Subject') {
                    label.textContent = 'Insurance Type';
                }
            });
            let input = document.querySelector('input[name="field_subject"]');
            if (input && input.placeholder === 'Subject') {
                input.placeholder = 'Insurance Type';
            }
        });
    </script>
<?php }

// add_filter( 'geodir_ppl_block_output_contact', 'add_insurance_type_field_to_contact_form', 10, 2 );
function add_insurance_type_field_to_contact_form($html, $args)
{
    $custom_field_html = '
    <div class="mb-3">
        <label class="form-label">Type of Insurance Needed <small>(select all that apply)</small></label><br/>
        <div class="form-check"><input class="form-check-input" type="checkbox" name="insurance_type[]" value="Auto" id="insurance_auto"><label class="form-check-label" for="insurance_auto">Auto</label></div>
        <div class="form-check"><input class="form-check-input" type="checkbox" name="insurance_type[]" value="Homeowners" id="insurance_home"><label class="form-check-label" for="insurance_home">Homeowners</label></div>
        <div class="form-check"><input class="form-check-input" type="checkbox" name="insurance_type[]" value="Renters" id="insurance_renters"><label class="form-check-label" for="insurance_renters">Renters</label></div>
        <div class="form-check"><input class="form-check-input" type="checkbox" name="insurance_type[]" value="Life" id="insurance_life"><label class="form-check-label" for="insurance_life">Life</label></div>
        <div class="form-check"><input class="form-check-input" type="checkbox" name="insurance_type[]" value="Health" id="insurance_health"><label class="form-check-label" for="insurance_health">Health</label></div>
        <div class="form-check"><input class="form-check-input" type="checkbox" name="insurance_type[]" value="Business" id="insurance_business"><label class="form-check-label" for="insurance_business">Business</label></div>
        <div class="form-check mb-2"><input class="form-check-input" type="checkbox" id="insurance_other_checkbox"><label class="form-check-label" for="insurance_other_checkbox">Other</label></div>
        <input type="text" class="form-control" name="insurance_type_other" id="insurance_type_other" placeholder="Please specify" style="display:none;" />
    </div>
    <script>
        document.addEventListener("DOMContentLoaded", function () {
            const otherCheckbox = document.getElementById("insurance_other_checkbox");
            const otherInput = document.getElementById("insurance_type_other");
            if (otherCheckbox && otherInput) {
                otherCheckbox.addEventListener("change", function () {
                    otherInput.style.display = this.checked ? "block" : "none";
                    if (this.checked) { otherInput.setAttribute("name", "insurance_type[]"); otherInput.value = ""; }
                    else { otherInput.removeAttribute("name"); otherInput.value = ""; }
                });
            }
        });
    </script>';
    $html = preg_replace('/(<form[^>]*>)/i', '$1' . $custom_field_html, $html);
    return $html;
}

// add_filter( 'geodir_email_content', 'add_insurance_type_to_email_content', 20, 3 );
function add_insurance_type_to_email_content($content, $email, $args)
{
    if (isset($_POST['insurance_type']) && is_array($_POST['insurance_type'])) {
        $selected_types = array_map('sanitize_text_field', $_POST['insurance_type']);
        $content = "<p><strong>Insurance Type:</strong> " . implode(', ', $selected_types) . "</p>" . $content;
    }
    return $content;
}

function exclude_press_release_category($query)
{
    if ($query->is_home() && $query->is_main_query()) {
        $query->set('cat', '-141');
    }
}
add_action('pre_get_posts', 'exclude_press_release_category');

add_action( 'geodir_ppl_pre_owner_new_lead_email', 'handle_owner_new_lead_email', 15, 2 );
function handle_owner_new_lead_email( $lead, $email_vars ) {
    $email_name = 'owner_new_lead';
    if ( ! GeoDir_Email::is_email_enabled( $email_name ) ) return false;

    $listing = geodir_get_post_info( $lead->listing_id );
    $author_data = get_userdata( $listing->post_author );
    if ( empty( $author_data ) ) return false;

    $recipient = $listing->email;
    if ( ! is_email( $recipient ) ) return false;

    $email_vars['to_name']  = 'Agency';
    $email_vars['to_email'] = $recipient;

    $subject     = GeoDir_Email::get_subject( $email_name, $email_vars );
    $headers     = GeoDir_Email::get_headers( $email_name, $email_vars );
    $attachments = GeoDir_Email::get_attachments( $email_name, $email_vars );
    $plain_text  = GeoDir_Email::get_email_type() !== 'html';
    $template    = $plain_text ? 'plain-text-email.php' : 'html-email.php';

    $content = geodir_get_template_html(
        $template,
        array(
            'email_name'    => $email_name,
            'email_vars'    => $email_vars,
            'email_heading' => '',
            'sent_to_admin' => false,
            'message_body'  => GeoDir_Email::get_content( $email_name, $email_vars ),
        ),
        'geodir-pay-per-lead',
        GEODIR_PPL_PLUGIN_DIR . 'templates/'
    );

    GeoDir_Email::send( $recipient, $subject, $content, $headers, $attachments );
}

// ============================================================================
// 4. USERWP CUSTOMIZATIONS
// ============================================================================

add_filter('uwp_account_available_tabs', 'uwp_account_available_tabs_cb');
function uwp_account_available_tabs_cb($tabs)
{
    unset($tabs['notifications']);
    unset($tabs['privacy']);
    return $tabs;
}

add_filter('uwp_account_available_tabs', 'add_favorites_tab_to_uwp');
function add_favorites_tab_to_uwp($tabs)
{
    $tabs['favorites'] = array(
        'title' => __('Favorites', 'userswp'),
        'icon'  => 'fas fa-heart'
    );
    return $tabs;
}

add_action('uwp_account_form_display', 'custom_display_form', 20, 1);
function custom_display_form($type)
{
    if ($type == 'favorites') {
        if (uwp_get_option("design_style", 'bootstrap')) {
            custom_get_bootstrap_favorites();
        }
    }
}

function custom_get_bootstrap_favorites()
{
    $user = wp_get_current_user();
    $post_type = 'gd_place';
    $favorite_ids = geodir_get_user_favourites($user->ID);
    if ($favorite_ids) {
        $paged = (get_query_var('paged')) ? absint(get_query_var('paged')) : 1;
        if (!empty($user) && !empty($user->ID) && (int) get_current_user_id() == (int) $user->ID) {
            $post_status = geodir_get_post_stati('author-archive', array('post_type' => $post_type));
        } else {
            $post_status = geodir_get_post_stati('public', array('post_type' => $post_type));
        }
        $args = array(
            'post_type'        => $post_type,
            'post_status'      => $post_status,
            'posts_per_page'   => uwp_get_option('profile_no_of_items', 10),
            'paged'            => $paged,
            'post__in'         => $favorite_ids,
            'uwp_geodir_query' => true
        );
        $args = apply_filters('uwp_listing_query_args', $args, $user, $post_type);
        $the_query = new WP_Query($args);
        $args['template_args']['the_query'] = $the_query;
        $args['template_args']['title']     = geodir_post_type_name($post_type, true);
        uwp_get_template("bootstrap/loop-posts.php", $args);
    }
}

// ============================================================================
// 5. EMAIL, LEAD & TAWK.TO
// ============================================================================

function add_tawkto_script_to_footer()
{ ?>
    <!--Start of Tawk.to Script-->
    <script type="text/javascript">
        var Tawk_API = Tawk_API || {}, Tawk_LoadStart = new Date();
        (function() {
            var s1 = document.createElement("script"), s0 = document.getElementsByTagName("script")[0];
            s1.async = true;
            s1.src = 'https://embed.tawk.to/685e35d92d3be4190e5ca7ce/1iuo04mn5';
            s1.charset = 'UTF-8';
            s1.setAttribute('crossorigin', '*');
            s0.parentNode.insertBefore(s1, s0);
        })();
    </script>
    <!--End of Tawk.to Script-->
<?php }
add_action('wp_footer', 'add_tawkto_script_to_footer');

add_action('wp_footer', function() {
    if (is_page('register')) { ?>
        <script>
        document.addEventListener("DOMContentLoaded", function() {
            const url = new URL(window.location.href);
            let fname = url.searchParams.get("fname");
            let lname = url.searchParams.get("lname");
            let email = url.searchParams.get("email");
            if (fname && document.getElementById("first_name")) document.getElementById("first_name").value = fname;
            if (lname && document.getElementById("last_name")) document.getElementById("last_name").value = lname;
            if (email && document.getElementById("email")) document.getElementById("email").value = email;
        });
        </script>
    <?php }
});

add_action('wp_footer', function() { ?>
    <script>
    const input = document.getElementById('get_a_quote_now');
    if (input) { input.classList.remove('btn', 'btn-primary'); }

    jQuery('<div class="form-check mt-3 custom-checkbox-wrapper">' +
        '<input type="checkbox" class="form-check-input custom-checkbox" id="ageConsentCheckbox" required>' +
        '<label class="form-check-label custom-label" for="ageConsentCheckbox">' +
            'I confirm I am at least 18 years old and I accept Ensurance.com\'s ' +
            '<a href="/privacy-policy/" target="_blank" rel="noopener noreferrer">Terms of Service</a>' +
            ' and ' +
            '<a href="/privacy-policy/" target="_blank" rel="noopener noreferrer">Privacy Policy</a>.' +
        '</label></div>').insertAfter(jQuery('textarea[name="field_message"]').closest('div'));

    jQuery(document).ready(function($) {
        $('input[name="field_name"]').attr('placeholder', 'Name (Required)').prev('label').text('Name (Required)');
        $('input[name="field_email"]').attr('placeholder', 'Email (Required)').prev('label').text('Email (Required)');
        $('input[name="field_phone"]').attr({ required: true, placeholder: 'Phone (Required)' }).prev('label').text('Phone (Required)');
    });
    </script>
<?php });

// add_filter( 'geodir_save_post_data', 'gd_send_agent_to_zoho_mvp', 20, 4 );
function gd_send_agent_to_zoho_mvp( $postarr, $gd_post, $post, $update ) {
    if ( empty( $postarr['post_id'] ) || empty( $postarr['post_status'] ) ) return $postarr;
    if ( $postarr['post_status'] !== 'publish' ) return $postarr;

    $post_id  = (int) $postarr['post_id'];
    $last_sync = get_post_meta( $post_id, '_zoho_last_sync', true );
    if ( $last_sync && ( time() - $last_sync ) < 30 ) return $postarr;

    $post_obj = get_post( $post_id );
    if ( ! $post_obj ) return $postarr;

    $package_id          = ! empty( $postarr['package_id'] ) ? (int) $postarr['package_id'] : 0;
    $package_data        = gd_get_package_details( $package_id );
    $previous_package_id = (int) get_post_meta( $post_id, '_previous_package_id', true );
    $previous_package_data = $previous_package_id ? gd_get_package_details( $previous_package_id ) : null;

    $plan_change_type = '';
    if ( $update && $package_id && $previous_package_id && $package_id !== $previous_package_id ) {
        $plan_change_type = gd_determine_plan_change_type( $previous_package_data, $package_data );
    }

    $payload = [
        'agent_listing_id'      => $post_id,
        'display_name'          => $post_obj->post_title,
        'company_name'          => $post_obj->post_title,
        'listing_status'        => $post_obj->post_status,
        'agent_email'           => $postarr['email'] ?? '',
        'agent_phone'           => $postarr['phone'] ?? '',
        'wp_user_id'            => $post_obj->post_author,
        'username'              => wp_get_current_user()->user_login,
        'state_covered'         => $postarr['region'] ?? '',
        'zip_codes_covered'     => $postarr['zip'] ?? '',
        'primary_zip'           => $postarr['zip'] ?? '',
        'coverage_radius'       => get_post_meta( $post_id, 'coverage_radius', true ),
        'created_date'          => $post_obj->post_date,
        'updated_date'          => current_time( 'mysql' ),
        'registered_on'         => $post_obj->post_date,
        'package_id'            => $package_id,
        'package_name'          => $package_data['name'] ?? '',
        'previous_package_id'   => $previous_package_id ?: '',
        'previous_package_name' => $previous_package_data['name'] ?? '',
        'plan_change_type'      => $plan_change_type,
        'source'                => 'GeoDirectory',
    ];

    $response = wp_remote_post(
        'https://flow.zoho.com/898355857/flow/webhook/incoming?zapikey=1001.bfbd47d2e90ab6f35d81b08964d47dbc.8ec18f05363ce9956248744ed1c826bc&isdebug=false',
        [ 'timeout' => 15, 'headers' => [ 'Content-Type' => 'application/json' ], 'body' => wp_json_encode( $payload ) ]
    );

    if ( is_wp_error( $response ) ) {
        error_log( 'Zoho Error: ' . $response->get_error_message() );
        return $postarr;
    }

    $status = wp_remote_retrieve_response_code( $response );
    if ( $status >= 200 && $status < 300 ) {
        update_post_meta( $post_id, '_zoho_last_sync', time() );
        if ( $package_id ) update_post_meta( $post_id, '_previous_package_id', $package_id );
    } else {
        error_log( 'Zoho Sync Failed. Status: ' . $status . ' Response: ' . wp_remote_retrieve_body( $response ) );
    }

    return $postarr;
}

function gd_get_package_details( $package_id ) {
    if ( empty( $package_id ) || ! function_exists( 'geodir_pricing_get_package' ) ) return [];
    $package = geodir_pricing_get_package( $package_id );
    if ( empty( $package ) || empty( $package->id ) ) return [];
    return [
        'id'           => (int) $package->id,
        'name'         => $package->name ?? '',
        'title'        => $package->title ?? '',
        'price'        => (float) ( $package->amount ?? 0 ),
        'status'       => $package->status ?? '',
        'post_type'    => $package->post_type ?? '',
        'is_recurring' => ! empty( $package->recurring ) ? 1 : 0,
        'trial'        => ! empty( $package->trial ) ? 1 : 0,
        'is_default'   => ! empty( $package->is_default ) ? 1 : 0,
    ];
}

function gd_determine_plan_change_type( $old_package, $new_package ) {
    if ( empty( $old_package ) || empty( $new_package ) ) return '';
    $old_price = (float) ( $old_package['price'] ?? 0 );
    $new_price = (float) ( $new_package['price'] ?? 0 );
    if ( $new_price > $old_price ) return 'upgrade';
    if ( $new_price < $old_price ) return 'downgrade';
    if ( $new_price === $old_price && $old_package['id'] !== $new_package['id'] ) return 'lateral';
    return '';
}

// ============================================================================
// 6. ADMIN CUSTOMIZATIONS
// ============================================================================

add_action('kadence_before_blog_loop', 'custom_add_search_to_blog_archive');
function custom_add_search_to_blog_archive()
{
    if (is_home() || is_archive()) {
        echo '<div class="custom-blog-search" style="margin-bottom: 20px;">';
        get_search_form();
        echo '</div>';
    }
}

add_filter('manage_gd_place_posts_columns', 'my_add_latitude_column');
function my_add_latitude_column( $columns ) {
    $new_columns = [];
    foreach ( $columns as $key => $value ) {
        $new_columns[ $key ] = $value;
        if ( $key === 'title' ) $new_columns['listing_latitude'] = __( 'Latitude', 'text-domain' );
    }
    return $new_columns;
}

add_action('manage_gd_place_posts_custom_column', 'my_show_latitude_column_data', 10, 2);
function my_show_latitude_column_data( $column, $post_id ) {
    if ( $column === 'listing_latitude' ) {
        $latitude = geodir_get_post_meta( $post_id, 'longitude' );
        echo $latitude ? esc_html( $latitude ) : '—';
    }
}

add_filter('comment_form_defaults', function( $defaults ) {
    $reply_text = __( 'Leave a Review', 'textdomain' );
    $defaults['title_reply'] = '<span class="gd-comment-review-title h4"
        data-review-text="' . esc_attr( $reply_text ) . '"
        data-reply-text="' . esc_attr( $defaults['title_reply'] ) . '">'
        . $reply_text . '</span>';
    if ( is_user_logged_in() ) {
        $policy_url = get_permalink( get_page_by_path( 'review-policy' ) );
        $defaults['logged_in_as'] .= '<p class="review-policy-link"><a href="' . esc_url( $policy_url ) . '" target="_blank">Sharing Guidelines</a></p>';
    }
    return $defaults;
});

add_filter( 'gettext', function( $translated_text, $untranslated_text, $domain ) {
    if ( trim( $untranslated_text ) === 'Leave a Review' ) return 'Share A Recommendation';
    return $translated_text;
}, 20, 3 );

// ============================================================================
// 7. NINJA FORMS
// ============================================================================

add_filter('ninja_forms_submit_data', function($form_data){
    foreach ($form_data['fields'] as &$field) {
        if (strpos($field['key'], 'lead_id') !== false) {
            $field['value'] = 'lead_' . uniqid('', true);
        }
    }
    return $form_data;
});

// ============================================================================
// 8. LEAD PAGE SHORTCODE
// ============================================================================

function lead_page_shortcode() {
    if (!isset($_GET['id'])) {
        return '<h3 style="color:red;">No lead specified</h3>';
    }

    $lead_id  = sanitize_text_field($_GET['id']);
    $response = wp_remote_get("https://hook.us2.make.com/iysgqdlai1efll0qsd6ybjc3kszmskhb?lead_id=" . $lead_id);

    if (is_wp_error($response)) return '<p>Error loading lead</p>';

    $body = wp_remote_retrieve_body($response);
    $data = json_decode($body, true);
    if (!$data) return '<p>Invalid response</p>';

    ob_start();
    ?>
    <style>
      *, *::before, *::after { box-sizing: border-box; margin: 0; padding: 0; }
      :root {
        --blue: #0073E6; --blue-dark: #0D4095; --blue-light: #e6f0f8;
        --orange: #F5A524; --orange-dark: #d98e18;
        --green: #16a34a; --green-light: #dcfce7;
        --white: #ffffff; --off-white: #f7f8fc;
        --border: #e2e8f0; --text-muted: #64748b; --text-dark: #1e293b;
        --text-light: rgba(255,255,255,0.55);
        --font-head: 'Manrope', sans-serif; --font-body: 'Inter', sans-serif;
      }
      body { background-color: var(--off-white); font-family: var(--font-body); color: var(--text-dark); }
      .wrapper { max-width: 700px; margin: 0 auto; padding: 40px 20px 60px; }
      .header-card { background: linear-gradient(165deg, #0073E6, #0D4095); border-radius: 16px 16px 0 0; padding: 36px 40px; }
      .header-card h1 { font-family: var(--font-head); font-size: 26px; font-weight: 800; color: var(--white); line-height: 1.35; }
      .header-card h1 span { color: var(--orange); }
      .header-card p { font-size: 13px; color: var(--text-light); margin-top: 10px; line-height: 1.6; }
      .main-card { background: var(--white); border-left: 1px solid var(--border); border-right: 1px solid var(--border); padding: 36px 40px; }
      .section-label { font-family: var(--font-head); font-size: 11px; font-weight: 700; color: var(--blue); text-transform: uppercase; letter-spacing: 1.5px; margin-bottom: 14px; display: flex; align-items: center; gap: 8px; }
      .section-label::before { content: ''; width: 8px; height: 8px; background: var(--orange); border-radius: 50%; flex-shrink: 0; }
      .section-label::after { content: ''; flex: 1; height: 1px; background: var(--border); }
      .info-grid { border: 1px solid var(--border); border-radius: 12px; overflow: hidden; margin-bottom: 28px; }
      .info-row { display: grid; grid-template-columns: 1fr 1fr; border-bottom: 1px solid var(--border); }
      .info-row:last-child { border-bottom: none; }
      .info-row.full { grid-template-columns: 1fr; }
      .info-row.thirds { grid-template-columns: 1fr 1fr 1fr; }
      .info-item { padding: 14px 20px; background: var(--white); transition: background 0.15s; }
      .info-item:hover { background: var(--blue-light); }
      .info-row:not(.full) .info-item:not(:last-child) { border-right: 1px solid var(--border); }
      .field-label { font-size: 10px; color: var(--text-muted); text-transform: uppercase; letter-spacing: 1px; font-weight: 600; display: block; margin-bottom: 4px; }
      .field-value { font-family: var(--font-head); font-size: 15px; color: var(--text-dark); font-weight: 700; }
      .field-value.highlight { color: var(--blue); }
      .field-value a { color: var(--blue); text-decoration: none; font-weight: 700; }
      .field-value a:hover { text-decoration: underline; }
      .timestamp { display: flex; align-items: center; justify-content: space-between; padding: 16px 0 0; border-top: 1px solid var(--border); margin-top: 8px; }
      .status-pill { background: var(--green-light); color: var(--green); font-size: 11px; font-weight: 700; padding: 6px 14px; border-radius: 50px; white-space: nowrap; }
      .footer-card { background: linear-gradient(165deg, #005cc4, #0D4095); border-radius: 0 0 16px 16px; padding: 24px 40px; display: flex; align-items: center; justify-content: space-between; }
      .footer-tagline { font-size: 12px; color: var(--text-light); font-style: italic; margin-top: 4px; }
      .legal { text-align: center; font-size: 11px; color: #94a3b8; line-height: 1.9; margin-top: 20px; padding: 0 20px; }
      .legal a { color: #94a3b8; text-decoration: underline; }
      @media (max-width: 580px) {
        .header-card, .footer-card { flex-direction: column; padding: 28px 24px; }
        .main-card { padding: 28px 24px; }
        .info-row, .info-row.thirds { grid-template-columns: 1fr; }
        .info-row:not(.full) .info-item:not(:last-child) { border-right: none; border-bottom: 1px solid var(--border); }
        .timestamp { flex-direction: column; gap: 10px; }
      }
    </style>
    <div class="wrapper">
      <div class="header-card">
        <h1>You're Connected to a <span>New Active Shopper</span></h1>
        <p>You now have direct access to this shopper's contact details. Reaching out early gives you the best chance to win the relationship.</p>
      </div>

      <div style="background:#f0f7ff; border-left:1px solid #e2e8f0; border-right:1px solid #e2e8f0; padding:20px 40px; border-bottom:1px solid #e2e8f0;">
        <p style="font-size:11px; font-weight:700; color:#0073E6; text-transform:uppercase; letter-spacing:1.2px; margin-bottom:10px;">What happens next</p>
        <ul style="list-style:none; padding:0; margin:0; font-size:13px; color:#475569; line-height:2;">
          <li>&#10003;&nbsp; This shopper is actively reviewing options</li>
          <li>&#10003;&nbsp; You have full access to reach out directly</li>
          <li>&#10003;&nbsp; Early contact typically leads to better engagement</li>
        </ul>
      </div>

      <div class="main-card">
        <p style="font-size:11px; font-weight:700; color:#F5A524; text-transform:uppercase; letter-spacing:1.5px; margin-bottom:6px;">Start here</p>
        <div class="section-label">Contact Information</div>
        <div class="info-grid">
          <div class="info-row">
            <div class="info-item"><span class="field-label">First Name</span><span class="field-value"><?php echo esc_html($data['first_name'] ?? ''); ?></span></div>
            <div class="info-item"><span class="field-label">Last Name</span><span class="field-value"><?php echo esc_html($data['last_name'] ?? ''); ?></span></div>
          </div>
          <div class="info-row">
            <div class="info-item">
              <span class="field-label">Phone Number</span>
              <span class="field-value"><a href="tel:<?php echo esc_attr($data['phone_number'] ?? ''); ?>"><?php echo esc_html($data['phone_number'] ?? ''); ?></a></span>
            </div>
            <div class="info-item">
              <span class="field-label">Email Address</span>
              <span class="field-value"><a href="mailto:<?php echo esc_attr($data['email_address'] ?? ''); ?>"><?php echo esc_html($data['email_address'] ?? ''); ?></a></span>
            </div>
          </div>
          <div class="info-row full">
            <div class="info-item"><span class="field-label">Address</span><span class="field-value"><?php echo esc_html(($data['address'] ?? '') . ', ' . ($data['city'] ?? '') . ', ' . ($data['state'] ?? '') . ' ' . ($data['zip_code'] ?? '')); ?></span></div>
          </div>
        </div>

        <div class="section-label">Personal Information</div>
        <div class="info-grid">
          <div class="info-row">
            <div class="info-item"><span class="field-label">Date of Birth</span><span class="field-value"><?php echo esc_html($data['month_and_year_dob'] ?? ''); ?></span></div>
            <div class="info-item"><span class="field-label">Marital Status</span><span class="field-value"><?php echo esc_html($data['marital_status'] ?? ''); ?></span></div>
          </div>
          <div class="info-row">
            <div class="info-item"><span class="field-label">Own or Rent</span><span class="field-value"><?php echo esc_html($data['own_or_rent_home'] ?? ''); ?></span></div>
            <div class="info-item"><span class="field-label">Military Affiliated</span><span class="field-value"><?php echo esc_html($data['is_military_affiliated'] ?? ''); ?></span></div>
          </div>
        </div>

        <p style="font-size:11px; font-weight:700; color:#64748b; text-transform:uppercase; letter-spacing:1px; margin-bottom:6px; margin-top:4px;">What we've gathered for you</p>
        <div class="section-label">Vehicle Information</div>
        <div class="info-grid">
          <div class="info-row thirds">
            <div class="info-item"><span class="field-label">Year</span><span class="field-value"><?php echo esc_html($data['primary_vehicle_year'] ?? ''); ?></span></div>
            <div class="info-item"><span class="field-label">Make</span><span class="field-value"><?php echo esc_html($data['primary_vehicle_make'] ?? ''); ?></span></div>
            <div class="info-item"><span class="field-label">Model</span><span class="field-value"><?php echo esc_html($data['primary_vehicle_model'] ?? ''); ?></span></div>
          </div>
          <div class="info-row">
            <div class="info-item"><span class="field-label">Primary Use</span><span class="field-value"><?php echo esc_html($data['primary_vehicle_use'] ?? ''); ?></span></div>
            <div class="info-item"><span class="field-label">Annual Miles</span><span class="field-value"><?php echo esc_html($data['primary_vehicle_annual_miles'] ?? ''); ?></span></div>
          </div>
          <div class="info-row">
            <div class="info-item"><span class="field-label">Ownership</span><span class="field-value"><?php echo esc_html($data['primary_vehicle_ownership_type'] ?? ''); ?></span></div>
            <div class="info-item"><span class="field-label">Number of Vehicles</span><span class="field-value"><?php echo esc_html($data['num_of_vehicles'] ?? ''); ?></span></div>
          </div>
        </div>

        <div class="section-label">Driving Record</div>
        <div class="info-grid">
          <div class="info-row">
            <div class="info-item"><span class="field-label">Accidents (last 5 yrs)</span><span class="field-value"><?php echo esc_html($data['num_of_accidents'] ?? ''); ?></span></div>
            <div class="info-item"><span class="field-label">Tickets (last 5 yrs)</span><span class="field-value"><?php echo esc_html($data['num_of_tickets'] ?? ''); ?></span></div>
          </div>
        </div>

        <p style="font-size:11px; font-weight:700; color:#F5A524; text-transform:uppercase; letter-spacing:1px; margin-bottom:6px; margin-top:4px;">Opportunity insight</p>
        <div class="section-label">Current Insurance</div>
        <div class="info-grid">
          <div class="info-row">
            <div class="info-item"><span class="field-label">Currently Insured</span><span class="field-value highlight"><?php echo esc_html($data['is_insured'] ?? ''); ?></span></div>
            <div class="info-item"><span class="field-label">Current Provider</span><span class="field-value"><?php echo esc_html($data['current_insurance_company'] ?? ''); ?></span></div>
          </div>
          <div class="info-row">
            <div class="info-item"><span class="field-label">Insured Duration</span><span class="field-value"><?php echo esc_html($data['continuous_insured_duration'] ?? ''); ?></span></div>
            <div class="info-item"><span class="field-label">Policy Expiration</span><span class="field-value"><?php echo esc_html($data['current_insurance_expiration'] ?? ''); ?></span></div>
          </div>
          <div class="info-row full">
            <div class="info-item"><span class="field-label">Bundle &amp; Save Interest</span><span class="field-value"><?php echo esc_html($data['bundle_and_save'] ?? ''); ?></span></div>
          </div>
        </div>

        <div style="border:1px solid #e2e8f0; border-radius:10px; padding:16px 20px; margin-bottom:24px; background:#f7f8fc;">
          <p style="font-size:11px; font-weight:700; color:#64748b; text-transform:uppercase; letter-spacing:1px; margin-bottom:8px;">Access Shopper Details</p>
          <p style="font-size:13px; color:#0073E6; word-break:break-all; margin-bottom:6px;">https://ensurance.com/lead-page/?id=<?php echo esc_html($lead_id); ?></p>
          <p style="font-size:11px; color:#94a3b8;">Save this link to access your shopper details anytime.</p>
        </div>

        <div class="timestamp">
          <div style="font-size:12px; color:#475569; line-height:1.7;">This shopper is currently reviewing insurance options.<br>You have full access to reach out and start the conversation.</div>
          <span class="status-pill">&#10003; Active Shopper</span>
        </div>
      </div>

      <div class="footer-card">
        <div>
          <p style="font-size:12px; color:rgba(255,255,255,0.6); margin-bottom:12px;">You're in control. Reach out when you're ready.</p>
          <img src="https://ensurance.com/wp-content/uploads/2026/03/ensurance-logo-finalized.png" width="150" alt="ensurance.com">
          <div class="footer-tagline">Online first. Human when it matters.</div>
        </div>
      </div>

      <p class="legal">
        This lead is exclusively assigned to you. Do not share or redistribute.<br>
        For support contact <a href="mailto:support@ensurance.com">support@ensurance.com</a>
      </p>
    </div>
    <?php
    return ob_get_clean();
}
add_shortcode('lead_page', 'lead_page_shortcode');

// ─────────────────────────────────────────────────────────────────────
// 2b-xiii. "START YOUR REQUEST" FORM SLOT — RENDER ONLY THE NINJA FORM
// The coverage quote templates (health / homeowners / renters / life /
// commercial) render their Ninja Form inside the .sq-formslot card. The
// original pattern rendered the page's editor content and relied on that
// content being ONLY the [ninja_form] shortcode — but these WordPress
// pages still hold their retired Gutenberg layouts (and staging refreshes
// from production restore them), so the old banners leaked into the card
// above the form. This renders just the form: the first [ninja_form]
// shortcode found in the page content, or the template's default form
// when none is present (form IDs are identical on staging + production).
// ─────────────────────────────────────────────────────────────────────
function ensurance_sq_render_form( $default_form_id ) {
    $content = get_post_field( 'post_content', get_the_ID() );
    if ( is_string( $content ) && preg_match( '/\[ninja_form\b[^\]]*\]/', $content, $m ) ) {
        echo do_shortcode( $m[0] );
        return;
    }
    echo do_shortcode( "[ninja_form id='" . absint( $default_form_id ) . "']" );
}

// ─────────────────────────────────────────────────────────────────────
// 2b-xiv. REMOVE GEODIRECTORY LOCATION-SWITCHER MODAL ON DESIGN PAGES
// GeoDirectory Location Manager prints a hidden "Change Location / Find
// awesome listings near you!" Bootstrap modal into wp_footer on every
// page. On the code-driven marketing pages that old directory language
// contradicts the trust-first experience (flagged in the 2026-07 copy
// review), so unhook it there. GeoDirectory listing pages are untouched.
// ─────────────────────────────────────────────────────────────────────
function ensurance_remove_gd_location_switcher() {
    if ( ! is_page() && ! is_front_page() ) {
        return;
    }
    $template = get_page_template_slug();
    if ( is_front_page() || ( is_string( $template ) && 0 === strpos( $template, 'page-' ) ) ) {
        remove_action( 'wp_footer', 'geodir_location_autocomplete_script' );
    }
}
add_action( 'template_redirect', 'ensurance_remove_gd_location_switcher' );

// ─────────────────────────────────────────────────────────────────────
// 2b-xv. INVESTOR BRIEF (CALM INTELLIGENCE REDESIGN) — FONT SWAP
// The investor brief was rebuilt on the Calm Intelligence design system
// (Albert Sans display, Rubik body, JetBrains Mono labels). The original
// enqueue in ensurance_marketing_fonts() still loads Inter for this
// template; per the "never modify existing functions" rule we dequeue it
// here at priority 20 and load the correct families instead. The page's
// CSS/JS enqueues in ensurance_marketing_assets() are unchanged — the
// same investor.css / investor.js files now carry the new design.
// ─────────────────────────────────────────────────────────────────────
function ensurance_investor_brief_fonts() {
    if ( ! is_page_template( 'page-investor-brief.php' ) ) {
        return;
    }

    // Inter belonged to the previous investor-brief design.
    wp_dequeue_style( 'ensurance-investor-fonts' );

    wp_enqueue_style(
        'ensurance-investor-brief-fonts',
        'https://fonts.googleapis.com/css2?family=Albert+Sans:wght@700;800;900&family=Rubik:wght@300;400;500&family=JetBrains+Mono:wght@400;500;600&display=swap',
        array(),
        null
    );
}
add_action( 'wp_enqueue_scripts', 'ensurance_investor_brief_fonts', 20 );
