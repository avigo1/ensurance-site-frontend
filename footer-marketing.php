<?php
/**
 * Marketing footer.
 *
 * Two link groups (site + legal) match the redesigned footer. Routes line up
 * with the header and homepage CTAs.
 *
 * TODO: pages for /how-it-works, /coverage, /trust-and-privacy, /for-agents,
 * /privacy, /terms, /licenses, /contact do not exist yet (Phase 3 — Homepage
 * Build / Phase 4 — Launch). Links resolve to 404 until those ship.
 */
$footer_site_nav = array(
    array( 'label' => 'How it works',      'href' => '/how-it-works' ),
    array( 'label' => 'Coverage types',    'href' => '/coverage' ),
    array( 'label' => 'Trust and privacy', 'href' => '/trust-and-privacy' ),
    array( 'label' => 'For agents',        'href' => '/for-agents' ),
);

$footer_legal_nav = array(
    array( 'label' => 'Privacy',  'href' => '/privacy' ),
    array( 'label' => 'Terms',    'href' => '/terms' ),
    array( 'label' => 'Licenses', 'href' => '/licenses' ),
    array( 'label' => 'Contact',  'href' => '/contact' ),
);
?>

<footer class="site-footer" role="contentinfo">
    <div class="site-footer__inner">

        <div class="site-footer__top">

            <div class="site-footer__brand">
                <a class="site-footer__brand-lockup" href="<?php echo esc_url( home_url( '/' ) ); ?>" aria-label="Ensurance home">
                    <span class="site-footer__brand-mark" aria-hidden="true">E</span>
                    <span class="site-footer__brand-text">Ensurance</span>
                </a>
                <p class="site-footer__description">
                    Ensurance helps shoppers start one guided insurance request that licensed agents, agencies, or approved insurance partners can use to review available carriers and provide quote options where available.
                </p>
            </div>

            <nav class="site-footer__nav" aria-label="Footer navigation">
                <?php foreach ( $footer_site_nav as $item ) : ?>
                    <a class="site-footer__link" href="<?php echo esc_url( home_url( $item['href'] ) ); ?>">
                        <?php echo esc_html( $item['label'] ); ?>
                    </a>
                <?php endforeach; ?>
            </nav>

            <nav class="site-footer__nav" aria-label="Legal navigation">
                <?php foreach ( $footer_legal_nav as $item ) : ?>
                    <a class="site-footer__link" href="<?php echo esc_url( home_url( $item['href'] ) ); ?>">
                        <?php echo esc_html( $item['label'] ); ?>
                    </a>
                <?php endforeach; ?>
            </nav>

        </div>

        <div class="site-footer__bottom">
            <p class="site-footer__copyright">&copy; <?php echo esc_html( date( 'Y' ) ); ?> Ensurance. All rights reserved.</p>
        </div>

    </div>
</footer>

<?php wp_footer(); ?>
</body>
</html>
