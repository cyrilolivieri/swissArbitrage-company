# SwissArbitrage Classification Rules
# Source of truth for file placement and naming in swissArbitrage-company

## Principes

- ISO 8601 dates: `YYYY-MM-DD` prefix mandatory
- No spaces — `-` between words, `_` between fields
- No special chars: `~ ! @ # $ % ^ & * ( ) ; : < > ? . , { } ' " |`
- Lowercase default; controlled vocab in UPPER if needed
- Max 50 chars when possible
- Version: `_v1`, `_v2` for drafts; no suffix = final

## Controlled Vocabulary — Domain (closed list)

`admin`, `product`, `supplier`, `finance`, `asset`, `site`, `order`, `customer`, `automation`, `legal`

## Controlled Vocabulary — Status (optional)

`draft`, `final`, `archived`, `rejected`

## Naming Patterns

| Type | Pattern | Example |
|------|---------|---------|
| Business / legal doc | `YYYY-MM-DD_domain_descriptor[_status].ext` | `2026-09-11_legal_privacy-policy_final.md` |
| Product dossier (§10) | `YYYY-MM-DD_product_family_sku-name/` | `2026-09-11_product_cable_usbc-2m-100w/` |
| Internal product file | `NN_descriptor_type.ext` | `01_family.md`, `05_competitor.md` |
| Script / bot | `domain_descriptor.py` | `crawler_digitec-prices.py` |
| Visual asset | `YYYY-MM-DD_asset_type_descriptor[_vN].ext` | `2026-09-11_asset_photo_cable-hero_v1.jpg` |
| Weekly report | `YYYY-MM-DD_report_wNN_descriptor.ext` | `2026-09-11_report_w37_margins.md` |
| Config / data | `domain_descriptor.yaml\|json` | `guardrails.yaml` |
| SOP | `sop_descriptor[_status].ext` | `sop_order-processing_v2.md` |

## Folder Structure

```
swissArbitrage-company/
├── 00_admin/      → brief, branding, contracts, legal, sops
├── 10_products/   → _template, candidates, live, rejected
├── 20_suppliers/  → primary, backup, purchase-orders, sku-mapping
├── 30_finance/    → accounting, pricing, reports, revenue-tracking, stress-tests
├── 40_assets/     → marketing, product-photos
├── 50_site/       → frontend, dashboard-ui, seo
├── 60_orders/     → customer-service, fulfillment, inventory, returns
├── 70_customers/  → customer data (LPD/GDPR sensitive)
├── 80_automation/ → rules, skills, memory, monitoring, crawlers, scoring, reporting, scripts
└── 90_archive/    → yearly archives
```

## Product Dossier §10 Checklist (20 items)

Each product dossier in `10_products/` must contain ALL 20 files:

1. `01_family.md` — product family
2. `02_candidate-name.md` — exact candidate name
3. `03_competitor.md` — Swiss competitor + URL + price + date
4. `04_competitor-shipping.md` — competitor shipping/fees
5. `05_supplier-primary.md` — primary supplier URL + cost + shipping + quality
6. `06_supplier-backup.md` — backup supplier URL + cost + shipping
7. `07_landed-cost.md` — estimated all-in landed cost
8. `08_selling-price.md` — proposed selling price CHF
9. `09_shipping-presentation.md` — shipping presentation to customer
10. `10_undercut-proof.md` — proof of 20% undercut (calculation)
11. `11_margin-proof.md` — proof of 50% gross margin (calculation)
12. `12_stress-test.md` — stress-test result
13. `13_weight-dimensions.md` — weight and dimensions
14. `14_return-risk.md` — return-risk assessment
15. `15_compatibility-risk.md` — compatibility-risk assessment
16. `16_compliance-safety.md` — compliance/safety note
17. `17_support-burden.md` — expected support burden
18. `18_keywords.md` — primary and secondary search-intent keywords
19. `19_seo-meta.md` — proposed title, meta title, meta description, structured data
20. `20_verdict.md` — verdict: launch / test / reject
