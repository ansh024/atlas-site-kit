const { chromium } = require('playwright');

const [url, output] = process.argv.slice(2);

if (!url || !output) {
  throw new Error('Usage: node scripts/capture-editor-guides.js <url> <output>');
}

(async () => {
  const browser = await chromium.launch({ headless: true });
  const page = await browser.newPage({ viewport: { width: 1440, height: 900 } });

  await page.goto(url, { waitUntil: 'domcontentloaded', timeout: 120000 });
  await page.waitForTimeout(1000);
  const height = await page.evaluate(() => document.documentElement.scrollHeight);

  // Trigger scroll-based reveal animations before taking the full-page image.
  for (let y = 0; y < height; y += 900) {
    await page.evaluate((scrollY) => window.scrollTo(0, scrollY), y);
    await page.waitForTimeout(25);
  }

  await page.evaluate(() => window.scrollTo(0, 0));
  await page.waitForTimeout(300);
  await page.screenshot({ path: output, fullPage: true });
  await browser.close();
})();
