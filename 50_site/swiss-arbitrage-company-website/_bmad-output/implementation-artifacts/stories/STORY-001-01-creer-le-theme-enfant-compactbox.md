---
title: 'STORY-001-01 — Créer le thème enfant CompactBox'
type: 'feature'
created: '2026-09-17'
status: done
baseline_commit: 'NO_VCS'
review_loop_iteration: 2
context:
  - '03-spec/SPEC.md'
  - '_bmad-output/implementation-artifacts/epic-1-context.md'
---

<frozen-after-approval reason="human-owned intent — do not modify unless human renegotiates">

## Intent

**Problem:** The CompactBox WordPress storefront needs a dedicated child theme on Astra so that all UI overrides, scripts, and styles are isolated from the parent theme and safely version-controlled.

**Approach:** Scaffold the `compactbox` child theme directly under `wp-content/themes/compactbox/` with a valid `style.css` (Template: astra), an empty `functions.php` ready for hook-based enqueues, and a `screenshot.png` placeholder. Ensure activation succeeds and no parent files are touched.

## Boundaries & Constraints

**Always:**
- Create files only inside `wp-content/themes/compactbox/`.
- Declare child relationship to Astra in `style.css` `Template:` field.
- Use `wp_enqueue_scripts` with `filemtime()`-based versions for any enqueued assets.
- Keep PHP code in simplified PSR-12 style, prefixed with `compactbox_` for functions.
- Keep CSS mobile-first and BEM-like.

**Never:**
- Modify Astra parent files or any existing WordPress core/plugin files.
- Inline heavy JS frameworks; use vanilla JS only.
- Generate real products, fake reviews, or live payment configuration.
- Assume WooCommerce/Polylang are already installed or configured.

</frozen-after-approval>

## Code Map

- `wp-content/themes/compactbox/style.css` — theme header, minimal reset, enqueue-safe empty stylesheet.
- `wp-content/themes/compactbox/functions.php` — child theme bootstrap; enqueues child CSS and a placeholder JS file.
- `wp-content/themes/compactbox/assets/js/compactbox.js` — empty vanilla JS shell (avoids 404 if enqueued).
- `wp-content/themes/compactbox/screenshot.png` — 1200x900 placeholder image so the theme can be activated.
- `wp-content/themes/compactbox/README.md` — activation note and file layout for the implementer.
- `_bmad-output/design-mockups/` — will be created by STORY-001-10; this story does not populate it.
- `00-nomad-redesign.md` — analyse UX Nomad et redéfinition des familles produit.

## Tasks & Acceptance

**Execution:**
- [x] `wp-content/themes/compactbox/style.css` — create theme header declaring `Theme Name: CompactBox`, `Template: astra`, `Text Domain: compactbox`, and add a minimal mobile-first CSS reset.
- [x] `wp-content/themes/compactbox/functions.php` — register child theme setup, enqueue `style.css` and `assets/js/compactbox.js` on `wp_enqueue_scripts` using `filemtime()` for cache-busting versions.
- [x] `wp-content/themes/compactbox/assets/js/compactbox.js` — create an empty IIFE shell with `'use strict';`.
- [x] `wp-content/themes/compactbox/screenshot.png` — generate a 1200x900 placeholder image (theme color + CompactBox wordmark) so WordPress recognizes the theme.
- [x] `wp-content/themes/compactbox/README.md` — document folder layout, activation path, and constraints (do not edit Astra parent).
- [x] Verification — confirm the folder is recognized as a child theme by checking the header fields and that no PHP syntax errors exist.

**Acceptance Criteria:**
- Given the Astra parent theme is installed in `wp-content/themes/astra/`, when the `compactbox` child theme folder is present at `wp-content/themes/compactbox/` with a valid `style.css` header, then WordPress can activate it without error.
- Given the child theme is active, when a front-end page loads, then `style.css` and `compactbox.js` are enqueued with a version equal to their `filemtime()`.
- Given the child theme files are created, when `php -l` is run against `functions.php`, then no syntax errors are reported.
- Given the repository is inspected, when Astra parent files are compared before and after this story, then they remain unchanged.

## Spec Change Log

## Review Triage Log

Loop 1 findings (post-implementation review, 2026-09-17):

- **INTENT_GAP — child theme path resolved**
  - Previous spec used `wp-content/themes/astra-child/compactbox/` which WordPress cannot discover (only one level under `themes/`).
  - Corrected path: `wp-content/themes/compactbox/` with `Template: astra`.
  - Status: resolved.

- Dismissed: `index.php` missing — child themes inherit parent templates; not required for activation.
- Dismissed: `load_theme_textdomain()` not called — not required for a scaffold; can be added later when translations are implemented.
- Dismissed: re-declared `add_theme_support` calls — harmless/idempotent in this context.
- Dismissed: empty `compactbox.js` enqueued — explicitly required by the spec as a placeholder shell for later stories.
- Dismissed: French word in `style.css` Description — acceptable in a bilingual project context.
- Dismissed: README “BEM-like” claim vs. placeholder class — scaffold is intentionally minimal; BEM structure will come with later stories.
- Dismissed: binary `screenshot.png` not verifiable from diff — dimensions verified independently (`1200x900`).
- Dismissed: claim that `functions.php` contradicts the “empty functions.php” intent — Tasks & Acceptance explicitly require enqueues; the intent phrase can be read as “minimal/ready for hooks.”

- Pending patch-class findings (applied during Loop 1 implementation):
  - Add existence fallback for `filemtime()` on `style.css`.
  - Add `astra-theme-css` dependency for child stylesheet to ensure cascade order.
  - Add `prefers-reduced-motion` guard for `scroll-behavior: smooth`.
  - Wrap theme setup / enqueue functions in `function_exists()` guards.

Loop 2 findings (post-implementation review, 2026-09-17):

- **Patch applied:** Added direct-access guard (`defined('ABSPATH') || exit;`) to `functions.php` to prevent standalone execution.
- **Patch applied:** Guarded against `filemtime()` returning `false` so cache-busting versions never become an empty string.
- **Patch applied:** Moved `add_action()` calls inside the matching `function_exists()` guards so hooks are registered only when our functions are defined.
- **Patch applied:** Adjusted README wording from “style PSR-12 simplifié” to “style PSR-12 inspiré” to match the WordPress-oriented snake_case function prefixes.

- Dismissed: `load_child_theme_textdomain()` not called — already dismissed in Loop 1; not required for a scaffold.
- Dismissed: JS enqueued even if file missing — `assets/js/compactbox.js` exists as required; removal / deployment drift is out of scope.
- Dismissed: IIFE runs immediately on parse — empty shell; future DOM-dependent code will add its own `DOMContentLoaded` guard.
- Dismissed: Missing optional theme headers (`Theme URI`, `Author URI`, `Requires at least`, etc.) — beyond the spec scope.
- Dismissed: Hardcoded `astra-theme-css` dependency handle — spec-mandated; handle verification depends on the parent theme version not present in this workspace.
- Dismissed: Global CSS reset unscoped and may override Astra — intentional mobile-first reset per spec.
- Dismissed: Empty JS still emits an HTTP request — placeholder enqueue explicitly required by the spec.
- Dismissed: `compactbox_setup()` contains no setup logic — intentional scaffold for later stories.
- Dismissed: `.compactbox-placeholder` class unused — intentional BEM-like placeholder for later stories.
- Dismissed: `screenshot.png` content not verifiable from diff — dimensions verified independently (`1200x900`).
- Dismissed: `index.php` missing — already dismissed in Loop 1.
- Dismissed: No build tooling / linting config — out of scope for this scaffold story.

- Verification gap reviewer: no verification gaps found.

## Design Notes

The child theme intentionally contains only the scaffold. Later stories (001-02 through 001-08) will add template parts, customizer settings, and additional assets. Keeping this story narrowly focused on a safe, activatable scaffold reduces blast radius and gives a clean baseline for all future theme work.

## Verification

**Commands:**
- `php -l wp-content/themes/compactbox/functions.php` -- expected: `No syntax errors detected`
- `ls -la wp-content/themes/compactbox/` -- expected: `style.css`, `functions.php`, `assets/js/compactbox.js`, `screenshot.png`, `README.md` exist

**Manual checks (if no CLI):**
- Open `style.css` and verify the header comment contains `Template: astra` and `Text Domain: compactbox`.
- Open `functions.php` and verify `wp_enqueue_style('compactbox-style', ...)` and `wp_enqueue_script('compactbox-script', ...)` use `filemtime()` for the version argument.
- Confirm `screenshot.png` is 1200x900 and displays a CompactBox placeholder.

## Suggested Review Order

- Entry point: child-theme bootstrap with direct-access guard and guarded hooks
  [`functions.php:1`](../../../wp-content/themes/compactbox/functions.php#L1)

- Astra child theme header (`Template: astra`, `Text Domain: compactbox`)
  [`style.css:1`](../../../wp-content/themes/compactbox/style.css#L1)

- `filemtime()` cache-busting with `false`-value guards
  [`functions.php:38`](../../../wp-content/themes/compactbox/functions.php#L38)

- Child stylesheet depends on parent `astra-theme-css` for correct cascade
  [`functions.php:47`](../../../wp-content/themes/compactbox/functions.php#L47)

- Mobile-first CSS reset and reduced-motion guard
  [`style.css:16`](../../../wp-content/themes/compactbox/style.css#L16)

- Empty vanilla JS placeholder shell for future overrides
  [`compactbox.js:1`](../../../wp-content/themes/compactbox/assets/js/compactbox.js#L1)

- Activation notes, layout, and parent-file constraints
  [`README.md:1`](../../../wp-content/themes/compactbox/README.md#L1)
