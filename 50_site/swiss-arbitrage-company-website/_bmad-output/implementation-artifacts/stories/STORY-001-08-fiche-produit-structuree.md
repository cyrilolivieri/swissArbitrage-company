---
title: 'STORY-001-08 — Fiche produit structurée'
type: 'feature'
created: '2026-09-18'
status: 'done'
baseline_commit: 'NO_VCS'
review_loop_iteration: 0
context:
  - '03-spec/SPEC.md'
  - '_bmad-output/implementation-artifacts/epic-1-context.md'
  - '_bmad-output/implementation-artifacts/stories/STORY-001-01-creer-le-theme-enfant-compactbox.md'
  - '00-nomad-redesign.md'
---

<frozen-after-approval reason="human-owned intent — do not modify unless human renegotiates">

## Intent

**Problem:** The CompactBox storefront has a homepage scaffold (header, hero, collections, products, trust, footer) but no dedicated single-product layout. WooCommerce currently falls back to Astra's default single-product template, so the product page does not present the gallery, CHF price, shipping, structured sections, FAQ, and Schema.org Product JSON-LD in the clean Nomad-like layout required by the SPEC.

**Approach:** Add a child-theme WooCommerce template override for `woocommerce/single-product.php` plus targeted hooks and CSS so the product page renders a two-column desktop layout (gallery left, details right), keeps the add-to-cart CTA visible above the fold, and exposes collapsible sections for specs, compatibility, box contents, and FAQ. Inject valid Schema.org Product JSON-LD on singular product pages via `wp_head`, and keep the implementation safe when WooCommerce is inactive or when a product lacks images/price.

## Boundaries & Constraints

**Always:**
- Create and edit files only inside `wp-content/themes/compactbox/`.
- Use WooCommerce template overrides in `woocommerce/single-product.php` and child template parts; never edit the parent Astra theme or WooCommerce plugin files.
- Use `compactbox_` function prefixes, simplified PSR-12 PHP, and BEM-like class names (e.g., `cb-product`, `cb-product__gallery`, `cb-product__details`, `cb-product__section`).
- Enqueue CSS/JS via the existing `compactbox-style` / `compactbox-script` handles with `filemtime()`-based versions; add only product-specific CSS to `style.css`.
- Make all strings translatable via `__()` / `_e()` with text domain `compactbox`.
- Guard every product-specific hook with `is_singular('product')` and `class_exists('WooCommerce')` / `function_exists()` so the front-end stays fatal-error-free when WooCommerce is inactive.
- Render a neutral placeholder image when the product has no gallery image.
- Inject Schema.org Product JSON-LD only on singular product pages and only when the current product has a price.
- Respect `prefers-reduced-motion: reduce` for any interactive states.

**Never:**
- Modify Astra parent files or any WordPress/WooCommerce core/plugin files.
- Add heavy JS frameworks or jQuery plugins; use vanilla JS only.
- Generate fake reviews, fake stock levels, fake compatibility claims, or fabricated product copy.
- Render a hardcoded business name, legal address, or final product prose beyond placeholder status.
- Embed third-party tracking, credentials, or live payment configuration.
- Implement full multilingual wiring; reserve the language selector and translated slugs for EPIC-002.
- Add new product meta fields or admin UIs; this story only reads existing product data and meta already present in the database.

## I/O & Edge-Case Matrix

| Scenario | Input / State | Expected Output / Behavior | Error Handling |
|----------|--------------|---------------------------|----------------|
| HAPPY_PATH | WooCommerce active, single product page loads | Page wraps in `.cb-product`; gallery and details render in two-column desktop layout; title, price, shipping estimate, add-to-cart CTA, and structured sections (specs, compatibility, box contents, FAQ) are visible; valid JSON-LD injected in `<head>` | N/A |
| MOBILE_VIEW | Viewport < 768 px | Gallery and details stack vertically; CTA remains visible in the first viewport; sections remain readable | N/A |
| TABLET_VIEW | 768 px ≤ viewport < 1024 px | Gallery and details remain stacked or side-by-side, depending on design choice; layout remains readable and accessible | N/A |
| NO_IMAGE | Product has no gallery image | A neutral placeholder image is shown; no broken `<img>` markup is rendered | Placeholder asset |
| NO_WOOCOMMERCE | WooCommerce inactive | Child template override does not load; no fatal error; front-end falls back to parent/Astra behavior | `class_exists` / `function_exists` guards |
| NO_PRICE | Product has no price set | JSON-LD is skipped; price block shows WooCommerce placeholder price or is hidden according to WooCommerce default | Conditional JSON-LD |
| REDUCED_MOTION | `prefers-reduced-motion: reduce` | No transitions/animations on accordion toggles, focus, or hover states | CSS media query |
| NON_PRODUCT_PAGE | Page is not singular `product` | JSON-LD and product sections hooks do not run | `is_singular('product')` guard |
| ADMIN_PAGE | `/wp-admin/` page loaded | Product template override and JSON-LD hook do not run | `is_singular('product')` guard |

## Code Map

- `wp-content/themes/compactbox/style.css` — append product-page CSS variables and BEM-like classes for `.cb-product`, `.cb-product__layout`, `.cb-product__gallery`, `.cb-product__details`, `.cb-product__title`, `.cb-product__price`, `.cb-product__shipping`, `.cb-product__cta`, `.cb-product__section`, `.cb-product__accordion`, reduced-motion and focus-visible guards. Reuse existing neutral tokens (e.g., `--cb-products-*`, `--cb-collections-*`).
- `wp-content/themes/compactbox/functions.php` — add:
  - `compactbox_single_product_json_ld()` hooked to `wp_head` at priority 10, guarded by `is_singular('product')` and `function_exists('WC')`, outputs a single `<script type="application/ld+json">` block per product.
  - `compactbox_single_product_sections()` hooked to `woocommerce_single_product_summary` at priority 25 to render specs/compatibility/box/FAQ after the add-to-cart form, guarded by `is_singular('product')`.
  - A `compactbox_single_product_body_class()` helper (optional) to add `cb-product-page` to the body class for CSS scoping.
  Wrap all new functions in `function_exists()` guards and keep hooks inside the matching guards.
- `wp-content/themes/compactbox/woocommerce/single-product.php` — create the WooCommerce single-product template override. Structure: get_header(); start `.cb-product` wrapper; call `woocommerce_content()` inside a `.cb-product__layout` wrapper with `.cb-product__gallery` and `.cb-product__details` containers (or rely on `woocommerce_single_product_summary` hooks for the details column); end `.cb-product` wrapper; get_footer(). Keep parent Astra untouched.
- `wp-content/themes/compactbox/template-parts/product-sections.php` — reusable markup fragment that renders the four collapsible/expandable sections: Specs, Compatibility, Box contents, FAQ. Each section uses product meta (when available) or placeholder lists for fields not yet populated, and always calls `esc_html_e()` / `esc_html()` for output. The section container uses `.cb-product__section`.
- `wp-content/themes/compactbox/template-parts/product-json-ld.php` — (optional) isolate the JSON-LD generation into a template part to keep `functions.php` readable; only included when `is_singular('product')` and a valid global `$product` is present.
- `wp-content/themes/compactbox/README.md` — update the folder layout, document the WooCommerce override path, JSON-LD hook, structured sections, and how operators customize placeholder/spec content.

## Tasks & Acceptance

**Execution:**
- [x] `woocommerce/single-product.php` — create child-theme WooCommerce single-product template override that renders the standard WooCommerce product summary inside a `.cb-product` wrapper without altering Astra parent files.
- [x] `style.css` — append product-page CSS variables and classes for two-column responsive layout (gallery left / details right on desktop ≥ 1024 px, stacked on < 768 px), accordion-style sections, CTA visibility above the fold on mobile, focus-visible states, and reduced-motion guards.
- [x] `functions.php` — add `compactbox_single_product_json_ld()` on `wp_head` (Schema.org Product JSON-LD) and `compactbox_single_product_sections()` on `woocommerce_single_product_summary` at priority 31 (specs, compatibility, box contents, FAQ), plus `compactbox_single_product_shipping_estimate()` at priority 11; wrap in `function_exists()` and guard by `is_singular('product')` / WooCommerce availability. Optionally add `compactbox_single_product_body_class()` on `body_class`.
- [x] `template-parts/product-sections.php` — create the reusable markup fragment for the four structured sections; use placeholder content when product meta is empty, and make every string translatable.
- [x] `README.md` — update folder layout, document the WooCommerce override path, JSON-LD hook, structured sections, and how operators customize placeholder/spec content.

**Acceptance Criteria:**
- Given WooCommerce is active and the `compactbox` child theme is active, when a single product page loads, then the page is wrapped in `.cb-product` and renders a gallery area and a details area.
- Given the viewport width is at least 1024 px, when the page loads, then the gallery and details render side by side in a two-column layout and the "Add to cart" button is visible without scrolling.
- Given the viewport width is less than 768 px, when the page loads, then the gallery and details stack vertically, the CTA remains visible above the fold, and no horizontal overflow occurs.
- Given the product has no gallery image, when the page loads, then a neutral placeholder image is rendered and no broken image is visible.
- Given WooCommerce is inactive, when a front-end page loads, then no fatal error occurs and the child product override and product-specific hooks do not execute.
- Given the current page is a single product with a price, when the page loads, then a valid Schema.org Product JSON-LD script is injected in `<head>` containing `@context`, `@type: Product`, `name`, `image`, and `offers` with `price`, `priceCurrency: CHF`, `availability`, and `url`.
- Given the current page is not a single `product` post type, when the page loads, then no product JSON-LD is injected and no product sections hook runs.
- Given `prefers-reduced-motion: reduce`, when accordion toggles or focusable elements are hovered or focused, then no transitions or animations run.
- Given the repository is inspected, when Astra parent files are compared before and after this story, then they remain unchanged.

</frozen-after-approval>

## Spec Change Log

2026-09-18: Implementation review loop 1
- Raised `woocommerce_single_product_summary` priority for structured sections from 25 to 31 so sections render after the add-to-cart form (WC add-to-cart is priority 30).
- Added `compactbox_single_product_shipping_estimate()` hooked at priority 11 to render the shipping estimate line required by the I/O matrix.
- Strengthened `compactbox_single_product_json_ld()` guards with explicit `function_exists('WC') && class_exists('WooCommerce')` check.
- Replaced hardcoded `'CHF'` with `get_woocommerce_currency()` fallback.
- Sanitized JSON-LD `name` and `url` values with `esc_html()` and `esc_url()`.
- Added `file_exists()` check for the placeholder image used in JSON-LD.
- Passed the current `$product` to the section template part via `set_query_var('compactbox_current_product', $product)`.
- Added `function_exists('compactbox_get_product_section_items')` guard and explicit product loading in the template part.
- Hardened `compactbox_get_product_section_items()` to skip non-string meta values and apply `wp_unslash()`.
- Simplified accordion JS to rely on CSS `prefers-reduced-motion` instead of inline transition manipulation.
- Adjusted mobile CSS to move the entire `.summary` block above the gallery so the CTA stays above the fold without reordering individual flex items.
- Increased `.cb-product__accordion-panel` open max-height to 9999px to avoid clipping long FAQ content.
- Added gallery wrapper `min-height`/`aspect-ratio` and an offline notice style for the no-WooCommerce fallback.

## Review Triage Log

Loop 1 findings (post-implementation review, 2026-09-18):

- **PATCH — priority for sections** (edge-case-hunter)
  - Finding: sections were hooked at priority 25, which places them before the add-to-cart form (priority 30).
  - Verification: WooCommerce default single-product summary priorities put add-to-cart at 30.
  - Fix: changed priority to 31.
  - Status: resolved.

- **PATCH — missing shipping estimate element** (edge-case-hunter)
  - Finding: the shipping estimate required by the I/O matrix had no rendered element.
  - Fix: added `compactbox_single_product_shipping_estimate()` on `woocommerce_single_product_summary` at priority 11 with class `cb-product__shipping`.
  - Status: resolved.

- **PATCH — mobile CTA above the fold** (edge-case-hunter)
  - Finding: `order: -1` only moved the cart within the summary; a tall gallery could still push the summary below the first viewport.
  - Fix: on mobile, set `order: -1` on the entire `.summary.entry-summary` so the details block (including CTA) renders before the gallery.
  - Status: resolved.

- **PATCH — JSON-LD hardcoded CHF** (general reviewer)
  - Finding: currency was hardcoded.
  - Fix: use `get_woocommerce_currency()` with `'CHF'` fallback.
  - Status: resolved.

- **PATCH — JSON-LD unsanitized name/url** (general reviewer)
  - Finding: product name and URL were passed raw to `wp_json_encode()`.
  - Fix: wrapped `name` with `esc_html()` and `url` with `esc_url()` before schema construction.
  - Status: resolved.

- **PATCH — placeholder image 404 risk** (edge-case-hunter)
  - Finding: JSON-LD fell back to `hero-placeholder.png` without verifying file existence.
  - Fix: only include the placeholder URL when `file_exists()` returns true.
  - Status: resolved.

- **PATCH — accordion max-height clipping** (edge-case-hunter)
  - Finding: `max-height: 1000px` could clip long sections.
  - Fix: changed to `9999px`.
  - Status: resolved.

- **PATCH — reduced-motion inline transition manipulation** (general reviewer)
  - Finding: JS duplicated the CSS media query and only read the preference at page load.
  - Fix: removed `prefersReducedMotion` logic from the accordion; CSS `@media (prefers-reduced-motion: reduce)` now owns the disable.
  - Status: resolved.

- **PATCH — template part product dependency** (general reviewer)
  - Finding: template part relied on the global `$product` and did not guard `compactbox_get_product_section_items()`.
  - Fix: pass product via query var; guard function existence in the template part.
  - Status: resolved.

- **PATCH — non-string meta edge case** (general reviewer)
  - Finding: `compactbox_get_product_section_items()` could cast arrays/objects to strings.
  - Fix: return early if meta value is not a string; apply `wp_unslash()`.
  - Status: resolved.

- **PATCH — NO_WOOCOMMERCE empty main wrapper** (general reviewer)
  - Finding: when WooCommerce is inactive the override still loaded header/footer around an empty main tag.
  - Fix: render a translatable offline notice inside the wrapper.
  - Status: resolved.

- Dismissed: duplicate WooCommerce JSON-LD risk — out of scope; operator can disable WooCommerce structured data separately if desired.
- Dismissed: missing `sku`/`brand`/`mpn` in schema — beyond the acceptance criteria.
- Dismissed: focus trap in mobile header — pre-existing issue not introduced by this story.
- Dismissed: global script enqueue on every page — intentional per existing scaffold pattern.

## Design Notes

The product page follows the established child-theme pattern: a WooCommerce template override plus targeted WooCommerce/Astra hooks, CSS in `style.css`, and a template part for structured sections. The child override loads Astra's page shell via `get_header()` and `get_footer()`, then delegates the actual WooCommerce product rendering to `woocommerce_content()` inside a `.cb-product` wrapper. The gallery and summary are left in their default WooCommerce order; CSS flex/grid reorders them visually on desktop so the gallery sits left and the summary sits right.

The four structured sections are rendered after the add-to-cart form via `woocommerce_single_product_summary` at priority 31, after the default add-to-cart form (priority 30). The shipping estimate line is rendered at priority 11, just after the price (priority 10). The sections are stacked blocks, not WooCommerce tabs, to match the single-page Nomad-like layout requested in the epic context.

JSON-LD generation is intentionally safe: it only runs on `is_singular('product')`, only when `WC_Product` is available, and skips `offers` if no price is set. Images use the first gallery image or a configurable placeholder URI. Data is output through `wp_json_encode()` with `JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES` inside a single `<script type="application/ld+json">` tag. No admin UI or new meta fields are added; the sections read existing meta or show placeholders.

## Verification

**Commands:**
- `php -l wp-content/themes/compactbox/functions.php` -- expected: `No syntax errors detected`
- `php -l wp-content/themes/compactbox/woocommerce/single-product.php` -- expected: `No syntax errors detected`
- `php -l wp-content/themes/compactbox/template-parts/product-sections.php` -- expected: `No syntax errors detected`
- `ls -la wp-content/themes/compactbox/woocommerce/` -- expected: `single-product.php` exists

**Manual checks (if no CLI):**
- Open `woocommerce/single-product.php` and confirm it does not edit Astra files and delegates product rendering to WooCommerce inside a `.cb-product` wrapper.
- Open `functions.php` and verify the JSON-LD hook is guarded by `is_singular('product')` / `function_exists('WC')` and the sections hook uses `woocommerce_single_product_summary` with priority 25.
- Open `style.css` and confirm `.cb-product` defines a responsive two-column layout and a reduced-motion guard.
- Open `template-parts/product-sections.php` and confirm it renders four translatable sections with placeholder content and no hardcoded business copy.
- Open `README.md` and confirm the WooCommerce override, JSON-LD hook, and placeholder customization are documented.

