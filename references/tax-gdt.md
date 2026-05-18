# Tax & GDT Compliance — Cambodia ERP Requirements

This reference captures the tax modules an ERP must support to remain GDT-compliant. Verified against public sources as of May 2026; always reconfirm against `tax.gov.kh` and the latest Prakas before configuring rates in a production system.

## Regulator and regime

- **Authority:** General Department of Taxation (GDT), under the Ministry of Economy and Finance.
- **Regime:** Self-declaration is the only regime. Taxpayers classify themselves under one of three categories — Small, Medium, or Large — based on annual turnover, sector, and legal form. GDT may reclassify if declared turnover misrepresents reality.
- **Registration:** Tax Identification Number (TIN) must be obtained within 15 working days of business commencement. Failure can incur administrative penalties of several million KHR.
- **Filing systems:** GDT E-Filing portal and the GDT Taxpayer Application. Hard filing remains permitted but with an earlier deadline.

## Taxpayer classification (broad bands — confirm current MEF thresholds)

| Class | Indicative annual turnover (agriculture/services/commerce) |
|---|---|
| Small | ≤ KHR 250 million (~USD 62,500) |
| Medium | KHR 250 million to KHR 700 million |
| Large | > KHR 700 million, or any company/foreign branch |

Sector-specific thresholds differ; the ERP should let the user record the entity's official classification rather than auto-derive it.

## Corporate Income Tax (CIT) — "Tax on Income" (ToI)

- **Standard rate:** 20% on taxable profit.
- **Sector-specific rates:** Insurance 5% on gross premium; oil/gas/some minerals 30%.
- **Filing:** Annual return ("Annual ToI Declaration") due **within 3 months of fiscal year-end** (i.e. by 31 March for calendar-year filers).
- **Prepayment of ToI ("PToI"):** Monthly prepayment of **1% of monthly turnover** (inclusive of all taxes except VAT) due by the **20th** of the following month (or **25th** via e-Filing). Creditable against annual ToI liability.
- **Minimum Tax (MT):** A separate 1% turnover tax that applies if the entity does **not** maintain proper accounting records. Entities that maintain CIFRS-compliant records are exempt from MT (per Law on Financial Management 2017).
- **Loss carry-forward:** Up to 5 years, subject to conditions (no ownership change, no activity change, etc.).
- **Capital gains tax (CGT):** Flat 20%. Effective 1 January 2026 on sale/transfer of leases, investment assets, goodwill, IP, and foreign currencies. Real estate CGT was extended to 1 January 2027 (per the most recent Prakas — verify current effective date). CGT returns and payment due within 3 months of realising the gain.

## Value Added Tax (VAT)

- **Standard rate:** 10%.
- **Zero rate:** Exports; services performed within Cambodia but used entirely outside Cambodia by a non-resident; goods/services supplied by Supporting Industry QIPs; domestic supplies of milled rice and certain agricultural inputs; CMT services in garment manufacturing.
- **State-borne VAT (effective 1 January 2026, valid until end of 2028):** VAT on locally produced basic foods is borne by the state — covers livestock products (fresh or processed by fermenting/marinating/smoking), eggs, freshwater/saltwater fish including shrimp/crab/shellfish, and sugar (not candy). Restaurants are excluded. To qualify, taxpayers must request a state-borne VAT certificate, attach a supplier list to monthly VAT returns, maintain compliant accounting records, and file on time (Instruction No. 076 GDT, 2 January 2026).
- **Registration threshold:** Annual turnover above approximately KHR 125 million (~USD 31,250) — verify current MEF threshold.
- **Invoice timing:** Tax invoice must be issued within **7 days** after shipment, service rendering, or payment (whichever is earliest). Shipments without an accompanying invoice must carry a properly recorded shipping document.
- **Input VAT credit:** Claimable only on the actual purchase invoice — not on accruals — and only if the invoice contains the buyer's VATIN.
- **Output VAT:** Due when the actual sales invoice is issued (not on accrual).
- **Monthly return ("VAT Declaration"):** Due **20th of following month** (hard) / **25th** (e-Filing). Must be accompanied by purchase ledger, sales ledger, and copies of issued and received invoices.

### VAT on e-commerce (non-resident digital supplies)

- Rate: 10% on digital goods/services supplied by non-residents to Cambodian customers.
- Non-resident suppliers meeting the GDT threshold must register for Simplified VAT within 30 days of commencing economic activity.
- B2B with payment from a company bank account: non-resident does **not** collect 10% — the Cambodian buyer uses **reverse charge** (Form NR-VAT02), paying VAT in KHR.
- B2C: non-resident collects and remits 10% (Form NR-VAT01), with FX conversion at NBC end-of-month rate.

## Withholding Tax (WHT)

WHT applies on payments to both residents and non-residents. The ERP must capture the WHT category at the time of invoice posting and produce a monthly WHT schedule.

### Resident WHT rates (typical — confirm current Prakas)

| Payment type | Rate |
|---|---|
| Services to a non-registered resident taxpayer | 15% |
| Royalties, rent of movable property, interest | 15% |
| Interest paid by domestic banks to residents (term deposits) | 6% |
| Interest paid by domestic banks to residents (savings) | 4% |
| Rent of immovable property | 10% |

### Non-resident WHT rates

| Payment type | Standard rate | Treaty-country rate (where DTA applies) |
|---|---|---|
| Interest, royalties, rent, services, management fees, technical services | 14% | Typically 10% |
| Dividends | 14% | Typically 10% |

Cambodia's double-tax agreements (Singapore, Thailand, China, Vietnam, Indonesia, Brunei, Korea, Hong Kong, Malaysia and others) reduce these — the ERP must support per-counterparty DTA rate overrides with a treaty residency certificate stored as supporting evidence.

- **Filing:** Monthly WHT return, same deadlines as VAT (20th / 25th of following month).
- **GDT clarification on accruals:** WHT must be declared and paid on accrued expenses, not deferred to invoice receipt. The ERP must capture the WHT on the accrual voucher.

## Tax on Salary (ToS)

Cambodia has no general personal income tax. Tax on Salary is withheld monthly by the employer and remitted to GDT. See `payroll-hr.md` for full payroll detail; key tax facts:

### Resident monthly progressive brackets (2026)

| Monthly taxable salary (KHR) | Rate |
|---|---|
| 0 – 1,500,000 | 0% |
| 1,500,001 – 2,000,000 | 5% |
| 2,000,001 – 8,500,000 | 10% |
| 8,500,001 – 12,500,000 | 15% |
| Above 12,500,000 | 20% |

The KHR 1,500,000 0% threshold was set by Sub-Decree 196 (2022). Reconfirm with the latest MEF Sub-Decree each year — brackets have moved before and will move again.

### Non-resident

- Flat **20%** on Cambodian-sourced salary income, no deductions.

### Fringe Benefits Tax

- Flat **20%** on the value of fringe benefits (housing, car, meals, etc.), borne by the employer.

### Dependent allowances (deducted from gross before applying brackets)

- KHR 150,000/month per dependent child under 14 (or under 25 if full-time student at a recognised institution)
- KHR 150,000/month for a non-working spouse

### Filing

- ToS withheld monthly, remitted by **20th** (hard) / **25th** (e-Filing) of following month.
- Annual reconciliation due with the annual ToI declaration.

## Other taxes the ERP should be aware of

- **Patent tax:** Annual business registration tax due by 31 March, varies by taxpayer size.
- **Specific Tax on Certain Merchandise and Services:** Excise on alcohol, tobacco, petroleum, entertainment, telecom — rates vary.
- **Accommodation tax:** 2% on lodging services (tourism sector).
- **Public Lighting Tax (PLT):** 3% on alcohol and tobacco at each stage of distribution.
- **Property tax:** 0.1% of immovable property value (above KHR 100m threshold).
- **Unused Land Tax:** Annual, on undeveloped urban land.
- **Stamp duty:** On real estate transfers, vehicles, business transfers.

## Filing deadlines summary

| Filing | Hard deadline | E-Filing deadline |
|---|---|---|
| Monthly VAT | 20th of following month | 25th of following month |
| Monthly WHT | 20th of following month | 25th of following month |
| Monthly ToS (salary tax) | 20th of following month | 25th of following month |
| Monthly PToI (1% prepayment) | 20th of following month | 25th of following month |
| Specific Tax / PLT / Accommodation | 20th of following month | 25th of following month |
| Annual ToI declaration | 3 months after FY-end (31 March for calendar year) | Same |
| Patent tax | 31 March | 31 March |
| Capital gains tax | 3 months after realisation | Same |

If a deadline falls on a weekend or public holiday, it shifts to the next working day.

## Penalties

The ERP should model these so reminders escalate appropriately:

| Failure | Additional tax | Interest |
|---|---|---|
| Negligent underpayment | 10% of underpaid tax | 1.5% per month |
| Seriously negligent | 25% | 1.5% per month |
| Unilateral tax assessment (no return filed) | 40% | 1.5% per month |
| Obstruction of tax administration (real regime) | KHR 2,000,000 | — |
| Obstruction (estimated regime / small) | KHR 500,000 | — |

Late registration carries administrative penalties of several million KHR depending on category.

## Required ERP outputs for GDT e-Filing

For each monthly tax filing, the ERP must produce in a format ingestible by the GDT E-Filing system:

1. **VAT return body** — output VAT, input VAT, net payable.
2. **Sales ledger / output schedule** — invoice number, date, buyer name, buyer VATIN, taxable amount, VAT, currency.
3. **Purchase ledger / input schedule** — same fields for supplier-side invoices, indicating creditable vs non-creditable.
4. **WHT schedule** — recipient name, TIN/passport, payment type, gross, rate, WHT amount, treaty rate flag.
5. **Salary tax schedule** — employee, NSSF number, gross, dependents, taxable, ToS withheld, fringe benefit amount and FBT.
6. **PToI computation** — turnover base, 1% calc.
7. **Accrual / actual reconciliation** — timing differences between accrued declarations and invoice-based VAT/WHT.

## Compliance checklist — Tax & GDT

- [ ] TIN captured at entity setup; renewal/patent reminders configured
- [ ] Taxpayer classification (S/M/L) recorded and drives filing forms
- [ ] VAT 10% applied with zero-rate and state-borne exception logic
- [ ] Tax invoice numbering sequential, with VATIN, Khmer captions, issued within 7 days
- [ ] Input VAT only claimed on actual invoices with buyer VATIN present
- [ ] Reverse-charge VAT for B2B imports of digital services
- [ ] WHT captured at invoice/accrual entry, with resident vs non-resident split
- [ ] DTA treaty rate override with residency certificate evidence
- [ ] Monthly PToI (1% of turnover ex-VAT) auto-calculated
- [ ] Tax on Salary monthly with current brackets (verify Sub-Decree 196 / latest)
- [ ] Fringe Benefits Tax 20% accrued monthly
- [ ] Dependent allowance per employee (children, spouse) captured
- [ ] CIT annual return with 3-month deadline
- [ ] CGT 20% module for in-scope transactions (effective 1 Jan 2026)
- [ ] Monthly e-Filing export package (VAT, WHT, ToS, PToI) in GDT-acceptable format
- [ ] Penalty calculator (10% / 25% / 40% + 1.5%/month) for late filings
- [ ] Reconciliation report: accrual declarations vs invoice-based declarations
- [ ] Tax payment all in KHR; FX conversion at NBC end-of-month rate
