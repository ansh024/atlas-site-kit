# Design Direction — Ranked International Homepage

**Business:** Dallas SEO agency. B2B lead-gen + SEO-intent ranking.
**Method:** Frankenstein — borrow the *technique* from ReadMe / Sui / Maze (see `01-design-learnings.md`), pour our own content into it.
**Build target:** single `index.html` + GSAP (ScrollTrigger, SplitText), no framework. Mobile-responsive.

---

## A. Brand system

### Colors (from supplied palette)
```
--ocean-twilight : #1046ba   /* primary blue — links, mid backgrounds */
--egyptian-blue  : #06348e   /* deeper blue — gradients, accents */
--prussian-blue  : #0a1529   /* near-black navy — dark sections, text */
--chartreuse     : #caff00   /* ENERGY accent — CTAs, highlight-sweep, "page one" */
--bright-snow    : #f8f7f6   /* default airy background */
--white          : #ffffff   /* cards, alternating background */
```

**Usage rules**
- **Default canvas = bright-snow / white.** ~60% of the page is airy and light. This is the brief's instruction and our differentiator vs. dark dev-tool sites.
- **Blues** carry the hero gradient, the dark "promise" or proof sections, and the footer (prussian-blue).
- **Chartreuse is rare and loud** — reserve for: primary CTA buttons, the highlight-sweep marker, the single "you're on page one" payoff moment, and key numbers. If it's everywhere it stops meaning "win."
- Text: prussian-blue on light; bright-snow on dark. Never pure-black.

### Type
- **Display/headlines:** a tight, modern grotesk — **"Geist"** or **"Inter Tight"** (700/600), large, negative letter-spacing.
- **Accent word (italic serif):** **"Instrument Serif"** or **"Fraunces"** italic — used on ONE word per major headline (à la ReadMe's *adoption*). e.g. "You're on page *two*."
- **Body:** Inter / Geist 400–500.
- Load via Google Fonts / Fontshare. Big scale: hero clamp ~`clamp(2.8rem, 8vw, 7rem)`.

### Reusable components
- Pill CTA (chartreuse solid = primary; outline navy = secondary).
- Section eyebrow (small caps, chartreuse dot + label).
- Glass sticky nav.

---

## B. Section-by-section plan

> Each section lists: **content** · **borrowed technique** · **motion**.

### 0. Nav (sticky, glass)
- Logo · grouped menu (Services ▾, Industries ▾, Results, About) · phone (secondary) · **"Get my free audit"** (chartreuse, primary).
- Fixes brief P0.3/P0.4: one primary CTA, real links.
- Motion: translucent → solid bright-snow w/ shadow on scroll.

### 1. Hero  — *Sui hero × ReadMe accent word*
- **H1:** "Dallas SEO Company" kept small as eyebrow/keyword (local SEO intent).
- **Big line:** "Your customers are Googling. **You're on page *two*.**" (*two* = italic serif, chartreuse).
- **Subhead:** "We get Dallas roofers, HVAC crews, and clinics onto page one for the searches that bring paying customers — then turn those clicks into booked jobs."
- **Proof row:** ★ 5.0 on Google · 100+ businesses ranked · one client per industry.
- **Primary CTA:** "Get my free audit" → form anchor. Secondary: "See real results" → results section.
- **Background:** Sui-style **light-streak aurora**, retinted ocean→egyptian blue with a faint chartreuse streak, slow GSAP drift. Type can be on a light variant too — decide in build (lean: deep-blue gradient hero, white type, to contrast the white sections that follow).
- Motion: headline mask-reveal per line; proof stats fade-up stagger; bg drift loop.

### 2. ⭐ Video Review Section — *the essential one* — *Sui "Stay in loop" cards × ReadMe scale-in*
- **3 client video testimonials, 5:4 ratio.** Lives high (slides 2–3).
- **Signature layout/motion:** the **center video is "being edited"** — presented wide/featured inside a minimal editor frame (timeline scrubber bar, playhead, soft chrome), while the **two side videos sit squished/compressed** (narrower, slightly scaled-down, dimmed). On scroll or auto-rotate, focus **passes between the three** — the active one expands to the wide "edit" state, the previous one compresses back. Feels like a live edit bay.
- Each card: poster frame, play button, client name + business + one-line result ("Bella MedSpa — 40→600+ calls").
- Borrow Sui's content-card cluster + ReadMe's scale-in for the focus swap.
- Motion: GSAP timeline swapping `flex/width + scale + filter` between the three; playhead/timeline ticks animate on the active one; click → inline play (muted poster → video).
- *Implementation note:* use placeholder posters + the client videos already in `~/Downloads` (e.g. `video1-testimonial-01.mp4`, Homeowner Protection Alliance reviews) as stand-ins until real ones are dropped in `assets/`.

### 3. Trust / logo wall — *Sui + ReadMe logo walls*
- "Trusted by Dallas businesses that need the phone to ring." → client/industry logos, mono-tint on white.
- Motion: fade-up; optional slow marquee if many.

### 4. ⭐ The Promise / Differentiator statement — *Sui highlight-sweep* (biggest opportunity per brief A3)
- Editorial sentence on bright-snow, big:
  > "Ranked International takes **one client per industry, per city** — so we **never optimize your competitor against you**. **No 12-month contracts.** Every report ends with one number: **leads booked.**"
- **Highlight-sweep:** the bold phrases get wiped with a **chartreuse highlight box + small inline icon**, sequentially, as it scrolls into view.
- This is where the falsifiable, only-we-can-say-it claims live (brief §A3).
- Motion: ScrollTrigger staggered `scaleX` highlight reveals + icon pop.

### 5. Services — *ReadMe constellation connector map*
- Central node "Get you ranked" → connector lines draw out to grouped service nodes:
  - **SEO:** Local · Organic · Technical · Enterprise · Link Building
  - **Paid:** Google Ads · PPC · Enterprise PPC
  - **Consulting:** SEO Consulting · CRO Audit
- Grouping matches brief P2.1 (hub-and-spoke). Each node links to its service page.
- Motion: SVG paths draw-on (`strokeDashoffset`), nodes pop in along the lines, hover lifts a node + brightens its trace.
- *Need to create:* simple line-icon per service (SVG).

### 6. ⭐ Results / Case study — *Sui odometer counter × ReadMe product scale-in*
- Headline: "We don't report impressions. We report booked jobs."
- Hero stat with **odometer roll**: "Bella MedSpa: **40 → 600+** calls a month in **7 months**." Plus 1–2 more real numbers ("612 calls in 7 months").
- A ranking-proof visual (before/after SERP position or a calls graph) **scales in** like ReadMe's product UI.
- Link to `/case-studies/`.
- Motion: GSAP number tween w/ snap, on first view; graph bars grow.
- *Need to create:* a clean "calls per month" bar/line chart SVG, a SERP before/after card.

### 7. Why Ranked (capabilities) — *Sui line-by-line list w/ right icons*
- Rows, each revealing in sequence with a right-aligned icon:
  - "You get my cell number, not a support ticket."
  - "Fire us any month you're not winning."
  - "One client per industry, per city."
  - "Reports that end in leads booked, not 'visibility.'"
- Motion: staggered fade/slide; icon draw-in.

### 8. Industries — *Maze drifting pill marquee*
- "We rank the trades and clinics Dallas searches for." → two opposing-direction marquee rows of **pills**: Roofing · HVAC · Med Spa · Dentist · Plumbing · Roofing · Law · Auto · Landscaping … (19 total).
- Each pill links to its industry page (opens crawl paths — brief P2.3).
- Background can be a **chartreuse full-bleed** here OR save chartreuse for the CTA — decide in build (likely white pills on blue, save chartreuse for §10).
- Motion: seamless GSAP x-loops, pause on hover.

### 9. Process — "How it works" (light)
- 3–4 steps: Free audit → 90-day plan → Build & rank → Report leads booked. Pre-qualifies leads (brief gap analysis).
- Motion: connected step reveal.

### 10. ⭐ Final CTA — *Maze chartreuse full-bleed × Sui buttons*
- Full-bleed **chartreuse** payoff panel (the one loud moment): "Let's get you on **page one.**"
- **Audit form:** Name · Email · Phone · **Website URL** (brief 1.4) · Service ▾. Button: **"Get my free audit"** (brief 1.3). Sub-line: "I'll show you the 3 keywords you're losing to competitors right now." (brief A2).
- Motion: panel wipe-in; form fields stagger.

### 11. Footer (prussian-blue)
- Real nav only — services grouped, industries, About, Results, Dallas address + ONE phone (brief P0.1 — strip all Benchling/biotech placeholder + fake numbers/address).
- Motion: none / subtle.

---

## C. Section rhythm (light ↔ saturated)
```
Nav(glass) → Hero(blue gradient) → Video reviews(bright-snow) → Logos(white)
→ Promise/highlight(bright-snow) → Services constellation(prussian-blue, dark)
→ Results/counter(white) → Why Ranked(bright-snow) → Industries(ocean-blue)
→ Process(bright-snow) → Final CTA(CHARTREUSE) → Footer(prussian-blue)
```
Two dark blocks (Services, Footer) + one chartreuse (CTA) punctuate an otherwise airy, white-dominant page. Matches brief's "airy" instruction and the references' rhythm.

## D. Assets to create
- Service line-icons (SVG, ~10).
- Differentiator icons for highlight-sweep + capabilities list.
- "Calls per month" chart + SERP before/after card (SVG).
- Video-review poster frames (from supplied client videos in `~/Downloads`).
- Industry pill set (text only — no icon needed).
- Logo wall placeholders.
- Favicon / wordmark for Ranked International.

## E. Build order
1. Scaffold `index.html` + CSS tokens + GSAP includes + nav + hero. ✅ visual baseline.
2. Video-review section (the centerpiece) — get the edit-bay swap right.
3. Promise highlight-sweep.
4. Services constellation.
5. Results odometer + chart.
6. Why Ranked + Industries marquee.
7. Process + Final CTA form.
8. Footer + polish pass (responsive, reduced-motion, perf).

## F. Guardrails (from brief P0/P1)
- ONE primary CTA everywhere ("Get my free audit"). Phone secondary.
- Zero Benchling/biotech/fake-contact content.
- No fake testimonials — video reviews + named, real numbers only.
- Every claim has proof beside it.
- `prefers-reduced-motion` respected.
