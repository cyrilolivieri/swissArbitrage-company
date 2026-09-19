# HANDOFF COMPLET — Session 7 (2026-09-18, ~17h00 CEST)

**Projet :** Site e-commerce CompactBox (WordPress/WooCommerce local, futur compactbox.ch)
**Inspiration :** NOMAD (nomadgoods.com/ch) — clone local : `~/swissArbitrage-company/50_site/nomadgoods-clone/`
**Statut juridique projet :** pause depuis le 12/09 (attente assurance RC pro) — le build technique continue.
**URL de test :** http://localhost (guard offline DÉSACTIVÉ : `compactbox_site_online=yes` en base)

---

## 1. CE QUI A ÉTÉ FAIT CETTE SESSION (18/09 après-midi)

### A. Reverse engineering NOMAD — ÉTUDE STRUCTURELLE COMPLÈTE ✅
- Source de vérité : `~/swissArbitrage-company/50_site/nomadgoods-clone/nomadgoods_analysis.json`
  (19 sections, headings, nav_links, fonts, colors — CLÉS : `sections`, `headings`, `nav_links`, `fonts`, `colors`)
- **Anatomie réelle NOMAD (ordre exact) :**
  1. Hero "ALL NEW / iPhone 18 & iPhone Duo Are Here / Explore the Collection" (texte à GAUCHE, badge pilule bleu)
  2. Bandeau promo noir "NEW / Level Up Your New iPhone 18 / Shop Now"
  3. **"New for iPhone 18" = rangée de PRODUITS** (carousel horizontal — PAS une collection !)
  4. 4 tuiles catégories image pleine largeur (iPhone 18 / iPhone Duo / Apple Watch Bands / Accessories)
  5. 3 bandeaux promo empilés (NEW Rocky Point Band / Cables, Chargers & More / AirPods 5)
  6. Citation co-fondateur (Noah Dentzel)
  7. Communauté Instagram ("From the Nomad Community" + handles)
  8. Bande reviews "★★★★★ 17,000+ 5 Star Reviews / Trusted Daily by Nomads Like You"
  9. Best sellers (produits)
  10. Explore/About ("14 Years of Quality Goods", "We Are Nomad", "Sustainability")
- **Couleurs NOMAD :** #000, #0013BC, #0048ff (bleus badge/CTA), #005bd3, #2B2B2B, #E0E0E0, #E1E1E1, #F1F1F1 (gris cards), #E3163B, #EDB900 (accents), #fff
- **Font NOMAD :** Gotham-Book (payante) → nous utilisons **Montserrat** (Google Fonts) — décision utilisateur OK
- **Header NOMAD :** PAS une barre pleine largeur = **pillule blanche arrondie flottante** + barre d'annonce sombre au-dessus

### B. Header reconstruit (style NOMAD exact) ✅
- **Barre d'annonce** sombre (#2b1d16) fixe top 0, 32px : "Compact accessories for Swiss tech setups | Shop Cables, Hubs, and More"
- **Pillule blanche** : `.cb-header__pill` (max-width 1240, height 56px, border-radius 999px, shadow), position fixed top 40px
- Logo **COMPACTBOX** à gauche (dans `header-branding.php`), nav centre, icônes (recherche, panier, langues) à droite
- Fichiers : `template-parts/header.php` (réécrit avec `.cb-announce` + `.cb-header__pill`), `header-branding.php` (texte "COMPACTBOX")

### C. Hero reconstruit (style NOMAD exact) ✅
- `template-parts/hero.php` : badge pilule bleu "ALL NEW" → preline "Compact accessories" (petit) → headline "for Swiss tech setups." (grand, clamp 2.75–4.5rem) → subline → CTA pilule blanche "Shop Now"
- CSS : texte à GAUCHE (margin-left 70px), `justify-content: flex-start`, badge `border-radius: 999px` fond #1d4ed8
- Image : `assets/images/hero-nomad.jpg` (vraie photo NOMAD, coques cuir iPhone)
- Hauteur hero : 680px fixe (pas 100vh — évite que les sections d'en dessous soient invisibles)

### D. NOUVELLES SECTIONS dans front-page.php (anatomie NOMAD) ✅
Ordre actuel du site : header → hero → **promo banner** → **products** → **tiles 2×2** → **reviews band** → trust → footer
1. `.cb-promo` : bandeau noir plein écran "NEW / Level Up Your Setup / Shop Now" (image collection-cables.png à gauche, texte à droite, min-height 420px)
2. `.cb-products` : "New arrivals" — **4 VRAIS produits WooCommerce** avec images + prix (voir §2)
3. `.cb-tiles` : 4 tuiles image 2×2 avec label pilule en bas à gauche (Hubs USB-C, Adaptateurs, Stands laptop, Organisateurs)
4. `.cb-reviews` : bande "★★★★★ / Trusted by Swiss tech users / Fast local shipping — fair prices" (fond #f7f7f7)
5. Trust + Footer inchangés

### E. PRODUITS WOOCOMMERCE RÉPARÉS ✅
**Problème résolu :** `wc_get_products()` retournait 0 produits (lookup table non alimentée). **FIX : ré-enregistrer chaque produit via `wc_get_product($pid)->save()`** — 4 produits trouvés :
| ID | Nom | Prix | Image attachée |
|----|-----|------|--------|
| 11 | Câble USB-C vers USB-C 2m 100W PD | 14.90 | 46 (cable-usbc) |
| 12 | Support Laptop Aluminium Pliable | 19.90 | 48 (support-laptop) |
| 13 | Hub USB-C 4 Ports Aluminium | 16.90 | 50 (hub-usbc) |
| 14 | Chargeur GaN 65W USB-C | 24.90 | 51 (chargeur-gan) |

**ATTENTION piège WordPress :** `wp_insert_attachment()` avec un chemin absolu hors uploads génère une URL cassée `uploads//var/www/...` → 404. **Méthode correcte :** copier le fichier dans `/var/www/html/wp-content/uploads/products/` puis attacher avec chemin RELATIF (`products/cable-usbc.png`). Fichiers images dans `uploads/products/` (toutes tailles générées, HTTP 200 vérifié).

**Deuxième piège :** WordPress "big image threshold" a renommé `collection-stands.jpg` → `collection-stands-scaled.jpg` (original supprimé). Les templates pointent maintenant vers `-scaled.jpg`. Si tu réutilises des images >2560px, vérifier les noms.

### F. Template products.php ✅
- `template-parts/products.php` : requête `wc_get_products(['status'=>'publish','limit'=>8,'orderby'=>'date','order'=>'DESC'])`, **masque la section si 0 produits** (`return;`), grid 4 colonnes desktop, cards fond blanc avec image + nom + prix

### G. Corrections techniques session ✅
- `overflow: hidden` répété 5× sur body supprimé (cachait toutes les sections sous le hero)
- Parse error PHP products.php ligne 28 (`<?php` dans du PHP) → fichier réécrit proprement
- Parse error collections.php ligne 39 (`<?php :` typo) → fichier réécrit (families avec img par slug)
- `cb-trust__item` → `cb-trust__card` (mismatch class fixé)
- **Outil de capture : Playwright installé et validé** (recommandation code_master : `headless=True`, attendre `networkidle`, full_page=True). Google-chrome headless donne des captures TRONQUÉES — ne plus l'utiliser.

---

## 2. ÉTAT ACTUEL DU SITE (ce qui est en ligne sur http://localhost)

**Stack :** WordPress + WooCommerce sur Apache local `/var/www/html`, thème enfant **compactbox** (Template: astra) + plugin **compactbox-core** (guard offline).
**Config WP :** `show_on_front=page`, `page_on_front=2` (« Accueil »), template `front-page.php`. Menus : location `primary`→id16, `compactbox-primary`→id17 (menu "CompactBox Menu" : Accueil, Shop, Câbles, Hubs, Stands, Contact).
**Base DB :** compactbox_db / user compactbox_user (accès via PHP `wp-load.php` — `wp` CLI bloqué, `sudo -S` bloqué).

**Sections en place (dans l'ordre) :** announce bar → pill header → hero (texte gauche, badge, CTA) → promo noir → New arrivals (4 produits avec images+prix) → tiles 2×2 → bande reviews → trust "Why CompactBox?" → footer (colonnes + badges paiement Visa/MC/PayPal/Twint 48×32).

**Fichiers clés :**
- `/var/www/html/wp-content/themes/compactbox/style.css` — **v1.3.x, 749 lignes** (design system Nomad complet)
- `front-page.php` (structure NOMAD avec promo/tiles/reviews inline)
- `template-parts/` : header.php, header-branding.php, header-navigation.php, header-utilities.php, header-mobile-toggle.php, hero.php, products.php, collections.php (encore utilisé ? NON — remplacé par tiles dans front-page.php), trust.php, footer.php, product-sections.php
- `assets/images/` : hero-nomad.jpg, collection-{cables.png, hubs.png, stands-scaled.jpg, supports.jpg, adapters.jpg, organizers.jpg, nomad.jpg, nomad.png}
- `wp-content/uploads/products/` : cable-usbc.png, hub-usbc.png, support-laptop.jpg, chargeur-gan.jpg (+ toutes tailles générées)

**Paiements footer :** Visa, Mastercard, PayPal, Twint (zahls.ch plan Beginners — cartes + Twint + PostFinance ; PostFinance pas encore dans les badges, à ajouter).

**Captures récentes :** /tmp/cb-tour25-final.png (dernier état complet), /tmp/nomad-haut-clean.png (référence header NOMAD), /tmp/cb-comp.png + /tmp/nomad-comp.png (comparaison côte à côte).

---

## 3. CE QUI RESTE À FAIRE (dans l'ordre convenu avec l'utilisateur)

**Décision utilisateur (18/09 soir) :** avancer sur le reste du site MAIS les nouvelles pages doivent être construites DIRECTEMENT dans le style Nomad existant (pas de rattrapage final). Les images produit viendront d'AliExpress (dropshipping) → remplacement en fin de parcours.

1. **Templates WooCommerce style Nomad** (prochaine étape immédiate) :
   - Page Shop (`archive-product`) : grille produits style cards NOMAD
   - Fiche produit (`single-product`) : layout Nomad (galerie gauche, infos droite, prix, CTA "Add to Cart" pilule noire)
   - Panier + checkout (une page, récapitulatif, LCD art. 3)
2. **Contenu des 4 produits** : descriptions FR enrichies, specs, stock, liens AliExpress (à fournir par l'utilisateur — PAS encore disponibles)
3. **zahls.ch** : plugin WooCommerce zahls.ch à installer/configurer (mode test d'abord — API key pas encore créée)
4. **EPIC-002** : multilingue FR/DE/EN/IT via Polylang + SEO (pas commencé)
5. **Pass final** : remplacement images AliExpress + alignement NOMAD complet (sections 5-10 de l'anatomie : bandeaux promo empilés, citation fondateur, communauté, best sellers, Explore/About)

**Questions ouvertes pour l'utilisateur (réponses données ou à re-confirmer demain) :**
- Images AliExpress : liens produits pas encore fournis → partir sur descriptions sans liens d'abord
- zahls.ch : compte pas encore créé → mode test d'abord
- Logo paiement PostFinance à ajouter au footer

---

## 4. OUTILS & COMMANDES VALIDÉS (ne pas redécouvrir)

- **Screenshot fiable :** Playwright (installé) — `from playwright.sync_api import sync_playwright; browser = p.chromium.launch(headless=True); page = browser.new_page(viewport={'width': 1280, 'height': 900}); page.goto('http://localhost/', wait_until='networkidle'); page.screenshot(path='...', full_page=True)` — scroll progressif avant capture pour déclencher lazy loading
- **NOMAD live :** Playwright avec `wait_until='domcontentloaded'` + `wait_for_timeout(4000)` (networkidle timeout) ; popup à fermer : `page.mouse.click(1113, 25)`
- **PHP CLI :** scripts dans /tmp/*.php avec `define('WP_USE_THEMES', false); require('/var/www/html/wp-load.php');` puis `php /tmp/script.php`
- **Réparer lookup WooCommerce :** `wc_get_product($pid)->save()` pour chaque produit
- **Attacher image produit :** copier dans uploads/ d'abord, puis `wp_insert_attachment` + `wp_generate_attachment_metadata` + `set_post_thumbnail`
- **Consulter code-master :** `hermes -p code_master -z "question" chat` (a validé le pattern Playwright)

---

## 5. DÉCISIONS UTILISATEUR (verbatim/paraphrase de la session)

1. « utilise la meme image que NOMAD pour l'instant. On remplacera par la suite » → placeholders = vraies images NOMAD
2. « Typographie Gotham impossible sans licence → utilise quelque chose de ressemblant » → Montserrat
3. « Le haut de la page d'accueil n'est pas identique !! La surface blanche avec écrit NOMAD à gauche » → pill header + announce bar construits
4. « il faut bien comprendre les sections du site NOMAD (par exemple "New for iPhone 18" est une zone pour les produits mis en avant ponctuellement) » → étude nomadgoods_analysis.json faite, structure recopiée
5. « Ces images viendront probablement d'aliexpress puisqu'on fait du dropship. Pense-tu que nous devrions avancer avec le reste du site puis à la fin corriger ce qui n'est pas encore aligné » → OUI avancer, MAIS nouvelles pages direct dans le style Nomad
6. Gauntlet-loop : ~21 tours effectués au total (5+5+5+3+3) — l'utilisateur itère jusqu'à satisfaction

## 5. NE PAS FAIRE / PIÈGES

- NE PAS utiliser google-chrome --headless pour les captures (tronque) → Playwright uniquement
- NE PAS recréer de doublons CSS header (anciens blocs `.cb-header__brand` etc. restants = supprimés le 18/09, garder le style pill unique)
- NE PAS modifier les fichiers via `wp` CLI ou `sudo -S` (bloqués) → PHP direct avec wp-load.php
- NE PAS redemander le contexte utilisateur : tout est dans ce handoff + mémoire
- Les images `collection-hubs.png` et `collection-cables.png` ont de la TRANSPARENCE (produit seul) — invisibles sur fond blanc → utiliser pour fonds sombres uniquement
- hero-nomad.jpg = photo cuir iPhone NOMAD ; collection-nomad.png = dérivé — tout remplacer par des images CompactBox réelles plus tard

## 6. HORS PÉRIMÈTRE TECHNIQUE (rappel)

- Assurance RC professionnelle + inscription raison individuelle : en attente côté utilisateur (pause du 12/09)
- Choix du nom final + domaine .ch : à faire (actuellement "CompactBox" de travail)
- Production finale : WooCommerce chez Infomaniak, paiement zahls.ch