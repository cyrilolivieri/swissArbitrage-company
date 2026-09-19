# CompactBox — Site e-commerce suisse

## 1. Contexte et mission

**Société** : SwissArbitrage-company  
**Projet** : `swiss-arbitrage-company-website`  
**Nom de marque temporaire** : CompactBox (peut changer avant mise en ligne)  
**Modèle** : arbitrage électronique (sourcing AliExpress → vente en Suisse), side-business semi-automatique, CA plafonné CHF 100k/an  
**Mode d’autonomie** : semi-auto — GO demandé avant actions coûteuses, destructrices ou en ligne.

**Mission** : concevoir et construire un storefront WooCommerce suisse, multilingue, inspiré de l’architecture UX de [Nomad Goods](https://nomadgoods.com/ch/), pour vendre une gamme restreinte d’accessoires électroniques compacts avec un arbitrage de prix clair, un dropshipping discipliné et une conformité LCD art. 3 suisse.

---

## 2. Non-négociables

1. Marché **Suisse uniquement** ; prix en CHF partout.
2. **Side-business** : pas de design pour la scale. Catalogue réduit et maîtrisé.
3. Plafond de revenus **CHF 100k/an** pour rester hors TVA obligatoire en distance selling (alertes douces à CHF 70k et CHF 90k).
4. Prix client final **≥ 20% moins cher** que le concurrent suisse (Digitec/Galaxus) toutes charges visibles comprises.
5. **Marge brute ≥ 50%** après coût produit réaliste (prix fournisseur, shipping, marge erreur/defect, frais de paiement).
6. Acquisition **pull-only** : Google Search + Google Shopping. Pas de pub sociale, pas de storytelling de marque.
7. Dropshipping-first avec bascule manuelle possible vers le mode classique (stock local).
8. Aucun produit ne va en ligne sans un dossier §10 complet.
9. Exclusions strictes : batteries, écouteurs, smartphones, souris, produits à fort risque ergonomique/sécurité/firmware, imitations de marque.
10. Conformité **LCD art. 3** : identité vendeur, étapes contractuelles, correction d’erreurs, confirmation email.

---

## 3. Catalogue MVP (6 familles validées)

| # | Famille | Motif de sélection |
|---|---------|---------------------|
| 1 | **Câbles USB-C** | Charge + données, 1-2m, 60-100W, tressé/nylon. Demande visible, faible retour, clair en SEO |
| 2 | **Hubs USB-C** | 4-7 ports, compact aluminium, PD pass-through. Besoin laptop/home-office, marge confortable |
| 3 | **Stands laptop** | Aluminium pliable, ajustable, portable. Aucune électronique, faible risque, compact |
| 4 | **Supports téléphone / tablette** | Bureau, voiture, lit. Usage évident, faible taux de retour |
| 5 | **Adaptateurs USB-C** | HDMI, DisplayPort, USB-A, SD, voyage. Requête de comparaison forte, SKU simples |
| 6 | **Organisateurs câbles / accessoires bureau** | Clips, serre-câbles, range-câbles, kit bureau. Bas prix, add-on, faible support |

**Exclus du MVP** : chargeurs GaN (risque sécurité/CE/AVS disproportionné pour un side-business).

---

## 4. Benchmark UX

Référence principale : **Nomad Goods** (`https://nomadgoods.com/ch/`).  
Patterns à répliquer et adapter :

- **Header** : logo CompactBox, navigation par famille de produits (Câbles, Hubs, Stands, Supports, Adaptateurs, Organisateurs), champ de recherche discret, panier, sélecteur de langue.
- **Hero** : pleine largeur, visuel scénario d'usage tech suisse, CTA unique vers famille phare.
- **Category grid** : 6 familles en cards visuelles avec image et label court.
- **Best-sellers carousel** : max 4 produits MVP en carrousel horizontal.
- **Trust storytelling** : "Prix comparés", "Livraison suisse", "Sans compromis".
- **Scenario split section** : bureau / mobile / voyage.
- **Footer** : identité vendeur, liens légaux, livraison/retours, paiement, réseaux sociaux.

Contrainte : le design doit rester **sobre, suisse et informatif** — pas gadget-hype, pas market-place, pas brand-heavy.

---

## 5. Stack technique validée

| Couche | Choix | Raison |
|--------|-------|--------|
| CMS/Store | WordPress + WooCommerce | SEO natif, maîtrise hébergement, plugins dropshipping matures |
| Hébergement cible | Infomaniak | Hébergeur suisse, ~5–10 CHF/mois |
| Thème | Astra parent + thème enfant `compactbox` dans `wp-content/themes/compactbox/` | Léger, personnalisable, nom de marque propre, compatible Woo |
| Multilingue | Polylang for WooCommerce | FR/DE/EN/IT, URLs propres, hreflang |
| Dropshipping | AliNext Lite (gratuit) | Import AliExpress, forwarding de commandes |
| Tracking | Advanced Shipment Tracking | Sync tracking AliExpress → client |
| Marges | Cost of Goods for WooCommerce | Suivi du coût produit et de la marge |
| Paiement | zahls.ch | Cartes + Twint + PostFinance, plan Beginners 0 CHF/mois |

---

## 6. Langues et marché

- **Langues** : FR (primaire), DE, EN, IT.
- **Pays de vente** : Suisse uniquement (CH).
- **Devise** : CHF, position right_space.
- **Unités** : kg / cm.

---

## 7. Contraintes légales suisses (LCD art. 3)

Le site doit exposer, de manière claire et complète :

- **Identité et coordonnées du vendeur** (nom, adresse physique, email).
- **Étapes techniques de conclusion du contrat** : parcours “Comment commander”.
- **Moyen de détecter et corriger les erreurs** avant validation de la commande.
- **Confirmation de commande par email sans délai**.

Pages obligatoires : CGV, Politique de confidentialité, Livraison, Retours et remboursements, Contact (identité vendeur), Comment commander.

---

## 8. Priorisation

1. **Storefront / design** (premier) — navigation, hero, grid produits, fiches produits, pages légales.
2. **Multilingue + SEO** — hreflang, titres, meta, schema.org Product, FAQ.
3. **Paiement zahls.ch** — intégration WooCommerce en mode test.
4. **Dropshipping + tracking + marges** — AliNext Lite, AST, Cost of Goods.
5. **Dashboard opérateur + plafond TVA** — compteur de CA, alertes 70k/90k, marge par SKU.

---

## 9. Livrables attendus

1. `01-brief/brief.md` — ce document.
2. `02-prd/prd.md` — exigences produit détaillées.
3. `03-spec/SPEC.md` — spécification technique du build.
4. `04-architecture/ARCHITECTURE-SPINE.md` — architecture et flux.
5. `HANDOFF-TO-OPENCODE.md` — commande de reprise pour OpenCodeBMAD.

---

## 10. Questions résolues / supposées

| Question | Réponse |
|----------|---------|
| Plateforme | WordPress + WooCommerce |
| Paiement | zahls.ch (cartes, Twint, PostFinance) |
| Multilingue | Polylang for WooCommerce, FR/DE/EN/IT |
| Dropshipping | AliNext Lite gratuit + forwarding semi-auto |
| Backup supplier | Oui, 2ème compte/fournisseur AliExpress par SKU |
| Notifications | Email uniquement |
| Site avant GO live | Guard offline : HTTP 503 + noindex par défaut |
| Hébergement | Infomaniak (migration différée post-assurance RC) |

Points encore bloquants pour le online : assurance RC professionnelle, inscription raison individuelle, adresse légale de contact, achat du domaine `compactbox.ch`.

---

## 11. Métriques de succès

- 6 familles de produits live avec marge ≥ 50% et sous-cotation ≥ 20%.
- 4 langues fonctionnelles sur toutes les pages publiques.
- Commande test complète (parcours, paiement test, email de confirmation).
- Dashboard opérateur affichant CA rolling 12 mois + alertes.
- Site non indexé (503/noindex) jusqu’à GO opérateur.

---

*Brief BMAD — généré le 2026-09-17.*
