#!/usr/bin/env bash
set -euo pipefail
source "$(dirname "$0")/wp-local-guard.sh"

if ! "$WP_ENV_BIN" run cli wp core is-installed >/dev/null 2>&1; then
  "$WP_ENV_BIN" run cli wp core install \
    --url="$WP_LOCAL_URL" \
    --title="Ranked International Local QA" \
    --admin_user=admin \
    --admin_password=password \
    --admin_email=admin@example.test \
    --skip-email >/dev/null
fi

ACF_SLUG="$("$WP_ENV_BIN" run cli wp plugin list --field=name | grep '^advanced-custom-fields' | head -1 | tr -d '\r')"
[[ -n "$ACF_SLUG" ]] || { echo "Advanced Custom Fields was not mounted by wp-env." >&2; exit 1; }
YOAST_SLUG="$("$WP_ENV_BIN" run cli wp plugin list --field=name | grep '^wordpress-seo' | head -1 | tr -d '\r')"
[[ -n "$YOAST_SLUG" ]] || { echo "Yoast SEO was not mounted by wp-env." >&2; exit 1; }
SMTP_SLUG="$("$WP_ENV_BIN" run cli wp plugin list --field=name | grep '^wp-mail-smtp' | head -1 | tr -d '\r')"
[[ -n "$SMTP_SLUG" ]] || { echo "WP Mail SMTP was not mounted by wp-env." >&2; exit 1; }
"$WP_ENV_BIN" run cli wp plugin activate "$ACF_SLUG" "$YOAST_SLUG" "$SMTP_SLUG" ranked-international >/dev/null
"$WP_ENV_BIN" run cli wp option update blogname "Ranked International Local QA" >/dev/null
"$WP_ENV_BIN" run cli wp option update permalink_structure '/%postname%/' >/dev/null
"$WP_ENV_BIN" run cli wp rewrite flush --hard >/dev/null

# The plugin seeds the Local SEO reference as a draft. Publish only inside this
# disposable wp-env database so the canonical route is available for QA.
SERVICE_ID="$("$WP_ENV_BIN" run cli wp post list --post_type=rip_service --name=local-seo-services --post_status=draft,publish --field=ID | tr -d '\r' | grep -E '^[0-9]+$' | head -1)"
if [[ ! "$SERVICE_ID" =~ ^[0-9]+$ ]]; then
  echo "Local SEO service fixture was not created." >&2
  exit 1
fi
"$WP_ENV_BIN" run cli wp post update "$SERVICE_ID" --post_status=publish >/dev/null
"$WP_ENV_BIN" run cli wp rewrite flush --hard >/dev/null

echo "Local WordPress ready: $WP_LOCAL_URL/local-seo-services/"
echo "Admin: $WP_LOCAL_URL/wp-admin/ (admin / password)"
