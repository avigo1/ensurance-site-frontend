# Phase 4 — QA, Review & Go-Live

**Status**: Not started. Awaiting Phase 3 completion.  
**Goal**: Perform final QA, get stakeholder sign-off, and switch the live WordPress homepage to use the new `page-home.php` template.

---

## Context for a New Session

- **Live site**: https://ensurance.com (WordPress on SiteGround, Kadence child theme)
- **GitHub repo**: https://github.com/avigo1/ensurance-site-frontend
- **Local repo path**: `/Users/austinvigo/Documents/Github/ensurance-site-frontend/`
- **WordPress admin**: https://ensurance.com/wp-admin
- **Deploy command**: `git push && ssh ensurance '/home/u2514-jukueftqhhlm/deploy-theme.sh'`

### Prerequisites

- Phase 1 complete (GitHub → SiteGround pipeline working)
- Phase 2 complete (production header and footer built)
- Phase 3 complete (all homepage components built and tested)

### What This Phase Is

All code is written. Phase 4 is about verifying everything works correctly and then making the switch so visitors see the new homepage. The key action in WordPress admin is assigning the "Home Page (Marketing)" template to the existing homepage page.

---

## Pre-Launch QA Checklist

### Visual QA

Run through the homepage at each breakpoint and verify:

| Breakpoint | Device | Check |
|------------|--------|-------|
| 1440px | Large desktop | All sections layout correctly |
| 1200px | Standard desktop | No overflow |
| 1024px | Small desktop / large tablet | Container padding adjusts |
| 768px | Tablet (landscape) | Mobile nav triggers |
| 390px | iPhone 14 | Single column, no horizontal scroll |
| 375px | iPhone SE | No clipped content |

For each breakpoint check:
- [ ] No horizontal scrollbar
- [ ] No text overflow or clipping
- [ ] Images load and display correctly
- [ ] Buttons are full-width on mobile (if applicable)
- [ ] Logo is visible in header and footer

### Functional QA

- [ ] Header CTA button links to the correct page
- [ ] All nav links in the header go to the correct destinations
- [ ] All footer links go to the correct destinations
- [ ] Mobile hamburger menu opens and closes
- [ ] Mobile nav links work and close the menu on tap
- [ ] Any CTA buttons on the homepage link correctly
- [ ] Page does not load Kadence's native header or footer

### Cross-Browser QA

Test on:
- [ ] Chrome (Mac)
- [ ] Safari (Mac)
- [ ] Safari (iOS — most critical for mobile)
- [ ] Chrome (Android)
- [ ] Firefox (optional)

### Performance QA

Run https://ensurance.com/ through [PageSpeed Insights](https://pagespeed.web.dev/) after switching the template:

Targets:
- Mobile score: 70+
- Desktop score: 85+
- Largest Contentful Paint (LCP): under 3.5s
- Cumulative Layout Shift (CLS): under 0.1

Common issues to fix if scores are low:
- Images not sized correctly — add `width` and `height` attributes
- Fonts loading slowly — add `<link rel="preconnect" href="https://fonts.googleapis.com">` in `header-marketing.php`
- Unused CSS — confirm `marketing.css` only loads on marketing pages

### Regression QA — Existing Pages Must Be Unaffected

The most critical check: confirm existing pages still work correctly.

- [ ] Visit `/insurance-agencies/` — should load with Kadence header/footer
- [ ] Visit an agent profile page — should load with Kadence header/footer
- [ ] Visit `/blog/` — should load with Kadence header/footer
- [ ] Visit `/login/` (UserWP) — should load with Kadence header/footer
- [ ] Visit a purchased lead page (e.g., `/lead-page/?id={any-test-id}`) — shortcode should still render

---

## Go-Live: Switch the WordPress Homepage Template

This is the single most important step. Until this is done, visitors see the old Gutenberg homepage. After this is done, they see the new PHP template.

### Step 1 — Assign the Template in WordPress Admin

1. Log into https://ensurance.com/wp-admin
2. Go to **Pages**
3. Find the page that is set as the static front page (check Settings → Reading if unsure)
4. Edit that page
5. In the right sidebar, find **Page Attributes** → **Template**
6. Change from the current template to **"Home Page (Marketing)"**
7. Click **Update**

### Step 2 — Verify the Switch

1. Visit https://ensurance.com/ in an incognito window
2. Confirm the new header, footer, and homepage sections appear
3. Confirm the old Gutenberg content is gone
4. Check that `marketing.css` and `marketing.js` are loading (DevTools → Network tab)

### Step 3 — Check the Console for Errors

In DevTools → Console:
- [ ] No JavaScript errors
- [ ] No 404 errors for CSS or JS files
- [ ] No PHP errors (would appear as broken HTML in the page source)

---

## Rollback Plan

If anything goes wrong after the template switch:

### Option A — Revert the WordPress Template (30 seconds)

1. WordPress admin → Pages → Edit the homepage
2. Template → Change back to the previous template
3. Click Update

This is instant and does not require any code changes.

### Option B — Revert the Code (if a code bug caused the issue)

```bash
git revert HEAD
git push && ssh ensurance '/home/u2514-jukueftqhhlm/deploy-theme.sh'
```

Then switch the WordPress template back (Option A).

---

## Post-Launch Monitoring

After go-live, monitor for 30 minutes:

- [ ] Check https://ensurance.com/ from a fresh browser — correct page renders
- [ ] Check GeoDirectory listing pages — still working
- [ ] Check the lead purchase flow (checkout.html, lead-page shortcode) — still working
- [ ] Check that the Incoming Lead Make scenario still fires correctly (submit a test lead)

---

## Exit Criteria

Phase 4 is complete when:
- [ ] All visual QA items pass at all breakpoints
- [ ] All functional QA items pass
- [ ] Regression QA confirms existing pages are unaffected
- [ ] WordPress homepage page is using the "Home Page (Marketing)" template
- [ ] Visitors to https://ensurance.com/ see the new homepage design
- [ ] No JavaScript errors in the console
- [ ] PageSpeed score meets targets
- [ ] Stakeholder sign-off received

---

## What Comes Next (Future Phases)

After the homepage is live:

- **About Page** — Create `page-about.php` using the same header/footer + relevant components
- **Contact Page** — Create `page-contact.php`
- **Pricing Page** — Create `page-pricing.php` (agent pricing tiers)
- **WordPress Nav Menus** — Replace hardcoded nav links in `header-marketing.php` with WordPress-managed menus
- **Staging Environment** — Set up a SiteGround staging site for QA before pushing to production
