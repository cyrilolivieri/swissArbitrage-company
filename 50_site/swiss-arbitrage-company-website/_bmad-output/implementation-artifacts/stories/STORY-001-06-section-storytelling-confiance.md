---
title: 'STORY-001-06 — Section storytelling confiance'
type: 'feature'
created: '2026-09-17'
baseline_commit: 'NO_VCS'
status: 'done'
review_loop_iteration: 0
context:
  - '03-spec/SPEC.md'
  - '_bmad-output/implementation-artifacts/epic-1-context.md'
  - '_bmad-output/implementation-artifacts/stories/STORY-001-01-creer-le-theme-enfant-compactbox.md'
  - '_bmad-output/implementation-artifacts/stories/STORY-001-02-header-nomad-like-navigation-langue-panier.md'
  - '_bmad-output/implementation-artifacts/stories/STORY-001-03-hero-pleine-largeur-homepage.md'
  - '_bmad-output/implementation-artifacts/stories/STORY-001-04-grids-de-collections-par-famille.md'
  - '_bmad-output/implementation-artifacts/stories/STORY-001-05-section-best-sellers-nouveautes.md'
  - '00-nomad-redesign.md'
---

<frozen-after-approval reason="human-owned intent — do not modify unless human renegotiates">

## Intent

**Problem:** The CompactBox homepage currently surfaces collections and products, but lacks a trust-oriented section that reassures Swiss visitors about delivery, pricing fairness, and product quality before they reach checkout.

**Approach:** Add a factual, three-block trust storytelling section directly below the product carousel on the homepage. Each block uses an icon, a short heading, and a one-line fact; no fake reviews, no fake guarantees.

## Boundaries & Constraints

**Always:**
- Create and edit files only inside `wp-content/themes/compactbox/`.
- Use the Astra hook `astra_content_before` and child template parts; never edit the parent theme.
- Use `compactbox_` function prefixes, simplified PSR-12 PHP, and BEM-like class names (e.g. `cb-trust`, `cb-trust__grid`, `cb-trust__card`).
- Enqueue CSS/JS via `wp_enqueue_scripts` with `filemtime()`-based versions; extend existing `compactbox-style` / `compactbox-script` handles.
- Keep copy factual, sober, and focused on the three blocks: **Swiss delivery**, **Compared prices**, **No compromise**.
- Use SVG icons inlined as simple, accessible graphics (icon + text, no standalone icon meaning).
- Make the section responsive and readable on mobile.
- Respect `prefers-reduced-motion`.

**Never:**
- Modify Astra parent files or any WordPress core/plugin files.
- Add heavy JS frameworks or jQuery plugins; use vanilla JS only.
- Generate fake testimonials, fake reviews, fake certifications, or unrealistic promises.
- Hardcode final brand copy beyond the factual trust signals.
- Embed credentials or third-party API calls.

## I/O & Edge-Case Matrix

| Scenario | Input / State | Expected Output / Behavior | Error Handling |
|----------|--------------|---------------------------|----------------|
| HAPPY_PATH | Homepage loads | Trust section renders below product carousel with 3 visible cards | N/A |
| MOBILE_VIEW | Viewport < 768 px | Cards stack vertically, readable, no horizontal overflow | N/A |
| DESKTOP_VIEW | Viewport ≥ 1024 px | Cards render in a single row of three equal columns | N/A |
| NO_PRODUCTS_CAROUSEL | WooCommerce inactive | Trust section still renders below collections because it is independent | N/A |
| REDUCED_MOTION | `prefers-reduced-motion: reduce` | No entrance animations or transitions | N/A |
| NOT_HOMEPAGE | Single post or archive viewed | Trust section does not render outside the homepage | N/A |
| MISSING_ICON | An SVG icon file is missing | Section still renders with text-only fallback; no broken-image icon | N/A |

</frozen-after-approval>

## Code Map

- `wp-content/themes/compactbox/style.css` — extend with trust section variables, full-width `.cb-trust` wrapper, `.cb-trust__grid` responsive grid, `.cb-trust__card` with icon, heading and description styling, and reduced-motion guard. Reuses existing color tokens where possible.
- `wp-content/themes/compactbox/assets/js/compactbox.js` — no new behavior required; trust section is static. Confirm the file still initializes header toggle and carousel only.
- `wp-content/themes/compactbox/functions.php` — add `compactbox_homepage_trust()` hooked to `astra_content_before` at priority 25 so it renders after products (priority 20) on the homepage only; guard by `is_front_page()`.
- `wp-content/themes/compactbox/template-parts/trust.php` — render a semantic `<section class="cb-trust">` with an `h2`, a three-card grid, each card containing an inline SVG icon, a heading and a short factual description. Pull copy from translatable strings with `__()`.
- `wp-content/themes/compactbox/README.md` — update file layout and document the trust section hook priority and how to edit or extend the blocks.

## Tasks & Acceptance

**Execution:**
- [x] `style.css` -- add trust section CSS variables, full-width `.cb-trust` wrapper, `.cb-trust__grid` responsive grid, `.cb-trust__card` styling with inline SVG icon, heading, description, focus-visible states, and reduced-motion guard -- establishes the visual shell.
- [x] `template-parts/trust.php` -- create semantic trust section with `h2`, render three cards (Swiss delivery, Compared prices, No compromise) using inline SVG icons and translatable strings; keep copy factual and sober -- content anchor for the homepage.
- [x] `functions.php` -- add `compactbox_homepage_trust()` hooked to `astra_content_before` with priority 25 and guarded by `is_front_page()`; wrap in `function_exists()` and add the action only when the function is defined -- wires the trust section below the product carousel without touching the parent theme.
- [x] `README.md` -- update folder layout, document the trust template part, hook priority (25), the three blocks, and how to customize copy -- continuity for future stories.

**Acceptance Criteria:**
- Given Astra parent is installed and the child theme is active, when the homepage front-end loads, then a `section.cb-trust` element is rendered below the product carousel with three `.cb-trust__card` elements.
- Given the viewport width is less than 768 px, when the homepage loads, then the trust cards stack vertically, remain readable, and no horizontal page overflow occurs.
- Given the viewport width is at least 1024 px, when the homepage loads, then the three trust cards render in a single equal-width row.
- Given WooCommerce is inactive, when the homepage loads, then the trust section still renders because it does not depend on products.
- Given `prefers-reduced-motion: reduce`, when the homepage loads, then no entrance animations or transitions run in the trust section.
- Given a single post or archive page is loaded, when the page renders, then the trust section does not appear outside the homepage.
- Given the repository is inspected, when Astra parent files are compared before and after this story, then they remain unchanged.

## Spec Change Log

## Review Triage Log

## Design Notes

The trust section intentionally follows the product carousel and reuses the same Astra hook (`astra_content_before`) with a higher priority (25 vs. products at 20 vs. collections at 15 vs. hero at 10) so the visual order on the homepage is hero → collections → products → trust. The section is static and does not require new JavaScript; it focuses on three factual signals:

1. **Swiss delivery** — factual wording about local dispatch/delivery to Switzerland (no guaranteed time promises unless final logistics are known).
2. **Compared prices** — factual wording about benchmarking against the local market; no specific competitor names or absolute savings claims.
3. **No compromise** — factual wording about selected specs and quality criteria.

SVG icons are inlined directly in the template part so the section works even if external icon files are missing, and so no extra HTTP request is needed. The CSS uses existing neutral color tokens and adds only trust-specific spacing and grid rules.

## Verification

**Commands:**
- `php -l wp-content/themes/compactbox/functions.php` -- expected: `No syntax errors detected`
- `ls -la wp-content/themes/compactbox/template-parts/` -- expected: `trust.php` exists

**Manual checks (if no CLI):**
- Open `template-parts/trust.php` and confirm it includes `section.cb-trust`, an `h2`, three `.cb-trust__card` elements, and inline SVG icons.
- Open `functions.php` and verify `compactbox_homepage_trust()` is hooked to `astra_content_before` with priority 25 and guarded by `is_front_page()`.
- Open `style.css` and confirm `.cb-trust__grid` is a responsive grid with a reduced-motion media query.
- Open `README.md` and confirm the trust section, hook priority, and the three blocks are documented.

## Suggested Review Order

**Trust hook and front-page guard**

- Entry point: trust section is wired to `astra_content_before` with priority 25, only renders on the homepage.
  [`functions.php:120`](../../../wp-content/themes/compactbox/functions.php#L120)

**Trust markup and icon cards**

- Semantic trust section with heading, three icon cards, and translatable factual copy.
  [`template-parts/trust.php:55`](../../../wp-content/themes/compactbox/template-parts/trust.php#L55)

**Trust CSS and responsive grid**

- Full-width wrapper, responsive three-column grid, and reduced-motion guard.
  [`style.css:715`](../../../wp-content/themes/compactbox/style.css#L715)

**Documentation**

- README documents the trust hook, blocks, and customization notes.
  [`README.md:31`](../../../wp-content/themes/compactbox/README.md#L31)
