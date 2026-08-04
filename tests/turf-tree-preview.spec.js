const { test, expect } = require('@playwright/test');

test.beforeEach(async ({ page }) => {
  // The always-on GHL iframe emits analytics requests after it renders, so
  // networkidle is not a meaningful readiness signal for this page.
  await page.goto('/seo-for-turf-tree-care-outdoor-services/', { waitUntil: 'domcontentloaded' });
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
  await expect(page.locator('#audit .ghl-audit-form')).toHaveAttribute('src', /api\.leadconnectorhq\.com\/widget\/form\//);
  await expect(page.frameLocator('#audit .ghl-audit-form').locator('body')).toContainText('Full Name', { timeout: 15_000 });
  await expect(page.frameLocator('#audit .ghl-audit-form').locator('input')).not.toHaveCount(0);

  const mobile = page.viewportSize().width <= 640;
  const placements = ['hero-audit', 'final-audit', ...(mobile ? ['mobile-sticky-audit'] : [])];
  for (const [index, placement] of placements.entries()) {
    if (index > 0) {
      await page.goto('/seo-for-turf-tree-care-outdoor-services/', { waitUntil: 'domcontentloaded' });
    }
    if (placement === 'mobile-sticky-audit') {
      await expect.poll(async () => page.evaluate(() => {
        scrollTo(0, 500);
        dispatchEvent(new Event('scroll'));
        return document.querySelector('.mobile-sticky-actions').classList.contains('is-visible');
      })).toBe(true);
    }
    await page.locator(`[data-track="${placement}"]`).click();
    await expect(page).toHaveURL(/#audit$/);
    await expect(page.locator('#audit')).toBeInViewport();
    await expect(page.locator('#auditModal')).toHaveCount(0);
  }
  if (mobile) {
    await expect(page.locator('.mobile-sticky-actions')).not.toHaveClass(/is-visible/);
  }
});

test('removes a legacy popup if stale markup injects one beside the inline form', async ({ page }) => {
  await page.route('**/seo-for-turf-tree-care-outdoor-services/', async route => {
    const response = await route.fetch();
    const body = (await response.text()).replace(
      '</main>',
      '</main><div class="audit-modal" id="auditModal" aria-hidden="true"></div>'
    );
    await route.fulfill({ response, body });
  });
  await page.reload({ waitUntil: 'domcontentloaded' });
  await expect(page.locator('#audit.trade-audit')).toHaveCount(1);
  await expect(page.locator('#auditModal')).toHaveCount(0);
});

test('prevents long-lived browser or CDN caching of campaign HTML', async ({ request }) => {
  const response = await request.get('/seo-for-turf-tree-care-outdoor-services/');
  expect(response.headers()['cache-control']).toContain('no-store');
  expect(response.headers()['cloudflare-cdn-cache-control']).toBe('no-store');
  expect(response.headers()['cdn-cache-control']).toBe('no-store');
});

test('keeps the mobile audit and call actions visible and touch safe', async ({ page }, testInfo) => {
  test.skip(testInfo.project.name !== 'mobile');
  const sticky = page.locator('.mobile-sticky-actions');
  await expect.poll(async () => page.evaluate(() => {
    scrollTo(0, 500);
    dispatchEvent(new Event('scroll'));
    return document.querySelector('.mobile-sticky-actions').classList.contains('is-visible');
  })).toBe(true);

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
