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
 *                              container — which is Account alone as of Step 13
 *                              of templates/agent-dashboard/build-steps.md,
 *                              and nothing once Step 14 lands.
 *   eyebrow  string  optional  Kicker above the title. Empty by default: the
 *                              current design has no per-view eyebrow.
 *   intro    string  optional  Lead paragraph.
 *   href     string  optional  Destination. Defaults to `?view=<view>` on
 *                              /dashboard/.
 *   modifier string  optional  Extra class on the container.
 *   part     string  optional  Template part rendered INSIDE the container,
 *                              AFTER the generic eyebrow/title/intro — the
 *                              escape hatch for views whose real content is a
 *                              card grid, status rows, an accordion and so on.
 *                              The two compose: a view that sets a title and a
 *                              part gets the shared header and then its own
 *                              markup (Requests), and one that sets only a
 *                              part renders the part alone (Today, whose <h1>
 *                              is the greeting). Ignored if the file does not
 *                              exist, so a view can be listed before it is
 *                              built.
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
            // The design's own title and scope line. Both go through the shared
            // view header, and the part below adds only the table — the header
            // is the same object on all three of the non-Today views, so it is
            // described once here rather than rebuilt in each part.
            'title' => 'Requests',
            'intro' => 'Every request matched to your service areas since access started.',
            'part'  => 'components/dashboard-view-requests',
        ),
        array(
            'view'  => 'profile',
            'label' => 'Agency Profile',
            // Icon `user`.
            'icon'  => '<svg viewBox="0 0 24 24" width="17" height="17" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true" focusable="false"><circle cx="12" cy="8" r="5"/><path d="M20 21a8 8 0 0 0-16 0"/></svg>',
            // The design's own title and intro, through the shared view header.
            // The intro is the whole reason this view is not a form: it says
            // what these fields DO (decide which requests reach the agent) and
            // who changes them (support), which is Step 13's first requirement
            // and the scope note at the top of build-steps.md restated for the
            // one view an agent would otherwise expect to edit.
            'title' => 'Agency Profile',
            'intro' => 'These fields decide which requests reach you. To change anything here, message agent support and we will update it for you.',
            'part'  => 'components/dashboard-view-profile',
        ),
        array(
            'view'  => 'account',
            'label' => 'Account',
            // Icon `lock`.
            'icon'  => '<svg viewBox="0 0 24 24" width="17" height="17" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true" focusable="false"><rect x="3" y="11" width="18" height="11" rx="2"/><path d="M7 11V7a5 5 0 0 1 10 0v4"/></svg>',
            // The design's own title and intro, through the shared view header.
            // The intro is what makes the rows below legible as rows rather than
            // as settings with their controls missing: nothing here is self-serve
            // YET, and support can do all of it today. It is also the promise the
            // support row at the bottom has to keep, which is why that row is the
            // one action on the view.
            'title' => 'Account',
            'intro' => 'Self-serve account changes are coming soon. Agent support can update any of this for you today.',
            'part'  => 'components/dashboard-view-account',
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
 * The agency's OWN name — what the business is called on the record.
 *
 * The design (templates/agent-dashboard/AgentDashboard.dc.html) takes this from
 * an `agencyName` prop — "Coastline Insurance Group". This resolver itself holds
 * nothing: it returns '' and leaves the record to its filter, which is where the
 * company name now attaches (ensurance_dashboard_recorded_agency_name, Step 6 of
 * the setup flow — the sign-up `company` field plus the Agency Profile's own name
 * field, both in ENSURANCE_COMPANY_META). An agency with no name on file still
 * resolves to '', and the surfaces above decide what to do about that —
 * ensurance_dashboard_agency_name() falls back to the user record so the rail has
 * something to greet, while the Agency Profile says so instead, because the
 * profile is where the agent goes to CHECK the record and the honest answer there
 * is that we do not hold one — and, since Step 6, offers them the box to fix it
 * in.
 *
 * THIS IS THE FIX FOR THE DUPLICATE. Before it existed, the profile's "Agency
 * name" chip read from ensurance_dashboard_agency_name() and so printed the
 * agent's own name straight back beside "Agent name" — one value, two labels,
 * neither of them true of the other.
 *
 * The admin preview is the one exception, gated on `?slot=quiet` exactly like the
 * license and phone, so one URL shows the whole agency record as the design draws
 * it (ensurance_dashboard_sample_agency).
 *
 * Point the filter at the real agency record when it exists — every surface that
 * names the agency reads from here, directly or through the fallback.
 *
 * @param int $user_id Optional. Defaults to the current user.
 * @return string The recorded agency name, '' when there is none.
 */
function ensurance_dashboard_agency_record_name( $user_id = 0 ) {
    $user_id = $user_id ? (int) $user_id : get_current_user_id();
    $sample  = ensurance_dashboard_sample_agency();
    $name    = ( 'quiet' === ensurance_dashboard_priority_preview() ) ? $sample['name'] : '';

    /**
     * Filter the agency's recorded name.
     *
     * @param string $name    Agency name, '' when the record has none.
     * @param int    $user_id User the agency is being resolved for.
     */
    return (string) apply_filters( 'ensurance_dashboard_agency_record_name', $name, $user_id );
}

/**
 * The name shown wherever the dashboard has to GREET the agency — the rail's user
 * card, its initials, the quiet panel's sentence, Today's "Displayed name" row.
 *
 * The recorded agency name when there is one
 * (ensurance_dashboard_agency_record_name), and otherwise the best name the user
 * record carries, in that order:
 *
 *   display_name → "First Last" → user_login
 *
 * THE FALLBACK IS FOR GREETING, NOT FOR THE RECORD. A card that says "Welcome
 * back" over a blank is worse than one that uses the agent's own name, so these
 * surfaces take it. The Agency Profile deliberately does NOT: it reads the record
 * resolver directly, so an agency we have no name for reads "Not on file" rather
 * than the agent's name wearing an agency label.
 *
 * @param int $user_id Optional. Defaults to the current user.
 * @return string Agency name, '' if there is no user (logged-out callers).
 */
function ensurance_dashboard_agency_name( $user_id = 0 ) {
    $user = $user_id ? get_userdata( (int) $user_id ) : wp_get_current_user();

    if ( ! ( $user instanceof WP_User ) || empty( $user->ID ) ) {
        return '';
    }

    $name = trim( ensurance_dashboard_agency_record_name( $user->ID ) );

    if ( '' === $name ) {
        $name = trim( (string) $user->display_name );
    }

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
 * The exception is the admin-only preview toggle, which returns the design's own
 * sample request — the way this card gets reviewed before a queue exists. An
 * agent cannot reach it: ensurance_dashboard_priority_preview() is capability-
 * gated for exactly this reason. TWO preview states qualify, not one:
 * /dashboard/?slot=live because the card is what that state renders, and
 * ?slot=decided because the request still EXISTS after it is decided — Step 12's
 * Requests view lists it with an Accepted / Passed badge (see
 * ensurance_dashboard_request_rows), and a decided preview that dropped the row
 * out of the table would be showing a confirmation about a request no history
 * contains. Today itself is unaffected: the decided state renders its own panel
 * and never reads this.
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
 *   matched_at int     Unix time the request was matched to this agent, 0 for
 *                      unknown. The card does not print it; the Requests row
 *                      for the same request stamps itself from it.
 *   detail     string  One-line summary of the request for the Requests row —
 *                      the same facts the card spreads across tiles, written as
 *                      a sentence fragment. '' when there is nothing to add.
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
        // Same treatment in the other direction: the design's Requests row
        // stamps this request "2h ago" and its card calls it "2 hours ago", and
        // both are the one moment it was matched.
        'matched_at' => time() - ( 2 * HOUR_IN_SECONDS ),
        // The Requests row's one line, from the design's own `reqRows`. It is
        // the two tiles an agent scans first, not a summary of all four — the
        // carrier and the submitted time say nothing in a list where every row
        // carries a stamp of its own.
        'detail'     => '2 drivers, 2 vehicles · ZIP 93013',
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

    // Admin preview only — see the note above for why `decided` counts too.
    if ( in_array( ensurance_dashboard_priority_preview(), array( 'live', 'decided' ), true ) ) {
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
        'matched_at' => isset( $request['matched_at'] ) ? (int) $request['matched_at'] : 0,
        'detail'     => isset( $request['detail'] ) ? (string) $request['detail'] : '',
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

/**
 * The design's own sample matching profile — the fabricated counties, coverage
 * types and match stats behind the `quiet` preview.
 *
 * The counterpart of ensurance_dashboard_sample_request(), and kept in one
 * function for the same reason: the quiet panel's sentence and its stat row are
 * about the same agency, and must not disagree about it.
 *
 * PREVIEW ONLY. Nothing reaches this except through
 * ensurance_dashboard_priority_preview(), which is capability-gated — an agent
 * can never be shown these values. Copied field for field from the `isQuiet`
 * branch of templates/agent-dashboard/AgentDashboard.dc.html, with one change:
 * the design's fixed "Matched in August" label is built from the current month
 * here, so the preview cannot read as stale in September.
 *
 * @return array{areas:string[],coverages:string[],stats:array<int,array{label:string,value:string}>}
 */
function ensurance_dashboard_sample_matching() {
    return array(
        // Bare county names — see ensurance_dashboard_service_areas().
        'areas'     => array( 'Coastal', 'Ventura', 'Santa Barbara' ),
        'coverages' => array( 'Auto', 'Home', 'Life' ),
        'stats'     => array(
            array( 'label' => 'Last match', 'value' => '2 days ago' ),
            array( 'label' => sprintf( 'Matched in %s', wp_date( 'F' ) ), 'value' => '4 requests' ),
            array( 'label' => 'Typical pace here', 'value' => '1–3 per week' ),
        ),
    );
}

/**
 * The counties an agent's requests are matched from.
 *
 * Step 8 of templates/agent-dashboard/build-steps.md: the quiet panel's one
 * sentence names them, because "matching is on" means nothing without saying
 * matching on WHAT — and the counties are half of that answer
 * (ensurance_dashboard_coverage_types is the other half).
 *
 * NAMES CARRY NO "COUNTY". The list is 'Coastal', not 'Coastal County', because
 * the surfaces that name the whole list say the word once for all of them
 * ("Coastal, Ventura, and Santa Barbara counties") — pluralized by whoever is
 * printing it. That is the opposite of ensurance_dashboard_sample_request(),
 * whose `county` names ONE county inline ("Auto coverage — Coastal County") and
 * therefore carries the word itself.
 *
 * THIS RESOLVER ITSELF HOLDS NOTHING: it returns an empty array and leaves the
 * list to its filter, which is where the agent's own states now attach
 * (ensurance_dashboard_stored_service_areas, Step 7 of the setup flow — the
 * profile's picker writes them). An agency that has set none comes back empty and
 * the sentence names no areas rather than inventing some. The admin preview is the
 * one exception, for the same reason the live card has one.
 *
 * @param int $user_id Optional. Defaults to the current user.
 * @return string[] County names without the word "County", '' entries dropped.
 */
function ensurance_dashboard_service_areas( $user_id = 0 ) {
    $user_id = $user_id ? (int) $user_id : get_current_user_id();
    $sample  = ensurance_dashboard_sample_matching();
    $areas   = ( 'quiet' === ensurance_dashboard_priority_preview() ) ? $sample['areas'] : array();

    /**
     * Filter the counties an agent is matched in.
     *
     * The hook the real agency profile attaches to when it exists.
     *
     * @param string[] $areas   County names, empty when none are set.
     * @param int      $user_id User the dashboard is being rendered for.
     */
    $areas = apply_filters( 'ensurance_dashboard_service_areas', $areas, $user_id );

    return is_array( $areas ) ? array_values( array_filter( array_map( 'strval', $areas ) ) ) : array();
}

/**
 * The coverage types an agent's requests are matched on.
 *
 * The other half of what the quiet panel's sentence names (see
 * ensurance_dashboard_service_areas, which this mirrors in every respect —
 * including having nothing to return today outside the admin preview).
 *
 * Stored as they are DISPLAYED — 'Auto', not 'auto'. The quiet sentence runs
 * them mid-sentence and lowercases them there; a badge or a label wants them as
 * written.
 *
 * @param int $user_id Optional. Defaults to the current user.
 * @return string[] Coverage type names, '' entries dropped.
 */
function ensurance_dashboard_coverage_types( $user_id = 0 ) {
    $user_id   = $user_id ? (int) $user_id : get_current_user_id();
    $sample    = ensurance_dashboard_sample_matching();
    $coverages = ( 'quiet' === ensurance_dashboard_priority_preview() ) ? $sample['coverages'] : array();

    /**
     * Filter the coverage types an agent is matched on.
     *
     * @param string[] $coverages Coverage type names, empty when none are set.
     * @param int      $user_id   User the dashboard is being rendered for.
     */
    $coverages = apply_filters( 'ensurance_dashboard_coverage_types', $coverages, $user_id );

    return is_array( $coverages ) ? array_values( array_filter( array_map( 'strval', $coverages ) ) ) : array();
}

/**
 * The quiet panel's stat row — what matching has actually done lately.
 *
 * Step 8 asks for three: last match, matched this month, typical pace. They are
 * the evidence behind the pulsing "Matching is on" — an agent with nothing
 * waiting is being asked to believe the system is working, and three numbers is
 * how the panel earns that instead of asserting it.
 *
 * NONE OF THEM EXIST YET. Matches are not recorded (the same gap behind
 * ensurance_dashboard_request_count), so this returns an empty array outside the
 * admin preview and components/dashboard-slot-quiet.php drops the whole ruled
 * row — an empty band of hairlines, or a "last match: never", would both be
 * worse than the panel simply not making the claim.
 *
 * SHAPE — a list of ['label' => …, 'value' => …] pairs, in the order shown. Any
 * pair missing either half is dropped, the same rule the live card's fact tiles
 * follow: a labeled blank is not a stat.
 *
 * @param int $user_id Optional. Defaults to the current user.
 * @return array<int,array{label:string,value:string}>
 */
function ensurance_dashboard_match_stats( $user_id = 0 ) {
    $user_id = $user_id ? (int) $user_id : get_current_user_id();
    $sample  = ensurance_dashboard_sample_matching();
    $stats   = ( 'quiet' === ensurance_dashboard_priority_preview() ) ? $sample['stats'] : array();

    /**
     * Filter the stats shown on Today's quiet panel.
     *
     * The hook the real match history attaches to when it exists.
     *
     * @param array $stats   List of ['label' => …, 'value' => …] pairs.
     * @param int   $user_id User the dashboard is being rendered for.
     */
    $stats = apply_filters( 'ensurance_dashboard_match_stats', $stats, $user_id );

    if ( ! is_array( $stats ) ) {
        return array();
    }

    $clean = array();

    foreach ( $stats as $stat ) {
        if ( empty( $stat['label'] ) || empty( $stat['value'] ) ) {
            continue;
        }

        $clean[] = array(
            'label' => (string) $stat['label'],
            'value' => (string) $stat['value'],
        );
    }

    return $clean;
}

/**
 * What the quiet panel says — its status label, headline, sentence, stats and
 * closing line.
 *
 * Step 8 of templates/agent-dashboard/build-steps.md. Nothing is waiting on the
 * agent, and the panel's whole job is to make that read as a NORMAL condition
 * rather than as a dead end: matching is on, here is exactly what it is watching
 * for, here is what it has done lately, and here is who to talk to if the volume
 * is wrong. There is deliberately no "check back later" — nothing about this
 * state asks the agent to come back, because the email does that.
 *
 * THE SENTENCE IS ASSEMBLED, NOT WRITTEN DOWN. It names the counties, the
 * coverage types and the inbox, and each of the three can be missing today (see
 * the resolvers above), so each has a fallback that drops the detail and leaves a
 * sentence that is still true — never a blank, never a placeholder. With nothing
 * known at all it degrades to "You are in the running for every request matched
 * to your service areas. Nothing is required of you until one lands — we email
 * you the moment it does."
 *
 * The closing line is PLAIN TEXT, not a link: v1's agency profile is read-only,
 * and Step 8 is explicit that no add-a-county or add-a-coverage affordance may
 * appear here. It routes volume changes to agent support, which is where every
 * "change this" path in the product ends.
 *
 * Copy is the design's own (the `isQuiet` branch of
 * templates/agent-dashboard/AgentDashboard.dc.html).
 *
 * @param int $user_id Optional. Defaults to the current user.
 * @return array{status:string,title:string,body:string,stats:array,note:string}
 */
function ensurance_dashboard_quiet_panel( $user_id = 0 ) {
    $user_id   = $user_id ? (int) $user_id : get_current_user_id();
    $areas     = ensurance_dashboard_service_areas( $user_id );
    $coverages = ensurance_dashboard_coverage_types( $user_id );
    $inbox     = ensurance_dashboard_request_inbox( $user_id );

    // WHAT is being matched. Lowercased because these run mid-sentence — the
    // design writes "every auto, home, and life request", and "every Auto,
    // Home, and Life request" reads like a form label dropped into prose.
    // wp_sprintf's %l is the list join: "a", "a and b", "a, b, and c".
    $kinds = ! empty( $coverages )
        ? sprintf( 'every %s request', wp_sprintf( '%l', array_map( 'strtolower', $coverages ) ) )
        : 'every request';

    // …and WHERE from. The word "county" is added once for the whole list, which
    // is why the names themselves do not carry it.
    $where = ! empty( $areas )
        ? sprintf( 'from %s %s', wp_sprintf( '%l', $areas ), ( 1 === count( $areas ) ) ? 'county' : 'counties' )
        : 'matched to your service areas';

    $lands = ( '' !== $inbox )
        ? sprintf( 'Nothing is required of you until one lands — we email %s the moment it does.', $inbox )
        : 'Nothing is required of you until one lands — we email you the moment it does.';

    $panel = array(
        'status' => 'Matching is on',
        'title'  => 'No request is waiting on you',
        'body'   => sprintf( 'You are in the running for %s %s. %s', $kinds, $where, $lands ),
        'stats'  => ensurance_dashboard_match_stats( $user_id ),
        'note'   => 'To widen what reaches you, message agent support and we will update your counties or coverage types.',
    );

    /**
     * Filter the quiet panel's copy.
     *
     * @param array $panel   ['status' => …, 'title' => …, 'body' => …, 'stats' => …, 'note' => …].
     * @param int   $user_id User the panel is being rendered for.
     */
    return (array) apply_filters( 'ensurance_dashboard_quiet_panel', $panel, $user_id );
}

/**
 * Where "message agent support" goes, from every surface that says it.
 *
 * Step 9 of templates/agent-dashboard/build-steps.md gives the setup card
 * exactly ONE button, and it routes to agent support rather than to a form the
 * agent fills in themselves. That is not a v1 shortcut — the scope note at the
 * top of build-steps.md makes it the rule for the whole product: agency data is
 * read-only, and every "change this" path ends at support (Steps 8, 13, 14 and
 * the Step 15 pass all restate it). So the destination lives here, once, and
 * those surfaces link to it rather than each deciding for themselves.
 *
 * IT IS THE ACCOUNT VIEW, which is what the design does — its `finishSetup`
 * switches the view to `account`, the same place the profile view's locked
 * notice sends people. That view's agent-support row (reply time, and the one
 * action on the page) is the end of the chain, and its button is the link that
 * finally reaches a human: see ensurance_dashboard_support_contact_url().
 *
 * Read off ensurance_dashboard_views() rather than written out, so the URL
 * cannot drift from the rail's own Account row. If support ever gets a real
 * destination — a help desk, a mailto, a contact form — filter it here and every
 * surface follows.
 *
 * @return string Absolute URL.
 */
function ensurance_dashboard_support_url() {
    $url = '';

    foreach ( ensurance_dashboard_views() as $view ) {
        if ( 'account' === $view['view'] ) {
            $url = (string) $view['href'];
            break;
        }
    }

    /**
     * Filter the destination behind the product's "message agent support" links.
     *
     * @param string $url Resolved support URL.
     */
    $url = (string) apply_filters( 'ensurance_dashboard_support_url', $url );

    // The registry always carries an Account row, so this is a guard rather than
    // a branch anyone should hit — /contact is the one page that reaches a human
    // without going through the dashboard at all.
    return '' !== $url ? $url : home_url( '/contact/' );
}

/**
 * The three things that have to be true before an agent can be matched.
 *
 * Step 9 of templates/agent-dashboard/build-steps.md — the checklist on the
 * setup card, and the definition of "not yet matchable" that puts the slot in
 * `setup` to begin with (see ensurance_dashboard_matchable_slot below). One
 * list, read by both, so the card can never show a blocking step the state
 * resolver disagrees about.
 *
 * IN THE DESIGN'S ORDER, which is also the order matching applies them: an
 * agency has to exist before it has service areas, and requests are filtered by
 * county before coverage type. That order is what makes "Step N of 3" mean
 * anything — N is a position in a sequence, not a count of what is missing.
 *
 * STATUS IS DERIVED, NEVER STORED. Each step has a `done` test against the
 * resolver that owns the data, and exactly one not-done step becomes `current`:
 * the FIRST one. Everything after it is `upcoming` and, per the step, not
 * actionable — the card asks for one thing at a time because support handles
 * them one at a time. A step that is done stays done wherever it sits in the
 * list; the sequence orders the work, it does not gate the record.
 *
 * WHAT IS ACTUALLY KNOWN TODAY. Service areas and coverage types have no funnel
 * capturing them (see ensurance_dashboard_service_areas), so both come back
 * empty and a founding agent is genuinely blocked on the first of them — which
 * is exactly the "Step 2 of 3" the design draws. The identity step reads as done
 * for anyone signed in: /create-account plus the manual approval that follows is
 * how a founding agency gets an account at all, so the account existing IS the
 * verification record until a real one exists to point the filter at.
 *
 * RETURN SHAPE — one entry per step, in order:
 *   key     string  Stable slug ('identity', 'areas', 'coverages').
 *   number  int     1-based position, for the "Step N of 3" eyebrow.
 *   label   string  The checklist line, phrased for the status it came back in.
 *   status  string  'done' | 'current' | 'upcoming'.
 *   title   string  Headline naming this step, used when it is the current one.
 *   body    string  One sentence on why it blocks matching.
 *
 * @param int $user_id Optional. Defaults to the current user.
 * @return array<int,array{key:string,number:int,label:string,status:string,title:string,body:string}>
 */
function ensurance_dashboard_setup_steps( $user_id = 0 ) {
    $user_id = $user_id ? (int) $user_id : get_current_user_id();

    // `label` is how the line reads while it is still outstanding; `done_label`
    // once it is not. The design writes the finished identity step as "Agency
    // name and license verified" and the outstanding one as "Service areas —
    // not set", so the phrasing genuinely differs per state rather than a single
    // string being recolored.
    $steps = array(
        array(
            'key'        => 'identity',
            'label'      => 'Agency name and license',
            'done_label' => 'Agency name and license verified',
            'title'      => 'Confirm your agency name and license',
            'body'       => 'Every founding agency is verified before matching starts. Until that is on file, nothing can reach you — message agent support and we will finish it with you.',
            'done'       => ( '' !== ensurance_dashboard_agency_name( $user_id ) ),
        ),
        array(
            'key'        => 'areas',
            'label'      => 'Service areas',
            'done_label' => 'Service areas set',
            'title'      => 'Add the counties you write in',
            // The design's own sentence, verbatim.
            'body'       => 'Requests are matched to service area first. Until this is set, nothing can reach you — message agent support and we will add them for you.',
            'done'       => ( array() !== ensurance_dashboard_service_areas( $user_id ) ),
        ),
        array(
            'key'        => 'coverages',
            'label'      => 'Coverage types',
            'done_label' => 'Coverage types set',
            // Written to the pattern of the one above — the design only draws
            // the service-areas card, but the same slot has to speak when
            // coverage types are the thing standing in the way.
            'title'      => 'Add the coverage types you write',
            'body'       => 'After service area, requests are matched on coverage type. Until this is set, nothing can reach you — message agent support and we will add them for you.',
            'done'       => ( array() !== ensurance_dashboard_coverage_types( $user_id ) ),
        ),
    );

    // The first outstanding step, and only the first: the card names one
    // blocking thing, so exactly one line can be current.
    $current = -1;

    foreach ( $steps as $i => $step ) {
        if ( ! $step['done'] ) {
            $current = $i;
            break;
        }
    }

    $resolved = array();

    foreach ( $steps as $i => $step ) {
        if ( $step['done'] ) {
            $status = 'done';
        } elseif ( $i === $current ) {
            $status = 'current';
        } else {
            $status = 'upcoming';
        }

        // "Service areas — not set". The suffix is on the current line only:
        // it is the one the card is about, and an upcoming step is not missing
        // yet — its turn has not come.
        $label = ( 'done' === $status ) ? $step['done_label'] : $step['label'];

        if ( 'current' === $status ) {
            $label .= ' — not set';
        }

        $resolved[] = array(
            'key'    => $step['key'],
            'number' => $i + 1,
            'label'  => $label,
            'status' => $status,
            'title'  => $step['title'],
            'body'   => $step['body'],
        );
    }

    /**
     * Filter the dashboard's setup checklist.
     *
     * The hook real onboarding attaches to when it exists. Entries must keep
     * the shape documented above; a `status` outside done / current / upcoming
     * is treated as outstanding by everything that reads this.
     *
     * @param array $resolved Resolved steps, in order.
     * @param int   $user_id  User the checklist is being resolved for.
     */
    return (array) apply_filters( 'ensurance_dashboard_setup_steps', $resolved, $user_id );
}

/**
 * Whether the agent can be matched at all — nothing on the setup checklist is
 * outstanding.
 *
 * The one question the `setup` state answers, asked in one place so the card and
 * the state resolver cannot disagree.
 *
 * @param int $user_id Optional. Defaults to the current user.
 * @return bool
 */
function ensurance_dashboard_matchable( $user_id = 0 ) {
    foreach ( ensurance_dashboard_setup_steps( $user_id ) as $step ) {
        if ( 'done' !== $step['status'] ) {
            return false;
        }
    }

    return true;
}

/**
 * Sends a matchable agent with nothing waiting to `quiet` instead of `setup`.
 *
 * ensurance_dashboard_priority_state() falls back to `setup` whenever no request
 * is waiting, which was right while setup was a labeled box and is now too
 * blunt: Step 9 defines `setup` as "not yet matchable", and an agent whose
 * checklist is finished is matchable — they are simply having a quiet day
 * (Step 8). Without this, a fully set-up agency would be shown a setup card with
 * nothing left to set up.
 *
 * A FILTER, not an edit to the resolver, for the same reason
 * ensurance_dashboard_decided_slot is one: the slot stays driven by a single
 * value with several inputs. Priority 9 puts it BEFORE the decided check, which
 * must keep the last word — a decision the agent just made outranks both.
 *
 * This is the branch the profile lights up, and since Step 7 of the setup flow it
 * is reachable: an agent who adds a state on Agency Profile finishes the
 * checklist, and the slot moves from `setup` to `quiet` with no change here.
 *
 * @param string $state   State resolved so far.
 * @param int    $user_id User the slot is being resolved for.
 * @return string
 */
function ensurance_dashboard_matchable_slot( $state, $user_id ) {
    return ( 'setup' === $state && ensurance_dashboard_matchable( $user_id ) ) ? 'quiet' : $state;
}
add_filter( 'ensurance_dashboard_priority_state', 'ensurance_dashboard_matchable_slot', 9, 2 );

/**
 * The states an agency writes in — the one thing standing between a new agent and
 * matching.
 *
 * Step 1 of the setup flow (templates/agent-dashboard/setup-flow/build-steps.md)
 * settled that STATES REPLACE COUNTIES: the product had modelled service areas as
 * counties everywhere, nothing ever captured one, and the flow being built asks
 * the agent for states. Rather than ship a second geography alongside the empty
 * first, the existing resolver becomes the states resolver and this is its honest
 * name.
 *
 * IT IS A NAME, NOT A SECOND SOURCE. Everything still resolves through
 * ensurance_dashboard_service_areas() and its `ensurance_dashboard_service_areas`
 * filter, so there is exactly one list and one seam to hook storage onto. What
 * this buys is that the setup flow can say "states" throughout without the county
 * vocabulary leaking into it, and that retiring the county naming later is a
 * change to this function alone.
 *
 * WHAT RESOLVES IT. Storage landed with Step 7 of the setup flow:
 * ensurance_dashboard_stored_service_areas() attaches the agent's own list to that
 * filter, so this returns what they set on the Agency Profile and comes back empty
 * only until they set it — which is exactly what keeps
 * ensurance_dashboard_can_receive_leads() false and the setup card on screen until
 * then.
 *
 * @param int $user_id Optional. Defaults to the current user.
 * @return string[] State names, empty when none are set.
 */
function ensurance_dashboard_served_states( $user_id = 0 ) {
    $user_id = $user_id ? (int) $user_id : get_current_user_id();

    return ensurance_dashboard_service_areas( $user_id );
}

/**
 * The states an agency can say it writes in — the 50 plus DC, code => name.
 *
 * THE LIST IS CLOSED, deliberately. The picker offers these and nothing else, so
 * "CA", "Calif." and "california" can never land in the record as three different
 * places: whatever eventually matches a request against a state is comparing
 * against this list or it is comparing against typos.
 *
 * Keyed by USPS code because the profile shows both halves — the code in mono
 * beside the full name on each chip — and because a code is the sane thing for
 * storage to key on later. The stored value is the NAME (see
 * ensurance_dashboard_served_states_csv), which is what the resolvers already
 * return and what reads correctly in a sentence.
 *
 * @return array<string,string> USPS code => state name, in alphabetical order.
 */
function ensurance_dashboard_us_states() {
    return array(
        'AL' => 'Alabama',        'AK' => 'Alaska',         'AZ' => 'Arizona',
        'AR' => 'Arkansas',       'CA' => 'California',     'CO' => 'Colorado',
        'CT' => 'Connecticut',    'DE' => 'Delaware',       'DC' => 'District of Columbia',
        'FL' => 'Florida',        'GA' => 'Georgia',        'HI' => 'Hawaii',
        'ID' => 'Idaho',          'IL' => 'Illinois',       'IN' => 'Indiana',
        'IA' => 'Iowa',           'KS' => 'Kansas',         'KY' => 'Kentucky',
        'LA' => 'Louisiana',      'ME' => 'Maine',          'MD' => 'Maryland',
        'MA' => 'Massachusetts',  'MI' => 'Michigan',       'MN' => 'Minnesota',
        'MS' => 'Mississippi',    'MO' => 'Missouri',       'MT' => 'Montana',
        'NE' => 'Nebraska',       'NV' => 'Nevada',         'NH' => 'New Hampshire',
        'NJ' => 'New Jersey',     'NM' => 'New Mexico',     'NY' => 'New York',
        'NC' => 'North Carolina', 'ND' => 'North Dakota',   'OH' => 'Ohio',
        'OK' => 'Oklahoma',       'OR' => 'Oregon',         'PA' => 'Pennsylvania',
        'RI' => 'Rhode Island',   'SC' => 'South Carolina', 'SD' => 'South Dakota',
        'TN' => 'Tennessee',      'TX' => 'Texas',          'UT' => 'Utah',
        'VT' => 'Vermont',        'VA' => 'Virginia',       'WA' => 'Washington',
        'WV' => 'West Virginia',  'WI' => 'Wisconsin',      'WY' => 'Wyoming',
    );
}

/**
 * The USPS code for a state name, '' when the name is not one of ours.
 *
 * Case- and space-insensitive, because the stored value is a name and a name that
 * arrives from storage with different capitalisation is still that state — but an
 * unrecognised one gets '' rather than a guess, and the chip then shows the name
 * alone instead of inventing a code for it.
 *
 * @param string $name State name.
 * @return string Two-letter code, or ''.
 */
function ensurance_dashboard_state_code( $name ) {
    $needle = strtolower( trim( (string) $name ) );

    foreach ( ensurance_dashboard_us_states() as $code => $state ) {
        if ( strtolower( $state ) === $needle ) {
            return $code;
        }
    }

    return '';
}

/**
 * The states still available to add — every state the agent has not already got.
 *
 * The design's rule, and the reason the picker cannot produce a duplicate: an
 * already-served state is not in the list to be chosen. That is a better guard
 * than validating the choice afterwards, because there is no wrong choice to make
 * and nothing to explain when one is made.
 *
 * @param int $user_id Optional. Defaults to the current user.
 * @return array<string,string> code => name, already-served states removed.
 */
function ensurance_dashboard_state_choices( $user_id = 0 ) {
    $user_id = $user_id ? (int) $user_id : get_current_user_id();

    $served = array_map( 'strtolower', ensurance_dashboard_served_states( $user_id ) );
    $choices = array();

    foreach ( ensurance_dashboard_us_states() as $code => $state ) {
        if ( ! in_array( strtolower( $state ), $served, true ) ) {
            $choices[ $code ] = $state;
        }
    }

    return $choices;
}

/**
 * The served states as ONE comma-separated line — the value storage will read.
 *
 * "California,Texas,Nevada" — the shape the record is stored in
 * (ENSURANCE_DASHBOARD_STATES_META: one meta value, no JSON and no separate
 * table). It is published into the page as a hidden input so anything reading the
 * current list reads a field rather than reconstructing it from the DOM.
 *
 * IT IS NOT WHAT SAVES. Step 7 posts an intent — "add California", "remove Texas"
 * — rather than this snapshot, so two quick changes cannot overwrite each other
 * (see ensurance_dashboard_handle_states). This stays the published value and
 * assets/dashboard.js keeps it in step with the chips.
 *
 * @param int $user_id Optional. Defaults to the current user.
 * @return string Comma-separated state names, '' when none are set.
 */
function ensurance_dashboard_served_states_csv( $user_id = 0 ) {
    $user_id = $user_id ? (int) $user_id : get_current_user_id();

    return implode( ',', ensurance_dashboard_served_states( $user_id ) );
}

/**
 * Retitles the Agency Profile view's intro now that part of it is self-serve.
 *
 * The registry's sentence sends the agent to support for every change on the view
 * ("To change anything here, message agent support and we will update it for
 * you"). That was true of the whole record and is now wrong about the one thing
 * the view exists for: states are set here, by the agent, without asking anyone.
 *
 * A FILTER, NOT AN EDIT. ensurance_dashboard_views() carries no seam of its own
 * and is an existing function, so page-dashboard.php — a template, which CLAUDE.md
 * lists as safe to edit — applies `ensurance_dashboard_view_item` to each entry on
 * its way to the header, and this hooks that.
 *
 * @param array $item One entry from ensurance_dashboard_views().
 * @return array
 */
function ensurance_dashboard_profile_view_intro( $item ) {
    if ( ! isset( $item['view'] ) || 'profile' !== $item['view'] ) {
        return $item;
    }

    $item['intro'] = 'These fields decide which requests reach you. Add the states you are licensed in — changes take effect on the next match.';

    return $item;
}
add_filter( 'ensurance_dashboard_view_item', 'ensurance_dashboard_profile_view_intro' );

/**
 * Cuts the setup checklist down to the one condition that actually gates matching.
 *
 * Step 1 of the setup flow settled the gate: an agent can receive leads when their
 * STATES ARE SET, and nothing else. Agency name and coverage types stay on the
 * Agency Profile as part of the record, and support can still fill them, but
 * neither blocks a lead any more.
 *
 * WHY THE OLD GATE HAD TO GO. ensurance_dashboard_setup_steps() requires all three
 * of agency name, service areas and coverage types, and no funnel fills any of
 * them — so no real agent could ever come back matchable. Worse, leaving coverage
 * types in would have let an agent finish the one step the flow shows them, watch
 * the card say done, and still receive nothing, blocked by a condition they were
 * never shown.
 *
 * A FILTER, NOT AN EDIT, for the two reasons this file already does it elsewhere
 * (see ensurance_dashboard_matchable_slot): CLAUDE.md's standing rule is new
 * functions rather than changes to existing ones, and it keeps the checklist a
 * single value with several inputs. Because ensurance_dashboard_matchable() is a
 * reduction over this same filtered list, the old boolean follows the new gate on
 * its own — there is no second definition of "matchable" to keep in step.
 *
 * DONE-NESS IS READ, NOT RECOMPUTED. The kept step's status comes off the step
 * ensurance_dashboard_setup_steps() already resolved, so the test for "are there
 * states" lives in exactly one place. Only the copy is restated, because the
 * original names counties and this flow asks for states.
 *
 * @param array $steps   Resolved steps, in order.
 * @param int   $user_id User the checklist is being resolved for.
 * @return array The states step alone, or $steps untouched if it is not present.
 */
function ensurance_dashboard_states_only_checklist( $steps, $user_id ) {
    foreach ( (array) $steps as $step ) {
        if ( ! isset( $step['key'] ) || 'areas' !== $step['key'] ) {
            continue;
        }

        $done = ( isset( $step['status'] ) && 'done' === $step['status'] );

        return array(
            array(
                'key'    => 'areas',
                // The only step left, so it is always the first one.
                'number' => 1,
                // `name` is this flow's addition to the documented shape: the bare
                // noun, for ensurance_dashboard_can_receive_leads()'s missing list,
                // where "States — not set" would read as its own sentence.
                'name'   => 'States',
                'label'  => $done ? 'States set' : 'States — not set',
                // With one condition left there is no "upcoming": it is either
                // done or it is the thing being asked for.
                'status' => $done ? 'done' : 'current',
                'title'  => 'Add your states before requests can reach you',
                // One sentence, and it has to carry both halves: matching is off
                // until the states are on the profile, AND nothing is being sent
                // meanwhile. Without the second half an agent can reasonably read
                // the first as "requests are queuing up somewhere".
                'body'   => 'Matching stays off until your states are on your agency profile, and nothing is sent to you in the meantime.',
            ),
        );
    }

    // No states step to keep means someone has already filtered the checklist into
    // a different shape; leave it alone rather than blanking it.
    return $steps;
}
add_filter( 'ensurance_dashboard_setup_steps', 'ensurance_dashboard_states_only_checklist', 10, 2 );

/**
 * Whether this agent can receive leads, and what is missing if they cannot.
 *
 * Step 2 of the setup flow (templates/agent-dashboard/setup-flow/build-steps.md).
 * THE one derived value the whole flow reads — the setup card, the Today slot, the
 * Agency Profile and anything added later all ask this rather than re-deriving the
 * condition. The gate is states-only (see
 * ensurance_dashboard_states_only_checklist), and the point of naming it once is
 * that changing the gate again is a change here and nowhere else.
 *
 * READ-ONLY. It resolves, and that is all: no writes, no redirects, no fetching.
 * Callers decide what to do about a false — this never decides for them.
 *
 * `can` DELEGATES rather than re-testing. ensurance_dashboard_matchable() is
 * already the app's boolean for this, so it stays the single answer and this
 * function adds only the list beside it. The two therefore cannot disagree: both
 * reduce over ensurance_dashboard_setup_steps().
 *
 * RETURN SHAPE:
 *   can      bool   True when nothing is outstanding.
 *   missing  array  One entry per outstanding item, in checklist order:
 *                     key    string  Stable slug ('areas').
 *                     name   string  The bare noun ('States').
 *                     title  string  Headline asking for it.
 *                     body   string  One sentence on why it blocks matching.
 *                   Empty exactly when `can` is true.
 *
 * @param int $user_id Optional. Defaults to the current user.
 * @return array{can:bool,missing:array<int,array{key:string,name:string,title:string,body:string}>}
 */
function ensurance_dashboard_can_receive_leads( $user_id = 0 ) {
    $user_id = $user_id ? (int) $user_id : get_current_user_id();
    $missing = array();

    foreach ( ensurance_dashboard_setup_steps( $user_id ) as $step ) {
        if ( isset( $step['status'] ) && 'done' === $step['status'] ) {
            continue;
        }

        $missing[] = array(
            'key'   => isset( $step['key'] ) ? (string) $step['key'] : '',
            // Falls back to the checklist label for a step added by some other
            // filter, which has no reason to know about `name`.
            'name'  => isset( $step['name'] ) ? (string) $step['name'] : ( isset( $step['label'] ) ? (string) $step['label'] : '' ),
            'title' => isset( $step['title'] ) ? (string) $step['title'] : '',
            'body'  => isset( $step['body'] ) ? (string) $step['body'] : '',
        );
    }

    $eligibility = array(
        'can'     => ensurance_dashboard_matchable( $user_id ),
        'missing' => $missing,
    );

    /**
     * Filter whether an agent can receive leads.
     *
     * The seam for a real eligibility record (a suspended agency, a lapsed
     * subscription) to veto matching without touching the setup checklist. Keep
     * `missing` empty whenever `can` is true — every consumer treats the two as
     * describing one state.
     *
     * @param array $eligibility ['can' => bool, 'missing' => array].
     * @param int   $user_id     User eligibility is being resolved for.
     */
    return (array) apply_filters( 'ensurance_dashboard_can_receive_leads', $eligibility, $user_id );
}

/**
 * The Agency Profile view's URL — where an agent goes to set their states.
 *
 * Resolved from the rail registry rather than written down, exactly the way
 * ensurance_dashboard_support_url() resolves Account: the registry already owns
 * every view's href (see ensurance_dashboard_views), so a link built here cannot
 * drift from the row the agent would otherwise click.
 *
 * @return string Absolute URL to the Agency Profile view.
 */
function ensurance_dashboard_profile_url() {
    $url = '';

    foreach ( ensurance_dashboard_views() as $view ) {
        if ( 'profile' === $view['view'] ) {
            $url = (string) $view['href'];
            break;
        }
    }

    /**
     * Filter the destination behind the product's "set up your agency" links.
     *
     * @param string $url Resolved Agency Profile URL.
     */
    $url = (string) apply_filters( 'ensurance_dashboard_profile_url', $url );

    // The registry always carries a Profile row, so this is a guard rather than a
    // branch anyone should hit.
    return '' !== $url ? $url : add_query_arg( 'view', 'profile', home_url( '/dashboard/' ) );
}

/**
 * The two tiles under the setup card's checklist.
 *
 * Step 3 of the setup flow (templates/agent-dashboard/setup-flow/build-steps.md).
 * They replace the card's single "message agent support" button, which was the
 * right control while every agency field was changed by a human and is now the
 * one thing on the card pointing away from the only door: states are entered on
 * Agency Profile and nowhere else.
 *
 * TWO, NOT ONE, because there are two things worth checking before matching turns
 * on. The first is the blocking one — the states. The second is where a matched
 * request would actually land, which is not blocking and is exactly the thing an
 * agent discovers too late: matching switches on, a request is sent, and it goes
 * to an address nobody reads.
 *
 * NEITHER IS A SUBMIT. Both are ordinary navigation to a view that already exists
 * (see ensurance_dashboard_profile_url / ensurance_dashboard_support_url, which
 * both resolve out of the rail registry) — the agent chooses to go, and nothing
 * about this card writes, redirects or traps.
 *
 * RETURN SHAPE — one entry per tile, in the order shown:
 *   key    string  Stable slug ('profile', 'account').
 *   title  string  The tile's line.
 *   sub    string  One line under it saying what is there.
 *   url    string  Where it goes.
 *
 * @param int $user_id Optional. Defaults to the current user.
 * @return array<int,array{key:string,title:string,sub:string,url:string}>
 */
function ensurance_dashboard_setup_tiles( $user_id = 0 ) {
    $user_id = $user_id ? (int) $user_id : get_current_user_id();

    $tiles = array(
        array(
            'key'   => 'profile',
            'title' => 'Review your agency profile',
            'sub'   => 'Add the states you write in',
            'url'   => ensurance_dashboard_profile_url(),
        ),
        array(
            'key'   => 'account',
            'title' => 'Confirm where requests are emailed',
            'sub'   => 'Check the inbox matched requests are sent to',
            'url'   => ensurance_dashboard_support_url(),
        ),
    );

    /**
     * Filter the setup card's tiles.
     *
     * @param array $tiles   Tiles in display order.
     * @param int   $user_id User the card is being rendered for.
     */
    return (array) apply_filters( 'ensurance_dashboard_setup_tiles', $tiles, $user_id );
}

/**
 * Hands the tiles to the setup card.
 *
 * A filter rather than an edit to ensurance_dashboard_setup_panel(), per
 * CLAUDE.md's standing rule — the panel keeps writing the card's copy and this
 * only adds the row of destinations under it.
 *
 * @param array $panel   The card's copy.
 * @param int   $user_id User the card is being rendered for.
 * @return array
 */
function ensurance_dashboard_setup_panel_tiles( $panel, $user_id ) {
    // No headline means no card (see ensurance_dashboard_setup_panel), and tiles
    // on a card that does not render would just be resolved and thrown away.
    if ( empty( $panel['title'] ) ) {
        return $panel;
    }

    $panel['tiles'] = ensurance_dashboard_setup_tiles( $user_id );

    return $panel;
}
add_filter( 'ensurance_dashboard_setup_panel', 'ensurance_dashboard_setup_panel_tiles', 10, 2 );

/**
 * Retitles the setup card's eyebrow to the state it is actually reporting.
 *
 * ensurance_dashboard_setup_panel() writes "Step N of M" — a position in a
 * sequence, which was the right eyebrow for a three-step checklist worked through
 * one item at a time. With the gate cut to states alone that sentence renders as
 * "Step 1 of 1", which tells an agent nothing and reads like a bug.
 *
 * Step 3 asks for the matching-off state instead, and the words are the mirror of
 * the quiet panel's "Matching is on" (components/dashboard-slot-quiet.php) —
 * deliberately, because they are the same claim about the same machinery with the
 * answer flipped. The pulsing dot beside it is drawn by the component.
 *
 * @param array $panel   The card's copy.
 * @param int   $user_id User the card is being rendered for.
 * @return array
 */
function ensurance_dashboard_setup_panel_eyebrow( $panel, $user_id ) {
    if ( empty( $panel['title'] ) ) {
        return $panel;
    }

    $panel['eyebrow'] = 'Matching is off';

    return $panel;
}
add_filter( 'ensurance_dashboard_setup_panel', 'ensurance_dashboard_setup_panel_eyebrow', 10, 2 );

/**
 * What the setup card says — its eyebrow, headline, sentence, checklist and the
 * one button under them.
 *
 * Step 9 of templates/agent-dashboard/build-steps.md. First run: the agent has
 * an account and cannot be matched yet, so this is the action of the moment and
 * gets the same dark navy weight as a waiting request. Everything on it is about
 * ONE thing — the first outstanding step (ensurance_dashboard_setup_steps) — with
 * the other two visible only so the agent can see how much is left.
 *
 * The headline names that one blocking thing and the sentence says why it blocks
 * matching. Neither is written down here: both come off the step itself, so a
 * card about coverage types cannot end up captioned with the copy for counties.
 *
 * ONE BUTTON, and it goes to agent support (ensurance_dashboard_support_url) —
 * not to a form. The agent cannot fix any of this themselves in v1, and a button
 * that opened an editable profile would be promising something the product does
 * not do.
 *
 * NO BLOCKING STEP, NO CARD. With the checklist finished there is no headline to
 * write and nothing to ask for, so the title comes back empty and the component
 * renders nothing — the same rule the live card and the decided panel follow.
 * The slot should not be in this state at all by then; see
 * ensurance_dashboard_matchable_slot.
 *
 * @param int $user_id Optional. Defaults to the current user.
 * @return array{eyebrow:string,title:string,body:string,steps:array,cta:string,cta_url:string}
 */
function ensurance_dashboard_setup_panel( $user_id = 0 ) {
    $user_id = $user_id ? (int) $user_id : get_current_user_id();
    $steps   = ensurance_dashboard_setup_steps( $user_id );

    $panel = array(
        'eyebrow' => '',
        'title'   => '',
        'body'    => '',
        'steps'   => $steps,
        'cta'     => 'Message agent support',
        'cta_url' => ensurance_dashboard_support_url(),
    );

    foreach ( $steps as $step ) {
        if ( 'current' !== $step['status'] ) {
            continue;
        }

        // "Step 2 of 3" — the position of the blocking step in the sequence,
        // counted against the checklist actually shown rather than a literal 3,
        // so the two can never disagree if onboarding ever grows a fourth.
        $panel['eyebrow'] = sprintf( 'Step %d of %d', $step['number'], count( $steps ) );
        $panel['title']   = $step['title'];
        $panel['body']    = $step['body'];
        break;
    }

    /**
     * Filter the setup card's copy.
     *
     * @param array $panel   ['eyebrow' => …, 'title' => …, 'body' => …, 'steps' => …, 'cta' => …, 'cta_url' => …].
     * @param int   $user_id User the card is being rendered for.
     */
    return (array) apply_filters( 'ensurance_dashboard_setup_panel', $panel, $user_id );
}

/**
 * When founding access started — the timeline's origin, and the date every other
 * date on it is counted from.
 *
 * Step 10 of templates/agent-dashboard/build-steps.md. Unlike most of the
 * dashboard's data, this one is REAL today: a founding agency gets an account
 * through /create-account (see ensurance_founding_plans), and the moment that
 * account was created is the moment its 60 days started. So `user_registered` is
 * not a stand-in for the access record — until a subscription record exists, it
 * IS the access record.
 *
 * It is stored in UTC, and the offset is stated rather than left to strtotime's
 * default, which would read the string as server-local and slide the whole
 * timeline by the server's offset.
 *
 * THE FILTER IS THE SEAM. When real founding-access records exist (a Stripe
 * subscription, a manually set start for an agency onboarded by hand), point
 * `ensurance_dashboard_access_start` at them and every date, the day counter and
 * the elapsed/future split follow with no other change.
 *
 * @param int $user_id Optional. Defaults to the current user.
 * @return int Unix timestamp, or 0 when there is no start date to count from.
 */
function ensurance_dashboard_access_start( $user_id = 0 ) {
    $user_id = $user_id ? (int) $user_id : get_current_user_id();
    $user    = $user_id ? get_userdata( $user_id ) : null;
    $start   = 0;

    if ( $user && ! empty( $user->user_registered ) ) {
        $parsed = strtotime( $user->user_registered . ' +00:00' );
        $start  = $parsed ? (int) $parsed : 0;
    }

    /**
     * Filter when founding access started for an agent.
     *
     * @param int $start   Unix timestamp, 0 when unknown.
     * @param int $user_id User the timeline is being resolved for.
     */
    return (int) apply_filters( 'ensurance_dashboard_access_start', $start, $user_id );
}

/**
 * How long founding access runs before it converts — 60 days.
 *
 * Named once rather than written into the timeline, because it decides three
 * things that must agree: the "60-day mark" label, the date under it, and the
 * "N days left" the today segment counts down. A term change moves all three.
 *
 * @return int Days. At least 1.
 */
function ensurance_dashboard_access_term() {
    /**
     * Filter the founding access term, in days.
     *
     * @param int $days Term length.
     */
    return max( 1, (int) apply_filters( 'ensurance_dashboard_access_term', 60 ) );
}

/**
 * What continued access costs after the term — the design's "$29 / month".
 *
 * The public price, from the /pricing-plans card and its FAQ ("Founding Agent
 * Access may continue at $29 per month unless canceled before the subscription
 * begins"), stated in the design's shorter form.
 *
 * A CARD IS TAKEN UP FRONT. The 60 days are free; the payment method is not
 * optional, and the subscription converts against the card already on file unless
 * it is cancelled before the term ends. That is why the timeline states a first
 * charge as a fact and the Account view's founding-access row leads with the
 * cancel window.
 *
 * WHAT IS NOT SETTLED is where in the theme that card becomes readable — nothing
 * here can see it yet, which is why ensurance_dashboard_payment_method() comes
 * back empty and its row waits. This string and
 * ensurance_dashboard_founding_timeline() are the two places on the dashboard
 * that move if the price or the term does.
 *
 * @return string Formatted price, '' to drop it from the timeline.
 */
function ensurance_dashboard_access_price() {
    /**
     * Filter the price shown on the founding access timeline.
     *
     * @param string $price Formatted price string.
     */
    return (string) apply_filters( 'ensurance_dashboard_access_price', '$29 / month' );
}

/**
 * Whole calendar days from one moment to another, in the site's timezone.
 *
 * CALENDAR days, not 86400-second blocks: both ends are floored to local midnight
 * before they are compared, so "day 18" turns over when the date does rather than
 * at the hour access started. That also makes it DST-safe — a 23- or 25-hour day
 * still counts as one.
 *
 * @param int $from Unix timestamp to count from.
 * @param int $to   Unix timestamp to count to.
 * @return int Days between them. Negative when $to is before $from.
 */
function ensurance_dashboard_days_between( $from, $to ) {
    $tz   = wp_timezone();
    $from = ( new DateTimeImmutable( '@' . (int) $from ) )->setTimezone( $tz )->setTime( 0, 0 );
    $to   = ( new DateTimeImmutable( '@' . (int) $to ) )->setTimezone( $tz )->setTime( 0, 0 );
    $diff = $from->diff( $to );

    return (int) $diff->days * ( $diff->invert ? -1 : 1 );
}

/**
 * How far into founding access the agent is — the "day N" on the timeline.
 *
 * DAYS ELAPSED, which is what makes the timeline's arithmetic hold: the design
 * reads day 18 on Aug 11 from a Jul 24 start (18 days elapsed), and puts the
 * 60-day mark 60 days after that same start. Counting from 1 instead would leave
 * the counter and the cancel date one day apart forever. The first day is
 * therefore day 0 — zero days used — which is exactly what the segment beside it
 * says by naming today's date as the start.
 *
 * @param int $user_id Optional. Defaults to the current user.
 * @param int $now     Optional. Moment to measure to. Defaults to now.
 * @return int Days elapsed, 0 when there is no start date.
 */
function ensurance_dashboard_access_day( $user_id = 0, $now = 0 ) {
    $start = ensurance_dashboard_access_start( $user_id );

    if ( ! $start ) {
        return 0;
    }

    return max( 0, ensurance_dashboard_days_between( $start, $now ? (int) $now : time() ) );
}

/**
 * The founding access timeline — access started → today → the 60-day mark → the
 * first charge.
 *
 * Step 10 of templates/agent-dashboard/build-steps.md, and the ONLY place billing
 * dates appear on Today. Nothing else in the view may restate them — not the
 * greeting row, and not the sidebar's founding-access card, which Step 1 left out
 * for exactly this reason.
 *
 * BUILT TO BE REITERATED. The component that draws this takes whatever list it is
 * given and does not know what a "60-day mark" is, and every visual difference
 * between one segment and the next is DERIVED from `at` rather than authored:
 *
 *   - a segment whose `at` has passed is elapsed and takes the accent rule;
 *   - a segment whose `at` is still ahead (or unknown) takes the neutral border;
 *   - the LAST elapsed segment is the current one, and takes the accent label.
 *
 * So a fifth milestone — a verification date, a renewal, a second charge — is one
 * entry through the filter below, in the right place, with the right rule, with
 * nothing else touched. Same for dropping one: pass four, pass three, pass six.
 *
 * WHAT EACH SEGMENT CARRIES:
 *   key     string  Stable slug, for filters targeting one segment.
 *   label   string  The first line — what the moment is.
 *   date    string  The second line's date, '' when the moment has no date to show.
 *   note    string  The rest of the second line, joined to `date` with an em dash.
 *   at      int     Unix timestamp the status is derived from. 0 = unknown, treated as ahead.
 *   status  string  'done' | 'current' | 'upcoming'. Derived, never authored.
 *
 * TODAY'S SECOND LINE IS "N DAYS LEFT", where the design writes "Profile live,
 * matching on". Two reasons for the change. It would be a second copy of status
 * the priority slot already owns, which Step 15 forbids outright; and it can
 * simply be FALSE — an agent in `setup` is neither live nor matching, and the
 * timeline has no business claiming otherwise. Days remaining is true in every
 * state, is nowhere else on the page, and is what an agent looking at a countdown
 * actually wants from it.
 *
 * NO START DATE, NO TIMELINE. An empty list takes the whole section down in
 * components/dashboard-timeline.php rather than drawing four rules over blanks —
 * the same rule the live card and the setup card follow.
 *
 * @param int $user_id Optional. Defaults to the current user.
 * @param int $now     Optional. Moment to resolve against. Defaults to now.
 * @return array<int,array{key:string,label:string,date:string,note:string,at:int,status:string}>
 */
function ensurance_dashboard_founding_timeline( $user_id = 0, $now = 0 ) {
    $user_id = $user_id ? (int) $user_id : get_current_user_id();
    $now     = $now ? (int) $now : time();
    $start   = ensurance_dashboard_access_start( $user_id );

    if ( ! $start ) {
        return array();
    }

    $term = ensurance_dashboard_access_term();

    // The two future dates, stepped in DAYS rather than by adding seconds, so a
    // DST change inside the term cannot land the mark on the wrong date.
    $origin = ( new DateTimeImmutable( '@' . $start ) )->setTimezone( wp_timezone() );
    $mark   = $origin->modify( sprintf( '+%d days', $term ) )->getTimestamp();
    $charge = $origin->modify( sprintf( '+%d days', $term + 1 ) )->getTimestamp();

    $day  = max( 0, ensurance_dashboard_days_between( $start, $now ) );
    $left = $term - $day;

    // The countdown, phrased for the day it is on. Past the term it stops
    // counting rather than going negative — the two segments after it carry
    // what happened instead.
    if ( $left > 1 ) {
        $remaining = sprintf( '%d days left', $left );
    } elseif ( 1 === $left ) {
        $remaining = '1 day left';
    } elseif ( 0 === $left ) {
        $remaining = 'Last day';
    } else {
        $remaining = 'Access period ended';
    }

    $segments = array(
        array(
            'key'   => 'started',
            'label' => 'Access started',
            'date'  => wp_date( 'M j', $start ),
            'note'  => '',
            'at'    => $start,
        ),
        array(
            'key'   => 'today',
            // "Today — day 18". The em dash is the design's.
            'label' => sprintf( 'Today — day %d', $day ),
            'date'  => '',
            'note'  => $remaining,
            // Always the last elapsed moment, which is what makes this the
            // current segment on every day of the term and after it.
            'at'    => $now,
        ),
        array(
            'key'   => 'mark',
            'label' => sprintf( '%d-day mark', $term ),
            'date'  => wp_date( 'M j', $mark ),
            'note'  => 'cancel by this date',
            'at'    => $mark,
        ),
        array(
            'key'   => 'charge',
            'label' => 'First charge',
            'date'  => wp_date( 'M j', $charge ),
            'note'  => ensurance_dashboard_access_price(),
            'at'    => $charge,
        ),
    );

    /**
     * Filter the founding access timeline before statuses are derived.
     *
     * The hook to add, remove or reword a milestone. Entries must keep the shape
     * documented above minus `status`, which is resolved below from `at` — so a
     * segment added here cannot be given the wrong rule.
     *
     * @param array $segments Segments in display order.
     * @param int   $user_id  User the timeline is being resolved for.
     * @param int   $now      Moment the timeline is resolved against.
     */
    $segments = (array) apply_filters( 'ensurance_dashboard_founding_timeline', $segments, $user_id, $now );

    // The current segment is the elapsed one nearest to now. Found first, because
    // "is this the current one" cannot be answered while looking at one segment
    // in isolation — every other part of the status can.
    $current = -1;
    $nearest = 0;

    foreach ( $segments as $i => $segment ) {
        $at = isset( $segment['at'] ) ? (int) $segment['at'] : 0;

        if ( $at > 0 && $at <= $now && $at >= $nearest ) {
            $nearest = $at;
            $current = $i;
        }
    }

    $resolved = array();

    foreach ( $segments as $i => $segment ) {
        $at = isset( $segment['at'] ) ? (int) $segment['at'] : 0;

        if ( $i === $current ) {
            $status = 'current';
        } elseif ( $at > 0 && $at <= $now ) {
            $status = 'done';
        } else {
            $status = 'upcoming';
        }

        $resolved[] = array(
            'key'    => isset( $segment['key'] ) ? (string) $segment['key'] : (string) $i,
            'label'  => isset( $segment['label'] ) ? (string) $segment['label'] : '',
            'date'   => isset( $segment['date'] ) ? (string) $segment['date'] : '',
            'note'   => isset( $segment['note'] ) ? (string) $segment['note'] : '',
            'at'     => $at,
            'status' => $status,
        );
    }

    return $resolved;
}

/**
 * A past moment written the way Today's Recent column writes it — "2h ago",
 * "Aug 6".
 *
 * Step 11 of templates/agent-dashboard/build-steps.md, whose activity rows carry
 * a relative timestamp on the right. The design writes those strings out; here
 * they are derived from the moment itself, so a cached render cannot go stale.
 *
 * COARSE ON PURPOSE, and it switches units the way the design's own list does:
 * anything inside the last day reads as elapsed time ("2h ago"), anything older
 * reads as a date ("Aug 6"). That is the boundary at which an agent stops
 * thinking in hours and starts thinking in days — "43h ago" answers a question
 * nobody asked. This is deliberately NOT ensurance_dashboard_countdown(), which
 * measures the other direction (time LEFT on a request, to the minute, because a
 * deadline closing in is exactly when the minutes matter).
 *
 * A dated string gains its year once it is outside the current one, so an entry
 * from a previous term cannot read as this August.
 *
 * Returns '' for an unknown moment, or one that has not happened — a row with
 * nothing to stamp drops the stamp rather than printing "0m ago" or a date in
 * the future.
 *
 * @param int $at  Unix timestamp of the moment.
 * @param int $now Optional. Moment to measure from. Defaults to now.
 * @return string Relative time like "12m ago" / "2h ago", or a date; '' if none.
 */
function ensurance_dashboard_relative_time( $at, $now = 0 ) {
    $at  = (int) $at;
    $now = $now ? (int) $now : time();

    if ( $at <= 0 || $at > $now ) {
        return '';
    }

    $ago = $now - $at;

    if ( $ago < MINUTE_IN_SECONDS ) {
        return 'Just now';
    }

    if ( $ago < HOUR_IN_SECONDS ) {
        return sprintf( '%dm ago', (int) floor( $ago / MINUTE_IN_SECONDS ) );
    }

    if ( $ago < DAY_IN_SECONDS ) {
        return sprintf( '%dh ago', (int) floor( $ago / HOUR_IN_SECONDS ) );
    }

    // Same-year dates stay short, the way the design writes them; older ones
    // carry the year, since "Aug 6" with no year is a different claim.
    $format = ( wp_date( 'Y', $at ) === wp_date( 'Y', $now ) ) ? 'M j' : 'M j, Y';

    return wp_date( $format, $at );
}

/**
 * What a shopper sees of this agency — the left reference column on Today.
 *
 * Step 11 of templates/agent-dashboard/build-steps.md: the four pieces of the
 * agency that face outward, so an agent can check what is being shown on their
 * behalf without leaving Today. Displayed name, the counties they are matched
 * in, the coverages they are matched on, and the inbox a matched request is sent
 * to.
 *
 * READ-ONLY, WITH NO EDIT AFFORDANCE — not even a disabled one. The step says so
 * outright, and the scope note at the top of build-steps.md makes it the rule for
 * the whole product: agency data is not editable in v1, and every "change this"
 * path ends at agent support. So these are values and captions and nothing else;
 * the sentence that routes changes to support is already on the quiet panel
 * (Step 8) and gets its own row on Account (Step 14).
 *
 * NOTHING NEW IS RESOLVED HERE. Every value comes from the resolver that already
 * owns it — ensurance_dashboard_agency_name(), _service_areas(),
 * _coverage_types(), _request_inbox() — which is what keeps this column and the
 * surfaces that state the same things (the rail's user card, the quiet panel's
 * sentence) from ever disagreeing. Two of those have nothing to return outside
 * the admin preview today, and their rows simply do not render: a caption over a
 * blank would be the one thing worse than a missing row, since it would read as
 * "your service areas are empty" rather than "not set up yet".
 *
 * SHAPE — a list of ['key' => …, 'value' => …, 'caption' => …], in display
 * order, with `value` shown above `caption`. Any row missing either half is
 * dropped.
 *
 * Captions are the design's own (the "What shoppers see" block of
 * templates/agent-dashboard/AgentDashboard.dc.html).
 *
 * @param int $user_id Optional. Defaults to the current user.
 * @return array<int,array{key:string,value:string,caption:string}>
 */
function ensurance_dashboard_shopper_rows( $user_id = 0 ) {
    $user_id   = $user_id ? (int) $user_id : get_current_user_id();
    $areas     = ensurance_dashboard_service_areas( $user_id );
    $coverages = ensurance_dashboard_coverage_types( $user_id );

    $rows = array(
        array(
            'key'     => 'name',
            'value'   => ensurance_dashboard_agency_name( $user_id ),
            'caption' => 'Displayed name',
        ),
        array(
            'key'     => 'areas',
            // Plain commas, not wp_sprintf's "%l" — this is a list under a
            // label, not prose, and "Coastal, Ventura, and Santa Barbara"
            // reads as a sentence fragment in a column of values.
            'value'   => implode( ', ', $areas ),
            'caption' => 'Service areas',
        ),
        array(
            'key'     => 'coverages',
            'value'   => implode( ', ', $coverages ),
            'caption' => 'Coverage types',
        ),
        array(
            'key'     => 'inbox',
            'value'   => ensurance_dashboard_request_inbox( $user_id ),
            'caption' => 'Where requests are sent',
        ),
    );

    /**
     * Filter the rows in Today's "What shoppers see" column.
     *
     * The hook the real agency record attaches to when it exists. Rows must keep
     * the shape documented above; anything missing a value or a caption is
     * dropped rather than rendered blank.
     *
     * @param array $rows    Rows in display order.
     * @param int   $user_id User the column is being resolved for.
     */
    $rows = (array) apply_filters( 'ensurance_dashboard_shopper_rows', $rows, $user_id );

    $clean = array();

    foreach ( $rows as $i => $row ) {
        if ( empty( $row['value'] ) || empty( $row['caption'] ) ) {
            continue;
        }

        $clean[] = array(
            'key'     => isset( $row['key'] ) ? (string) $row['key'] : (string) $i,
            'value'   => (string) $row['value'],
            'caption' => (string) $row['caption'],
        );
    }

    return $clean;
}

/**
 * What has happened on this account lately — the right reference column on Today.
 *
 * Step 11 of templates/agent-dashboard/build-steps.md. Four lines at most, each
 * one thing that happened and when: a decision made, a detail updated, a license
 * verified. It is a record, not a queue — nothing here is actionable, and nothing
 * here restates the priority slot's ask.
 *
 * IT IS ALSO NOT A STATUS PANEL. Step 11 forbids repeating what the rail already
 * says, and Step 15 generalizes it: no status, count or date may appear twice on
 * the page. So this column never counts anything ("4 matched this month" belongs
 * to the quiet panel), never says what is waiting (the slot owns that), and never
 * dates the term (the timeline owns that).
 *
 * AND IT DOES NOT DATE A MATCH — the rule Step 15's pass added, because the
 * column was breaking it twice over. A request ARRIVING is already stamped
 * everywhere it needs to be: the live card's "Submitted" tile carries the one
 * still waiting, the quiet panel's "Last match" stat carries the most recent one
 * when nothing is, and the Requests view stamps every one of them. A "request
 * matched" row here put that same moment on Today a second time — beside the tile
 * saying it in the live state, and beside a stat that disagreed with it in the
 * quiet one. Decisions and record changes are what this column is for; the
 * arrival belongs to the surfaces above it. Anything attached through the filter
 * below should follow the same line — and, when a real trail starts recording
 * decisions, hold the newest one back while the decided panel is still confirming
 * it, for the same reason: the slot is already saying it, five inches higher.
 *
 * NOTHING IS RECORDED YET. No matched request, decision or profile change writes
 * an activity trail — the same gap behind ensurance_dashboard_request_count() and
 * ensurance_dashboard_match_stats() — so this returns an empty list and the
 * column does not render. The admin preview is the one exception, for the same
 * reason the live card has one: this band has to be reviewable before the product
 * produces the events it lists.
 *
 * THE PREVIEW IS NOT SLOT-SPECIFIC. Unlike the sample request or the sample
 * matching data, which each belong to one slot state, the reference band renders
 * under EVERY state — so any `?slot=` preview shows the sample rows.
 *
 * SHAPE — a list of ['key' => …, 'what' => …, 'at' => …, 'when' => …], newest
 * first. `at` is the moment; `when` is what the row prints, derived from `at`
 * when the entry does not state it. The order given is the order shown: entries
 * are not re-sorted, since an entry may legitimately arrive with no timestamp at
 * all. Anything with nothing to say (no `what`) is dropped, and the list is cut
 * to $limit.
 *
 * @param int $user_id Optional. Defaults to the current user.
 * @param int $limit   Optional. Most rows to return. Step 11 caps the column at 4.
 * @param int $now     Optional. Moment relative stamps are measured from.
 * @return array<int,array{key:string,what:string,at:int,when:string}>
 */
function ensurance_dashboard_activity( $user_id = 0, $limit = 4, $now = 0 ) {
    $user_id = $user_id ? (int) $user_id : get_current_user_id();
    $limit   = max( 0, (int) $limit );
    $now     = $now ? (int) $now : time();

    $entries = array();

    // Admin preview only — see the note above. The design's own rows, with its
    // fixed strings ("2h ago", "Aug 6") expressed as real moments so the preview
    // exercises ensurance_dashboard_relative_time() rather than hardcoding its
    // output.
    //
    // The design's newest row is "Auto request matched — Coastal County", stamped
    // the same 2h ago as the live card's Submitted tile. It is not here: see the
    // note above on why this column does not date a match. Its place is taken by
    // the passed request from ensurance_dashboard_sample_history(), so the two
    // decision rows on this column are the same two decisions the Requests view
    // lists — one fabricated agency, described the same way on both views.
    if ( '' !== ensurance_dashboard_priority_preview() ) {
        $entries = array(
            array(
                'key'  => 'accepted',
                'what' => 'Home request accepted — Ventura',
                'at'   => $now - ( 5 * DAY_IN_SECONDS ),
            ),
            array(
                'key'  => 'areas',
                'what' => 'Service areas updated',
                'at'   => $now - ( 7 * DAY_IN_SECONDS ),
            ),
            array(
                'key'  => 'passed',
                'what' => 'Auto request passed — Santa Barbara',
                'at'   => $now - ( 9 * DAY_IN_SECONDS ),
            ),
            array(
                'key'  => 'license',
                'what' => 'License CA-0K48219 verified',
                'at'   => $now - ( 18 * DAY_IN_SECONDS ),
            ),
        );
    }

    /**
     * Filter Today's recent activity rows.
     *
     * The hook a real activity trail attaches to when it exists. Entries must
     * keep the shape documented above and arrive newest first; `when` is
     * optional and is derived from `at` when omitted.
     *
     * @param array $entries Entries, newest first.
     * @param int   $user_id User the column is being resolved for.
     * @param int   $limit   Most rows the column will show.
     * @param int   $now     Moment relative stamps are measured from.
     */
    $entries = (array) apply_filters( 'ensurance_dashboard_activity', $entries, $user_id, $limit, $now );

    // Asked for nothing, return nothing — the cap is applied after each row is
    // built below, which would otherwise let a zero limit through with one row.
    if ( 0 === $limit ) {
        return array();
    }

    $clean = array();

    foreach ( $entries as $i => $entry ) {
        if ( empty( $entry['what'] ) ) {
            continue;
        }

        $at   = isset( $entry['at'] ) ? (int) $entry['at'] : 0;
        $when = isset( $entry['when'] ) ? (string) $entry['when'] : ensurance_dashboard_relative_time( $at, $now );

        $clean[] = array(
            'key'  => isset( $entry['key'] ) ? (string) $entry['key'] : (string) $i,
            'what' => (string) $entry['what'],
            'at'   => $at,
            'when' => $when,
        );

        if ( count( $clean ) >= $limit ) {
            break;
        }
    }

    return $clean;
}

/**
 * The four states a matched request can be in on the Requests view — THE list.
 *
 * Step 12 of templates/agent-dashboard/build-steps.md: each row carries a status
 * badge, and its tone maps to the status. One array holds both halves of that
 * mapping, so a row can only ever be in a state that has a label and a tone, and
 * neither can be written down twice.
 *
 * THE TONES ARE THE DESIGN'S, and there are three of them for four states:
 *
 *   awaiting → info     the one row still asking something of the agent
 *   accepted → ok       a request they took
 *   passed   → neutral  a request they let go
 *   expired  → neutral  a request that timed out
 *
 * Passed and expired share a tone on purpose. Neither is an outcome to
 * congratulate or warn about — both are simply closed — and a distinct color for
 * each would put weight on a difference the badge's own word already states.
 * Nothing here is red: passing is a legitimate answer (Step 6 says not to
 * discourage it), and an expired request is a queue event, not the agent's
 * error.
 *
 * @return array<string,array{label:string,tone:string}> Status slug => label and
 *                                                       badge tone.
 */
function ensurance_dashboard_request_statuses() {
    return array(
        'awaiting' => array(
            'label' => 'Awaiting you',
            'tone'  => 'info',
        ),
        'accepted' => array(
            'label' => 'Accepted',
            'tone'  => 'ok',
        ),
        'passed'   => array(
            'label' => 'Passed',
            'tone'  => 'neutral',
        ),
        'expired'  => array(
            'label' => 'Expired',
            'tone'  => 'neutral',
        ),
    );
}

/**
 * The design's own sample history — the closed requests behind the Requests
 * preview.
 *
 * The counterpart of ensurance_dashboard_sample_request() and
 * ensurance_dashboard_sample_matching(), and separate from the first of those on
 * purpose: this is everything the agent has ALREADY answered, while the sample
 * request is the one still waiting. ensurance_dashboard_request_rows() puts the
 * two together, which is what makes the awaiting row at the top of the table the
 * same request Today is asking about rather than a fifth invention.
 *
 * PREVIEW ONLY. Nothing reaches this except through
 * ensurance_dashboard_priority_preview(), which is capability-gated — an agent
 * can never be shown these values. Copied row for row from `reqRows` in
 * templates/agent-dashboard/AgentDashboard.dc.html, minus its first row (the
 * awaiting one, which comes from the live request instead), with the design's
 * fixed date strings expressed as real moments so the preview exercises
 * ensurance_dashboard_relative_time() rather than hardcoding its output.
 *
 * @param int $now Optional. Moment the sample's ages are measured back from.
 * @return array<int,array{key:string,title:string,detail:string,at:int,status:string}>
 */
function ensurance_dashboard_sample_history( $now = 0 ) {
    $now = $now ? (int) $now : time();

    return array(
        array(
            'key'    => 'sample-home-ventura',
            'title'  => 'Home — Ventura County',
            'detail' => 'Single family, purchased 2019',
            'at'     => $now - ( 5 * DAY_IN_SECONDS ),
            'status' => 'accepted',
        ),
        array(
            'key'    => 'sample-auto-santa-barbara',
            'title'  => 'Auto — Santa Barbara County',
            'detail' => '1 driver · ZIP 93101',
            'at'     => $now - ( 9 * DAY_IN_SECONDS ),
            'status' => 'passed',
        ),
        array(
            'key'    => 'sample-life-coastal',
            'title'  => 'Life — Coastal County',
            'detail' => 'Term, age 41',
            'at'     => $now - ( 12 * DAY_IN_SECONDS ),
            'status' => 'accepted',
        ),
        array(
            'key'    => 'sample-home-coastal',
            'title'  => 'Home — Coastal County',
            'detail' => 'Condo · ZIP 93013',
            'at'     => $now - ( 16 * DAY_IN_SECONDS ),
            'status' => 'expired',
        ),
    );
}

/**
 * Every request matched to this agent, newest first — the Requests view's table.
 *
 * Step 12 of templates/agent-dashboard/build-steps.md. The whole history in one
 * list: what the request was, the one line that describes it, when it arrived,
 * and where it ended up.
 *
 * THE TOP ROW IS TODAY'S SLOT, NOT A COPY OF IT. The step is explicit that the
 * awaiting row is the only row tied to Today, and the tie is real rather than
 * cosmetic — the row is built from ensurance_dashboard_live_request(), the same
 * resolver the live card renders, and its status follows
 * ensurance_dashboard_decision(). So accepting on Today does not leave a stale
 * "Awaiting you" in the table: the same row re-reads as Accepted, and Undo puts
 * it back. Nothing in this file can disagree with the slot about the one request
 * that is in both places, because there is only one request.
 *
 * EVERYTHING BELOW IT IS HISTORY, AND THERE IS NONE YET. No decision writes a
 * history row — ensurance_dashboard_record_decision() names that as the queue's
 * job — so the table holds at most the live row today, and usually nothing at
 * all. The admin preview is the one exception, for the same reason the live card
 * has one: /dashboard/?view=requests&slot=live is how the full five-row table
 * gets reviewed before a queue exists. (`?slot=` is the preview switch for the
 * whole dashboard, not just Today; the reference band on Today reads it the same
 * way.)
 *
 * NEWEST FIRST is the caller's contract, not a sort applied here: rows may
 * legitimately arrive with no timestamp at all — the queue can know an order it
 * cannot date — and re-sorting on `at` would drop those to the bottom. The live
 * row is prepended because it is by definition the most recent thing matched.
 *
 * SHAPE — a list of ['key' => …, 'title' => …, 'detail' => …, 'at' => …,
 * 'when' => …, 'status' => …, 'label' => …, 'tone' => …]. `when` is what the row
 * prints, derived from `at` when the entry does not state it; `label` and `tone`
 * come from ensurance_dashboard_request_statuses() and are not overridable per
 * row, so two rows in the same state can never look different. A row with no
 * title, or in a state that list does not name, is dropped.
 *
 * @param int $user_id Optional. Defaults to the current user.
 * @param int $now     Optional. Moment relative stamps are measured from.
 * @return array<int,array{key:string,title:string,detail:string,at:int,when:string,status:string,label:string,tone:string}>
 */
function ensurance_dashboard_request_rows( $user_id = 0, $now = 0 ) {
    $user_id  = $user_id ? (int) $user_id : get_current_user_id();
    $now      = $now ? (int) $now : time();
    $statuses = ensurance_dashboard_request_statuses();
    $rows     = array();

    // The request Today is asking about, as the table's newest row. Its status
    // is whatever the agent has done with it so far — nothing yet, accepted, or
    // passed — which is what keeps the two surfaces telling one story.
    $live = ensurance_dashboard_live_request( $user_id );

    if ( ! empty( $live ) ) {
        $decision = ensurance_dashboard_decision( $user_id );

        $decided = array(
            'accept' => 'accepted',
            'pass'   => 'passed',
        );

        $rows[] = array(
            'key'    => 'live',
            'title'  => sprintf( '%s — %s', $live['coverage'], $live['county'] ),
            'detail' => $live['detail'],
            'at'     => $live['matched_at'],
            'status' => isset( $decided[ $decision ] ) ? $decided[ $decision ] : 'awaiting',
        );
    }

    // Admin preview only — see the note above.
    if ( '' !== ensurance_dashboard_priority_preview() ) {
        $rows = array_merge( $rows, ensurance_dashboard_sample_history( $now ) );
    }

    /**
     * Filter the rows of the dashboard's Requests table.
     *
     * The hook the real matching queue attaches to when it exists. Entries must
     * keep the shape documented above and arrive NEWEST FIRST — they are not
     * re-sorted. `when` is optional and is derived from `at` when omitted;
     * `status` must be one of ensurance_dashboard_request_statuses(), whose
     * label and tone are applied afterwards and cannot be overridden per row.
     *
     * @param array $rows    Rows, newest first.
     * @param int   $user_id User the table is being resolved for.
     * @param int   $now     Moment relative stamps are measured from.
     */
    $rows = (array) apply_filters( 'ensurance_dashboard_request_rows', $rows, $user_id, $now );

    $clean = array();

    foreach ( $rows as $i => $row ) {
        $status = isset( $row['status'] ) ? sanitize_key( $row['status'] ) : '';

        // A row with nothing to name, or in a state the badge has no word for,
        // is dropped rather than rendered as a blank line or an unlabeled chip.
        if ( empty( $row['title'] ) || ! isset( $statuses[ $status ] ) ) {
            continue;
        }

        $at = isset( $row['at'] ) ? (int) $row['at'] : 0;

        $clean[] = array(
            'key'    => isset( $row['key'] ) ? (string) $row['key'] : (string) $i,
            'title'  => (string) $row['title'],
            'detail' => isset( $row['detail'] ) ? (string) $row['detail'] : '',
            'at'     => $at,
            'when'   => isset( $row['when'] ) ? (string) $row['when'] : ensurance_dashboard_relative_time( $at, $now ),
            'status' => $status,
            'label'  => $statuses[ $status ]['label'],
            'tone'   => $statuses[ $status ]['tone'],
        );
    }

    return $clean;
}

/**
 * The design's own sample agency record — agency name, license number and phone.
 *
 * The fields on the Agency Profile view that no other surface resolves, and the
 * only ones the product has nowhere to read from at all: /create-account collects
 * first name, last name, username and email, and nothing since captures an agency
 * name, a license or a phone number (the same gap behind
 * ensurance_dashboard_service_areas).
 *
 * PREVIEW ONLY, like ensurance_dashboard_sample_request() and
 * ensurance_dashboard_sample_matching() — nothing reaches these values except
 * through the capability-gated ensurance_dashboard_priority_preview(). An agent
 * is never shown a license number that is not theirs.
 *
 * Copied field for field from the `isProf` view of
 * templates/agent-dashboard/AgentDashboard.dc.html.
 *
 * @return array{name:string,license:string,phone:string}
 */
function ensurance_dashboard_sample_agency() {
    return array(
        'name'    => 'Coastline Insurance Group',
        'license' => 'CA-0K48219',
        'phone'   => '(805) 555-0142',
    );
}

/**
 * The agency's license number, as shown on the Agency Profile view.
 *
 * NOTHING CARRIES IT TODAY. It is verified out of band — the manual approval
 * behind a founding agency's account is the record (see the identity step in
 * ensurance_dashboard_setup_steps) — and no field in the product stores the
 * number itself. So this returns '' and the profile drops the chip rather than
 * printing a labeled blank, which on this view would read as "your license is
 * missing" rather than "we have not put it on screen yet".
 *
 * The admin preview is the one exception, gated on `?slot=quiet` for the same
 * reason ensurance_dashboard_service_areas() is: that is the preview in which
 * the agency record is fully populated, so one URL shows the whole profile as
 * the design draws it.
 *
 * Point the filter at the real agency record when it exists.
 *
 * @param int $user_id Optional. Defaults to the current user.
 * @return string License number, '' when it is not known.
 */
function ensurance_dashboard_license_number( $user_id = 0 ) {
    $user_id = $user_id ? (int) $user_id : get_current_user_id();
    $sample  = ensurance_dashboard_sample_agency();
    $license = ( 'quiet' === ensurance_dashboard_priority_preview() ) ? $sample['license'] : '';

    /**
     * Filter the agency license number shown on the Agency Profile view.
     *
     * @param string $license License number, '' when unknown.
     * @param int    $user_id User the profile is being resolved for.
     */
    return (string) apply_filters( 'ensurance_dashboard_license_number', $license, $user_id );
}

/**
 * The agency's phone number, as shown on the Agency Profile view.
 *
 * Mirrors ensurance_dashboard_license_number() in every respect, including
 * having nothing to return outside the admin preview — with one difference in
 * what the view does about it. Phone is one of the four fields the profile
 * promises, so an unresolved number holds its chip and reads "Not on file"
 * instead of being dropped the way the license is
 * (ensurance_dashboard_profile_fields).
 *
 * IT IS NOT A CONTACT ROUTE. The number is here as matching-relevant reference —
 * what an accepted shopper would be given — not as a way to reach the agency, so
 * the profile prints it as a value and never as a `tel:` link.
 *
 * @param int $user_id Optional. Defaults to the current user.
 * @return string Phone number as it should be displayed, '' when not known.
 */
function ensurance_dashboard_agency_phone( $user_id = 0 ) {
    $user_id = $user_id ? (int) $user_id : get_current_user_id();
    $sample  = ensurance_dashboard_sample_agency();
    $phone   = ( 'quiet' === ensurance_dashboard_priority_preview() ) ? $sample['phone'] : '';

    /**
     * Filter the agency phone number shown on the Agency Profile view.
     *
     * @param string $phone   Phone number, '' when unknown.
     * @param int    $user_id User the profile is being resolved for.
     */
    return (string) apply_filters( 'ensurance_dashboard_agency_phone', $phone, $user_id );
}

/**
 * The name of the PERSON the account belongs to, as shown on Agency Profile.
 *
 * Distinct from ensurance_dashboard_agency_name(), which names the BUSINESS and
 * is what the rail's user card, its initials and the quiet panel all greet. The
 * profile shows both because they answer different questions — who we have on
 * file as the agent, and which agency they are matched as — and support needs to
 * be told which of the two is wrong when one of them is.
 *
 * FIRST AND LAST NAME, AND NOTHING ELSE. Those are the two fields
 * /create-account collects for the person, and they are the answer this chip is
 * asked for. There is no fallback to display_name or user_login on purpose:
 * display_name is a WordPress setting an agent never chose and user_login is a
 * username — printing either under "Agent name" would state as the agent's name
 * something that is not it. An account with neither half filled in resolves to ''
 * and the chip reads "Not on file", which is the truth and is what the locked
 * notice sends them to support about.
 *
 * IT IS NOT THE AGENCY'S NAME, and the profile must never print it as though it
 * were: the agency chip beside this one reads
 * ensurance_dashboard_agency_record_name(), which holds nothing today and says so,
 * rather than the greeting fallback that would echo this value back under the
 * other label.
 *
 * @param int $user_id Optional. Defaults to the current user.
 * @return string "First Last", or '' when the user record carries neither.
 */
function ensurance_dashboard_agent_name( $user_id = 0 ) {
    $user = $user_id ? get_userdata( (int) $user_id ) : wp_get_current_user();

    if ( ! ( $user instanceof WP_User ) || empty( $user->ID ) ) {
        return '';
    }

    // One half on its own is still the name we hold — the collapse keeps a
    // missing first or last name from leaving a stray space in the chip.
    $name = trim( preg_replace( '/\s+/', ' ', $user->first_name . ' ' . $user->last_name ) );

    /**
     * Filter the agent's own name on the Agency Profile view.
     *
     * @param string  $name The resolved name.
     * @param WP_User $user The user it was resolved from.
     */
    return (string) apply_filters( 'ensurance_dashboard_agent_name', $name, $user );
}

/**
 * The labeled values at the top of the Agency Profile view.
 *
 * Step 13 of templates/agent-dashboard/build-steps.md: the identifying half of
 * the agency record, over the service areas and coverage types that follow it.
 *
 * FOUR OF THEM ARE PROMISED. Agent name, agency name, phone and email are what
 * this view exists to confirm, so they hold their slot whether or not anything
 * can resolve them — a chip reading "Not on file" tells an agent that we do not
 * have their phone number, which is precisely the thing the locked notice below
 * asks them to message support about. License number is the exception: it is
 * verified out of band and is not part of what the view promises, so it appears
 * only when it is known (see ensurance_dashboard_license_number).
 *
 * STRINGS, NOT A FORM. This resolver returns values to print, and it says nothing
 * about how they are rendered. Since Step 6 of the setup flow the component draws
 * ONE of them — the agency name — as a real input, and every other field as the
 * static box the step asks for: no Save, and emphatically not a disabled form,
 * which would be a dead affordance on every field.
 *
 * NOTHING NEW IS RESOLVED HERE, the same rule Today's reference column follows
 * (see ensurance_dashboard_shopper_rows): the name and inbox come from the
 * resolvers that already own them, so the profile, the rail's user card and the
 * quiet panel's sentence can never disagree about the agency they describe.
 *
 * "NOT ON FILE" IS A STATEMENT, NOT A PROMPT — as true of the empty chips as of
 * the filled ones: there is nothing to click on one and nothing to type into it.
 * The agency-name field is the exception the component makes, and it makes it
 * properly: an input with a placeholder, not a chip with placeholder text in it.
 *
 * SHAPE — a list of ['key' => …, 'label' => …, 'value' => …, 'empty' => bool] in
 * display order. A promised field with nothing to resolve carries the placeholder
 * as its `value` and `empty` true, which is what the component renders in the
 * faint shade. Any other field with no value is DROPPED rather than rendered
 * blank.
 *
 * Labels are the design's own (the `isProf` view of
 * templates/agent-dashboard/AgentDashboard.dc.html), except the inbox: the design
 * calls it "Request inbox", and this view says "Email" because on a record of who
 * we have on file it sits beside a name and a phone number and is read as the
 * agency's address. The value is unchanged — it is still the inbox an accepted
 * request's contact details are sent to (ensurance_dashboard_request_inbox), and
 * Today's accepted panel still names it in those words.
 *
 * @param int $user_id Optional. Defaults to the current user.
 * @return array<int,array{key:string,label:string,value:string,empty:bool}>
 */
function ensurance_dashboard_profile_fields( $user_id = 0 ) {
    $user_id = $user_id ? (int) $user_id : get_current_user_id();

    // What a promised field says when nothing resolves it. One phrase for all
    // four, because four different ways of saying "we do not have this" would
    // read as four different problems.
    $unknown = 'Not on file';

    $fields = array(
        array(
            'key'     => 'agent',
            'label'   => 'Agent name',
            'value'   => ensurance_dashboard_agent_name( $user_id ),
            'promise' => true,
        ),
        array(
            'key'   => 'name',
            'label' => 'Agency name',
            /*
             * The RECORD resolver, not the greeting one. The rail's card falls
             * back to the user's own name so it has something to say hello to;
             * doing that here printed the agent's name twice under two labels,
             * with nothing to tell an agent that we hold no agency name at all.
             * See ensurance_dashboard_agency_record_name().
             */
            'value'   => ensurance_dashboard_agency_record_name( $user_id ),
            'promise' => true,
        ),
        array(
            'key'     => 'phone',
            'label'   => 'Phone',
            'value'   => ensurance_dashboard_agency_phone( $user_id ),
            'promise' => true,
        ),
        array(
            'key'     => 'inbox',
            'label'   => 'Email',
            'value'   => ensurance_dashboard_request_inbox( $user_id ),
            'promise' => true,
        ),
        array(
            'key'   => 'license',
            'label' => 'License number',
            'value' => ensurance_dashboard_license_number( $user_id ),
        ),
    );

    /**
     * Filter the labeled values on the Agency Profile view.
     *
     * The hook the real agency record attaches to when it exists. Fields must
     * keep the shape documented above. A field marked `promise` keeps its slot
     * with a "not on file" placeholder when it has no value; any other field
     * with no value is dropped rather than rendered blank.
     *
     * @param array $fields  Fields in display order.
     * @param int   $user_id User the profile is being resolved for.
     */
    $fields = (array) apply_filters( 'ensurance_dashboard_profile_fields', $fields, $user_id );

    $clean = array();

    foreach ( $fields as $i => $field ) {
        if ( empty( $field['label'] ) ) {
            continue;
        }

        $value = isset( $field['value'] ) ? (string) $field['value'] : '';
        $empty = ( '' === trim( $value ) );

        if ( $empty && empty( $field['promise'] ) ) {
            continue;
        }

        $clean[] = array(
            'key'   => isset( $field['key'] ) ? (string) $field['key'] : (string) $i,
            'label' => (string) $field['label'],
            'value' => $empty ? $unknown : $value,
            'empty' => $empty,
        );
    }

    return $clean;
}

/**
 * The agency name the RECORD holds — the company name given at sign-up, and the
 * one the agent typed on the profile after it.
 *
 * Step 6 of the setup flow (design_handoff_agency_profile/SETUP-FLOW.md): the
 * name has to survive a reload, which means it has to be read from somewhere.
 * That somewhere already exists — ENSURANCE_COMPANY_META (`_ensurance_company_name`),
 * written by ensurance_remember_company_name() from the `company` field on
 * /create-account and read by ensurance_get_company_name(). It is the same value
 * Make matches an agent row on, so the profile edits the record the rest of the
 * funnel already uses rather than opening a second one beside it.
 *
 * A FILTER, NOT AN EDIT, per CLAUDE.md's standing rule and per the invitation in
 * ensurance_dashboard_agency_record_name()'s own docblock ("point the filter at
 * the real agency record when it exists"). This is that record.
 *
 * THE PREVIEW STILL WINS. A non-empty $name means `?slot=quiet` resolved the
 * sample agency, and the admin preview is supposed to show the record as the
 * design draws it — so the stored name fills the gap rather than overriding it.
 * Outside the preview $name is always '', so a real agent sees their own.
 *
 * @param string $name    Name resolved so far ('' for a real agent).
 * @param int    $user_id User the agency is being resolved for.
 * @return string
 */
function ensurance_dashboard_recorded_agency_name( $name, $user_id ) {
    if ( '' !== trim( (string) $name ) ) {
        return $name;
    }

    return ensurance_get_company_name( $user_id );
}
add_filter( 'ensurance_dashboard_agency_record_name', 'ensurance_dashboard_recorded_agency_name', 10, 2 );

/**
 * Where the Agency Profile's name field posts (raw — esc_url at output).
 *
 * The profile view's own URL, so the save lands back on the view the agent was
 * looking at rather than on Today. Resolved through
 * ensurance_dashboard_profile_url() for the reason that function exists: the rail
 * registry owns every view's href, and a second URL written by hand is a second
 * thing to keep in step.
 *
 * `?slot=` is deliberately NOT carried, unlike the live card's
 * ensurance_dashboard_decision_action(). A decision made under the preview is a
 * previewed decision and has to stay one; a NAME is written to the real record
 * whatever the page was previewing, so the post lands on the plain profile where
 * the value that was just saved is the value on screen.
 *
 * @return string
 */
function ensurance_dashboard_agency_name_action() {
    return ensurance_dashboard_profile_url();
}

/**
 * Saves the agency name posted from the Agency Profile view.
 *
 * Step 6 of the setup flow. THE EXISTING MUTATION: update_user_meta() against
 * ENSURANCE_COMPANY_META, the key ensurance_remember_company_name() already
 * writes at sign-up — no endpoint, no REST route, no second store.
 *
 * AN ORDINARY FORM POST, modelled on ensurance_dashboard_handle_decision(): the
 * design has no Save button, so assets/dashboard.js submits this form on blur and
 * on Enter, and with JavaScript off Enter in the single-field form still submits
 * it natively. Post-redirect-get, so a refresh cannot re-save and the confirmation
 * arrives on a clean URL.
 *
 * WHAT IT DOES NOT DO, because the step is explicit that the app's rules win:
 * there is no length rule, no character rule and no format rule here. The value
 * gets sanitize_text_field() and trim() — exactly what the sign-up saver applies
 * — and nothing else.
 *
 * EMPTY AFTER TRIM SAVES NOTHING. It is not a delete: a field cleared by accident
 * (or by a browser restoring a blank) must not wipe a name support may have put
 * there. The redirect re-renders the stored value, which is the revert. The script
 * does the same thing without the round trip.
 *
 * UNCHANGED SAVES NOTHING EITHER, and reports nothing: blur fires every time focus
 * leaves the field, and a "saved" line after a visit where nothing was typed would
 * be a claim about a write that never happened.
 *
 * A failed or expired nonce returns WITHOUT saving and WITHOUT redirecting — the
 * same choice the decision handler makes, and the same reason: the view renders
 * again showing the stored name, which is the truth, instead of WordPress's "link
 * expired" interstitial.
 */
function ensurance_dashboard_handle_agency_name() {
    // Cheapest test first — this runs on every front-end request.
    if ( ! isset( $_POST['dash_agency_name'] ) || ! is_page( 'dashboard' ) || ! is_user_logged_in() ) {
        return;
    }

    $nonce = isset( $_POST['dash_agency_nonce'] ) ? sanitize_text_field( wp_unslash( $_POST['dash_agency_nonce'] ) ) : '';

    if ( ! wp_verify_nonce( $nonce, 'ensurance_dashboard_agency_name' ) ) {
        return;
    }

    $user_id = get_current_user_id();
    $target  = ensurance_dashboard_agency_name_action();

    /*
     * A SAVE POSTED FROM THE PREVIEW WRITES NOTHING. Under `?slot=quiet` the field
     * is showing the sample agency (ensurance_dashboard_sample_agency), not this
     * account's record, and posting it would file "Coastline Insurance Group"
     * against a real user. Same rule the decided slot follows: what a preview
     * produces stays a preview. The redirect still lands on the plain profile, so
     * what the reviewer sees next is their own record.
     *
     * The marker is a hidden field rather than a `?slot=` read, because the form's
     * action deliberately drops the preview arg
     * (ensurance_dashboard_agency_name_action) — by the time this runs there is
     * nothing in the URL left to tell.
     */
    if ( ! empty( $_POST['dash_agency_preview'] ) ) {
        wp_safe_redirect( $target );
        exit;
    }

    $posted = trim( sanitize_text_field( wp_unslash( $_POST['dash_agency_name'] ) ) );
    $stored = ensurance_get_company_name( $user_id );

    if ( '' !== $posted && $posted !== $stored ) {
        update_user_meta( $user_id, ENSURANCE_COMPANY_META, $posted );

        // Carried in the URL rather than in a session flash, the way the decided
        // slot carries its own outcome: the confirmation belongs to the page the
        // redirect lands on, and nothing else has to be stored to show it.
        $target = add_query_arg( 'saved', 'name', $target );
    }

    wp_safe_redirect( $target );
    exit;
}
add_action( 'template_redirect', 'ensurance_dashboard_handle_agency_name' );

/**
 * Whether this request is the landing after a name was saved.
 *
 * Read-only presentation state — no side effects, so no nonce to verify. The
 * value is compared against one known word, so an arbitrary `?saved=` cannot
 * reach the page as anything but false.
 *
 * @return bool
 */
function ensurance_dashboard_agency_name_saved() {
    if ( empty( $_GET['saved'] ) || ! is_string( $_GET['saved'] ) ) {
        return false;
    }

    return ( 'name' === sanitize_key( wp_unslash( $_GET['saved'] ) ) );
}

/**
 * User-meta key holding the states an agency writes in.
 *
 * ONE COMMA-SEPARATED LINE — "California,Texas,Nevada" — which is the shape
 * ensurance_dashboard_served_states_csv() published into the page as a hidden
 * field while the storage behind it was still being written. No JSON, no
 * separate table: the list is short, closed
 * (ensurance_dashboard_us_states) and only ever read whole.
 *
 * INTERIM STORE, in the same sense as ENSURANCE_DASHBOARD_DECISION_META. The real
 * home for served states is whatever eventually matches a request against an
 * agency; when that exists it takes over through the
 * `ensurance_dashboard_service_areas` filter and this can go, with no change to
 * anything that reads it.
 */
if ( ! defined( 'ENSURANCE_DASHBOARD_STATES_META' ) ) {
    define( 'ENSURANCE_DASHBOARD_STATES_META', '_ensurance_served_states' );
}

/**
 * The canonical name for a state, '' when it is not one of the 50 plus DC.
 *
 * THE CLOSED LIST IS THE VALIDATION, and this is where a value meets it: whatever
 * arrives — from the select, from a hand-built post, from a meta row written
 * years ago — is either one of our states or it is nothing. "california" and
 * " California " come back as "California"; "Califnoria" comes back as '' and is
 * dropped rather than stored as a place that does not exist.
 *
 * The inverse of ensurance_dashboard_state_code(), and built on it, so there is
 * one matcher and not two.
 *
 * @param string $name Name as it arrived.
 * @return string The state's name as this product spells it, or ''.
 */
function ensurance_dashboard_state_name( $name ) {
    $code   = ensurance_dashboard_state_code( $name );
    $states = ensurance_dashboard_us_states();

    return isset( $states[ $code ] ) ? $states[ $code ] : '';
}

/**
 * The states this agent has stored, in the order they were added.
 *
 * Every name is put back through ensurance_dashboard_state_name(), so a row that
 * predates the closed list — or one a human edited — cannot put a state on the
 * profile that the picker would never have offered. Duplicates collapse for the
 * same reason: the chips are a set, however the meta got written.
 *
 * ORDER IS INSERTION ORDER, not alphabetical. The chips are a record of what the
 * agent did, and a list that reshuffles itself after every add makes the one that
 * was just added the hardest to find.
 *
 * @param int $user_id Optional. Defaults to the current user.
 * @return string[] State names, empty when none are stored.
 */
function ensurance_dashboard_stored_states( $user_id = 0 ) {
    $user_id = $user_id ? (int) $user_id : get_current_user_id();
    $stored  = (string) get_user_meta( $user_id, ENSURANCE_DASHBOARD_STATES_META, true );
    $states  = array();

    if ( '' === trim( $stored ) ) {
        return $states;
    }

    foreach ( explode( ',', $stored ) as $piece ) {
        $name = ensurance_dashboard_state_name( $piece );

        if ( '' !== $name && ! in_array( $name, $states, true ) ) {
            $states[] = $name;
        }
    }

    return $states;
}

/**
 * Hands the stored states to the resolver the whole product already reads.
 *
 * Step 7 of the setup flow (design_handoff_agency_profile/SETUP-FLOW.md). THE ONE
 * SEAM: ensurance_dashboard_service_areas() is what
 * ensurance_dashboard_served_states(), the profile's chips, the picker's
 * remaining choices, the setup checklist and therefore
 * ensurance_dashboard_can_receive_leads() all reduce to. Attaching storage here
 * means matching switches on and off with the list and nothing else had to be
 * told about it — including Today's setup card, which reads the same value.
 *
 * A FILTER, NOT AN EDIT, per CLAUDE.md's standing rule and per that resolver's own
 * docblock ("the hook the real agency profile attaches to when it exists").
 *
 * THE PREVIEW STILL WINS, exactly as it does for the agency name: a non-empty
 * $areas means `?slot=quiet` resolved the sample, and the preview is there to show
 * the view populated. Outside it $areas is always empty, so a real agent sees
 * their own list.
 *
 * @param string[] $areas   Areas resolved so far (the sample, or empty).
 * @param int      $user_id User the dashboard is being rendered for.
 * @return string[]
 */
function ensurance_dashboard_stored_service_areas( $areas, $user_id ) {
    if ( ! empty( $areas ) ) {
        return $areas;
    }

    return ensurance_dashboard_stored_states( $user_id );
}
add_filter( 'ensurance_dashboard_service_areas', 'ensurance_dashboard_stored_service_areas', 10, 2 );

/**
 * Answers a states post — 204 to the script, a redirect to a browser.
 *
 * TWO CALLERS, ONE HANDLER. The picker sends its add and remove with fetch, so the
 * page it is already on stays put and the chips do not blink; a browser with no
 * fetch submits the same form the ordinary way and needs to be sent somewhere.
 * The difference is one hidden field, and it changes nothing about what was
 * written — this is not a second endpoint, it is the same post answered in the
 * shape its sender can read.
 *
 * A REFUSAL IS A REFUSAL EITHER WAY. 403 tells the script the change did not
 * land, which is what makes it put the chip back and say so. The form path
 * returns without redirecting instead — the view renders again from the record,
 * which is the same correction stated by simply showing the truth. That is the
 * choice ensurance_dashboard_handle_decision() makes on a bad nonce, and for the
 * same reason.
 *
 * ALWAYS EXITS on the fetch path, and on a successful form post.
 *
 * @param bool $ok Whether the change was accepted.
 */
function ensurance_dashboard_states_response( $ok ) {
    if ( ! empty( $_POST['dash_states_async'] ) ) {
        status_header( $ok ? 204 : 403 );
        exit;
    }

    if ( ! $ok ) {
        return;
    }

    wp_safe_redirect( ensurance_dashboard_profile_url() );
    exit;
}

/**
 * Adds or removes one served state.
 *
 * Step 7 of the setup flow. THE EXISTING MUTATION, in the sense the handoff means
 * it: one update_user_meta() against the agency record, no endpoint and no REST
 * route — the profile posts to the page it is on, the way Today's Accept / Pass
 * buttons do (ensurance_dashboard_handle_decision).
 *
 * AN INTENT, NOT A SNAPSHOT. The post says "add California" or "remove Texas"; it
 * never sends the whole list. Two changes made in quick succession therefore both
 * land, in either order, and a stale page cannot overwrite the record with the
 * list as it looked a minute ago. The hidden CSV field keeps its documented job —
 * publishing the current list into the page — and is no longer what saves.
 *
 * REMOVE OUTRANKS ADD, because the no-script path posts both: a chip's × is a
 * submit button inside the same form as the select, so pressing it sends whatever
 * the select happens to be showing alongside it. One of the two has to win and it
 * is the button the agent actually pressed.
 *
 * NO-OPS ARE SUCCESSES, not errors: nothing selected, a state already served, a
 * state removed that was not there, a name that is not one of ours. The design's
 * rule is that the picker cannot offer a wrong choice
 * (ensurance_dashboard_state_choices removes what is already served), so a
 * duplicate arriving here is a stale page or a hand-built post — and the record
 * ends up in the state the agent asked for either way, which is the definition of
 * nothing having gone wrong.
 *
 * LICENSING IS NOT CHECKED HERE, and must not be. Verification is server-side
 * truth held elsewhere (see the closing note on the view): this never blocks a
 * state, never marks one verified, and never says anything about one. It records
 * what the agent claims to write in.
 *
 * A failed or expired nonce writes nothing and says so (403 / no redirect).
 */
function ensurance_dashboard_handle_states() {
    $adding   = isset( $_POST['dash_state_add'] );
    $removing = isset( $_POST['dash_state_remove'] );

    // Cheapest test first — this runs on every front-end request.
    if ( ( ! $adding && ! $removing ) || ! is_page( 'dashboard' ) || ! is_user_logged_in() ) {
        return;
    }

    $nonce = isset( $_POST['dash_states_nonce'] ) ? sanitize_text_field( wp_unslash( $_POST['dash_states_nonce'] ) ) : '';

    if ( ! wp_verify_nonce( $nonce, 'ensurance_dashboard_states' ) ) {
        ensurance_dashboard_states_response( false );
        return;
    }

    /*
     * A CHANGE MADE IN THE PREVIEW WRITES NOTHING. Under `?slot=quiet` the chips
     * are the sample agency's, not this account's, so adding to them would file
     * the sample against a real user — the same rule the agency name follows
     * (ensurance_dashboard_handle_agency_name). The script does not even send the
     * request; this is the guard for the form path, which cannot know.
     */
    if ( ! empty( $_POST['dash_states_preview'] ) ) {
        ensurance_dashboard_states_response( true );
        return;
    }

    $user_id = get_current_user_id();
    $states  = ensurance_dashboard_stored_states( $user_id );

    if ( $removing ) {
        $target = ensurance_dashboard_state_name( sanitize_text_field( wp_unslash( $_POST['dash_state_remove'] ) ) );
        $states = array_values( array_diff( $states, array( $target ) ) );
    } else {
        $target = ensurance_dashboard_state_name( sanitize_text_field( wp_unslash( $_POST['dash_state_add'] ) ) );

        // '' is the placeholder option, or a name that is not one of ours.
        if ( '' !== $target && ! in_array( $target, $states, true ) ) {
            $states[] = $target;
        }
    }

    // Written even when the list did not change: the value stored is the value
    // resolved, and a write of the same line costs nothing. Removing the last
    // state stores '' — an empty list is a record, not a missing one, and it is
    // what turns matching back off (ensurance_dashboard_can_receive_leads).
    update_user_meta( $user_id, ENSURANCE_DASHBOARD_STATES_META, implode( ',', $states ) );

    ensurance_dashboard_states_response( true );
}
add_action( 'template_redirect', 'ensurance_dashboard_handle_states' );

/**
 * Rewrites the quiet panel's sentence now that the areas it names are STATES.
 *
 * Step 8 of the setup flow (design_handoff_agency_profile/SETUP-FLOW.md): when
 * matching turns on, the panel that reports it has to name what it turned on FOR
 * — the states the agent just set, and the inbox a match will be emailed to.
 * ensurance_dashboard_quiet_panel() writes that sentence with the county
 * vocabulary the dashboard was built on ("from Coastal, Ventura and Santa Barbara
 * counties"), which since Step 7 renders as "from California and Texas counties":
 * the one sentence confirming that matching is on, describing places that do not
 * exist.
 *
 * A FILTER, NOT AN EDIT, per CLAUDE.md's standing rule — and the sentence is
 * rebuilt whole rather than patched, because it is one sentence: only the middle
 * clause changes, but a str_replace on "counties" would leave this depending on
 * the exact words of a string it does not own. The coverage clause and the inbox
 * clause are therefore restated here, and both keep their fallbacks — nothing in
 * the sentence is allowed to render as a blank.
 *
 * THE CLOSING LINE CHANGES TOO. It read "message agent support and we will update
 * your counties or coverage types", which was true while the whole agency record
 * was read-only and is now a misdirection: states are the one thing the agent
 * sets themselves, on the profile. It still says support for coverage types,
 * which they cannot. It stays PLAIN TEXT — the handoff's Step 8 rules out an
 * add-a-state affordance in this panel, and naming where the door is does not
 * open one here.
 *
 * @param array $panel   The panel's copy.
 * @param int   $user_id User the panel is being rendered for.
 * @return array
 */
function ensurance_dashboard_quiet_panel_states( $panel, $user_id ) {
    $states    = ensurance_dashboard_served_states( $user_id );
    $coverages = ensurance_dashboard_coverage_types( $user_id );
    $inbox     = ensurance_dashboard_request_inbox( $user_id );

    // WHAT is being matched — the design's own phrasing, lowercased because it
    // runs mid-sentence (see ensurance_dashboard_quiet_panel).
    $kinds = ! empty( $coverages )
        ? sprintf( 'every %s request', wp_sprintf( '%l', array_map( 'strtolower', $coverages ) ) )
        : 'every request';

    // …and WHERE from. No trailing noun: a state's name is already the whole
    // answer, which is exactly what "counties" had to be added for.
    $where = ! empty( $states )
        ? sprintf( 'from %s', wp_sprintf( '%l', $states ) )
        : 'matched to your states';

    // …and WHERE IT GOES. The panel promising an email is only useful if the
    // agent knows which inbox to watch.
    $lands = ( '' !== $inbox )
        ? sprintf( 'Nothing is required of you until one lands — we email %s the moment it does.', $inbox )
        : 'Nothing is required of you until one lands — we email you the moment it does.';

    $panel['body'] = sprintf( 'You are in the running for %s %s. %s', $kinds, $where, $lands );
    $panel['note'] = 'To widen what reaches you, add states on your agency profile — or message agent support to change your coverage types.';

    return $panel;
}
add_filter( 'ensurance_dashboard_quiet_panel', 'ensurance_dashboard_quiet_panel_states', 10, 2 );

/**
 * The design's own sample ACCOUNT values — a card and a password age.
 *
 * PREVIEW ONLY, and gated exactly like ensurance_dashboard_sample_agency(): the
 * Account view's rows fall back to these under `?slot=quiet`, which is the state
 * in which the whole agent record is populated, so one URL shows the view as the
 * design draws it. ensurance_dashboard_priority_preview() is capability-gated, so
 * a real agent can never be shown them.
 *
 * `password` is an AGE in days rather than a date, because the design's own value
 * is one ("password last changed 18 days ago") and a fixed date would drift out
 * of that sentence the week after it was written.
 *
 * Copied field for field from the `isAcct` view of
 * templates/agent-dashboard/AgentDashboard.dc.html.
 *
 * @return array{payment:string,password:int}
 */
function ensurance_dashboard_sample_account() {
    return array(
        'payment'  => 'Visa •••• 4242 — expires 09/28',
        'password' => 18,
    );
}

/**
 * The card the subscription is billed to, written the way it should read.
 *
 * A CARD IS REQUIRED to take founding access — the 60 days are free, the payment
 * method is not optional — so every agent looking at this row has one. Nothing in
 * the theme can reach it yet (whatever takes the card at sign-up owns that
 * record), so this returns '' and the Account view DROPS the row rather than
 * describing a card it cannot see. Same rule as the profile's license and phone
 * chips: a labeled blank on a read-only record reads as data that has gone
 * missing, and a sentence in its place would be this file guessing at the state
 * of someone's billing.
 *
 * A DISPLAY STRING, not card data. Whatever fills this in (Stripe, most likely)
 * should return the same already-redacted summary the design writes — brand, last
 * four, expiry — because nothing on this page has any business handling more of a
 * card than that.
 *
 * @param int $user_id Optional. Defaults to the current user.
 * @return string Payment method summary, '' when there is none on file.
 */
function ensurance_dashboard_payment_method( $user_id = 0 ) {
    $user_id = $user_id ? (int) $user_id : get_current_user_id();
    $sample  = ensurance_dashboard_sample_account();
    $payment = ( 'quiet' === ensurance_dashboard_priority_preview() ) ? $sample['payment'] : '';

    /**
     * Filter the payment method shown on the Account view.
     *
     * @param string $payment Redacted payment method summary, '' when none.
     * @param int    $user_id User the account is being resolved for.
     */
    return (string) apply_filters( 'ensurance_dashboard_payment_method', $payment, $user_id );
}

/**
 * The address an agent SIGNS IN with, for the Account view's sign-in row.
 *
 * DELIBERATELY NOT ensurance_dashboard_request_inbox(), even though the two are
 * the same address on every account today. They answer different questions — this
 * one is "how do I get in", that one is "where do matched requests land" — and
 * the moment an agency points its request inbox at a shared address through the
 * filter, a sign-in row reading from it would be telling agents to log in with a
 * mailbox that has no account behind it.
 *
 * @param int $user_id Optional. Defaults to the current user.
 * @return string Email address, '' when there is no user or no address.
 */
function ensurance_dashboard_signin_email( $user_id = 0 ) {
    $user_id = $user_id ? (int) $user_id : get_current_user_id();
    $user    = $user_id ? get_userdata( $user_id ) : false;
    $email   = ( $user && ! empty( $user->user_email ) ) ? (string) $user->user_email : '';

    return is_email( $email ) ? $email : '';
}

/**
 * When the agent's password was last changed — the second half of the sign-in row.
 *
 * WORDPRESS DOES NOT RECORD THIS. The user record keeps the hash and nothing
 * about when it was set, so there is no honest value to return and this comes
 * back 0; the sign-in row then prints the address alone rather than guessing at
 * an age. Registration date is NOT a stand-in — it would silently claim every
 * agent last changed their password on the day they signed up.
 *
 * A MOMENT, not a written-out "18 days ago", so a cached render cannot go stale —
 * the same rule the timeline and the Recent column follow.
 *
 * A password-age plugin, or a reset flow that stamps its own user meta, points the
 * filter here and the row picks the clause back up with no other change.
 *
 * @param int $user_id Optional. Defaults to the current user.
 * @param int $now     Optional. Moment the sample age is measured back from.
 * @return int Unix timestamp, 0 when it is not known.
 */
function ensurance_dashboard_password_changed( $user_id = 0, $now = 0 ) {
    $user_id = $user_id ? (int) $user_id : get_current_user_id();
    $now     = $now ? (int) $now : time();
    $changed = 0;

    if ( 'quiet' === ensurance_dashboard_priority_preview() ) {
        $sample  = ensurance_dashboard_sample_account();
        $changed = $now - ( (int) $sample['password'] * DAY_IN_SECONDS );
    }

    /**
     * Filter when the agent's password was last changed.
     *
     * @param int $changed Unix timestamp, 0 when unknown.
     * @param int $user_id User the account is being resolved for.
     */
    return (int) apply_filters( 'ensurance_dashboard_password_changed', $changed, $user_id );
}

/**
 * Where the Account view's one action actually goes.
 *
 * THE END OF THE LINE. Every "change this" path in the product routes to agent
 * support, and ensurance_dashboard_support_url() sends all of them to this VIEW —
 * the profile's locked notice, the setup card's button, the quiet panel's closing
 * line. So the Message button here is the last link in that chain and has to reach
 * a human: /contact/, with the topic preselected so these arrive tagged as coming
 * from an agent (see $ct_topics in page-contact.php).
 *
 * Not ensurance_founding_agent_contact_url(), whose `founding` topic is for
 * PROSPECTIVE members coming off the /pricing-plans CTAs. An agent messaging from
 * inside the dashboard has already joined.
 *
 * @return string Raw URL — esc_url at output.
 */
function ensurance_dashboard_support_contact_url() {
    $url = add_query_arg( 'topic', 'agent', home_url( '/contact/' ) );

    /**
     * Filter the destination behind the Account view's Message button.
     *
     * The hook for a real support desk (a help widget, a ticket form) when one
     * exists. Everything in the dashboard that says "message agent support" ends
     * up here, so changing it moves all of them.
     *
     * @param string $url Contact URL.
     */
    return (string) apply_filters( 'ensurance_dashboard_support_contact_url', $url );
}

/**
 * The Account view's ruled rows — access and billing, payment, sign-in, support.
 *
 * Step 14 of templates/agent-dashboard/build-steps.md, and the last of the three
 * views outside Today. It is the answer to "what am I signed up for, what happens
 * at the end of it, and how do I change any of it".
 *
 * DISPLAY-ONLY, WITH ONE EXCEPTION. The step is explicit: no Cancel, no Update, no
 * Change — the agent support row is the single row carrying an action. That is not
 * a placeholder for buttons that are coming; it is the product's actual shape
 * today (the scope note at the top of build-steps.md), and a Cancel button that
 * opened a contact form would be a worse lie than no button. So `action` is set on
 * exactly one row here, and the component that renders these has no other
 * interactive element in it.
 *
 * THE DATES COME FROM THE TIMELINE, never from a second calculation. Today's
 * founding access timeline (ensurance_dashboard_founding_timeline) owns the cancel
 * date and the first-charge date; this view reads the same two segments, so the
 * two surfaces cannot disagree about when an agent is charged. They are on
 * different views, which is what keeps Step 15's "no date in two places" intact —
 * the rule is about one screen, and an account page that hid its own billing dates
 * to satisfy it would be unusable.
 *
 * TENSE IS DERIVED. The segments carry their own status, so the sentence reads
 * "begins Sep 23" while the charge is ahead and "from Sep 23" once it is not,
 * and the cancel clause stops inviting a cancellation the window has closed on.
 * Every founding agent crosses that boundary on day 60.
 *
 * SHAPE — one entry per row, in display order:
 *   key    string  Stable slug, for filters targeting one row.
 *   title  string  The row's name.
 *   detail string  The line under it.
 *   action array   ['label' => …, 'url' => …], or empty for a display-only row.
 *
 * A row with no title or no detail is DROPPED rather than ruled off around a
 * blank — the same rule the profile's chips follow.
 *
 * @param int $user_id Optional. Defaults to the current user.
 * @param int $now     Optional. Moment to resolve against. Defaults to now.
 * @return array<int,array{key:string,title:string,detail:string,action:array}>
 */
function ensurance_dashboard_account_rows( $user_id = 0, $now = 0 ) {
    $user_id = $user_id ? (int) $user_id : get_current_user_id();
    $now     = $now ? (int) $now : time();

    // ── Founding access: what it costs, when that starts, and the window to
    // get out before it does. Built from the timeline's own two future
    // milestones so the dates are the same object Today shows.
    $mark   = array();
    $charge = array();

    foreach ( ensurance_dashboard_founding_timeline( $user_id, $now ) as $segment ) {
        if ( 'mark' === $segment['key'] ) {
            $mark = $segment;
        } elseif ( 'charge' === $segment['key'] ) {
            $charge = $segment;
        }
    }

    $sentences = array();

    // The design's `billNote` — "$29 / month begins Sep 23." The price rides on
    // the charge segment's note (ensurance_dashboard_access_price), so filtering
    // the price away drops the clause instead of printing a bare date.
    if ( ! empty( $charge['date'] ) && ! empty( $charge['note'] ) ) {
        $sentences[] = ( 'upcoming' === $charge['status'] )
            ? sprintf( '%s begins %s.', $charge['note'], $charge['date'] )
            : sprintf( '%s from %s.', $charge['note'], $charge['date'] );
    }

    if ( ! empty( $mark['date'] ) ) {
        $sentences[] = ( 'upcoming' === $mark['status'] )
            ? sprintf( 'Cancel before %s and you are never charged — message support and we will take care of it.', $mark['date'] )
            : sprintf( 'The cancel window closed %s — message support to make a change.', $mark['date'] );
    }

    // NO START DATE, NO DATES AT ALL — the timeline returns nothing without one
    // (a user record with no registration date), so the row falls back to the
    // terms themselves, which are true whether or not this account's dates are
    // known. It still says the two things that matter: it converts, and it can
    // be stopped before it does.
    if ( empty( $sentences ) ) {
        $price = ensurance_dashboard_access_price();

        $sentences[] = ( '' !== $price )
            ? sprintf(
                'Founding access runs %d days, then continues at %s. Cancel before it ends and you are never charged — message support and we will take care of it.',
                ensurance_dashboard_access_term(),
                $price
            )
            : sprintf(
                'Founding access runs %d days. Cancel before it ends and you are never charged — message support and we will take care of it.',
                ensurance_dashboard_access_term()
            );
    }

    $rows = array(
        array(
            'key'    => 'access',
            'title'  => 'Founding Agent Access',
            'detail' => implode( ' ', $sentences ),
        ),
    );

    // ── Payment method, when something can tell us what it is. A card is
    // required to take founding access, so an unresolved one is a gap in what
    // this theme can read rather than a fact about the account — the row waits
    // instead of characterizing it. See ensurance_dashboard_payment_method().
    $payment = ensurance_dashboard_payment_method( $user_id );

    if ( '' !== $payment ) {
        $rows[] = array(
            'key'    => 'payment',
            'title'  => 'Payment method',
            'detail' => $payment,
        );
    }

    // ── Sign-in. The address is the row; the password age is a clause that only
    // appears when something actually knows it.
    $email = ensurance_dashboard_signin_email( $user_id );

    if ( '' !== $email ) {
        $changed = ensurance_dashboard_password_changed( $user_id, $now );
        $detail  = $email;

        if ( $changed > 0 && $changed <= $now ) {
            $days = ensurance_dashboard_days_between( $changed, $now );

            if ( $days <= 0 ) {
                $aged = 'password last changed today';
            } elseif ( 1 === $days ) {
                $aged = 'password last changed yesterday';
            } else {
                $aged = sprintf( 'password last changed %d days ago', $days );
            }

            $detail = sprintf( '%s — %s', $email, $aged );
        }

        $rows[] = array(
            'key'    => 'signin',
            'title'  => 'Sign-in',
            'detail' => $detail,
        );
    }

    /*
     * ── Agent support, and the one row with an action.
     *
     * THE HOURS ARE NOT THE DESIGN'S. It writes "Weekdays 7am–5pm PT · typical
     * reply under 2 hours", and both halves of that are a promise nothing else in
     * the product makes: /contact states one to two business days, in its intro,
     * its trust cues, its FAQ answer and its success screen. Shipping a two-hour
     * SLA on the one surface an agent is told to use for everything — cancelling,
     * service areas, a locked profile — would set an expectation the team has
     * already said it does not meet, on the page where missing it costs the most.
     * So the row keeps its shape and states the reply time the rest of the site
     * states. Real staffed hours belong here through the filter below, alongside
     * a matching change to page-contact.php.
     */
    $rows[] = array(
        'key'    => 'support',
        'title'  => 'Agent support',
        'detail' => 'Messages are answered by email, usually within one to two business days.',
        'action' => array(
            'label' => 'Message',
            'url'   => ensurance_dashboard_support_contact_url(),
        ),
    );

    /**
     * Filter the Account view's rows.
     *
     * The hook a real subscription record attaches to. Rows must keep the shape
     * documented above; anything missing a title or a detail is dropped, and an
     * `action` without both a label and a URL is ignored rather than rendered as
     * a dead control.
     *
     * @param array $rows    Rows in display order.
     * @param int   $user_id User the account is being resolved for.
     * @param int   $now     Moment the rows were resolved against.
     */
    $rows = (array) apply_filters( 'ensurance_dashboard_account_rows', $rows, $user_id, $now );

    $clean = array();

    foreach ( $rows as $i => $row ) {
        if ( empty( $row['title'] ) || empty( $row['detail'] ) ) {
            continue;
        }

        $action = array();

        if ( ! empty( $row['action']['label'] ) && ! empty( $row['action']['url'] ) ) {
            $action = array(
                'label' => (string) $row['action']['label'],
                'url'   => (string) $row['action']['url'],
            );
        }

        $clean[] = array(
            'key'    => isset( $row['key'] ) ? (string) $row['key'] : (string) $i,
            'title'  => (string) $row['title'],
            'detail' => (string) $row['detail'],
            'action' => $action,
        );
    }

    return $clean;
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
            // Paid $29/mo path: a verified agent who logs in is routed here (via
            // ensurance_founding_plan_login_redirect), where the Stripe Checkout
            // launch route (page-founding-checkout.php →
            // ensurance_founding_checkout_start) creates a subscription Session
            // and hands off to Stripe. See section 2b-v-a5.
            'destination' => home_url( '/founding-checkout/' ),
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
// 2b-v-a5. FOUNDING AGENT — COMPANY NAME + STRIPE SUBSCRIPTION CHECKOUT
// ============================================================================
// Self-serve paid onboarding for the $29/mo "Join as a Founding Agent" plan.
//
// FLOW: /create-account?plan=monthly → account created (email-activation) → the
// agent verifies by email and logs in → ensurance_founding_plan_login_redirect
// sends them to /founding-checkout/ (the `monthly` destination) →
// ensurance_founding_checkout_start() creates a Stripe Checkout Session and
// redirects to Stripe → on payment, Stripe fires its events and MAKE reads the
// session metadata (company_name / first_name / last_name) to update the agent
// row → Stripe returns the browser to /dashboard/?checkout=success.
//
// WordPress is intentionally NOT in the Stripe→status loop (no webhook handler);
// the authoritative subscription status lives in Stripe/Make. The only local
// signal is a best-effort "_ensurance_subscription_active" flag set on the
// success return, used purely to avoid sending a paid agent back through
// checkout (which would open a SECOND subscription).
//
// CONFIG (per environment, in wp-config.php — never committed): define
// ENSURANCE_STRIPE_SECRET_KEY and ENSURANCE_STRIPE_PRICE_MONTHLY (Stripe TEST
// key + a test $29/mo recurring Price on staging; LIVE values on prod). Missing
// config bails gracefully to the manual contact URL.

/** User-meta key holding a new agent's company / agency name (Make match key). */
if ( ! defined( 'ENSURANCE_COMPANY_META' ) ) {
    define( 'ENSURANCE_COMPANY_META', '_ensurance_company_name' );
}

/** User-meta flag: this agent has completed Stripe checkout at least once. */
if ( ! defined( 'ENSURANCE_SUBSCRIPTION_ACTIVE_META' ) ) {
    define( 'ENSURANCE_SUBSCRIPTION_ACTIVE_META', '_ensurance_subscription_active' );
}

/**
 * Durably remember the company / agency name entered at sign-up.
 *
 * Hooked on uwp_after_custom_fields_save alongside ensurance_remember_founding_plan
 * (same rationale — it fires after the user row exists and still holds the raw
 * submitted $data). Kept as its OWN function rather than folded into the plan
 * saver, per the standing CLAUDE.md rule (new functions, not edits to existing
 * ones). `company` is not a UsersWP field, so UsersWP ignores it in validation
 * but passes it through in $data — exactly how the `plan` field flows.
 *
 * @param string $form_type 'register' | 'account' | ...
 * @param array  $data      raw submitted fields (includes our `company`)
 * @param array  $result    validated fields
 * @param int    $user_id   the new user's id
 */
function ensurance_remember_company_name( $form_type, $data, $result, $user_id ) {
    if ( 'register' !== $form_type || empty( $user_id ) ) {
        return;
    }
    if ( isset( $data['company'] ) && '' !== trim( (string) $data['company'] ) ) {
        update_user_meta( $user_id, ENSURANCE_COMPANY_META, sanitize_text_field( wp_unslash( $data['company'] ) ) );
    }
}
add_action( 'uwp_after_custom_fields_save', 'ensurance_remember_company_name', 10, 4 );

/** Read an agent's stored company / agency name ('' if none). */
function ensurance_get_company_name( $user_id ) {
    return trim( (string) get_user_meta( (int) $user_id, ENSURANCE_COMPANY_META, true ) );
}

/**
 * Mark the agent's subscription active on the Stripe success return.
 *
 * Best-effort only: WordPress is not on the Stripe webhook, so this is the local
 * signal that keeps a returning subscriber out of checkout (see the guard in
 * ensurance_founding_checkout_start). The source of truth stays Stripe/Make.
 * Runs on the /dashboard/?checkout=success landing.
 */
function ensurance_mark_subscription_active_on_return() {
    if ( ! is_user_logged_in() || ! is_page( 'dashboard' ) ) {
        return;
    }
    if ( ! isset( $_GET['checkout'] ) || 'success' !== $_GET['checkout'] ) {
        return;
    }
    update_user_meta( get_current_user_id(), ENSURANCE_SUBSCRIPTION_ACTIVE_META, 1 );
}
add_action( 'template_redirect', 'ensurance_mark_subscription_active_on_return' );

/**
 * Create a Stripe Checkout Session for the $29/mo plan and redirect to it.
 *
 * Called by page-founding-checkout.php before any output. ALWAYS exits: to
 * Stripe on success; to /login, /dashboard/, /pricing-plans/ or the manual
 * contact URL otherwise. Uses the Stripe REST API via wp_remote_post (the same
 * outbound idiom as the site's other integrations — no SDK to bundle). The
 * agent identifiers are stamped onto BOTH the session metadata and the
 * subscription metadata, so Make can match the agent row from whichever event
 * it watches.
 *
 * @return void  Never returns — every path calls exit.
 */
function ensurance_founding_checkout_start() {
    if ( ! is_user_logged_in() ) {
        wp_safe_redirect( add_query_arg( 'redirect_to', rawurlencode( home_url( '/founding-checkout/' ) ), home_url( '/login/' ) ) );
        exit;
    }

    $user = wp_get_current_user();

    // Already checked out once — never open a second subscription.
    if ( get_user_meta( $user->ID, ENSURANCE_SUBSCRIPTION_ACTIVE_META, true ) ) {
        wp_safe_redirect( home_url( '/dashboard/' ) );
        exit;
    }

    // Only the paid monthly plan checks out here.
    if ( 'monthly' !== ensurance_get_remembered_founding_plan( $user->ID ) ) {
        wp_safe_redirect( home_url( '/dashboard/' ) );
        exit;
    }

    // Config must be present; otherwise fall back to the manual contact path.
    if ( ! defined( 'ENSURANCE_STRIPE_SECRET_KEY' ) || ! ENSURANCE_STRIPE_SECRET_KEY
        || ! defined( 'ENSURANCE_STRIPE_PRICE_MONTHLY' ) || ! ENSURANCE_STRIPE_PRICE_MONTHLY ) {
        error_log( 'Ensurance founding checkout: ENSURANCE_STRIPE_SECRET_KEY / ENSURANCE_STRIPE_PRICE_MONTHLY not configured.' );
        wp_safe_redirect( ensurance_founding_agent_contact_url() );
        exit;
    }

    $company = ensurance_get_company_name( $user->ID );

    // Stripe expects application/x-www-form-urlencoded with bracket-notation keys;
    // wp_remote_post form-encodes an array body, and Stripe decodes the keys back.
    $body = array(
        'mode'                    => 'subscription',
        'line_items[0][price]'    => ENSURANCE_STRIPE_PRICE_MONTHLY,
        'line_items[0][quantity]' => 1,
        'customer_email'          => $user->user_email,
        'client_reference_id'     => (string) $user->ID,
        // Build these as plain strings, NOT via add_query_arg — Stripe needs the
        // literal {CHECKOUT_SESSION_ID} token (add_query_arg would URL-encode the
        // braces, and wp_remote_post's own encoding would then double-encode them,
        // so Stripe could not substitute the session id).
        'success_url'             => home_url( '/dashboard/?checkout=success&session_id={CHECKOUT_SESSION_ID}' ),
        'cancel_url'              => home_url( '/pricing-plans/?checkout=cancelled' ),
        // Match key for the Make scenario — on the session…
        'metadata[wp_user_id]'    => (string) $user->ID,
        'metadata[company_name]'  => $company,
        'metadata[first_name]'    => $user->first_name,
        'metadata[last_name]'     => $user->last_name,
        'metadata[plan]'          => 'monthly',
        // …and on the subscription, so it rides subscription/invoice events too.
        'subscription_data[metadata][wp_user_id]'   => (string) $user->ID,
        'subscription_data[metadata][company_name]' => $company,
        'subscription_data[metadata][first_name]'   => $user->first_name,
        'subscription_data[metadata][last_name]'    => $user->last_name,
        'subscription_data[metadata][plan]'         => 'monthly',
    );

    $response = wp_remote_post(
        'https://api.stripe.com/v1/checkout/sessions',
        array(
            'timeout' => 20,
            'headers' => array(
                'Authorization' => 'Bearer ' . ENSURANCE_STRIPE_SECRET_KEY,
                'Content-Type'  => 'application/x-www-form-urlencoded',
            ),
            'body'    => $body,
        )
    );

    if ( is_wp_error( $response ) ) {
        error_log( 'Ensurance founding checkout: Stripe request failed — ' . $response->get_error_message() );
        wp_safe_redirect( add_query_arg( 'checkout', 'error', home_url( '/pricing-plans/' ) ) );
        exit;
    }

    $code = (int) wp_remote_retrieve_response_code( $response );
    $data = json_decode( wp_remote_retrieve_body( $response ), true );

    if ( $code < 200 || $code >= 300 || empty( $data['url'] ) ) {
        $msg = isset( $data['error']['message'] ) ? $data['error']['message'] : 'unexpected response';
        error_log( 'Ensurance founding checkout: Stripe returned ' . $code . ' — ' . $msg );
        wp_safe_redirect( add_query_arg( 'checkout', 'error', home_url( '/pricing-plans/' ) ) );
        exit;
    }

    // External host → wp_redirect (wp_safe_redirect would reject stripe.com).
    wp_redirect( esc_url_raw( $data['url'] ) );
    exit;
}

// ============================================================================
// 2b-v-a6. FOUNDING AGENT — CREATE STARTER SHEET ROW ON EMAIL VERIFICATION
// ============================================================================
// A self-serve agent becomes a WordPress user at sign-up but has NO row in the
// auto_insurance_agents Google Sheet — and the Make "Agent_Signup" scenario that
// sets subscription_tier=Growth on payment is UPDATE-ONLY, so with no row it
// always fails. Fix: the moment a funnel agent verifies their email, fire a Make
// "Agent Row Upsert" webhook that creates the row as `Starter`; Stripe checkout
// later flips it to `Growth`. This also makes unpaid agents legitimately Starter,
// which is the unified lead-pricing model (see §2b-v-a5 / plans/).
//
// TIMING: hooked on uwp_email_activation_success, which UsersWP fires ONLY on a
// successful email-link activation (permalinks.php → uwp_process_activation_link),
// passing the user id. That handler wp_safe_redirect()s to /login immediately
// after the do_action (no auto-login), so this callback runs synchronously before
// the redirect — the POST is therefore NON-BLOCKING so it cannot delay it.
//
// CONFIG (wp-config.php, per environment — never committed):
// ENSURANCE_AGENT_UPSERT_WEBHOOK_URL = the Make custom-webhook URL. If unset, this
// logs and no-ops (activation still succeeds).

/** User-meta flag: the agent-row upsert webhook was already fired for this user. */
if ( ! defined( 'ENSURANCE_AGENT_ROW_NOTIFIED_META' ) ) {
    define( 'ENSURANCE_AGENT_ROW_NOTIFIED_META', '_ensurance_agent_row_notified' );
}

/**
 * On email-link activation of a founding-funnel agent, POST their identity to the
 * Make "Agent Row Upsert" webhook so a Starter auto_insurance_agents row exists.
 *
 * Fires for BOTH plans (monthly and free 60-day — anyone carrying the founding
 * plan meta); non-agent registrations are skipped. Fire-and-forget + a per-user
 * guard; the Make scenario upserts (match-or-add), so a repeat never duplicates a
 * row. New function only — no edits to existing ones (CLAUDE.md rule).
 *
 * @param int $user_id The user who just activated (the only arg UsersWP passes).
 */
function ensurance_notify_agent_row_on_activation( $user_id ) {
    $user_id = (int) $user_id;
    if ( ! $user_id ) {
        return;
    }

    // Email-link activation only (this hook also fires on admin manual activation).
    if ( ! isset( $_GET['uwp_activate'] ) || 'yes' !== $_GET['uwp_activate'] ) {
        return;
    }

    // Founding-funnel agents only — monthly OR 60-day carry the plan meta.
    $plan = ensurance_get_remembered_founding_plan( $user_id );
    if ( '' === $plan ) {
        return;
    }

    // Fire at most once per user (guards double-clicked activation links).
    if ( get_user_meta( $user_id, ENSURANCE_AGENT_ROW_NOTIFIED_META, true ) ) {
        return;
    }

    if ( ! defined( 'ENSURANCE_AGENT_UPSERT_WEBHOOK_URL' ) || ! ENSURANCE_AGENT_UPSERT_WEBHOOK_URL ) {
        error_log( 'Ensurance agent-row webhook: ENSURANCE_AGENT_UPSERT_WEBHOOK_URL not configured.' );
        return;
    }

    $user = get_userdata( $user_id );
    if ( ! $user ) {
        return;
    }

    $payload = array(
        'event'        => 'agent_signup_activated',
        'wp_user_id'   => $user_id,
        'company_name' => ensurance_get_company_name( $user_id ),
        'first_name'   => $user->first_name,
        'last_name'    => $user->last_name,
        'email'        => $user->user_email,
        'plan'         => $plan, // 'monthly' | '60-day'
        'tier'         => 'Starter',
    );

    // Fire-and-forget: the activation handler redirects immediately after this
    // hook returns, so do NOT block on the response.
    wp_remote_post(
        ENSURANCE_AGENT_UPSERT_WEBHOOK_URL,
        array(
            'blocking' => false,
            'timeout'  => 5,
            'headers'  => array( 'Content-Type' => 'application/json' ),
            'body'     => wp_json_encode( $payload ),
        )
    );

    update_user_meta( $user_id, ENSURANCE_AGENT_ROW_NOTIFIED_META, 1 );
}
add_action( 'uwp_email_activation_success', 'ensurance_notify_agent_row_on_activation', 10, 1 );

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
