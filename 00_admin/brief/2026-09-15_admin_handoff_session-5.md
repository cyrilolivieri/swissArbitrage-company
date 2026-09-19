# Handoff Session 5 — SwissArbitrage/CompactBox

**Date:** 2026-09-15
**Session ID:** 20260915_154400
**Status:** En pause — utilisateur absent
**Mode:** Semi-auto

---

## Décisions validées

- **Nom marque** : CompactBox
- **Domaine** : compactbox.ch (libre, à réserver)
- **Stack** : LAMP classique (Apache + MariaDB + PHP 8.3 + WordPress + WooCommerce)
- **Thème** : Astra + thème enfant "CompactBox" activé
- **Produits** : 4 familles retenues (câble USB-C, stand laptop, hub USB-C, chargeur GaN)

---

## Ce qui tourne

### Localhost
- WordPress + WooCommerce actif sur http://localhost
- Thème enfant "CompactBox" activé (CSS personnalisé dans `/var/www/html/wp-content/themes/astra-child/`)
- 4 produits WooCommerce créés avec prix CHF
- Pages légales créées : Livraison, Retours, CGV, Confidentialité, Contact, Comment commander
- Permalinks activés (`/%postname%/`)

### Données concurrentielles
- 18 produits Digitec/Galaxus extraits avec prix (dans `competitor_prices.json`)
- 4 familles passent les seuils de marge (≥50%) et sous-cotation (≥20%)
- Coûts AliExpress estimés collectés pour les 4 familles

---

## En cours — bloqué par utilisateur

### Design du site
L'utilisateur a rejeté les 3 directions visuelles proposées (Swiss Design Strict / Tech-Friendly / Minimal Warm) comme "trop minimalistes".

**Ce qui a été fait :**
- Analyse visuelle de sites leaders : UGREEN, Anker, Satechi, Nomad
- Identifié les patterns communs : hero banner produit, grid produits, catégories par usage, couleur marque forte

**Ce que l'utilisateur a demandé :** Des URLs de sites concurrents à succès pour s'en inspirer.

**URLs analysées et transmises :**
- https://www.ugreen.com — Header noir, storytelling par scénarios, stats marque
- https://www.anker.com — Header blanc, catégories par usage, avis pros, grid prix
- https://satechi.net — Épuré mais riche en images
- https://nomadgoods.com — Lifestyle tech, storytelling fort

**Prochaine étape attendue :** L'utilisateur revient, choisit quelle direction l'inspire, puis on construit le mockup HTML.

---

## Todo restant

| # | Tâche | Statut |
|---|---|---|
| sa-1 | Nom + domaine .ch | ✅ Terminé |
| sa-2 | Gate 2 : Product discovery Digitec/Galaxus | ✅ Terminé |
| sa-3 | Gate 3 : Vetting fournisseurs AliExpress | ⏳ En attente |
| sa-4 | Gate 4 : Pricing + stress-test + dossiers | ⏳ En attente |
| sa-5 | Gate 5 : Setup local WP/WooCommerce | 🔄 En cours (design pending) |
| sa-6 | Gate 6 : SEO + Google Shopping feed | ⏳ En attente |
| sa-7 | Handoff session | 🔄 Ce fichier |

---

## Fichiers importants

- `/home/cyril/swissArbitrage-company/00_admin/brief/2026-09-15_admin_handoff_session-4.md` — Session précédente
- `/home/cyril/swissArbitrage-company/10_products/candidates/competitor_prices.json` — Prix concurrents
- `/tmp/compactbox_directions.html` — 3 directions visuelles (rejetées par user)
- `/var/www/html/wp-content/themes/astra-child/style.css` — CSS personnalisé CompactBox
- `~/bin/wp` — WP-CLI utilisable

---

## Notes

- **Règle récente** : Après avoir posé une question, TOUJOURS attendre la réponse de l'utilisateur avant de continuer.
- **Contrainte design** : L'utilisateur veut du "moins minimaliste" que les 3 directions initiales. S'inspirer des leaders (UGREEN/Anker) qui ont du contenu riche, des sections, des storytelling.
- **Pas de GO nécessaire** pour la suite — l'utilisateur doit revenir et choisir sa direction visuelle.

---

**Prochain message attendu :** Retour de l'utilisateur avec son choix de direction visuelle ou demande d'approfondissement sur un site concurrent.
