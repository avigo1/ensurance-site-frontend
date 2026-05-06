# Phase 1 — Infrastructure & Deployment Pipeline

**Status**: Complete.
**Goal**: Establish the GitHub repository and connect it to SiteGround with a staging-first deployment pipeline.

---

## What Was Built

### GitHub Repository ✅
- Repo: https://github.com/avigo1/ensurance-site-frontend
- Two branches:
  - `staging` → deploys to staging14.ensurance.com
  - `main` → deploys to ensurance.com (production)

### SiteGround Connection ✅

Both theme directories are connected to GitHub:

| Environment | Theme Path | Branch |
|-------------|-----------|--------|
| Production | `/home/u2514-jukueftqhhlm/www/ensurance.com/public_html/wp-content/themes/kadence-child/` | `main` |
| Staging | `/home/u2514-jukueftqhhlm/www/staging14.ensurance.com/public_html/wp-content/themes/kadence-child/` | `staging` |

### Deploy Scripts on SiteGround ✅
- `/home/u2514-jukueftqhhlm/deploy-staging.sh` — pulls `staging` branch to staging site
- `/home/u2514-jukueftqhhlm/deploy-prod.sh` — pulls `main` branch to production site

### Local Deploy Aliases ✅ (configured in `~/.zshrc`)
```bash
alias deploy-staging="git push origin staging && ssh ensurance /home/u2514-jukueftqhhlm/deploy-staging.sh"
alias deploy-prod="git push origin main && ssh ensurance /home/u2514-jukueftqhhlm/deploy-prod.sh"
```

### Production Backup ✅
- Saved at: `/home/u2514-jukueftqhhlm/kadence-child-prod-backup-20260506`

---

## Standard Development Workflow

```
1. Work on local `staging` branch
2. deploy-staging  →  verify on staging14.ensurance.com
3. git checkout main && git merge staging
4. deploy-prod     →  live on ensurance.com
```

---

## Critical Finding: WordPress `page-{slug}.php` Auto-Apply

**What happened:** When `page-home.php` was first deployed to production, WordPress automatically applied it as the homepage template — replacing the existing Gutenberg homepage — without any action in WP Admin.

**Why it happens:** WordPress's template hierarchy automatically uses `page-{slug}.php` for any WordPress page whose slug matches the filename. The homepage's slug is `home`, so `page-home.php` was applied immediately on deploy.

**Impact on the plan:** `page-home.php` was removed from the `main` branch. It exists only on `staging` for Phase 3 development. This means:

1. **Deploying `page-home.php` to `main` IS the homepage launch switch.** There is no separate WP Admin step needed.
2. **Do not merge `staging` to `main` carelessly.** Once `page-home.php` appears on `main`, it goes live instantly.
3. The Phase 4 go-live step is simply: merge `page-home.php` to `main` and run `deploy-prod`.

**Current state of `page-home.php`:**
- `staging` branch: present (placeholder, will be built out in Phase 3)
- `main` branch: intentionally absent until Phase 4 go-live

---

## SSH Reference

```bash
# Connect to SiteGround
ssh ensurance

# Full command if alias fails
ssh -i ~/.ssh/siteground -p 18765 u2514-jukueftqhhlm@ssh.ensurance.com
```

---

## Rollback Plan

If production breaks after a deploy:

```bash
# Revert last commit
git revert HEAD --no-edit
deploy-prod
```

If `style.css` is corrupted or deleted:
```bash
ssh ensurance "cp /home/u2514-jukueftqhhlm/kadence-child-prod-backup-20260506/style.css \
  /home/u2514-jukueftqhhlm/www/ensurance.com/public_html/wp-content/themes/kadence-child/style.css"
```

---

## Exit Criteria — All Met ✅

- [x] `staging` branch exists in GitHub
- [x] Backups of production theme exist on SiteGround
- [x] Staging theme connected to `staging` branch
- [x] Production theme connected to `main` branch
- [x] `style.css` intact on both staging and production
- [x] `deploy-staging` pipeline tested and working
- [x] GeoDirectory listing pages unaffected on production

---

## What Comes Next

**Phase 2** — Build `header-marketing.php` and `footer-marketing.php` with actual design. All work on the `staging` branch.
