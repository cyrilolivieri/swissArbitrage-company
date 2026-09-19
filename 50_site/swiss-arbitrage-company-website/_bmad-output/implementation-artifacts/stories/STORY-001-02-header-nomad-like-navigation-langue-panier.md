---
title: 'STORY-001-02 — Header Nomad-like navigation + sélecteur langue + panier'
type: 'feature'
created: '2026-09-17'
status: 'draft'
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

**Problem:** The CompactBox child theme currently has only a scaffold. The storefront needs a Nomad Goods-style sticky header with primary navigation by product family, a language selector, and a cart/search utility area so visitors can orient and convert immediately.

**Approach:** Build the header inside the child theme using Astra hooks and child-only template parts. Keep it mobile-first: hamburger menu on small screens, horizontal nav on desktop. Provide placeholder text strings ready for Polylang translation keys.

## Boundaries & Constraints

**Always:**
- Create files only inside `wp-content/themes/compactbox/`.
- Use Astra action hooks (`astra_header_before`, `astra_primary_navigaion`, `astra_header`) or child template parts, never edit the parent theme.
- Use `compactbox_` function prefixes, simplified PSR-12 PHP, and BEM-like class names (e.g. `cb-header`, `cb-header__nav`, `cb-header__toggle`).
- Enqueue CSS/JS via `wp_enqueue_scripts` with `filemtime()`-based versions; reuse existing `compactbox-style` / `compactbox-script` handles by extending their files.
- Keep copy factual and sober; no fake data, reviews, or products.
- Reserve FR/DE/EN/IT language selector markup even if Polylang is not yet active.
- Use WordPress/WooCommerce built-in cart fragments where available.

**Never:**
- Modify Astra parent files or any WordPress core/plugin files.
- Add heavy JS frameworks or jQuery plugins; use vanilla JS only.
- Generate real products, fake reviews, or live payment configuration.
- Implement a functional Polylang switcher inside the header; only the hook/placeholder is required until EPIC-002.
- Embed credentials or third-party API calls.

## I/O & Edge-Case Matrix

| Scenario | Input / State | Expected Output / Behavior | Error Handling |
|----------|--------------|---------------------------|----------------|
| HAPPY_PATH | Astra + child theme active | Sticky header loads with logo, family nav, search, cart, language selector | N/A |
| MOBILE_VIEW | Viewport < 768 px | Hamburger toggle shown; menu opens/closes via JS; focusable elements reachable | Menu traps focus while open (future enhancement); at minimum toggles with keyboard |
| EMPTY_CART | WooCommerce cart has 0 items | Cart link shows `0` or icon-only, no fatal error | N/A |
| NO_WOOCOMMERCE | WooCommerce plugin absent | Cart link hidden or falls back to placeholder text | Graceful: no fatal errors, no broken markup |
| REDUCED_MOTION | `prefers-reduced-motion: reduce` | Menu transitions disabled | N/A |

</frozen-after-approval>

## Code Map

- `wp-content/themes/compactbox/style.css` — extend with header layout variables, sticky positioning, mobile-first hamburger rules, and reduced-motion guard.
- `wp-content/themes/compactbox/assets/js/compactbox.js` — add header hamburger toggle and viewport resize listener; keep small and vanilla.
- `wp-content/themes/compactbox/functions.php` — add `compactbox_header_markup()` hooked to Astra header action; register `compactbox_register_menus()` with a `compactbox-primary` menu location; add cart fragment helper only when WooCommerce exists.
- `wp-content/themes/compactbox/template-parts/header-branding.php` — logo + home link markup.
- `wp-content/themes/compactbox/template-parts/header-navigation.php` — `<nav>` wrapping `wp_nav_menu(['theme_location' => 'compactbox-primary'])`.
- `wp-content/themes/compactbox/template-parts/header-utilities.php` — search icon trigger, cart link with count, language selector placeholder.
- `wp-content/themes/compactbox/template-parts/header-mobile-toggle.php` — hamburger button.
- `wp-content/themes/compactbox/template-parts/header.php` — orchestrates the above parts inside a sticky wrapper.
- `wp-content/themes/astra/` — parent theme directory (read-only; not present in workspace, but treat as external).
- `wp-content/themes/compactbox/README.md` — update with header notes and new files.

## Tasks & Acceptance

**Execution:**
- [ ] `style.css` -- add header CSS variables, sticky `.cb-header`, desktop `.cb-header__nav`, mobile `.cb-header__menu` overlay, hamburger `.cb-header__toggle`, utility alignment, and reduced-motion guard -- establishes the visual shell for all header states.
- [ ] `assets/js/compactbox.js` -- add `compactboxHeaderToggle()` IIFE: toggle `aria-expanded`, add/remove `.is-open` class on menu, close on Escape, resize listener resets state on desktop -- makes the mobile menu interactive.
- [ ] `functions.php` -- register `compactbox-primary` menu location on `after_setup_theme`; add `compactbox_header()` hooked to an Astra header action; add `compactbox_cart_link()` helper that returns count via `WC()->cart->get_cart_contents_count()` if WooCommerce exists; enqueue assets as already configured in STORY-001-01 -- wires WordPress primitives without touching parent theme.
- [ ] `template-parts/header-branding.php` -- create site logo/home link using `home_url()` and `get_bloginfo('name')` with `.cb-header__brand` class -- brand anchor for header.
- [ ] `template-parts/header-navigation.php` -- create `<nav aria-label="Primary">` calling `wp_nav_menu(['theme_location' => 'compactbox-primary', 'container' => false, 'menu_class' => 'cb-header__menu'])` -- primary family navigation.
- [ ] `template-parts/header-utilities.php` -- create utility bar with search link/placeholder, cart link with count badge, and FR/DE/EN/IT language selector placeholder -- conversion and language affordances.
- [ ] `template-parts/header-mobile-toggle.php` -- create hamburger `<button class="cb-header__toggle" aria-controls="cb-header-menu" aria-expanded="false">` -- mobile menu control.
- [ ] `template-parts/header.php` -- assemble sticky wrapper around branding, navigation, utilities, and mobile toggle; output via `compactbox_header()` hook -- complete header component.
- [ ] `README.md` -- update file layout, document Astra hook dependency, menu registration, and header behavior -- continuity for future stories.

**Acceptance Criteria:**
- Given Astra parent is installed and the child theme is active, when a front-end page loads, then the header markup is rendered at the Astra header hook with the classes `cb-header`, `cb-header__brand`, `cb-header__nav`, `cb-header__menu`, `cb-header__utilities`, `cb-header__toggle`.
- Given the viewport width is 768 px or greater, when the page loads, then the primary navigation is horizontal and the hamburger toggle is visually hidden.
- Given the viewport width is less than 768 px, when the hamburger toggle is clicked, then the primary navigation menu toggles visible/hidden and the button `aria-expanded` attribute updates.
- Given WooCommerce is active and the cart is empty, when the page loads, then the cart link displays `0` items or an icon-only state without throwing errors.
- Given WooCommerce is not active, when the page loads, then the cart link is hidden or replaced by a placeholder and no PHP fatal error occurs.
- Given `prefers-reduced-motion: reduce`, when the mobile menu opens, then no CSS transition/animation runs.
- Given the repository is inspected, when Astra parent files are compared before and after this story, then they remain unchanged.

## Spec Change Log

## Review Triage Log

## Design Notes

The header is intentionally decoupled into Astra-compatible template parts. Because the Astra parent theme is not present in this workspace, the implementation relies on Astra's documented header hooks (`astra_header_before`, `astra_primary_navigaion`, `astra_header`) and will gracefully degrade if the hook is absent. The Polylang selector is a visual placeholder only; actual language switching will be wired in EPIC-002.

## Verification

**Commands:**
- `php -l wp-content/themes/compactbox/functions.php` -- expected: `No syntax errors detected`
- `ls -la wp-content/themes/compactbox/template-parts/` -- expected: `header.php`, `header-branding.php`, `header-navigation.php`, `header-utilities.php`, `header-mobile-toggle.php` exist
- `ls -la wp-content/themes/compactbox/assets/js/compactbox.js` -- expected: file exists and contains hamburger toggle code

**Manual checks (if no CLI):**
- Open `template-parts/header.php` and confirm it includes the brand, nav, utilities, and toggle parts with the correct class names.
- Open `functions.php` and verify `register_nav_menus(['compactbox-primary' => ...])` and a header hook function are present.
- Open `style.css` and confirm `.cb-header` is `position: sticky` and reduced-motion media query exists.
- Open `compactbox.js` and confirm the toggle function listens for click and Escape key events.

## Suggested Review Order

- Header assembly and Astra hook wiring
  [`functions.php`](../../../wp-content/themes/compactbox/functions.php)
- Sticky header CSS and mobile-first layout
  [`style.css`](../../../wp-content/themes/compactbox/style.css)
- Vanilla JS hamburger toggle
  [`assets/js/compactbox.js`](../../../wp-content/themes/compactbox/assets/js/compactbox.js)
- Header template parts
  [`template-parts/header.php`](../../../wp-content/themes/compactbox/template-parts/header.php)
