# CompactBox — Product Requirements Document (PRD)

## 1. Objectif

Permettre à l’opérateur de SwissArbitrage de disposer d’un storefront WooCommerce suisse, multilingue, low-friction, dédié à la vente d’accessoires électroniques compacts en dropshipping, avec un design inspiré de Nomad Goods, une conformité LCD art. 3 et un contrôle strict des marges.

---

## 2. Users & jobs-to-be-done

| Rôle | Besoin principal | Critère de succès |
|------|------------------|---------------------|
| **Visiteur suisse** | Trouver rapidement un accessoire utile, comprendre le prix final, commander en confiance | Parcours ≤ 3 clics vers fiche produit, prix CHF clair, livraison/retours explicites |
| **Client** | Payer en toute sécurité, recevoir confirmation et suivi | Confirmation email immédiate, tracking récupéré automatiquement |
| **Opérateur** | Gérer catalogue, marges, commandes et plafond TVA sans coder | Dashboard clair, alertes automatiques, actions en 1 clic |
| **Moteur de recherche** | Indexer des pages produit structurées, multilingues, avec données de prix fiables | Schema.org Product, hreflang, meta optimisées, feed Google Shopping valide |

---

## 3. Exigences fonctionnelles

### 3.1 Storefront / UX

| ID | Exigence | Priorité |
|----|----------|----------|
| FR-01 | Header avec logo, navigation par famille de produits, recherche, sélecteur de langue, panier. | P0 |
| FR-02 | Hero pleine largeur avec visuel scénario d'usage tech suisse, CTA unique vers famille phare. | P0 |
| FR-03 | Category grid : 6 familles visuelles (Câbles, Hubs, Stands, Supports, Adaptateurs, Organisateurs). | P0 |
| FR-04 | Best-sellers carousel horizontal : max 4 produits MVP. | P1 |
| FR-05 | Trust storytelling : "Prix comparés", "Livraison suisse", "Sans compromis". | P1 |
| FR-06 | Scenario split section : bureau / mobile / voyage. | P1 |
| FR-07 | Fiche produit : galerie, titre SEO, prix CHF, shipping visible, specs, compatibilité, contenu boîte, FAQ, CTA. | P0 |
| FR-08 | Footer avec identité vendeur, liens légaux, paiements acceptés, sélecteur de langue. | P0 |

### 3.2 Catalogue

| ID | Exigence | Priorité |
|----|----------|----------|
| FR-09 | Catalogue limité aux 6 familles MVP : Câbles, Hubs, Stands, Supports, Adaptateurs, Organisateurs. | P0 |
| FR-10 | Chaque SKU live dispose d’un dossier produit §10 complet. | P0 |
| FR-11 | Prix et coût fournisseur saisis dans WooCommerce ; marge calculée. | P0 |
| FR-12 | Pas de chargeurs GaN dans le MVP. | P0 |
| FR-13 | Ajout/édition d’un SKU possible uniquement via validation marge ≥50% et sous-cotation ≥20%. | P1 |

### 3.3 Commande / Checkout

| ID | Exigence | Priorité |
|----|----------|----------|
| FR-13 | Panier avec récapitulatif editable (quantité, suppression). | P0 |
| FR-14 | Checkout en une page avec étapes : facturation, livraison, récapitulatif, paiement. | P0 |
| FR-15 | Étape “récapitulatif” obligatoire avant paiement pour détecter/corriger les erreurs (LCD art. 3). | P0 |
| FR-16 | Paiement via zahls.ch : cartes, Twint, PostFinance. | P0 |
| FR-17 | Confirmation de commande par email immédiate (transactionnelle, multilingue). | P0 |

### 3.4 Dropshipping

| ID | Exigence | Priorité |
|----|----------|----------|
| FR-18 | Mode dropshipping activé par défaut (toggle global et par commande). | P0 |
| FR-19 | Sur commande payée, création semi-automatique de la commande AliExpress vers le fournisseur primaire. | P0 |
| FR-20 | Si fournisseur primaire indisponible ou marge cassée, mise en attente avec notification opérateur + proposition backup/refund/stock/annulation. | P1 |
| FR-21 | Récupération automatique du numéro de tracking AliExpress et envoi au client. | P1 |
| FR-22 | Log commande : fournisseur utilisé, ID commande fournisseur, coût réel, frais de port, tracking, ETA. | P1 |

### 3.5 Multilingue

| ID | Exigence | Priorité |
|----|----------|----------|
| FR-23 | Site entièrement traduit en FR, DE, EN, IT. | P0 |
| FR-24 | URLs traduites et hreflang corrects. | P0 |
| FR-25 | Emails transactionnels traduits selon la langue de commande. | P1 |

### 3.6 SEO / Acquisition

| ID | Exigence | Priorité |
|----|----------|----------|
| FR-26 | Titre/meta/description par page et par produit. | P0 |
| FR-27 | Schema.org Product JSON-LD (prix, disponibilité, devise CHF, shippingDetails). | P0 |
| FR-28 | Feed Google Shopping valide (XML) avec prix, stock, shipping CH. | P1 |
| FR-29 | FAQ structurée par produit pour répondre aux objections compatibilité/livraison. | P1 |

### 3.7 Légal

| ID | Exigence | Priorité |
|----|----------|----------|
| FR-30 | Page “Comment commander” décrivant les étapes contractuelles. | P0 |
| FR-31 | Page Contact avec identité vendeur complète (nom, adresse, email). | P0 |
| FR-32 | CGV, Politique de confidentialité, Livraison, Retours et remboursements. | P0 |
| FR-33 | Mention “pas de droit général de révocation en Suisse” claire sur la page Retours. | P0 |

### 3.8 Dashboard opérateur

| ID | Exigence | Priorité |
|----|----------|----------|
| FR-34 | Compteur de revenus rolling 12 mois avec alertes à CHF 70k (soft) et CHF 90k (hard). | P0 |
| FR-35 | Marge brute réalisée par SKU et par commande. | P0 |
| FR-36 | Liste des commandes en attente / en cours / expédiées. | P0 |
| FR-37 | Toggle dropshipping global et override par commande. | P1 |
| FR-38 | Recommandation de dé-listing si marge rolling < 50%. | P2 |

---

## 4. Exigences non-fonctionnelles

| ID | Exigence | Cible |
|----|----------|-------|
| NFR-01 | Temps de chargement page produit | ≤ 3 s sur localhost, ≤ 2 s en production cible |
| NFR-02 | Score Lighthouse accessibilité | ≥ 90 |
| NFR-03 | Responsive | Mobile-first, desktop ≥ 1200 px |
| NFR-04 | SEO | Noindex global tant que site non validé ; bascule indexation via setting |
| NFR-05 | Sécurité | Mots de passe forts, pas de credentials en clair, pas d’accès admin exposé |
| NFR-06 | Backup | DB + uploads sauvegardés avant toute migration ou mise à jour critique |
| NFR-07 | Accessibilité | Labels ARIA, contrastes, navigation clavier |

---

## 5. Règles métier

| Règle | Description |
|-------|-------------|
| R01 | Aucun SKU ne peut être publié sans dossier §10 complet et validation scoring ≥ 70. |
| R02 | Prix client final = prix affiché + shipping client ; doit être ≤ 80% du prix concurrent tout compris. |
| R03 | Marge brute = (prix client − coût réel) / prix client ; doit être ≥ 50% en condition normale, ≥ 45% au stress-test. |
| R04 | Dropshipping ON par défaut ; opérateur peut forcer OFF par commande. |
| R05 | Si CA rolling 12 mois ≥ CHF 90k, bloquer les nouvelles commandes jusqu’à GO opérateur. |
| R06 | Aucune donnée client ne quitte l’UE/Suisse sans information conforme. |
| R07 | Pas de fausses reviews, fausses urgences, fausses ruptures de stock. |

---

## 6. Hypothèses et dépendances

- L’opérateur fournira l’adresse légale, le logo et la palette avant la mise en ligne.
- L’assurance RC professionnelle et l’inscription à la raison individuelle seront finalisées avant migration Infomaniak.
- Les fournisseurs AliExpress restent disponibles et respectent les coûts estimés.
- Le plugin zahls.ch WooCommerce est installable et fonctionnel en mode test sans contrat live.

---

## 7. Critères d’acceptation globaux

1. Le site affiche les 6 familles MVP en 4 langues.
2. Une commande test peut être passée jusqu’à la confirmation email en environnement local.
3. Le dashboard affiche CA rolling, marges et alertes.
4. Toutes les pages légales et le parcours “Comment commander” sont présents.
5. Le site est en mode offline (503/noindex) par défaut et passe online uniquement sur GO explicite.

---

*PRD BMAD — généré le 2026-09-17.*
