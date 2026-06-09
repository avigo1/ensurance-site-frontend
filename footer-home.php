<?php
/**
 * Marketing footer — Homepage (auto-forward design).
 *
 * Self-contained chrome for the homepage only, ported verbatim from the
 * bespoke package's includes/footer.php + the mobile sticky CTA that sat
 * after the footer in index.php. Called via get_footer('home').
 *
 * Links are WP-resolved with home_url(); the logo and copy are unchanged.
 */
?>
<footer class="site-footer">
  <div class="container footer-grid">
    <div>
      <a class="brand footer-brand" href="<?php echo esc_url( home_url( '/' ) ); ?>" aria-label="Ensurance.com homepage">
        <img src="<?php echo esc_url( get_stylesheet_directory_uri() . '/assets/images/simple_navy_logo_with_registered_trademark.png' ); ?>" alt="Ensurance.com" class="brand-logo-image" />
      </a>
      <p>Ensurance helps shoppers start a guided insurance quote request online and move toward clearer quote options through licensed review when appropriate.</p>
    </div>
    <nav aria-label="Footer navigation">
      <a href="<?php echo esc_url( home_url( '/how-it-works' ) ); ?>">How it works</a>
      <a href="<?php echo esc_url( home_url( '/coverage' ) ); ?>">Coverage types</a>
      <a href="<?php echo esc_url( home_url( '/trust-center' ) ); ?>">Trust Center</a>
      <a href="<?php echo esc_url( home_url( '/for-agents' ) ); ?>">For agents</a>
    </nav>
    <nav aria-label="Legal navigation">
      <a href="<?php echo esc_url( home_url( '/privacy' ) ); ?>">Privacy</a>
      <a href="<?php echo esc_url( home_url( '/terms' ) ); ?>">Terms</a>
      <a href="<?php echo esc_url( home_url( '/contact' ) ); ?>">Contact</a>
    </nav>
  </div>
  <div class="container footer-bottom">
    <p>&copy; <?php echo esc_html( date( 'Y' ) ); ?> Ensurance. All rights reserved.</p>
  </div>
</footer>

<div aria-label="Mobile quote request action" class="mobile-sticky-cta">
  <span>Quote help, less chaos.</span>
  <a class="btn btn-primary" data-cta-text="Start My Auto Quote Request" data-page-type="homepage" data-track="mobile_sticky_cta_click" href="<?php echo esc_url( home_url( '/auto-insurance-quote-request' ) ); ?>">Start My Auto Quote Request</a>
</div>

<?php wp_footer(); ?>
</body>
</html>
