# Handoff — SwissArbitrage Company

**Société** : [NOM À DÉFINIR — voir propositions §Brainstorm]
**Session** : 2026-09-11
**Agent** : Hermes Agent (CEO piloté par IA)
**Opérateur** : Cyril Olivieri, Suisse (Europe/Zurich)

---

## 1. Goal

Créer et lancer une boutique e-commerce suisse d'arbitrage électronique (accessoires compacts) entre AliExpress et concurrents suisses (Digitec/Galaxus). Side-business strict, CA plafonné à CHF 100k/an pour rester hors TVA obligatoire, dropshipping-first avec toggle mode classique.

---

## 2. Current State

### ✅ Done
- [verified] Brief lu et compris (source : `00_admin/brief/2026-09-11_admin_brief_swiss-arbitrage.md`)
- [verified] Structure de dossier complète créée sous `swissArbitrage-company/` (10 dossiers + archive)
- [verified] Brief déplacé + `guardrails.yaml` extrait dans `80_automation/rules/`
- [verified] Skill `swissarbitrage-classification` créé et installé
- [verified] `CLASSIFICATION_RULES.md` à la racine (naming convention ISO 8601 + vocabulaire contrôlé)
- [verified] Template produit §10 créé : 20 fichiers pré-nommés dans `10_products/_template/`
- [verified] Script de vérification `automation_check-swissarbitrage.py` + wrapper `.sh` dans `80_automation/scripts/`
- [verified] Symlink `check-swissarbitrage.sh` à la racine
- [verified] Cron quotidien `swissarbitrage-classification-check` (job ID `17be1828a4cd`) à 17h00 CET
- [verified] `README.md` à la racine documentant structure + règles

### 🔄 In Progress / Blocked
- **Nom de marque + domaine** : brainstorming non conclu (voir §Brainstorm)
- **Plateforme e-commerce** : recommandation faite (WooCommerce chez Infomaniak) mais décision opérateur non prise
- **Adresse légale de contact** : opérateur a confirmé qu'il en a une mais n'a pas encore communiqué
- **Plan d'exécution détaillé** : non encore écrit

---

## 3. Decisions Made

| Décision | Raison |
|----------|--------|
| Workspace structuré en 10 dossiers numérotés | Recommandé par sources e-commerce (Spark Shipping, Filently, Ecommerce Paradise) + brief §10 |
| Naming convention ISO 8601 + vocabulaire contrôlé | Standards ISO 15489/8601, NARA, Records Express |
| Template produit §10 avec 20 fichiers pré-nommés | Brief impose qu'aucun SKU ne va en live sans dossier complet |
| Skill dédié `swissarbitrage-classification` | Réutilisable, centralisé, vérifiable par cron |
| Script de vérification Python + cron quotidien | Automatisation anti-anarchie, silencieux si OK |
| Check silencieux sur stdout si OK | Évite le bruit — seules les violations sont signalées |

## 4. Rejected Alternatives

| Alternative | Rejetée car |
|-------------|-------------|
| Shopify | ~600$/an vs ~60 CHF/an pour WooCommerce — le brief dit "side business, do not design for scale" |
| Custom code from scratch | Reverse engineering API AliExpress = fragile, 2-4 semaines de dev, contre ToS |
| Structure de dossier sans dossier `80_automation/` | Les scripts agent-CEO ne sont pas des automations du site — ils sont le système nerveux de l'entreprise (MindStudio, Inventory Source) |
| Nommage sans date prefix | ISO 8601 obligatoire pour tri chronologique automatique (Filently, Sortio) |

---

## 5. Brainstorm — Nom de marque + Domaine

Le brief §4 impose : minimal, pas gadget-hype, pas brand-heavy, pas marketplace-styled.

### Propositions (à valider / rejetter par l'opérateur)

| Nom | .ch | Sentiment | Note |
|-----|-----|-----------|------|
| **connectbox** | à vérifier | Neutre, pro | Court, fonctionne en FR/DE/EN/IT |
| **techbase** | à vérifier | Sobre, suisse | "Base" = fondamental, pas hype |
| **linkstore** | à vérifier | Direct | "Store" = e-commerce clair, un peu commun |
| **essentialtech** | à vérifier | Premium | Un peu long |
| **coreaccessory** | à vérifier | Descriptif | Peut-être trop long |

**GO attendu** : choix final + vérification disponibilité .ch

---

## 6. Next Steps (ordre)

1. **Décider plateforme** — WooCommerce recommandé, GO opérateur nécessaire
2. **Décider nom + domaine** — choisir parmi brainstorm ou proposer nouveau
3. **Obtenir adresse légale** — pour compliance LCD art. 3 suisse
4. **Écrire plan détaillé** — basé sur les 8 gates du brief §18, sauvegardé dans `00_admin/brief/plan.md`
5. **Démarrer Gate 1** — confirmation scope, plateforme, langues, paiement
6. **Démarrer Gate 2** — product discovery : crawler Digitec/Galaxus pour cables/hubs/stands ≥ CHF 25

---

## 7. Suggested Skills for Next Agent

- `swissarbitrage-classification` — obligatoire avant chaque création de fichier
- `webapp-testing` — pour tester le site e-commerce une fois construit
- `hermes-agent` — si besoin de configurer des bots Hermes Desktop supplémentaires
- `gauntlet-loop` — pour la vérification qualité des livrables critiques

---

## 8. Artefacts

| Path | Description |
|------|-------------|
| `swissArbitrage-company/` | Workspace complet |
| `00_admin/brief/2026-09-11_admin_brief_swiss-arbitrage.md` | Brief source (stratégie AI) |
| `CLASSIFICATION_RULES.md` | Naming convention + vocabulaire contrôlé |
| `README.md` | Documentation structure + règles |
| `80_automation/rules/guardrails.yaml` | Règles machine-readable extraites du brief |
| `80_automation/scripts/automation_check-swissarbitrage.py` | Script de vérification classification |
| `10_products/_template/` | 20 fichiers template dossier produit §10 |
| `check-swissarbitrage.sh` | Symlink accès rapide vérification |
| Cron `17be1828a4cd` | Check quotidien 17h00 CET |

---

## 9. Informations sensibles (non incluses)

- Adresse de contact légale de l'opérateur (à communiquer)
- Token/credentials (non créés à ce stade)

---

*Handoff généré le 2026-09-11. Session à reprendre au point : décision plateforme + nom de marque.*
