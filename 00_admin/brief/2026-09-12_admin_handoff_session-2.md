# Handoff — SwissArbitrage Company (Session 2)

**Société** : [NOM À DÉFINIR — voir §Brainstorm]
**Session** : 2026-09-12
**Agent** : Hermes Agent (CEO piloté par IA)
**Opérateur** : Cyril Olivieri, Suisse (Europe/Zurich)

---

## 1. Goal

Créer et lancer une boutique e-commerce suisse d'arbitrage électronique (accessoires compacts) entre AliExpress et concurrents suisses (Digitec/Galaxus). Side-business strict, CA plafonné à CHF 100k/an pour rester hors TVA obligatoire, dropshipping-first avec toggle mode classique.

---

## 2. Current State

### ✅ Décisions validées (Session 2)
- [verified] **Plateforme** : WooCommerce auto-hébergé chez Infomaniak (~5-10 CHF/mois)
- [verified] **Paiement** : zahls.ch (cartes + Twint + PostFinance), plan Beginners (0 CHF/mois)
- [verified] **Structure juridique** : Raison individuelle suisse + assurance RC professionnelle (→ Sàrl si CA > CHF 100k/an)
- [verified] **Nom de marque** : "swisstechbase" REJETÉ (risque LCD art. 3 — indication trompeuse sur l'origine)

### 🔄 In Progress / Blocked
- **Nom de marque + domaine .ch** : à re-rechercher (voir §Brainstorm)
- **Assurance RC professionnelle** : opérateur doit l'acquérir avant reprise
- **Adresse légale** : à communiquer pour compliance LCD art. 3
- **Plan d'exécution détaillé** : validé en draft, en attente de validation opérateur

---

## 3. Décisions validées (Session 2)

| Décision | Raison |
|----------|--------|
| WooCommerce @ Infomaniak | ~10× moins cher que Shopify, data en Suisse, SEO natif |
| zahls.ch (plan Beginners) | 0 CHF/mois, Twint inclus, plugin WooCommerce gratuit |
| Raison individuelle + assurance RC | Protection suffisante pour side-business CHF 100k max, coût ~CHF 1-2k/an |
| Rejet "swisstechbase" | Risque élevé LCD art. 3 — suggestion trompeuse d'origine suisse |
| Pas de Sàrl à ce stade | CHF 10k+/an disproportionné sur un CA max CHF 100k |
| Pas de Wyoming LLC | Aucune protection juridique en Suisse, conçu pour business global/Thaïlande |

## 4. Rejected Alternatives (Session 2)

| Alternative | Rejetée car |
|-------------|-------------|
| swisstechbase | Analyse LexForge : risque élevé LCD art. 3 (tromperie sur origine) |
| Wyoming LLC | Protection juridique inexistante en Suisse, conçu pour résidence Thaïlande |
| Sàrl Suisse | CHF 10k+/an = 10-20% du CA max, mange la marge brute |
| Particulier sans inscription | Patrimoine exposé sans limite, pas de protection du nom |
| Stripe seul | Pas de Twint suisse, indispensable pour le marché CH |

---

## 5. Brainstorm — Nom de marque + Domaine (Session 2)

Le brief §4 impose : minimal, pas gadget-hype, pas brand-heavy, pas marketplace-styled.
**Contrainte ajoutée** : éviter les préfixes géographiques (Swiss, Suisse, CH) pour échapper au risque LCD art. 3.

### Propositions (à valider / rejetter par l'opérateur)

| Nom | .ch | Sentiment | Note |
|-----|-----|-----------|------|
| **connectbox** | à vérifier | Neutre, pro | Court, fonctionne en FR/DE/EN/IT |
| **techbase** | déjà pris | Sobre, suisse | "Base" = fondamental, pas hype |
| **corebox** | à vérifier | Court, technique | Évoque l'essentiel |
| **linkbase** | à vérifier | Neutre, pro | Connectivité sans prétention |
| **essentialaccessory** | à vérifier | Descriptif | Un peu long mais clair |

**GO attendu** : choix final + vérification disponibilité .ch

---

## 6. Prochaines étapes (ordre)

1. **Acquérir assurance RC professionnelle** → pause active du projet
2. **Choisir nouveau nom + domaine .ch** — sans préfixe géographique
3. **Obtenir adresse légale** — pour compliance LCD art. 3 suisse
4. **Inscrire raison individuelle au registre du commerce**
5. **Écrire plan détaillé** — basé sur les 8 gates du brief §18
6. **Démarrer Gate 1** — confirmation scope, plateforme, langues, paiement
7. **Démarrer Gate 2** — product discovery : crawler Digitec/Galaxus

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
| `00_admin/brief/2026-09-11_admin_handoff_session-1.md` | Handoff session 1 |
| `00_admin/brief/2026-09-12_admin_handoff_session-2.md` | Ce fichier |
| `00_admin/brief/2026-09-11_admin_plan.md` | Plan d'exécution détaillé (8 gates) |
| `CLASSIFICATION_RULES.md` | Naming convention + vocabulaire contrôlé |
| `README.md` | Documentation structure + règles |
| `80_automation/rules/guardrails.yaml` | Règles machine-readable extraites du brief |
| `80_automation/scripts/automation_check-swissarbitrage.py` | Script de vérification classification |
| `10_products/_template/` | 20 fichiers template dossier produit §10 |
| `check-swissarbitrage.sh` | Symlink accès rapide vérification |
| Cron `17be1828a4cd` | Check quotidien 17h00 CET |
| `~/lexforge/Etudes/12092026_SwissTechBase_ArbitrageElectronique/` | Note juridique LexForge (risques nom + structure) |
| `~/lexforge/Etudes/11092026_StructureEcommerceGlobalPOD/` | Note comparative structures (LexForge) |

---

## 9. Informations sensibles (non incluses)

- Adresse de contact légale de l'opérateur (à communiquer)
- Token/credentials (non créés à ce stade)
- Décision assurance RC (en cours)

---

## 10. Pause active

**Projet en pause** jusqu'à acquisition assurance RC professionnelle.
Reprise au point : choix nom de marque + domaine .ch + inscription raison individuelle.

---

*Handoff généré le 2026-09-12. Session à reprendre après acquisition assurance RC.*
