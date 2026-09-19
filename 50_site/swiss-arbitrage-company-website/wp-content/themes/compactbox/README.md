# CompactBox — Astra Child Theme

Child theme Astra minimal pour le storefront CompactBox.

## Activation

1. Vérifier que le thème parent Astra est présent dans `wp-content/themes/astra/`.
2. Copier ou versionner ce dossier dans `wp-content/themes/compactbox/`.
3. Dans l'admin WordPress : **Apparence > Thèmes**, activer **CompactBox**.

## Structure

```text
wp-content/themes/compactbox/
├── style.css              # En-tête thème + reset CSS mobile-first + header + hero + products + footer + fiche produit
├── functions.php          # Bootstrap enfant : enqueue CSS/JS + header hook + hero hook + footer hook + hooks fiche produit + JSON-LD
├── woocommerce/
│   └── single-product.php # Override WooCommerce pour la fiche produit structurée (.cb-product)
├── template-parts/
│   ├── header.php         # Assemblage du header sticky
│   ├── header-branding.php
│   ├── header-navigation.php
│   ├── header-utilities.php
│   ├── header-mobile-toggle.php
│   ├── hero.php           # Hero pleine largeur homepage (front-page only)
│   ├── collections.php    # Grille des 6 familles produit homepage (front-page only)
│   ├── products.php       # Carrousel des 8 nouveaux produits WooCommerce homepage (front-page only)
│   ├── product-sections.php # Sections repliables fiche produit : specs, compatibilité, contenu boîte, FAQ
│   ├── trust.php          # Section storytelling confiance homepage (front-page only)
│   └── footer.php         # Footer multi-colonnes site-wide (toutes les pages publiques)
├── assets/
│   ├── js/
│   │   └── compactbox.js  # Header hamburger toggle + product carousel controls
│   └── images/
│       ├── hero-placeholder.png        # 1920x1080, image de remplacement du hero
│       └── collections/                # 6 placeholders 800x600 pour les familles
│           ├── cables.png
│           ├── hubs.png
│           ├── stands.png
│           ├── supports.png
│           ├── adapters.png
│           └── organizers.png
├── screenshot.png         # Capture 1200x900 pour la galerie de thèmes
└── README.md              # Ce fichier
```

## Contraintes

- Ne jamais modifier les fichiers du thème parent Astra ni ceux de WordPress / des plugins.
- Toutes les personnalisations doivent passer par ce thème enfant (hooks, CSS overrides, template-parts).
- Les assets sont versionnés avec `filemtime()` pour le cache-busting.
- PHP : style PSR-12 inspiré avec préfixe `compactbox_` pour les fonctions.
- CSS : mobile-first, noms de classes BEM-like.
- JS : vanilla JS uniquement, pas de framework lourd.
- Header : assemblé via le hook Astra `astra_header_before` ; le menu `compactbox-primary` est enregistré sous **Apparence > Menus**.
- Hero : rendu via le hook Astra `astra_content_before`, uniquement sur la page d’accueil (`is_front_page()`). Pour remplacer l’image, écraser `assets/images/hero-placeholder.png` (format paysage 16:9, recommandé 1920×1080). Pour modifier le texte/CTA, éditer `template-parts/hero.php`.
- Collections : rendues via le hook Astra `astra_content_before` avec priorité 15, uniquement sur la page d’accueil, juste en dessous du hero. La grille est responsive (2 colonnes mobile, 3 tablette, 4 desktop). Les 6 familles et leurs descriptions sont dans `template-parts/collections.php`. Pour remplacer les images, écraser les fichiers 800x600 dans `assets/images/collections/`.
- Produits : rendus via le hook Astra `astra_content_before` avec priorité 20, uniquement sur la page d’accueil, juste en dessous de la grille collections. Le template part `template-parts/products.php` interroge `wc_get_products()` pour retourner les 8 produits publiés les plus récents (`orderby=date`, `order=DESC`) et affiche un carrousel accessible avec image, nom, prix et lien. Si aucun produit n’existe, un message factuel d’état vide est affiché. La largeur des cartes est responsive : 50 % mobile (2 visibles), 33.333 % tablette (3 visibles), 25 % desktop (4 visibles). Un placeholder CSS neutre remplace l’image manquante. Pour modifier le nombre ou l’ordre des produits, ajuster le tableau passé à `wc_get_products()` dans `template-parts/products.php`.
- Confiance : rendue via le hook Astra `astra_content_before` avec priorité 25, uniquement sur la page d’accueil, juste en dessous du carrousel produits. Le template part `template-parts/trust.php` affiche trois blocs factuels (Livraison suisse, Prix comparés, Sans compromis) avec icônes SVG inline, titres et descriptions. La grille est empilée sur mobile et passe à trois colonnes sur desktop. Le contenu est traduisible via `__()` et modifiable directement dans `template-parts/trust.php`.
- Footer : rendu via le hook Astra `astra_footer_before` avec priorité par défaut (10), **sur toutes les pages front-end publiques** (pas de garde `is_front_page()`). Le template part `template-parts/footer.php` affiche quatre blocs : identité vendeur (nom et adresse légale avec placeholders traduisibles), liens service et légal (CGV, Privacy, Delivery, Returns, Contact, How to Order), icônes SVG inline des moyens de paiement acceptés (Visa, Mastercard, PayPal, Twint) et placeholder de sélecteur de langue FR/DE/EN/IT. Les URLs des liens boutique utilisent `wc_get_page_permalink()` quand WooCommerce est actif, sinon `home_url('/shop/')`. La grille passe d’une colonne empilée sur mobile à deux colonnes sur tablette et quatre colonnes sur desktop. Les liens sont accessibles au clavier et respectent `prefers-reduced-motion: reduce`.
- Panier : le compteur utilise `WC()->cart` quand WooCommerce est actif ; sinon, un placeholder est affiché sans fatal error.
- Sélecteur de langue : placeholder FR/DE/EN/IT réservé pour Polylang (EPIC-002).

## Fiche produit structurée

- **Override WooCommerce** : `woocommerce/single-product.php` remplace le template simple-produit par défaut d’Astra. Il appelle `get_header('shop')` et `get_footer('shop')`, puis délègue le rendu WooCommerce à `woocommerce_content()` dans une balise `.cb-product` / `.cb-product__layout`. Astra et le plugin WooCommerce restent inchangés.
- **Disposition** : en mobile (< 768 px), la galerie et le résumé s’empilent verticalement ; le bouton « Add to cart » reste visible dans le premier viewport grâce à l’ordre flex. En desktop (≥ 1024 px), une grille CSS affiche la galerie à gauche et les détails à droite, le résumé étant `sticky` sous le header.
- **Sections repliables** : `template-parts/product-sections.php` est injecté après le formulaire d’ajout au panier via le hook `woocommerce_single_product_summary` priorité 25. Quatre sections sont rendues : Specs, Compatibility, Box contents, et FAQ. Chaque section lit le meta produit correspondant (`compactbox_specs`, `compactbox_compatibility`, `compactbox_box_contents`, `compactbox_faq`) ; si le meta est vide, des placeholders neutres et traduisibles sont affichés. L’accordion est contrôlé par `assets/js/compactbox.js` (vanilla JS, ARIA `aria-expanded` + attribut `hidden`) et respecte `prefers-reduced-motion: reduce`.
- **JSON-LD Schema.org** : `compactbox_single_product_json_ld()` est accroché à `wp_head`. Il ne s’exécute que sur `is_singular('product')`, uniquement si WooCommerce est actif, et uniquement si le produit a un prix numérique positif. Le script injecté contient `@context`, `@type: Product`, `name`, `image`, `description`, `url`, et `offers` avec `priceCurrency: CHF`, `price`, `availability` et `url`.
- **Classes body** : `compactbox_single_product_body_class()` ajoute `cb-product-page` au `body_class` sur les fiches produit pour scoper le CSS sans toucher aux autres templates.
- **Personnalisation du contenu** : pour remplacer les placeholders par du contenu réel, renseigner les meta produit listés ci-dessus (une entrée par ligne) ou modifier directement les listes par défaut dans `template-parts/product-sections.php`. L’image de remplacement utilisée pour le JSON-LD et les produits sans galerie est `assets/images/hero-placeholder.png`.

## Personnalisation du footer

- **Adresse légale / nom / e-mail** : définir les options `compactbox_legal_address`, `compactbox_brand_name` et `compactbox_contact_email` dans la base WordPress (`wp_options`) ou via un plugin de settings ; en l’absence de valeur, le footer affiche des placeholders traduisibles.
- **Icônes de paiement** : modifier les SVG inline dans `template-parts/footer.php`. N’ajouter que des moyens de paiement effectivement supportés.
- **Liens légaux** : adapter les slugs `home_url('/cgv/')`, `home_url('/privacy/')`, `home_url('/contact/')` et `home_url('/how-to-order/')` selon les pages réelles du site.
