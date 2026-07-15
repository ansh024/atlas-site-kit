#!/usr/bin/env python3
"""
SEO meta snapshot + diff tool for the WordPress migration.

Usage:
  1. Before switching to WordPress (against the live site):
       python3 meta-audit.py snapshot https://rankedinternational.com before.json
  2. After the WP site is up (against staging or production):
       python3 meta-audit.py snapshot https://staging.example.com after.json
  3. Compare:
       python3 meta-audit.py diff before.json after.json

Checks per page: HTTP status, <title>, meta description, canonical,
meta robots, og:title / og:description / og:url, first <h1>, and
whether duplicate <title>/canonical tags exist (the classic WP-plugin
double-tag bug).

No third-party dependencies — plain urllib + regex.
"""
import json
import re
import sys
import urllib.request

# Pages the redesign introduces or replaces — meta changes here are expected.
REDESIGN_PATHS = [
    "/",
    "/construction/",
    "/case-studies/",
    "/case-studies/alexis-delivery-service/",
    "/case-studies/bella-med-spa/",
    "/case-studies/dfw-flower-wall/",
    "/case-studies/reyes-custom-millwork/",
    "/case-studies/social-pro-photo-booth/",
    "/case-studies/turf-and-design/",
]

# Everything else the live WordPress site serves today (from its Yoast
# sitemaps, July 2026). These must come through the plugin install
# COMPLETELY unchanged — any diff here is a regression.
EXISTING_PATHS = [
    "/about-us/",
    "/contact/",
    "/our-services/",
    "/thank-you/",
    "/blog/",
    # service pages
    "/local-seo-services/",
    "/organic-seo-services/",
    "/technical-seo-services/",
    "/seo-consultant-services/",
    "/google-ads-management-services/",
    "/ppc-management-services/",
    "/enterprise-ppc-management-services/",
    "/enterprise-seo-services/",
    "/link-building-services/",
    "/cro-audit-services/",
    # old industry pages (top-level slugs — these are exactly what the
    # rip_industry URL fallback must never hijack)
    "/roofing/",
    "/hvac/",
    "/plumbing/",
    "/electrician/",
    "/fencing/",
    "/flooring/",
    "/manufacturing/",
    "/medical-supply-companies/",
    "/moving-company/",
    "/pharmaceutical/",
    "/remodeling/",
    "/plastic-surgery/",
    "/pools/",
    "/orthopedic/",
    "/nursing-homes/",
    "/med-spa/",
    "/windows-doors/",
    "/trucking/",
    # blog posts
    "/what-is-seo/",
    "/understanding-seo-services-types-and-benefits/",
    "/what-youre-missing-with-seo-that-will-make-a-world-of-difference/",
]

PATHS = REDESIGN_PATHS + EXISTING_PATHS

FIELDS = [
    "status", "title", "meta_description", "canonical", "robots",
    "og_title", "og_description", "og_url", "h1",
    "title_tag_count", "canonical_tag_count",
]


def fetch(url):
    req = urllib.request.Request(url, headers={"User-Agent": "meta-audit/1.0"})
    try:
        with urllib.request.urlopen(req, timeout=30) as r:
            return r.status, r.read().decode("utf-8", errors="replace")
    except urllib.error.HTTPError as e:
        return e.code, ""
    except Exception as e:
        return f"ERROR: {e}", ""


def first(pattern, html, flags=re.I | re.S):
    m = re.search(pattern, html, flags)
    return re.sub(r"\s+", " ", m.group(1)).strip() if m else None


def meta_content(html, attr, value):
    # matches both <meta name=... content=...> and <meta content=... name=...>
    pat1 = rf'<meta[^>]+{attr}=["\']{value}["\'][^>]+content=["\']([^"\']*)["\']'
    pat2 = rf'<meta[^>]+content=["\']([^"\']*)["\'][^>]+{attr}=["\']{value}["\']'
    return first(pat1, html) or first(pat2, html)


def audit_page(url):
    status, html = fetch(url)
    return {
        "status": status,
        "title": first(r"<title[^>]*>(.*?)</title>", html),
        "meta_description": meta_content(html, "name", "description"),
        "canonical": first(r'<link[^>]+rel=["\']canonical["\'][^>]+href=["\']([^"\']*)["\']', html)
        or first(r'<link[^>]+href=["\']([^"\']*)["\'][^>]+rel=["\']canonical["\']', html),
        "robots": meta_content(html, "name", "robots"),
        "og_title": meta_content(html, "property", "og:title"),
        "og_description": meta_content(html, "property", "og:description"),
        "og_url": meta_content(html, "property", "og:url"),
        "h1": first(r"<h1[^>]*>(.*?)</h1>", html),
        "title_tag_count": len(re.findall(r"<title[\s>]", html, re.I)),
        "canonical_tag_count": len(re.findall(r'rel=["\']canonical["\']', html, re.I)),
    }


def snapshot(base_url, out_file):
    base = base_url.rstrip("/")
    results = {}
    for path in PATHS:
        url = base + path
        print(f"  fetching {url} ...")
        results[path] = audit_page(url)
        page = results[path]
        if page["title_tag_count"] and page["title_tag_count"] > 1:
            print(f"    !! {page['title_tag_count']} <title> tags on this page")
        if page["canonical_tag_count"] and page["canonical_tag_count"] > 1:
            print(f"    !! {page['canonical_tag_count']} canonical tags on this page")
    with open(out_file, "w") as f:
        json.dump({"base_url": base, "pages": results}, f, indent=2)
    print(f"\nSaved snapshot of {len(results)} pages to {out_file}")


def norm_url(value, base):
    """Canonical/og:url will legitimately differ by domain between
    static hosting and WP staging — compare paths, not hosts."""
    if not isinstance(value, str):
        return value
    return re.sub(r"^https?://[^/]+", "", value) or "/"


def diff(before_file, after_file):
    before = json.load(open(before_file))
    after = json.load(open(after_file))
    problems = 0
    print("=" * 60)
    print("EXISTING PAGES — any diff below is a REGRESSION")
    print("=" * 60)
    problems += diff_paths(EXISTING_PATHS, before, after)
    print()
    print("=" * 60)
    print("REDESIGN PAGES — diffs expected; review they're intentional")
    print("=" * 60)
    diff_paths(REDESIGN_PATHS, before, after)  # informational, not counted
    if problems == 0:
        print("\nNo regressions on existing pages.")
    else:
        print(f"\n{problems} REGRESSION(S) on existing pages — do not go live.")
    sys.exit(1 if problems else 0)


def diff_paths(paths, before, after):
    problems = 0
    for path in paths:
        b = before["pages"].get(path)
        a = after["pages"].get(path)
        header_shown = False
        if not b or not a:
            print(f"\n{path}\n  MISSING from {'before' if not b else 'after'} snapshot")
            problems += 1
            continue
        for field in FIELDS:
            bv, av = b.get(field), a.get(field)
            if field in ("canonical", "og_url"):
                bv, av = norm_url(bv, before["base_url"]), norm_url(av, after["base_url"])
            if bv != av:
                if not header_shown:
                    print(f"\n{path}")
                    header_shown = True
                print(f"  {field}:")
                print(f"    before: {bv!r}")
                print(f"    after:  {av!r}")
                problems += 1
        # flag duplicates in the after snapshot even if before had them too
        if (a.get("title_tag_count") or 0) > 1 or (a.get("canonical_tag_count") or 0) > 1:
            print(f"\n{path}\n  !! duplicate <title>/canonical tags in AFTER snapshot")
            problems += 1
    if problems == 0:
        print("  (no differences)")
    return problems


if __name__ == "__main__":
    if len(sys.argv) == 4 and sys.argv[1] == "snapshot":
        snapshot(sys.argv[2], sys.argv[3])
    elif len(sys.argv) == 4 and sys.argv[1] == "diff":
        diff(sys.argv[2], sys.argv[3])
    else:
        print(__doc__)
        sys.exit(2)
