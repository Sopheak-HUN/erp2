# Tech Stack — Cambodia ERP Requirements

This reference covers the technology choices an ERP implementation in Cambodia must make: ERP platform options, payment-rail integrations, hosting, GDT e-Filing connectivity, identity, language/font stack, and security. Verified May 2026; cross-check vendor capabilities before recommending.

The skill should use this file when the user asks anything about which ERP product to pick, hosting decisions, integration choices, deployment architecture, or how to connect the ERP to local banks/tax systems.

## ERP platform options used in Cambodia

There is no single "Cambodian standard" ERP — the choice depends on company size, sector, budget, and tolerance for customisation. The realistic options as of 2026:

### Tier 1 — international ERPs with strong local presence

| Platform | Best fit | Cambodian readiness | Notes |
|---|---|---|---|
| **Odoo** (Community / Enterprise) | SMEs to mid-market, 10–500 employees | High — multiple local partners (ERP Compaz, ERP Cambodia, GIANTBROTHER and others) routinely deliver Khmer invoice templates, ABA PayWay integration, GDT-compliant reports | Open source; modular; lowest TCO for SMEs; weak out-of-the-box for Cambodian payroll/NSSF — usually requires custom modules |
| **SAP Business One** | Mid-market manufacturing, distribution, garment factories | Medium — implemented by regional partners (often Vietnam/Thailand-based) | Strong inventory and manufacturing; expensive licensing; localisation is bolt-on |
| **Microsoft Dynamics 365 Business Central** | Mid-market, especially companies already on Microsoft 365 | Medium — limited local partners; remote implementation common | Strong Office/Power BI integration; localisation requires AppSource add-ons |
| **Oracle NetSuite** | Larger SMEs with multi-entity / international operations | Low–Medium — usually delivered from Singapore/Bangkok | Strong multi-entity and multi-currency; high cost; least local depth |

### Tier 2 — accounting-first cloud SaaS

These suit very small companies or companies whose ERP needs are mostly bookkeeping + invoicing. They typically need supplementary tooling for inventory and payroll.

| Platform | Best fit | Cambodian readiness | Notes |
|---|---|---|---|
| **QuickBooks Online / Desktop** | Micro and small businesses; bookkeepers and CPAs trained on it widely | High awareness, low native compliance — Khmer fonts and KHR work but GDT-format reports are manual | Most common entry-level accounting tool in Cambodia |
| **Xero** | Small businesses, especially services and trading | High — GIANTBROTHER is a certified local partner | Clean UI; weak inventory; payroll add-on does not handle NSSF natively |
| **MYOB** | Small businesses, legacy users from Australia/NZ supply chains | Declining usage | Regional rather than Cambodia-specific |

### Tier 3 — local Cambodian-built ERPs

| Platform | Notes |
|---|---|
| **MAQSU ERP** | Cambodian vendor; ERPNext-based; markets explicit "Cambodia Tax Compliance"; covers accounting, inventory, manufacturing, HRM, POS |
| **CUSCEN ERP** | Cambodian vendor; modular, SME-focused; positioned on affordability |
| **Bongloy / Banhji / Camly** | Lighter accounting-first products with KHR-first design (verify current product positioning) |
| Various custom builds | Several Phnom Penh dev shops deliver bespoke ERPs on Laravel/Django/Next.js for specific verticals (garment factories, schools, F&B chains) |

**Selection heuristic the skill should apply:**

1. < 20 employees, services / trading → Xero or QuickBooks + outsourced payroll, OR Odoo Community with a local partner.
2. 20–200 employees, mixed operations → **Odoo Enterprise with a Cambodian partner** is the sweet spot in 2026. ERPNext via MAQSU is a viable alternative.
3. 200+ employees, manufacturing / multi-entity → SAP Business One or Odoo Enterprise with serious customisation budget. NetSuite if international parent already standardised.
4. Banks, MFIs, listed companies → Core banking / specialist platforms outside the scope of "general ERP"; this skill is not the right tool for that decision.

## Localisation requirements regardless of platform

These are the must-build / must-configure items on top of any ERP for Cambodia. The skill should output them as a tech-localisation checklist:

- **Khmer language UI and printouts** — UTF-8 throughout, Khmer Unicode font (Khmer OS, Battambang, Nokora, or Hanuman). Avoid legacy Khmer Limon encoding; it breaks search, sorting, and PDF rendering.
- **Khmer script rendering in PDFs** — many global ERPs use wkhtmltopdf or older PDF engines that mis-render Khmer. Validate with a real Khmer invoice template before go-live. Recent stacks should use libraries with proper Indic/Khmer script support (e.g., WeasyPrint, Chromium-based PDF, or commercial PDF engines).
- **Dual-currency presentation** with KHR as a presentation currency, NBC end-of-month exchange rate.
- **Invoice template** with the Khmer "Tax Invoice" heading (វិក្កយបត្រអាករ), 10-digit VATIN fields, sequential numbering, Khmer + English captions, signature/stamp area.
- **Sequential invoice numbering** enforced at database level (unique constraint + gap detection), not just UI.
- **GDT e-Filing export** — CSV/Excel layouts that match the GDT Taxpayer Application import schemas for VAT, WHT, ToS, and PToI.
- **WHT engine** with resident/non-resident split, DTA treaty-rate override, and accrual-month declaration.
- **NSSF payroll module** — three schemes (occupational risk, healthcare, pension) with the Phase 1/2/3 escalation schedule and the KHR 400k–1.2m pension wage cap.
- **Seniority indemnity accrual** with bi-annual June/December payouts for UDC contracts.
- **Khmer date formatting** option for statutory documents (Buddhist calendar year supported alongside Gregorian — not strictly required by tax law but expected on many forms).

## Bank and payment integrations

Cambodia's banking has moved fast; the ERP must connect to at least one of the major banks for reconciliation and digital payment:

- **ABA Bank** — dominant retail and SME bank. **ABA PayWay** is the most common payment gateway for e-commerce and POS; Cambodian Odoo partners routinely build PayWay integrations for invoicing and POS. KHQR support via the bank app.
- **ACLEDA Bank** — largest by branch network; widely used by older businesses. Offers ACLEDA Pay / ACLEDA Unity.
- **Wing Bank** — strong in micropayments and unbanked segment.
- **Canadia Bank, Vattanac Bank, Sathapana, Maybank Cambodia, Prince Bank** — all have business banking APIs to varying maturity.
- **Bakong** — the National Bank of Cambodia's interbank payment and settlement system (DLT-based). KHQR is the unified QR standard linked to Bakong, accepted across banks and e-wallets. Any modern ERP POS or invoicing module should support KHQR generation.
- **PiPay, TrueMoney Cambodia** — e-wallets relevant to retail.

For most SMEs, the practical integration list is: bank statement import (CSV/MT940/CAMT.053), ABA PayWay for online payments, KHQR generation for invoices and POS, and a manual reconciliation workflow against the GL.

## Hosting and data residency

- **No formal data localisation law currently mandates that accounting data live on Cambodian soil**, but the Law on Accounting and Auditing requires records to be available for inspection in Cambodia. The practical interpretation: cloud is acceptable as long as the company can produce records on demand to GDT/ACAR.
- **Cloud-based ERP is the default trend in Cambodia.** Lower upfront cost, easier deployment, better suited to companies with multiple sites or remote staff.
- **Realistic hosting options:**
  - Vendor SaaS (Odoo.sh, QuickBooks Online, Xero, NetSuite, Business Central cloud) — easiest, no infrastructure.
  - Regional cloud (AWS Singapore, GCP Singapore/Jakarta, Azure Singapore) — best latency from Phnom Penh; common choice for self-hosted Odoo/ERPNext.
  - Local hosting (data centres in Phnom Penh — Telcotech, EZECOM, Cellcard/CamGSM) — used by entities preferring on-shore data residency or by sectors with regulator preference (banks, MFIs).
  - On-premise — declining, but still used by larger garment factories and groups with weak internet redundancy.
- **Backup and DR** — keep at least one off-site copy. Internet redundancy (dual ISP) is recommended in Phnom Penh and essential outside the capital.
- **Personal Data Protection Law:** Cambodia has been drafting a data protection law; current rules sit in the Law on E-Commerce (2019) and consumer-protection regulations. The ERP should support data subject access requests and access controls even ahead of a formal PDPL.

## GDT and government system connectivity

- **GDT E-Filing portal** — taxpayers upload monthly return data through a web portal and the GDT Taxpayer Application. There is no public API for direct ERP-to-GDT submission as of 2026; integration is **export-and-upload**, so the ERP must generate the exact CSV/Excel schemas the portal accepts. Verify the latest template versions before each filing cycle — GDT updates these without long notice.
- **GDCE (customs)** — ASYCUDA World is used for customs declarations. ERP integration is typically a manual export of the SAD reference into the inventory landed cost; few SMEs do automated integration.
- **MoC business registration portal** — used for company registration and annual filings; not typically integrated.
- **NSSF portal** — accepts monthly contribution files (CSV); ERP must produce the file in NSSF's column order.
- **CamDX / Cambodia Data Exchange** — government's emerging interoperability platform. Watch for future tax filing APIs here.

## Identity, access, and security

- **Authentication:** SSO with Microsoft Entra ID or Google Workspace is the default for companies on those productivity suites. For local-only deployments, enforce strong passwords + MFA at minimum.
- **Role-based access** must align with **segregation of duties** for KICPAA / audit purposes:
  - Sales clerk cannot post journals or void invoices.
  - Accounts payable cannot create suppliers and approve payments.
  - Period close and re-open requires controller-level approval and is logged.
- **Audit log** with user, timestamp, before/after values on every posted-transaction change — required for any entity subject to statutory audit.
- **Encryption:** HTTPS for all transport; at-rest encryption for any cloud DB. Bank API credentials in a secrets manager (HashiCorp Vault, AWS Secrets Manager, or platform-native vault), never in code.
- **Backups:** daily encrypted, with at least one off-site copy; quarterly restore tests; 10-year retention to match tax record retention.

## Recommended stack templates

The skill can offer a tech-stack recommendation per company profile. These are starting points, not prescriptions — confirm against the customer's specific scope.

### Profile A — Small services / trading company (10–30 employees, 1 location)

- **ERP:** Odoo Enterprise (or ERPNext via MAQSU) implemented by a local partner.
- **Hosting:** Odoo.sh or AWS Singapore.
- **Payroll:** Odoo Cambodian payroll module (custom NSSF + ToS configuration) or BetterHR for payroll-only.
- **Payments:** ABA PayWay for online; KHQR for in-person.
- **Bank reconciliation:** Bank statement CSV import (manual until ABA/ACLEDA open up open-banking APIs).
- **Documents:** WeasyPrint or Chromium PDF for Khmer-safe invoice rendering.
- **Productivity:** Google Workspace or Microsoft 365.

### Profile B — Mid-market garment / manufacturing (100–500 employees, factory + office)

- **ERP:** Odoo Enterprise with manufacturing module OR SAP Business One.
- **Hosting:** AWS Singapore or local data centre, with on-premise replica at the factory for line continuity during internet outages.
- **Payroll:** Dedicated payroll engine (custom module or BetterHR) integrated to ERP; NSSF file generation, biometric time-attendance integration.
- **Customs:** Manual ASYCUDA integration; Master List (QIP) tracking module.
- **WMS:** Built-in Odoo/SAP warehouse module with barcode scanning.
- **Bank:** ABA + ACLEDA multi-bank reconciliation.
- **BI:** Power BI or Metabase against a replicated DB.

### Profile C — Group of companies with foreign parent (USD-functional, KHR-presentation)

- **ERP:** NetSuite or Dynamics 365 Business Central for multi-entity consolidation; local Odoo or QuickBooks at the Cambodian subsidiary feeding the group system.
- **Hosting:** Vendor SaaS.
- **Consolidation:** USD functional, KHR presentation per CIFRS requirement; intercompany elimination at group level.
- **Audit:** Big Four or KICPAA member firm with experience in CIFRS for the audited entities.

## Compliance checklist — Tech stack

- [ ] ERP platform selected with explicit reasoning against company size, sector, budget
- [ ] Cambodian implementation partner identified (preferred for Odoo, SAP B1, ERPNext)
- [ ] Khmer UTF-8 throughout; Khmer Unicode font selected and tested on PDFs
- [ ] Invoice template validated for Khmer rendering, sequential numbering, KHR equivalent
- [ ] Dual-currency presentation with NBC end-of-month rate revaluation
- [ ] GDT e-Filing export templates aligned with current GDT portal schemas (VAT, WHT, ToS, PToI)
- [ ] NSSF monthly contribution file (CSV) in NSSF column order, with Member IDs
- [ ] WHT engine with DTA treaty rate override
- [ ] Payroll module with Phase 1 pension rates and KHR 1.2m wage cap, escalation schedule loaded
- [ ] Seniority indemnity accrual with June/December payout runs
- [ ] Bank integration: at least statement import; ABA PayWay or KHQR for collection
- [ ] Hosting decision documented: vendor SaaS / regional cloud / local DC / on-prem with rationale
- [ ] Backup: daily, encrypted, off-site copy, 10-year retention, restore tested
- [ ] SSO + MFA on all ERP user accounts
- [ ] Role-based access aligned with SoD (sales / AP / GL / payments segregated)
- [ ] Audit log (user, time, before/after) on posted-transaction changes
- [ ] Secrets management for bank/API credentials
- [ ] Disaster recovery plan with documented RPO/RTO targets
- [ ] Data subject access procedure (ahead of formal PDPL)
- [ ] Inspection-readiness: records accessible for GDT/ACAR inspection within Cambodia
