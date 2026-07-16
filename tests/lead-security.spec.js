const { test, expect } = require('@playwright/test');
const { randomUUID } = require('crypto');

async function config(page) {
  await page.goto('/local-seo-services/', { waitUntil: 'networkidle' });
  return page.evaluate(() => window.RankdWP);
}

function validLead(overrides = {}) {
  return {
    action: 'rankd_audit_lead',
    name: 'Security QA',
    email: `security-${randomUUID()}@example.test`,
    phone: '469-555-0100',
    website: 'https://example.test',
    market: 'Dallas, TX',
    service: 'Local SEO',
    page_url: 'http://localhost:8891/local-seo-services/',
    request_id: randomUUID(),
    recaptcha_token: 'local-test',
    ...overrides,
  };
}

test('rejects a populated honeypot without storing a lead', async ({ page, request }) => {
  const settings = await config(page);
  const response = await request.post(settings.ajaxUrl, { form: validLead({ nonce: settings.nonce, company_fax: 'bot' }) });
  expect(response.status()).toBe(400);
  expect((await response.json()).data.code).toBe('submission_rejected');
});

test('rejects invalid server-side fields', async ({ page, request }) => {
  const settings = await config(page);
  const response = await request.post(settings.ajaxUrl, { form: validLead({ nonce: settings.nonce, phone: 'bad' }) });
  expect(response.status()).toBe(422);
  expect((await response.json()).data.code).toBe('invalid_phone');
});

test('accepts a scheme-less website while CAPTCHA is disabled', async ({ page, request }) => {
  const settings = await config(page);
  const response = await request.post(settings.ajaxUrl, { form: validLead({ nonce: settings.nonce, website: 'www.example.test', recaptcha_token: '' }) });
  expect(response.status()).toBe(202);
  expect((await response.json()).success).toBe(true);
});

test('rate limits repeated rejected attempts', async ({ page, request }) => {
  const settings = await config(page);
  const email = `rate-${randomUUID()}@example.test`;
  let response;
  for (let attempt = 0; attempt < 6; attempt += 1) {
    response = await request.post(settings.ajaxUrl, { form: validLead({ nonce: settings.nonce, email, request_id: randomUUID(), recaptcha_token: 'wrong' }) });
  }
  expect(response.status()).toBe(429);
  const payload = await response.json();
  expect(payload.data.code).toBe('rate_limited');
  expect(payload.data.retryAfter).toBe(900);
});
