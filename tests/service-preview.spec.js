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

test('audit form submits through the real local WordPress AJAX endpoint', async ({ page }) => {
  await page.locator('a[href="#audit"]').first().click();
  await page.getByLabel('Name').fill('Local QA');
  await page.getByLabel('Work email').fill('qa@example.test');
  await page.getByLabel('Phone').fill('469-555-0100');
  await page.getByRole('button', { name: 'Continue' }).click();
  await page.getByLabel('Website').fill('https://example.test');
  await page.getByLabel('Primary market').fill('Dallas, TX');
  const responsePromise = page.waitForResponse(response => response.url().includes('admin-ajax.php'));
  await page.getByRole('button', { name: 'Submit audit' }).click();
  const response = await responsePromise;
  expect(response.ok()).toBeTruthy();
  await expect(page.getByRole('heading', { name: 'Audit request received' })).toBeVisible();
});

test('mobile layout has no horizontal document overflow', async ({ page }, testInfo) => {
  test.skip(testInfo.project.name !== 'mobile');
  const dimensions = await page.evaluate(() => ({ scroll: document.documentElement.scrollWidth, client: document.documentElement.clientWidth }));
  expect(dimensions.scroll).toBeLessThanOrEqual(dimensions.client + 1);
});
