# Handoff — CompactBox → OpenCodeBMAD

## Objectif final

Construire le **storefront WooCommerce multilingue** du site CompactBox pour SwissArbitrage, avec :
- un design inspiré de l’architecture UX de [Nomad Goods](https://nomadgoods.com/ch/),
- un catalogue MVP de 6 familles de produits,
- la conformité LCD art. 3 suisse,
- un mode dropshipping-first semi-automatique,
- un suivi strict des marges et du plafond de TVA CHF 100k/an.

Le site reste en **mode offline (503 + noindex)** jusqu’à GO explicite de l’opérateur.

---

## Stack choisie

| Couche | Technologie |
|--------|-------------|
| CMS / e-commerce | WordPress + WooCommerce |
| Hébergement cible | Infomaniak (développement local pour l’instant) |
| Thème | Astra + thème enfant “CompactBox” |
| Multilingue | Polylang for WooCommerce (FR / DE / EN / IT) |
| Dropshipping | AliNext Lite (gratuit) |
| Tracking | Advanced Shipment Tracking |
| Marges | Cost of Goods for WooCommerce |
| Paiement | zahls.ch (cartes + Twint + PostFinance) |
| SEO | Yoast SEO ou Rank Math + JSON-LD + feed Google Shopping |
| Custom | Plugin “CompactBox Core” (offline guard, revenue ceiling, dashboard, order hooks) |

---

## Livrables amont BMAD

| Document | Chemin |
|----------|--------|
| Brief | `/home/cyril/swissArbitrage-company/50_site/swiss-arbitrage-company-website/01-brief/brief.md` |
| PRD | `/home/cyril/swissArbitrage-company/50_site/swiss-arbitrage-company-website/02-prd/prd.md` |
| SPEC | `/home/cyril/swissArbitrage-company/50_site/swiss-arbitrage-company-website/03-spec/SPEC.md` |
| Architecture | `/home/cyril/swissArbitrage-company/50_site/swiss-arbitrage-company-website/04-architecture/ARCHITECTURE-SPINE.md` |
| Epics & Stories | `/home/cyril/swissArbitrage-company/50_site/swiss-arbitrage-company-website/05-epics/stories.yaml` |

---

## Prochaine étape technique prioritaire

**Design storefront** : créer le thème enfant CompactBox sur Astra, construire la homepage, les grids de collections par famille, les fiches produit et le footer. Reproduire l’architecture UX de Nomad Goods (header sticky riche, hero full-width, grids, storytelling confiance, footer multi-colonnes). Le site doit rester offline par défaut (HTTP 503 + noindex) via l’option `compactbox_site_online=no`. Ne commencer ni le dropshipping avancé, ni le paiement live, ni le dashboard opérateur avant que le storefront de base et le multilingue ne soient fonctionnels.

---

## Commande exacte pour OpenCodeBMAD

```bash
cd /home/cyril/swissArbitrage-company/50_site/swiss-arbitrage-company-website && opencode run "bmad-build-auto"
```

---

## Rappels pour l’agent de build

- Lire d’abord le brief, le PRD, le SPEC, l’architecture et le stories.yaml ci-dessus.
- Garder le site en mode offline par défaut (`compactbox_site_online=no`).
- Ne publier aucun produit sans dossier §10 complet.
- Exclure les chargeurs GaN du MVP.
- Maintenir le plafond CHF 100k/an via le revenue monitor.
- Demander GO opérateur avant toute action destructive ou avant migration en ligne.

---

*Handoff mis à jour le 2026-09-17 — prêt pour OpenCodeBMAD.*
