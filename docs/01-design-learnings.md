# Design Learnings — Reference Teardown

Source material analyzed frame-by-frame (ffmpeg, ~1 frame / 1.5–2s):
- **ReadMe** (`ReadMe · Developer-friendly API documentation.mp4`, 78s, dark theme)
- **Sui** (`Sui _ The Full Stack for a New Global Economy.mp4`, 58s, blue/black/white)
- **Maze** (static hero screenshot, chartreuse theme)

The goal of this doc: isolate *what specifically works* and *why*, as a reusable motion + layout vocabulary we can Frankenstein onto Ranked International's content. Pair this with `02-design-direction.md`, which maps each technique to a concrete section.

---

## 1. ReadMe — teardown

### Flow (in order)
1. **Hero** — "Docs that **drive adoption**" (sans + italic-serif accent word). Dark navy with a slow-drifting **starfield/particle field**. Centered email input + pill CTA. The background **shifts color** mid-scroll (navy → green "matrix" code-rain → back), signalling "your docs, your theme."
2. **Product UI scale-in** — a real screenshot of the ReadMe dashboard **scales up from small and slides over the hero**, anchoring the abstract promise in a tangible product.
3. **Logo wall** — "Trusted by teams who know good docs drive adoption" → NVIDIA, Asana, VISA, MongoDB, Amazon, SpaceX. Mono-tint logos on dark.
4. **Constellation feature map** — "Everything to **build great docs**". A central node with **thin glowing SVG connector lines** that *draw on* and link out to feature cards (Guides, API, Ask AI, Components, Glossary). Purple accent blobs pulse along the traces.
5. **Big-quote testimonial** — single quote, large, lots of negative space, name underneath.
6. **Keyword-highlight body copy** — feature paragraphs where specific words ("WYSIWYG editor", "Git integration is amazing") are **colored/italicized** to carry the eye.
7. **Enterprise grid** — Role-based access / SOC2 / SSO with small circular badge graphics on black.
8. **Outro** — "Where great docs **come from**" → logo → mega footer.

### What works & why
- **Mixed type voice:** tight sans for the structural words + an **italic serif accent word** in a brand color. Cheap to do, instantly editorial, stops the headline feeling like a generic SaaS template.
- **Color-shifting hero background** = the page feels alive in the first 3 seconds without a video. It's just animated gradients + particles.
- **The constellation/connector diagram** is the signature. Thin animated lines that draw-on (SVG `stroke-dashoffset`) make a boring "feature list" feel like a *system*. High perceived sophistication, low asset cost.
- **Product-UI scale-in** converts an abstract claim into proof. We don't have a SaaS UI — but we have **video reviews and ranking screenshots**, which play the same role.
- **Restraint:** huge negative space around testimonials. The dark canvas makes one quote feel premium.

### Steal for Ranked
- Italic-serif accent word in headlines.
- Constellation connector diagram → for the **Services** section.
- Product scale-in → for the **video-review** and **ranking-proof** reveals.
- Big-quote testimonial treatment.

---

## 2. Sui — teardown

### Flow (in order)
1. **Hero** — "**Build full stack**" in massive tight sans. Background is an **electric-cobalt-blue gradient with vertical light-streak "aurora" bars** that breathe/shift. White type, two buttons (solid + ghost). Feels like a light leak / energy field.
2. **Logo wall on WHITE** — "The most innovative companies build on Sui" (Franklin Templeton, OKX, Google…). Hard cut from saturated blue → clean white = strong rhythm.
3. **Editorial statement w/ highlight-sweep** *(signature)* — "Sui is the only platform where assets, data, and permissions can be owned, programmed, and verified. The result? **Superior products**, **real user trust** and **value that's shared**, not extracted." As you scroll, **keyword phrases get wiped with a black/blue highlight box + a small inline icon**, sequentially, like a smart-highlighter moving through the sentence. Extremely distinctive, very cheap (span backgrounds + icons revealed on scroll).
4. **Line-by-line list reveal** — "Ownable by design / Verifiable by default / Business ready / Composable and scalable / High-performing without trade-offs." Each row reveals in sequence with a **right-aligned icon**.
5. **Full-bleed brand moment** — entire viewport fills with the **vertical blue light-streaks**. A palette-cleanser between content blocks.
6. **"Innovation, engineered."** — black canvas with **blueprint/wireframe cards** (3D wireframe cubes, "DATA SECURITY", "VERIFIABLE COMPUTE") scattered and joined by faint connector dots. Technical, premium.
7. **"Why builders choose Sui / How users benefit"** — two-column checklist on white.
8. **"Industry transformation powered by Sui"** — dark, tabbed category cards (DeFi, Gaming, Institutions, AI).
9. **"Start building / coding / earning / connecting"** — big list where **each row expands on hover** into description + CTA (accordion menu).
10. **"Stay in the loop"** — content/video cards + an **animated odometer counter** ($180M → $280M → $417,340,609 rolling up). Social icons fill on hover.
11. **Mega footer** on black.

### What works & why
- **Saturated-color ↔ pure-white rhythm.** Alternating a vivid branded section with a clean white editorial section gives the whole page a heartbeat. Prevents fatigue.
- **The highlight-sweep is the single best transferable effect.** It turns a plain value-prop sentence into a guided, kinetic read and naturally emphasizes the *claims you most want remembered*. Perfect for our differentiators.
- **Animated odometer counter** = instant credibility for a number. "$417,340,609" rolling up hits harder than a static figure. Our equivalent: "**612 calls**", "**40 → 600+**".
- **Hover-expand accordion list** is an elegant way to present services/CTAs without a wall of cards.
- **Line-by-line reveal with right-aligned icons** is a clean, confident way to list capabilities.
- **Blueprint cards on black** read as "serious engineering." We can echo this lightly for a "how the SEO machine works" moment, but it's optional.

### Steal for Ranked
- **Highlight-sweep** → the differentiator / promise statement (our biggest opportunity per the brief).
- **Saturated ↔ white rhythm** → overall page structure.
- **Odometer counter** → results / case-study numbers.
- **Hover-expand list** → services or industries.
- **Vertical light-streak gradient** → hero background, retinted to our blues + chartreuse.

---

## 3. Maze — teardown (static)

- Full-bleed **chartreuse (#caff00)** panel, dark-olive headline type on it.
- A **marquee of white rounded "pills"** (Card sorting, Tree testing, Surveys, Participant management…) drifting horizontally, some partially clipped at the edges — implies an endless toolkit.
- Confident, oversized, friendly geometric sans.

### What works & why
- One **bold flat color** owning a full section is punchy and memorable; chartreuse reads as energetic/modern.
- The **drifting pill marquee** is a low-effort, high-charm way to show breadth (many services/industries) without a static grid.

### Steal for Ranked
- A **chartreuse full-bleed accent section** (one, used sparingly — likely the final CTA or the "page one" payoff moment).
- **Drifting pill marquee** → our **19 industries** ("Roofing", "HVAC", "Med Spa", "Dentist"…). Perfect content fit.

---

## 4. Consolidated motion vocabulary (the toolkit)

| Effect | Source | GSAP approach | Use on |
|---|---|---|---|
| Headline reveal (mask up, per-word/line) | both | SplitText + `y`/clip stagger, ScrollTrigger | every section H2 |
| Italic-serif accent word | ReadMe | just type styling | hero + section heads |
| Light-streak aurora gradient bg | Sui | CSS conic/linear gradients + slow GSAP keyframe drift | hero, CTA |
| Color-shift background | ReadMe | GSAP tween bg + ScrollTrigger scrub | hero / transitions |
| **Highlight-sweep on keywords** | Sui | spans w/ scaleX-0 bg + inline icon, ScrollTrigger stagger | promise/differentiator statement |
| Constellation connector lines | ReadMe | inline SVG paths, `strokeDashoffset` draw-on | services map |
| Product / media scale-in | ReadMe | `scale` + `y` from small, ScrollTrigger | video reviews, ranking proof |
| Line-by-line list w/ right icon | Sui | stagger reveal | differentiators / capabilities |
| **Odometer counter** | Sui | GSAP tween on number + `snap`, ScrollTrigger once | results numbers |
| Hover-expand accordion | Sui | height/opacity tween on hover | services or industries |
| Drifting pill marquee | Maze | seamless x-loop (GSAP) | industries |
| Big-quote testimonial w/ space | ReadMe | fade/clip reveal | testimonials |

**Principles distilled:**
1. Alternate **airy white/bright-snow** sections with **saturated blue (and one chartreuse)** sections for rhythm.
2. Every claim travels with **proof sitting next to it** (number, name, video).
3. Motion **guides the read** (highlight-sweep, draw-on lines) — it is never decoration for its own sake.
4. **Big type + generous negative space** does most of the "premium" work; effects are accents.
5. Reveal on scroll, but keep it **fast and subtle** (200–600ms, small offsets) — these references never feel laggy.
