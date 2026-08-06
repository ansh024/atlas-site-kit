const { test, expect } = require('@playwright/test');

test.beforeEach(async ({ page }) => {
  await page.goto('/local-seo-services/', { waitUntil: 'networkidle' });
});

test('renders the service narrative and structured UI', async ({ page }) => {
  await expect(page).toHaveTitle(/Local SEO Services/);
  await expect(page.locator('body')).toHaveClass(/rip-service-page/);
  await expect(page.locator('main#top.rip-service-content h1')).toHaveCount(1);
  await expect(page.locator('main#top.rip-service-content h1')).toContainText('Own the searches happening');
  await expect(page.locator('.search-console')).toBeVisible();
  await expect(page.locator('.problem-row')).toHaveCount(4);
  await expect(page.locator('.blueprint__tabs [role="tab"]')).toHaveCount(7);
  await expect(page.locator('.svc-faq__item')).toHaveCount(5);
  await expect(page.locator('.svc-definition')).toBeVisible();
  await expect(page.locator('.svc-journey details')).toHaveCount(0);
  await expect(page.locator('.chapter-rail')).toHaveCount(0);
  await expect(page.locator('#problems h2')).toContainText('What is costing you leads?');
  await expect(page.locator('body')).not.toContainText(/Benchling|Outgrid|Biopharmaceutical/);
});

test('service hero reserves room for the fixed theme header', async ({ page }, testInfo) => {
  const padding = await page.locator('.svc-hero').evaluate(element => Number.parseFloat(getComputedStyle(element).paddingTop));
  const expected = testInfo.project.name === 'mobile' ? 90 : 112;
  expect(padding).toBe(expected);
});

test('section headings use a vertical eyebrow-heading-copy stack', async ({ page }) => {
  const heads = page.locator('.svc-section-head');
  for (let index = 0; index < await heads.count(); index += 1) {
    const head = heads.nth(index);
    const eyebrow = head.locator('.svc-eyebrow');
    const heading = head.locator('h2');
    const copy = head.locator(':scope > p:last-child');
    if (!await copy.count()) continue;
    const [eyebrowBox, headingBox, copyBox] = await Promise.all([eyebrow.boundingBox(), heading.boundingBox(), copy.boundingBox()]);
    expect(eyebrowBox.y).toBeLessThan(headingBox.y);
    expect(headingBox.y).toBeLessThan(copyBox.y);
  }
});

test('keeps service copy legible and avoids long dashes', async ({ page }) => {
  await expect(page.locator('body')).not.toContainText(/[—–]/);

  for (const selector of ['.svc-hero__summary', '.problem-row__cost p', '.blueprint__tabs button strong', '.phase-list p', '.fit-grid li']) {
    const fontSize = await page.locator(selector).first().evaluate(element => Number.parseFloat(getComputedStyle(element).fontSize));
    expect(fontSize, `${selector} should remain readable`).toBeGreaterThanOrEqual(15);
  }
});

test('workstream tabs and FAQ are keyboard operable', async ({ page }) => {
  const firstTab = page.locator('.blueprint__tabs [role="tab"]').first();
  await firstTab.focus();
  await page.keyboard.press('ArrowDown');
  await expect(page.locator('.blueprint__tabs [aria-selected="true"]')).toContainText('Local keyword strategy');

  const secondFaq = page.locator('.svc-faq__item button').nth(1);
  await secondFaq.focus();
  await page.keyboard.press('Enter');
  await expect(secondFaq).toHaveAttribute('aria-expanded', 'true');
});

// The audit popup is retired site-wide. These pages keep the theme footer, and
// the Organic GHL form lives in it, so main.js gives that section the `#audit`
// anchor every CTA already points at.
const auditFormInFooter = page => page.locator(
  '#uicore-tb-footer iframe[src*="leadconnectorhq.com/widget/form"]'
);

// The local WordPress database is a restore of the live site. Restores taken
// before the form was added to the Elementor footer have no target to scroll
// to, which is a stale fixture rather than a broken page.
async function skipWithoutFooterForm(page) {
  const present = await auditFormInFooter(page).count();
  test.skip(present === 0, 'Local WordPress restore predates the footer audit form; run npm run wp:restore-live.');
}

test('no page renders the retired audit popup', async ({ page }) => {
  await expect(page.locator('#auditModal')).toHaveCount(0);
  await expect(page.locator('.audit-modal')).toHaveCount(0);
  await expect(page.locator('.audit-modal__wpforms')).toHaveCount(0);
  await expect(page.locator('body')).not.toHaveClass(/audit-modal-open/);
});

test('audit CTA scrolls to the footer form instead of opening a popup', async ({ page }) => {
  await skipWithoutFooterForm(page);

  const target = page.locator('[data-rip-audit-target]');
  await expect(target).toHaveCount(1);
  await expect(target).toHaveAttribute('id', 'audit');
  await expect(auditFormInFooter(page)).toHaveAttribute(
    'src',
    /api\.leadconnectorhq\.com\/widget\/form\/f0ApiaQNdHgKKFOqtp8q/
  );

  await page.locator('.svc-hero__actions a[href="#audit"]').click();

  await expect(page).toHaveURL(/#audit$/);
  await expect(target).toBeInViewport();
  await expect(page.locator('#auditModal')).toHaveCount(0);
  await expect(page.locator('body')).not.toHaveClass(/audit-modal-open/);
});

// This campaign runs its own GHL form, not the site-wide Organic one, and it
// sits in the page like the turf lander's — a popup over the page was costing
// conversions here.
test('SEO businesses campaign renders its own Meta Form inline', async ({ page }) => {
  await page.goto('/seo-for-businesses/', { waitUntil: 'domcontentloaded' });
  await expect(page.locator('#auditModal')).toHaveCount(0);
  await expect(page.locator('.audit-modal__wpforms')).toHaveCount(0);
  await expect(page.locator('#inline-f0ApiaQNdHgKKFOqtp8q')).toHaveCount(0);

  const form = page.locator('#audit.trade-audit .ghl-audit-form');
  await expect(form).toHaveCount(1);
  await expect(form).toHaveAttribute('id', 'inline-NnlAud8uVZoK09OlAAFj');
  await expect(form).toHaveAttribute('data-form-name', 'Meta Form - General');
  await expect(form).toHaveAttribute('data-form-id', 'NnlAud8uVZoK09OlAAFj');
  await expect(form).toHaveAttribute('data-height', '772');
  await expect(form).toHaveAttribute(
    'src',
    'https://api.leadconnectorhq.com/widget/form/NnlAud8uVZoK09OlAAFj'
  );
  await expect(page.frameLocator('#audit .ghl-audit-form').locator('input')).not.toHaveCount(0);
});

// Every CTA on the lander is an #audit anchor. With the form in the page they
// must scroll to it, never re-open a popup or leave the page scroll-locked.
test('SEO businesses CTAs scroll to the inline audit form', async ({ page }) => {
  await page.goto('/seo-for-businesses/', { waitUntil: 'domcontentloaded' });
  await page.locator('a[href="#audit"]:visible').first().click();

  await expect(page.locator('#auditModal')).toHaveCount(0);
  await expect(page.locator('body')).not.toHaveClass(/audit-modal-open/);
  await expect(page.locator('#audit.trade-audit')).toBeInViewport();
});

// The desktop case-study rules only win from the "must remain last" block in
// trade-landing.css. When a template is left out of it, the metrics collapse
// into the narrow 250px column and clip their numbers.
test('SEO businesses case study lays its metrics across the full width', async ({ page }) => {
  await page.setViewportSize({ width: 1440, height: 900 });
  await page.goto('/seo-for-businesses/', { waitUntil: 'domcontentloaded' });

  const proof = page.locator('.trade-case__proof');
  await expect(proof).toHaveCSS('grid-template-columns', /^\d+(\.\d+)?px$/);

  const blocks = page.locator('.trade-case__metrics .cs__metric-block');
  await expect(blocks).toHaveCount(3);
  for (let i = 0; i < 3; i += 1) {
    const block = blocks.nth(i);
    const { width, scrollWidth } = await block.evaluate(el => ({
      width: el.getBoundingClientRect().width,
      scrollWidth: el.scrollWidth,
    }));
    expect(width).toBeGreaterThan(150);
    expect(scrollWidth).toBeLessThanOrEqual(Math.ceil(width));
  }
});

// The campaign has its own tracked line, so its sticky call must not fall back
// to the general number the homepage template ships with.
test('SEO businesses campaign calls its own tracked line', async ({ page }) => {
  await page.goto('/seo-for-businesses/', { waitUntil: 'domcontentloaded' });
  const call = page.locator('.mobile-sticky-call');
  await expect(call).toHaveAttribute('href', 'tel:+18333857090');
  await expect(call).toHaveAttribute('aria-label', /833-385-7090/);
});

// Every CTA on the page is an #audit anchor, so each one has to reach the form
// and leave the page scrollable — a CTA that lands short of the form, or behind
// the theme's fixed navigation, is a dead end for the only action here.
test('every audit CTA reaches the footer form and leaves the page scrollable', async ({ page }) => {
  await skipWithoutFooterForm(page);

  const target = page.locator('[data-rip-audit-target]');
  const ctas = page.locator('a[href="#audit"]:visible');
  expect(await ctas.count()).toBeGreaterThan(0);

  for (let index = 0; index < await ctas.count(); index += 1) {
    await page.evaluate(() => window.scrollTo(0, 0));
    await ctas.nth(index).click();

    await expect(target).toBeInViewport();
    // The theme's navigation is fixed, so the form's heading must clear it.
    const top = await target.evaluate(element => element.getBoundingClientRect().top);
    expect(top).toBeGreaterThanOrEqual(0);
    await expect(page.locator('body')).not.toHaveClass(/audit-modal-open/);
    await expect(page.locator('#auditModal')).toHaveCount(0);
  }
});

test('mobile layout has no horizontal document overflow', async ({ page }, testInfo) => {
  test.skip(testInfo.project.name !== 'mobile');
  const dimensions = await page.evaluate(() => ({ scroll: document.body.scrollWidth, client: document.documentElement.clientWidth }));
  expect(dimensions.scroll).toBeLessThanOrEqual(dimensions.client + 1);
});

test('mobile process phases use a horizontal card rail', async ({ page }, testInfo) => {
  test.skip(testInfo.project.name !== 'mobile');
  const rail = await page.locator('.phase-list').evaluate(element => ({ scroll: element.scrollWidth, client: element.clientWidth }));
  expect(rail.scroll).toBeGreaterThan(rail.client);
  await expect(page.locator('.phase-list li')).toHaveCount(4);
});

test('mobile diagnostic problems use a horizontal card rail', async ({ page }, testInfo) => {
  test.skip(testInfo.project.name !== 'mobile');
  const rail = await page.locator('.problem-list').evaluate(element => ({ scroll: element.scrollWidth, client: element.clientWidth }));
  expect(rail.scroll).toBeGreaterThan(rail.client);
  await expect(page.locator('.problem-row')).toHaveCount(4);
});

test('mobile customer journey uses a horizontal card rail', async ({ page }, testInfo) => {
  test.skip(testInfo.project.name !== 'mobile');
  const rail = await page.locator('.journey-flow').evaluate(element => ({ scroll: element.scrollWidth, client: element.clientWidth }));
  expect(rail.scroll).toBeGreaterThan(rail.client);
  await expect(page.locator('.journey-flow > div')).toHaveCount(4);
});

test('mobile blueprint keeps its explanation and deliverables separate', async ({ page }, testInfo) => {
  test.skip(testInfo.project.name !== 'mobile');
  const layout = await page.locator('.blueprint__canvas').evaluate(canvas => {
    const description = canvas.querySelector('#blueprintDescription').getBoundingClientRect();
    const output = canvas.querySelector('.blueprint__output').getBoundingClientRect();
    return { descriptionBottom: description.bottom, outputTop: output.top, height: canvas.getBoundingClientRect().height };
  });
  expect(layout.descriptionBottom).toBeLessThanOrEqual(layout.outputTop);
  expect(layout.height).toBeLessThan(520);
});

test('mobile workstream selectors stay compact and touch-safe', async ({ page }, testInfo) => {
  test.skip(testInfo.project.name !== 'mobile');
  const height = await page.locator('.blueprint__tabs [role="tab"]').first().evaluate(element => element.getBoundingClientRect().height);
  expect(height).toBeGreaterThanOrEqual(44);
  expect(height).toBeLessThanOrEqual(50);
});

test('mobile system outcome label does not overlap its icon', async ({ page }, testInfo) => {
  test.skip(testInfo.project.name !== 'mobile');
  const layout = await page.locator('.system-map__core').evaluate(core => {
    const icon = core.querySelector('svg').getBoundingClientRect();
    const label = core.querySelector('strong').getBoundingClientRect();
    return { iconBottom: icon.bottom, labelTop: label.top };
  });
  expect(layout.iconBottom).toBeLessThanOrEqual(layout.labelTop);
});
