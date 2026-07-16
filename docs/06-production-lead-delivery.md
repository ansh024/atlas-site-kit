# Production Lead Delivery

## Release blockers

Do not publish the audit form until all of the following are complete:

- Rotate the reCAPTCHA secret that was exposed during development.
- Restrict the replacement v3 key to the production and staging hostnames.
- Configure `RIP_RECAPTCHA_SITE_KEY`, `RIP_RECAPTCHA_SECRET_KEY`, the score
  threshold, and `RIP_AUDIT_LEAD_RECIPIENT` outside Git.
- Submit a staging lead and confirm the private record, owner inbox delivery,
  Reply-To address and WordPress delivery state. WP Mail SMTP and a
  transactional provider such as Brevo are optional deliverability upgrades,
  not release blockers.

## Delivery contract

The AJAX endpoint returns `delivered` when the lead was stored and `wp_mail()`
accepted the owner notification. It returns HTTP 202 with `delivery_pending`
when the lead was safely stored but email needs retry. It never shows the final
success state when storage fails.

Retries run through WP-Cron after 5 minutes, 30 minutes, 2 hours, 12 hours and
24 hours. A terminal failure is visible in Audit Leads and an admin notice.
Administrators can use **Retry notification** from the lead row actions.

`wp_mail()` acceptance is not proof of inbox placement. The hosting mail log
or an optional transactional provider is authoritative for final delivery
investigation. The optional
`rip_audit_lead_message_id` filter can attach a provider message identifier
when the production mail integration exposes one.

## Privacy and retention

Audit Leads are private, excluded from REST, front-end queries, search and
sitemaps, and accessible only to administrators. The plugin stores submitted
contact fields, source URL, timestamps, CAPTCHA score and delivery operations.
It never stores raw IP addresses, CAPTCHA tokens or credentials.

Records are permanently deleted after 180 days. Add the audit form, its
purpose, recipients and this retention period to the production privacy notice
before launch. Change retention with the `rip_audit_lead_retention_days` filter
only after the privacy notice and business policy are updated.

## Future CRM

No CRM is configured in this release. Integrate later from
`rip_audit_lead_captured` using the stored lead ID and an asynchronous,
idempotent webhook. Do not add synchronous CRM calls to the browser request.
