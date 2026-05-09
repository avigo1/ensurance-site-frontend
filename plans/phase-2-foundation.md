# Phase 2 — Header, Footer & Design Foundation

**Status**: Not started. Awaiting design mockups.  
**Goal**: Build the production-quality header and footer that will be shared across all new marketing pages. Finalize the design token system.

---

## Context for a New Session

- **Live site**: https://ensurance.com (WordPress on SiteGround, Kadence child theme)
- **GitHub repo**: https://github.com/avigo1/ensurance-site-frontend
- **Local repo path**: `/Users/austinvigo/Documents/Github/ensurance-site-frontend/`
- **Deploy command**: `git push && ssh ensurance '/home/u2514-jukueftqhhlm/deploy-theme.sh'`

### Prerequisites

Phase 1 must be complete before starting Phase 2:
- GitHub → SiteGround deployment pipeline is working
- `git push` + deploy script deploys to production

### Design Mockups

Design mockups must be available before building. The header and footer designs should define:
- Logo placement and sizing
- Navigation link structure and labels
- CTA button label and destination
- Mobile behavior (hamburger menu opens to full-screen nav, slide-in panel, dropdown, etc.)
- Footer columns, link structure, brand text
- Color usage (should match the design token system already in `assets/marketing.css`)

---

## Files to Edit

### `header-marketing.php`

Current state: A functional stub. Has the correct structure and classes, but nav links are hardcoded placeholders and logo uses a path that assumes a `logo-white.png` image file.

This file renders the `<head>` tag (with `wp_head()`), the opening `<body>`, and the sticky header nav.

Full structure of current stub:
```
<!DOCTYPE html>
<html>
  <head> ... wp_head() ... </head>
  <body>
    <header class="site-header">
      <div class="site-header__inner">
        <a class="site-header__logo"> ... </a>
        <nav class="site-header__nav"> ... </nav>
        <div class="site-header__cta"> ... </div>
        <button class="site-header__mobile-toggle"> ... </button>
      </div>
    </header>
```

### `footer-marketing.php`

Current state: A functional stub. Brand column + 3 link columns (For Consumers, For Agents, Company) + copyright bar.

This file also contains `wp_footer()` and closing `</body></html>`.

### `assets/marketing.css`

Current state: Full design token system + base styles for header, footer, buttons, typography, layout utilities, responsive breakpoints.

Sections 6 (Header) and 7 (Footer) contain the current CSS for header and footer. These will need to be updated to match the final design.

### `assets/marketing.js`

Current state: Mobile nav toggle + smooth scroll. May need updates depending on the final mobile nav behavior.

---

## Implementation Tasks

### Task 1 — Update Design Tokens (if needed)

Review `assets/marketing.css` `:root` block. If the design mockups specify different brand colors, fonts, or spacing, update the design token values here.

**Rule**: Never hardcode color values or font names outside the `:root` block. All rules must reference `var(--*)` variables.

Current tokens:
```css
--color-primary:        #0073E6;
--color-primary-dark:   #0D4095;
--color-primary-light:  #e6f0f8;
--color-accent:         #F5A524;
--color-accent-dark:    #d98e18;
--font-heading:         'Manrope', sans-serif;
--font-body:            'Inter', sans-serif;
```

### Task 2 — Build the Header

Update `header-marketing.php` with:
- Final logo (update the `<img>` src path to match the actual file in `assets/images/`)
- Final nav links
- Final CTA button label and href
- Correct `aria-label` attributes for accessibility

Update Section 6 (Header) in `assets/marketing.css` with any styling changes needed to match the design.

Update `assets/marketing.js` if mobile nav behavior needs to change from the current toggle.

**Accessibility requirements**:
- `<nav>` must have `aria-label="Main navigation"`
- Mobile toggle button must have `aria-expanded` and `aria-controls`
- All images must have `alt` text

### Task 3 — Build the Footer

Update `footer-marketing.php` with:
- Final logo (white version in footer)
- Final footer link structure and labels
- Brand tagline (currently: "Online first. Human when it matters." — confirm this is correct)
- Social media links (if applicable)

Update Section 7 (Footer) in `assets/marketing.css` with styling changes.

### Task 4 — Add Logo Files

If logo images are not already in the repo, add them:
```
assets/images/logo.png        ← Color logo (for header on white background)
assets/images/logo-white.png  ← White logo (for footer on dark background)
```

Recommended sizes: max 280px wide, 2x resolution for retina (e.g., 560px wide image displayed at 140px).

### Task 5 — Verify on All Breakpoints

Test the header and footer at:
- Desktop: 1440px, 1200px, 1024px
- Tablet: 768px
- Mobile: 375px, 390px (iPhone 14)

The current responsive CSS handles 768px breakpoint (mobile nav) and 480px breakpoint (single-column footer).

---

## CSS Rules for This Phase

All new CSS must:
1. Go into the appropriate section of `assets/marketing.css` (Section 6 for header, Section 7 for footer)
2. Use `var(--*)` tokens — never hardcoded values
3. Follow BEM naming: `.site-header__nav`, `.site-header__logo`, etc.
4. Be scoped to `.site-header` or `.site-footer` (not global)

---

## Deployment Checklist

Before pushing:
- [ ] Header renders correctly at desktop + mobile breakpoints
- [ ] Footer renders correctly at desktop + mobile breakpoints
- [ ] Mobile nav toggle opens and closes
- [ ] Logo images exist in `assets/images/` (not broken img tags)
- [ ] No changes to `style.css`
- [ ] No changes to existing `functions.php` functions
- [ ] GeoDirectory pages (`/insurance-agencies/`) still load with their original header/footer

Deploy:
```bash
git add header-marketing.php footer-marketing.php assets/marketing.css assets/marketing.js assets/images/
git commit -m "Build production header and footer"
git push && ssh ensurance '/home/u2514-jukueftqhhlm/deploy-theme.sh'
```

---

## Exit Criteria

Phase 2 is complete when:
- [ ] Header matches the approved design mockup at all breakpoints
- [ ] Footer matches the approved design mockup at all breakpoints
- [ ] Mobile nav opens/closes correctly on iOS and Android
- [ ] Logo images are not broken on production
- [ ] Design tokens in `assets/marketing.css` reflect the final brand colors/fonts
- [ ] GeoDirectory pages are unaffected (visual regression check)

---

## Refinement (TODO)

Issues discovered during implementation that need future attention:

- [ ] **Page background color conflict**: The header/page background appears gray instead of white on staging. WordPress Gutenberg block editor has its own color settings that are conflicting with the theme's `--color-background: #FDFBF7` token. Need to investigate whether Gutenberg inline styles or the Kadence parent theme are injecting a background color, and override or disable them for marketing pages.

---

## What Comes Next

**Phase 3** — Build the homepage sections using components.
