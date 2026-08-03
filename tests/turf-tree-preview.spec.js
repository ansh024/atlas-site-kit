const { test, expect } = require('@playwright/test');

test.beforeEach(async ({ page }) => {
  await page.goto('/seo-for-turf-tree-care-outdoor-services/', { waitUntil: 'networkidle' });
});

test('offers tracked audit and phone actions at every conversion point', async ({ page }) => {
  const callLinks = page.locator('a[href="tel:+18334024789"]');
  await expect(callLinks).toHaveCount(3);
  await expect(page.locator('.trade-hero__actions .trade-call-btn')).toContainText('Call 833-402-4789');
  await expect(page.locator('.trade-cta__buttons .trade-call-btn')).toContainText('Call 833-402-4789');
  await expect(page.locator('.mobile-sticky-call')).toHaveAttribute('aria-label', /833-402-4789/);

  await page.evaluate(() => {
    window.__rankedAnalytics = [];
    window.addEventListener('ranked:analytics', event => window.__rankedAnalytics.push(event.detail));
    const call = document.querySelector('[data-track="hero-call"]');
    call.addEventListener('click', event => event.preventDefault(), { once: true });
    call.click();
  });
  expect(await page.evaluate(() => window.__rankedAnalytics)).toContainEqual({
    event: 'call_cta_click',
    placement: 'hero-call'
  });
});

test('takes every audit CTA to the inline form instead of opening a popup', async ({ page }) => {
  await expect(page.locator('#auditModal')).toHaveCount(0);
  await expect(page.locator('#audit.trade-audit')).toHaveCount(1);

  await page.locator('[data-track="hero-audit"]').click();
  await expect(page).toHaveURL(/#audit$/);
  await expect(page.locator('#audit')).toBeInViewport();
  await expect(page.locator('#audit .ghl-audit-form')).toHaveAttribute('src', /api\.leadconnectorhq\.com\/widget\/form\//);
  if (page.viewportSize().width <= 640) {
    await expect(page.locator('.mobile-sticky-actions')).not.toHaveClass(/is-visible/);
  }
});

test('keeps the mobile audit and call actions visible and touch safe', async ({ page }, testInfo) => {
  test.skip(testInfo.project.name !== 'mobile');
  await page.evaluate(() => scrollTo(0, 500));
  const sticky = page.locator('.mobile-sticky-actions');
  await expect(sticky).toHaveClass(/is-visible/);

  for (const selector of ['.mobile-sticky-audit', '.mobile-sticky-call']) {
    const box = await page.locator(selector).boundingBox();
    expect(box.height).toBeGreaterThanOrEqual(52);
  }

  const dimensions = await page.evaluate(() => ({
    documentWidth: document.documentElement.scrollWidth,
    viewportWidth: document.documentElement.clientWidth,
    stickyRight: document.querySelector('.mobile-sticky-actions').getBoundingClientRect().right
  }));
  expect(dimensions.documentWidth).toBeLessThanOrEqual(dimensions.viewportWidth + 1);
  expect(dimensions.stickyRight).toBeLessThanOrEqual(dimensions.viewportWidth);
});
