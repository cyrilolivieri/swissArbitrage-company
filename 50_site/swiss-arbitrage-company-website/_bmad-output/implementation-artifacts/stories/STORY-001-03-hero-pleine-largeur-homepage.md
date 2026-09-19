---
title: 'STORY-001-03 — Hero pleine largeur homepage'
type: 'feature'
created: '2026-09-17'
status: 'done'
baseline_commit: 'NO_VCS'
review_loop_iteration: 1
context:
  - '03-spec/SPEC.md'
  - '_bmad-output/implementation-artifacts/epic-1-context.md'
  - '_bmad-output/implementation-artifacts/stories/STORY-001-01-creer-le-theme-enfant-compactbox.md'
  - '_bmad-output/implementation-artifacts/stories/STORY-001-02-header-nomad-like-navigation-langue-panier.md'
  - '00-nomad-redesign.md'
---

<frozen-after-approval reason="human-owned intent — do not modify unless human renegotiates">

## Intent

**Problem:** The CompactBox homepage needs an above-the-fold hero section that immediately communicates the brand's value proposition and drives visitors toward the shop.

**Approach:** Add a full-width, 60–80vh hero component to the child theme using Astra-compatible template parts and child-only hooks. Include a strong current headline, a single CTA to the shop, and a replaceable placeholder image ready for the final asset.

## Boundaries & Constraints

**Always:**
- Create files only inside `wp-content/themes/compactbox/`.
- Use Astra action hooks (`astra_content_before`, `astra_header_after`) or child template parts, never edit the parent theme.
- Use `compactbox_` function prefixes, simplified PSR-12 PHP, and BEM-like class names (e.g. `cb-hero`, `cb-hero__content`, `cb-hero__cta`).
- Enqueue CSS/JS via `wp_enqueue_scripts` with `filemtime()`-based versions; reuse existing `compactbox-style` / `compactbox-script` handles by extending their files.
- Keep copy factual and sober; no fake data, reviews, or products.
- Ensure the hero is responsive, accessible, and respects `prefers-reduced-motion`.
- Keep the placeholder image dimensions explicit (16:9 landscape) and document how to replace it.

**Never:**
- Modify Astra parent files or any WordPress core/plugin files.
- Add heavy JS frameworks or jQuery plugins; use vanilla JS only.
- Generate real products, fake reviews, or live payment configuration.
- Hardcode final brand copy; use current, placeholder-ready copy.
- Embed credentials or third-party API calls.

## I/O & Edge-Case Matrix

| Scenario | Input / State | Expected Output / Behavior | Error Handling |
|----------|--------------|---------------------------|----------------|
| HAPPY_PATH | Astra + child theme active, homepage loaded | Full-width hero renders with headline, subline, CTA, placeholder image | N/A |
| MOBILE_VIEW | Viewport < 768 px | Hero stacks vertically, text readable, CTA remains tappable, image covers area | N/A |
| NO_IMAGE | Placeholder image missing | Background fallback color keeps the section visually intact | No broken image icon visible |
| REDUCED_MOTION | `prefers-reduced-motion: reduce` | No parallax or fade animations | N/A |
| NOT_HOMEPAGE | Single post or archive viewed | Hero does not render outside the homepage | N/A |

</frozen-after-approval>

## Code Map

- `wp-content/themes/compactbox/style.css` — extend with hero layout variables, full-width container, 60–80vh min-height, text/image stacking, CTA styling, and reduced-motion guard.
- `wp-content/themes/compactbox/assets/js/compactbox.js` — no new JS required; hero is CSS-driven. Keep existing header toggle intact.
- `wp-content/themes/compactbox/functions.php` — add `compactbox_homepage_hero()` hooked to `astra_content_before`, guarded by `is_front_page()`.
- `wp-content/themes/compactbox/template-parts/hero.php` — hero markup: wrapper, content column (headline, subline, CTA), media column/background placeholder image.
- `wp-content/themes/compactbox/assets/images/hero-placeholder.png` — 16:9 placeholder image (e.g. 1920x1080) used until final asset is supplied.
- `wp-content/themes/compactbox/README.md` — update file layout and document how to replace the hero image and copy.

## Tasks & Acceptance

**Execution:**
- [x] `style.css` -- add hero CSS variables, full-width `.cb-hero`, 60–80vh min-height, centered content layout, `.cb-hero__headline`, `.cb-hero__subline`, `.cb-hero__cta`, responsive stacking, and reduced-motion guard -- establishes the visual shell and responsive behavior.
- [x] `assets/images/hero-placeholder.png` -- generate a 1920x1080 placeholder image with a neutral theme color and "CompactBox hero — replace me" indicator so the section renders cleanly before the final asset arrives.
- [x] `template-parts/hero.php` -- create semantic hero markup with `section.cb-hero`, a content wrapper containing an `h1` headline, a subline paragraph, a single primary CTA link to the shop page, and a placeholder image element with `alt` text -- content and accessibility anchor for the homepage.
- [x] `functions.php` -- add `compactbox_homepage_hero()` hooked to `astra_content_before` and guarded by `is_front_page()`; keep the function inside a `function_exists()` block and add the action only when the function is defined -- wires the hero to the homepage without touching the parent theme.
- [x] `README.md` -- update folder layout, document the hero template part, hook (`astra_content_before`), placeholder image path, and how to swap copy/image -- continuity for future stories.

**Acceptance Criteria:**
- Given Astra parent is installed and the child theme is active, when the homepage front-end loads, then a `section.cb-hero` element is rendered immediately after the header with a single CTA link pointing to the shop URL.
- Given the viewport width is 768 px or greater, when the homepage loads, then the hero section has a height between 60vh and 80vh and the headline/CTA remain readable over the background.
- Given the viewport width is less than 768 px, when the homepage loads, then the hero content stacks vertically, the CTA remains tappable, and no horizontal overflow occurs.
- Given the hero placeholder image is missing, when the homepage loads, then a solid background color keeps the section visually intact and no broken-image icon is visible.
- Given `prefers-reduced-motion: reduce`, when the homepage loads, then no CSS transition/animation runs on the hero.
- Given a single post or archive page is loaded, when the page renders, then the hero section does not appear outside the homepage.
- Given the repository is inspected, when Astra parent files are compared before and after this story, then they remain unchanged.

## Spec Change Log

## Review Triage Log

Loop 1 findings (post-implementation review, 2026-09-17):

- **Dismissed:** CTA focus-visible / WCAG 2.2 — patched in style.css by adding `:focus-visible` outline.
- **Dismissed:** CTA link could be empty if WooCommerce shop page is falsy — patched in template-parts/hero.php by falling back to `home_url('/shop/')` when `wc_get_page_permalink('shop')` is empty.
- **Dismissed:** Missing broken-image fallback for missing placeholder — patched by adding `onerror="this.style.display='none'"` to the hero `<img>` and a solid `--cb-hero-bg` fallback color already exists.
- **Dismissed:** Hero width may be constrained by parent container — patched by using `width: 100vw` with `margin-left: calc(50% - 50vw)` and `max-width: 100%` to break out to full viewport width while preventing horizontal overflow.
- **Dismissed:** Overlay could block future interactive media — patched by adding `pointer-events: none` to `.cb-hero__media`.
- **Dismissed:** No placeholder wordmark visible on image — verified by reading `assets/images/hero-placeholder.png`; text "CompactBox hero — replace me" is rendered.
- **Dismissed:** H1 duplication with Astra page title — out of scope for this story; operator can disable homepage title via Astra/customizer.
- **Dismissed:** Long translations overflow — placeholder copy is concise; final brand copy will be controlled by operator.
- **Dismissed:** `is_front_page()` edge cases — existing guard is the required spec behavior.
- **Dismissed:** No print media query — out of scope for MVP.
- **Dismissed:** No automated test file created — spec verification uses manual/CLI checks and the project has no established test harness.
- **Dismissed:** Inconsistency between `compactbox_header()` and `compactbox_homepage_hero()` action placement — both functions are safe; the hero action is inside the `function_exists()` guard as explicitly required by the spec.
- **Dismissed:** Decorative image alt text not ideal for final state — spec explicitly asks for explicit alt text on placeholder image; final asset will replace alt text.## Design Notes

The hero is intentionally a single-component story that depends only on the child theme scaffold (STORY-001-01) and the header hook conventions established in STORY-001-02. It hooks to `astra_content_before` so it sits above the default Astra page content on the homepage only. The placeholder image is generated at 1920x1080 and served responsively with `srcset`/`sizes` if feasible; if not, a single `img` with `loading="eager"` and an explicit `alt` is sufficient. The CTA points to the WooCommerce shop page (`wc_get_page_permalink('shop')` if available, otherwise `home_url('/shop/')`) without assuming WooCommerce is installed.

## Verification

**Commands:**
- `php -l wp-content/themes/compactbox/functions.php` -- expected: `No syntax errors detected`
- `ls -la wp-content/themes/compactbox/template-parts/` -- expected: `hero.php` exists
- `ls -la wp-content/themes/compactbox/assets/images/` -- expected: `hero-placeholder.png` exists
- `php -r "echo extension_loaded('gd') ? 'gd ok' : 'gd missing';"` -- expected: `gd ok` (so the placeholder image can be generated)

**Manual checks (if no CLI):**
- Open `template-parts/hero.php` and confirm it includes `section.cb-hero`, an `h1`, a subline, a single CTA, and an `img` with `alt` text.
- Open `functions.php` and verify `compactbox_homepage_hero()` is hooked to `astra_content_before` and guarded by `is_front_page()`.
- Open `style.css` and confirm `.cb-hero` has `min-height: 60vh`/`max-height: 80vh` or equivalent and a reduced-motion media query exists.
- Confirm `assets/images/hero-placeholder.png` is present and displays a placeholder wordmark.

## Suggested Review Order

**Hero hook and front-page guard**

- Entry point: hero is wired to `astra_content_before` and only renders on the homepage.
  [`functions.php:78`](../../../wp-content/themes/compactbox/functions.php#L78)

**Hero markup and accessibility**

- Semantic hero section with h1, subline, CTA, and placeholder image.
  [`template-parts/hero.php:27`](../../../wp-content/themes/compactbox/template-parts/hero.php#L27)

- Shop URL fallback guards against an empty WooCommerce shop permalink.
  [`template-parts/hero.php:18`](../../../wp-content/themes/compactbox/template-parts/hero.php#L18)

- Broken-image guard hides the placeholder if it fails to load.
  [`template-parts/hero.php:35`](../../../wp-content/themes/compactbox/template-parts/hero.php#L35)

**Hero responsive CSS and reduced-motion guard**

- Full-width hero breaks out of parent containers while capping at 80vh.
  [`style.css:254`](../../../wp-content/themes/compactbox/style.css#L254)

- Overlay and media are non-interactive so content remains accessible.
  [`style.css:267`](../../../wp-content/themes/compactbox/style.css#L267)

- CTA focus-visible outline supports keyboard navigation.
  [`style.css:325`](../../../wp-content/themes/compactbox/style.css#L325)

- Reduced-motion guard disables hero transitions.
  [`style.css:340`](../../../wp-content/themes/compactbox/style.css#L340)

**Placeholder image generation and dimensions**

- Generated 1920x1080 placeholder with "CompactBox hero — replace me" text.
  [`assets/images/hero-placeholder.png`](../../../wp-content/themes/compactbox/assets/images/hero-placeholder.png)

**Documentation**

- README documents the hero hook, replacement image path, and copy swap instructions.
  [`README.md:41`](../../../wp-content/themes/compactbox/README.md#L41)


