# WordPress Deployment Checklist — Custom GSAP Pages

This is the standard playbook for turning a Claude Code–built, GSAP-animated page (homepage, contact, landing page, etc.) into a live page on a client's **self-hosted WordPress.org** site. Follow this every time — it's what keeps output world-class instead of "another page builder hack," and keeps the client from being fully dependent on you for every text edit.

## Pipeline overview

```
1. Build          →  Claude Code, static HTML/CSS/JS/GSAP, in this repo
2. WP-ify         →  Convert into a child theme + custom page template(s) + ACF fields
3. Test locally   →  WordPress Studio (mirrors client's real plugin stack)
4. Deploy         →  FTP/SFTP the theme to the client's self-hosted server
5. Handoff        →  Client edits content via ACF fields; blog stays on Elementor/Gutenberg, untouched
```

Core approach: **custom page templates in a lightweight child theme**, built with Claude Code and tested locally in **WordPress Studio**, with a small set of **ACF fields** for client-editable text/images, deployed via FTP/SFTP to the client's own host (most client sites are plain self-hosted WordPress.org, not WordPress.com/Pressable — see §5).

---

## 1. Build the page (Claude Code, this repo)

- [ ] Build the page as static HTML/CSS/JS with GSAP, same as any other page in this repo — no WordPress-specific concerns yet at this stage.
- [ ] Keep sections that will need client editing clearly identifiable (e.g. a distinct wrapper/comment around the hero headline, subtext, and hero image) — makes step 2 mechanical instead of a rewrite.
- [ ] Finalize animations, responsive behavior, and content structure *before* converting to a WP template — much easier to edit plain HTML/JS than PHP-with-embedded-loops.

## 2. Convert to WordPress (child theme + template + ACF)

- [ ] Create/reuse a child theme (`style.css` with `Template:` pointing to parent, `functions.php` enqueuing parent + child styles).
- [ ] One custom page template per unique design, e.g. `page-home.php`, `page-contact.php`, `page-landing-x.php` — port the static HTML into it, swapping the client-editable spots for ACF field calls (see below).
- [ ] Every custom template **must** call `get_header()` / `wp_head()` and `get_footer()` / `wp_footer()` — even for a "bare" page with no theme chrome, write a minimal header/footer partial that only outputs `wp_head()`/`wp_footer()`. Non-negotiable: Yoast SEO, analytics, and most plugins hook into these and silently stop working without them.
- [ ] Enqueue GSAP + page-specific JS/CSS conditionally (`is_page_template()` check in `functions.php`), not inline `<script>` tags — keeps other pages fast and avoids global namespace collisions.
- [ ] Keep the child theme lightweight — only override the specific parent template files you need, don't copy the whole parent theme.

### Client-editable content (ACF)

The point of this step: the client should be able to change headlines, body text, and images **without depending on you**, but can't touch structure, animation, or layout.

- [ ] Install **ACF** (free tier is enough unless a page needs repeatable blocks like testimonials — then ACF Pro).
- [ ] Register field groups via **PHP** (`acf_add_local_field_group`) so fields ship with the theme code and are version-controlled — not created ad hoc in the ACF admin UI. (Local JSON is the other valid option if you want fields editable via UI + synced via git; pick one approach and stay consistent.)
- [ ] Scope fields tightly: plain `text` fields (not WYSIWYG/textarea) for anything animation-sensitive, so clients can't inject stray HTML/formatting that breaks layout.
- [ ] Set a `maxlength` on every text field matched to what the design/animation can actually handle.
- [ ] Every field read in the template has a fallback: `get_field('headline') ?: 'Default Headline'` — never render blank space if a client clears a field.
- [ ] Output all field values through `esc_html()` (or `esc_url()` for links, `wp_kses_post()` only if rich text is genuinely needed).
- [ ] Image fields: constrain via CSS (`object-fit: cover` on a fixed-aspect container) so a wrong-aspect-ratio upload crops instead of distorting.
- [ ] Scope field group visibility/permissions to the relevant page template and appropriate user roles (Editor/Admin), not site-wide.

## 3. Test locally (WordPress Studio)

- [ ] Spin up a local site in [WordPress Studio](https://developer.wordpress.com/studio/) matching the client's PHP/WP version.
- [ ] If the client has an existing live site, pull it down first rather than starting from a bare install — you want to test against their real plugin stack (Yoast, caching, Elementor, security), not a clean slate.
- [ ] Confirm which plugins are active on the live site and replicate locally so conflicts surface before deploy, not after.
- [ ] Drop the child theme into the local site's `wp-content/themes/`, activate, assign the custom template to the relevant Page, and fill ACF fields with real (and worst-case) content.

## 4. Pre-launch stress test

- [ ] Test every editable field with **worst-case content**: longest realistic headline, empty field, wrong image aspect ratio — before handoff, not after the client finds it.
- [ ] Confirm GSAP animations degrade gracefully with edited content (e.g., SplitText/character-stagger animations re-flow correctly if text length changes).
- [ ] Check the page with caching plugins active (if the client has one) — animations and enqueued scripts sometimes get stripped/reordered by aggressive minifiers.
- [ ] Run a quick Lighthouse pass — GSAP + custom pages can regress performance if scripts aren't deferred/conditionally loaded.
- [ ] Verify Yoast SEO panel appears and functions normally on the custom template (title tag, meta description, canonical, schema, sitemap inclusion) — confirms `wp_head()`/`wp_footer()` wiring is correct.
- [ ] Confirm the page doesn't pick up stray Elementor global CSS/widgets (inspect element for unexpected `elementor-*` classes/stylesheets) — see FAQ below.

## 5. Deploy

**Check hosting type first** — WordPress.org is the free open-source software you self-host anywhere (Bluehost/SiteGround/WP Engine/GoDaddy/a VPS); WordPress.com/Pressable are managed hosting companies. **Most client sites, including ones previously built with Elementor, are self-hosted WordPress.org** — this is the default assumption unless confirmed otherwise.

- [ ] **Self-hosted WordPress.org (default/common case):** Studio is used for local build/test only. Deploy is manual: zip or FTP/SFTP the child theme folder to `wp-content/themes/` on the client's actual host (FileZilla, Cyberduck, `scp`/`rsync`, or the host's file manager). Never edit live via the in-admin Theme File Editor — a syntax error there can white-screen the entire site, including the admin panel.
- [ ] **If client is on WordPress.com (paid plan) or Pressable (rare):** Studio Sync can push local → client site directly (staging first if available); it triggers a full backup automatically.
- [ ] After deploy: hard-refresh and check the live page against the local version pixel-for-pixel; check browser console for 404s on enqueued assets (path mismatches are the #1 post-deploy bug).
- [ ] Assign the new template to the correct Page via **Page Attributes → Template** in wp-admin on the live site.

## 6. Client handoff

- [ ] Tell the client explicitly which fields they can self-edit (via ACF, on the specific pages you built) and which parts require a developer (structure, animations, layout, new pages) — set this expectation before they go looking for an Elementor-style editor.
- [ ] Document per-page: template file name, which ACF fields exist, char limits, and any GSAP quirks (e.g., "don't paste text over 40 characters into Headline").
- [ ] Confirm the client's existing blog workflow (Gutenberg or Elementor, whichever they used before) is untouched and still works — see FAQ below.

---

## FAQ

**Can the client still write blog posts with Elementor/Gutenberg after I deploy custom pages — will it break anything?**
Yes, safely, in almost all cases. Your custom pages use a **Page Attributes → Template** override applied only to the specific `Page` entries you build (Home, Contact, etc.). Blog posts are a different post type (`Post`), rendered through the theme's own post template or Elementor's own template — never through your custom `page-*.php` files. The two don't intersect structurally. The one thing to check post-launch: some page builders inject **global CSS** (Site Settings → global colors/fonts/global widgets) sitewide, which could bleed into your custom pages' styling. Verify this once in step 4 (inspect element on your custom page, look for unexpected Elementor classes/stylesheets) — if it's clean, the client can publish blog posts freely with zero coordination with you going forward.

**Do I need to rebuild everything as PHP from scratch each time?**
No — step 1 (build in Claude Code as static HTML/CSS/JS) stays exactly like normal page-building work in this repo. Step 2 is a fairly mechanical port: wrap the finished markup in a PHP template file, swap hardcoded client-editable text/images for `get_field()` calls, and enqueue the same CSS/JS files via `wp_enqueue_script`/`style` instead of `<link>`/`<script>` tags.

---

## Why this stack (for future reference)

- **Why not Elementor/page builders:** bloated CSS/JS, animation conflicts, fights for control over markup GSAP needs to hook into directly.
- **Why child theme + custom templates over a custom Gutenberg block:** far less dev overhead for one-off landing/marketing pages; a custom block is only worth it if the client needs true drag-and-drop-level self-editing, which isn't the goal here.
- **Why ACF over raw post content:** constrains what the client can touch to specific, safe fields instead of full markup — this is what makes "client can edit text" safe rather than risky, and removes you as a bottleneck for routine copy changes.
- **Why WordPress Studio over building straight on the live site:** catches WP-specific bugs (enqueue conflicts, template registration, plugin interactions) in a disposable local sandbox before the client ever sees a broken page.
- **Why FTP/SFTP as the default deploy path (not Studio Sync):** most client sites are self-hosted WordPress.org on arbitrary hosts, where Studio Sync (WordPress.com/Pressable-only) isn't available.

## Sources
- [WordPress Studio Docs](https://developer.wordpress.com/docs/developer-tools/studio/)
- [Studio Sync — Connect Local and Production/Staging](https://developer.wordpress.com/docs/developer-tools/studio/sync/)
- [Child Themes – Theme Handbook (WordPress.org)](https://developer.wordpress.org/themes/advanced-topics/child-themes/)
- [ACF: Register Fields via PHP](https://www.advancedcustomfields.com/resources/register-fields-via-php/)
- [ACF: Local JSON](https://www.advancedcustomfields.com/resources/local-json/)
