---
title: 'STORY-001-07 — Footer multi-colonnes'
type: 'feature'
created: '2026-09-17'
status: 'done'
review_loop_iteration: 0
context:
  - '03-spec/SPEC.md'
  - '_bmad-output/implementation-artifacts/epic-1-context.md'
  - '_bmad-output/implementation-artifacts/stories/STORY-001-01-creer-le-theme-enfant-compactbox.md'
  - '_bmad-output/implementation-artifacts/stories/STORY-001-02-header-nomad-like-navigation-langue-panier.md'
  - '_bmad-output/implementation-artifacts/stories/STORY-001-06-section-storytelling-confiance.md'
  - '00-nomad-redesign.md'
---

<frozen-after-approval reason="human-owned intent — do not modify unless human renegotiates">

## Intent

**Problem:** The CompactBox storefront currently lacks a footer that provides essential trust, support, and legal information for Swiss e-commerce visitors. Without seller identity, legal links, payment signals, and language selection, the store feels incomplete and fails to meet baseline e-commerce expectations.

**Approach:** Add a multi-column, Nomad-like footer at the bottom of every public page. The footer is implemented as a child theme template part, wired via Astra's `astra_footer_before` hook, and includes seller identity (with placeholders for legal address if not finalized), primary support/legal links, accepted payment icons, and a language selector placeholder for EPIC-002.

## Boundaries & Constraints

**Always:**
- Create and edit files only inside `wp-content/themes/compactbox/`.
- Use the Astra hook `astra_footer_before` and child template parts; never edit the parent theme.
- Use `compactbox_` function prefixes, simplified PSR-12 PHP, and BEM-like class names (e.g. `cb-footer`, `cb-footer__column`, `cb-footer__link`).
- Enqueue CSS/JS via existing `compactbox-style` / `compactbox-script` handles with `filemtime()`-based versions.
- Make the footer render on every public front-end page (not only the homepage).
- Keep legal copy factual and placeholder-aware: use a translatable placeholder string for seller address if the legal address is not finalized.
- Use inline SVG icons for payment methods so no external request is required.
- Build mobile-first: stacked columns on small screens, multi-column grid on desktop.
- Include the language selector placeholder already used in the header (FR/DE/EN/IT).
- Ensure footer links are keyboard-accessible with visible focus states.

**Never:**
- Modify Astra parent files or any WordPress core/plugin files.
- Add heavy JS frameworks or jQuery plugins; use vanilla JS only.
- Hardcode the final legal business name or address beyond placeholder status unless explicitly provided.
- Generate fake payment-provider certifications or unsupported payment icons.
- Embed credentials or third-party API calls.

## I/O & Edge-Case Matrix

| Scenario | Input / State | Expected Output / Behavior | Error Handling |
|----------|--------------|---------------------------|----------------|
| HAPPY_PATH | Public page loads | Footer renders below Astra footer with seller identity, links, payments, language selector | N/A |
| MOBILE_VIEW | Viewport < 768 px | Footer columns stack vertically, no horizontal overflow | N/A |
| DESKTOP_VIEW | Viewport ≥ 1024 px | Footer columns render in a multi-column grid (4 columns on largest screens) | N/A |
| NO_LEGAL_ADDRESS | `compactbox_legal_address` option absent | Seller block renders with a translatable placeholder line | N/A |
| NO_WOOCOMMERCE | WooCommerce inactive | Footer still renders; cart/count references not shown; shop links fall back to `home_url('/shop/')` | N/A |
| REDUCED_MOTION | `prefers-reduced-motion: reduce` | No animations or transitions in footer links | N/A |
| ADMIN_PAGE | `/wp-admin/` page loaded | Child footer template is not hooked into admin | N/A |
| MISSING_PAYMENT_ICON | SVG icon file or inline SVG absent | Section still renders text-only; no broken image | Inline SVG only |

</frozen-after-approval>

## Code Map

- `wp-content/themes/compactbox/style.css` — extend with footer CSS variables, full-width `.cb-footer` wrapper, responsive `.cb-footer__grid` (stacked mobile → 2 cols tablet → 4 cols desktop), `.cb-footer__column`, `.cb-footer__link`, `.cb-footer__payment` list with inline SVG icons, language selector placeholder styling, and reduced-motion guard. Reuses existing neutral color tokens.
- `wp-content/themes/compactbox/functions.php` — add `compactbox_footer()` hooked to `astra_footer_before` at default priority (10). Does not guard by `is_front_page()` so the footer appears on every public page. Wrap in `function_exists()` and add the action only when the function is defined. No new helper functions required.
- `wp-content/themes/compactbox/template-parts/footer.php` — render a semantic `<footer class="cb-footer">` with: a 4-column grid containing (1) seller identity/address placeholder + optional contact email, (2) shop/support links (CGV, Privacy, Delivery, Returns, Contact, How to Order), (3) accepted payment icons as inline SVGs, and (4) language selector placeholder (same pattern as header). Use `__()` for translatable strings and `home_url()` / `wc_get_page_permalink()` where available. Add a bottom sub-footer with copyright placeholder.
- `wp-content/themes/compactbox/README.md` — update file layout and document the footer hook (`astra_footer_before`), the four columns, the placeholder strings, and how to customize legal address and payment icons.

## Tasks & Acceptance

**Execution:**
- [ ] `template-parts/footer.php` -- create semantic multi-column footer with seller identity placeholder, legal/support links, payment icons (inline SVG), and language selector placeholder -- content anchor for every public page.
- [ ] `style.css` -- add footer section CSS variables, full-width wrapper, responsive grid, column/link styling, payment icon list, language selector placeholder, focus-visible states, and reduced-motion guard -- establishes the visual shell.
- [ ] `functions.php` -- add `compactbox_footer()` hooked to `astra_footer_before` with default priority 10 and no `is_front_page()` guard; wrap in `function_exists()` -- wires the footer to every public page without touching the parent theme.
- [ ] `README.md` -- update folder layout, document the footer template part, hook, column structure, placeholder usage, and how to customize legal address and payment icons -- continuity for future stories.

**Acceptance Criteria:**
- Given Astra parent is installed and the child theme is active, when any public front-end page loads, then a `footer.cb-footer` element is rendered below the Astra footer with four logical blocks.
- Given the viewport width is less than 768 px, when the page loads, then the footer columns stack vertically, remain readable, and no horizontal page overflow occurs.
- Given the viewport width is at least 1024 px, when the page loads, then the footer columns render in a multi-column grid (at least three visible columns).
- Given the `compactbox_legal_address` option is not set, when the footer renders, then the seller block shows a translatable placeholder line.
- Given WooCommerce is inactive, when the footer renders, then shop links still point to `home_url('/shop/')` and no fatal error occurs.
- Given `prefers-reduced-motion: reduce`, when the footer renders, then no animations or transitions run in the footer.
- Given the repository is inspected, when Astra parent files are compared before and after this story, then they remain unchanged.

## Spec Change Log

## Review Triage Log

- Finding: footer markup used four `<h2>` elements inside `footer.cb-footer` without awareness of surrounding heading outline; decision: the footer is a distinct page region and each column heading is structurally parallel, so `<h2>` is acceptable per HTML5 sectioning-element semantics. Kept as-is; no change required.
- Finding: payment icons had `aria-label` on `<li>` while inner SVG was `aria-hidden`; decision: added `role="img"` to the `<li>` so the accessible name is conveyed reliably. Kept as patch.
- Finding: language selector is only a placeholder string; decision: explicitly matches the spec and the header placeholder pattern, to be wired in EPIC-002. Deferred to EPIC-002.
- Finding: `Delivery` and `Returns` links pointed to the shop page; decision: the spec says "shop links fall back to `home_url('/shop/')`" and the current placeholder links are acceptable until dedicated pages exist. Documented in README that operators should adapt slugs. Kept as-is.
- Finding: hardcoded legal/support page slugs; decision: consistent with existing homepage sections and documented in README as operator-configurable slugs. Kept as-is.
- Finding: `$has_woocommerce` unused dead code; decision: removed. Kept as patch.
- Finding: `$compactbox_page_url` closure declared `static` without captured variables; decision: replaced with a prefixed named helper `compactbox_page_url()` inside `function_exists()` guard. Kept as patch.
- Finding: `gmdate('Y')` used for copyright year instead of `wp_date('Y')`; decision: changed to `wp_date('Y')`. Kept as patch.
- Finding: `compactbox_contact_email` not sanitized as email; decision: changed to `sanitize_email()`. Kept as patch.
- Finding: legal address passed through `esc_html`, collapsing line breaks; decision: changed to `wp_kses_post(nl2br($legal_address, true))`. Kept as patch.
- Finding: no unit/snapshot/integration tests for footer; decision: project has no automated WordPress test harness; verification relies on `php -l` and manual checks per spec. Deferred to future QA harness story.
- Finding: inline SVG icons are not reusable components; decision: acceptable for a static footer and consistent with trust section approach. Deferred to future design-system story if needed.

## Design Notes

The footer is rendered site-wide (not just the homepage) via `astra_footer_before` with default priority 10. Unlike the homepage sections (hero, collections, products, trust) that use `astra_content_before`, the footer intentionally targets the footer hook so it appears after the main content on every public page. The footer is static and does not require new JavaScript.

Column layout:
1. **Seller identity** — brand name placeholder + legal address placeholder + contact email placeholder. All strings are translatable via `__()`.
2. **Support / legal links** — CGV, Privacy, Delivery, Returns, Contact, How to Order. URLs use `wc_get_page_permalink()` when WooCommerce pages exist, otherwise `home_url()` fallbacks.
3. **Payments accepted** — inline SVG icons for common Swiss e-commerce methods (Visa, Mastercard, PayPal, Twint) as a starting set. No unsupported provider claims.
4. **Language selector** — same placeholder pattern as the header (FR/DE/EN/IT), wired in EPIC-002.

A bottom sub-footer contains a minimal copyright placeholder. The design reuses the existing neutral color tokens and BEM-like naming convention, keeping visual consistency with the header, hero, and trust sections.

## Verification

**Commands:**
- `php -l wp-content/themes/compactbox/functions.php` -- expected: `No syntax errors detected`
- `php -l wp-content/themes/compactbox/template-parts/footer.php` -- expected: `No syntax errors detected`
- `ls -la wp-content/themes/compactbox/template-parts/` -- expected: `footer.php` exists

**Manual checks (if no CLI):**
- Open `template-parts/footer.php` and confirm it includes `footer.cb-footer`, a `.cb-footer__grid` with four logical blocks, inline SVG payment icons, and translatable strings.
- Open `functions.php` and verify `compactbox_footer()` is hooked to `astra_footer_before` with default priority 10 and no `is_front_page()` guard.
- Open `style.css` and confirm `.cb-footer__grid` is a responsive grid with a reduced-motion media query.
- Open `README.md` and confirm the footer section, hook, columns, and placeholders are documented.

## Suggested Review Order

**Footer hook and site-wide rendering**

- Entry point: footer is wired to `astra_footer_before` with default priority, no front-page guard.
  [`functions.php`](../../../wp-content/themes/compactbox/functions.php)

**Footer markup and columns**

- Semantic footer with seller identity, legal links, payment icons, and language selector placeholder.
  [`template-parts/footer.php`](../../../wp-content/themes/compactbox/template-parts/footer.php)

**Footer CSS and responsive grid**

- Full-width wrapper, responsive multi-column grid, and reduced-motion guard.
  [`style.css`](../../../wp-content/themes/compactbox/style.css)

**Documentation**

- README documents the footer hook, columns, and customization notes.
  [`README.md`](../../../wp-content/themes/compactbox/README.md)
