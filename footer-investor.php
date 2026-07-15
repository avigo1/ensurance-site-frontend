<?php
/**
 * Footer — private investor brief.
 *
 * Securities disclaimer strip + slim dark footer (inverse wordmark, tagline,
 * legal links). Copy lives in components/_investor-brief-data.php.
 */

$ib_footer = require __DIR__ . '/components/_investor-brief-data.php';
?>

<section class="ib-disclaimer" aria-label="Investor disclaimer">
    <div class="ib-shell">
        <p><?php echo esc_html( $ib_footer['disclaimer'] ); ?></p>
    </div>
</section>

<footer class="ib-footer" role="contentinfo">
    <div class="ib-footer__accent" aria-hidden="true"></div>
    <div class="ib-shell ib-footer__inner">
        <div class="ib-footer__brand">
            <a class="ib-logo" href="/" aria-label="Ensurance home">
                <img src="<?php echo esc_url( get_stylesheet_directory_uri() . '/assets/images/logo-white.png' ); ?>" alt="Ensurance.com">
            </a>
            <p><?php echo esc_html( $ib_footer['footer']['tagline'] ); ?></p>
        </div>
        <nav class="ib-footer__nav" aria-label="Footer links">
            <?php foreach ( $ib_footer['footer']['links'] as $link ) : ?>
                <a href="<?php echo esc_url( $link['href'] ); ?>"><?php echo esc_html( $link['label'] ); ?></a>
            <?php endforeach; ?>
        </nav>
    </div>
</footer>

<?php wp_footer(); ?>
</body>
</html>
