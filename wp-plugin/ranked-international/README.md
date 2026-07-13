# Ranked International Pages (WordPress companion plugin)

Adds the Ranked International marketing pages on top of any active WordPress
theme, without touching the theme at all: no `header.php`, `footer.php`, or
theme `functions.php` edits. Install it, uninstall it, and the rest of the
site is untouched either way.

There are two different mechanisms in this plugin, for two different needs:

1. **Home and the Case Studies hub** are one-off, hand-built **Page
   Templates** — pick them from Page Attributes on a Page, like any theme
   template.
2. **Industry Pages** (e.g. Construction SEO) and **Case Studies** (e.g.
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
   - seeds the existing content — the Construction Industry Page and all 6
     Case Studies — as real posts, so nothing from the current site is lost.
5. Create two Pages for the hand-built templates, and assign each via Page
   Attributes → Template:

   | Page slug | Template |
   |---|---|
   | `/` (site home, or whatever page you set as the front page) | Ranked Intl: Home |
   | `/case-studies/` | Ranked Intl: Case Studies (Hub) |

That's it — Construction and the 6 case studies are already live at
`/construction/` and `/case-studies/<slug>/` from the activation seed.

## How the client adds a new Industry Page or Case Study

1. WP Admin → **Industry Pages** (or **Case Studies**) → Add New.
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

Both field groups are bundled as ACF "local JSON" (`acf-json/`), so they
appear automatically the moment ACF is active — nothing to configure by hand.

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

- `assets/css/styles.min.css`, `assets/css/case-study.css` — site + case
  study styles, enqueued only on pages using one of these templates.
- `assets/js/main.js`, `assets/js/case-study.js` — page behavior (nav,
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
