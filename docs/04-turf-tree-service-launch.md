# Turf & Tree Service launch notes

## Purpose

`/turf-tree-service/` is a conversion-focused landing page for turf, tree service and outdoor home businesses. It combines direct-response copy with verified TX Artificial Turf & Design case-study evidence.

The production implementation is a selectable WordPress page template in the
Ranked International plugin. It deliberately uses the active theme's header,
navigation and footer, so a non-coder can continue to control those shared
elements from Appearance without editing this landing page.

## Page decisions

- Hero promise: “Show up first on Google and get the calls.”
- Primary CTA: `Get Free SEO Audit` across navigation, hero, mobile sticky CTA and final CTA.
- Verified proof: 221 monthly organic visitors, five #1 rankings, Domain Authority 55, 275 ranking keywords and 4,400+ backlinks for TX Artificial Turf & Design.
- Free-audit section: three compact deliverables for keyword gaps, Google Maps visibility and a clear plan.
- Differentiator cards: a mobile horizontal swipe experience with six focused brand-color commitment cards.
- Comparison table: Ranked International versus a typical SEO agency, positioned above the FAQ.

## Responsive behavior

- Desktop hero CTA and proof stats use explicit vertical spacing to prevent overlap.
- Mobile commitments use horizontal native scroll snap.
- Mobile final CTA stacks headline, supporting copy and CTA vertically.
- The mobile sticky audit CTA appears after the visitor scrolls beyond the initial hero.
- Mobile comparison table uses compact fixed columns so all three columns remain visible.

## Content and design guardrails

- Only documented case-study metrics are displayed as client results.
- Production copy uses neutral letter spacing and does not use em dashes or en dashes.
- Eyebrows are used only when they improve hierarchy.
- Compact sections use reduced vertical padding to avoid decorative empty space.

## Local preview

Start the disposable local WordPress replica, then open:

```sh
npm run wp:start
```

Open `http://localhost:8891/turf-tree-service/`.

The local replica includes a locally published fixture page with this template
selected. That page exists only in the local Docker database and is not part of
the deployable plugin.

## Publishing in WordPress

After the plugin release is deployed:

1. In WordPress, create a Page titled `Turf & Tree Service` with the slug
   `turf-tree-service`.
2. In Page Attributes or Template, select `Ranked Intl: Turf & Tree Service`.
3. Keep the page as a draft while reviewing the theme navigation, footer and
   shared WPForms audit modal.
4. Publish only after the page is approved. If the landing page should appear
   in navigation, add that menu item in Appearance, not in the template.
