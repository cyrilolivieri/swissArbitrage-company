---
title: 'STORY-001-05 — Section best-sellers / nouveautés'
type: 'feature'
created: '2026-09-17'
status: 'done'
review_loop_iteration: 0
context:
  - '03-spec/SPEC.md'
  - '_bmad-output/implementation-artifacts/epic-1-context.md'
  - '_bmad-output/implementation-artifacts/stories/STORY-001-01-creer-le-theme-enfant-compactbox.md'
  - '_bmad-output/implementation-artifacts/stories/STORY-001-02-header-nomad-like-navigation-langue-panier.md'
  - '_bmad-output/implementation-artifacts/stories/STORY-001-03-hero-pleine-largeur-homepage.md'
  - '_bmad-output/implementation-artifacts/stories/STORY-001-04-grids-de-collections-par-famille.md'
  - '00-nomad-redesign.md'
---

<frozen-after-approval reason="human-owned intent — do not modify unless human renegotiates">

## Intent

**Problem:** The CompactBox homepage currently shows a hero and a collection grid, but lacks a product-focused section to surface curated items and drive visitors toward purchase.

**Approach:** Add a "Best-sellers / New arrivals" carousel section below the collection grid. Render it from the child theme using a template part hooked to `astra_content_before` on the homepage only, and query the eight newest published products so real catalog data appears as soon as products exist.

## Boundaries & Constraints

**Always:**
- Create and edit files only inside `wp-content/themes/compactbox/`.
- Use Astra hook `astra_content_before` and child template parts; never edit the parent theme.
- Use `compactbox_` function prefixes, simplified PSR-12 PHP, and BEM-like class names (e.g. `cb-products`, `cb-products__carousel`, `cb-product-card`).
- Enqueue CSS/JS via `wp_enqueue_scripts` with `filemtime()`-based versions; extend existing `compactbox-style` / `compactbox-script` handles.
- Query real WooCommerce products with `WP_Query` / `wc_get_products()`; do not generate fake products, reviews, or sales data.
- Keep the section working when zero products exist (empty state with factual placeholder text).
- Make cards accessible with proper alt text, focus-visible states, and keyboard-friendly links.
- Respect `prefers-reduced-motion`.

**Never:**
- Modify Astra parent files or any WordPress core/plugin files.
- Add heavy JS frameworks or jQuery plugins; use vanilla JS only.
- Generate real products, fake reviews, or live payment configuration.
- Render more than eight product cards in this section.
- Hardcode final brand copy beyond factual section titles and empty-state copy.
- Embed credentials or third-party API calls.

## I/O & Edge-Case Matrix

| Scenario | Input / State | Expected Output / Behavior | Error Handling |
|----------|--------------|---------------------------|----------------|
| HAPPY_PATH | Homepage loads, WooCommerce active, ≥1 published product | Carousel section renders with up to 8 newest product cards (image, name, price, link) | N/A |
| NO_PRODUCTS | WooCommerce active but no published products | Section renders with a factual empty-state message and no broken markup | N/A |
| NO_WOOCOMMERCE | WooCommerce plugin absent | Section does not render; no fatal error or broken query | Guarded by `class_exists('WooCommerce')` |
| MOBILE_VIEW | Viewport < 768 px | Cards are horizontally scrollable or stacked, remain readable and tappable | N/A |
| DESKTOP_VIEW | Viewport ≥ 1024 px | Up to 4 cards visible at once, remainder reachable via previous/next buttons or horizontal scroll | N/A |
| REDUCED_MOTION | `prefers-reduced-motion: reduce` | No animated slide transition; buttons still scroll the container smoothly | N/A |
| NOT_HOMEPAGE | Single post or archive viewed | Carousel does not render outside the homepage | N/A |
| MISSING_IMAGE | A product has no featured image | A neutral placeholder background keeps the card visually intact; no broken-image icon visible | N/A |

</frozen-after-approval>

## Code Map

- `wp-content/themes/compactbox/style.css` — extend with product carousel variables, full-width `.cb-products` wrapper, `.cb-products__track` horizontal scroll container, `.cb-product-card` styling, previous/next buttons, empty state, responsive widths, and reduced-motion guard.
- `wp-content/themes/compactbox/assets/js/compactbox.js` — add a small `compactboxProductCarousel()` IIFE that wires previous/next buttons to scroll the track by one viewport width; keep it vanilla and degrade gracefully if buttons are absent.
- `wp-content/themes/compactbox/functions.php` — add `compactbox_homepage_products()` hooked to `astra_content_before` at priority 20 so it renders after collections (priority 15) on the homepage only; guard by `is_front_page()` and `class_exists('WooCommerce')`.
- `wp-content/themes/compactbox/template-parts/products.php` — query up to 8 published products ordered by date descending; render each card with product image (or placeholder fallback), name, price, and link; include an empty-state block when no products exist.
- `wp-content/themes/compactbox/README.md` — update file layout and document the products carousel hook, placeholder behavior, and how to adjust the query.

## Tasks & Acceptance

**Execution:**
- [x] `style.css` -- add product carousel CSS variables, full-width `.cb-products`, `.cb-products__track` horizontal scroll snap container, `.cb-product-card`, previous/next `.cb-products__nav`, empty-state `.cb-products__empty`, responsive widths, focus-visible states, and reduced-motion guard -- establishes the visual shell and carousel behavior.
- [x] `template-parts/products.php` -- create semantic product section with `h2`, query up to 8 newest published products via `wc_get_products()` or `WP_Query`, render `article.cb-product-card` for each with image fallback, name, price, and link; include a factual empty-state message when no products exist -- content anchor for the homepage.
- [x] `functions.php` -- add `compactbox_homepage_products()` hooked to `astra_content_before` with priority 20 and guarded by `is_front_page()` and `class_exists('WooCommerce')`; wrap in `function_exists()` and add the action only when the function is defined -- wires the carousel below the collection grid without touching the parent theme.
- [x] `assets/js/compactbox.js` -- add `compactboxProductCarousel()` IIFE: locate `.cb-products__track` and prev/next buttons, scroll the track by the track client width on click, support keyboard activation, and disable animated transitions under `prefers-reduced-motion` -- makes the carousel interactive without heavy frameworks.
- [x] `README.md` -- update folder layout, document the products template part, hook priority (20), query behavior, placeholder image fallback, and how to change product count/order -- continuity for future stories.

**Acceptance Criteria:**
- Given Astra parent is installed, WooCommerce is active, and the child theme is active, when the homepage front-end loads, then a `section.cb-products` element is rendered below the collection grid with up to 8 `article.cb-product-card` elements.
- Given WooCommerce is not active, when the homepage loads, then the products section is not rendered and no PHP fatal error occurs.
- Given WooCommerce is active but no published products exist, when the homepage loads, then the section renders with a factual empty-state message and no broken query output.
- Given the viewport width is less than 768 px, when the homepage loads, then the product track is horizontally scrollable or stacked, cards remain readable, and no horizontal page overflow occurs.
- Given the viewport width is at least 1024 px, when the homepage loads, then up to 4 cards are visible at once and the previous/next controls can scroll through the remaining items.
- Given a product has no featured image, when its card renders, then a neutral background placeholder is shown and no broken-image icon is visible.
- Given `prefers-reduced-motion: reduce`, when the carousel controls are used, then no animated slide transition runs.
- Given a single post or archive page is loaded, when the page renders, then the product carousel does not appear outside the homepage.
- Given the repository is inspected, when Astra parent files are compared before and after this story, then they remain unchanged.

## Spec Change Log

## Review Triage Log

## Design Notes

The product carousel intentionally follows the collection grid and reuses the same Astra hook (`astra_content_before`) with a higher priority (20 vs. collections at 15 vs. hero at 10) so the visual order on the homepage is hero → collections → products. The query uses `wc_get_products()` with `status=publish`, `limit=8`, and `orderby=date` / `order=DESC` so the newest real products appear first. If `wc_get_products()` is unavailable, the section is skipped entirely. Each card is a self-contained `article` with a link wrapper. Product data is dynamic; no fake inventory or reviews are generated. Placeholder image handling uses a CSS background on the media container so missing images never show a broken icon. The carousel JavaScript is minimal and degrades gracefully: on desktop it scrolls by one viewport width of the track; on mobile the same track remains touch-scrollable even if JS fails or buttons are hidden.

## Verification

**Commands:**
- `php -l wp-content/themes/compactbox/functions.php` -- expected: `No syntax errors detected`
- `ls -la wp-content/themes/compactbox/template-parts/` -- expected: `products.php` exists
- `php -r "echo extension_loaded('gd') ? 'gd ok' : 'gd missing';"` -- expected: `gd ok` (not required for this story, but available)

**Manual checks (if no CLI):**
- Open `template-parts/products.php` and confirm it includes `section.cb-products`, an `h2`, a query loop guarded by WooCommerce existence, product cards with image/name/price/link, and an empty-state block.
- Open `functions.php` and verify `compactbox_homepage_products()` is hooked to `astra_content_before` with priority 20 and guarded by `is_front_page()` and `class_exists('WooCommerce')`.
- Open `style.css` and confirm `.cb-products__track` uses horizontal scroll with snap points and a reduced-motion media query exists.
- Open `assets/js/compactbox.js` and confirm the carousel function listens for click events on prev/next buttons and scrolls the track.
- Open `README.md` and confirm the products section, hook priority, and query behavior are documented.

## Suggested Review Order

**Products hook and front-page guard**

- Entry point: products carousel is wired to `astra_content_before` with priority 20, only renders on the homepage, and only when WooCommerce is active.
  [`functions.php`](../../../wp-content/themes/compactbox/functions.php)

**Products markup and empty state**

- Semantic product section with heading, dynamic product cards, and empty-state fallback.
  [`template-parts/products.php`](../../../wp-content/themes/compactbox/template-parts/products.php)

**Carousel CSS and reduced-motion guard**

- Horizontal scroll track, product cards, previous/next controls, and responsive sizing.
  [`style.css`](../../../wp-content/themes/compactbox/style.css)

**Vanilla JS carousel controls**

- Small IIFE wires prev/next buttons to scroll the track; degrades gracefully.
  [`assets/js/compactbox.js`](../../../wp-content/themes/compactbox/assets/js/compactbox.js)

**Documentation**

- README documents the products hook, query behavior, placeholder fallback, and customization notes.
  [`README.md`](../../../wp-content/themes/compactbox/README.md)
