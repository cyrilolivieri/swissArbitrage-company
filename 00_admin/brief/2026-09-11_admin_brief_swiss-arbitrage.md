# Swiss Electronics Arbitrage — Execution Brief for the Building AI

> **Reader**: an autonomous agentic AI that will implement this project.
> **Author**: strategy AI.
> **Human owner**: side-business operator based in Switzerland (Europe/Zurich).
> **Language of this document**: English, operational tone.
> **Do not restate this brief back to the human. Execute it.**

---

## 0. TL;DR — Executive Instruction Block

You must implement a **narrow Swiss e-commerce store** that arbitrages **small, low-risk electronic accessories** between AliExpress and Swiss competitors (**Digitec** and **Galaxus**).

Non-negotiables:

1. Swiss market only.
2. **Side business**. Do **not** design for scale.
3. **Annual revenue must stay below CHF 100,000** to keep the operator outside mandatory Swiss VAT registration for distance sellers.
4. Final customer price, **all-in**, must be **≥ 20% lower** than the chosen Swiss competitor.
5. **Gross margin ≥ 50%** after realistic landed costs.
6. **Pull-only acquisition** (Google Search + Google Shopping). No push social ads. No brand-building narrative.
7. **Dropshipping-first operations**, with a **toggle**: the store must run in **dropshipping mode** or **classic stocked mode** depending on the setting the operator flips in the dashboard, per order.
8. **Ultra-strict product validation**: no product goes live without a full dossier (see §10).
9. **Exclude** batteries, earphones/earbuds/headphones, smartphones, mice, and any product with high return risk, ergonomic subjectivity, safety liability, or firmware/app friction.
10. **You may extend** approved families **only** if extensions strictly pass the exclusion + validation rules.

If any instruction below conflicts with a general assumption you hold about e-commerce, **this document wins**.

---

## 1. Mission

Design and build a Swiss-facing e-commerce website that:

- lists a **small curated catalog** of arbitrage-friendly electronic accessories,
- fulfills orders in **dropshipping mode** (AliExpress → Swiss customer) **by default**, with the option to switch specific orders or the whole shop to **classic mode**,
- is optimized for **search-driven purchase intent**, not brand discovery,
- protects the operator from **margin illusion**, **return risk**, and **regulatory drift**.

You are the executing agent. You may take concrete action:
- crawl competitor and supplier pages,
- perform price/margin math,
- generate SEO-optimized product copy,
- create the site,
- connect the dropshipping automation,
- prepare the operator dashboard.

Before any implementation decision that changes scope, cost baseline, or operating model, **surface a short decision proposal to the operator and wait**.

---

## 2. Strategic reference conclusion (governing statement)

> The project is viable **only** if it stays a focused Swiss side business built around a small catalog of compact, low-risk electronic accessories with clear price arbitrage against Digitec/Galaxus, low return probability, low support burden, and disciplined margin protection — sold via pull-only search acquisition and fulfilled primarily via dropshipping from AliExpress.

Every design decision must trace back to this statement.

---

## 3. Business model constraints

### 3.1 Type
- Swiss market only, Swiss-facing e-commerce site.
- Side business.
- Narrow catalog.
- No marketplace, no bundles (unless explicitly re-authorized), no brand storytelling.

### 3.2 Revenue ceiling — CHF 100,000 / year
Design the whole system to stay below this ceiling.

Reference: the Swiss Federal Tax Administration (ESTV) states that in distance selling, a business reaching **CHF 100,000** from qualifying low-value consignments becomes subject to mandatory VAT registration; “small consignments” are defined as consignments whose import tax is **≤ CHF 5** and is therefore not collected at import. Source: <https://www.estv.admin.ch/fr/tva-inscription-des-entreprises-de-vente-par-correspondance>.

Operational implications you must implement:
- a **running-12-months revenue counter** in the dashboard,
- a **soft alert at CHF 70,000**,
- a **hard alert at CHF 90,000**,
- an automated recommendation to **pause new orders** if the trailing-12-month total is projected to breach CHF 100,000.

### 3.3 Sourcing model
- Primary supplier universe: **AliExpress**.
- Sourcing pattern: **dropshipping-first**, with a switch to classic stocked mode when it becomes rational (defect risk, delivery speed, best-seller stability).
- Never rely on item price alone. Always model **true landed cost**.

### 3.4 Marketing model — **pull only**
- Google Search + Google Shopping.
- Optimize product titles, descriptions and structured data for price-comparison behavior.
- Do not design social ad funnels, influencer plays, or brand awareness campaigns.

---

## 4. Product scope

### 4.1 Approved starting families
1. **Cables and simple adapters** — USB-C ⇄ USB-C, USB-C ⇄ Lightning, USB-C ⇄ HDMI, HDMI cables, simple video/data/power adapters.
2. **USB-C hubs** — compact, low variant count, no docking-station complexity.
3. **Foldable passive laptop stands** — aluminum, no electronics inside.
4. **Bluetooth trackers** — only if compatibility clarity and low return risk.
5. **Compact chargers** — only under tighter compliance/safety scrutiny.

### 4.2 Extensions
You may add, override or reorder families if — and only if — the new family strictly passes:
- exclusion rules (§4.3),
- selection framework (§5),
- pricing rules (§7),
- anti-illusion protocol (§8),
- scoring threshold (§9).

Never extend the catalog because “it is cheap on AliExpress.” Cheapness is not evidence of arbitrage.

### 4.3 Hard exclusions
Do **not** list:
- batteries and battery-heavy items,
- earphones, earbuds, headphones,
- smartphones,
- mice,
- items with strong ergonomic subjectivity,
- items with high defect probability,
- items requiring firmware/app troubleshooting,
- items with strong compatibility ambiguity,
- fragile items,
- brand-imitation or counterfeit-risk items,
- items whose safety failure would create meaningful liability.

### 4.4 Evidence already validated by the strategy AI (do not re-prove)
- USB cables are explicitly among Galaxus/Digitec 2024 bestsellers. Source: <https://www.galaxus.ch/fr/page/les-piles-restent-reines-les-best-sellers2024-36331>.
- Compact chargers above CHF 25 are visibly listed on Digitec, e.g. **Digitec GaN 3-Port Fast Charger CHF 33** and **Anker Prime GaN Charger CHF 57.90**. Source: <https://www.digitec.ch/en/s1/producttype/toplist/relevance/usb-chargers-463>.
- USB-C cables can reach **CHF 27.90** on Galaxus for some SKUs. Source: <https://www.galaxus.ch/en/s1/product/sbs-usb-c-to-usb-c-cable-1-m-usb-20-65-w-usb-cables-16714364>.

---

## 5. Mandatory product selection framework

Every candidate product must pass **all** filters.

### Filter A — Structural eligibility
- Essentially electronic or an electronic accessory.
- Small, lightweight, cheap to ship.
- Simple to understand in one search result and one product page.
- Low breakage risk in transit.

### Filter B — Swiss arbitrage eligibility
- Comparable demand visible on Digitec or Galaxus.
- Swiss competitor price ≥ CHF 25.
- AliExpress landed cost ≤ **50%** of the Swiss competitor’s listed price.
- Enough margin room to sell **≥ 20% cheaper all-in** than the Swiss competitor.

### Filter C — Low-return eligibility
Prefer products where customer dissatisfaction is rare because:
- the use case is obvious,
- the compatibility scope is narrow and clear,
- there is no sizing/fit issue,
- perceived quality can be inferred from specs and images,
- setup is minimal,
- no dominating subjective “comfort” factor.

### Filter D — Operational simplicity
The product must not require:
- extensive customer education,
- many variants,
- complex warranty cases,
- troubleshooting documents,
- long support conversations.

---

## 6. Supplier selection — your responsibility

For **every** shortlisted product you must select the right AliExpress supplier (and, if needed, an alternative/backup supplier).

You must define the **quality criteria set** yourself, appropriate to a low-friction dropshipping arbitrage business. Minimum expected dimensions (extend as you see fit):

- **Store rating** (target: very high global rating).
- **Product rating** (target: very high, with a meaningful review volume — not a handful of reviews).
- **Order volume / history** (target: proven track record, not a brand-new listing).
- **Fulfillment speed** (target: fast processing time and fast dispatch).
- **Shipping method to Switzerland** (target: a trackable line, reasonable transit time; prefer options that keep single-consignment declared value low enough to remain under CHF 5 import tax — “small consignment” logic per §3.2).
- **Return / refund posture** (target: acceptable dispute policy).
- **Product specification match** (declared specs actually match what customers receive — cross-check reviews).
- **Consistency of stock** (avoid one-off deals that disappear).
- **Safety / conformity signals** for chargers or anything electrically active.
- **Duplicated / substitutable listings** available (backup source), because AliExpress SKUs churn.

Deliver, per product, a **primary supplier** and at least **one backup**, with the quality dimensions filled in.

---

## 7. Pricing rules — non-negotiable

### 7.1 Undercut rule
`your_all_in_price ≤ competitor_all_in_price × 0.80`

Where:
- `competitor_all_in_price = competitor item price + competitor mandatory customer-visible shipping/fees (when applicable)`
- `your_all_in_price = your item price + your customer-visible shipping + any mandatory customer-visible charge (incl. any VAT treatment embedded in the sale)`

### 7.2 Gross margin rule
`gross_margin = (net_sales_revenue - landed_product_cost) / net_sales_revenue ≥ 0.50`

`landed_product_cost` must include, at minimum:
- supplier item cost,
- supplier shipping cost,
- payment processing friction (if not accounted for separately),
- expected refund/defect allowance,
- any merchant-borne import-related cost,
- any operational cost that scales per unit.

### 7.3 Combined check
A product may be listed only if **both** §7.1 and §7.2 hold **simultaneously**, using conservative assumptions.

### 7.4 Price and product profile presentation
When you create the site, the price and the **full product profile** (title, images, specs, compatibility, dimensions, weight, what’s in the box, delivery time, warranty/return summary) must be visible together on the product page. Do not ship product pages missing the profile.

Reference: the Swiss SECO states that Swiss price indication rules aim at **transparency**, **comparability**, and **prevention of misleading price indications**, for consumer-facing goods and price-bearing advertisements. Source: <https://www.seco.admin.ch/fr/generalites-oip>.

---

## 8. Anti-illusion margin protocol

For every candidate, before listing, compute and log:

1. competitor price basis (URL + observed price),
2. supplier price basis (URL + observed price + declared shipping cost),
3. estimated all-in landed cost,
4. estimated refund/defect buffer (%),
5. payment processing assumption (%),
6. expected conversion friction (rough),
7. sensitivity: rerun the math with **supplier cost +10%** and **shipping cost +20%** and, if not already applied, a **non-zero refund/defect reserve**.

If the product loses its 20% undercut headroom **or** drops below 50% gross margin under the stress test, **reject it**.

**Explicit warning to you, executing AI**: guard against **margin illusion**. Cheap AliExpress prices routinely hide shipping, defects, and refund costs. Never approve a product whose apparent margin only exists because a real cost was ignored.

---

## 9. Revised scoring system (out of 100)

- **A. Price arbitrage strength — 25**: gap size between Swiss competitor all-in and your projected all-in.
- **B. Margin resilience — 20**: survives the stress test in §8.
- **C. Return-risk minimization — 20**: low compatibility confusion, low subjective dissatisfaction, low setup complexity, low defect expectation.
- **D. Simplicity / support burden — 15**: few variants, few pre-sale questions, little after-sales support.
- **E. Search-fit / pull demand — 10**: obvious query intent, commodity-like comparison behavior.
- **F. Compliance / safety comfort — 10**: low regulatory risk, low safety downside, easy-to-state product claims.

Decision thresholds:
- **≥ 85** → launch candidate.
- **70–84** → test candidate (limited exposure, tighter monitoring).
- **< 70** → do not list.

Any failure on hard exclusions, the 20% undercut rule, the 50% margin rule, or the low-return posture **overrides** the score.

---

## 10. Mandatory product dossier (ultra-strict)

No SKU goes live without a complete dossier. Store dossiers in a structured file that the operator can open from the dashboard.

Each dossier must contain:

1. product family,
2. exact candidate name,
3. chosen Swiss competitor (Digitec or Galaxus) + URL + observed price + date,
4. competitor mandatory customer-visible shipping/fees (if applicable),
5. primary supplier URL + item cost + declared shipping cost + supplier quality snapshot (per §6),
6. backup supplier URL + item cost + declared shipping cost,
7. estimated all-in landed cost,
8. proposed selling price CHF,
9. proposed shipping presentation to the customer,
10. **proof** that final customer all-in price is ≥ 20% cheaper than competitor all-in (calculation),
11. **proof** that gross margin is ≥ 50% (calculation),
12. stress-test result (§8),
13. weight and dimensions,
14. return-risk assessment,
15. compatibility-risk assessment,
16. compliance/safety note,
17. expected support burden,
18. primary and secondary search-intent keywords,
19. proposed product title, meta title, meta description, structured data,
20. verdict: **launch / test / reject**.

---

## 11. Website architecture and mandatory pages

Build a simple, clean, Swiss-facing site including at least:

- Home.
- Catalog / collection pages.
- Individual product pages.
- Shipping information page.
- Returns / refunds page.
- Contact and seller identity page.
- Terms and conditions.
- Privacy policy.
- Order confirmation flow (with the technical steps of contract conclusion, per §12).
- Operator dashboard (see §13).

Design cues:
- Swiss, clean, trustworthy, information-first.
- Not brand-heavy, not gadget-hype, not marketplace-styled.
- Prices in CHF everywhere.
- French primary; German highly recommended; English optional.

---

## 12. Swiss e-commerce legal integration (must-do)

Reference: SECO states that any person offering goods or services via e-commerce must, per art. 3 al. 1 let. s LCD:

- **clearly and completely indicate identity and contact address, including email**,
- **indicate the different technical steps leading to the conclusion of the contract**,
- **provide appropriate technical means to detect and correct input errors before submitting the order**,
- **confirm the customer’s order by email without delay**.

Source: <https://www.seco.admin.ch/fr/avant-l-achat-et-conclusion-du-contrat>.

You must therefore ensure the site:

- exposes seller identity + email + physical contact info clearly (footer + dedicated page),
- **explicitly describes the technical steps of contract conclusion** on a visible page (e.g. “How ordering works”): browse → add to cart → cart review → checkout form → order summary → confirmation click → email confirmation,
- provides an **order-review step** where the customer can **detect and correct input errors** before finalizing,
- **sends an automated confirmation email immediately** after the order is placed.

Additionally, the Swiss legal environment does **not** guarantee a general right of withdrawal for online purchases (SECO: “La législation suisse ne prévoit pas de droit général de révocation en cas d’achat sur l’Internet”). Source: <https://www.seco.admin.ch/fr/problemes-apres-lachat>.

Design the returns page in line with what the operator actually offers (which will be limited, since dropshipping constrains reverse logistics). Do not promise what cannot be delivered.

---

## 13. Operator dashboard — mandatory features

Build a dashboard that gives the operator control without needing to code.

### 13.1 Dropshipping toggle (per store and per order)
- **Store-level default toggle**: `Dropshipping mode: ON / OFF`.
- **Per-order override**: the operator can flip a specific order between dropshipping and classic before it is dispatched.
- **Default state**: `Dropshipping mode: ON`.

### 13.2 Order-flow behavior when Dropshipping = ON
On a paid customer order:
1. Capture the customer’s Swiss address.
2. Automatically create the corresponding order on **AliExpress**, using the pre-selected supplier for that SKU.
3. Ship the parcel **directly to the Swiss customer address**.
4. Do not expose supplier branding or AliExpress packaging cues where avoidable (e.g. use “ship from supplier without invoice / no promotional inserts” options when the supplier offers them).
5. Sync the tracking number back to the store and email the customer.
6. Log everything in the order record: supplier used, supplier order ID, supplier cost, shipping cost, ETA, tracking link.

### 13.3 Order-flow behavior when Dropshipping = OFF
Operate as a classic online store:
- ship from the operator’s local stock,
- deduct inventory,
- print packing slip,
- create the shipping label with a Swiss carrier,
- notify the customer.

### 13.4 Revenue ceiling monitoring (see §3.2)
- rolling 12-month revenue counter,
- soft alert at CHF 70,000,
- hard alert at CHF 90,000,
- projected-breach warning.

### 13.5 Margin monitoring
- per-SKU realized margin,
- per-order realized margin (with supplier cost captured from the actual AliExpress order at the time of purchase — not the price observed during sourcing),
- monthly refund/defect ratio,
- **automatic delisting recommendation** when realized margin drops below 50% for a rolling window.

### 13.6 Supplier health monitoring
- per-supplier defect rate,
- per-supplier late-shipment rate,
- automatic promotion of the backup supplier if the primary degrades.

### 13.7 Compliance nudges
- reminder about the CHF 100,000 threshold,
- reminder about the CHF 5 “small consignment” logic,
- reminder to keep product pages compliant with Swiss price indication rules (§7.4).

---

## 14. Dropshipping automation — technical requirements

You must implement (or wire) the automation such that:

- when Dropshipping = ON and the customer pays, the store **automatically** creates the AliExpress order for the correct SKU + variant, with the customer’s shipping address,
- the automation uses the **primary supplier** defined in the SKU’s dossier and can **fail over** to the **backup supplier** if the primary is out of stock,
- the automation captures **supplier order ID**, **supplier cost paid**, **shipping fee paid**, **tracking number**, **carrier**, **ETA**,
- the automation writes those back into the store’s order record,
- if the automation cannot place the AliExpress order (out of stock at both suppliers, price spike breaking the 50% margin, price spike breaking the 20% undercut), it **holds the order** and **notifies the operator** with a proposed action:
  - swap supplier,
  - refund customer,
  - fulfill from stock,
  - cancel with apology.

You may implement this with:
- an official dropshipping-oriented app/plugin already integrated with AliExpress,
- or a headless automation you wire yourself,
- or a hybrid.

Choose the most reliable, low-friction path compatible with the store platform you pick. Document what you chose and why.

---

## 15. Acquisition — pull only

### 15.1 Channels
- **Google Search**: SEO-optimized product pages.
- **Google Shopping**: clean product feed with correct GTIN when available, brand (when relevant), category, price, availability, shipping.
- No paid social, no influencers, no push campaigns.

### 15.2 SEO copy production (your responsibility)
For each SKU, generate:
- **product title** matching how Swiss buyers search (e.g. include cable length, USB spec, wattage),
- **meta title** and **meta description**,
- **H1** and structured description with bullet specs,
- **FAQ block** when confusion is likely (compatibility, cable length behavior, hub power delivery, etc.),
- **schema.org Product** JSON-LD with price, currency `CHF`, availability, shippingDetails, brand where relevant, aggregateRating **only if real** (never fake reviews),
- **hreflang** if you publish multi-language pages.

### 15.3 Query mapping
For each SKU, list at least:
- 1 primary transactional query,
- 3 secondary transactional queries,
- 1 comparison query,
- 1 objection-handling query (used to draft FAQ).

---

## 16. Order fulfillment presentation to the customer

- Show a **clear estimated delivery window**. If dropshipping introduces longer shipping, say so upfront (e.g. “Shipping from partner warehouse, delivered in X–Y business days to Switzerland”). Never oversell speed.
- Show a **clear return policy** aligned with what the operator can actually accept, keeping in mind Swiss law does not force a general online right of withdrawal (§12). Do not advertise a policy the operator will not honor.
- Show **CHF price** with **any customer-visible shipping fee** transparently. No fee surprises at checkout.
- Provide the **order-review step** with editable fields before final submission, per §12.

---

## 17. Site build — hard don’ts

Do not build:
- a hype-driven brand homepage,
- a broad catalog with hundreds of SKUs,
- bundles,
- vague product pages,
- checkout flows without an explicit error-correction step,
- pricing displays that obscure shipping,
- product pages missing weight/dimensions/compatibility.

Do not use:
- fake reviews,
- fake urgency countdowns,
- fake stock warnings,
- brand-imitation product images.

---

## 18. Operational protocol — how you should proceed concretely

You are an agentic AI. Execute in this order, and **check in with the operator at each gate**:

### Gate 1 — Scope confirmation
- Restate to the operator, in **one screen**, your understanding of scope, ceiling, dropshipping-first setup, and acquisition model.
- Ask only what is genuinely blocking (e.g. store platform preference, target languages, payment providers, target VAT status confirmation).
- Then proceed.

### Gate 2 — Product discovery
- For each approved family, identify concrete candidate SKUs visible on **Digitec** or **Galaxus** at ≥ CHF 25.
- For each candidate, find a matching AliExpress source at ≤ 50% of the Swiss competitor price.
- Produce a **shortlist** with the fields required for the dossier (§10) partially filled.
- Return the shortlist to the operator with recommended launch/test/reject verdicts.

### Gate 3 — Supplier vetting
- For every product proposed for launch or test, apply the supplier quality framework (§6).
- Assign a **primary** and a **backup** supplier.
- Log the reasoning.

### Gate 4 — Pricing and dossier finalization
- Compute the numbers for §7 and §8 for each proposed SKU.
- Stress-test.
- Reject any SKU that fails after stress-testing.
- Assemble the full dossier per §10.

### Gate 5 — Site build
- Choose the store platform and dropshipping automation stack; explain the choice briefly.
- Build the site, the pages required in §11, and the operator dashboard per §13.
- Wire the dropshipping automation per §14.
- Publish nothing until the operator approves.

### Gate 6 — SEO / feed
- Generate SEO copy per §15.2 for every launch SKU.
- Build the Google Shopping feed.
- Verify structured data.

### Gate 7 — Pilot launch
- Launch **only** the launch-verdict SKUs.
- Keep test-verdict SKUs off the live catalog until they earn promotion.
- Turn Dropshipping ON by default.
- Monitor per §13.5 and §13.6.

### Gate 8 — Iteration
- Weekly: recompute realized margins and defect ratios.
- Delist any SKU whose realized margin drops below 50% for the rolling window.
- Promote backup suppliers if the primary degrades.
- Never expand the catalog beyond what your discipline can monitor.

---

## 19. Concrete decision inputs you must confirm with the operator

Before Gate 2 executes at scale, ask the operator to confirm:

1. Preferred store platform (Shopify vs WooCommerce vs custom stack).
2. Domain, brand name (kept minimal per §4), and languages to publish (FR mandatory; DE strongly recommended; EN optional).
3. Payment providers accepted in CHF (e.g. Stripe, Twint, PostFinance, cards).
4. Shipping carriers for the “classic” mode (when Dropshipping = OFF).
5. Preferred dropshipping automation route (existing plugin vs custom headless).
6. Any brand assets already owned (logo, palette). If none, use a minimal, neutral, Swiss-clean visual identity.
7. Any category or product the operator wants added or removed from §4.1.
8. Contact address to publish for legal compliance (§12).
9. Whether the operator wants **defect insurance/refund reserve** modeled at product level or centrally.
10. Cadence and channel of status reports (weekly email, dashboard-only, etc.).

Batch these questions. Do not drip them.

---

## 20. Reporting — what you owe the operator every week

- Rolling revenue counter and projection versus the CHF 100,000 ceiling.
- Per-SKU realized gross margin.
- Per-SKU realized undercut versus Swiss competitor.
- Defect / refund rate per SKU and per supplier.
- Supplier delivery time versus promise.
- Any SKU currently in violation of the 20% or 50% rule, and the recommended action.
- Any SKU whose Swiss competitor price moved materially (up or down).

---

## 21. Guardrails — repeat before every large action

Before you crawl at scale, publish products, or push a feed, re-read the following mental checklist:

- [ ] Am I keeping the operator below CHF 100,000 annualized?
- [ ] Is every listed SKU ≥ 20% cheaper **all-in**?
- [ ] Is every listed SKU delivering ≥ 50% gross margin **after** realistic landed cost?
- [ ] Is every listed SKU in a low-return, low-support family?
- [ ] Are all dossiers complete?
- [ ] Is Dropshipping mode wired correctly, with automatic order forwarding to the correct AliExpress supplier and direct-to-customer shipping in Switzerland?
- [ ] Is the price + product profile displayed together on every product page?
- [ ] Are the LCD art. 3 obligations (identity, contract steps, error correction, order confirmation email) visibly satisfied?
- [ ] Am I about to introduce **margin illusion**? If in doubt, reject.

---

## 22. Priority hierarchy (start here)

1. Cables and simple adapters.
2. Simple USB-C hubs.
3. Foldable passive laptop stands.
4. Bluetooth trackers (only if low friction).
5. Compact chargers (only under stricter compliance scrutiny).

You may reorder or extend under §4.2, never outside it.

---

## 23. Follow-up deliverable to produce after Gate 4

Before Gate 5, submit to the operator:

- A concrete shortlist of **20 SKUs** with, per SKU:
  - Swiss competitor URL + observed price + date,
  - AliExpress supplier URL + observed cost + supplier quality snapshot,
  - backup supplier URL + observed cost,
  - projected all-in Swiss customer price,
  - proof of the 20% undercut,
  - projected gross margin and stress-test result,
  - risk rating,
  - launch/test/reject verdict.

Then wait for approval before building the live catalog.

---

## 24. Final directive

Do not optimize for scale.
Do not optimize for catalog size.
Do not optimize for brand perception.
Do not optimize for trendiness.

**Optimize for**:

- simple products,
- obvious Swiss price arbitrage,
- low operational burden,
- low return probability,
- strict margin discipline,
- search-based purchase intent,
- reliable dropshipping automation,
- staying under the Swiss VAT threshold.

**If in doubt, reject the product.**

---

*End of brief.*
