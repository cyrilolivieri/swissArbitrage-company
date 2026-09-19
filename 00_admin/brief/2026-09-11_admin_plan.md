# SwissArbitrage Company — Plan d'exécution détaillé

**Basé sur** : brief §18 (8 gates) + §19 (10 questions de décision)
**Plateforme recommandée** : WooCommerce auto-hébergé chez Infomaniak (~5-10 CHF/mois)
**Langues** : FR (obligatoire), DE, EN, IT
**Nom de marque** : [TBD — voir brainstorm dans handoff]
**Adresse légale** : [TBD — à communiquer par l'opérateur]
**Date** : 2026-09-11
**Statut** : Draft — en attente de validation opérateur

---

## Résumé des Gates

| Gate | Objectif | Livrable |
|------|----------|----------|
| Gate 1 | Confirmation scope, plateforme, langues, paiement | Document de confirmation |
| Gate 2 | Discovery produits (câbles, hubs, stands) | Shortlist SKU avec prix concurrents |
| Gate 3 | Vetting fournisseurs AliExpress | Primary + backup par SKU |
| Gate 4 | Finalisation pricing + dossiers §10 | 20 dossiers complets avec stress-test |
| Gate 5 | Construction site + dashboard + automation | Site fonctionnel, non publié |
| Gate 6 | SEO + Google Shopping feed | Copy + structured data par SKU |
| Gate 7 | Pilot launch | SKUs live, dropshipping ON |
| Gate 8 | Itération hebdo | Monitoring, delisting, ajustements |

---

## Gate 1 — Scope Confirmation

### Objectif
Valider avec l'opérateur les décisions bloquantes avant tout travail technique.

### Questions à confirmer (§19)

1. **Plateforme** : WooCommerce chez Infomaniak ? (recommandé, ~5-10 CHF/mois)
2. **Langues** : FR + DE + EN + IT — confirmé ✓
3. **Paiement** : Stripe (cartes) + Twint (Suisse) ?
4. **Shipping mode classique** : Swiss Post ?
5. **Automation dropshipping** : AliDropship (plugin WooCommerce ~40$ one-time) ?
6. **Nom + domaine** : choisir parmi brainstorm (connectbox, techbase, linkstore, etc.)
7. **Catégorie produit** : ajouter/supprimer parmi §4.1 ?
8. **Adresse légale** : à communiquer pour LCD art. 3
9. **Refund reserve** : 5% centralisée par défaut ?
10. **Reporting** : dashboard-only + alertes auto ?

### Livrable
Document `2026-09-11_admin_scope-confirmation.md` signé par l'opérateur.

### Critère de passage
GO explicite de l'opérateur sur les 10 points.

---

## Gate 2 — Product Discovery

### Objectif
Identifier 20+ SKU candidates sur Digitec/Galaxus avec prix ≥ CHF 25, puis matcher sur AliExpress.

### Familles prioritaires (§22)
1. Câbles et adaptateurs simples (USB-C, HDMI)
2. Hubs USB-C compacts
3. Supports laptop passifs pliants
4. Trackers Bluetooth (si faible friction)
5. Chargeurs compacts (scrutiny strict)

### Méthode
1. Crawler Digitec/Galaxus pour chaque famille
2. Extraction prix, ratings, URLs
3. Matching AliExpress pour chaque SKU
4. Filtrage : prix AliExpress ≤ 50% concurrent suisse
5. Shortlist avec verdict launch/test/reject

### Livrable
`10_products/candidates/` rempli de dossiers produit partiels (points 1-6 du §10).

### Critère de passage
20 SKU avec competitor URL + AliExpress URL + prix observé.

---

## Gate 3 — Supplier Vetting

### Objectif
Assigner primary + backup supplier pour chaque SKU retenu.

### Critères (§6)
- Store rating très élevé
- Product rating + volume reviews significatif
- Order volume / historique prouvé
- Fulfillment speed rapide
- Shipping method trackable vers Suisse
- Return/refund posture acceptable
- Product spec match (cross-check reviews)
- Stock consistency
- Safety/conformity signals (chargeurs)
- Backup listing disponible

### Livrable
Dossiers candidates mis à jour avec `05_supplier-primary.md` + `06_supplier-backup.md`.

---

## Gate 4 — Pricing + Dossier Finalization

### Objectif
Calculer pricing, stress-test, scoring §9, assembler dossiers complets.

### Calculs obligatoires (§7 + §8)
- Undercut : prix final ≤ concurrent × 0.80
- Marge brute : (net - landed) / net ≥ 0.50
- Stress-test : supplier cost +10%, shipping +20%, refund reserve non-zero
- Score sur 100 (A:25, B:20, C:20, D:15, E:10, F:10)

### Seuils
- ≥ 85 → launch
- 70-84 → test
- < 70 → reject

### Livrable
20 dossiers complets dans `10_products/candidates/`, puis migration vers `live/` ou `rejected/`.

### Critère de passage
GO explicite de l'opérateur sur la shortlist.

---

## Gate 5 — Site Build

### Objectif
Construire le site WooCommerce avec toutes les pages obligatoires (§11) + dashboard (§13) + automation (§14).

### Pages obligatoires
- Home
- Catalog / collections
- Pages produit individuelles
- Shipping info
- Returns / refunds
- Contact + identité vendeur
- Terms and conditions
- Privacy policy
- Order confirmation flow
- Dashboard opérateur

### Dashboard opérateur (§13)
- Toggle dropshipping ON/OFF (store + per order)
- Revenue ceiling monitoring (70k soft, 90k hard)
- Margin monitoring per SKU/order
- Supplier health monitoring
- Compliance nudges

### Automation dropshipping (§14)
- Order forwarding AliExpress automatique
- Capture supplier cost, tracking, ETA
- Failover backup supplier
- Hold + notify si rupture ou margin spike

### Livrable
Site fonctionnel en local/staging, non publié.

---

## Gate 6 — SEO + Feed

### Objectif
Générer copy SEO + Google Shopping feed pour chaque SKU live.

### Par SKU (§15.2)
- Product title (FR/DE/EN/IT)
- Meta title + meta description
- H1 + description structurée
- FAQ block si nécessaire
- Schema.org Product JSON-LD
- hreflang multilingue

### Query mapping
- 1 query transactionnelle primaire
- 3 queries transactionnelles secondaires
- 1 query comparaison
- 1 query objection-handling (FAQ)

### Livrable
Feed Google Shopping + pages produit SEO-optimisées.

---

## Gate 7 — Pilot Launch

### Objectif
Mettre en live uniquement les SKUs ≥ 85 pts, dropshipping ON par défaut.

### Actions
- Activer nom de domaine
- Configurer paiement (Stripe + Twint)
- Publier pages produit
- Soumettre feed Google Shopping
- Activer automation AliExpress
- Monitorer premières commandes

### Livrable
Site live, CA tracking actif.

---

## Gate 8 — Itération Hebdo

### Objectif
Monitorer, ajuster, delister si nécessaire.

### Metrics (§20)
- Rolling revenue vs 100k ceiling
- Per-SKU realized margin
- Per-SKU undercut vs competitor
- Defect / refund rate
- Supplier delivery time
- SKU rule violations
- Competitor price changes

### Actions récurrentes
- Recompute marges réalisées
- Delist si margin < 50% sur fenêtre glissante
- Promote backup supplier si primary dégrade
- Ne jamais étendre le catalogue au-delà de la discipline de monitoring

---

## Calendrier indicatif

| Semaine | Gate | Focus |
|---------|------|-------|
| S1 | Gate 1 | Confirmation scope + plateforme |
| S2 | Gate 2 | Product discovery (20 SKU candidates) |
| S3 | Gate 3 | Supplier vetting |
| S4 | Gate 4 | Dossier finalization + GO opérateur |
| S5-S6 | Gate 5 | Site build + dashboard + automation |
| S7 | Gate 6 | SEO + Google Shopping feed |
| S8 | Gate 7 | Pilot launch |
| S9+ | Gate 8 | Itération hebdo |

---

## Hypothèses en attente

- [ ] Plateforme confirmée (WooCommerce recommandé)
- [ ] Nom de marque + domaine choisis
- [ ] Adresse légale communiquée
- [ ] Payment providers confirmés
- [ ] Budget hébergement accepté

---

*Plan draft — à valider par l'opérateur avant exécution.*
