---
title: 'STORY-001-04 — Grids de collections par famille'
type: 'feature'
created: '2026-09-17'
status: 'in-progress'
baseline_commit: 'NO_VCS'
review_loop_iteration: 0
context:
  - '03-spec/SPEC.md'
  - '_bmad-output/implementation-artifacts/epic-1-context.md'
  - '_bmad-output/implementation-artifacts/stories/STORY-001-01-creer-le-theme-enfant-compactbox.md'
  - '_bmad-output/implementation-artifacts/stories/STORY-001-02-header-nomad-like-navigation-langue-panier.md'
  - '_bmad-output/implementation-artifacts/stories/STORY-001-03-hero-pleine-largeur-homepage.md'
  - '00-nomad-redesign.md'
---

<frozen-after-approval reason="human-owned intent — do not modify unless human renegotiates">

## Intent

**Problem:** The CompactBox homepage needs a visual family grid so visitors can immediately discover the six MVP product families and jump to the corresponding shop sections.

**Approach:** Add a full-width "Shop by family" section below the hero. Render it via a child template part hooked to `astra_content_before` on the homepage, with six cards in a responsive grid (2 columns mobile, 3–4 desktop), each showing a placeholder image, family name, short description, and a link.

## Boundaries & Constraints

**Always:**
- Create and edit files only inside `wp-content/themes/compactbox/`.
- Use Astra hook `astra_content_before` and child template parts; never edit the parent theme.
- Use `compactbox_` function prefixes, simplified PSR-12 PHP, and BEM-like class names (e.g. `cb-collections`, `cb-collections__grid`, `cb-collection-card`).
- Enqueue CSS/JS via `wp_enqueue_scripts` with `filemtime()`-based versions; extend existing `compactbox-style` / `compactbox-script` handles.
- Keep copy factual and sober; no fake data, reviews, or products.
- Generate one placeholder image per family under `assets/images/collections/`.
- Make cards accessible with proper alt text and focus-visible states.
- Respect `prefers-reduced-motion`.

**Never:**
- Modify Astra parent files or any WordPress core/plugin files.
- Add heavy JS frameworks or jQuery plugins.
- Generate real products, fake reviews, or live payment configuration.
- Hardcode final brand copy beyond factual family names and short descriptions.
- Embed credentials or third-party API calls.

## I/O & Edge-Case Matrix

| Scenario | Input / State | Expected Output / Behavior | Error Handling |
|----------|--------------|---------------------------|----------------|
| HAPPY_PATH | Homepage loads with child theme active | Six collection cards render in a responsive grid, each with image, name, description, link | N/A |
| MOBILE_VIEW | Viewport < 768 px | Grid is 2 columns, cards stack cleanly, text readable, images scale | N/A |
| DESKTOP_VIEW | Viewport ≥ 1024 px | Grid is 4 columns for the first row (3 + 3 split on 1024 px if 4 does not fit) | N/A |
| MISSING_IMAGE | A family placeholder image is absent | Background color keeps the card visually intact; no broken-image icon visible | N/A |
| NO_WOOCOMMERCE | WooCommerce shop page is missing | Family links fall back to `home_url('/shop/')` or home URL | Graceful: no fatal error |
| REDUCED_MOTION | `prefers-reduced-motion: reduce` | No hover transitions or animations on cards | N/A |
| NOT_HOMEPAGE | Single post or archive viewed | Collection grid does not render outside the homepage | N/A |

</frozen-after-approval>

## Code Map

- `wp-content/themes/compactbox/style.css` — extend with collection grid variables, full-width `.cb-collections` wrapper, `.cb-collections__grid` using CSS Grid (2 columns mobile, 3 tablet, 4 desktop), `.cb-collection-card` styling, hover/focus states, and reduced-motion guard.
- `wp-content/themes/compactbox/functions.php` — add `compactbox_homepage_collections()` hooked to `astra_content_before`, guarded by `is_front_page()` and running after the hero so collections appear below it.
- `wp-content/themes/compactbox/template-parts/collections.php` — collection section markup: heading, 6 cards loop, each with image, name, description, link.
- `wp-content/themes/compactbox/assets/images/collections/` — six 800x600 placeholder images named `cables.png`, `hubs.png`, `stands.png`, `supports.png`, `adapters.png`, `organizers.png`.
- `wp-content/themes/compactbox/README.md` — update file layout and document the collections section, hook, placeholder image paths, and how to replace copy/images.

## Tasks & Acceptance

**Execution:**
- [x] `style.css` -- add collection grid CSS variables, full-width `.cb-collections`, `.cb-collections__grid` with 2/3/4 responsive columns, `.cb-collection-card`, image aspect-ratio, typography, hover/focus-visible, and reduced-motion guard -- establishes the visual shell and responsive behavior.
- [x] `assets/images/collections/*.png` -- generate six 800x600 placeholder images, one per family, with a neutral theme color and compact family label so cards render cleanly before final assets arrive.
- [x] `template-parts/collections.php` -- create semantic collection section with `h2`, six `article.cb-collection-card` elements, each containing an image (with `alt`), family name, short description, and link to shop fallback -- content and accessibility anchor for the homepage.
- [x] `functions.php` -- add `compactbox_homepage_collections()` hooked to `astra_content_before` after `compactbox_homepage_hero` (priority 15) and guarded by `is_front_page()`; wrap in `function_exists()` and add the action only when the function is defined -- wires the grid below the hero without touching the parent theme.
- [x] `README.md` -- update folder layout, document the collections template part, hook priority, placeholder image path, and how to swap copy/images -- continuity for future stories.

**Acceptance Criteria:**
- Given Astra parent is installed and the child theme is active, when the homepage front-end loads, then a `section.cb-collections` element is rendered below the hero with six `article.cb-collection-card` elements.
- Given the viewport width is less than 768 px, when the homepage loads, then the collection grid uses 2 columns, cards scale, and no horizontal overflow occurs.
- Given the viewport width is at least 1024 px, when the homepage loads, then the collection grid uses 4 columns (or 3 columns if the design caps at 3) and the cards remain aligned.
- Given a collection placeholder image is missing, when the homepage loads, then a solid background color keeps the card visually intact and no broken-image icon is visible.
- Given WooCommerce is not active or the shop page is missing, when the homepage loads, then each family link falls back to `home_url('/shop/')` or `home_url('/')` without throwing errors.
- Given `prefers-reduced-motion: reduce`, when the homepage loads, then no CSS transition/animation runs on the collection cards.
- Given a single post or archive page is loaded, when the page renders, then the collection grid does not appear outside the homepage.
- Given the repository is inspected, when Astra parent files are compared before and after this story, then they remain unchanged.

## Spec Change Log

## Review Triage Log

## Design Notes

The collection grid intentionally follows the hero and reuses the same Astra hook (`astra_content_before`) with a higher priority (15 vs. the default 10 used by the hero) so the visual order is hero → collections on the homepage. Each card is a self-contained `article` with a link wrapper or a standalone link inside. Family data is hardcoded in the template part as a small array of associative arrays — this keeps the story scoped and avoids creating a customizer panel or admin UI. The six families match the MVP list defined in `00-nomad-redesign.md`: Câbles USB-C, Hubs USB-C, Stands laptop, Supports téléphone/tablette, Adaptateurs USB-C, Organisateurs câbles / accessoires bureau. Placeholder images are generated at 800x600 (4:3) so they scale consistently inside a `aspect-ratio: 4/3` container.

## Verification

**Commands:**
- `php -l wp-content/themes/compactbox/functions.php` -- expected: `No syntax errors detected`
- `ls -la wp-content/themes/compactbox/template-parts/` -- expected: `collections.php` exists
- `ls -la wp-content/themes/compactbox/assets/images/collections/` -- expected: six `.png` files exist
- `php -r "echo extension_loaded('gd') ? 'gd ok' : 'gd missing';"` -- expected: `gd ok` (so the placeholder images can be generated)

**Manual checks (if no CLI):**
- Open `template-parts/collections.php` and confirm it includes `section.cb-collections`, an `h2`, six `article.cb-collection-card` elements, each with an image, name, description, and link.
- Open `functions.php` and verify `compactbox_homepage_collections()` is hooked to `astra_content_before` with priority 15 and guarded by `is_front_page()`.
- Open `style.css` and confirm `.cb-collections__grid` has responsive columns (2/3/4) and a reduced-motion media query exists.
- Confirm `assets/images/collections/*.png` is present and displays family placeholder labels.

## Suggested Review Order

**Collections hook and front-page guard**

- Entry point: collections are wired to `astra_content_before` with priority 15 and only render on the homepage.
  [`functions.php`](../../../wp-content/themes/compactbox/functions.php)

**Collections markup and accessibility**

- Semantic collection section with heading and six family cards.
  [`template-parts/collections.php`](../../../wp-content/themes/compactbox/template-parts/collections.php)

**Responsive grid CSS and reduced-motion guard**

- Full-width collections with 2/3/4 column grid.
  [`style.css`](../../../wp-content/themes/compactbox/style.css)

**Placeholder image generation**

- Generated 800x600 placeholders for all six families.
  [`assets/images/collections/`](../../../wp-content/themes/compactbox/assets/images/collections/)

**Documentation**

- README documents the collections hook, replacement image path, and copy swap instructions.
  [`README.md`](../../../wp-content/themes/compactbox/README.md)
