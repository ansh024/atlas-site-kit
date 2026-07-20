#!/usr/bin/env bash
set -euo pipefail
source "$(dirname "$0")/wp-local-guard.sh"

SERVICE_PATH="/local-seo-services/"
STATUS="$(curl --silent --output /tmp/ranked-service.html --write-out '%{http_code}' "$WP_LOCAL_URL$SERVICE_PATH")"

# CI seeds the canonical service slug; the disposable live-data replica keeps
# the client's current offer-page slug. Exercise whichever local route exists.
if [[ "$STATUS" != "200" ]]; then
  SERVICE_PATH="/local-seo-services-offer/"
  STATUS="$(curl --silent --output /tmp/ranked-service.html --write-out '%{http_code}' "$WP_LOCAL_URL$SERVICE_PATH")"
fi

[[ "$STATUS" == "200" ]] || { echo "Expected HTTP 200 for a Local SEO service route, got $STATUS" >&2; exit 1; }
perl -0777 -ne 'if (/<main\b.*?<\/main>/si) { print $&; }' /tmp/ranked-service.html > /tmp/ranked-service-main.html

assert_count() {
  local expected="$1" pattern="$2" label="$3"
  local actual
  actual="$(grep -Eio "$pattern" /tmp/ranked-service.html | wc -l | tr -d ' ')"
  [[ "$actual" == "$expected" ]] || { echo "$label: expected $expected, got $actual" >&2; exit 1; }
}
assert_present() { grep -Eiq "$1" /tmp/ranked-service.html || { echo "Missing: $2" >&2; exit 1; }; }
assert_absent() { ! grep -Eiq "$1" /tmp/ranked-service-main.html || { echo "Unexpected: $2" >&2; exit 1; }; }

MAIN_H1_COUNT="$(perl -0777 -ne 'if (/<main\b.*?<\/main>/si) { $main = $&; @h1 = ($main =~ /<h1(?:[ >])/gi); print scalar @h1; }' /tmp/ranked-service.html)"
[[ "$MAIN_H1_COUNT" == "1" ]] || { echo "Page-content H1 count: expected 1, got ${MAIN_H1_COUNT:-0}" >&2; exit 1; }
assert_count 1 '<link rel="canonical"' 'Canonical tag count'
assert_present '<title>' 'document title'
assert_present 'Own the searches happening' 'Local SEO hero'
assert_present '"@type"[[:space:]]*:[[:space:]]*"Service"' 'Service schema'
assert_present '"@type"[[:space:]]*:[[:space:]]*"BreadcrumbList"' 'Breadcrumb schema'
assert_present '"@type"[[:space:]]*:[[:space:]]*"FAQPage"' 'FAQ schema'
assert_absent 'Benchling|Outgrid|Biopharmaceutical|Industrial Biotech' 'legacy template content in the service page'

"$WP_ENV_BIN" run cli wp plugin is-active ranked-international
YOAST_SLUG="$("$WP_ENV_BIN" run cli wp plugin list --status=active --field=name | grep '^wordpress-seo' | head -1 | tr -d '\r')"
[[ -n "$YOAST_SLUG" ]] || { echo "Yoast SEO is not active." >&2; exit 1; }
SMTP_SLUG="$("$WP_ENV_BIN" run cli wp plugin list --status=active --field=name | grep '^wp-mail-smtp' | head -1 | tr -d '\r')"
[[ -n "$SMTP_SLUG" ]] || { echo "WP Mail SMTP is not active." >&2; exit 1; }
ACF_SLUG="$("$WP_ENV_BIN" run cli wp plugin list --status=active --field=name | grep '^advanced-custom-fields' | head -1 | tr -d '\r')"
[[ -n "$ACF_SLUG" ]] || { echo "Advanced Custom Fields is not active." >&2; exit 1; }
"$WP_ENV_BIN" run cli wp eval 'if (get_post_type_object("rip_service") === null) { exit(1); }'

# City Pages must remain a first-class reusable content type with their ACF
# editor, top-level routing, and Dallas seed. Publish only inside this
# disposable environment so the public template can be exercised.
"$WP_ENV_BIN" run cli wp eval '
$city = get_page_by_path("dallas", OBJECT, "rip_city");
if (!$city || get_post_meta($city->ID, "hero_eyebrow", true) !== "DALLAS SEO AGENCY") { exit(1); }
$groups = acf_get_field_groups(array("post_type" => "rip_city"));
if (!in_array("group_rip_city_page", wp_list_pluck($groups, "key"), true)) { exit(1); }
wp_update_post(array("ID" => $city->ID, "post_status" => "publish"));
'
CITY_STATUS="$(curl --silent --output /tmp/ranked-city.html --write-out '%{http_code}' "$WP_LOCAL_URL/dallas/")"
[[ "$CITY_STATUS" == "200" ]] || { echo "Expected HTTP 200 for the Dallas City Page, got $CITY_STATUS" >&2; exit 1; }
grep -Fq 'DALLAS SEO AGENCY' /tmp/ranked-city.html || { echo "Missing: Dallas City Page ACF hero" >&2; exit 1; }
grep -Fq 'Results Dallas businesses' /tmp/ranked-city.html || { echo "Missing: Dallas City Page localized results heading" >&2; exit 1; }
"$WP_ENV_BIN" run cli wp eval '
$source = get_page_by_path("dallas", OBJECT, "rip_city");
$copy_id = rip_duplicate_city_post($source->ID);
if (is_wp_error($copy_id)) { exit(1); }
$copy = get_post($copy_id);
if ($copy->post_type !== "rip_city" || $copy->post_status !== "draft") { exit(1); }
if (get_post_meta($copy_id, "hero_eyebrow", true) !== "DALLAS SEO AGENCY") { exit(1); }
if (get_post_meta($copy_id, "_hero_eyebrow", true) !== "field_rip_city_hero_eyebrow") { exit(1); }
wp_delete_post($copy_id, true);
'

echo "Smoke tests passed: routing, plugin activation, reusable City Page/ACF, single H1/title/canonical, schema, and legacy-content guard."
