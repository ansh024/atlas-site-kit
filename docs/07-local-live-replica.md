# Local live-site replica

`wp-data/` may contain an UpdraftPlus-style snapshot of the live site. It is
used only to reproduce the production rendering environment locally: the
database, active theme, page-builder plugins, uploads, and current site data.
It is deliberately ignored by Git.

## Restore it safely

1. Keep the complete backup set in `wp-data/`: database, themes, plugins,
   uploads, and uploads2.
2. Run `npm run wp:restore-live` from the repository root.
3. Open `http://localhost:8891/local-seo-services-offer/` and compare it with
   production. The WordPress login is `local-admin` / `password`.

The command starts the repository's disposable Docker `wp-env`, extracts the
backup to a temporary system directory, imports it into that Docker database,
and rewrites the Ranked International URLs to `localhost:8891`. It never
uploads data or connects to the production database.

## Local safety controls

- The command refuses any target other than `localhost:8891` or `127.0.0.1:8891`.
- A local-only must-use plugin blocks outgoing email and all non-local HTTP
  requests. This keeps stored production credentials, webhooks, analytics, and
  plugin update checks inert.
- Git Updater, Google Site Kit, Maintenance, UpdraftPlus, and Angie are
  deactivated after import. Elementor, UICore, the restored theme, WPForms, and
  other rendering dependencies remain available.
- The currently checked-out `ranked-international` plugin is activated over the
  backed-up `atlas-site-kit` copy so local code changes are visible immediately.

## Resetting

Use `npm run wp:reset` for a fresh fixture database, or `npm run wp:destroy` to
remove this repository's containers and volumes. Run `npm run wp:restore-live`
again whenever a live-faithful local replica is needed.
