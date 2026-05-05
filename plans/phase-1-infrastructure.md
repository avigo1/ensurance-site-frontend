# Phase 1 — Infrastructure & Deployment Pipeline

**Status**: Mostly complete. One step remaining (Day 2 SiteGround connection).  
**Goal**: Establish the GitHub repository and connect it to SiteGround so every `git push` deploys to production.

---

## Context for a New Session

- **Live site**: https://ensurance.com (WordPress on SiteGround, Kadence child theme)
- **GitHub repo**: https://github.com/avigo1/ensurance-site-frontend
- **Local repo path**: `/Users/austinvigo/Documents/Github/ensurance-site-frontend/`
- **Theme directory on SiteGround**: `/home/u2514-jukueftqhhlm/public_html/wp-content/themes/kadence-child/`
- **SSH command**: `ssh ensurance` (configured in `~/.ssh/config` — Host=ssh.ensurance.com, Port=18765, User=u2514-jukueftqhhlm, Key=~/.ssh/siteground)

### What This Phase Is About

Ensurance.com is a WordPress site hosted on SiteGround. The frontend is a Kadence child theme (directory: `kadence-child`). The goal of this phase is to:

1. Version-control the theme files in GitHub (done)
2. Connect SiteGround's `kadence-child` directory to GitHub so that pushes auto-deploy (not yet done)

The repo is the **source of truth**. SiteGround should mirror it exactly, except for `style.css` which is intentionally excluded via `.gitignore` (it contains ~2000 lines of legacy GeoDirectory CSS that must remain untouched).

---

## What's Already Done

### GitHub Repository ✅
- Repo created: https://github.com/avigo1/ensurance-site-frontend
- Initial commit pushed with all foundational files:
  - `functions.php` — ALL existing GeoDirectory/UserWP code + new marketing enqueue function
  - `header-marketing.php` — Marketing header stub
  - `footer-marketing.php` — Marketing footer stub
  - `page-home.php` — Homepage template stub
  - `assets/marketing.css` — Full design token system + base styles
  - `assets/marketing.js` — Mobile nav toggle + smooth scroll
  - `CLAUDE.md` — Project documentation
  - `.gitignore` — Excludes `style.css` and `kadence-style-child-min.css`

---

## What Remains — Day 2: Connect SiteGround to GitHub

### Step 1 — Backup the Existing Theme on SiteGround

```bash
ssh ensurance
cp -r /home/u2514-jukueftqhhlm/public_html/wp-content/themes/kadence-child \
      /home/u2514-jukueftqhhlm/kadence-child-backup-$(date +%Y%m%d)
ls -la /home/u2514-jukueftqhhlm/ | grep kadence-child-backup
```

### Step 2 — Verify Current State of kadence-child

```bash
ls -la /home/u2514-jukueftqhhlm/public_html/wp-content/themes/kadence-child/
```

Expected files (minimum): `style.css`, `functions.php`, `kadence-style-child-min.css`

### Step 3 — Initialize Git and Connect to GitHub

```bash
cd /home/u2514-jukueftqhhlm/public_html/wp-content/themes/kadence-child/
git init
git config user.email "austinvigo@gmail.com"
git config user.name "Austin Vigo"
git remote add origin https://github.com/avigo1/ensurance-site-frontend.git
```

### Step 4 — Deploy GitHub Files to SiteGround

```bash
git fetch origin main
git checkout -b main --track origin/main
```

If the branch already exists or there are conflicts:
```bash
git fetch origin main
git reset --hard origin/main
```

**Note**: This overwrites `functions.php` with the GitHub version. That is intentional — the GitHub version contains all the original GeoDirectory/UserWP code plus the new marketing enqueue. `style.css` will NOT be touched (excluded via `.gitignore`).

### Step 5 — Verify the Deployment

```bash
ls -la /home/u2514-jukueftqhhlm/public_html/wp-content/themes/kadence-child/
```

Expected new files: `header-marketing.php`, `footer-marketing.php`, `page-home.php`, `assets/marketing.css`, `assets/marketing.js`

Expected untouched files: `style.css`, `kadence-style-child-min.css`

**If `style.css` is missing — STOP and restore from backup.**

### Step 6 — Set Up Auto-Deploy (Deploy Script)

SiteGround does not support incoming webhooks on shared hosting. Set up a pull script:

```bash
cat > /home/u2514-jukueftqhhlm/deploy-theme.sh << 'EOF'
#!/bin/bash
cd /home/u2514-jukueftqhhlm/public_html/wp-content/themes/kadence-child/
git pull origin main
EOF
chmod +x /home/u2514-jukueftqhhlm/deploy-theme.sh
```

To deploy from local:
```bash
git push && ssh ensurance '/home/u2514-jukueftqhhlm/deploy-theme.sh'
```

Optional: add a shell alias to `~/.zshrc` on the local machine:
```bash
alias deploy-theme="git push && ssh ensurance '/home/u2514-jukueftqhhlm/deploy-theme.sh'"
```

### Step 7 — Test the Pipeline

```bash
cd /Users/austinvigo/Documents/Github/ensurance-site-frontend
echo "<!-- pipeline test -->" >> page-home.php
git add page-home.php
git commit -m "Test deployment pipeline"
git push && ssh ensurance '/home/u2514-jukueftqhhlm/deploy-theme.sh'
ssh ensurance "grep 'pipeline test' /home/u2514-jukueftqhhlm/public_html/wp-content/themes/kadence-child/page-home.php"
```

If the grep returns a match, the pipeline works. Revert:
```bash
git revert HEAD --no-edit
git push && ssh ensurance '/home/u2514-jukueftqhhlm/deploy-theme.sh'
```

### Step 8 — Verify the Live Site Is Unbroken

Visit these pages on https://ensurance.com and confirm they look normal:
1. Any GeoDirectory listing page (e.g., `/insurance-agencies/`)
2. An agent profile page
3. The blog

The homepage may look different at this point — that is expected.

---

## Rollback Plan

```bash
ssh ensurance
cp -r /home/u2514-jukueftqhhlm/kadence-child-backup-YYYYMMDD/* \
      /home/u2514-jukueftqhhlm/public_html/wp-content/themes/kadence-child/
```

Replace `YYYYMMDD` with the date used in Step 1.

---

## Exit Criteria

Phase 1 is complete when:
- [ ] Backup of kadence-child exists on SiteGround
- [ ] All GitHub files are present in SiteGround's kadence-child directory
- [ ] `style.css` is still present and untouched on SiteGround
- [ ] A `git push` + deploy script call updates SiteGround within ~30 seconds
- [ ] GeoDirectory listing pages still render correctly on the live site

---

## What Comes Next

**Phase 2** — Build the actual header and footer designs.
