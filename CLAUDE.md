# Ensurance Site Frontend — CLAUDE.md

**Project**: ensurance-site-frontend  
**Repository**: https://github.com/avigo1/ensurance-site-frontend  
**Deployment**: SiteGround via Git (staging-first workflow)  
**Staging**: staging19.ensurance.com  
**Production**: ensurance.com  

---

## Current Focus

The frontend is being rebuilt from Gutenberg blocks to code-driven PHP templates. Work is organized into four phases. Each phase has a standalone plan in `/plans/` — detailed enough to hand off to a new agent with no prior context.

| Phase | Plan | Status |
|-------|------|--------|
| 1 — Infrastructure & Deployment Pipeline | [plans/phase-1-infrastructure.md](plans/phase-1-infrastructure.md) | **Complete** |
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

This connects to `ssh.ensurance.com:18765` as `u2514-jukueftqhhlm` using `~/.ssh/siteground`. Full command if alias fails:

```bash
ssh -i ~/.ssh/siteground -p 18765 u2514-jukueftqhhlm@ssh.ensurance.com
```

### SiteGround Theme Paths

| Environment | Path |
|-------------|------|
| Production | `/home/u2514-jukueftqhhlm/www/ensurance.com/public_html/wp-content/themes/kadence-child/` |
| Staging | `/home/u2514-jukueftqhhlm/www/staging19.ensurance.com/public_html/wp-content/themes/kadence-child/` |

### Clone This Repo

```bash
cd ~/Documents/Github
git clone https://github.com/avigo1/ensurance-site-frontend.git
cd ensurance-site-frontend
git checkout staging
```

### Deploy Changes

**Always deploy to staging first, verify, then promote to production.**

```bash
# Deploy to staging19.ensurance.com
deploy-staging

# Deploy to ensurance.com (production)
deploy-prod
```

These aliases are configured in `~/.zshrc`. They push to GitHub and pull to SiteGround in one command.

---

## Figma Workflow

Design-to-code for marketing pages is streamlined via Figma MCP (Model Context Protocol) integration. Claude can read Figma designs directly and build templates.

### How It Works

1. **Share a Figma link** — paste the design file URL
2. **Specify what to build** — "build the hero section from Frame X" or "build the homepage from this file"
3. **Claude extracts design data** — reads layout, colors, fonts, spacing, components directly from Figma
4. **Build happens automatically** — outputs `page-*.php`, `/components/*.php`, and CSS all mapped to your design tokens

### Example

```
You: "Here's the homepage design: https://www.figma.com/design/abc123/... — build it"

Claude: [reads Figma file] [extracts colors, spacing, typography] [generates page-home.php and components]
```

### What Claude Pulls from Figma

- Exact colors, fonts, font sizes, line heights, letter spacing
- Spacing and padding (maps to `--space-*` variables)
- Component structure and variants
- Text content (headings, body copy)
- Layout and responsive breakpoints

### Constraints

- All CSS must use `var(--color-*)` and `var(--space-*)` design tokens (no hardcoded values)
- All templates must use `get_header('marketing')` and `get_footer('marketing')`
- Components go in `/components/` as PHP partials
- Pages go in `page-*.php` templates
- Styling is `assets/marketing.css` only

### Figma MCP Configuration

- **Server**: `figma-developer-mcp` (installed on demand via npx)
- **API Key**: Stored in `.claude/settings.local.json` (gitignored)
- **Config**: `.mcp.json` (approved in `.claude/settings.json`)

No additional setup needed — just paste a Figma link.

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
├── header-home.php              ← SHOPPER header (Calm Intelligence)
├── header-agent.php             ← AGENT header (Calm Intelligence)
├── footer-home.php              ← Global footer (both sides)
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

### Site Chrome — Two Headers, One Footer

The redesigned ("Calm Intelligence") pages split the site in two by audience.
**Pick the header by which side of the site the page belongs to**, not by how it
looks:

| | Header | Used for |
|---|---|---|
| **Shopper side** | `get_header('home')` → `header-home.php` | Homepage, coverage pages, quote/insurance forms. Has shopper nav + "Start My Auto Quote Request" CTA. |
| **Agent side** | `get_header('agent')` → `header-agent.php` | Agency sign-up, login, dashboard, account management. Logo only — no nav, no buttons. |

**Both sides share one footer**: `get_footer('home')` → `footer-home.php`.

Rules that are easy to get wrong:

- `footer-home.php` closes `</body></html>` itself, so **the header and footer
  must be swapped together**. Mixing `get_header()` (Kadence) with
  `get_footer('home')` leaves Kadence's `#wrapper` / `#inner-wrap` unclosed.
- Both headers are styled by `assets/home.css` via the shared `.site-header` /
  `.container` / `.header-inner` / `.brand` classes, so any page using them must
  enqueue `home.css` (and `home.js`, which the footer's sticky CTA depends on).
- `footer-home.php` ships the shopper mobile sticky CTA. On agent pages, hide it
  in the page's own CSS rather than editing the shared footer — see
  `assets/publish-your-agency.css`.
- Pages still on the **legacy Kadence chrome** (`get_header()` / `get_footer()`)
  include /login, /register and the rest of the un-migrated site. Migrating one
  means switching both header and footer at once.

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

### Branch Strategy

| Branch | Deploys to |
|--------|-----------|
| `staging` | staging19.ensurance.com |
| `main` | ensurance.com (production) |

Always work on `staging`. Merge to `main` only when verified on staging.

### Standard Workflow

```bash
# 1. Make sure you're on the staging branch
git checkout staging

# 2. Make your changes, then commit
git add .
git commit -m "Add hero section to homepage"

# 3. Deploy to staging and verify on staging19.ensurance.com
deploy-staging

# 4. When satisfied, promote to production
git checkout main
git merge staging
deploy-prod
```

### Before Every Push

- [ ] Changes are only in marketing pages (page-*.php, components/, assets/)
- [ ] No edits to `style.css`
- [ ] No edits to existing `functions.php` code (only new functions)
- [ ] CSS uses design tokens (var(--color-*), var(--space-*))
- [ ] All templates call `get_header('marketing')` and `get_footer('marketing')`

### Verify After Deploying to Production

1. Visit https://ensurance.com/
2. Verify the change appears
3. Check a GeoDirectory page (e.g., https://ensurance.com/insurance-agencies/) — should be unchanged

---

## SSH & Git Integration

### Connecting to SiteGround

SSH config (`~/.ssh/config`):

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

### Theme Paths on SiteGround

```bash
# Production
cd /home/u2514-jukueftqhhlm/www/ensurance.com/public_html/wp-content/themes/kadence-child/

# Staging
cd /home/u2514-jukueftqhhlm/www/staging19.ensurance.com/public_html/wp-content/themes/kadence-child/
```

### Deploy Aliases (`~/.zshrc`)

```bash
alias deploy-staging="git push origin staging && ssh ensurance /home/u2514-jukueftqhhlm/deploy-staging.sh"
alias deploy-prod="git push origin main && ssh ensurance /home/u2514-jukueftqhhlm/deploy-prod.sh"
```

### Deploy Scripts on SiteGround

- `/home/u2514-jukueftqhhlm/deploy-staging.sh` — pulls `staging` branch to staging site
- `/home/u2514-jukueftqhhlm/deploy-prod.sh` — pulls `main` branch to production site

---

## Troubleshooting

### Change doesn't appear after deploy

1. Verify push succeeded: `git log --oneline` (should show your commit)
2. Hard-refresh browser: `Cmd+Shift+R`
3. Check what's on SiteGround:
   ```bash
   ssh ensurance "git -C /home/u2514-jukueftqhhlm/www/staging19.ensurance.com/public_html/wp-content/themes/kadence-child log --oneline -5"
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
- [x] Set up staging environment on SiteGround (staging19.ensurance.com — complete)
- [ ] Add automated tests

---

## Questions?

Refer to this file when making changes. If something is unclear, update this document so future developers (and future you) don't get stuck.

---

**Last Updated**: 2026-05-05  
**Maintained By**: Team
