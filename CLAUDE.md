# Ensurance Site Frontend — CLAUDE.md

**Project**: ensurance-site-frontend  
**Repository**: https://github.com/avigo1/ensurance-site-frontend  
**Deployment**: SiteGround via Git  
**Production**: ensurance.com  

---

## Current Focus

The frontend is being rebuilt from Gutenberg blocks to code-driven PHP templates. Work is organized into four phases. Each phase has a standalone plan in `/plans/` — detailed enough to hand off to a new agent with no prior context.

| Phase | Plan | Status |
|-------|------|--------|
| 1 — Infrastructure & Deployment Pipeline | [plans/phase-1-infrastructure.md](plans/phase-1-infrastructure.md) | Mostly complete — Day 2 (SiteGround connection) remains |
| 2 — Header, Footer & Design Foundation | [plans/phase-2-foundation.md](plans/phase-2-foundation.md) | Not started — awaiting design mockups |
| 3 — Homepage Build | [plans/phase-3-homepage-build.md](plans/phase-3-homepage-build.md) | Not started — awaiting Phase 2 |
| 4 — QA, Review & Go-Live | [plans/phase-4-launch.md](plans/phase-4-launch.md) | Not started — awaiting Phase 3 |

**If you are a new agent starting work**: Read the plan for the current phase first. Each plan contains all the context, file paths, commands, and exit criteria you need.

---

## Quick Start

### Connect to SiteGround

```bash
ssh ensurance
```

This connects to `ssh.ensurance.com:18765` with the configured identity. (Configured in `~/.ssh/config`.)

### Clone This Repo

```bash
cd ~/Documents/Github
git clone https://github.com/avigo1/ensurance-site-frontend.git
cd ensurance-site-frontend
```

### Deploy Changes

After making changes locally:

```bash
git add .
git commit -m "Your descriptive message"
git push
```

Changes appear on production within 1 minute via SiteGround's Git integration.

---

## Architecture Overview

This repository is the **WordPress theme** for Ensurance.com. It controls all frontend rendering: page templates, headers, footers, styling, and components.

### Directory Structure

```
/
├── style.css                    ← Legacy GeoDirectory styles (DO NOT EDIT)
├── functions.php                ← PHP hooks & enqueue functions
├── header-marketing.php         ← Shared header for marketing pages
├── footer-marketing.php         ← Shared footer for marketing pages
├── page-home.php                ← Homepage template
├── page-about.php               ← About page template (future)
├── /components/                 ← Reusable page sections
│   ├── hero.php                 ← Hero section partial
│   ├── cta-block.php            ← CTA block partial
│   └── feature-grid.php         ← Feature grid partial (examples)
├── /assets/
│   ├── marketing.css            ← Marketing pages CSS + design tokens
│   ├── marketing.js             ← Marketing pages JavaScript
│   └── /images/                 ← Image assets
└── CLAUDE.md                    ← This file

### Parent Theme

This is a **Kadence child theme**. The parent theme (Kadence) handles:
- GeoDirectory pages, listing pages, agent profiles
- Blog and archive pages
- WordPress core functionality

This child theme **overrides and extends** Kadence for marketing pages only.

---

## Design Token System

All colors, fonts, and spacing for marketing pages are defined as CSS custom properties (variables) in `assets/marketing.css`:

```css
:root {
  /* Colors */
  --color-primary: #0073E6;
  --color-primary-dark: #0D4095;
  --color-accent: #F5A524;
  --color-text: #1e293b;
  --color-background: #ffffff;

  /* Typography */
  --font-heading: 'Manrope', sans-serif;
  --font-body: 'Inter', sans-serif;

  /* Spacing */
  --space-md: 2rem;
  --space-lg: 4rem;
}
```

**All CSS rules reference these variables.** To rebrand the entire site, edit this block only.

---

## Adding a New Marketing Page

### 1. Create the Template

Create `page-{slug}.php`:

```php
<?php get_header('marketing'); ?>

<main class="page-content">
  <h1><?php the_title(); ?></h1>
  
  <!-- Use components from /components/ -->
  <?php get_template_part('components/hero'); ?>
  
</main>

<?php get_footer('marketing'); ?>
```

### 2. Register in functions.php

Update the conditional enqueue:

```php
if (is_front_page() || is_page_template('page-{slug}.php')) {
```

### 3. Create WordPress Page

- Go to WordPress admin → Pages → Add New
- Title: "Your Page Name"
- Slug: "{slug}"
- Template: "page-{slug}.php"
- Publish

### 4. Commit and Deploy

```bash
git add page-{slug}.php
git commit -m "Add {slug} page template"
git push
```

---

## Component Usage

Components are reusable PHP partials in `/components/`. Use them with:

```php
<?php get_template_part('components/hero'); ?>
```

### Example Component: Hero

File: `components/hero.php`

```php
<section class="hero">
  <h1 style="color: var(--color-primary);"><?php the_title(); ?></h1>
  <p style="color: var(--color-text-muted);">Tagline here</p>
</section>
```

**Rule**: All CSS in components must use `var(--color-*)` and `var(--space-*)` variables, never hardcoded values.

---

## CSS & Styling

### Where CSS Goes

- **Marketing pages**: `assets/marketing.css` (loaded conditionally)
- **GeoDirectory/legacy**: `style.css` (unchanged, not for editing)

### CSS Loading

In `functions.php`:

```php
function ensurance_marketing_assets() {
    if (is_front_page() || is_page_template('page-*.php')) {
        wp_enqueue_style('marketing', get_stylesheet_directory_uri() . '/assets/marketing.css');
    }
}
```

`marketing.css` only loads on marketing pages. GeoDirectory pages keep their existing styles.

### Naming Conventions

- **BEM for classes**: `.hero`, `.hero__title`, `.hero--large`
- **Utility prefix**: `.u-` (e.g., `.u-flex`, `.u-mt-lg` for Tailwind-like utilities)
- **State classes**: `.is-active`, `.is-loading`

Example:

```css
.hero {
  padding: var(--space-lg);
  background: var(--color-background);
}

.hero__title {
  color: var(--color-primary);
  font-family: var(--font-heading);
}

.hero--dark {
  background: var(--color-primary-dark);
  color: var(--color-background);
}
```

---

## JavaScript

Minimal JavaScript in `assets/marketing.js`. Use vanilla JS, no jQuery.

Common use cases:
- Mobile nav toggle
- Smooth scroll
- Form validation

Load with:

```php
wp_enqueue_script('marketing', get_stylesheet_directory_uri() . '/assets/marketing.js', array(), false, true);
```

---

## What NOT to Edit

### ❌ DO NOT TOUCH

1. **`style.css`** — Legacy GeoDirectory styles. Editing breaks agent listing pages.
2. **`functions.php` existing functions** — Only add new functions, never modify existing ones.
3. **`/node_modules/`, `wp-content/plugins/`** — Plugin and core code.
4. **WordPress database** — Content goes in page template PHP, not the database (except for WordPress pages themselves).

### ✅ Safe to Edit

- `page-*.php` files
- `header-marketing.php` / `footer-marketing.php`
- `components/*.php`
- `assets/marketing.css` / `assets/marketing.js`
- This file (`CLAUDE.md`)

---

## Deployment Workflow

### Before Every Push

Run this checklist:

- [ ] Changes are only in marketing pages (page-*.php, components/, assets/)
- [ ] No edits to `style.css`
- [ ] No edits to existing `functions.php` code (only new functions)
- [ ] CSS uses design tokens (var(--color-*), var(--space-*))
- [ ] All templates call `get_header('marketing')` and `get_footer('marketing')`

### Deploy

```bash
# See changes
git status

# Stage all changes
git add .

# Write a clear commit message
git commit -m "Add hero section to homepage"

# Push to GitHub (auto-deploys to SiteGround)
git push
```

### Verify on Production

After pushing:

1. Wait 1 minute for SiteGround to sync
2. Visit https://ensurance.com/
3. Verify the change appears
4. Check a GeoDirectory page (e.g., https://ensurance.com/insurance-agencies/) — should be unchanged

---

## SSH & Git Integration

### Connecting to SiteGround

Your SSH config (`~/.ssh/config`) has:

```
Host ensurance
    HostName ssh.ensurance.com
    User u2514-jukueftqhhlm
    Port 18765
    IdentityFile ~/.ssh/siteground
    IdentitiesOnly yes
```

Connect with:

```bash
ssh ensurance
```

Navigate to the theme directory:

```bash
cd /home/u2514-jukueftqhhlm/public_html/wp-content/themes/kadence-child/
```

### Git Deployment

SiteGround is configured to auto-pull from GitHub and deploy to `/wp-content/themes/kadence-child/`. Pushes to `main` branch deploy automatically.

---

## Troubleshooting

### Change doesn't appear on production

1. Verify push succeeded: `git log --oneline` (should show your commit)
2. Wait 1 minute (SiteGround sync delay)
3. Hard-refresh browser: `Cmd+Shift+R` (Mac) or `Ctrl+Shift+R` (Windows)
4. Check SiteGround Git deployment status via SSH:
   ```bash
   ssh ensurance
   cd public_html/wp-content/themes/kadence-child/
   git log --oneline -5
   ```

### CSS not applying

- Check that `marketing.css` is enqueued in `functions.php`
- Verify the conditional (e.g., `is_front_page()`) matches the page you're testing
- Use DevTools → Network tab to confirm the file loads
- Check for CSS specificity conflicts (use `!important` as last resort)

### Broke the homepage

Rollback the last commit:

```bash
git revert HEAD
git push
```

This creates a new commit that undoes the previous one, keeping history intact.

---

## Future Roadmap

- [ ] Add About page template
- [ ] Add Contact page template
- [ ] Implement WordPress nav menus (currently hardcoded in header)
- [ ] Add blog/news section (integrate with existing blog)
- [ ] Set up staging environment on SiteGround
- [ ] Add automated tests

---

## Questions?

Refer to this file when making changes. If something is unclear, update this document so future developers (and future you) don't get stuck.

---

**Last Updated**: 2026-05-05  
**Maintained By**: Team
