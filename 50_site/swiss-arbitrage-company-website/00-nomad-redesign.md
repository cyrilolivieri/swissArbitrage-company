---
name: compactbox-nomad-redesign
---

# CompactBox — Analyse UX Nomad + Redesign Familles Produit

## 1. Analyse UX Nomad Goods (homepage ch)

Nomad Goods est un site e-commerce premium d'accessoires tech, construit sur Shopify, avec une esthétique "lifestyle dark". Les patterns clés :

### Header
- Logo centré ou gauche, navigation par grandes catégories (iPhone, Apple Watch, Bands, Accessories, Power, Cases...)
- Mega-menu visuel avec images de catégories
- Recherche discrète, compte, panier
- Langue / pays en bas de page plutôt qu'en header

### Hero
- Full-width, image ou vidéo immersive
- Titre court, storytelling événementiel ("iPhone 18 is here")
- Un seul CTA fort
- Alternance hero promotionnel + hero produit

### Grids / Collections
- Grille de cards visuelles avec image + label court
- Split layouts (50/50) pour scénarios
- Carrousels horizontaux de produits (best-sellers, nouveautés)

### Storytelling confiance
- Citation fondateur
- Reviews / 5 stars
- Communauté Instagram
- Matériau / savoir-faire (Horween leather)
- Stats marque ("14 years of quality goods")

### Footer
- Multi-colonnes : shop, support, company, social
- Identité vendeur, paiements, newsletter

## 2. Adaptation CompactBox

CompactBox ne peut pas copier Nomad (marque premium cuir), mais peut reprendre l'**architecture des sections** et l'**équilibre visuel riche** que tu as demandé (moins minimaliste).

### Structure homepage proposée

1. **Announcement bar** : livraison suisse, prix en CHF
2. **Header** : logo CompactBox + navigation par famille + recherche + panier + sélecteur langue (FR/DE/EN/IT)
3. **Hero** : scénario d'usage tech suisse, image produit, CTA "Shop [famille phare]"
4. **Category grid** : 6 familles en cards visuelles
5. **Best-sellers carousel** : 4 SKU MVP
6. **Trust section** : "Prix comparés · Livraison suisse · Sans compromis"
7. **Scenario split section** : bureau / mobile / voyage
8. **Reviews / social proof** (placeholder)
9. **Footer multi-colonnes** : liens légaux, contact, paiements, identité vendeur

## 3. Familles de produit redéfinies pour CompactBox

À partir des prix concurrents extraits et des patterns Nomad, voici 6 familles cohérentes pour un MVP compact-accessories suisse :

| # | Famille | Description | Exemple SKU | Prix concurrent CHF | Prix CompactBox cible |
|---|---------|-------------|-------------|---------------------|-----------------------|
| 1 | **Câbles USB-C** | Charge + données, 1-2m, 60-100W, tressé/nylon | Câble USB-C 2m 100W | 18-27 | 14.90 |
| 2 | **Hubs USB-C** | 4-7 ports, compact aluminium, PD pass-through | Hub 4 ports USB-C | 20-50 | 16.90 |
| 3 | **Stands laptop** | Aluminium pliable, ajustable, portable | Stand laptop alu | 24-48 | 19.90 |
| 4 | **Supports téléphone / tablette** | Bureau, voiture, lit, bureau (pas de bras mécanique complexe) | Support bureau phone | 15-35 | 12.90 |
| 5 | **Adaptateurs USB-C** | HDMI, DisplayPort, USB-A, SD, voyage | Adaptateur USB-C HDMI | 20-40 | 16.90 |
| 6 | **Organisateurs câbles / accessoires bureau** | Clips, serre-câbles, range-câbles, souris pad compact | Kit organisateur câbles | 8-20 | 9.90 |

**Exclusions maintenues** : chargeurs GaN (risque sécurité/CE/AVS), batteries, écouteurs, smartphones, souris, produits à fort risque firmware.

## 4. Navigation par famille (inspirée de Nomad)

- **Shop by category** en homepage (grid visuel)
- Menu header : Câbles · Hubs · Stands · Supports · Adaptateurs · Organisateurs
- Chaque famille = page archive WooCommerce + landing page storytelling

## 5. Implications pour le brief/PRD/spec

- Remplacer "chargeur GaN" par "organisateurs câbles / accessoires bureau"
- Ajouter des exemples de SKU et prix cibles
- Préciser la structure homepage section par section
- Maintenir le thème enfant CompactBox, mais corriger le chemin WordPress

## 6. Décision chemin thème enfant

WordPress ne scanne pas les sous-dossiers de `wp-content/themes/`. Deux options standards :

- **Option A** : `wp-content/themes/compactbox/` avec `Template: astra` dans style.css → thème enfant nommé CompactBox, propre
- **Option B** : `wp-content/themes/astra-child/` → nom générique, déjà partiellement en place

**Recommandation** : Option A (`wp-content/themes/compactbox/`). C'est plus propre, le thème porte le nom de la marque, et ça évite la confusion avec un dossier sous Astra.
