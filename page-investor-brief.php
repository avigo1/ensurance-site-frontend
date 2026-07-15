<?php
/**
 * Template Name: Investor Brief (Private)
 *
 * Private investor brief — Calm Intelligence redesign. Uses dedicated
 * investor header/footer (NOT the public marketing header): the page is
 * noindex/nofollow and must not surface the marketing nav. Copy lives in
 * components/_investor-brief-data.php.
 *
 * Not in marketing nav. Not in sitemap. The request form is UI only — no
 * backend is wired yet (see the TODO in assets/investor.js).
 *
 * The design's Traction / "Proof to date" section is intentionally not
 * rendered: there is no verified data to populate it yet.
 */

$brief = require __DIR__ . '/components/_investor-brief-data.php';

// Inject title/OG meta via wp_head (template-local, no global side effects).
add_action( 'wp_head', function () use ( $brief ) {
    $m = $brief['meta'];
    echo '<title>' . esc_html( $m['title'] ) . '</title>' . "\n";
    echo '<meta name="description" content="' . esc_attr( $m['description'] ) . '">' . "\n";
    echo '<link rel="canonical" href="' . esc_url( $m['og_url'] ) . '">' . "\n";
    echo '<meta property="og:type" content="website">' . "\n";
    echo '<meta property="og:url" content="' . esc_url( $m['og_url'] ) . '">' . "\n";
    echo '<meta property="og:title" content="' . esc_attr( $m['og_title'] ) . '">' . "\n";
    echo '<meta property="og:description" content="' . esc_attr( $m['og_desc'] ) . '">' . "\n";
    echo '<meta name="twitter:card" content="summary">' . "\n";
    echo '<meta name="twitter:title" content="' . esc_attr( $m['og_title'] ) . '">' . "\n";
    echo '<meta name="twitter:description" content="' . esc_attr( $m['og_desc'] ) . '">' . "\n";
}, 1 );

// Inline Lucide glyphs (stroke 2, round caps) used on this page.
if ( ! function_exists( 'ensurance_investor_icon' ) ) {
    function ensurance_investor_icon( $name, $size = 20, $class = '' ) {
        $paths = array(
            'arrow-right'  => '<path d="M5 12h14"/><path d="m12 5 7 7-7 7"/>',
            'check'        => '<path d="M20 6 9 17l-5-5"/>',
            'shield-check' => '<path d="M20 13c0 5-3.5 7.5-7.66 8.95a1 1 0 0 1-.67-.01C7.5 20.5 4 18 4 13V6a1 1 0 0 1 1-1c2 0 4.5-1.2 6.24-2.72a1.17 1.17 0 0 1 1.52 0C14.51 3.81 17 5 19 5a1 1 0 0 1 1 1z"/><path d="m9 12 2 2 4-4"/>',
            'chevron-down' => '<path d="m6 9 6 6 6-6"/>',
        );
        if ( ! isset( $paths[ $name ] ) ) {
            return '';
        }
        return '<svg class="' . esc_attr( $class ) . '" width="' . (int) $size . '" height="' . (int) $size . '" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">' . $paths[ $name ] . '</svg>';
    }
}

$ib_svg_allowed = array(
    'svg'  => array( 'class' => true, 'width' => true, 'height' => true, 'viewbox' => true, 'fill' => true, 'stroke' => true, 'stroke-width' => true, 'stroke-linecap' => true, 'stroke-linejoin' => true, 'aria-hidden' => true ),
    'path' => array( 'd' => true ),
);

get_header( 'investor' );
?>

<main id="main" class="investor-brief">

    <?php $hero = $brief['hero']; ?>
    <section class="ib-hero" aria-labelledby="hero-title">
        <div class="ib-shell ib-hero__grid">

            <div class="ib-hero__copy">
                <p class="ib-eyebrow"><?php echo esc_html( $hero['eyebrow'] ); ?></p>
                <h1 id="hero-title"><?php echo esc_html( $hero['headline'] ); ?></h1>
                <p class="ib-hero__body"><?php echo esc_html( $hero['body'] ); ?></p>
                <div class="ib-hero__actions">
                    <button type="button" class="ib-btn ib-btn--primary" data-scroll-to="request-materials" data-event="hero_request_materials_click"><?php echo esc_html( $hero['cta_primary'] ); ?></button>
                    <button type="button" class="ib-btn ib-btn--secondary" data-scroll-to="cate" data-event="hero_review_cate_click"><?php echo esc_html( $hero['cta_secondary'] ); ?></button>
                </div>
                <p class="ib-hero__support"><?php echo esc_html( $hero['support'] ); ?></p>
                <div class="ib-hero__badges" aria-label="Investor brief trust signals">
                    <?php foreach ( $hero['badges'] as $badge ) : ?>
                        <span class="ib-badge"><?php echo esc_html( $badge ); ?></span>
                    <?php endforeach; ?>
                </div>
            </div>

            <div class="ib-card ib-card--inverse ib-thesis" aria-label="Investor brief summary">
                <p class="ib-eyebrow ib-eyebrow--inverse"><?php echo esc_html( $hero['thesis']['eyebrow'] ); ?></p>
                <h2 class="ib-thesis__title"><?php echo esc_html( $hero['thesis']['headline'] ); ?></h2>
                <ul class="ib-thesis__list">
                    <?php foreach ( $hero['thesis']['points'] as $point ) : ?>
                        <li><span class="ib-thesis__dot" aria-hidden="true"></span><span><?php echo esc_html( $point ); ?></span></li>
                    <?php endforeach; ?>
                </ul>
            </div>

        </div>
    </section>

    <?php $problem = $brief['problem']; ?>
    <section class="ib-section ib-shell" aria-labelledby="problem-title">
        <div class="ib-section__head">
            <p class="ib-eyebrow"><?php echo esc_html( $problem['eyebrow'] ); ?></p>
            <h2 id="problem-title"><?php echo esc_html( $problem['headline'] ); ?></h2>
            <p class="ib-section__intro"><?php echo esc_html( $problem['body'] ); ?></p>
        </div>
        <div class="ib-grid ib-grid--260">
            <?php foreach ( $problem['cards'] as $card ) : ?>
                <article class="ib-card ib-info-card">
                    <p class="ib-kicker"><?php echo esc_html( $card['kicker'] ); ?></p>
                    <h3><?php echo esc_html( $card['title'] ); ?></h3>
                    <p><?php echo esc_html( $card['body'] ); ?></p>
                </article>
            <?php endforeach; ?>
        </div>
    </section>

    <?php $shift = $brief['shift']; ?>
    <section class="ib-section ib-shell" aria-labelledby="shift-title">
        <div class="ib-section__head">
            <p class="ib-eyebrow"><?php echo esc_html( $shift['eyebrow'] ); ?></p>
            <h2 id="shift-title"><?php echo esc_html( $shift['headline'] ); ?></h2>
            <p class="ib-section__intro"><?php echo esc_html( $shift['body'] ); ?></p>
        </div>
        <div class="ib-card ib-card--lg ib-flow">
            <div class="ib-flow__track" aria-label="Ensurance workflow">
                <?php foreach ( $shift['steps'] as $i => $step ) : ?>
                    <span class="ib-flow__step"><?php echo esc_html( $step ); ?></span>
                    <?php if ( $i < count( $shift['steps'] ) - 1 ) : ?>
                        <?php echo wp_kses( ensurance_investor_icon( 'arrow-right', 16, 'ib-flow__arrow' ), $ib_svg_allowed ); ?>
                    <?php endif; ?>
                <?php endforeach; ?>
            </div>
        </div>
    </section>

    <?php $building = $brief['building']; ?>
    <section class="ib-section ib-shell" aria-labelledby="building-title">
        <div class="ib-section__head">
            <p class="ib-eyebrow"><?php echo esc_html( $building['eyebrow'] ); ?></p>
            <h2 id="building-title"><?php echo esc_html( $building['headline'] ); ?></h2>
            <p class="ib-section__intro"><?php echo esc_html( $building['body'] ); ?></p>
        </div>
        <div class="ib-grid ib-grid--230">
            <?php foreach ( $building['cards'] as $card ) : ?>
                <article class="ib-card ib-info-card">
                    <p class="ib-kicker"><?php echo esc_html( $card['kicker'] ); ?></p>
                    <h3><?php echo esc_html( $card['title'] ); ?></h3>
                    <p><?php echo esc_html( $card['body'] ); ?></p>
                </article>
            <?php endforeach; ?>
        </div>
    </section>

    <?php $cate = $brief['cate']; ?>
    <section class="ib-section ib-shell" aria-labelledby="cate-title">
        <div id="cate" class="ib-card ib-card--inverse ib-card--xl ib-cate">
            <div class="ib-cate__grid">
                <div>
                    <p class="ib-eyebrow ib-eyebrow--inverse"><?php echo esc_html( $cate['eyebrow'] ); ?></p>
                    <h2 id="cate-title" class="ib-cate__title"><?php echo esc_html( $cate['headline'] ); ?></h2>
                    <?php foreach ( $cate['paragraphs'] as $p ) : ?>
                        <p class="ib-cate__body"><?php echo esc_html( $p ); ?></p>
                    <?php endforeach; ?>
                </div>
                <div class="ib-cate__panel">
                    <p><?php echo esc_html( $cate['callout'] ); ?></p>
                </div>
            </div>
        </div>
    </section>

    <?php $model = $brief['model']; ?>
    <section class="ib-section ib-shell" aria-labelledby="model-title">
        <div class="ib-section__head">
            <p class="ib-eyebrow"><?php echo esc_html( $model['eyebrow'] ); ?></p>
            <h2 id="model-title"><?php echo esc_html( $model['headline'] ); ?></h2>
        </div>
        <div class="ib-grid ib-grid--230">
            <?php foreach ( $model['cards'] as $card ) : ?>
                <article class="ib-card ib-info-card">
                    <p class="ib-kicker"><?php echo esc_html( $card['kicker'] ); ?></p>
                    <h3><?php echo esc_html( $card['title'] ); ?></h3>
                    <p><?php echo esc_html( $card['body'] ); ?></p>
                </article>
            <?php endforeach; ?>
        </div>
    </section>

    <?php $raise = $brief['raise']; ?>
    <section class="ib-section ib-shell" aria-labelledby="raise-title">
        <div class="ib-card ib-card--brand ib-card--xl ib-raise">
            <div class="ib-raise__grid">
                <div>
                    <p class="ib-eyebrow"><?php echo esc_html( $raise['eyebrow'] ); ?></p>
                    <h2 id="raise-title"><?php echo esc_html( $raise['headline'] ); ?></h2>
                    <?php foreach ( $raise['paragraphs'] as $p ) : ?>
                        <p class="ib-section__intro"><?php echo esc_html( $p ); ?></p>
                    <?php endforeach; ?>
                </div>
                <div class="ib-raise__panel">
                    <p class="ib-kicker"><?php echo esc_html( $raise['funds_label'] ); ?></p>
                    <ul aria-label="Use of funds">
                        <?php foreach ( $raise['funds'] as $item ) : ?>
                            <li><?php echo wp_kses( ensurance_investor_icon( 'check', 16, 'ib-raise__check' ), $ib_svg_allowed ); ?><span><?php echo esc_html( $item ); ?></span></li>
                        <?php endforeach; ?>
                    </ul>
                </div>
            </div>
        </div>
    </section>

    <?php $milestones = $brief['milestones']; ?>
    <section class="ib-section ib-shell" aria-labelledby="prove-title">
        <div class="ib-section__head">
            <p class="ib-eyebrow"><?php echo esc_html( $milestones['eyebrow'] ); ?></p>
            <h2 id="prove-title"><?php echo esc_html( $milestones['headline'] ); ?></h2>
            <p class="ib-section__intro"><?php echo esc_html( $milestones['body'] ); ?></p>
        </div>
        <div class="ib-grid ib-grid--260">
            <?php foreach ( $milestones['cards'] as $card ) : ?>
                <article class="ib-card ib-info-card">
                    <p class="ib-kicker"><?php echo esc_html( $card['kicker'] ); ?></p>
                    <h3><?php echo esc_html( $card['title'] ); ?></h3>
                    <p><?php echo esc_html( $card['body'] ); ?></p>
                </article>
            <?php endforeach; ?>
        </div>
    </section>

    <?php $form = $brief['form']; ?>
    <section class="ib-section ib-section--last ib-shell" aria-labelledby="request-title">
        <div id="request-materials" class="ib-card ib-card--tint ib-card--xl ib-request">
            <div class="ib-request__grid">

                <div class="ib-request__intro">
                    <p class="ib-eyebrow"><?php echo esc_html( $form['eyebrow'] ); ?></p>
                    <h2 id="request-title"><?php echo esc_html( $form['headline'] ); ?></h2>
                    <?php foreach ( $form['paragraphs'] as $p ) : ?>
                        <p class="ib-section__intro"><?php echo esc_html( $p ); ?></p>
                    <?php endforeach; ?>
                    <div class="ib-callout">
                        <?php echo wp_kses( ensurance_investor_icon( 'shield-check', 18, 'ib-callout__icon' ), $ib_svg_allowed ); ?>
                        <p><?php echo esc_html( $form['callout'] ); ?></p>
                    </div>
                </div>

                <div class="ib-card ib-request__success" id="investorFormSuccess" hidden>
                    <span class="ib-badge ib-badge--ok"><span class="ib-badge__dot" aria-hidden="true"></span><?php echo esc_html( $form['success']['badge'] ); ?></span>
                    <h3><?php echo esc_html( $form['success']['headline'] ); ?></h3>
                    <p><?php echo esc_html( $form['success']['body'] ); ?></p>
                </div>

                <div class="ib-card ib-request__card" id="investorFormCard">
                    <form id="investorForm" action="#" method="post" novalidate>
                        <div class="ib-form__row">
                            <div class="ib-field">
                                <label for="investor-name">Name</label>
                                <input id="investor-name" name="name" type="text" autocomplete="name">
                            </div>
                            <div class="ib-field">
                                <label for="investor-email">Email</label>
                                <input id="investor-email" name="email" type="email" autocomplete="email">
                            </div>
                        </div>
                        <div class="ib-form__row">
                            <div class="ib-field">
                                <label for="investor-firm">Firm or organization</label>
                                <input id="investor-firm" name="firm" type="text" autocomplete="organization">
                            </div>
                            <div class="ib-field">
                                <label for="investor-type">Investor type</label>
                                <span class="ib-select">
                                    <select id="investor-type" name="investor_type">
                                        <option value="" disabled selected>Select one</option>
                                        <?php foreach ( $form['investor_types'] as $opt ) : ?>
                                            <option><?php echo esc_html( $opt ); ?></option>
                                        <?php endforeach; ?>
                                    </select>
                                    <?php echo wp_kses( ensurance_investor_icon( 'chevron-down', 18, 'ib-select__chevron' ), $ib_svg_allowed ); ?>
                                </span>
                            </div>
                        </div>
                        <div class="ib-field">
                            <label for="investor-background">Relevant background</label>
                            <textarea id="investor-background" name="background" rows="3"></textarea>
                            <p class="ib-field__help"><?php echo esc_html( $form['background_help'] ); ?></p>
                        </div>
                        <div class="ib-field">
                            <label for="investor-message">Message <span class="ib-field__optional">Optional</span></label>
                            <textarea id="investor-message" name="message" rows="3"></textarea>
                        </div>
                        <label class="ib-consent">
                            <input id="investorConsent" name="consent" type="checkbox">
                            <span class="ib-consent__box" aria-hidden="true">
                                <svg width="13" height="13" viewBox="0 0 24 24" fill="none" stroke="#fff" stroke-width="3" stroke-linecap="round" stroke-linejoin="round"><path d="M20 6 9 17l-5-5"/></svg>
                            </span>
                            <span><?php echo esc_html( $form['consent'] ); ?></span>
                        </label>
                        <button type="submit" class="ib-btn ib-btn--primary ib-btn--full" id="investorSubmit" disabled data-event="investor_request_submit">
                            <?php echo esc_html( $form['submit_label'] ); ?>
                        </button>
                    </form>
                </div>

            </div>
        </div>
    </section>

</main>

<?php get_footer( 'investor' ); ?>
