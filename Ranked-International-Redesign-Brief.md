# Ranked International — Website Redesign Brief

**Prepared for:** Anshul
**Site:** https://rankedinternational.com/
**Primary goals:** (1) Rank high on Google for SEO-service intent, (2) Generate inbound B2B leads
**Date:** June 26, 2026

---

## How to read this

Items are sequenced by impact, not by effort. Do P0 before anything else. P1 items move the conversion and ranking needle most. P2 are compounding plays that pay off over months. Each item lists *what*, *why it matters*, and *what "done" looks like*.

---

## P0 — Credibility emergencies (fix this week, before any redesign work)

These are live right now and actively cost you trust and rankings.

### 0.1 Remove all leftover template placeholder content
The homepage and footer contain content from a biotech-software (Benchling) demo template that was never cleaned out.

Currently live and indexed:
- Homepage section "Suitable solutions for your research needs" (cellular therapy, Biopharmaceutical, Agritech R&D, Bioprocess development).
- Footer blocks: "About Outgrid," "Benchling for Academics," "RNA Therapeutics," "The Benchling R&D Cloud," Notebook, Registry, etc.
- A fake second phone number (866-231-1840), a fax line (866-215-0052), and a fake address ("111 W Monroe St. #007, Fade, AZ 85003") sitting next to your real Dallas address.

**Why:** For a B2B buyer judging whether you sweat website details, this signals the opposite. It also creates thin, off-topic indexed content that dilutes your topical relevance for SEO.
**Done when:** Every section and footer link reflects only Ranked International's real business, services, contact info, and address. One phone number, one address, no biotech/lab content anywhere.

### 0.2 Replace the fake testimonials
Six identical copy-pasted quotes, all praising "this SEO software." You're an agency, not software.

**Why:** Obvious fakes are worse than no testimonials; they actively erode trust.
**Done when:** Replaced with real, distinct client quotes (named where possible), or removed until you have real ones.

### 0.3 Fix the broken primary CTA
The "Free Quote" hero button links to the homepage itself (`https://rankedinternational.com`), not to the form or contact page.

**Why:** Your single most prominent conversion path is a dead loop.
**Done when:** "Free Quote" points to the form/contact and is verified working.

### 0.4 Fix dead navigation/footer anchors
"Industries We Serve" (parent), "About Us," and "Case Studies" all link to `#`.

**Why:** Dead links waste crawl paths to real pages (e.g., your 19 industry pages) and frustrate buyers.
**Done when:** Every nav and footer link resolves to a real page or is removed.

---

## P1 — Conversion & lead generation (highest revenue leverage)

### 1.1 Rewrite the hero value proposition
Current: "Dallas SEO Company / We help companies rank high on Google... The number one digital marketing agency in Dallas."

Problems: "Number one" is an unprovable claim sophisticated buyers discount instantly. The framing is about you ("we help"), not the buyer's problem.

**Action:** Lead with the buyer's outcome and a specific proof point. Structure:
- H1: keep "Dallas SEO Company" (good local-intent keyword).
- Subhead: an outcome-led, specific promise. See **Appendix A** for the recommended hero copy and alternates, rewritten against Harry Dry's tests.
- Add one credible proof stat (real client result, not "10X" floating with no story).

**Done when:** Hero answers "why you over 200 other Dallas agencies" and "what outcome do I get," in the buyer's language.

### 1.2 Establish a single CTA hierarchy
Currently four competing asks: Free Quote, Get Started, Schedule a Free Call, Call Now. No hierarchy.

**Action:** Choose ONE primary action (recommend: the audit/quote form). Make phone and "schedule a call" clearly secondary. Use consistent CTA language sitewide.

**Done when:** Every page has one obvious primary action; secondary actions are visually subordinate.

### 1.3 Strengthen the form CTA copy and value exchange
- Replace "SEND" with value-restating copy: "Get My Free SEO Audit" or "Get My Free Quote."
- Offer something concrete in return for contact details (free audit, competitor gap report, custom quote) rather than a generic inquiry.

**Done when:** The button restates the value and the form promises a specific, valuable deliverable.

### 1.4 Tune form fields for lead *quality*, not just volume
Current fields (Name, Email, Phone, Service) are lean — good. But your goal is qualified B2B leads, not raw volume.

**Action:** Add one field: "Your website URL." Optionally a budget-range dropdown.
**Why:** Website URL lets sales prep before the first call and lightly self-qualifies. Budget range filters tire-kickers. For B2B, slightly higher qualification often raises lead quality more than it lowers volume.
**Done when:** Form captures website URL; test budget-range field and measure quality vs. volume.

### 1.5 Place social proof strategically on every service page
Three high-leverage positions:
1. One specific result stat directly under the page H1 (before first scroll).
2. A relevant case study mid-page, right after the service description (proof next to claim).
3. A testimonial block just above the final CTA (where doubt peaks).

**Critical:** Match proof to page. The Local SEO page shows a local ranking win; the PPC page shows a paid result. No identical quotes everywhere.
**Done when:** Each service page carries page-relevant, specific proof in all three positions.

---

## P2 — Information architecture & SEO structure

### 2.1 Restructure navigation into hub-and-spoke
Currently 10 services + 19 industries dumped flat into the nav (29 links, no hierarchy). This spreads link equity thin and signals no priority to Google.

**Action:**
- Build real hub pages: `/services/` and `/industries/` (the Industries parent currently links to `#`).
- Group services by theme in the nav: SEO (Local, Organic, Technical, Enterprise, Link Building), Paid (Google Ads, PPC, Enterprise PPC), Consulting (SEO Consulting, CRO Audit).
- Hubs link down to spokes and across between related pages, concentrating equity on money pages.

**Done when:** Two hub pages exist, nav is grouped by theme, and hubs internally link to all spokes.

### 2.2 Keep the URL structure (it's already good)
Flat, readable, keyword-bearing slugs (`/local-seo-services/`, `/technical-seo-services/`). Don't over-engineer. Only change: ensure new hub pages get clean slugs (`/services/`, `/industries/`).

### 2.3 Improve crawl & indexing
- Fix all `#` anchors (covered in P0.4) so crawl paths to the 19 industry pages open up.
- Add internal links FROM blog posts TO relevant service pages (blog is currently an island).
- Submit a clean XML sitemap in Google Search Console and confirm all money pages are indexed.

**Done when:** No dead internal links, blog links into services, sitemap submitted and pages indexed.

### 2.4 Prevent keyword cannibalization between service and industry pages
The real risk isn't Local vs E-commerce SEO — it's **service pages vs industry pages** chasing the same intent (e.g., "Local SEO services" vs "HVAC SEO").

**Action:** One primary keyword per page.
- Service pages → the service keyword ("local SEO services," "technical SEO audit").
- Industry pages → industry + location + service ("HVAC SEO company Dallas").
- Make H1s and title tags distinct; cross-link deliberately instead of letting them compete.

**Done when:** Each page has a documented single primary keyword and distinct title/H1.

---

## P2 — Content strategy & topical authority

### 3.1 Differentiate service-page copy
Current copy is generic agency boilerplate ("cutting-edge SEO, targeted PPC, high-converting content") — every competitor says this. The problem isn't too-technical or too-simple; it's undifferentiated.

**Action:** Add specificity — name the industries served, the concrete outcomes, and your actual process/methodology on each service page.
**Done when:** A buyer could not swap your copy with a competitor's without noticing.

### 3.2 Build real case studies (highest-trust B2B asset)
"Case Studies" currently links to `#` — there are none. Floating claims ("10X increase," "Domain Authority 90") are decoration without a story.

**Action:** Build a `/case-studies/` hub with 2–4 structured studies. Each needs: starting baseline metrics, what you did, hard numbers over time (traffic, rankings, leads, revenue), a timeframe, and a named client quote.
**Done when:** Case Studies page is live with at least 2–3 real, numbers-backed studies, linked from nav and service pages.

### 3.3 Build topical clusters that map to money pages
Current blog: two thin, partly nonsensical posts (the "What is SEO?" post opens about "keeping track of company meetings" — more template junk).

**Action:** Build pillar-and-cluster content around buyer questions and commercial intent:
- Local SEO for service businesses (pillar) + supporting posts.
- Industry SEO: one pillar each for top 3–4 industries (HVAC, roofing, med spa, etc.).
- SEO vs PPC / when to use each.
- "How to choose an SEO agency / what to expect" (bottom-funnel, high commercial intent).

Each pillar links to its supporting posts AND down to the relevant service page (this is what converts authority into leads).
**Done when:** At least one full cluster (pillar + 3–4 posts) is published and internally linked to a money page.

### 3.4 Strategic reframe — win the long tail first
As a small agency, going broad against established players for "SEO" is unwinnable. Your fast path is the industry + city + service intersection you already have in the nav. "Roofing SEO Dallas" is winnable in months; "SEO" is not. Prioritize unique, strong content on your top industry pages over broad authority plays.

### 3.5 Add E-E-A-T signals
Top competitors have real About/team pages with faces and credentials. Google and buyers both reward this.
**Action:** Build a real About page with team, credentials, and the Dallas local story. Replace the `#` About link.
**Done when:** About page is live with real team info and links correctly.

---

## Competitor gap analysis — pending input

You haven't shared a specific competitor sitemap/URL yet, so this section is a placeholder. From your own structure, the patterns top Dallas/B2B SEO agencies almost always have that you currently lack:
- A real Case Studies / Results section with numbers.
- A pricing or "how it works" page that pre-qualifies leads.
- About/team pages with real faces and credentials (E-E-A-T).
- Industry pages with unique content rather than near-duplicate templates.

**Next step:** Share a top-ranking competitor's URL and I'll produce a concrete page-by-page and content-structure diff.

---

## Appendix A — Copy rewrite (the Harry Dry pass)

Every line below was run through six tests. Use them on any future copy too:

1. **Falsifiable.** Could a competitor honestly claim the opposite? If not, it's a platitude. Cut it. ("Results-driven" fails. "Fire us any month" passes.)
2. **Show, don't tell.** Write what the reader can picture. "Page two of Google" beats "low visibility."
3. **Specific beats vague.** Names, numbers, exact details. "612 calls in 7 months" beats "more leads."
4. **Prove the claim.** Every claim needs evidence sitting right next to it.
5. **One reader.** Say "you" and "your roofing company," never "businesses."
6. **Read it out loud.** If you stumble or run out of breath, cut words.

### A1. Hero headline

**Before:** "Dallas SEO Company. The number one digital marketing agency in Dallas."
**Fails:** "Number one" is unprovable, not visual, and every agency says it.

**After (recommended):**
> **Your customers are Googling. You're on page two.**
> We get Dallas roofers, HVAC crews, and clinics onto page one for the searches that bring paying customers, then turn those clicks into booked jobs.
> 5.0 on Google. 100+ businesses ranked.
> Button: **Get my free audit**

**Why it works:** Leads with the prospect's pain as a picture (page two), then resolves it. Short enough to read in one breath. Names real industries so the reader sees themselves.

**Alternates to test:**
- "Be the first name they find, not the fourth they scroll past."
- "More calls from Google. That's the whole job."

### A2. Primary CTA

**Before:** "SEND" / "Get Started" / "Free Quote" (and the button is broken, see P0.3).
**After (basic):** "Get my free audit."
**After (with risk reversal, stronger):** pair the button with what happens next.
> "Get a free audit and I'll show you the three keywords you're losing to competitors right now."
> Button: **Show me my 3 biggest leaks**

Specificity plus a concrete next step beats a generic verb every time.

### A3. "Why Choose Ranked" — your biggest opportunity

**Before:** "transparent, ethical, results-driven... no generic tactics or bloated reports."
**Fails hard:** No competitor advertises being opaque, unethical, or generic. These words do zero work.

**After:** swap adjectives for policies a competitor literally cannot copy. Each of these passes the falsifiable test because a contract-locked, multi-client agency cannot say them:
- "We take one client per industry, per city. We will never optimize your competitor against you."
- "No 12-month contracts. Fire us any month you're not winning."
- "You get my cell number, not a support ticket."
- "Every report ends with one number: leads booked. Not impressions. Not 'visibility.'"

Important: if these are true for you, this is your whole pitch, put it front and center. If they're not true, that's a signal to change the offer, not just the copy.

### A4. Proof and testimonials

**Before:** six identical "this SEO software" quotes; "10X increase" and "Domain Authority 90" floating with no story.
**After pattern:** a specific number tied to a real name and face beats a big round number alone.
> "Bella MedSpa went from 40 calls a month to 600+ in seven months." (with name, photo, and a link to the case study)

Use real figures only. Specificity works because it's checkable. Inventing numbers breaks the mechanism and repeats the fake-testimonial problem from P0.2.

### A5. Service-page openers

**Before (Local SEO):** "Boost your visibility in local search results and map listings."
**Fails:** "Boost your visibility" is the vaguest phrase in marketing.
**After:**
> "When someone two miles away types 'roofer near me,' you want to be the first call, not buried under three competitors. We get you into the Google map pack and keep you there."

### The one rule for the whole site

If a competitor's website could run your exact sentence word for word, delete it and write something only you can say.

---

## Suggested sequencing

1. **Week 1:** All of P0. (Non-negotiable — these are live credibility leaks.)
2. **Weeks 2–3:** P1 (hero, CTA hierarchy, form, service-page proof).
3. **Weeks 3–5:** P2 IA (hubs, nav grouping, crawl fixes, cannibalization map).
4. **Ongoing:** P2 content (case studies first, then clusters, then E-E-A-T).
