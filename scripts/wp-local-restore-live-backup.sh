#!/usr/bin/env bash
set -euo pipefail

# Restore a supplied UpdraftPlus backup into this repository's disposable
# wp-env instance. This command never connects to production and is guarded to
# localhost:8891 by wp-local-guard.sh.
source "$(dirname "$0")/wp-local-guard.sh"

BACKUP_DIR="${WP_LIVE_BACKUP_DIR:-$(pwd)/wp-data}"
DB_ARCHIVE="$(find "$BACKUP_DIR" -maxdepth 1 -type f -name '*-db.gz' -print -quit)"
THEMES_ARCHIVE="$(find "$BACKUP_DIR" -maxdepth 1 -type f -name '*-themes.zip' -print -quit)"
PLUGINS_ARCHIVE="$(find "$BACKUP_DIR" -maxdepth 1 -type f -name '*-plugins.zip' -print -quit)"
UPLOADS_ARCHIVE="$(find "$BACKUP_DIR" -maxdepth 1 -type f -name '*-uploads.zip' -print -quit)"
UPLOADS2_ARCHIVE="$(find "$BACKUP_DIR" -maxdepth 1 -type f -name '*-uploads2.zip' -print -quit)"

for archive in "$DB_ARCHIVE" "$THEMES_ARCHIVE" "$PLUGINS_ARCHIVE" "$UPLOADS_ARCHIVE" "$UPLOADS2_ARCHIVE"; do
	if [[ -z "$archive" || ! -f "$archive" ]]; then
		echo "A complete live backup was not found in $BACKUP_DIR." >&2
		exit 66
	fi
done

if ! command -v docker >/dev/null 2>&1; then
	echo "Docker is required for the isolated local restore." >&2
	exit 69
fi

STAGE_DIR="$(mktemp -d "${TMPDIR:-/tmp}/ranked-live-replica.XXXXXX")"
cleanup() { rm -rf "$STAGE_DIR"; }
trap cleanup EXIT

echo "Starting the disposable local WordPress environment at $WP_LOCAL_URL..."
"$WP_ENV_BIN" start >/dev/null

WORDPRESS_CONTAINER=""
while IFS= read -r candidate; do
	if docker inspect "$candidate" --format '{{range .Mounts}}{{println .Source}}{{end}}' | grep -Eq '/wp-plugin/ranked-international$'; then
		WORDPRESS_CONTAINER="$candidate"
		break
	fi
done < <(docker ps -q --filter 'label=com.docker.compose.service=wordpress')

if [[ -z "$WORDPRESS_CONTAINER" ]]; then
	echo "Could not identify this repository's wp-env WordPress container." >&2
	exit 70
fi

CLI_CONTAINER=""
while IFS= read -r candidate; do
	if docker inspect "$candidate" --format '{{range .Mounts}}{{println .Source}}{{end}}' | grep -Eq '/wp-plugin/ranked-international$'; then
		CLI_CONTAINER="$candidate"
		break
	fi
done < <(docker ps -q --filter 'label=com.docker.compose.service=cli')

if [[ -z "$CLI_CONTAINER" ]]; then
	echo "Could not identify this repository's wp-env CLI container." >&2
	exit 70
fi

echo "Unpacking the backup into a temporary local staging area..."
gzip -cd "$DB_ARCHIVE" > "$STAGE_DIR/live.sql"
unzip -q "$THEMES_ARCHIVE" -d "$STAGE_DIR"
unzip -q "$PLUGINS_ARCHIVE" -d "$STAGE_DIR"
unzip -q "$UPLOADS_ARCHIVE" -d "$STAGE_DIR"
unzip -q "$UPLOADS2_ARCHIVE" -d "$STAGE_DIR"
TABLE_PREFIX="$(grep -m1 -oE 'CREATE TABLE `[^`]+_options`' "$STAGE_DIR/live.sql" | sed -E 's/CREATE TABLE `(.+)_options`/\1_/')"

if [[ ! "$TABLE_PREFIX" =~ ^[A-Za-z0-9_]+_$ ]]; then
	echo "Could not determine the backup database table prefix." >&2
	exit 65
fi

echo "Copying backed-up themes, plugins, and uploads into Docker only..."
docker exec "$WORDPRESS_CONTAINER" mkdir -p /var/www/html/wp-content/mu-plugins
docker cp "$STAGE_DIR/themes/." "$WORDPRESS_CONTAINER:/var/www/html/wp-content/themes/"
docker cp "$STAGE_DIR/plugins/." "$WORDPRESS_CONTAINER:/var/www/html/wp-content/plugins/"
docker cp "$STAGE_DIR/uploads" "$WORDPRESS_CONTAINER:/var/www/html/wp-content/"
mkdir -p "$STAGE_DIR/mu-plugins"
cp "$(pwd)/wp-local/mu-plugins/rip-local-safety.php" "$STAGE_DIR/mu-plugins/"
docker cp "$STAGE_DIR/mu-plugins/." "$WORDPRESS_CONTAINER:/var/www/html/wp-content/mu-plugins/"
docker cp "$STAGE_DIR/live.sql" "$WORDPRESS_CONTAINER:/tmp/ranked-live-backup.sql"
docker cp "$STAGE_DIR/live.sql" "$CLI_CONTAINER:/tmp/ranked-live-backup.sql"

# wp-env uses wp_ by default. The live snapshot uses its own prefix, so update
# only this disposable environment's generated config before WordPress loads.
docker exec "$WORDPRESS_CONTAINER" sed -i "s|^\\\$table_prefix = getenv_docker.*|\\\$table_prefix = '${TABLE_PREFIX}';|" /var/www/html/wp-config.php

echo "Replacing only the local Docker database..."
"$WP_ENV_BIN" run cli wp db reset --yes --skip-plugins --skip-themes >/dev/null
"$WP_ENV_BIN" run cli wp db import /tmp/ranked-live-backup.sql --skip-plugins --skip-themes >/dev/null

for production_url in \
	'https://rankedinternational.com' \
	'https://www.rankedinternational.com' \
	'http://rankedinternational.com' \
	'http://www.rankedinternational.com'; do
	"$WP_ENV_BIN" run cli wp search-replace "$production_url" "$WP_LOCAL_URL" \
		--all-tables --precise --skip-columns=guid --skip-plugins --skip-themes >/dev/null
done

"$WP_ENV_BIN" run cli wp option update home "$WP_LOCAL_URL" --skip-plugins --skip-themes >/dev/null
"$WP_ENV_BIN" run cli wp option update siteurl "$WP_LOCAL_URL" --skip-plugins --skip-themes >/dev/null
"$WP_ENV_BIN" run cli wp option update blogname 'Ranked International Live Replica (Local)' --skip-plugins --skip-themes >/dev/null
"$WP_ENV_BIN" run cli wp option update blog_public 0 --skip-plugins --skip-themes >/dev/null

# Keep rendering dependencies active. Deactivate only integrations that are not
# required to render pages and could otherwise attempt remote work.
"$WP_ENV_BIN" run cli wp plugin deactivate atlas-site-kit git-updater google-site-kit maintenance updraftplus angie --skip-plugins --skip-themes >/dev/null 2>&1 || true
"$WP_ENV_BIN" run cli wp plugin activate ranked-international --skip-plugins --skip-themes >/dev/null

if "$WP_ENV_BIN" run cli wp user get local-admin --field=ID --skip-plugins --skip-themes >/dev/null 2>&1; then
	"$WP_ENV_BIN" run cli wp user update local-admin --user_pass=password --role=administrator --skip-plugins --skip-themes >/dev/null
else
	"$WP_ENV_BIN" run cli wp user create local-admin local-admin@example.test --role=administrator --user_pass=password --skip-plugins --skip-themes >/dev/null
fi

"$WP_ENV_BIN" run cli wp rewrite flush --hard >/dev/null
"$WP_ENV_BIN" run cli wp cache flush --skip-plugins --skip-themes >/dev/null 2>&1 || true

echo "Local live replica ready: $WP_LOCAL_URL"
echo "Preview: $WP_LOCAL_URL/local-seo-services-offer/"
echo "Admin:   $WP_LOCAL_URL/wp-admin/ (local-admin / password)"
echo "Safety:  mail and non-local HTTP requests are blocked by a local mu-plugin."
