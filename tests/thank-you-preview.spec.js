const { test, expect } = require('@playwright/test');

// The SEO for Businesses campaign advertises its own tracked line; every other
// route keeps the general number.
for (const [path, number] of [
  ['/seo-audit-thank-you/', '833-402-4789'],
  ['/turf-thank-you/', '833-402-4789'],
  ['/thank-you-seo-for-businesses-lp/', '833-385-7090'],
  ['/thank-you-main-site/', '833-402-4789'],
]) {
  test(`${path} offers a direct phone call`, async ({ page }) => {
    await page.goto(path, { waitUntil: 'networkidle' });

    const phone = page.locator('.rip-thank-you__cta--phone');
    await expect(phone).toHaveAttribute('href', `tel:+1${number.replace(/-/g, '')}`);
    await expect(phone).toHaveText(`Call ${number}`);
    await expect(phone).toHaveAttribute('aria-label', new RegExp(number));

    const box = await phone.boundingBox();
    expect(box.height).toBeGreaterThanOrEqual(44);
    expect(await page.evaluate(() => document.documentElement.scrollWidth)).toBeLessThanOrEqual(
      await page.evaluate(() => document.documentElement.clientWidth + 1)
    );
  });
}

test('SEO businesses thank-you fires only its dedicated Meta conversion pixel', async ({ page }) => {
  await page.goto('/thank-you-seo-for-businesses-lp/', { waitUntil: 'networkidle' });

  await expect(page.locator('#rip-meta-seo-businesses-conversion')).toHaveCount(1);
  const html = await page.content();
  expect(html).toContain("fbq('init','1975861066398590')");
  expect(html).toContain("fbq('track','PageView')");
  expect(html).toContain("fbq('track','Lead')");
  // Whitespace-tolerant: the stray block pasted into WP admin writes
  // `fbq('init', '1686…')` with a space, which an exact match would miss.
  expect(html).not.toMatch(/fbq\(\s*'init'\s*,\s*'1686472339304024'/);
  expect(html).not.toContain('facebook.com/tr?id=1686472339304024');
  expect(html).toContain('1975861066398590&amp;ev=PageView&amp;noscript=1');

  // Exactly one pixel, one PageView fallback — no duplicate noscript beacons.
  expect(html.match(/facebook\.com\/tr\?id=/g)).toHaveLength(1);
  expect(html.match(/fbq\(\s*'init'/g)).toHaveLength(1);
});
