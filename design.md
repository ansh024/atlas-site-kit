# Ranked International — Design System

Single source of truth for building any new page (services, case studies, industries, about, contact) so it looks and moves like the homepage. The homepage (`index.html`, `css/styles.css`, `js/main.js`) is the living reference implementation — when in doubt, copy from it.

---

## 1. Brand in one paragraph

Ranked International is a Dallas SEO agency for local trades and clinics (roofers, HVAC, med spas, dentists, law firms). The brand voice is **direct, falsifiable, outcome-obsessed**: we talk about "booked jobs" and "calls," never "visibility" or "impressions." Visually: **airy, white-dominant, editorial** pages punctuated by deep navy sections and ONE loud chartreuse payoff moment. Confident B2B, not startup-cute.

**Key claims that can appear on any page:** one client per industry per city · no 12-month contracts · reports end in leads booked · you get a real person, not a ticket · 100+ businesses ranked · 5-star rated on Google.

---

## 2. Color system

Defined as CSS custom properties on `:root` (copy verbatim into any page, or link the shared stylesheet):

```css
--ocean-twilight:#1046ba;  /* primary blue — links, accents, mid backgrounds, gradients */
--egyptian-blue:#06348e;   /* deeper blue — gradient partner for ocean-twilight */
--prussian-blue:#0a1529;   /* near-black navy — dark sections, footer, headings, dark text */
--chartreuse:#caff00;      /* THE accent — CTAs, highlights, payoff moments. RARE & LOUD */
--bright-snow:#f8f7f6;     /* default page background */
--white:#ffffff;           /* cards, alternating sections */

--ink:#0a1529;             /* default body text */
--ink-soft:#46506a;        /* muted text on light backgrounds */
--line:#e7e6e3;            /* hairline borders/dividers on light */
```

### Usage rules (non-negotiable)

1. **~60% of every page is light** (bright-snow or white). Airy is the differentiator.
2. **Blues** carry: hero backgrounds, 1–2 dark sections per page, the footer. Dark sections use `--prussian-blue` (or `#060d1a` for the extra-dark process variant); saturated blue sections use `linear-gradient(160deg, var(--ocean-twilight), var(--egyptian-blue))`.
3. **Chartreuse is rare.** Allowed uses: primary CTA button, eyebrow dot, highlight-sweep marker, one full-bleed CTA panel per page, key stat/icon accents on dark backgrounds, chartreuse `em` accent inside dark-section titles. If chartreuse is everywhere it stops meaning "win."
4. **Text:** `--ink` (prussian) on light; `#fff` / `--bright-snow` on dark. **Never pure black** (`#000`).
5. Muted text on dark: `rgba(255,255,255,.7)` body, `.35–.5` for labels/footers.
6. Hairlines on dark: `rgba(255,255,255,.1–.12)`.

### Section background rhythm

Alternate light ↔ saturated. Homepage rhythm as the template:

```
Nav (transparent→glass) → Hero (dark navy + bg image) → dark navy strip (trusted bar)
→ white → white → dark image section → white → blue gradient → near-black
→ white → CHARTREUSE CTA → prussian footer
```

Rules of thumb per page: **max 2–3 dark blocks, exactly 1 chartreuse block** (the final CTA), everything else light. Dark hero → light body is the default opening move.

---

## 3. Typography

Google Fonts, loaded in `<head>`:

```html
<link rel="preconnect" href="https://fonts.googleapis.com">
<link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
<link href="https://fonts.googleapis.com/css2?family=Inter+Tight:ital,wght@0,400;0,500;0,600;0,700;0,800;1,500&family=Inter:wght@400;500;600&family=Instrument+Serif:ital@0;1&display=swap" rel="stylesheet">
```

```css
--font-display:"Inter Tight",system-ui,sans-serif;  /* all headings, buttons, stats, labels */
--font-body:"Inter",system-ui,sans-serif;           /* body copy, forms */
--font-serif:"Instrument Serif",Georgia,serif;      /* italic accent word ONLY */
```

### Type scale & rules

| Role | Spec |
|---|---|
| Hero H1 | display 800, `64px` (desktop) / `clamp(2.4rem,8vw,3.6rem)` mobile, `line-height:1.08`, `letter-spacing:0` |
| Section title (H2) | display 700, `clamp(2rem,5vw,3.6rem)`, `line-height:1.02`, `letter-spacing:0`, `max-width:16ch` |
| Big editorial statement | display 500, `clamp(1.625rem,4.05vw,3.8rem)`, `letter-spacing:0` |
| Card H3 | display 600–700, `1.05–1.65rem` |
| Body | body 400, `1rem–1.08rem`, `line-height:1.5–1.6`, color `--ink-soft` on light |
| Eyebrow / label | display 600–700, `.66–.82rem`, `letter-spacing:0`, UPPERCASE |
| Big stat number | display 800, `clamp(2.4rem,5vw,3.6rem)` up to `clamp(4rem,14vw,9rem)`, `letter-spacing:0`, `line-height:≤1` |

**The italic-serif accent word:** `em` renders as Instrument Serif italic globally (`em{font-family:var(--font-serif);font-style:italic;font-weight:400}`). Use on **ONE word per major headline**. Color: `--ocean-twilight` on light sections, `--chartreuse` on dark sections. Example: `Real Dallas businesses. <em>Real results.</em>`

- Letter-spacing is always `0` across all page typography, including headings, italic emphasis, labels, buttons, navigation, and body copy. Never use negative or positive tracking.
- Quotes/testimonials: Instrument Serif italic, `clamp(1.55rem,2.6vw,2.4rem)`.

---

## 4. Layout tokens

```css
--maxw:1200px;                 /* default content width */
--pad:clamp(20px,5vw,72px);    /* horizontal section padding */
--r:16px;                      /* default card radius */
--ease:cubic-bezier(.22,.61,.36,1);  /* house ease for CSS transitions */
```

- Content containers: `max-width:var(--maxw); margin:0 auto; padding:0 var(--pad)`. Wide sections (nav, hero, case studies, practice cards) use `1320–1440px`.
- Section vertical padding: `clamp(80px,10vw,140px)` standard; up to `clamp(90px,12vw,150px)` for feature sections.
- Radii: pills/buttons `999px` · cards `12–20px` · inputs `12px` · modals `18px` · mega menu `1.5rem`.
- Shadows: soft, blue-tinted, big negative spread — e.g. `0 10px 30px -20px rgba(10,21,41,.4)` (resting card), `0 30px 60px -30px rgba(16,70,186,.5)` (active/featured), `0 8px 24px -8px rgba(202,255,0,.6)` (chartreuse button glow).

---

## 5. Core components (copy the CSS from styles.css)

### Buttons (`.btn`)
Pill-shaped, display font 600, `padding:.95em 1.6em`, hover = `translateY(-2px)` + deeper shadow.

- `.btn--primary` — chartreuse bg, prussian text, chartreuse glow. **THE one primary CTA: "Get my free audit"** (opens the audit modal via `href="#audit"`). Every page ends in this.
- `.btn--dark` — prussian bg, white text.
- `.btn--outline` — white bg, ocean-twilight text/border. Secondary actions on light.
- `.btn--ghost-light` — transparent, white border. Secondary on dark.
- Sizes: `.btn--sm`, `.btn--lg` (16px/32px), `.btn--block`.

### Eyebrow (`.eyebrow`)
Small-caps label with a glowing chartreuse dot, sits above every section title:
```html
<p class="eyebrow"><span class="dot"></span> Case studies</p>
```
`.eyebrow--light` on dark sections. Hero variant: chartreuse uppercase text, no dot.

### Section head (`.section-head` + `.section-title`)
Standard opener for every section: eyebrow → H2 (with one `em` accent). `--light` / `--dark` modifiers for dark sections.

### Nav (fixed, transparent → glass)
Reuse the homepage `<header class="nav">` verbatim on every page (logo, Services mega menu, Industries mega menu, links, phone, chartreuse CTA button). Starts transparent with white text over the hero; JS toggles `.is-stuck` after 80px scroll → frosted bright-snow glass (`rgba(248,247,246,.85)` + `backdrop-filter:blur(14px)`) with ink text. **If a page has a light hero**, add `.is-stuck` styling from load or use a dark strip behind nav — never white-on-white.

Mega menus: fixed dropdown, dark glass (`rgba(10,21,41,.96)` + blur) when over hero, light when stuck; grid of icon+title+desc items, quick-links column, and a promo image card. Lucide icons.

### Footer
Reuse verbatim: prussian-blue, brand blurb + address, 4 link columns (SEO / Paid / Consulting / Company), base bar. Links hover chartreuse. Update `href`s as real pages come online.

### Audit modal (`#auditModal` + `form()` in main.js)
Global lead-capture: any `a[href="#audit"]` opens a 2-step modal (name/email → website/service → done). Include the modal markup + JS on **every page** so every CTA works.

### Cards
White bg, `1px solid var(--line)`, radius `--r`, soft navy shadow. Featured/active state: scale to 1, saturate, stronger blue-tinted shadow, optional chartreuse inset ring (`inset 0 0 0 2px rgba(202,255,0,.5)`).

### Pills (`.pill`)
Marquee/industry chips: white pill with prussian text; `.pill--out` = transparent with white border (on blue backgrounds).

### Metric block (`.cs__metric-block`)
Stat cards with corner-bracket marks (::before/::after 13px L-brackets), tiny uppercase label, huge display number, sub-line. Great for case-study pages.

### FAQ accordion
Border-top/bottom hairlines, big display-font question, circular ocean-twilight chevron that rotates 180°, `grid-template-rows:0fr→1fr` open animation. One open at a time.

### Forms
Inputs: `1.5px solid #d9e1ec` (or `rgba(10,21,41,.2)` on chartreuse), radius 12px, `#f8fafc` bg → white + ocean-twilight border + `0 0 0 4px rgba(16,70,186,.1)` ring on focus.

### Texture details (what makes it feel premium)
- Film-grain overlay on hero (`.hero__grain`, base64 PNG, opacity .32).
- SVG noise (`feTurbulence`, soft-light, opacity .16) on photographic dark sections.
- Edge-fade masks on marquees: `mask-image:linear-gradient(to right,transparent,#000 8%,#000 92%,transparent)`.
- Glassmorphism: `backdrop-filter:blur(10–20px)` on nav, mega menu, play buttons, modal backdrop.

---

## 6. Motion system (GSAP)

CDN includes at the end of `<body>` on every page:

```html
<script src="https://cdn.jsdelivr.net/npm/gsap@3.12.5/dist/gsap.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/gsap@3.12.5/dist/ScrollTrigger.min.js"></script>
<script src="https://unpkg.com/lucide@latest/dist/umd/lucide.js"></script>
<script src="js/main.js"></script>  <!-- or a page-specific js file -->
<script>lucide.createIcons();</script>
```

### House motion rules

1. `gsap.registerPlugin(ScrollTrigger)` at top; every init function bails on missing elements (`if (!el) return`) so one shared JS file can serve all pages.
2. **Respect reduced motion.** `const reduce = matchMedia('(prefers-reduced-motion: reduce)').matches;` — every animation checks it and `gsap.set`s the final state instead. CSS also nukes all animation under the media query.
3. **Default entrance:** fade-up — `{ y: 24–40, opacity: 0, duration: .5–.9, ease: 'power3.out' }`, stagger `.08–.15` for groups. Trigger: `scrollTrigger:{ trigger, start:'top 75%' }` (titles use `top 82%`, small items `top 88%`).
4. **Eases:** `power3.out` for entrances · `power2.in` for exits · `back.out(1.6–2.2)` for pop-ins (dots, arrows, nodes) · `none` for scrubbed/marquee motion.
5. **Hero:** a single timeline on load — eyebrow → title → sub → CTA → stats, overlapping with negative position offsets (`'-=.4'`), visual slides in from the right.
6. **Scroll-scrubbed** (highlight-sweep pattern): `scrub: 0.8` between `start:'top 72%'` and `end:'bottom 42%'`, driving CSS custom properties (e.g. `--hl-progress`).
7. **SVG line draw-on:** set `strokeDasharray/offset = getTotalLength()`, tween offset to 0; optional bright "spark" clone (`#ff73f6` glow) chasing the line.
8. **Marquees:** duplicate content once, tween `x` by `-50%`, `ease:'none', repeat:-1`, ~22–38s; slow global timeline on hover.
9. **Carousels/swaps:** kill tweens before switching state to avoid zombie classes; auto-advance every 5.5–6s, reset the timer on manual interaction.
10. **CSS-only micro-interactions** use `transition: ... .25s var(--ease)` — hover lifts (`translateY(-2px…-4px)`), scale-on-active, color fades. Don't use GSAP for hovers.
11. Reveal helper: elements needing pre-hidden state get class `.reveal{opacity:0;transform:translateY(24px)}` — GSAP takes over from there.

### Signature moves (reuse on new pages where fitting)
- **Highlight-sweep** (`.hl`): chartreuse/dark wipe across bold keywords with inline icon pop — for editorial promise statements.
- **Edit-bay / accordion cards**: flex-grow swap between featured (wide, saturated) and compressed (dim, scaled .96) siblings.
- **Odometer / counter stats**: number tween with snap on first view; bars growing from bottom.
- **Constellation / hub-and-spoke**: center node + curved SVG connectors drawing out to nodes.

---

## 7. Turf & Tree landing-page learnings

These are non-negotiable defaults drawn from the Turf & Tree service-page review. Apply them to new landing pages unless the brief explicitly calls for a different treatment.

### Copy and hierarchy

- Keep copy direct, concrete and outcome-led. Lead with what the visitor gets, such as calls, rankings, map visibility or a clear audit result.
- Use only documented client metrics and claims. Do not carry a revenue, lead-volume or timeline claim from another source unless it is verified for the client shown.
- Avoid em dashes and en dashes in production copy. Use short sentences, commas or parentheses instead.
- Eyebrows are optional, not mandatory. Remove them when the heading is already self-explanatory or a section needs a cleaner, quieter start.
- Avoid redundant introductory lines under a strong section heading. A proof section should lead with the result and evidence, not filler such as “See real client results.”

### Layout and spacing

- Match vertical padding to content density. Compact utility sections such as audit deliverables, comparison tables and fit checks should use roughly half the standard section padding.
- Validate the desktop hero as a stack: text block, CTA row and proof stats need explicit vertical gaps. Never allow CTA controls or stats to overlap.
- Remove low-value sections rather than preserving them for page length. Trust strips, process explainers and headings that do not advance the conversion narrative should be omitted when they are not needed.
- Final CTAs need a compact, balanced two-column layout: constrain the headline width and scale, reduce excess vertical padding, and align the action block deliberately.

### Reusable section patterns

- Free-audit sections should be concise: a clear headline plus three compact deliverable cards. Do not add tabs, simulated dashboards or supporting UI unless the brief requires them.
- Proof sections should show the client logo, documented strategy, concrete metrics and a link to the full case study. Keep disclaimers and decorative copy out of the visible layout unless legally required.
- Comparison sections belong directly before the FAQ when used. Use a readable three-column table with five or fewer decision-level rows and horizontal overflow on mobile.
- On mobile, swipeable commitment cards should use native horizontal scroll snap, show a small preview of the next card, and remain fully usable without JavaScript controls.
- Illustrations in card carousels should be simple, brand-color-led, text-free artwork. Let the illustration occupy the upper area and keep the copy on the card surface without a separate gradient panel behind it.

### Quality checks before handoff

- Check desktop and mobile for unintended white space, especially before and after short content blocks.
- Check that all visible copy uses `letter-spacing: 0` when the page’s typography rule calls for neutral tracking.
- Check that removed sections leave no orphaned in-page anchors or navigation links.
- Check card images load before horizontal swipe interaction begins.

---

## 8. Page scaffold (boilerplate for every new page)

```html
<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8" />
<meta name="viewport" content="width=device-width, initial-scale=1.0" />
<title>[Page Topic] — Ranked International | Dallas SEO Company</title>
<meta name="description" content="[Outcome-focused, Dallas-local, ~150 chars]" />
<!-- fonts block from §3 -->
<link rel="stylesheet" href="css/styles.css?v=2" />
<!-- optional: <link rel="stylesheet" href="css/[page].css"> for page-specific sections -->
</head>
<body>

<!-- NAV: copy verbatim from index.html -->

<!-- HERO: dark (prussian/blue gradient) hero with eyebrow, H1 (one em accent),
     sub, ONE chartreuse CTA. Shorter than homepage hero is fine (e.g. 480–560px). -->

<!-- BODY SECTIONS: alternate light/dark per §2 rhythm -->

<!-- FINAL CTA: chartreuse full-bleed panel + "Get my free audit" — every page ends here -->

<!-- FOOTER + AUDIT MODAL: copy verbatim from index.html -->

<!-- scripts block from §6 -->
</body>
</html>
```

### New-page checklist

- [ ] Nav, footer, audit modal copied in; all `#audit` CTAs open the modal
- [ ] ONE primary CTA ("Get my free audit"); phone is secondary
- [ ] Exactly one chartreuse section (final CTA); ≤3 dark sections
- [ ] Every H1/H2 uses the display font, `letter-spacing:0`, and one italic-serif `em` max
- [ ] Each section has a clear heading; add an eyebrow only when it improves hierarchy
- [ ] Entrance animations use the fade-up defaults; `reduce` respected
- [ ] Title/meta description: outcome + "Dallas" + service keyword
- [ ] Every claim sits next to proof (a number, a name, a screenshot)
- [ ] No fake testimonials, no placeholder contact info surfacing as real
- [ ] Responsive pass at 980px / 860px / 560px (homepage breakpoints)
- [ ] Cross-links: service pages ↔ industry pages ↔ case studies (SEO crawl paths)

---

## 9. Per-page-type guidance

### Service page (e.g. /local-seo)
Dark hero (service name as H1, outcome subhead, CTA) → "what you get" cards (white) → proof/results section with metric blocks → process strip (reuse `.process` pattern) → FAQ → chartreuse CTA. Pull the relevant mega-menu description as the meta description seed.

### Case study page
Light hero with client name + ONE hero stat (odometer) → serif pull-quote block (reuse `.cs__quote` styling on a prussian card) → metric blocks with corner brackets → "how we did it" timeline → related industries pills → chartreuse CTA. Numbers are the heroes; make them display-800 and huge.

### Industry page (e.g. /roofing-seo)
Dark hero speaking the trade's language ("Dallas roofers: …") → pain/promise editorial statement (highlight-sweep) → relevant case study card → services-for-this-industry (hub-and-spoke or cards) → FAQ → chartreuse CTA.

### Listing/index pages (case studies, blog)
White/bright-snow, card grids with the standard card treatment, featured item uses the "active card" state (bigger, saturated, blue shadow).

---

## 10. Assets & conventions

- `assets/` — images (`hero-bg.jpg`, `bg.jpg`), `assets/brand logos/` (client logos, ~40px tall, opacity .55 → 1 on hover), `assets/video/` (testimonials as `review1-3.mp4`), `assets/posters/`, `assets/frames/` (hero SERP-climb webm/mp4), `assets/icons/`.
- Icons: **Lucide** via CDN (`data-lucide="..."` + `lucide.createIcons()`); inline SVGs (stroke-width 2.2–2.6, round caps) for bespoke marks; chartreuse-filled SVGs for stat icons on dark.
- Photography/video treatment on dark cards: `filter:saturate(.85) brightness(.65–.82)` + navy gradient overlay (`linear-gradient(180deg, transparent, rgba(5,20,48,.9))`) so white text always reads.
- Class naming: BEM-ish (`.block__element--modifier`); state classes `.is-active`, `.is-open`, `.is-stuck`, `.lit`; JS hooks via `data-*` attributes (`data-cs-idx`, `data-audit-step`, `data-practice-card`).
- CSS organization: one file, banner-comment section dividers (`/* ==== SECTION ==== */`), tokens on `:root` at top, responsive + reduced-motion at bottom.
- Preview: `npx serve` on port 4599 (`.claude/launch.json`). Note: screenshots blank during continuous GSAP loops — pause/seek timelines to capture.

## 11. Copy voice cheatsheet

- Second person, present tense, concrete: "Your customers are Googling. You're on page 2."
- Verbs of outcome: rank, book, call, win. Banned: synergy, visibility, impressions-as-a-result, "solutions."
- Local anchors everywhere: Dallas, the trades (roofers, HVAC crews, clinics).
- Every headline could be falsified — that's what makes it credible.
- Micro-labels are uppercase and tiny; headlines are huge; nothing in between shouts.
