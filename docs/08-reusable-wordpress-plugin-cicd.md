# Reusable WordPress plugin CI/CD

Use this pattern when custom WordPress pages live in a plugin and the site's
editable content lives in the WordPress database. A deployment replaces plugin
files only; it must not deploy the database or `wp-content/uploads`.

## How this repository deploys

1. Develop in `wp-plugin/ranked-international/` and test with the isolated
   `@wordpress/env` site (`npm run wp:start`, then `npm run wp:test`).
2. Commit and push the source to `main`. This does **not** deploy production.
3. In GitHub, manually run **Actions → Publish WordPress plugin → Run workflow**.
4. The `verify` job installs Node 22 dependencies and Playwright Chromium,
   starts the disposable WordPress environment, runs smoke/browser tests, and
   stops it even if a test fails.
5. Only after verification passes, `publish` changes both plugin version
   declarations to `1.<github-run-number>.0`, lints every PHP file, creates a
   standalone Git repository from the plugin directory, and force-pushes it to
   the `plugin-deploy` branch using GitHub's built-in `GITHUB_TOKEN`.
6. The final step calls the secret `GITUPDATER_WEBHOOK_URL`. Git Updater on the
   WordPress site downloads `plugin-deploy` and installs it immediately. If the
   secret is absent, publishing succeeds but the site must be updated manually.

The plugin header connects Git Updater to the release branch:

```php
 * GitHub Plugin URI: OWNER/REPOSITORY
 * Primary Branch: plugin-deploy
```

The workflow needs `permissions: contents: write`. No production SSH, SFTP,
database, or WordPress admin credentials are stored in GitHub.

## One-time setup for another project

- Put all deployable code in one plugin folder with one main plugin PHP file.
- Keep user content in posts/options/ACF, and make seeders idempotent with a
  one-time option or explicit migration version.
- Add local automated tests and stable npm scripts for start, test, and stop.
- Copy `.github/workflows/publish-wp-plugin.yml`; replace the plugin path, main
  PHP filename, version constant, test commands, and desired version format.
- Add the GitHub Plugin URI and Primary Branch headers shown above.
- Install and activate Git Updater on WordPress. Public repositories need no
  GitHub token; private repositories require Git Updater authentication.
- In Git Updater's **Remote Management** screen, copy its update URL and save
  the complete URL as the GitHub Actions secret `GITUPDATER_WEBHOOK_URL`.
- Run the workflow once on staging. Confirm the installed version changed,
  pages render, forms work, and database content/media remain intact.
- Keep `workflow_dispatch` for client production sites. Use an automatic
  `push` trigger only when automatic production releases are genuinely wanted.

Do not commit the webhook URL: it contains the site's remote-management key.
Changing an ACF field name/key is a data migration, not an ordinary file deploy.
Force-pushing the generated release branch is intentional; `main` remains the
source of truth and `plugin-deploy` is only a clean installable artifact.

## Prompt for an LLM in a new project

```text
Set up this WordPress project with the same plugin-release CI/CD pattern:

- Inspect the repository first; identify the deployable plugin directory,
  main plugin PHP file, version header/constant, and existing test commands.
- Create an isolated local WordPress test command if one does not exist.
- Add a manually triggered GitHub Actions workflow with least-privilege
  `contents: write` permission.
- In a verify job, install dependencies, start local WordPress, run all tests,
  and always stop the environment.
- Make publishing depend on verification. Stamp a unique version in both the
  WordPress plugin header and cache-busting version constant, PHP-lint the
  entire plugin, then publish only the plugin folder as the root of a
  force-replaced `plugin-deploy` branch using `GITHUB_TOKEN`.
- Call `GITUPDATER_WEBHOOK_URL` after publishing, but allow the workflow to
  finish with a clear message when that optional secret is unset.
- Add `GitHub Plugin URI: OWNER/REPO` and `Primary Branch: plugin-deploy` to
  the plugin header.
- Never deploy/import the database, uploads, local backups, `.env` files, or
  credentials. Do not alter user content. Flag non-idempotent activation code
  and ACF field-name changes as migration risks.
- Document exact GitHub and WordPress/Git Updater one-time setup plus staging
  verification and rollback steps.

Adapt names and commands to this repository; do not blindly copy placeholders.
Show me the resulting diff and run every safe local validation available.
```

Rollback is performed by re-running a known-good source revision (which creates
a new, higher plugin version) or by restoring a known-good plugin zip. Avoid
rewinding only `plugin-deploy`: WordPress may ignore a lower version number.
