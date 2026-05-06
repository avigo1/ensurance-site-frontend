# Phase 3 — Homepage Build

**Status**: Not started. Awaiting Phase 2 completion and design mockups.  
**Goal**: Build the full homepage by composing reusable components in `page-home.php`.

---

## Context for a New Session

- **Live site**: https://ensurance.com (WordPress on SiteGround, Kadence child theme)
- **GitHub repo**: https://github.com/avigo1/ensurance-site-frontend
- **Local repo path**: `/Users/austinvigo/Documents/Github/ensurance-site-frontend/`
- **Deploy to staging**: `deploy-staging` (from `staging` branch)
- **Deploy to production**: `deploy-prod` (from `main` branch — see critical note below)

### Prerequisites

- Phase 1 complete (GitHub → SiteGround pipeline working) ✅
- Phase 2 complete (production header and footer built, design tokens finalized)
- Design mockups for the homepage sections are available

### Critical: How `page-home.php` Goes Live

WordPress automatically applies `page-{slug}.php` to any WordPress page whose slug matches. The homepage slug is `home`, so the moment `page-home.php` exists on the `main` branch and is deployed, it replaces the live homepage — no WP Admin action needed.

**Current state:**
- `page-home.php` exists on `staging` only (intentionally absent from `main`)
- All Phase 3 work happens on `staging` and is verified on staging14.ensurance.com
- Merging `page-home.php` to `main` + running `deploy-prod` IS the go-live switch (handled in Phase 4)

Do not merge `staging` to `main` until Phase 4 sign-off.

### What the Homepage Is

The homepage template is `page-home.php`. It currently renders a placeholder on staging. The goal of this phase is to replace the placeholder with real content sections, each built as a reusable PHP component in `/components/`.

The homepage uses:
- `header-marketing.php` — shared marketing header (built in Phase 2)
- `footer-marketing.php` — shared marketing footer (built in Phase 2)
- `assets/marketing.css` — all styles
- `assets/marketing.js` — interactive behavior

The homepage does NOT use Kadence Gutenberg blocks. It is entirely PHP + CSS.

---

## Architecture: Components

Each homepage section is a **component** — a PHP partial in `/components/`. This makes sections reusable across pages and easy for AI agents to edit independently.

### Component File Structure

```
/components/
  hero.php           ← Hero section
  how-it-works.php   ← Step-by-step explanation
  social-proof.php   ← Trust indicators / testimonials
  feature-grid.php   ← Feature/benefit grid
  cta-block.php      ← Final call-to-action section
  faq.php            ← FAQ accordion (optional)
```

### Component Rules

1. **Each component is a self-contained `<section>` element.**
2. **All CSS for the component lives in `assets/marketing.css`**, in Section 8 (Components), with a comment: `/* Hero — see components/hero.php */`
3. **No inline styles** — use CSS classes with `var(--*)` tokens.
4. **No PHP logic** in components unless fetching WordPress content. Static copy lives directly in the PHP file.
5. **All text is hardcoded PHP** (not pulled from the WordPress database), unless using `the_title()` or similar core functions.

### Using a Component in page-home.php

```php
<?php get_template_part('components/hero'); ?>
```

---

## Implementation Tasks

### Task 1 — Plan the Section Order

Based on the design mockup, determine the order and names of all homepage sections. A typical insurance marketplace homepage might include:

1. **Hero** — Headline, subheadline, primary CTA ("Get a Quote" / "Find an Agent")
2. **How It Works** — 3-step process (consumer flow or agent flow)
3. **Feature Grid** — Key benefits or differentiators
4. **Social Proof** — Testimonials, agent count, lead count, or trust badges
5. **CTA Block** — Final conversion section with contrast background

Confirm with the design mockup before writing any code.

### Task 2 — Build Each Component

For each component:

1. Create `components/{name}.php`
2. Add the HTML structure inside a `<section class="{name} section">` wrapper
3. Use `.container` div inside for max-width centering:
   ```php
   <section class="hero section">
       <div class="container">
           <!-- content -->
       </div>
   </section>
   ```
4. Add corresponding CSS to `assets/marketing.css` in Section 8, scoped to the component class.

#### Hero Component (`components/hero.php`)

Typical structure:
```html
<section class="hero section">
    <div class="container">
        <div class="hero__content">
            <h1 class="hero__headline">...</h1>
            <p class="hero__subheadline">...</p>
            <div class="hero__cta">
                <a href="/get-a-quote/" class="btn btn--primary">Get a Quote</a>
                <a href="/insurance-agencies/" class="btn btn--secondary">Find an Agent</a>
            </div>
        </div>
        <div class="hero__image">
            <img src="<?php echo get_stylesheet_directory_uri(); ?>/assets/images/hero.png" alt="..." />
        </div>
    </div>
</section>
```

#### How It Works Component (`components/how-it-works.php`)

Three-column grid with numbered steps.

#### Feature Grid Component (`components/feature-grid.php`)

Grid of benefit cards. Each card: icon + headline + short description.

#### Social Proof Component (`components/social-proof.php`)

Testimonial cards or stat counters.

#### CTA Block Component (`components/cta-block.php`)

Full-width section with contrast background, headline, and single CTA button.

### Task 3 — Compose the Homepage

Once components are built, update `page-home.php`:

```php
<?php
/**
 * Template Name: Home Page (Marketing)
 */
get_header('marketing');
?>

<main class="page-home">
    <?php get_template_part('components/hero'); ?>
    <?php get_template_part('components/how-it-works'); ?>
    <?php get_template_part('components/feature-grid'); ?>
    <?php get_template_part('components/social-proof'); ?>
    <?php get_template_part('components/cta-block'); ?>
</main>

<?php get_footer('marketing'); ?>
```

### Task 4 — Add Component CSS

In `assets/marketing.css`, Section 8 (Components), add a subsection for each component:

```css
/* ============================================================================
   8. COMPONENTS
   ============================================================================ */

/* Hero — see components/hero.php */
.hero { ... }
.hero__content { ... }
.hero__headline { ... }

/* How It Works — see components/how-it-works.php */
.how-it-works { ... }

/* etc. */
```

### Task 5 — Add Image Assets

Any images used in components (hero illustration, step icons, feature icons) go in `assets/images/`.

Naming convention:
- `hero.png` or `hero.jpg`
- `step-1.svg`, `step-2.svg`, `step-3.svg`
- `icon-{name}.svg`

Use SVG for icons. Use PNG/WebP for photos/illustrations.

### Task 6 — Responsive QA

Test the full homepage at:
- 1440px (large desktop)
- 1200px (standard desktop)
- 768px (tablet — mobile nav kicks in)
- 390px (iPhone 14)

Each component should have responsive rules in `assets/marketing.css` under Section 9 (Responsive).

---

## CSS Rules for This Phase

All component CSS must:
1. Go in Section 8 of `assets/marketing.css`, under a named comment block
2. Be scoped to the component class (e.g., `.hero`, `.how-it-works`)
3. Use `var(--*)` tokens — never hardcoded values
4. Use the `.container` class for width-capping, not width on the section itself
5. Use the `.section` utility class for vertical padding (`padding: var(--space-20) 0`)

---

## Deployment Workflow

Build section by section. Deploy after each component is done to catch issues early.

```bash
# After each component
git checkout staging
git add components/ assets/marketing.css
git commit -m "Add {component-name} component"
deploy-staging
```

Verify on production after each push.

---

## Exit Criteria

Phase 3 is complete when:
- [ ] All homepage sections match the approved design mockup
- [ ] All components are in `/components/`, not inline in `page-home.php`
- [ ] Homepage renders correctly at all breakpoints
- [ ] No Gutenberg blocks or Kadence shortcodes used anywhere
- [ ] GeoDirectory pages are unaffected (visual regression check)
- [ ] Page speed: homepage loads in under 3 seconds on a mobile connection (check with PageSpeed Insights)

---

## What Comes Next

**Phase 4** — QA, stakeholder review, and go-live (merge `page-home.php` to `main` + `deploy-prod` — this automatically switches the live homepage, no WP Admin action needed).
