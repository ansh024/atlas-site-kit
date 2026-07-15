# Ranked International Pages (WordPress companion plugin)

Adds the Ranked International marketing pages on top of any active WordPress
theme, without touching the theme at all: no `header.php`, `footer.php`, or
theme `functions.php` edits. Install it, uninstall it, and the rest of the
site is untouched either way.

## Recent case-study presentation updates (2026-07-16)

The case-study presentation was refined in both the static source site and
the WordPress plugin:

- Client logos were removed from individual case-study heroes.
- Hero outcome rows are now three compact horizontal result cards, rather
  than a tall stacked table. On mobile the cards remain readable without
  expanding the hero unnecessarily.
- The mobile case-study hero now has a dark navigation bar, a tighter top
  offset, a shorter audit CTA, and the “View live site” link beside it.
- Context pills below a case-study hero stack full-width on mobile, with
  explicit icon/text spacing.
- The Case Studies hub hero is text-only, centre-aligned, compact, and
  white on both desktop and mobile.
- Hero CTA entrance animation no longer applies a vertical `18px` offset.

For any future case-study styling or JavaScript update, make the source edit
in the repo root and synchronise it before deployment:

| Source of truth | Plugin copy |
|---|---|
| `css/case-study.css` | `assets/css/case-study.css` |
| `js/main.js` | `assets/js/main.js` |
| `js/main.min.js` (static pages only) | N/A |

## Safe local development and testing

This repository includes an isolated local WordPress environment powered by
the official `@wordpress/env` package, Docker, ACF, WP-CLI, and Playwright.
It does not read production credentials, import the client database, or call
the production site. The safety guard refuses any base URL except
`http://localhost:8891` or `http://127.0.0.1:8891`.

Prerequisites: Docker Desktop running, Node.js 22+, and npm.

```bash
npm install
npx --no-install playwright install chromium
npm run wp:start
```

Local URLs:

- Service preview: `http://localhost:8891/local-seo-services/`
- WordPress admin: `http://localhost:8891/wp-admin/`
- Local-only login: `admin` / `password`

Common commands:

```bash
npm run wp:test        # smoke + desktop/mobile/reduced-motion browser tests
npm run wp:test:smoke  # routing, schema, SEO tags, plugin state, legacy-copy guard
npm run wp:test:e2e    # browser interactions and real local AJAX submission
npm run wp:stop        # stop containers, retain the local database
npm run wp:reset       # erase and rebuild only the disposable local database
npm run wp:destroy     # destroy only this repository's wp-env containers/volumes
```

`wp:start` mounts `wp-plugin/ranked-international/` directly, installs a fresh
local WordPress + ACF, activates the plugin, publishes the Local SEO fixture in
the disposable database, and flushes local rewrites. Source edits are reflected
without copying files into a client site.

Production publishing is manual. Pushing to `main` no longer deploys anything.
The `Publish WordPress plugin` GitHub workflow must be deliberately dispatched;
it runs this same local WordPress test suite before the publish job can begin.

For the completed Local SEO reference-page implementation, responsive review
decisions, and its local-only publish boundary, see
[`docs/05-local-seo-service-template.md`](../../docs/05-local-seo-service-template.md).

There are two different mechanisms in this plugin, for two different needs:

1. **Home and the Case Studies hub** are one-off, hand-built **Page
   Templates** — pick them from Page Attributes on a Page, like any theme
   template.
2. **Industry Pages** (e.g. Construction SEO), **Service Pages** (e.g. Local SEO), and **Case Studies** (e.g.
   Alexis Delivery Service) are **Custom Post Types with ACF fields**. These
   are the pages the client needs to duplicate and edit going forward, so
   instead of a PHP file per page, there's one shared template per type and
   a field editor in wp-admin — Add New → fill in the fields → Publish.

## Install

1. Install **Advanced Custom Fields** (free), from the WP plugin directory,
   if it isn't already on the site. Required for #2 above — without it, the
   Industry Page / Case Study "Add New" screens won't show any fields.
2. Zip this folder (`ranked-international/`) — the zip's top-level folder
   name must be `ranked-international`.
3. WP Admin → Plugins → Add New → Upload Plugin → upload the zip → Activate.
4. On activation the plugin automatically:
   - flushes permalinks so the new URLs work immediately, and
   - seeds the existing content — the Construction Industry Page, Local SEO
     reference Service Page, and all 6 Case Studies — as **drafts**, so nothing from the current site is lost
     and nothing new goes live. Activating the plugin changes zero public
     URLs; every page goes live only by an explicit action in wp-admin.
5. Create two Pages for the hand-built templates, and assign each via Page
   Attributes → Template:

   | Page slug | Template |
   |---|---|
   | `/` (site home, or whatever page you set as the front page) | Ranked Intl: Home |
   | `/case-studies/` | Ranked Intl: Case Studies (Hub) |

### Go-live is a per-page switch

Nothing ships automatically. Each page goes live only when you flip its
switch, and each is independently reversible:

| Page | Goes live when… | Roll back by… |
|---|---|---|
| Home | you assign "Ranked Intl: Home" to a Page (or the front page) | switching the Page's template back |
| Case Studies hub | you assign "Ranked Intl: Case Studies (Hub)" to a `/case-studies/` Page | same |
| Each case study | you Publish its seeded draft under **Case Studies** | reverting it to draft |
| Construction | you Publish its seeded draft under **Industry Pages** AND trash/re-slug the old `/construction/` Page (the old Page wins until then) | restoring the old Page from trash |
| Local SEO | you Publish its seeded draft under **Service Pages** AND trash/re-slug the old `/local-seo-services/` Page (the old Page wins until then) | restoring the old Page from trash |

## How the client adds a new Industry, Service, or Case Study page

1. WP Admin → **Industry Pages**, **Service Pages**, or **Case Studies** → Add New.
2. Give it a title and a URL slug (Industry Pages are top-level, e.g. `hvac`
   → `/hvac/`; Case Studies are always under `/case-studies/`, e.g.
   `acme-plumbing` → `/case-studies/acme-plumbing/`).
3. Fill in the field editor below the title — hero copy, stats, services,
   FAQ, etc. Every field has an inline instruction describing what it
   controls and, for anything that accepts HTML (like `<em>`), where it's
   safe to use it.
4. Publish. No developer, no code, no template picker.

The easiest way to "duplicate" an existing page for a new one: open an
existing Industry Page or Case Study in wp-admin, use **Duplicate Post** (or
any duplication plugin) or just copy field values across manually into a new
"Add New" screen, then edit.

### Field group overview

- **Case Study Details** (`acf-json/group_rip_case_study.json`) — client
  name/logo, hero title/stats, a traffic chart (see below), context pills,
  3 strategy cards, results metrics, an optional keyword table, and up to 2
  related case studies (picked from existing posts).
- **Industry Page Details** (`acf-json/group_rip_industry_page.json`) — hero
  (title/stats/visual/checklist chips), a trusted-by logo marquee, proof
  stats, "who we rank" segment cards, an optional Ranked-vs-agency comparison
  table, 1-3 rotating client spotlight quotes, a 6-chip services diagram,
  process steps, and FAQ.

All field groups are bundled as ACF "local JSON" (`acf-json/`), so they
appear automatically the moment ACF is active — nothing to configure by hand.

- **Service Page Details** (`acf-json/group_rip_service_page.json`) — controlled
  service identity and evidence-object type, outcomes, diagnostic problems,
  workstreams, proof policy, engagement phases, qualification, FAQs, optional
  long-form guide, CTA, and SEO metadata. Local SEO is seeded as the reference.
  Service URLs stay top-level and existing WordPress Pages always win, making
  migration reversible page by page.

### Construction industry template repairs (July 2026)

The original static page at `construction/index.html` remains the visual source
of truth for the shared Industry Page template. Two production fixes restored
the WordPress version to that reference:

- Commit `2d42ca3` restored the construction-only responsive CSS that had not
  been carried into `templates/template-industry-page.php`. This includes the
  desktop/tablet/mobile hero layout, hero visual and floating proof callouts,
  proof-stat grid, industry segment cards, comparison table, and compact
  services diagram. It also restored the page-specific GSAP reveals and makes
  the comparison table round its actual final ACF row.
- Commit `d28913e` made the client-results carousel fail-safe. The first ACF
  testimonial is rendered with `is-active`, active cards and metric panels are
  visible without waiting for JavaScript, and GSAP no longer pre-hides their
  inner text before the section's scroll trigger fires. This prevents empty
  navy/white carousel shells on preview loads, restored-scroll loads, or when
  animation initialization is delayed.

When changing this page, keep `js/main.js` synchronized with
`assets/js/main.js`. Any construction-only CSS or markup behavior added to the
static reference must also be reflected in the WordPress industry template.
The repairs were validated with PHP/JavaScript syntax checks, `git diff
--check`, desktop and mobile browser captures, an anchored `#results` load,
and computed-opacity checks for the active testimonial and metrics panel.

### The traffic chart

Case Studies show a small line chart of monthly organic traffic. Instead of
hand-plotting SVG coordinates (the original static build's approach, which a
non-developer can't realistically edit), the client just adds a repeater of
plain numbers ("Chart data points") — e.g. 200, 220, 250, 290... — and
`rip_render_sparkline()` in the main plugin file draws the line/fill/dot from
whatever numbers are entered, any count. This is the one deliberate
simplification from the original hand-tuned chart, in exchange for it being
editable at all.

### Design simplifications made for editability

A few things were traded for making these pages genuinely replicable,
per client sign-off:

- The **roofing** industry page was a placeholder and was **not** migrated —
  Construction (the client's real page) is the one seeded example and the
  template it's built from.
- The **services diagram** is a fixed 6-slot layout (icon/title/text per
  chip) — works with fewer than 6, but the wiring-diagram SVG assumes 6 for
  the layout to look right.
- Icons throughout (segments, services, process) are entered as **lucide.dev
  icon names** (e.g. `hard-hat`, `search`) rather than custom SVG files —
  simpler to type in a text field, at the cost of the small set of hand-drawn
  icons the original design used in a couple of spots.

## What "bypassing the theme" means

Each template (Page Template or CPT single template) is a full,
self-contained HTML document — `<!DOCTYPE html>` through `</html>` — not a
fragment dropped into the active theme's `page.php`. WordPress serves it in
place of the theme's normal template (via the `template_include` filter).
`wp_head()`, `wp_body_open()`, and `wp_footer()` are still called at the
right points, so SEO plugins (Yoast, RankMath), analytics snippets, and other
plugins that hook into those still work — the page just doesn't inherit the
theme's own header/nav/footer markup, fonts, or CSS.

## Assets

- `assets/css/styles.min.css`, `assets/css/case-study.css`, `assets/css/service.css` — site + case
  study styles, enqueued only on pages using one of these templates.
- `assets/js/main.js`, `assets/js/case-study.js`, `assets/js/service.js` — page behavior (nav,
  hero animation, FAQ accordion, the audit-form modal, GSAP scroll effects).
  Unminified on purpose, so future edits don't require a build step.
- `assets/images/` — the shared design-chrome images (logo, hero backgrounds,
  brand logo marks, icons). Client-uploaded images (case study logos,
  industry hero visuals) go through the WordPress Media Library via ACF
  image fields instead, once a page is created from wp-admin. A few original
  filenames had spaces and were renamed without spaces for URL safety
  (`brand-logos/`, `rankd-international-logo.png`, etc).
- GSAP, ScrollTrigger, Lucide icons, and Google Fonts load from their
  original CDNs/URLs, same as the static build — not bundled locally.

## Lead form (the "free SEO audit" modal)

Every page has the same multi-step modal form (`#auditForm`). In the original
static build it just showed a fake "thanks" step with no backend. Here:

- `assets/js/main.js` feature-detects a `window.RankdWP` global (injected via
  `wp_localize_script`, see `rip_enqueue_assets()` in the main plugin file).
  If present, it POSTs the lead to `admin-ajax.php`; the confirmation step
  still shows either way, so nothing changes visually.
- `ranked-international.php` → `rip_handle_audit_lead()` handles
  `action=rankd_audit_lead`: sanitizes input, emails the lead via `wp_mail()`
  to the site's admin email (filterable via `rip_audit_lead_recipient`), and
  fires `do_action( 'rip_audit_lead_captured', $lead )` so a real CRM webhook
  can be wired up later in a small mu-plugin or theme snippet — no need to
  edit this plugin.

## Deploying onto the EXISTING rankedinternational.com WordPress site

The live site already has ~35 pages (services, about, contact, blog, and 19
old industry pages at top-level slugs like /roofing/, /hvac/, /med-spa/),
managed by Yoast SEO. The plugin is designed so activating it changes
**none** of them:

- **Existing Pages always win.** Industry Page posts resolve top-level URLs
  only as a fallback (`rip_industry_url_fallback()`): if a WordPress Page
  already exists at that slug, it keeps serving exactly as before. So the
  seeded Construction post does NOT take over `/construction/` until you
  deliberately trash (or re-slug) the old Page — that's the cutover switch,
  page by page, reversible by restoring the old Page from trash.
- **No duplicate meta tags.** Our templates print their own `<title>`,
  meta description, and canonical. On our templates only, the plugin
  suppresses WP core's and Yoast's/RankMath's versions of those tags
  (`rip_dedupe_head_tags()`), so there's exactly one of each. All other
  pages keep their Yoast meta untouched.

### Verifying nothing broke (../meta-audit.py)

A snapshot of every live URL's SEO meta (title, description, canonical,
robots, og tags, h1, status) is in `../baseline-live.json`. After installing
the plugin — on a staging copy first, ideally — run:

    python3 meta-audit.py snapshot https://<staging-or-live-host> after.json
    python3 meta-audit.py diff baseline-live.json after.json

The diff separates **existing pages** (any change = regression, exit code 1)
from **redesign pages** (changes expected — just review them). It also flags
duplicate `<title>`/canonical tags, the classic symptom of an SEO-plugin
conflict.

## CI/CD: pushing template updates from local → live

Templates, CSS, and JS live in this plugin's **files**; all page copy and
data live in the WordPress **database** (posts + ACF fields). Deploying new
plugin files therefore updates the design/layout everywhere while leaving
every word of live content untouched — that separation is structural, not a
convention to be careful about.

The pipeline (`.github/workflows/publish-wp-plugin.yml` in the repo root):

1. You edit templates/CSS/JS locally and push to `main`.
2. GitHub Actions lints the PHP, stamps a new version number (so WordPress
   sees an update and browser caches bust), and force-publishes just this
   plugin folder to the **`plugin-deploy`** branch.
3. The WordPress site pulls it via **Git Updater** (free plugin,
   git-updater.com). The `GitHub Plugin URI` / `Primary Branch` headers in
   `ranked-international.php` tell it what to track.

One-time setup on the WordPress site:

1. Install the Git Updater plugin and activate it.
2. The repo (`ansh024/atlas-site-kit`) is public, so no GitHub token is
   needed — Git Updater's free tier reads it directly. (A token in
   Settings → Git Updater is optional, only to raise API rate limits.)
3. **Push-based updates (recommended — polling is unreliable on shared
   hosting, whose IPs often exhaust GitHub's anonymous API rate limit):**
   in wp-admin go to Settings → Git Updater → **Remote Management** tab and
   copy the REST API key. Then in GitHub: repo → Settings → Secrets and
   variables → Actions → New repository secret:
   - Name: `GITUPDATER_WEBHOOK_URL`
   - Value: `https://rankedinternational.com/wp-json/git-updater/v1/update/?key=<THE-KEY>&plugin=atlas-site-kit&tag=plugin-deploy`

   The CI workflow calls that URL after each publish, so the site installs
   the new version within a minute of every push — no update screens at all.
   (Pasting that same URL in a browser manually forces an update any time.)

What a deploy can and cannot touch:

- **Updated by a deploy:** templates (`templates/`), styles/scripts
  (`assets/css`, `assets/js`), plugin logic (`includes/`,
  `ranked-international.php`), ACF field definitions (`acf-json/`).
- **Never touched:** published/draft pages, all field content the client
  entered, media library uploads, leads, Yoast settings. The content seed is
  guarded by a one-time option flag, so redeploys never re-run it.
- **Caveat — field renames:** editing a field's *label* or instructions in
  `acf-json/` is free. Renaming a field's `name`/key orphans the content
  stored under the old name; treat that as a migration, not a deploy.

## Things to double-check before a real deploy

- Placeholder phone number / contact details in the templates — confirm
  they're the client's real numbers before launch.
- `assets/images/` is ~28MB (mostly the hero frame sequence and video files)
  — fine for a plugin, but worth confirming against the host's upload size
  limit before uploading the zip through wp-admin (vs. SFTP).
- Google Fonts and the GSAP/Lucide CDNs are third-party requests; if the
  client has a strict CSP or wants everything self-hosted, those `<link>`/
  `<script>` tags in each template's `<head>`/footer are the ones to swap.
- `rip_audit_lead_recipient` defaults to the site's admin email — confirm
  that's where the client wants leads to land, or filter it to a different
  address.
- PHP 7.4+ required (the seed script uses arrow functions) — standard on any
  current WordPress host.
- The activation seed only runs once (guarded by an option flag). If you
  need to re-run it (e.g. on a fresh staging copy of the database), delete
  the `rip_content_seeded` option first.
