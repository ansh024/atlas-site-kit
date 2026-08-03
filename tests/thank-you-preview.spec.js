const { test, expect } = require('@playwright/test');

for (const path of ['/seo-audit-thank-you/', '/turf-thank-you/']) {
  test(`${path} offers a direct phone call`, async ({ page }) => {
    await page.goto(path, { waitUntil: 'networkidle' });

    const phone = page.locator('.rip-thank-you__cta--phone');
    await expect(phone).toHaveAttribute('href', 'tel:+18334024789');
    await expect(phone).toHaveText('Call 833-402-4789');
    await expect(phone).toHaveAttribute('aria-label', /833-402-4789/);

    const box = await phone.boundingBox();
    expect(box.height).toBeGreaterThanOrEqual(44);
    expect(await page.evaluate(() => document.documentElement.scrollWidth)).toBeLessThanOrEqual(
      await page.evaluate(() => document.documentElement.clientWidth + 1)
    );
  });
}
