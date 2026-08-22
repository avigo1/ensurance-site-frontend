<?php
/**
 * Hero — homepage "Spotlight" layout (Calm Intelligence v2 standalone design).
 *
 * Full-viewport, centered single column on the light page background with a
 * soft teal glow behind the headline: mono eyebrow with accent dot → H1 →
 * subtitle → CTA row → microcopy → trust strip. Replaces the v1 dark
 * video-layout hero.
 *
 * Included from page-home.php via `include` so it shares that template's
 * scope. Expects:
 *   - ensurance_home_icon()      inline Lucide icon helper
 *   - $ensurance_svg_allowed     wp_kses allowlist for the icon SVGs
 *   - $start_url                 escaped URL of the auto quote entry page
 *
 * The .ens-rv reveal animation is CSS-only (assets/home.css) and collapses to
 * a visible end-state under prefers-reduced-motion or without JS.
 */

if ( ! isset( $start_url, $ensurance_svg_allowed ) ) {
    return;
}
?>

<section class="hero" aria-label="Auto insurance quote help">
  <div class="hero__glow" aria-hidden="true"></div>

  <div class="hero__content">
    <h1 class="ens-rv" style="animation-delay: 0.13s;">More insurance choice. <span class="accent">Less quote chaos.</span></h1>

    <p class="hero__subtitle ens-rv" style="animation-delay: 0.21s;">Start one guided auto insurance request and move toward available quote options through licensed insurance professionals, without starting over company after company.</p>

    <div class="hero-actions ens-rv" style="animation-delay: 0.29s;" aria-label="Homepage actions">
      <a class="btn btn-primary btn--lg" data-cta-text="Start My Auto Quote Request" data-page-type="homepage" data-track="cta_click_start_auto_quote_request" href="<?php echo $start_url; ?>">Start My Auto Quote Request <?php echo wp_kses( ensurance_home_icon( 'arrow-right', 18 ), $ensurance_svg_allowed ); ?></a>
      <a class="hero__textlink" data-cta-text="See how it works" data-page-type="homepage" data-track="cta_click_see_how_ensurance_works" href="#how-it-works">See how it works <?php echo wp_kses( ensurance_home_icon( 'arrow-right', 16 ), $ensurance_svg_allowed ); ?></a>
    </div>

    <p class="hero__finetext ens-rv" style="animation-delay: 0.36s;">Free to start. One guided request &mdash; a more controlled way to shop.</p>

    <div class="hero__trust ens-rv" style="animation-delay: 0.46s;">
      <span class="trust-cue"><?php echo wp_kses( ensurance_home_icon( 'clock', 16 ), $ensurance_svg_allowed ); ?> Online first</span>
      <span class="trust-cue"><?php echo wp_kses( ensurance_home_icon( 'shield-check', 16 ), $ensurance_svg_allowed ); ?> Licensed insurance help</span>
      <span class="trust-cue"><?php echo wp_kses( ensurance_home_icon( 'ban', 16 ), $ensurance_svg_allowed ); ?> Less unwanted outreach</span>
      <span class="trust-cue"><?php echo wp_kses( ensurance_home_icon( 'lock', 16 ), $ensurance_svg_allowed ); ?> More controlled contact</span>
    </div>
  </div>
</section>