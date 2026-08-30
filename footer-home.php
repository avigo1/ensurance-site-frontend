<?php
/**
 * Marketing footer — Homepage (Calm Intelligence redesign).
 *
 * Self-contained chrome for the homepage only. Called via get_footer('home')
 * from page-home.php so it does NOT affect the shared marketing footer.
 *
 * Dark column footer matching the homepage v1 design. The mobile sticky CTA
 * (kept after the footer) and the .site-footer / .mobile-sticky-cta classes are
 * relied upon by assets/home.js — keep them intact. Links are WP-resolved.
 */
?>
<footer class="site-footer">
  <div class="container footer-grid">
    <div class="footer-brand">
      <a class="brand" href="<?php echo esc_url( home_url( '/' ) ); ?>" aria-label="Ensurance.com homepage">
        <img src="<?php echo esc_url( get_stylesheet_directory_uri() . '/assets/images/logo-white.png' ); ?>" alt="Ensurance.com" class="brand-logo-image" />
      </a>
      <p>Insurance quote help without the quote chaos.</p>
    </div>
    <div class="footer-cols">
      <nav class="footer-col" aria-label="Ensurance">
        <p class="footer-col__title">Ensurance</p>
        <a href="<?php echo esc_url( home_url( '/how-ensurance-works/' ) ); ?>">How it works</a>
        <a href="<?php echo esc_url( home_url( '/insurance-coverage/' ) ); ?>">Coverage types</a>
        <a href="<?php echo esc_url( home_url( '/insurance-by-state/' ) ); ?>">Coverage by State</a>
      </nav>
      <nav class="footer-col" aria-label="For agents">
        <p class="footer-col__title">For agents</p>
        <a href="<?php echo esc_url( home_url( '/for-agents' ) ); ?>">Join the network</a>
      </nav>
      <nav class="footer-col" aria-label="Trust">
        <p class="footer-col__title">Trust</p>
        <a href="<?php echo esc_url( home_url( '/trust-center' ) ); ?>">Trust Center</a>
        <a href="<?php echo esc_url( home_url( '/privacy-policy/' ) ); ?>">Privacy Policy</a>
        <a href="<?php echo esc_url( home_url( '/terms-of-use/' ) ); ?>">Terms of Use</a>
        <a href="<?php echo esc_url( home_url( '/contact' ) ); ?>">Contact Us</a>
      </nav>
    </div>
  </div>
  <div class="footer-bottom">
    <p>&copy; <?php echo esc_html( date( 'Y' ) ); ?> Ensurance. Insurance products are offered through licensed agents, agencies, or approved partners where available.</p>
  </div>
</footer>

<?php if ( is_front_page() || is_page( array( 'auto-insurance-quote', 'auto-insurance-quote-request' ) ) ) : ?>
<div aria-label="Mobile quote request action" class="mobile-sticky-cta">
  <a class="btn btn-primary" data-cta-text="Start My Auto Quote Request" data-page-type="auto" data-track="mobile_sticky_cta_click" href="<?php echo esc_url( home_url( '/insurance-coverage/auto-insurance-quote-request/auto-insurance-quote/' ) ); ?>">Start My Auto Quote Request</a>
</div>
<?php else : ?>
<div aria-label="Mobile insurance request action" class="mobile-sticky-cta">
  <a class="btn btn-primary" data-cta-text="Start an Insurance Request" data-page-type="global" data-track="mobile_sticky_cta_click" href="<?php echo esc_url( home_url( '/insurance-coverage/' ) ); ?>">Start an Insurance Request</a>
</div>
<?php endif; ?>

<?php wp_footer(); ?>
</body>
</html>
