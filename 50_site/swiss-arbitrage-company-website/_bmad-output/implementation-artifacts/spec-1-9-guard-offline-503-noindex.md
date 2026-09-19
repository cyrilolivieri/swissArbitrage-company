---
title: 'STORY-001-09 — Guard offline 503 + noindex'
type: 'feature'
created: '2026-09-18'
status: 'in-progress'
baseline_commit: 'NO_VCS'
review_loop_iteration: 0
context:
  - '03-spec/SPEC.md'
  - '_bmad-output/implementation-artifacts/epic-1-context.md'
---

<frozen-after-approval reason="human-owned intent — do not modify unless human renegotiates">

## Intent

**Problem:** The CompactBox storefront is being built in public but must stay invisible to visitors and search engines until the operator gives the GO. There is currently no mechanism to put the front-end offline while keeping the admin reachable.

**Approach:** Create a minimal CompactBox Core plugin with an `OfflineGuard` that returns HTTP 503 plus a `noindex` directive for every front-end request while `compactbox_site_online` is not `'yes'`. Expose a toggle under **Settings > CompactBox** so the operator can switch the site online without editing code.

## Boundaries & Constraints

**Always:**
- Create and edit files only inside `wp-content/plugins/compactbox-core/`.
- Use namespace `CompactBox\Core`, simplified PSR-12 PHP, and `compactbox_` / `CompactBox\Core\` prefixes.
- Default `compactbox_site_online` to `'no'` on plugin activation.
- Run the guard on `template_redirect` at priority `1` so it intercepts before any theme template is chosen.
- Only block public front-end requests: leave `/wp-admin/`, `wp-login.php`, `admin-ajax.php`, REST API requests, and WP-CLI untouched.
- Return a real HTTP 503 status, an `X-Robots-Tag: noindex` response header, and a minimal HTML body containing `<meta name="robots" content="noindex">`.
- Make every user-facing string translatable with text domain `compactbox-core`.
- Provide a single admin settings field under **Settings > CompactBox** with a proper sanitize callback that stores only `'yes'` or `'no'`.

**Never:**
- Modify the `compactbox` child theme, Astra, WooCommerce, or WordPress core files.
- Implement the other CompactBox Core modules (revenue monitor, operator dashboard, order hooks) — those belong to EPIC-005.
- Use a third-party maintenance-mode plugin, theme template, or external redirect for the offline page.
- Allow front-end access while offline based on user role, login state, or a query string shortcut.

## I/O & Edge-Case Matrix

| Scenario | Input / State | Expected Output / Behavior | Error Handling |
|----------|--------------|---------------------------|----------------|
| HAPPY_PATH_OFFLINE | `compactbox_site_online` is `'no'` or unset, front-end URL requested | HTTP 503 response with `X-Robots-Tag: noindex` and HTML body containing `<meta name="robots" content="noindex">` | N/A |
| ONLINE_ENABLED | `compactbox_site_online` is `'yes'`, front-end URL requested | Normal theme/page rendered, no 503/noindex from the guard | N/A |
| ADMIN_ACCESS | `compactbox_site_online` is `'no'`, request to `/wp-admin/` or `wp-login.php` | Admin/login page loads normally, no 503 | `is_admin()` / login URL guard |
| AJAX_REST | `compactbox_site_online` is `'no'`, request to `admin-ajax.php` or REST endpoint | Request handled normally | `wp_doing_ajax()` / REST-request guard |
| FIRST_ACTIVATION | Plugin activated for the first time | Option `compactbox_site_online` is created with value `'no'` | `add_option()` default |
| MISSING_OPTION | Option does not exist and plugin has not re-activated | Treat as offline (`'no'`) | Fallback in guard read |

</frozen-after-approval>

## Code Map

- `wp-content/plugins/compactbox-core/compactbox-core.php` — plugin header, activation hook that sets `compactbox_site_online` to `'no'`, and the loader that instantiates `OfflineGuard` and `AdminSettings`.
- `wp-content/plugins/compactbox-core/includes/class-offline-guard.php` — `CompactBox\Core\OfflineGuard` class. Contains the `template_redirect` callback, the `should_block()` predicate, and the `serve_503()` renderer.
- `wp-content/plugins/compactbox-core/includes/class-admin-settings.php` — `CompactBox\Core\AdminSettings` class. Registers the **Settings > CompactBox** submenu, the `compactbox` option group, and the `compactbox_site_online` checkbox field.
- `wp-content/plugins/compactbox-core/README.md` — file layout, activation steps, option key, and how to switch the site online.

## Tasks & Acceptance

**Execution:**
- [x] `wp-content/plugins/compactbox-core/compactbox-core.php` — create the plugin header, activation hook with `add_option('compactbox_site_online', 'no')`, and loader that calls `OfflineGuard::init()` and `AdminSettings::init()` inside `if (defined('ABSPATH'))` guards.
- [x] `wp-content/plugins/compactbox-core/includes/class-offline-guard.php` — implement `OfflineGuard` with `add_action('template_redirect', [$this, 'maybe_block'], 1)`. `maybe_block()` reads `get_option('compactbox_site_online', 'no')`, returns early if online or if the request is admin/login/ajax/rest/wp-cli. Otherwise calls `serve_503()`, which sends `status_header(503)`, `header('X-Robots-Tag: noindex')`, and outputs a minimal translatable HTML page with the meta noindex tag, then exits.
- [x] `wp-content/plugins/compactbox-core/includes/class-admin-settings.php` — implement `AdminSettings` with `add_options_page('CompactBox', 'CompactBox', 'manage_options', 'compactbox', [$this, 'render_page'])`, `register_setting('compactbox', 'compactbox_site_online', [$this, 'sanitize_online'])`, and a checkbox field that stores `'yes'` when checked and `'no'` when unchecked.
- [x] `wp-content/plugins/compactbox-core/README.md` — document the plugin purpose, file layout, activation, the `compactbox_site_online` option, and how to toggle it from **Settings > CompactBox**.

**Acceptance Criteria:**
- Given `compactbox_site_online` is `'no'` or unset, when any front-end URL is requested, then the server responds with HTTP 503 and the response body contains `<meta name="robots" content="noindex">`.
- Given `compactbox_site_online` is `'yes'`, when a front-end URL is requested, then the site renders normally without a 503 or noindex directive from the guard.
- Given the site is offline, when `/wp-admin/` or `wp-login.php` is requested, then the admin/login page is served normally.
- Given the site is offline, when `admin-ajax.php` or a REST API endpoint is requested, then the request is handled normally.
- Given the plugin is activated, when the option does not already exist, then `compactbox_site_online` is created with value `'no'`.
- Given an operator visits **Settings > CompactBox**, when they check "Site online" and save, then `compactbox_site_online` becomes `'yes'` and the front-end becomes accessible.

## Spec Change Log

## Review Triage Log

## Design Notes

The guard is intentionally implemented as a plugin rather than theme code so it stays active regardless of which theme is selected later and can be reused when CompactBox Core is fully built in EPIC-005. The `template_redirect` hook at priority `1` runs before WooCommerce or Astra choose a template, making the 503 response cheap and reliable.

The offline HTML is self-contained: it does not load the theme header/footer, so no extra styles, scripts, or WooCommerce code run while the site is offline. The `X-Robots-Tag` header plus a meta tag in the body ensures the page is not indexed even if a crawler ignores one of the two signals.

Admin access is determined by `is_admin()`, `wp_doing_ajax()`, and `defined('REST_REQUEST')` checks rather than by user capability, keeping the rule simple: public front-end is offline, everything else stays online.

## Verification

**Commands:**
- `php -l wp-content/plugins/compactbox-core/compactbox-core.php` — expected: `No syntax errors detected`
- `php -l wp-content/plugins/compactbox-core/includes/class-offline-guard.php` — expected: `No syntax errors detected`
- `php -l wp-content/plugins/compactbox-core/includes/class-admin-settings.php` — expected: `No syntax errors detected`

**Manual checks (if no CLI):**
- Open `class-offline-guard.php` and confirm `template_redirect` calls `status_header(503)`, sends `X-Robots-Tag: noindex`, and exits without loading the theme.
- Open `class-admin-settings.php` and confirm the settings page uses `register_setting('compactbox', 'compactbox_site_online', ...)` with a sanitize callback returning only `'yes'` or `'no'`.
- Open `compactbox-core.php` and confirm activation sets `compactbox_site_online` to `'no'` and the loader only runs when `ABSPATH` is defined.
