# SwissArbitrage Company

Agent-CEO powered electronics arbitrage business — Swiss market only.

## Quick Start

```bash
# Check workspace classification
./check-swissarbitrage.sh

# Full classification rules
see CLASSIFICATION_RULES.md
```

## Folder Structure

| Folder | Purpose |
|--------|---------|
| `00_admin/` | Brief, branding, contracts, legal, SOPs |
| `10_products/` | Product dossiers (_template, candidates, live, rejected) |
| `20_suppliers/` | Supplier data, SKU mapping, purchase orders |
| `30_finance/` | Pricing, stress-tests, revenue tracking, reports |
| `40_assets/` | Product photos, marketing visuals |
| `50_site/` | Web frontend, dashboard UI, SEO |
| `60_orders/` | Customer service, fulfillment, inventory, returns |
| `70_customers/` | Customer data (LPD/GDPR sensitive) |
| `80_automation/` | Agent-CEO scripts: crawlers, monitoring, scoring, reporting |
| `90_archive/` | Yearly archives |

## Naming Convention

- **Date prefix**: `YYYY-MM-DD` (ISO 8601)
- **Separators**: `-` between words, `_` between fields
- **No spaces, no special chars**: `~ ! @ # $ % ^ & * ( ) ; : < > ? , { } ' " |`
- **Controlled domains**: admin, product, supplier, finance, asset, site, order, customer, automation, legal
- **Example**: `2026-09-11_product_cable_usbc-2m-100w/`

## Product Dossier (§10)

Every product in `10_products/live/` must have all 20 files from the `_template/`. No exceptions.

## Automation Layer

The `80_automation/` folder contains the agent-CEO's operational scripts:
- `crawlers/` — Price monitoring (Digitec, Galaxus, AliExpress)
- `monitoring/` — Margin drift, revenue ceiling alerts
- `scoring/` — Product scoring algorithm (§9)
- `reporting/` — Weekly reports generation (§20)
- `scripts/` — Utility scripts including classification checker

## Governance

- **Revenue ceiling**: CHF 100,000/year (soft alert CHF 70k, hard alert CHF 90k)
- **Pricing rules**: ≥20% undercut vs competitor, ≥50% gross margin after landed costs
- **Pull-only acquisition**: Google Search + Google Shopping, no social ads
- **Dropshipping-first** with toggle to classic stocked mode
- **VAT threshold**: Stay below CHF 100k to remain outside mandatory Swiss VAT

## Legal Compliance

- Swiss LCD art. 3 obligations: identity, contract steps, error correction, order confirmation email
- No general right of withdrawal under Swiss law — returns policy must reflect actual capability
- Price indication transparency per SECO rules

## Classification Enforcement

A daily check runs at 17:00 CET to verify:
- Files are in allowed directories
- Filenames follow ISO 8601 + controlled vocabulary
- Product dossiers in `live/` are complete (20 files)
- Scripts follow `domain_descriptor.py` pattern

Run manually: `./check-swissarbitrage.sh`
