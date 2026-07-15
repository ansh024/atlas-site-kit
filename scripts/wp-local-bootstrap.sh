#!/usr/bin/env bash
set -euo pipefail
source "$(dirname "$0")/wp-local-guard.sh"

"$WP_ENV_BIN" run cli wp plugin activate advanced-custom-fields ranked-international >/dev/null
"$WP_ENV_BIN" run cli wp option update blogname "Ranked International Local QA" >/dev/null
"$WP_ENV_BIN" run cli wp option update permalink_structure '/%postname%/' >/dev/null
"$WP_ENV_BIN" run cli wp rewrite flush --hard >/dev/null

# The plugin seeds the Local SEO reference as a draft. Publish only inside this
# disposable wp-env database so the canonical route is available for QA.
SERVICE_ID="$("$WP_ENV_BIN" run cli wp post list --post_type=rip_service --name=local-seo-services --post_status=any --field=ID | tr -d '\r' | tail -1)"
if [[ ! "$SERVICE_ID" =~ ^[0-9]+$ ]]; then
  echo "Local SEO service fixture was not created." >&2
  exit 1
fi
"$WP_ENV_BIN" run cli wp post update "$SERVICE_ID" --post_status=publish >/dev/null
"$WP_ENV_BIN" run cli wp rewrite flush --hard >/dev/null

echo "Local WordPress ready: $WP_LOCAL_URL/local-seo-services/"
echo "Admin: $WP_LOCAL_URL/wp-admin/ (admin / password)"
