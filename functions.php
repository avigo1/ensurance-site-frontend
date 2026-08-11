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
// 2b-v-a2. AGENT LOGIN (/login) — SELF-CONTAINED ASSETS
// ============================================================================
// /login is rebuilt from the old UsersWP-shortcode page into a code-driven
// template (page-login.php) on the homepage chrome. It reuses assets/home.css +
// assets/home.js for tokens, chrome and base, and layers assets/login.css +
// assets/login.js on top for the page-specific sections (hero, login grid,
// new-agent card, trust callout, support, footer CTA). The login form is
// re-skinned only — UsersWP still processes authentication. As with the other
// standalone pages we DEQUEUE the shared marketing bundle so its generic
// selectors cannot fight this design. is_page('login') is the reliable gate
// (is_page_template can miss on DB-meta pages). New function — existing
// functions untouched.
function ensurance_login_assets() {
    if ( ! is_page( 'login' ) ) {
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
        'ensurance-login',
        get_stylesheet_directory_uri() . '/assets/login.css',
        array('ensurance-home'),
        filemtime(get_stylesheet_directory() . '/assets/login.css')
    );
    wp_enqueue_script(
        'ensurance-login',
        get_stylesheet_directory_uri() . '/assets/login.js',
        array('ensurance-home'),
        filemtime(get_stylesheet_directory() . '/assets/login.js'),
        true
    );
}
add_action('wp_enqueue_scripts', 'ensurance_login_assets', 20);

// ============================================================================
// 2b-v-a3. CREATE ACCOUNT (/create-account) — SELF-CONTAINED ASSETS
// ============================================================================
// /create-account is a standalone, code-driven sign-up screen
// (page-create-account.php) that re-skins the UsersWP registration form. Like
// /login it reuses assets/home.css + assets/home.js for the Calm Intelligence
// tokens, fonts and base, and layers assets/create-account.css +
// assets/create-account.js (password show/hide) on top. The shared marketing
// bundle is dequeued so its selectors cannot fight this design.
// is_page('create-account') is the reliable gate.
function ensurance_create_account_assets() {
    if ( ! is_page( 'create-account' ) ) {
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
        'ensurance-create-account',
        get_stylesheet_directory_uri() . '/assets/create-account.css',
        array('ensurance-home'),
        filemtime(get_stylesheet_directory() . '/assets/create-account.css')
    );
    wp_enqueue_script(
        'ensurance-create-account',
        get_stylesheet_directory_uri() . '/assets/create-account.js',
        array('ensurance-home'),
        filemtime(get_stylesheet_directory() . '/assets/create-account.js'),
        true
    );
}
add_action('wp_enqueue_scripts', 'ensurance_create_account_assets', 20);

// ============================================================================
// 2b-v-a3. AGENT DASHBOARD (/dashboard) — SELF-CONTAINED ASSETS
// ============================================================================
// The placeholder dashboard is another agent-side Calm Intelligence page, so it
// reuses assets/home.css + assets/home.js for the tokens, fonts, base and chrome
// bars, and layers assets/dashboard.css on top. The shared marketing bundle is
// dequeued so its selectors cannot fight this design. Mirrors
// ensurance_create_account_assets(). is_page('dashboard') is the reliable gate.
function ensurance_dashboard_assets() {
    if ( ! is_page( 'dashboard' ) ) {
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

    // Page-specific layer — loaded AFTER home.css via dependency.
    wp_enqueue_style(
        'ensurance-dashboard',
        get_stylesheet_directory_uri() . '/assets/dashboard.css',
        array('ensurance-home'),
        filemtime(get_stylesheet_directory() . '/assets/dashboard.css')
    );
}
add_action('wp_enqueue_scripts', 'ensurance_dashboard_assets', 20);

/**
 * Agent Dashboard view-switching script.
 *
 * assets/dashboard.js swaps the dashboard's view containers in place on a
 * rail click — the behavior the AgentDashboard design specifies (setState +
 * the `.ens-view` fade) — instead of letting the browser navigate. It is a
 * pure enhancement: without it the rail's links still navigate and PHP
 * renders the requested view, so nothing here is load-bearing.
 *
 * Added as its OWN function rather than a line inside
 * ensurance_dashboard_assets() to respect the standing rule in CLAUDE.md —
 * new functions only, never edits to existing ones. Priority 21 keeps it
 * ordered after that function; the script has no dependencies and is
 * footer-loaded, so the ordering is for readability, not correctness.
 *
 * @return void
 */
function ensurance_dashboard_view_script() {
    if ( ! is_page( 'dashboard' ) ) {
        return;
    }

    wp_enqueue_script(
        'ensurance-dashboard',
        get_stylesheet_directory_uri() . '/assets/dashboard.js',
        array(),
        filemtime( get_stylesheet_directory() . '/assets/dashboard.js' ),
        true
    );
}
add_action('wp_enqueue_scripts', 'ensurance_dashboard_view_script', 21);

/**
 * Which dashboard view is currently showing.
 *
 * The AgentDashboard design (templates/agent-dashboard/AgentDashboard.dc.html in
 * the Ensurance Design System) is a single shell whose left rail switches an
 * in-page view; the prototype holds that in client state. Server-side the same
 * idea is a `?view=` slug on /dashboard/, which keeps every view linkable,
 * bookmarkable and back-button-friendly — and lets the rail render its active
 * item in the initial HTML rather than after a paint.
 *
 * Deliberately NOT validated against a list of views: it sanitizes, it does not
 * check membership, so an unrecognized slug simply matches no rail item
 * (nothing highlights) rather than erroring. page-dashboard.php and
 * assets/dashboard.js each fall back to the default view when they get one.
 *
 * Used by components/dashboard-nav-item.php to decide the `is-active` state.
 *
 * @return string Sanitized view slug, e.g. 'today', 'requests', 'profile'.
 */
function ensurance_dashboard_current_view() {
    // Read-only presentation state — no side effects, so no nonce to verify.
    if ( empty( $_GET['view'] ) ) {
        return ensurance_dashboard_default_view();
    }

    $view = sanitize_key( wp_unslash( $_GET['view'] ) );

    return '' !== $view ? $view : ensurance_dashboard_default_view();
}

/**
 * The view /dashboard/ shows when the URL names none, or names one that does
 * not exist.
 *
 * Derived from ensurance_dashboard_views() rather than written out, so the
 * default is always the rail's first row — the design's own reading of "where
 * an agent lands". Renaming or reordering that first entry cannot leave a
 * stale slug behind in the three places that need this value
 * (ensurance_dashboard_current_view(), page-dashboard.php's `?view=` fallback,
 * and assets/dashboard.js, which reads it off `data-default-view`).
 *
 * @return string View slug, '' if the registry is somehow empty.
 */
function ensurance_dashboard_default_view() {
    $views = ensurance_dashboard_views();
    $first = reset( $views );

    return is_array( $first ) ? (string) $first['view'] : '';
}

/**
 * How many matched requests are waiting on the agent's decision.
 *
 * Drives the count badge on the rail's Requests row (`hasLive` / `liveCount` in
 * the AgentDashboard design). NOTHING PRODUCES REQUESTS YET — matching is not
 * built, and the Requests view itself is Step 12 of
 * templates/agent-dashboard/build-steps.md — so this returns 0 today and the
 * badge hides itself at zero exactly as the design specifies. That is the
 * honest state for a founding agent whose queue is empty, not a placeholder to
 * be dressed up with a fake number.
 *
 * When the real queue exists, return its count through the filter below (or
 * repoint this function at it) and the badge lights up with no change to the
 * rail, the registry or the nav-item component.
 *
 * @param int $user_id Optional. Defaults to the current user.
 * @return int Count of awaiting requests, never negative.
 */
function ensurance_dashboard_request_count( $user_id = 0 ) {
    $user_id = $user_id ? (int) $user_id : get_current_user_id();

    /**
     * Filter the awaiting-request count shown on the dashboard rail.
     *
     * @param int $count   Number of requests awaiting a decision.
     * @param int $user_id User the count is for.
     */
    $count = (int) apply_filters( 'ensurance_dashboard_request_count', 0, $user_id );

    return max( 0, $count );
}

/**
 * The Agent Dashboard's rail and views — THE single source of truth.
 *
 * One entry per row of the left rail in the AgentDashboard design
 * (templates/agent-dashboard/AgentDashboard.dc.html in the Ensurance Design
 * System), in the design's order. Everything the dashboard needs is derived
 * from this array:
 *
 *   - components/dashboard-sidebar.php loops it into nav items.
 *   - page-dashboard.php loops it into the matching .dash-view containers,
 *     and plucks `view` for the list of slugs it accepts in `?view=`.
 *
 * ADDING A ROW: append ONE entry here. The nav item, the view container, the
 * `?view=` deep link, the active highlight and the in-place fade in
 * assets/dashboard.js all come with it — there is nothing else to edit and no
 * second list to keep in step. (Before this array existed, those were three
 * hand-maintained lists in two files; a row whose container was missing
 * silently fell back to a full page load and the Dashboard view.)
 *
 * FIELDS
 *   view     string  required  Slug. Used in `?view=`, as the container's
 *                              data-view, and matched against
 *                              ensurance_dashboard_current_view() for the
 *                              active state.
 *   label    string  required  Rail row text.
 *   icon     string  optional  Inline SVG for the rail glyph. THEME-AUTHORED
 *                              markup only — components/dashboard-nav-item.php
 *                              runs it through wp_kses regardless. Use
 *                              `currentColor` so it inherits the row's color.
 *                              Glyphs are the design's own, copied path-for-
 *                              path from components/icons/Icon.jsx in the
 *                              design system (Lucide, stroke 2, round caps/
 *                              joins) at the rail's 17px.
 *   badge    int     optional  Count shown in a pill at the right of the row.
 *                              0 (the default) renders no pill — the design
 *                              hides the badge at zero rather than showing a
 *                              "0". Resolve it from a function, not a literal,
 *                              so the row cannot go stale.
 *   title    string  optional  <h1> of the view. Also becomes the container's
 *                              aria-label; falls back to `label`. A view with
 *                              no title and no `part` renders an EMPTY
 *                              container — which is every view but Today as of
 *                              Step 3 of templates/agent-dashboard/
 *                              build-steps.md, where only Today has content.
 *   eyebrow  string  optional  Kicker above the title. Empty by default: the
 *                              current design has no per-view eyebrow.
 *   intro    string  optional  Lead paragraph.
 *   href     string  optional  Destination. Defaults to `?view=<view>` on
 *                              /dashboard/.
 *   modifier string  optional  Extra class on the container.
 *   part     string  optional  Template part rendered INSIDE the container
 *                              instead of the generic eyebrow/title/intro —
 *                              the escape hatch for views whose real content
 *                              is a card grid, status rows, an accordion and
 *                              so on. Ignored if the file does not exist, so
 *                              a view can be listed before it is built.
 *
 * @return array[] Ordered rail items, defaults applied.
 */
function ensurance_dashboard_views() {
    $items = array(
        // The rail's first row, and the view the page falls back to. Its href
        // is the bare /dashboard/ URL rather than ?view=today:
        // ensurance_dashboard_current_view() defaults to this entry (via
        // ensurance_dashboard_default_view()), so the clean URL lands here and
        // keeps the row lit.
        array(
            'view'  => 'today',
            'label' => 'Today',
            // Icon `home`.
            'icon'  => '<svg viewBox="0 0 24 24" width="17" height="17" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true" focusable="false"><path d="m3 9 9-7 9 7v11a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2z"/><path d="M9 22V12h6v10"/></svg>',
            'href'  => home_url( '/dashboard/' ),
            // Today's <h1> is the greeting itself, not a view name, and the
            // design gives it no eyebrow or intro — so it renders through its
            // own part instead of the generic header the other three use.
            // Everything Phase 2 of build-steps.md adds (priority slot,
            // timeline, reference columns) appends inside that file.
            'part'  => 'components/dashboard-view-today',
        ),
        // The only row in the design that carries a count. It is resolved here
        // on every render rather than stored, so the pill can never disagree
        // with the queue — see ensurance_dashboard_request_count(), which
        // returns 0 until the matching pipeline exists, hiding the pill.
        array(
            'view'  => 'requests',
            'label' => 'Requests',
            // Icon `file-text`.
            'icon'  => '<svg viewBox="0 0 24 24" width="17" height="17" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true" focusable="false"><path d="M15 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V7Z"/><path d="M14 2v4a2 2 0 0 0 2 2h4"/><path d="M10 9H8"/><path d="M16 13H8"/><path d="M16 17H8"/></svg>',
            'badge' => ensurance_dashboard_request_count(),
        ),
        array(
            'view'  => 'profile',
            'label' => 'Agency Profile',
            // Icon `user`.
            'icon'  => '<svg viewBox="0 0 24 24" width="17" height="17" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true" focusable="false"><circle cx="12" cy="8" r="5"/><path d="M20 21a8 8 0 0 0-16 0"/></svg>',
        ),
        array(
            'view'  => 'account',
            'label' => 'Account',
            // Icon `lock`.
            'icon'  => '<svg viewBox="0 0 24 24" width="17" height="17" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true" focusable="false"><rect x="3" y="11" width="18" height="11" rx="2"/><path d="M7 11V7a5 5 0 0 1 10 0v4"/></svg>',
        ),
    );

    $defaults = array(
        'view'     => '',
        'label'    => '',
        'icon'     => '',
        'badge'    => 0,
        'title'    => '',
        'eyebrow'  => '',
        'intro'    => '',
        'href'     => '',
        'modifier' => '',
        'part'     => '',
    );

    foreach ( $items as $i => $item ) {
        $item = array_merge( $defaults, $item );

        if ( '' === $item['href'] ) {
            $item['href'] = add_query_arg( 'view', $item['view'], home_url( '/dashboard/' ) );
        }

        $items[ $i ] = $item;
    }

    return $items;
}

/**
 * The agency name shown on the dashboard rail's user card.
 *
 * The design (templates/agent-dashboard/AgentDashboard.dc.html) takes this from
 * an `agencyName` prop — "Coastline Insurance Group". Nothing in the funnel
 * captures an agency name yet: /create-account collects first name, last name,
 * username and email only, and agents do not manage their own listings (see the
 * product note in plans/agent-onboarding-1-free-agent.md). So this resolves the
 * best name the user record actually carries, in that order:
 *
 *   display_name → "First Last" → user_login
 *
 * When the Agency Profile view lands and the real agency record exists, point
 * this at it through the `ensurance_dashboard_agency_name` filter rather than
 * editing the rail — the card, its initials and anything else that greets the
 * agent all read from here.
 *
 * @param int $user_id Optional. Defaults to the current user.
 * @return string Agency name, '' if there is no user (logged-out callers).
 */
function ensurance_dashboard_agency_name( $user_id = 0 ) {
    $user = $user_id ? get_userdata( (int) $user_id ) : wp_get_current_user();

    if ( ! ( $user instanceof WP_User ) || empty( $user->ID ) ) {
        return '';
    }

    $name = trim( (string) $user->display_name );

    if ( '' === $name ) {
        $name = trim( $user->first_name . ' ' . $user->last_name );
    }

    if ( '' === $name ) {
        $name = (string) $user->user_login;
    }

    /**
     * Filter the agency name on the dashboard rail's user card.
     *
     * @param string  $name The resolved name.
     * @param WP_User $user The user it was resolved from.
     */
    return (string) apply_filters( 'ensurance_dashboard_agency_name', $name, $user );
}

/**
 * Initials for the user card's avatar circle.
 *
 * Same rule as the design's `initials`: the first letter of each of the first
 * two words, uppercased — "Coastline Insurance Group" → "CI". Falls back to the
 * design's own fallback, "A", when the name yields no letters, so the circle is
 * never empty.
 *
 * @param string $name Name to reduce. Defaults to ensurance_dashboard_agency_name().
 * @return string One or two uppercase characters.
 */
function ensurance_dashboard_agency_initials( $name = '' ) {
    if ( '' === $name ) {
        $name = ensurance_dashboard_agency_name();
    }

    $words    = preg_split( '/\s+/', trim( $name ), -1, PREG_SPLIT_NO_EMPTY );
    $initials = '';

    foreach ( array_slice( (array) $words, 0, 2 ) as $word ) {
        $initials .= mb_substr( $word, 0, 1 );
    }

    // mb_strtoupper is not part of WordPress's mbstring polyfill set, unlike
    // mb_substr above — so only use it when the extension is really loaded.
    $initials = function_exists( 'mb_strtoupper' ) ? mb_strtoupper( $initials ) : strtoupper( $initials );

    return '' !== $initials ? $initials : 'A';
}

/**
 * The agent's first name, for the dashboard's "Welcome back, {firstName}" line.
 *
 * The design takes this from an `agentName` prop and splits on whitespace
 * (`agentName.split(/\s+/)[0]`). Here it comes off the user record, in the
 * order that gives the friendliest name actually available:
 *
 *   first_name → first word of display_name → user_login
 *
 * /create-account collects a first name, so the first branch is what a
 * funnel-created agent normally hits; the rest cover users created another way
 * (wp-admin, an import) whose first_name is empty.
 *
 * Returns '' when there is no name to greet — the greeting line is then skipped
 * entirely rather than rendering a bare "Welcome back,".
 *
 * @param int $user_id Optional. Defaults to the current user.
 * @return string First name, or '' if there is no user.
 */
function ensurance_dashboard_first_name( $user_id = 0 ) {
    $user = $user_id ? get_userdata( (int) $user_id ) : wp_get_current_user();

    if ( ! ( $user instanceof WP_User ) || empty( $user->ID ) ) {
        return '';
    }

    $name = trim( (string) $user->first_name );

    if ( '' === $name ) {
        $name = trim( (string) $user->display_name );
    }

    if ( '' === $name ) {
        $name = (string) $user->user_login;
    }

    // Only the first word — display_name is often "First Last", and greeting
    // someone by their full name reads like a form letter.
    $words = preg_split( '/\s+/', $name, -1, PREG_SPLIT_NO_EMPTY );
    $first = ( is_array( $words ) && isset( $words[0] ) ) ? $words[0] : '';

    /**
     * Filter the first name used in the dashboard's welcome line.
     *
     * @param string  $first The resolved first name.
     * @param WP_User $user  The user it was resolved from.
     */
    return (string) apply_filters( 'ensurance_dashboard_first_name', $first, $user );
}

/**
 * The Today view's greeting — "Good morning, Marcus".
 *
 * Step 3 of templates/agent-dashboard/build-steps.md. The design hardcodes
 * `greeting: 'Good morning, ' + first`; the requirement is that it be
 * TIME-AWARE, so the salutation is picked from the hour here:
 *
 *   before 12:00 → morning, before 17:00 → afternoon, otherwise evening.
 *
 * WHICH clock: the SITE's timezone (wp_date), not the agent's. There is no
 * server-side way to know the visitor's, and the design's own timestamp is
 * stamped "PT" — one business clock for everyone. The stamp beside the
 * greeting names that timezone (ensurance_dashboard_timestamp), so an agent
 * reading "Good evening" at their 4pm can see which clock it is on. /dashboard
 * is a signed-in surface and therefore uncached, so the hour is the real one at
 * render time.
 *
 * The name is whatever ensurance_dashboard_first_name() resolves, and the
 * comma comes with it: a user record with no name at all greets with a bare
 * "Good morning" rather than "Good morning, ".
 *
 * @param int $user_id   Optional. Defaults to the current user.
 * @param int $timestamp Optional. Unix time to read the hour from. Defaults to
 *                       now. Pass the same value used for the stamp so the two
 *                       cannot straddle a minute (or an hour) boundary.
 * @return string Greeting line, never empty.
 */
function ensurance_dashboard_greeting( $user_id = 0, $timestamp = 0 ) {
    $timestamp = $timestamp ? (int) $timestamp : time();
    $first     = ensurance_dashboard_first_name( $user_id );

    // 'G' is the 24-hour hour without a leading zero, in the site's timezone.
    $hour = (int) wp_date( 'G', $timestamp );

    if ( $hour < 12 ) {
        $greeting = 'Good morning';
    } elseif ( $hour < 17 ) {
        $greeting = 'Good afternoon';
    } else {
        $greeting = 'Good evening';
    }

    if ( '' !== $first ) {
        $greeting .= ', ' . $first;
    }

    /**
     * Filter the dashboard's greeting line.
     *
     * @param string $greeting The assembled greeting.
     * @param string $first    First name it was built with, '' if none.
     * @param int    $hour     Site-local hour the salutation was picked from.
     */
    return (string) apply_filters( 'ensurance_dashboard_greeting', $greeting, $first, $hour );
}

/**
 * The timestamp shown opposite the greeting — "Tue Aug 11 · 9:42 AM PDT".
 *
 * The design's `stamp` is the fixed string "Tue Aug 11 · 9:42 AM PT"; this is
 * the live equivalent, formatted the same way and in the same site timezone the
 * greeting reads its hour from.
 *
 * The zone comes from wp_date's 'T' rather than a literal "PT", so the label
 * cannot go stale: it follows the site's timezone setting and says PDT/PST
 * rather than claiming one of them year-round. A site configured with a manual
 * UTC offset instead of a named zone gets that offset ("+05:30") — correct, if
 * less friendly, and the fix is to set a real timezone in Settings → General.
 *
 * Built from three wp_date() calls on ONE timestamp rather than a single format
 * string, because the "·" separator is multibyte and PHP's date() escape
 * (a backslash) only covers one byte of it.
 *
 * @param int $timestamp Optional. Unix time to format. Defaults to now.
 * @return string Formatted stamp. The CSS uppercases it for display.
 */
function ensurance_dashboard_timestamp( $timestamp = 0 ) {
    $timestamp = $timestamp ? (int) $timestamp : time();

    $stamp = wp_date( 'D M j', $timestamp )
        . ' · '
        . wp_date( 'g:i A', $timestamp )
        . ' ' . wp_date( 'T', $timestamp );

    /**
     * Filter the dashboard's greeting-row timestamp.
     *
     * @param string $stamp     The formatted stamp.
     * @param int    $timestamp Unix time it was formatted from.
     */
    return (string) apply_filters( 'ensurance_dashboard_timestamp', $stamp, $timestamp );
}

/**
 * The four states Today's priority slot can be in — THE list.
 *
 * Step 4 of templates/agent-dashboard/build-steps.md. The slot is the one
 * surface under the greeting that shows the single thing needing the agent's
 * attention, and it shows exactly ONE of these at a time — the design's
 * `deskState` enum plus the decided state its Accept/Pass buttons produce:
 *
 *   live     A matched request is waiting on a decision (Steps 5–6).
 *   setup    The agent is not matchable yet — something blocks matching (Step 9).
 *   quiet    Matching is on and nothing is waiting (Step 8).
 *   decided  The agent just accepted or passed (Step 7).
 *
 * In the design's own order of appearance in the template. Keys are the values
 * the slot is driven by (and the ones `?slot=` accepts); values are the plain
 * labels the placeholder box shows until each state's real surface is built.
 *
 * Three things read this one array: ensurance_dashboard_priority_state()
 * validates against it, the preview toggle accepts only its keys, and
 * components/dashboard-view-today.php looks up the label to render. A state
 * therefore cannot exist in one place and not another.
 *
 * @return array<string,string> State slug => placeholder label, in design order.
 */
function ensurance_dashboard_priority_states() {
    return array(
        'live'    => 'Live request',
        'setup'   => 'Setup',
        'quiet'   => 'Quiet',
        'decided' => 'Decided',
    );
}

/**
 * Which of those four states Today's priority slot is showing.
 *
 * THE single value the slot is driven by — the server-side equivalent of the
 * design's `deskState` prop. components/dashboard-view-today.php renders the one
 * state this returns and nothing else: no stacking, and no fallback branch that
 * paints when none of the four match.
 *
 * WHAT IT RESOLVES TO TODAY. Nothing in the product produces requests or records
 * decisions yet (see ensurance_dashboard_request_count), and an agent cannot be
 * matched until their service areas and coverage types exist — which no funnel
 * captures. So a founding agent is genuinely in `setup`, and that is what this
 * returns; `live` only when a request really is waiting, which is where the real
 * queue will light it up with no change here. `decided` follows a decision the
 * agent actually made (ensurance_dashboard_decided_slot), so it is reachable
 * today wherever `live` is; `quiet` is still only reachable through the filter
 * and the preview toggle below. That is the honest reading of the current
 * product, not a placeholder chosen to look good.
 *
 * @param int $user_id Optional. Defaults to the current user.
 * @return string One of the keys of ensurance_dashboard_priority_states().
 */
function ensurance_dashboard_priority_state( $user_id = 0 ) {
    $user_id = $user_id ? (int) $user_id : get_current_user_id();
    $states  = ensurance_dashboard_priority_states();

    $state = ( ensurance_dashboard_request_count( $user_id ) > 0 ) ? 'live' : 'setup';

    /**
     * Filter the state of the dashboard's priority slot.
     *
     * The hook the real queue / onboarding checks attach to when they exist.
     * Values outside ensurance_dashboard_priority_states() are ignored.
     *
     * @param string $state   Resolved state slug.
     * @param int    $user_id User the slot is being resolved for.
     */
    $filtered = (string) apply_filters( 'ensurance_dashboard_priority_state', $state, $user_id );

    if ( isset( $states[ $filtered ] ) ) {
        $state = $filtered;
    }

    // The dev toggle wins over everything above — it exists to look at a state
    // the product cannot currently produce.
    $preview = ensurance_dashboard_priority_preview();

    return '' !== $preview ? $preview : $state;
}

/**
 * The priority slot's dev/preview override — `?slot=quiet` on /dashboard/.
 *
 * Step 4 asks for the slot's driving value to be exposed as a tweak toggle, the
 * way the design's props panel exposes `deskState`. There is no props panel on a
 * WordPress page, so the equivalent is a query arg: /dashboard/?slot=setup shows
 * the setup state, ?slot=decided the decided one, and so on through
 * ensurance_dashboard_priority_states(). Anything else is ignored.
 *
 * WHO GETS IT: on PRODUCTION, administrators only. The states carry real-
 * sounding request, billing and contact copy as they are built out (Steps 5–9),
 * and an agent stumbling onto ?slot=live would be looking at a fabricated
 * request.
 *
 * On STAGING (and any other non-production environment) that drops to any
 * signed-in user, because the whole point of staging19 is reviewing surfaces the
 * product cannot produce yet — and every reviewer there is a teammate, on a site
 * whose data is not real. wp_get_environment_type() is what tells the two apart:
 * SiteGround's staging system defines WP_ENVIRONMENT_TYPE = 'staging' in that
 * site's wp-config.php, and production reports the 'production' default.
 *
 * Either way the capability stays filterable, so a single reviewer can be
 * granted or denied it without touching this function:
 *
 *     add_filter( 'ensurance_dashboard_priority_preview_cap', fn() => 'read' );
 *
 * The override lives in the URL rather than in state, so it is display-only,
 * shareable, and gone the moment it is dropped from the address bar. Note the
 * rail's links do not carry it: switching views and coming back to Today returns
 * the slot to its resolved state.
 *
 * @return string A valid state slug, or '' when no preview is in effect.
 */
function ensurance_dashboard_priority_preview() {
    // Read-only presentation state — no side effects, so no nonce to verify.
    // Same reasoning as ensurance_dashboard_current_view().
    if ( empty( $_GET['slot'] ) ) {
        return '';
    }

    // 'read' is "any signed-in user" — /dashboard/ has already bounced everyone
    // else to /login by the time this runs.
    $default_cap = ( 'production' === wp_get_environment_type() ) ? 'manage_options' : 'read';

    /**
     * Filter the capability required to preview a priority-slot state.
     *
     * @param string $capability Capability checked before honoring `?slot=`.
     */
    $capability = (string) apply_filters( 'ensurance_dashboard_priority_preview_cap', $default_cap );

    if ( ! current_user_can( $capability ) ) {
        return '';
    }

    $slot   = sanitize_key( wp_unslash( $_GET['slot'] ) );
    $states = ensurance_dashboard_priority_states();

    return isset( $states[ $slot ] ) ? $slot : '';
}

/**
 * The matched request Today's `live` slot is asking a decision about.
 *
 * Step 5 of templates/agent-dashboard/build-steps.md. The live card names ONE
 * request — its coverage type and county, when it expires, and the handful of
 * facts the agent decides on — so this is where that request comes from, and the
 * only place it does. components/dashboard-slot-live.php renders what this
 * returns and never fills in a field it is missing.
 *
 * THERE IS NO REQUEST TO RETURN TODAY. Nothing in the product produces matched
 * requests yet — the same reason ensurance_dashboard_request_count() returns 0
 * and ensurance_dashboard_priority_state() therefore resolves to `setup` — so
 * this returns an empty array, and a live slot with no request renders NOTHING
 * rather than a dark card of invented facts.
 *
 * The one exception is the admin-only preview toggle: /dashboard/?slot=live is
 * how this card gets reviewed before a queue exists, so that path (and only that
 * path) returns the design's own sample request. An agent cannot reach it —
 * ensurance_dashboard_priority_preview() is capability-gated for exactly this
 * reason.
 *
 * When the real queue lands, return its awaiting request through the filter
 * below and the card, its countdown and its tiles all follow with no change to
 * the markup.
 *
 * RETURN SHAPE
 *   coverage   string  Coverage type, as it reads before the word "coverage"
 *                      in the headline ("Auto" → "Auto coverage — …").
 *   county     string  County the request came from.
 *   expires_at int     Unix time the request stops being decidable, 0 for none.
 *                      A moment rather than a written-down "21h 40m", so the
 *                      countdown cannot be stale on a cached render.
 *   facts      array   Up to four ['label' => …, 'value' => …] pairs, in the
 *                      order they should be shown.
 *
 * Coverage and county are BOTH required — the headline names both, so a request
 * missing either is not renderable and comes back as no request at all.
 *
 * @param int $user_id Optional. Defaults to the current user.
 * @return array The request, or an empty array when nothing is waiting.
 */
/**
 * The design's own sample request — the ONE fabricated request on this page.
 *
 * Kept in a single function because two preview surfaces are about the same
 * request and must not disagree about it: the live card
 * (ensurance_dashboard_live_request) and the confirmation panel that follows a
 * decision on it (ensurance_dashboard_decided_county), which names the county
 * a passed request moved on to.
 *
 * PREVIEW ONLY. Nothing reaches this except through
 * ensurance_dashboard_priority_preview(), which is capability-gated — an agent
 * can never be shown these values. Copied field for field from the live card in
 * templates/agent-dashboard/AgentDashboard.dc.html.
 *
 * @return array A request in ensurance_dashboard_live_request()'s shape.
 */
function ensurance_dashboard_sample_request() {
    return array(
        'coverage'   => 'Auto',
        'county'     => 'Coastal County',
        // The design's fixed "Expires in 21h 40m" expressed as a real moment,
        // so the preview exercises the countdown rather than hardcoding its
        // output.
        'expires_at' => time() + ( 21 * HOUR_IN_SECONDS ) + ( 40 * MINUTE_IN_SECONDS ),
        'facts'      => array(
            array( 'label' => 'Shopper ZIP', 'value' => '93013' ),
            array( 'label' => 'Household', 'value' => '2 drivers, 2 vehicles' ),
            array( 'label' => 'Current carrier', 'value' => 'Renews in 6 weeks' ),
            array( 'label' => 'Submitted', 'value' => '2 hours ago' ),
        ),
    );
}

function ensurance_dashboard_live_request( $user_id = 0 ) {
    $user_id = $user_id ? (int) $user_id : get_current_user_id();

    $request = array();

    // Admin preview only — see the note above.
    if ( 'live' === ensurance_dashboard_priority_preview() ) {
        $request = ensurance_dashboard_sample_request();
    }

    /**
     * Filter the request shown in the dashboard's live priority slot.
     *
     * The hook the real matching queue attaches to when it exists. Return an
     * array in the shape documented above, or an empty array for "nothing is
     * waiting" — anything without a coverage type and a county is ignored.
     *
     * @param array $request Request data, empty when there is none.
     * @param int   $user_id User the slot is being resolved for.
     */
    $request = apply_filters( 'ensurance_dashboard_live_request', $request, $user_id );

    if ( ! is_array( $request ) || empty( $request['coverage'] ) || empty( $request['county'] ) ) {
        return array();
    }

    // Drop half-filled tiles rather than rendering a labeled empty box.
    $facts = array();

    if ( ! empty( $request['facts'] ) && is_array( $request['facts'] ) ) {
        foreach ( $request['facts'] as $fact ) {
            if ( empty( $fact['label'] ) || empty( $fact['value'] ) ) {
                continue;
            }

            $facts[] = array(
                'label' => (string) $fact['label'],
                'value' => (string) $fact['value'],
            );
        }
    }

    return array(
        'coverage'   => (string) $request['coverage'],
        'county'     => (string) $request['county'],
        'expires_at' => isset( $request['expires_at'] ) ? (int) $request['expires_at'] : 0,
        // The design's row is four tiles wide; a fifth would wrap to a lone
        // tile on a row of its own, so extras are dropped rather than allowed
        // to reshape the card.
        'facts'      => array_slice( $facts, 0, 4 ),
    );
}

/**
 * How long is left on a request — the "21h 40m" in the live card's countdown.
 *
 * The design writes that string out; here it is computed from the request's
 * expiry so it is right on every render. Deliberately COARSE and clock-styled
 * rather than WordPress's own human_time_diff(), which rounds to a single unit
 * ("22 hours") and loses the minutes the design shows as the deadline closes in.
 *
 * Returns '' for an expired or unset deadline, which is the caller's signal to
 * render no countdown at all — an "Expires in 0m" line would be worse than
 * none, and an expired request is a queue problem, not a display one.
 *
 * @param int $expires_at Unix time the request expires.
 * @param int $now        Optional. Moment to measure from. Defaults to now.
 * @return string Countdown like "1d 4h", "21h 40m" or "9m"; '' if none is due.
 */
function ensurance_dashboard_countdown( $expires_at, $now = 0 ) {
    $expires_at = (int) $expires_at;
    $now        = $now ? (int) $now : time();
    $left       = $expires_at - $now;

    if ( $expires_at <= 0 || $left <= 0 ) {
        return '';
    }

    $days    = (int) floor( $left / DAY_IN_SECONDS );
    $hours   = (int) floor( ( $left % DAY_IN_SECONDS ) / HOUR_IN_SECONDS );
    $minutes = (int) floor( ( $left % HOUR_IN_SECONDS ) / MINUTE_IN_SECONDS );

    // Two units at most, largest first — the smaller one stops mattering once
    // the larger is big enough to say.
    if ( $days > 0 ) {
        return sprintf( '%dd %dh', $days, $hours );
    }

    if ( $hours > 0 ) {
        return sprintf( '%dh %dm', $hours, $minutes );
    }

    // Under a minute still reads as "1m" — there IS time left, and rounding it
    // to zero would say otherwise.
    return sprintf( '%dm', max( 1, $minutes ) );
}

/**
 * The two decisions an agent can make about a live request — THE list.
 *
 * Step 6 of templates/agent-dashboard/build-steps.md. Accept and Pass are peers
 * in the design and peers here: one list, one handler, one outcome for the slot
 * (both leave it `decided`). Nothing in this file treats one as the expected
 * answer and the other as an escape hatch — no confirmation step on Pass, no
 * extra hoop, no second thought.
 *
 * The slugs are what the card's two buttons submit, what the handler validates
 * against, and what `?decision=` accepts when a previewed card is decided.
 *
 * @return string[] Decision slugs, in the order the card offers them.
 */
function ensurance_dashboard_decisions() {
    return array( 'accept', 'pass' );
}

/**
 * User-meta key holding the decision an agent has just made on Today's slot.
 *
 * INTERIM STORE. The real home for a decision is the matching queue — a row
 * against the request that was decided, by whom and when — and none of that
 * exists yet (see ensurance_dashboard_live_request). Until it does, this one
 * value is what keeps the slot in `decided` across the redirect that follows the
 * POST, and it is deliberately the smallest thing that can: which way the agent
 * decided, and nothing else. The queue takes over through the
 * `ensurance_dashboard_decision_recorded` action and the
 * `ensurance_dashboard_priority_state` filter, at which point this can go.
 */
if ( ! defined( 'ENSURANCE_DASHBOARD_DECISION_META' ) ) {
    define( 'ENSURANCE_DASHBOARD_DECISION_META', '_ensurance_dashboard_decision' );
}

/**
 * The decision an agent has just made, or '' when they have not made one.
 *
 * Read by ensurance_dashboard_decided_slot() to put the slot in `decided`, and
 * by components/dashboard-slot-decided.php to say which way it went.
 *
 * TWO SOURCES, and they do not mix. A decision made on a PREVIEWED request
 * (`/dashboard/?slot=live` — see ensurance_dashboard_priority_preview) rides
 * back in the URL as `?decision=accept`, exactly like the slot toggle it arrived
 * with: the request was a sample, so the decision about it is display state and
 * never touches the user record. A decision made on a REAL request is read from
 * user meta, where the handler recorded it.
 *
 * @param int $user_id Optional. Defaults to the current user.
 * @return string 'accept', 'pass', or '' when nothing has been decided.
 */
function ensurance_dashboard_decision( $user_id = 0 ) {
    $decisions = ensurance_dashboard_decisions();

    // Display-only preview state, same as `?slot=` — no side effects, so no
    // nonce to verify. The capability check lives in the preview function, so a
    // `?decision=` with no preview in effect is ignored here.
    if ( '' !== ensurance_dashboard_priority_preview() && ! empty( $_GET['decision'] ) ) {
        $previewed = sanitize_key( wp_unslash( $_GET['decision'] ) );

        return in_array( $previewed, $decisions, true ) ? $previewed : '';
    }

    $user_id  = $user_id ? (int) $user_id : get_current_user_id();
    $decision = (string) get_user_meta( $user_id, ENSURANCE_DASHBOARD_DECISION_META, true );

    return in_array( $decision, $decisions, true ) ? $decision : '';
}

/**
 * Record a decision against a real request.
 *
 * The seam the matching queue attaches to: everything that should happen when an
 * agent accepts or passes — releasing the shopper's contact details, telling the
 * shopper, handing the request to the next agent in that county, writing the
 * history row Step 12's Requests view lists — hangs off the action below. This
 * function itself only remembers the decision well enough for the slot to show
 * it (see ENSURANCE_DASHBOARD_DECISION_META).
 *
 * Not called for previewed requests. See ensurance_dashboard_decision().
 *
 * @param string $decision One of ensurance_dashboard_decisions().
 * @param int    $user_id  Optional. Defaults to the current user.
 * @return bool Whether the decision was valid and recorded.
 */
function ensurance_dashboard_record_decision( $decision, $user_id = 0 ) {
    $decision = sanitize_key( $decision );
    $user_id  = $user_id ? (int) $user_id : get_current_user_id();

    if ( ! $user_id || ! in_array( $decision, ensurance_dashboard_decisions(), true ) ) {
        return false;
    }

    update_user_meta( $user_id, ENSURANCE_DASHBOARD_DECISION_META, $decision );

    /**
     * Fires when an agent accepts or passes the request in Today's slot.
     *
     * @param string $decision 'accept' or 'pass'.
     * @param int    $user_id  Agent who decided.
     */
    do_action( 'ensurance_dashboard_decision_recorded', $decision, $user_id );

    return true;
}

/**
 * Puts the priority slot in `decided` once the agent has decided.
 *
 * The other half of "both buttons set the slot to `decided`" — the buttons post,
 * this is what the slot resolves to afterwards. Hooked onto the resolver's own
 * filter rather than written into ensurance_dashboard_priority_state(), so the
 * decision is one more input to that single value and not a second thing driving
 * the slot.
 *
 * It wins over `live`: a request the agent has already answered is not still
 * waiting on them. The decided panel's Undo is what clears the decision and
 * hands the slot back — see ensurance_dashboard_clear_decision().
 *
 * @param string $state   State resolved so far.
 * @param int    $user_id User the slot is being resolved for.
 * @return string
 */
function ensurance_dashboard_decided_slot( $state, $user_id ) {
    return ( '' !== ensurance_dashboard_decision( $user_id ) ) ? 'decided' : $state;
}
add_filter( 'ensurance_dashboard_priority_state', 'ensurance_dashboard_decided_slot', 10, 2 );

/**
 * Handles the Accept / Pass post from Today's live request card.
 *
 * Step 6 of templates/agent-dashboard/build-steps.md. The design's buttons flip a
 * component's state; there is no component here, so the decision is an ordinary
 * form post — which means it works with JavaScript off, is announced as a button
 * either way, and cannot be triggered by a link a shopper or a crawler follows.
 *
 * POST-REDIRECT-GET: the handler redirects rather than rendering, so the decided
 * slot lands on a clean URL and a refresh cannot decide the same request twice.
 *
 * WHERE THE DECISION GOES depends on where the request came from, and the rule is
 * that they match: a previewed request (`?slot=live`) produces a previewed
 * decision, carried in the URL and written nowhere, because the request it is
 * about was a sample. A real request — one the `ensurance_dashboard_live_request`
 * filter supplied — is recorded (ensurance_dashboard_record_decision).
 *
 * A failed or expired nonce returns WITHOUT deciding and WITHOUT redirecting:
 * the card renders again, still awaiting a decision, which is the truth. That is
 * better than WordPress's "link expired" interstitial for a control an agent
 * pressed deliberately.
 */
function ensurance_dashboard_handle_decision() {
    // Cheapest test first — this runs on every front-end request.
    if ( ! isset( $_POST['dash_decision'] ) || ! is_page( 'dashboard' ) || ! is_user_logged_in() ) {
        return;
    }

    $decision = sanitize_key( wp_unslash( $_POST['dash_decision'] ) );

    if ( ! in_array( $decision, ensurance_dashboard_decisions(), true ) ) {
        return;
    }

    $nonce = isset( $_POST['dash_decide_nonce'] ) ? sanitize_text_field( wp_unslash( $_POST['dash_decide_nonce'] ) ) : '';

    if ( ! wp_verify_nonce( $nonce, 'ensurance_dashboard_decide' ) ) {
        return;
    }

    // The card's form posts to its own URL, so `?slot=live` is still readable
    // here — which is how a previewed decision is told from a real one.
    if ( 'live' === ensurance_dashboard_priority_preview() ) {
        $target = add_query_arg(
            array(
                'slot'     => 'decided',
                'decision' => $decision,
            ),
            home_url( '/dashboard/' )
        );
    } else {
        ensurance_dashboard_record_decision( $decision );
        $target = home_url( '/dashboard/' );
    }

    wp_safe_redirect( $target );
    exit;
}
add_action( 'template_redirect', 'ensurance_dashboard_handle_decision' );

/**
 * Where the live card's Accept / Pass form posts (raw — esc_url at output).
 *
 * Its own URL, preview arg and all: the handler reads `?slot=live` back off the
 * post to tell a previewed decision from a real one. Nothing else is carried —
 * `?view=` is not, because Today is where the card is and where the decision
 * lands.
 *
 * @return string
 */
function ensurance_dashboard_decision_action() {
    $action = home_url( '/dashboard/' );

    if ( 'live' === ensurance_dashboard_priority_preview() ) {
        $action = add_query_arg( 'slot', 'live', $action );
    }

    return $action;
}

/**
 * Where an accepted request's contact details are sent.
 *
 * Step 7 of templates/agent-dashboard/build-steps.md: the accepted panel names
 * this address, because "we sent you the shopper's name, phone and email" is
 * only useful if the agent knows WHICH inbox to go and look in.
 *
 * The account's own email today — the address the agent signs in with is the
 * only one the product actually has. The design calls this the agency's
 * "request inbox" and shows it separately from sign-in (Steps 11 and 13), so
 * when that field exists it attaches here and every surface naming the inbox
 * follows.
 *
 * @param int $user_id Optional. Defaults to the current user.
 * @return string An email address, or '' when there is none to name.
 */
function ensurance_dashboard_request_inbox( $user_id = 0 ) {
    $user_id = $user_id ? (int) $user_id : get_current_user_id();
    $user    = $user_id ? get_userdata( $user_id ) : false;
    $inbox   = ( $user && ! empty( $user->user_email ) ) ? $user->user_email : '';

    /**
     * Filter the inbox an accepted request's contact details are sent to.
     *
     * @param string $inbox   Email address, '' when unknown.
     * @param int    $user_id User the dashboard is being rendered for.
     */
    $inbox = (string) apply_filters( 'ensurance_dashboard_request_inbox', $inbox, $user_id );

    return is_email( $inbox ) ? $inbox : '';
}

/**
 * The county a just-passed request moved on to.
 *
 * Step 7 needs exactly one field of the decided request — the county, so the
 * passed panel can say which county the request went back out to rather than
 * gesturing at "another agent" with no place attached. Everything else about
 * the request is gone from the panel by design: the fact tiles do not reappear.
 *
 * A previewed decision is about the sample request, so it reports that request's
 * county (ensurance_dashboard_sample_request). A real one has nowhere to read it
 * from yet — the interim store keeps the decision and nothing else (see
 * ENSURANCE_DASHBOARD_DECISION_META) — so this returns '' and the panel falls
 * back to a sentence that names no county. The queue supplies it through the
 * filter when it exists.
 *
 * @param int $user_id Optional. Defaults to the current user.
 * @return string County name, or '' when it is not known.
 */
function ensurance_dashboard_decided_county( $user_id = 0 ) {
    $user_id = $user_id ? (int) $user_id : get_current_user_id();
    $sample  = ensurance_dashboard_sample_request();
    $county  = ( 'decided' === ensurance_dashboard_priority_preview() ) ? $sample['county'] : '';

    /**
     * Filter the county named in the passed-request confirmation.
     *
     * @param string $county  County name, '' when unknown.
     * @param int    $user_id User the slot is being resolved for.
     */
    return (string) apply_filters( 'ensurance_dashboard_decided_county', $county, $user_id );
}

/**
 * What the decided panel says — its headline and its one sentence.
 *
 * Step 7 of templates/agent-dashboard/build-steps.md. The panel confirms a
 * decision the agent has just made, so both halves of it are about what
 * ALREADY HAPPENED, not about what they should do next: accepting names the
 * inbox the contact details went to and what the shopper was told to expect;
 * passing says the request left the queue and went to another agent in that
 * county. Neither line asks for anything, and neither hedges — the decision is
 * done, and Undo is right there for the one case where it was a misclick.
 *
 * Copy is the design's own (`decidedTitle` / `decidedBody` in
 * templates/agent-dashboard/AgentDashboard.dc.html), with its hardcoded inbox
 * and county replaced by the resolvers above. Each has a fallback that simply
 * drops the detail rather than printing a blank or a placeholder, because a
 * sentence that names no inbox is still true and "sent to ." is not.
 *
 * @param string $decision One of ensurance_dashboard_decisions().
 * @param int    $user_id  Optional. Defaults to the current user.
 * @return array{title:string,body:string} Empty strings for an unknown decision.
 */
function ensurance_dashboard_decided_panel( $decision, $user_id = 0 ) {
    $decision = sanitize_key( $decision );
    $user_id  = $user_id ? (int) $user_id : get_current_user_id();
    $panel    = array(
        'title' => '',
        'body'  => '',
    );

    if ( 'accept' === $decision ) {
        $inbox = ensurance_dashboard_request_inbox( $user_id );

        $panel['title'] = 'Request accepted';
        $panel['body']  = ( '' !== $inbox )
            ? sprintf( 'Contact details were sent to %s. The shopper was told to expect you within one business day.', $inbox )
            : 'Contact details were sent to your request inbox. The shopper was told to expect you within one business day.';
    } elseif ( 'pass' === $decision ) {
        $county = ensurance_dashboard_decided_county( $user_id );

        $panel['title'] = 'Request passed';
        $panel['body']  = ( '' !== $county )
            ? sprintf( 'It has been removed from your queue and offered to another agent in %s.', $county )
            : 'It has been removed from your queue and offered to another agent covering that area.';
    }

    /**
     * Filter the decided panel's headline and sentence.
     *
     * @param array  $panel    ['title' => …, 'body' => …].
     * @param string $decision 'accept' or 'pass'.
     * @param int    $user_id  User the panel is being rendered for.
     */
    return (array) apply_filters( 'ensurance_dashboard_decided_panel', $panel, $decision, $user_id );
}

/**
 * Undo a recorded decision — the other half of the panel's one control.
 *
 * Forgets the decision, which is all it takes to hand the slot back: the state
 * resolver reads that value (ensurance_dashboard_decided_slot), so with nothing
 * recorded Today returns to whatever it was before — `live` while the request is
 * still waiting.
 *
 * Fires its own action rather than reusing the recorded one with an "undone"
 * flag, so the queue can unwind an accept (re-lock the contact details, tell the
 * shopper nothing) without having to tell two meanings of one hook apart.
 *
 * @param int $user_id Optional. Defaults to the current user.
 * @return bool Whether a decision was there to undo.
 */
function ensurance_dashboard_clear_decision( $user_id = 0 ) {
    $user_id  = $user_id ? (int) $user_id : get_current_user_id();
    $decision = $user_id ? ensurance_dashboard_decision( $user_id ) : '';

    if ( '' === $decision ) {
        return false;
    }

    delete_user_meta( $user_id, ENSURANCE_DASHBOARD_DECISION_META );

    /**
     * Fires when an agent undoes the decision they just made.
     *
     * @param string $decision Decision that was undone — 'accept' or 'pass'.
     * @param int    $user_id  Agent who undid it.
     */
    do_action( 'ensurance_dashboard_decision_undone', $decision, $user_id );

    return true;
}

/**
 * Handles the Undo post from the decided panel.
 *
 * The mirror of ensurance_dashboard_handle_decision(), and deliberately built
 * the same way: a form post rather than a link, so undoing works with
 * JavaScript off, is announced as a button, and cannot be triggered by anything
 * following a URL. It redirects rather than rendering, so the slot lands back on
 * a clean address.
 *
 * A previewed decision was never written down, so undoing it only drops the
 * `?decision=` from the URL and puts the preview back on `?slot=live` — the
 * state the previewed card was decided from.
 *
 * A failed nonce returns without undoing and without redirecting: the panel
 * renders again, still decided, which is the truth.
 */
function ensurance_dashboard_handle_undo() {
    // Cheapest test first — this runs on every front-end request.
    if ( ! isset( $_POST['dash_undo'] ) || ! is_page( 'dashboard' ) || ! is_user_logged_in() ) {
        return;
    }

    $nonce = isset( $_POST['dash_undo_nonce'] ) ? sanitize_text_field( wp_unslash( $_POST['dash_undo_nonce'] ) ) : '';

    if ( ! wp_verify_nonce( $nonce, 'ensurance_dashboard_undo' ) ) {
        return;
    }

    // The panel's form posts to its own URL, so the preview args are still
    // readable here — which is how a previewed decision is told from a real one.
    if ( 'decided' === ensurance_dashboard_priority_preview() ) {
        $target = add_query_arg( 'slot', 'live', home_url( '/dashboard/' ) );
    } else {
        ensurance_dashboard_clear_decision();
        $target = home_url( '/dashboard/' );
    }

    wp_safe_redirect( $target );
    exit;
}
add_action( 'template_redirect', 'ensurance_dashboard_handle_undo' );

/**
 * Where the decided panel's Undo form posts (raw — esc_url at output).
 *
 * Its own URL, preview args and all, for the same reason
 * ensurance_dashboard_decision_action() carries them: the handler reads them
 * back off the post to tell a previewed decision from a recorded one.
 *
 * @return string
 */
function ensurance_dashboard_undo_action() {
    $action = home_url( '/dashboard/' );

    if ( 'decided' !== ensurance_dashboard_priority_preview() ) {
        return $action;
    }

    return add_query_arg(
        array(
            'slot'     => 'decided',
            'decision' => ensurance_dashboard_decision(),
        ),
        $action
    );
}

// ============================================================================
// 2b-v-a4. FOUNDING AGENT PLAN SELECTION — SIGN-UP FUNNEL MEMORY
// ============================================================================
// Single source of truth for the two Founding Agent plans an agent can pick.
//
// FUNNEL: the "Start 60 Day Access" / "Join as a Founding Agent" CTAs on /login
// and /pricing-plans link to /create-account?plan=<slug> (see
// ensurance_create_account_url). page-create-account.php reads the slug,
// preserves it through a failed-submit re-render via a hidden `plan` field, and
// sets the register form's redirect_to to that plan's destination. On a
// successful registration the slug is ALSO saved to user meta
// (ensurance_remember_founding_plan), so the choice is remembered on the user
// record even when there is no immediate redirect.
//
// IMMEDIATE POST-SIGNUP REDIRECT: only fires when UsersWP's registration action
// is 'auto_approve_login' (it auto-logs-in then honors redirect_to). The site is
// currently 'auto_approve' (user created, NOT logged in, shown a "please log in"
// notice), so today the redirect_to is set but not used — the durable user-meta
// copy is what a later step should rely on.
//
// FUTURE ITERATION (Stripe): route each plan to its Stripe checkout by changing
// ONLY the 'destination' values below, or by filtering
// 'ensurance_founding_plan_destination'. Read the remembered plan on the Stripe
// page with ensurance_get_remembered_founding_plan( $user_id ). Nothing else in
// the funnel has to change.

/** User-meta key holding a new agent's chosen Founding Agent plan slug. */
if ( ! defined( 'ENSURANCE_FOUNDING_PLAN_META' ) ) {
    define( 'ENSURANCE_FOUNDING_PLAN_META', '_ensurance_founding_plan' );
}

/**
 * The Founding Agent plan registry. Keyed by URL slug.
 * 'destination' is where a new agent lands AFTER the account is created —
 * interim GeoDirectory checkout today, Stripe checkout in the future iteration.
 *
 * @return array<string,array>
 */
function ensurance_founding_plans() {
    return array(
        '60-day'  => array(
            'label'       => 'Start 60 Day Access',
            'package_id'  => 14,
            // Free path: no payment, no listing form. Straight to the agent
            // dashboard (agents do not self-manage profiles — see
            // plans/agent-onboarding-1-free-agent.md). The immediate redirect
            // only fires under the 'auto_approve_login' registration action;
            // until then the durable user-meta copy carries the plan.
            'destination' => home_url( '/dashboard/' ),
        ),
        'monthly' => array(
            'label'       => 'Join as a Founding Agent',
            'package_id'  => 16,
            'destination' => home_url( '/publish-your-agency/insurance-agencies/?package_id=16' ),
        ),
    );
}

/**
 * Normalize a plan slug to a known one, or '' if unknown/absent. Safe to call on
 * raw request input — sanitizes and whitelists.
 */
function ensurance_founding_plan_valid( $slug ) {
    $slug  = is_string( $slug ) ? sanitize_key( $slug ) : '';
    $plans = ensurance_founding_plans();
    return isset( $plans[ $slug ] ) ? $slug : '';
}

/** Build the /create-account URL carrying a plan selection (raw — esc_url at output). */
function ensurance_create_account_url( $slug ) {
    $slug = ensurance_founding_plan_valid( $slug );
    $url  = home_url( '/create-account/' );
    return $slug ? add_query_arg( 'plan', $slug, $url ) : $url;
}

/**
 * Contact URL for the paid "Join as a Founding Agent" path (raw — esc_url at output).
 *
 * The monthly ($29/mo) plan is intentionally a MANUAL process: rather than a
 * self-serve create-account → Stripe checkout, prospective founding agents
 * contact the team, who set up the agency profile, bio and membership by hand
 * (see memory agents-cannot-manage-own-profiles). So the monthly CTAs point here,
 * not at ensurance_create_account_url('monthly'). The `topic` param preselects the
 * "Founding Agent membership" option on the contact form so these inquiries are
 * tagged for routing (page-contact.php). The `monthly` funnel plumbing
 * (registry entry, create-account handling) is left in place but dormant.
 */
function ensurance_founding_agent_contact_url() {
    return add_query_arg( 'topic', 'founding', home_url( '/contact/' ) );
}

/**
 * Founding Agent CTA destination with a logged-in short-circuit (raw — esc_url at output).
 *
 * Every "Start 60 Day Access" / "Join as a Founding Agent" button should send an
 * already-authenticated agent straight to their dashboard rather than back
 * through sign-up (ensurance_create_account_url) or the contact form
 * (ensurance_founding_agent_contact_url) — they have already joined. For a
 * logged-out visitor it defers to the normal per-CTA destination the caller
 * built and passed in. Wrap each CTA's URL at the call site:
 *
 *   ensurance_founding_cta_url( ensurance_create_account_url( '60-day' ) )
 *   ensurance_founding_cta_url( ensurance_founding_agent_contact_url() )
 *
 * Added rather than folded into the two builders above so those keep their
 * single responsibility and the login-aware behavior is opt-in per CTA.
 *
 * @param string $logged_out_url Raw URL to use when the visitor is not logged in.
 * @return string Raw URL.
 */
function ensurance_founding_cta_url( $logged_out_url ) {
    if ( is_user_logged_in() ) {
        return home_url( '/dashboard/' );
    }
    return $logged_out_url;
}

/**
 * Post-account-creation destination for a plan (interim checkout today, Stripe
 * later). Filterable so the future Stripe URLs can be swapped in without editing
 * the registry.
 */
function ensurance_founding_plan_destination( $slug ) {
    $slug  = ensurance_founding_plan_valid( $slug );
    $plans = ensurance_founding_plans();
    $dest  = $slug ? $plans[ $slug ]['destination'] : home_url( '/' );
    return apply_filters( 'ensurance_founding_plan_destination', $dest, $slug );
}

/** Read the plan a user chose at sign-up ('' if none). For the future Stripe step. */
function ensurance_get_remembered_founding_plan( $user_id ) {
    return ensurance_founding_plan_valid( get_user_meta( (int) $user_id, ENSURANCE_FOUNDING_PLAN_META, true ) );
}

/**
 * Durably remember the chosen plan on the user at registration. Hooked on
 * uwp_after_custom_fields_save, which fires after the user row is created and
 * still has the raw submitted $data (before UsersWP clears $_POST) — and in
 * EVERY registration action path, so the choice survives regardless of whether
 * the immediate redirect fires.
 *
 * @param string $form_type 'register' | 'account' | ...
 * @param array  $data      raw submitted fields (includes our `plan`)
 * @param array  $result    validated fields
 * @param int    $user_id   the new user's id
 */
function ensurance_remember_founding_plan( $form_type, $data, $result, $user_id ) {
    if ( 'register' !== $form_type || empty( $user_id ) ) {
        return;
    }
    $slug = ensurance_founding_plan_valid( isset( $data['plan'] ) ? $data['plan'] : '' );
    if ( $slug ) {
        update_user_meta( $user_id, ENSURANCE_FOUNDING_PLAN_META, $slug );
    }
}
add_action( 'uwp_after_custom_fields_save', 'ensurance_remember_founding_plan', 10, 4 );

/**
 * Route a Founding Agent to their plan's destination on login.
 *
 * WHY THIS EXISTS: the sign-up funnel sets the REGISTRATION form's redirect_to to
 * the plan destination (/dashboard/), but UsersWP only honors that when the
 * registration action is 'auto_approve_login' (immediate login). The default
 * registration form (id 1) is 'require_email_activation', so registration and
 * login are split across an email round-trip: the agent activates by email, is
 * sent to /login, and logs in there — a step that has no knowledge of the plan
 * and otherwise lands on the account page. Because the chosen plan is durably
 * saved on the user at registration (ensurance_remember_founding_plan →
 * _ensurance_founding_plan), we can re-derive the destination at login.
 *
 * Hooked on 'uwp_login_redirect', which runs LAST in UsersWP's
 * get_login_redirect_url() — after page-login.php's hardcoded account-page
 * redirect_to — so returning here overrides it, but only for users who actually
 * carry a plan. Everyone else (shoppers, staff) keeps the default. The
 * destination itself stays filterable via ensurance_founding_plan_destination().
 *
 * @param string  $redirect_to     the destination UsersWP resolved so far
 * @param mixed   $redirect_page_id unused
 * @param array   $data            submitted login fields
 * @param WP_User $user            the user who just logged in
 * @return string
 */
function ensurance_founding_plan_login_redirect( $redirect_to, $redirect_page_id, $data, $user ) {
    if ( ! ( $user instanceof WP_User ) || empty( $user->ID ) ) {
        return $redirect_to;
    }
    $slug = ensurance_get_remembered_founding_plan( $user->ID );
    if ( $slug ) {
        return ensurance_founding_plan_destination( $slug );
    }
    return $redirect_to;
}
add_filter( 'uwp_login_redirect', 'ensurance_founding_plan_login_redirect', 10, 4 );

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
        ''         => 'A general question',
        'request'  => 'About a request I started',
        'agent'    => "I'm an agent or agency",
        'founding' => 'Founding Agent membership',
        'press'    => 'Press or media',
        'privacy'  => 'A privacy request',
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
