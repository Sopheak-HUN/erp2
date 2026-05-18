# Inventory, Sales & Procurement — Cambodia ERP Requirements

This reference covers the operational ERP modules — inventory, sales, and procurement — focusing on the Cambodia-specific requirements that intersect with tax, invoicing, and customs compliance. Verified May 2026.

## Tax invoice — the central control point

The tax invoice is where accounting, VAT, and audit trail converge. Cambodia's invoice rules are prescriptive; the ERP must enforce them.

### Mandatory content (per Sub-Decree on VAT and Prakas on Invoice)

A compliant **tax invoice** (used for VAT-registered transactions) must include:

1. The words **"Tax Invoice"** (វិក្កយបត្រអាករ) — in Khmer.
2. **Sequential invoice number** — uninterrupted, no gaps; the ERP must enforce sequence integrity (a voided invoice still occupies a number).
3. **Date of issue** and date of supply (if different).
4. **Seller details:** legal name in Khmer (and English if applicable), full address, **VATIN (10-digit Tax Identification Number)**.
5. **Buyer details:** legal name, address, **VATIN if B2B** (required for the buyer to claim input VAT credit). For B2C, "Consumer" with national ID or passport is acceptable for high-value sales.
6. **Description of goods/services** — Khmer language required; English may be added.
7. **Quantity and unit price.**
8. **Pre-VAT amount, VAT amount (10%), and VAT-inclusive total** — shown separately.
9. **Currency** — if not KHR, the **KHR equivalent at the NBC end-of-month rate** for the supply month must be shown or footnoted.
10. **Signature / authorised stamp** — physical stamp ("chhab") is the Cambodian norm; e-invoices use authorised digital signatures.

### Simplified invoice (for retail / small-value transactions)

- Permitted below a value threshold set by GDT (verify current threshold; historically around KHR 400,000 / USD 100).
- Fewer fields required (no buyer details), but still sequential number, seller VATIN, date, total, VAT.
- The ERP should support both invoice types and the switching logic by transaction value and customer type.

### Invoice timing

- Issue within **7 days** of: shipment of goods, completion of services, or payment receipt — whichever is **earliest**.
- Shipments without an accompanying invoice must travel with a recorded shipping document (delivery note) that references the future invoice.
- Continuous-supply or instalment contracts follow timing rules set by separate MEF Prakas.

### Document numbering integrity

- Sequential, per legal entity, per fiscal year (typical convention).
- Voided invoices remain on file; the ERP must mark them voided rather than allowing deletion.
- Credit notes carry their own sequence and must reference the original invoice number.
- The auditor will reconcile the highest invoice number against declared sales each month — gaps are a red flag.

## Sales module — Cambodia-specific requirements

Beyond standard sales-order-to-invoice flow, the ERP must support:

- **VAT category per line:** standard 10%, zero-rated, exempt, state-borne (the new 1 Jan 2026 category for basic foods), out-of-scope.
- **VATIN validation at customer setup** — checksum logic against the 10-digit GDT TIN format; flag if buyer claims B2B but has no VATIN.
- **Export documentation pack:** for zero-rated exports, the ERP must produce or reference the customs declaration (SAD), bill of lading / airway bill, packing list, and certificate of origin. Without this evidence the zero-rate is challengeable on audit.
- **E-commerce flag:** if the supplier is non-resident selling digitally into Cambodia, simplified VAT rules apply (see `tax-gdt.md`); resident e-commerce sellers follow standard rules but should flag B2C vs B2B for reverse-charge logic.
- **Sales ledger output** for monthly VAT return: invoice number, date, buyer name, buyer VATIN, taxable amount in KHR, VAT, currency, FX rate used.
- **Khmer-language invoice printout** is required for statutory copies even if the operational copy is English.
- **Stamp duty flag** for sales of immovable property, vehicles, and shares — these trigger stamp duty separate from VAT.

## Procurement module — Cambodia-specific requirements

The buy-side carries the WHT burden, which most global ERPs handle poorly out of the box.

### WHT capture at invoice entry

For every supplier invoice the ERP must classify:

1. **Supplier type:** resident vs non-resident (drives WHT category).
2. **Supplier VAT registration:** registered vs non-registered (resident services from a non-registered supplier attract 15% WHT).
3. **Payment type:** services / rent / interest / royalties / dividends / management fees — each maps to a WHT rate.
4. **DTA applicability:** if non-resident, check if the supplier's country of residence has a DTA with Cambodia and a valid certificate of residency is on file → use treaty rate.

The system then:
- Reduces the cash payment to the supplier by the WHT amount.
- Credits WHT Payable (a GDT liability account).
- Includes the line in the monthly WHT schedule.

### Input VAT credit logic

Input VAT is creditable only when **all** these are true:

- Actual purchase invoice received (not accrual).
- Invoice contains the **buyer's VATIN**.
- Goods/services are used for taxable business purposes (not exempt, not personal).
- Invoice is for supplier-charged VAT, not VAT on non-creditable categories (entertainment, passenger vehicles, etc.).

The ERP must flag invoices that fail any of these and route them to a non-creditable input VAT account.

### Three-way matching with WHT

Standard three-way matching (PO → GRN → invoice) must be extended:

- PO records intended supplier type and projected WHT.
- GRN records actual receipt.
- Invoice records actual values, with WHT recalculated.
- Payment proposal nets WHT from the supplier remittance.

Cambodian bank transfers should carry the WHT certificate number as a reference for the supplier's records.

### Accrued expense vouchers (for accrual basis)

For services or supplies received before invoice:

- Voucher must capture: number, date, amount, description, supplier, expected WHT.
- WHT on the accrued amount is declared in the month of accrual.
- When the actual invoice arrives, the voucher is reversed and the invoice posted normally — the ERP must prevent double-declaration of WHT.
- A reconciliation report between accrued and invoiced WHT is required monthly.

### Purchase ledger output

For the monthly VAT return:

- Invoice number, date, supplier name, supplier VATIN, taxable amount in KHR, VAT, currency, FX rate.
- Flag: creditable vs non-creditable input VAT.
- WHT amount and category (separate from VAT).

## Inventory module — Cambodia-specific requirements

### Costing

- FIFO or weighted average — **LIFO is prohibited** under CIFRS / IFRS for SMEs.
- The ERP must let the entity choose and apply the method consistently. A change in costing method is a change in accounting policy and requires disclosure and (usually) retrospective application.
- Standard costing is acceptable for management reporting but must reconcile to FIFO/weighted average for statutory reporting.

### Valuation

- Lower of cost and **selling price less costs to complete and sell** (per IFRS for SMEs Section 13).
- Year-end impairment review mandatory.
- Slow-moving / obsolete inventory provisions must be supportable with documented analysis.

### Stock count and reconciliation

- Annual physical count minimum; perpetual inventory recommended.
- Variances investigated and posted before year-end close.
- For QIP entities benefiting from VAT exemptions, GDT may inspect inventory directly — the ERP must produce stock-on-hand reports with location, batch, and value.

### Customs and import inventory

- The General Department of Customs and Excise (GDCE) handles imports.
- Imported goods carry:
  - **Import duty** (rate per HS code — 0%, 7%, 15%, 35% bands typical).
  - **Special tax** on certain commodities (alcohol, tobacco, vehicles, petroleum).
  - **VAT 10%** on the duty-inclusive customs value.
- The ERP must capture the **customs declaration (SAD) number**, HS code, duty paid, special tax paid, and import VAT — all become part of the landed cost.
- Bonded warehouse / temporary admission regimes (Master List for QIP) require separate tracking.

### Manufacturing — Master List for QIPs

For Qualified Investment Projects, machinery and raw materials imported under the Master List (approved by CDC) are duty- and VAT-exempt. The ERP must:

- Tag Master List items and prevent their consumption from being charged with VAT in COGS.
- Track usage to support GDC audits — diversion of Master List materials to non-export use triggers retroactive duty + VAT + penalties.
- Maintain export reconciliation showing inputs-to-outputs ratio.

## Customer master and supplier master — Cambodia-specific fields

Both masters should capture:

- Legal name in Khmer + English
- VATIN (10-digit GDT TIN)
- Patent tax certificate number (annual)
- NSSF employer number (if relevant)
- Bank account in KHR and/or USD
- Country of tax residence (for DTA logic on supplier side)
- Treaty residency certificate file (for non-resident suppliers claiming DTA rate)
- Default WHT category (suppliers)
- Default VAT treatment (customers)
- Khmer mailing address with commune/district/province

## Required ERP outputs

- **Monthly sales ledger** — feeds VAT return.
- **Monthly purchase ledger** — feeds VAT return, with creditable/non-creditable split.
- **Monthly WHT schedule** — feeds WHT return.
- **Accrual reconciliation** — accrued vs invoiced VAT and WHT.
- **Customs reconciliation** — Master List consumption vs export output (for QIPs).
- **Invoice sequence integrity report** — confirms no gaps in tax invoice numbering.
- **Stock valuation report** — for CIFRS reporting and impairment review.

## Compliance checklist — Inventory, Sales & Procurement

- [ ] Tax invoice format: Khmer "Tax Invoice" heading, sequential number, both parties' VATIN, 10% VAT separately shown, KHR equivalent when other currency, signature/stamp
- [ ] Invoice issued within 7 days of supply/payment (earliest)
- [ ] Simplified invoice supported for low-value retail
- [ ] Invoice sequence integrity (no gaps, voided invoices retained)
- [ ] Credit note linked to original invoice
- [ ] VAT categories per line: standard, zero, exempt, state-borne (Jan 2026 basic foods), out-of-scope
- [ ] VATIN format validation (10 digits) on customer/supplier master
- [ ] Export evidence pack (SAD, BL/AWB, packing, CoO) for zero-rated exports
- [ ] Sales ledger output for monthly VAT return
- [ ] WHT category captured at supplier invoice; resident vs non-resident split
- [ ] DTA treaty rate logic with residency certificate evidence
- [ ] WHT auto-deducted from supplier payment; certificate generated
- [ ] WHT on accrued expenses declared in month of accrual
- [ ] Input VAT creditable only with actual invoice + buyer VATIN
- [ ] Non-creditable input VAT routed to separate GL account
- [ ] Purchase ledger output for monthly VAT return
- [ ] Three-way matching with WHT extension (PO → GRN → invoice → WHT)
- [ ] Inventory costing: FIFO or weighted average (no LIFO)
- [ ] Year-end impairment review and provision posting
- [ ] Customs SAD number, HS code, import duty, special tax, import VAT captured per landed cost
- [ ] QIP Master List items tagged, consumption tracked for GDCE audit
- [ ] Master files include Khmer name, VATIN, patent number, bank, country of residence
- [ ] Reconciliation: accrued vs invoiced VAT and WHT, monthly
