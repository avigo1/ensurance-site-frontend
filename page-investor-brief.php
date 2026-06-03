<?php
/**
 * Template Name: Investor Brief (Private)
 *
 * Private investor brief. Uses dedicated investor header/footer (NOT the
 * public marketing header) — this page is noindex/nofollow and should not
 * surface the marketing nav. Copy lives in components/_investor-brief-data.php.
 *
 * Not in marketing nav. Not in sitemap. Backend for the form is not wired —
 * see TODO in assets/investor.js.
 */

$brief = require __DIR__ . '/components/_investor-brief-data.php';

// Inject OG/meta tags via wp_head hook (template-local, no global side effects).
add_action( 'wp_head', function () use ( $brief ) {
    $m = $brief['meta'];
    echo '<title>' . esc_html( $m['title'] ) . '</title>' . "\n";
    echo '<meta name="description" content="' . esc_attr( $m['description'] ) . '">' . "\n";
    echo '<meta property="og:type" content="website">' . "\n";
    echo '<meta property="og:url" content="' . esc_url( $m['og_url'] ) . '">' . "\n";
    echo '<meta property="og:title" content="' . esc_attr( $m['og_title'] ) . '">' . "\n";
    echo '<meta property="og:description" content="' . esc_attr( $m['og_desc'] ) . '">' . "\n";
    echo '<meta name="twitter:card" content="summary_large_image">' . "\n";
    echo '<meta name="twitter:title" content="' . esc_attr( $m['og_title'] ) . '">' . "\n";
    echo '<meta name="twitter:description" content="' . esc_attr( $m['og_desc'] ) . '">' . "\n";
}, 1 );

get_header( 'investor' );
?>

<main id="main" class="investor-brief">

    <?php $hero = $brief['hero']; ?>
    <section class="hero" aria-labelledby="hero-title">
        <div class="hero-shell">
            <div class="hero-grid">

                <div class="hero-copy">
                    <p class="eyebrow"><?php echo esc_html( $hero['eyebrow'] ); ?></p>
                    <h1 id="hero-title"><?php echo esc_html( $hero['headline'] ); ?></h1>
                    <p class="hero-body"><?php echo esc_html( $hero['body'] ); ?></p>
                    <div class="hero-actions">
                        <a class="button button-primary"
                           href="<?php echo esc_url( $hero['cta']['href'] ); ?>"
                           data-track="<?php echo esc_attr( $hero['cta']['event'] ); ?>">
                            <?php echo esc_html( $hero['cta']['label'] ); ?>
                        </a>
                        <p class="support-text"><?php echo esc_html( $hero['cta']['support'] ); ?></p>
                    </div>
                </div>

                <aside class="workflow-visual" role="img"
                       aria-label="Workflow showing insurance demand moving from intent capture to request intelligence to agent engagement to execution support.">
                    <div class="workflow-visual__topline">
                        <span><?php echo esc_html( $hero['workflow']['label'] ); ?></span>
                        <small><?php echo esc_html( $hero['workflow']['badge'] ); ?></small>
                    </div>
                    <div class="workflow-stack">
                        <?php foreach ( $hero['workflow']['steps'] as $step ) : ?>
                            <div class="workflow-step">
                                <span class="status-dot <?php echo $step['state'] === 'muted' ? 'muted' : ''; ?>" aria-hidden="true"></span>
                                <div>
                                    <strong><?php echo esc_html( $step['title'] ); ?></strong>
                                    <em><?php echo esc_html( $step['detail'] ); ?></em>
                                </div>
                            </div>
                        <?php endforeach; ?>
                    </div>
                </aside>

            </div>

            <nav class="anchor-cards" aria-label="Page sections">
                <?php foreach ( $hero['anchors'] as $anchor ) : ?>
                    <a href="<?php echo esc_attr( $anchor['href'] ); ?>"><?php echo esc_html( $anchor['label'] ); ?></a>
                <?php endforeach; ?>
            </nav>
        </div>
    </section>

    <?php $problem = $brief['problem']; ?>
    <section class="section section-surface" id="problem" aria-labelledby="problem-title">
        <div class="container narrow">
            <p class="section-label"><?php echo esc_html( $problem['eyebrow'] ); ?></p>
            <h2 id="problem-title"><?php echo esc_html( $problem['headline'] ); ?></h2>
            <p class="section-body"><?php echo esc_html( $problem['body'] ); ?></p>
            <div class="card-row three" aria-label="Problem areas">
                <?php foreach ( $problem['cards'] as $card ) : ?>
                    <article class="mini-card"><span></span><h3><?php echo esc_html( $card ); ?></h3></article>
                <?php endforeach; ?>
            </div>
        </div>
    </section>

    <?php $shift = $brief['shift']; ?>
    <section class="section section-muted" id="shift" aria-labelledby="shift-title">
        <div class="container split">
            <div>
                <p class="section-label"><?php echo esc_html( $shift['eyebrow'] ); ?></p>
                <h2 id="shift-title"><?php echo esc_html( $shift['headline'] ); ?></h2>
                <p class="section-body"><?php echo esc_html( $shift['body'] ); ?></p>
            </div>
            <div class="system-card" aria-label="Structured workflow illustration">
                <?php foreach ( $shift['rows'] as $row ) : ?>
                    <div class="system-row"><span><?php echo esc_html( $row ); ?></span><b></b></div>
                <?php endforeach; ?>
            </div>
        </div>
    </section>

    <?php $building = $brief['building']; ?>
    <section class="section section-surface" id="building" aria-labelledby="building-title">
        <div class="container">
            <div class="section-head">
                <p class="section-label"><?php echo esc_html( $building['eyebrow'] ); ?></p>
                <h2 id="building-title"><?php echo esc_html( $building['headline'] ); ?></h2>
                <p class="section-body"><?php echo esc_html( $building['body'] ); ?></p>
            </div>
            <div class="workflow-cards">
                <?php foreach ( $building['cards'] as $card ) : ?>
                    <article>
                        <span class="status-dot <?php echo $card['state'] === 'muted' ? 'muted' : ''; ?>" aria-hidden="true"></span>
                        <h3><?php echo esc_html( $card['title'] ); ?></h3>
                        <p><?php echo esc_html( $card['detail'] ); ?></p>
                    </article>
                <?php endforeach; ?>
            </div>
        </div>
    </section>

    <?php $difference = $brief['difference']; ?>
    <section class="section section-navy" id="difference" aria-labelledby="difference-title">
        <div class="container split">
            <div>
                <p class="section-label inverse"><?php echo esc_html( $difference['eyebrow'] ); ?></p>
                <h2 id="difference-title"><?php echo esc_html( $difference['headline'] ); ?></h2>
                <p class="section-body inverse-body"><?php echo esc_html( $difference['body'] ); ?></p>
            </div>
            <div class="comparison-card">
                <?php foreach ( $difference['rows'] as $row ) : ?>
                    <div class="comparison-card__row <?php echo $row['preferred'] ? 'preferred' : ''; ?>">
                        <span><?php echo esc_html( $row['label'] ); ?></span>
                        <p><?php echo esc_html( $row['detail'] ); ?></p>
                    </div>
                <?php endforeach; ?>
            </div>
        </div>
    </section>

    <?php $why = $brief['why']; ?>
    <section class="section section-surface" id="why" aria-labelledby="why-title">
        <div class="container">
            <div class="section-head">
                <p class="section-label"><?php echo esc_html( $why['eyebrow'] ); ?></p>
                <h2 id="why-title"><?php echo esc_html( $why['headline'] ); ?></h2>
                <p class="section-body"><?php echo esc_html( $why['body'] ); ?></p>
            </div>
            <div class="card-row three">
                <?php foreach ( $why['cards'] as $card ) : ?>
                    <article class="feature-card">
                        <h3><?php echo esc_html( $card['title'] ); ?></h3>
                        <p><?php echo esc_html( $card['detail'] ); ?></p>
                    </article>
                <?php endforeach; ?>
            </div>
        </div>
    </section>

    <?php $who = $brief['who']; ?>
    <section class="section section-muted" id="who" aria-labelledby="who-title">
        <div class="container split aligned">
            <div>
                <p class="section-label"><?php echo esc_html( $who['eyebrow'] ); ?></p>
                <h2 id="who-title"><?php echo esc_html( $who['headline'] ); ?></h2>
                <p class="section-body"><?php echo esc_html( $who['body'] ); ?></p>
            </div>
            <ul class="profile-list" aria-label="Relevant investor profiles">
                <?php foreach ( $who['profiles'] as $profile ) : ?>
                    <li><?php echo esc_html( $profile ); ?></li>
                <?php endforeach; ?>
            </ul>
        </div>
    </section>

    <?php $form = $brief['form']; ?>
    <section class="section section-muted" id="request-materials" aria-labelledby="form-title">
        <div class="container form-layout">
            <div class="form-intro">
                <p class="section-label"><?php echo esc_html( $form['eyebrow'] ); ?></p>
                <h2 id="form-title"><?php echo esc_html( $form['headline'] ); ?></h2>
                <p class="section-body"><?php echo esc_html( $form['body'] ); ?></p>
            </div>

            <form class="investor-form" id="investorForm" action="#" method="post" novalidate>

                <div class="hidden-field" aria-hidden="true">
                    <label for="company_site">Company site</label>
                    <input id="company_site" name="company_site" type="text" tabindex="-1" autocomplete="off">
                </div>

                <div class="form-grid">
                    <label>Full name
                        <input name="full_name" type="text" required autocomplete="name">
                    </label>
                    <label>Email
                        <input name="email" type="email" required autocomplete="email">
                    </label>
                    <label>LinkedIn profile
                        <input name="linkedin_profile" type="url" required inputmode="url" placeholder="https://www.linkedin.com/in/name">
                    </label>
                    <label>Firm or company
                        <input name="firm_or_company" type="text" required autocomplete="organization">
                    </label>
                    <label>Investor type
                        <select name="investor_type" required>
                            <option value="">Select one</option>
                            <?php foreach ( $form['investor_types'] as $opt ) : ?>
                                <option><?php echo esc_html( $opt ); ?></option>
                            <?php endforeach; ?>
                        </select>
                    </label>
                    <label>Are you an accredited investor?
                        <select name="accredited_investor" required>
                            <option value="">Select one</option>
                            <?php foreach ( $form['accredited_options'] as $opt ) : ?>
                                <option><?php echo esc_html( $opt ); ?></option>
                            <?php endforeach; ?>
                        </select>
                    </label>
                    <label class="full">Relevant background
                        <textarea name="relevant_background" maxlength="400" required></textarea>
                        <small>400 character max</small>
                    </label>
                    <label class="full">Investment relevance
                        <textarea name="investment_relevance" maxlength="600" required placeholder="Why are you interested in Ensurance?"></textarea>
                        <small>600 character max</small>
                    </label>
                    <label class="full">Referral source
                        <input name="referral_source" type="text">
                    </label>
                </div>

                <label class="consent">
                    <input name="consent" type="checkbox" required>
                    <span><?php echo esc_html( $form['consent_text'] ); ?></span>
                </label>

                <button class="button button-primary submit" type="submit" data-track="form_submit_attempt">
                    <?php echo esc_html( $form['submit_label'] ); ?>
                </button>
                <p class="form-message" id="formMessage" role="status" aria-live="polite"></p>
            </form>
        </div>
    </section>

</main>

<?php get_footer( 'investor' ); ?>
