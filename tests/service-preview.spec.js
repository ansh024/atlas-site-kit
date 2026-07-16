const { test, expect } = require('@playwright/test');

test.beforeEach(async ({ page }) => {
  await page.goto('/local-seo-services/', { waitUntil: 'networkidle' });
});

test('renders the service narrative and structured UI', async ({ page }) => {
  await expect(page).toHaveTitle(/Local SEO Services/);
  await expect(page.locator('h1')).toHaveCount(1);
  await expect(page.locator('h1')).toContainText('Own the searches happening');
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

test('audit CTA opens the WPForms-owned modal', async ({ page }) => {
  await page.locator('.svc-hero__actions a[href="#audit"]').click();
  await expect(page.locator('#auditModal')).toHaveClass(/is-open/);
  await expect(page.locator('#auditModal')).toHaveAttribute('aria-hidden', 'false');
  await expect(page.locator('#auditForm')).toHaveCount(0);
  await expect(page.locator('.audit-modal__wpforms')).toBeVisible();
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
