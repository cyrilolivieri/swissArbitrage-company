# CompactBox — Architecture Spine

## 1. Vue d’ensemble

L’architecture vise un **storefront WooCommerce minimal, maîtrisé et conforme**, avec une couche custom légère pour les règles métier suisses (plafond TVA, marge, offline guard).  
Les briques lourdes (dropshipping, paiement, multilingue) sont déléguées à des plugins éprouvés ; le code custom se concentre sur l’orchestration et la surveillance.

---

## 2. Philosophie architecturale

1. **Plugins pour le métier lourd** : WooCommerce, Polylang, AliNext Lite, zahls.ch, AST, Cost of Goods.
2. **Thème enfant pour le design** : Astra parent intact, customisation isolée dans `wp-content/themes/compactbox/`.
3. **Plugin custom minimal** : uniquement pour les règles propres à CompactBox (offline guard, revenue ceiling, dashboard, order hooks).
4. **Semi-auto par défaut** : l’opérateur garde la main sur les décisions critiques (forwarding, override dropshipping).
5. **Locale-first** : tout est développé/testé sur localhost avant migration Infomaniak.

---

## 3. Schéma d’architecture

```text
┌──────────────────────────────────────────────────────────────┐
│                     Navigateur client                        │
└──────────────────────┬───────────────────────────────────────┘
                       │ HTTPS (production) / HTTP (local)
┌──────────────────────▼───────────────────────────────────────┐
│                    Apache 2.4                                │
└──────────────────────┬───────────────────────────────────────┘
                       │
┌──────────────────────▼───────────────────────────────────────┐
│  WordPress + WooCommerce + Polylang for WooCommerce         │
│  ─────────────────────────────────────────────────────────  │
│  Astra parent theme                                         │
│  └── CompactBox child theme in `wp-content/themes/compactbox/` (UI/UX, layout, assets)         │
│  Plugins dropshipping/paiement/marges/tracking              │
│  └── AliNext Lite | zahls.ch | AST | Cost of Goods | SEO    │
│  Plugin custom : CompactBox Core                            │
│  ├── OfflineGuard.php      (503 + noindex)                 │
│  ├── RevenueMonitor.php    (plafond CHF 100k)                │
│  ├── OperatorDashboard.php (widget admin + page)           │
│  └── OrderHooks.php        (hold/fallback/log)               │
└──────────────────────┬───────────────────────────────────────┘
                       │
┌──────────────────────▼───────────────────────────────────────┐
│  MariaDB 10.11                                              │
│  ├── Tables WP/WC standards                                  │
│  └── Méta custom compactbox_*                                │
└──────────────────────────────────────────────────────────────┘

Externes :
  AliExpress  ←→ AliNext Lite / extension Chrome
  zahls.ch    ←→ plugin REST gateway
  Google      ←→ sitemap, structured data, shopping feed
```

---

## 4. Décisions architecturales clés (ADRs)

### ADR-001 : WordPress/WooCommerce vs Shopify

**Décision** : WordPress auto-hébergé chez Infomaniak.
**Motivation** : coût ~10× inférieur à Shopify, maîtrise des données en Suisse, SEO natif, plugin zahls.ch/Twint, plugin AliNext gratuit.
**Trade-off** : maintenance opérateur plus élevée ; compensée par un dashboard custom et documentation.

### ADR-002 : Astra + thème enfant vs thème sur mesure

**Décision** : Astra gratuit + thème enfant CompactBox.
**Motivation** : rapidité, compatibilité WooCommerce, possibilité de reproduire la structure UX Nomad Goods via overrides.
**Trade-off** : design légèrement contraint ; acceptable pour un MVP.

### ADR-003 : AliNext Lite (gratuit) vs DSers/AliDropship

**Décision** : AliNext Lite.
**Motivation** : gratuit, import produit, forwarding de commandes, compatible WooCommerce.
**Trade-off** : fonctionnalités limitées ; le plugin custom compensera pour le hold/fallback.

### ADR-004 : Polylang for WooCommerce vs WPML

**Décision** : Polylang for WooCommerce.
**Motivation** : plus léger, moins coûteux, URLs propres, hreflang automatique.
**Trade-off** : gestion des traductions produits manuelle ou via import CSV.

### ADR-005 : Plugin custom pour règles métier

**Décision** : plugin “CompactBox Core” séparé du thème.
**Motivation** : séparation des préoccupations ; le thème peut être changé sans perdre la logique métier.
**Trade-off** : un composant de plus à maintenir ; reste très petit (≈ 5 classes).

### ADR-006 : Mode offline par défaut

**Décision** : site en 503/noindex tant que `compactbox_site_online` n’est pas activé.
**Motivation** : éviter tout indexation Google prématurée et toute vente avant conformité totale.
**Trade-off** : nécessite une action opérateur pour go-live.

---

## 5. Structure du code

### Thème enfant `compactbox`

```
wp-content/themes/compactbox/
├── style.css
├── functions.php
├── screenshot.png
├── assets/
│   ├── css/
│   │   ├── compactbox-base.css
│   │   ├── compactbox-header.css
│   │   ├── compactbox-hero.css
│   │   ├── compactbox-product.css
│   │   └── compactbox-footer.css
│   ├── js/
│   │   └── compactbox-ui.js
│   └── images/
│       ├── logo.svg
│       └── favicon.png
└── template-parts/
    ├── hero.php
    ├── category-grid.php
    ├── best-sellers.php
    ├── trust-signals.php
    ├── scenario-split.php
    └── footer-widgets.php
```

### Plugin custom `compactbox-core`

```
wp-content/plugins/compactbox-core/
├── compactbox-core.php
├── includes/
│   ├── class-plugin.php
│   ├── class-offline-guard.php
│   ├── class-revenue-monitor.php
│   ├── class-operator-dashboard.php
│   └── class-order-hooks.php
├── admin/
│   ├── views/
│   │   └── dashboard.php
│   ├── css/
│   │   └── admin.css
│   └── js/
│       └── admin.js
├── languages/
│   └── compactbox-core.pot
└── readme.txt
```

---

## 6. Flux de données

### 6.1 Chargement d’une page produit

```
Requête /fr/produit/cable-usb-c-2m-100w/
  → Polylang détecte la langue (fr)
  → WooCommerce récupère le produit
  → Cost of Goods ajoute le coût (backend)
  → Thème enfant affiche galerie, specs, FAQ
  → Hook injecte schema.org Product JSON-LD
  → Header envoie hreflang pour fr/de/en/it
```

### 6.2 Passage d’une commande

```
Checkout submit
  → WooCommerce crée la commande (status: pending)
  → zahls.ch redirige vers paiement
  → Retour paiement OK (status: processing)
  → OrderHooks vérifie marge réalisée
  → Si OK : statut reste processing, notification opérateur pour forwarding
  → Si KO : statut on-hold, email opérateur avec options
```

### 6.3 Forwarding AliExpress

```
Opérateur clique “Forward to AliExpress” dans l’admin
  → AliNext Lite ouvre la commande fournisseur avec adresse client
  → Opérateur confirme le paiement AliExpress (semi-auto)
  → Tracking récupéré via AliNext ou saisi manuellement
  → AST met à jour la commande et notifie le client
  → OrderHooks logue coût réel + tracking
```

### 6.4 Revenue ceiling

```
Chaque nuit / toutes les 4h
  → RevenueMonitor calcule le CA des 365 derniers jours
  → Si ≥ 70k : alerte dashboard + email soft
  → Si ≥ 90k : alerte hard + blocage nouvelles commandes (pending)
```

---

## 7. Points de vigilance

| Risque | Mitigation |
|--------|------------|
| Plugin AliNext Lite gratuit limité | Documenter limites, prévoir fallback manuel |
| Fournisseurs AliExpress volatiles | Backup par SKU, surveillance prix/stock |
| Marge cassée en cours de commande | OrderHooks + hold + notification opérateur |
| Dérive TVA CHF 100k | RevenueMonitor + alertes 70k/90k |
| Conformité LCD art. 3 | Pages légales, récapitulatif checkout, email confirmation |
| Sécurité WP | Mises à jour auto, mot de passe fort, 2FA, backups |
| Performance | Cache objet/page, images WebP, lazy loading |

---

## 8. Intégrations externes

| Système | Données | Protocole |
|---------|---------|-----------|
| AliExpress | Produits, commandes, tracking | Extension Chrome / interface web AliNext |
| zahls.ch | Paiements, statuts | Plugin WooCommerce REST |
| Google Merchant Center | Feed XML | Fichier statique `compactbox-shopping.xml` |
| Google Search Console | Sitemap, indexation | `/sitemap_index.xml` + robots.txt |

---

## 9. Environnements

| Environnement | Localhost | Infomaniak (cible) |
|---------------|-----------|--------------------|
| URL | http://localhost | https://compactbox.ch |
| WP_DEBUG | true | false |
| Site online | no | no jusqu’à GO |
| Paiement | zahls.ch test | zahls.ch live |
| Dropshipping | test/manual | live/manual |

---

## 10. Conventions et standards

- PHP : PSR-12, namespaces `CompactBox\Core`.
- CSS : mobile-first, variables CSS pour couleurs/espacements.
- JS : vanilla, dépendances minimales.
- Noms de hooks : préfixe `compactbox_`.
- Méta données : préfixe `_compactbox_`.
- Options site : préfixe `compactbox_`.
- Pas de modifications de core WP/WC/plugin tiers.

---

*ARCHITECTURE-SPINE — générée le 2026-09-17.*
