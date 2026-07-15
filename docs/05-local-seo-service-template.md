# Local SEO Service Template — Implementation Record

## Status

The Local SEO reference page is implemented in the reusable WordPress Service
Page system and is available only in the isolated local WordPress environment.
It has **not** been published to the client site. Production publishing remains
a deliberate manual workflow.

- Local preview: `http://localhost:8891/local-seo-services/`
- Local WordPress admin: `http://localhost:8891/wp-admin/`
- Fixture login: `admin` / `password`

## What was built

The reference page establishes the configurable service-page pattern for Local
SEO, Organic SEO, Technical SEO, Link Building, Google Ads, PPC, consulting,
and CRO. It uses one `rip_service` custom post type and one shared template,
with ACF local-JSON fields for service-specific copy and repeatable content.

The Local SEO fixture includes:

- A navy, outcome-led hero with breadcrumb, audit CTA, verified-proof slot,
  outcome strip, and a local-search evidence object.
- A visible customer-journey explanation, including a concise definition of
  Local SEO. The definition is not hidden in an accordion.
- Diagnostic problem cards covering profile, coverage, signal, and review
  gaps.
- A selectable workstream blueprint for Business Profile, local keywords,
  service/location pages, citations, reviews, authority, and reporting.
- A connected local-authority system diagram, proof card, differentiation
  comparison, engagement phases, fit criteria, FAQs, guide slot, and tailored
  final audit CTA.

The chapter rail was intentionally removed after review; it duplicated the
page structure rather than helping visitors navigate it.

## Visual and copy decisions recorded during review

- All section heads follow a conventional vertical sequence: eyebrow, heading,
  then supporting copy. They are no longer arranged across three columns.
- Hero type is 25% smaller than the first implementation and scales fluidly
  across viewports. The hero grain overlay was removed because it made the
  background look enlarged and low-resolution.
- Supporting text, labels, controls, and focus states were raised to a
  legible size and contrast level. Long dashes have been removed from rendered
  service copy.
- The Local SEO definition is permanently visible. Problem copy was rewritten
  into short symptom, consequence, and Ranked response statements.
- The proof chart no longer uses the problematic bar-height animation.
- The workstream introduction no longer includes the "No mystery retainers"
  supporting sentence. On mobile, the section begins with 38px of top padding.
- The "What Ranked changes" response column uses brand blue.

## Responsive behavior

The reference page is tested from 320px upward. At the mobile breakpoint
(`720px` and below):

- The hero stacks copy above the local-search evidence object.
- Customer-journey steps, diagnostic problems, and engagement phases become
  horizontal swipe rails with scroll snapping and visible next-card previews.
  Diagnostic and phase cards are 76vw wide (maximum 315px) so the next card
  clearly peeks into view.
- The workstream selector preserves 44px touch targets. Its detail canvas is
  normal document flow rather than overlapping positioned elements, and has
  been compacted substantially.
- The connected-system diagram does not animate on mobile. It renders complete
  immediately, and the phone icon is explicitly sized so the "Calls &
  bookings" label cannot overlap it.
- The shared audit modal cannot contribute horizontal overflow when closed.

Desktop retains the editorial two-column compositions where appropriate, with
the system map and workstream detail panel presented alongside their content.
`prefers-reduced-motion` presents the same information without motion.

## Accessibility and interaction safeguards

- One canonical H1 and semantic section headings.
- Visible keyboard focus, keyboard-operable workstream tabs and FAQs, and
  accessible accordion state.
- Minimum readable body text and contrast-safe supporting copy.
- No information depends on animation; the mobile system diagram and reduced
  motion presentation are static.
- Service, BreadcrumbList, and conditional FAQPage structured data are emitted
  only when supported by visible page content.

## Relevant implementation files

- `wp-plugin/ranked-international/templates/template-service.php` — shared
  service-page markup and Local SEO reference rendering.
- `wp-plugin/ranked-international/assets/css/service.css` — service-specific
  responsive layout, accessibility, and interaction styling.
- `wp-plugin/ranked-international/assets/js/service.js` — tabs, FAQ behavior,
  reduced-motion handling, and desktop-only system animation.
- `wp-plugin/ranked-international/acf-json/group_rip_service_page.json` —
  editable Service Page fields.
- `wp-plugin/ranked-international/includes/seed.php` — disposable Local SEO
  fixture used in the local environment.
- `tests/service-preview.spec.js` — desktop, mobile, and reduced-motion
  browser coverage.

## Local verification

Run the following before changing or publishing the template:

```bash
npm run wp:start
npm run wp:test
```

`wp:test` runs local WordPress smoke checks plus the Playwright suite across
desktop, mobile, and reduced-motion projects. Current coverage includes the
single H1/canonical/schema guards, legacy-content guard, no-long-dash check,
section heading order, keyboard interactions, audit form behavior, mobile
overflow, swipe rails, workstream layout, and the system-diagram icon/label
separation.

## Publish boundary

The local fixture does not alter the client database or live URLs. Do not use
the local preview as a deployment mechanism. Any production release must use
the manual `Publish WordPress plugin` workflow described in the plugin README,
followed by the per-page go-live switch and the live-site verification steps.
