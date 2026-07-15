#!/usr/bin/env bash
set -euo pipefail
source "$(dirname "$0")/wp-local-guard.sh"

STATUS="$(curl --silent --output /tmp/ranked-service.html --write-out '%{http_code}' "$WP_LOCAL_URL/local-seo-services/")"
[[ "$STATUS" == "200" ]] || { echo "Expected HTTP 200, got $STATUS" >&2; exit 1; }

assert_count() {
  local expected="$1" pattern="$2" label="$3"
  local actual
  actual="$(grep -Eio "$pattern" /tmp/ranked-service.html | wc -l | tr -d ' ')"
  [[ "$actual" == "$expected" ]] || { echo "$label: expected $expected, got $actual" >&2; exit 1; }
}
assert_present() { grep -Eiq "$1" /tmp/ranked-service.html || { echo "Missing: $2" >&2; exit 1; }; }
assert_absent() { ! grep -Eiq "$1" /tmp/ranked-service.html || { echo "Unexpected: $2" >&2; exit 1; }; }

assert_count 1 '<h1([ >])' 'Canonical H1 count'
assert_count 1 '<link rel="canonical"' 'Canonical tag count'
assert_count 1 '<title>' 'Title tag count'
assert_present 'Own the searches happening' 'Local SEO hero'
assert_present '"@type":"Service"' 'Service schema'
assert_present '"@type":"BreadcrumbList"' 'Breadcrumb schema'
assert_present '"@type":"FAQPage"' 'FAQ schema'
assert_absent 'Benchling|Outgrid|Biopharmaceutical|Industrial Biotech' 'legacy template content'

"$WP_ENV_BIN" run cli wp plugin is-active ranked-international
"$WP_ENV_BIN" run cli wp plugin is-active advanced-custom-fields
"$WP_ENV_BIN" run cli wp eval 'if (get_post_type_object("rip_service") === null) { exit(1); }'

echo "Smoke tests passed: routing, plugin activation, single H1/title/canonical, schema, and legacy-content guard."
