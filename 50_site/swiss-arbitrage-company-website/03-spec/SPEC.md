# CompactBox — Spécification Technique (SPEC)

## 1. Périmètre

Ce SPEC couvre la construction du **storefront WooCommerce** et de ses briques opérationnelles pour le projet CompactBox.  
Il ne couvre pas le sourcing AliExpress, la comptabilité externe ni les démarches administratives (RC, assurance).

---

## 2. Stack et versions

| Composant | Détails |
|-----------|---------|
| OS serveur (local) | Linux Mint / Ubuntu-like, Apache 2.4, MariaDB 10.11, PHP 8.3 |
| WordPress | Dernière stable compatible PHP 8.3 |
| WooCommerce | Dernière stable |
| Thème parent | Astra (gratuit) |
| Thème enfant | `compactbox` dans `wp-content/themes/compactbox/` (Template: astra) |
| Multilingue | Polylang for WooCommerce |
| Dropshipping | AliNext Lite (gratuit) |
| Tracking | Advanced Shipment Tracking |
| Marges | Cost of Goods for WooCommerce |
| Paiement | zahls.ch plugin WooCommerce |
| SEO | Yoast SEO (ou Rank Math) + JSON-LD manuels si nécessaire |

---

## 3. Architecture fonctionnelle

```text
Visiteur
   │
   ▼
Apache / WordPress
   │
   ├── Astra parent + CompactBox child theme  ← UI, layout, couleurs, typographie
   │
   ├── WooCommerce
   │   ├── Catalog (products, categories, attributes)
   │   ├── Cart / Checkout
   │   ├── Orders
   │   └── Emails (WC_Email)
   │
   ├── Polylang for WooCommerce  ← translations, hreflang, URLs
   │
   ├── AliNext Lite  ← import supplier products, order forwarding
   ├── Advanced Shipment Tracking  ← tracking import
   ├── Cost of Goods  ← cost field + margin reporting
   └── zahls.ch  ← payment gateway
   │
   └── Plugin custom “CompactBox Core”
       ├── Offline guard (503 + noindex)
       ├── Revenue ceiling monitor
       ├── Dashboard operator widget
       └── Order hold / fallback logic hooks
```

---

## 4. Composants techniques détaillés

### 4.1 Thème enfant CompactBox

Dossier : `wp-content/themes/compactbox/`

Fichiers attendus :
- `style.css` — en-tête thème (`Template: astra`) + overrides CSS.
- `functions.php` — enqueue scripts/styles, actions/filtres custom.
- `screenshot.png` — screenshot thème 1200x900.
- `template-parts/` — hero, category-grid, trust-signals, footer-widgets.
- `assets/` — images logo/favicon, polices si besoin.

Contraintes UX :
- Header sticky, hauteur 70–90 px.
- Navigation principale : familles de produits (Câbles, Hubs, Stands, Supports, Adaptateurs, Organisateurs).
- Hero full-width, hauteur 60–80vh, visuel scénario d'usage tech suisse, CTA unique.
- Category grid : 6 cards visuelles, 2 colonnes mobile, 3-4 desktop.
- Best-sellers carousel horizontal : max 4 produits MVP.
- Scenario split section : bureau / mobile / voyage.
- Footer multi-colonnes.
- Boutons : border-radius modéré, palette noir/blanc + accent à définir.

### 4.2 Polylang for WooCommerce

- Langues : FR (default), DE, EN, IT.
- Settings : détection par langue dans URL (`/fr/`, `/de/`, `/en/`, `/it/`).
- Produits, catégories, pages légales, menus, widgets traduits.
- Emails transactionnels traduits via chaînes WooCommerce + `.mo` / Loco Translate.

### 4.3 AliNext Lite

- Utilisé en mode **semi-auto** : import de produits, mise à jour des prix/stock, forwarding de commandes.
- Mapping SKU WooCommerce ↔ AliExpress.
- Fournisseur primaire + backup par SKU (stocké en méta).
- Hook WooCommerce `woocommerce_order_status_processing` pour proposer forwarding.

### 4.4 Advanced Shipment Tracking (AST)

- Provider : “AliExpress Standard Shipping / Cainiao / Other”.
- Tracking number importé via AliNext ou saisi manuellement.
- Email de notification au client quand statut passe à “shipped”.

### 4.5 Cost of Goods for WooCommerce

- Champ `cost_of_goods` sur chaque produit/variante.
- Rapport marge par commande : `line_total - (qty * cost_of_goods)`.
- Dashboard widget marge par SKU.

### 4.6 zahls.ch

- Plugin officiel installé.
- Mode test activé jusqu’au GO live.
- Méthodes activées : cartes, Twint, PostFinance.

### 4.7 Plugin custom “CompactBox Core”

Dossier : `wp-content/plugins/compactbox-core/`

Fichiers :
- `compactbox-core.php` — en-tête plugin.
- `includes/class-offline-guard.php` — 503 + noindex si `compactbox_site_online != 'yes'`.
- `includes/class-revenue-monitor.php` — calcul rolling 12 mois, alertes 70k/90k.
- `includes/class-admin-dashboard.php` — widget admin + page “CompactBox”.
- `includes/class-order-hooks.php` — hold/fallback si marge cassée.
- `assets/admin.css`, `assets/admin.js`.

---

## 5. Spécification des pages

| Page | Type WP | Contenu | Langue |
|------|---------|---------|--------|
| Accueil | page template | Hero, collections, best-sellers, confiance, footer | FR/DE/EN/IT |
| Shop | page WooCommerce | Grille des produits, filtres par famille | FR/DE/EN/IT |
| Fiche produit | template WC | Galerie, specs, compatibilité, FAQ, CTA | FR/DE/EN/IT |
| Panier | page WC | Récap editable | FR/DE/EN/IT |
| Checkout | page WC | Facturation, livraison, récap, paiement | FR/DE/EN/IT |
| Comment commander | page | Étapes contractuelles (LCD art. 3) | FR/DE/EN/IT |
| Livraison | page | Délais, zones, coûts, transitaires | FR/DE/EN/IT |
| Retours et remboursements | page | Politique limitée, pas de droit de révocation général | FR/DE/EN/IT |
| Contact | page | Identité vendeur, formulaire, email | FR/DE/EN/IT |
| CGV | page | Conditions de vente | FR/DE/EN/IT |
| Confidentialité | page | Politique données | FR/DE/EN/IT |

---

## 6. Modèle de données custom

### Méta produit

| Clé | Type | Description |
|-----|------|-------------|
| `_compactbox_supplier_primary_url` | text | URL AliExpress fournisseur primaire |
| `_compactbox_supplier_backup_url` | text | URL AliExpress fournisseur backup |
| `_compactbox_competitor_url` | text | URL Digitec/Galaxus |
| `_compactbox_competitor_price` | decimal | Prix concurrent CHF |
| `_compactbox_stress_margin` | decimal | Marge au stress-test |
| `_compactbox_dossier_path` | text | Chemin dossier §10 |
| `_compactbox_score` | int | Score BMAD 0–100 |

### Option site

| Clé | Valeur par défaut | Description |
|-----|-------------------|-------------|
| `compactbox_site_online` | `no` | Site online (indexation autorisée) |
| `compactbox_dropshipping_default` | `yes` | Mode dropshipping par défaut |
| `compactbox_revenue_ceiling_soft` | `70000` | Alert soft CHF |
| `compactbox_revenue_ceiling_hard` | `90000` | Alert hard CHF |

### Méta commande

| Clé | Type | Description |
|-----|------|-------------|
| `_compactbox_dropshipping_override` | yes/no | Override mode par commande |
| `_compactbox_supplier_used` | text | Fournisseur utilisé |
| `_compactbox_supplier_order_id` | text | ID commande AliExpress |
| `_compactbox_supplier_cost` | decimal | Coût réel payé |
| `_compactbox_tracking_number` | text | Numéro tracking |
| `_compactbox_tracking_provider` | text | Transporteur |

---

## 7. Flux critiques

### 7.1 Parcours client standard

```
Accueil → Navigation → Fiche produit → Ajouter au panier → Panier
→ Checkout (facturation/livraison) → Récapitulatif → Paiement zahls.ch
→ Commande confirmée → Email WC → Tracking AST
```

### 7.2 Commande dropshipping

```
Paiement confirmé (status: processing)
  → CompactBox Core vérifie marge réalisée
  → Si OK : AliNext Lite propose forwarding vers fournisseur primaire
  → Opérateur valide (semi-auto) ou auto-si configuré
  → AliExpress expédie au client suisse
  → Tracking récupéré → AST → email client
  → Log commande mis à jour
```

### 7.3 Hold / fallback

```
Si marge cassée OU fournisseur primaire indisponible
  → Statut commande : on-hold
  → Email opérateur + notification dashboard
  → Propositions : backup supplier / refund / stock local / cancel
```

---

## 8. Points d’intégration

| Système externe | Données échangées | Méthode |
|-----------------|-------------------|---------|
| AliExpress | Produits, commandes, tracking | AliNext Lite + Chrome extension / API web |
| zahls.ch | Paiement, statut | Plugin WooCommerce REST |
| Google Shopping | Feed XML produits | Plugin ou script générant `compactbox-shopping.xml` |
| Google Search Console | Indexation | Sitemap + robots.txt |

---

## 9. Sécurité

- Mots de passe admin WordPress ≥ 20 caractères, 2FA si possible via plugin.
- Répertoire `wp-admin` non exposé sur URL prévisible.
- Credentials API stockés dans `wp-config.php` ou options chiffrées, jamais en versionnement.
- Pas de données bancaires stockées localement (PCI-DSS délégué à zahls.ch).

---

## 10. Tests d’acceptation

| ID | Test | Attendu |
|----|------|---------|
| T01 | Chargement homepage FR | Affichage hero + navigation + collections |
| T02 | Switch langue DE | URL `/de/`, contenu traduit, hreflang présent |
| T03 | Fiche produit avec prix CHF | Prix + shipping + specs visibles, schema.org présent |
| T04 | Ajout panier + checkout | Commande test sans erreur, récapitulatif affiché |
| T05 | Paiement test zahls.ch | Retour OK, statut processing |
| T06 | Email confirmation | Reçu dans les 60 s, contenu multilingue |
| T07 | Dashboard opérateur | CA rolling, alertes, marges visibles |
| T08 | Mode offline | Site renvoie 503/noindex quand `compactbox_site_online=no` |

---

## 11. Conventions de code

- PHP : PSR-12 simplifié, namespaces `CompactBox\Core`.
- CSS : BEM-like, mobile-first.
- JS : vanilla ou Astra hooks, pas de framework lourd.
- Commits : messages en anglais, scope explicite.
- Pas de modifications directes dans les plugins ; utiliser hooks/filtres et thème enfant.

---

*SPEC BMAD — généré le 2026-09-17.*
