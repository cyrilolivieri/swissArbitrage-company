# Epic 1 Context: Design storefront CompactBox

<!-- Compiled from planning artifacts. Edit freely. Regenerate with compile-epic-context if planning docs change. -->

## Goal

Build the CompactBox child theme on Astra and reproduce a Nomad Goods-style storefront (header, hero, collection grids, product pages, footer). Keep the site in offline guard mode (HTTP 503 + noindex) until the operator GO. Planning artifacts were not available, so this context relies on the epics file and the project SPEC.

## Stories

- Story 001-01: Créer le thème enfant CompactBox
- Story 001-02: Header Nomad-like navigation + sélecteur langue + panier
- Story 001-03: Hero pleine largeur homepage
- Story 001-04: Grids de collections par famille
- Story 001-05: Section best-sellers / nouveautés
- Story 001-06: Section storytelling confiance
- Story 001-07: Footer multi-colonnes
- Story 001-08: Fiche produit structurée
- Story 001-09: Guard offline 503 + noindex
- Story 001-10: Wireframe / maquettes statiques des pages clés

## Requirements & Constraints

- Deliver a WordPress child theme under `wp-content/themes/astra-child/` named `compactbox`, with `style.css`, `functions.php`, and `screenshot.png`.
- Do not modify the Astra parent theme. All overrides must come from the child theme.
- Enqueue child CSS/JS via `wp_enqueue_scripts`, using `filemtime()` based versions.
- Implement a sticky 70–90 px header with product-family navigation for six MVP families (Cables, Hubs, Stands, Organizers, Phone Mounts, Adapters), a search trigger, cart link, and FR/DE/EN/IT language selector; mobile-first hamburger menu.
- Build a full-width homepage hero 60–80vh with a strong current headline, a single CTA to the shop, and a replaceable placeholder image for the final asset.
- Provide collection grids that render two columns on mobile and four on desktop, one card per family with image, name, short description, and link.
- Add a best-sellers/new arrivals carousel capped at eight products; do not generate fake reviews or fake products.
- Add a trust storytelling section with blocks: Swiss delivery, Compared prices, No compromise; keep copy factual and sober.
- Build a multi-column footer with seller identity (placeholder if legal address not final), links to CGV, Privacy, Delivery, Returns, Contact, How to Order, accepted payment icons, and language selector.
- Implement a structured product page with gallery, SEO title, CHF price, visible shipping, specs, compatibility, box contents, FAQ, Add-to-cart CTA, and injected Schema.org Product JSON-LD.
- Keep the front-end offline with HTTP 503 + meta noindex while `compactbox_site_online` is not enabled; admin must remain accessible; provide a switch under Settings > CompactBox.
- Deliver static HTML mockups for homepage, product page, cart, checkout, and legal pages into `_bmad-output/design-mockups/`.

## Technical Decisions

- **Stack**: WordPress + WooCommerce on Astra, child theme `compactbox`, Polylang for WooCommerce for FR/DE/EN/IT, PHP 8.3, Apache 2.4, MariaDB 10.11.
- **Theme location**: `wp-content/themes/astra-child/`; files include `style.css`, `functions.php`, optional `template-parts/`, and `assets/` for logos/fonts/images.
- **CSS/JS loading**: all overrides enqueued from the child theme; no inline heavy frameworks; vanilla JS or Astra hooks preferred; BEM-like class names, mobile-first CSS.
- **Offline guard**: implemented as part of the CompactBox Core plugin (`wp-content/plugins/compactbox-core/includes/class-offline-guard.php`), returning 503 + noindex for non-admin front-end requests when `compactbox_site_online != 'yes'`.
- **Site option**: `compactbox_site_online` defaults to `no`; exposed in Settings > CompactBox.
- **Code style**: simplified PSR-12 PHP with `CompactBox\Core` namespaces; commits in English with explicit scopes; no direct plugin modifications — use hooks and child theme overrides.

## UX & Interaction Patterns

- Sticky header with primary navigation by product family plus dropdowns by usage.
- Hero full-width with a single primary CTA.
- Collection cards: image, name, short description, link.
- Best-seller carousel with real products only, max eight items.
- Trust section blocks with factual copy.
- Footer multi-column layout with legal, support, payments, and language links.
- Product page: gallery left / details right (or stacked mobile), CTA visible above the fold, accordion/tabs for specs, compatibility, box contents, FAQ.

## Cross-Story Dependencies

- Story 001-01 (child theme scaffold) is a prerequisite for Stories 001-02 through 001-08.
- Story 001-09 (offline guard) is also implemented in EPIC-005 (CompactBox Core plugin); for EPIC-001, the guard should at minimum be configurable and active even if the full plugin is later.
- Story 001-10 (static mockups) depends on the visual direction defined in Stories 001-02 through 001-08.
- Translation strings and hreflang behavior are detailed in EPIC-002, but Story 001-02 must reserve the FR/DE/EN/IT selector in the header.
