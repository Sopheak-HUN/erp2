---
name: cambodia-erp-standard
description: Produce Cambodia-compliant ERP specifications, requirement checklists, configuration standards, tech-stack recommendations, and custom-build architecture guides covering Finance & Accounting (CIFRS/CIFRS for SMEs), Tax & GDT compliance (VAT, CIT, WHT, salary tax, e-filing), Payroll & HR (NSSF, Cambodian Labor Law), Inventory/Sales/Procurement, the technology stack (ERP platform selection — Odoo, SAP, QuickBooks, Xero, MAQSU, ERPNext — hosting, bank/payment integration via ABA PayWay and KHQR/Bakong, Khmer Unicode rendering, GDT e-Filing connectivity, security), and custom builds on Laravel 13 + Nuxt 4 + PrimeVue with multi-tenancy. Trigger this skill whenever the user asks about ERP, accounting system, business software requirements, technology choices, or custom-build architecture for Cambodia — including phrases like "Cambodian chart of accounts," "GDT-compliant invoicing," "Khmer/Riel reporting," "NSSF payroll module," "tax module for Cambodia," "ERP localization Cambodia," "which ERP for my Cambodian company," "Odoo Cambodia," "tech stack for accounting software in Cambodia," "Laravel ERP Cambodia," "Nuxt PrimeVue accounting SaaS," "multi-tenant ERP Cambodia," or any request to design, evaluate, configure, select, build, or document an ERP/accounting system intended for use in the Kingdom of Cambodia. Use this skill even when the user only mentions one domain (e.g. just payroll, just invoicing, or just platform selection) as long as Cambodia is the jurisdiction. Output is structured markdown — specs, checklists, gap analyses, recommendations, or architecture documents — not Word/Excel files unless the user explicitly requests them.
---

# Cambodia ERP Standard

This skill helps Claude produce specifications, requirement checklists, configuration standards, and gap analyses for ERP and accounting systems deployed in Cambodia. It encodes current (2026) Cambodian regulatory requirements so that the output is jurisdictionally accurate rather than generic.

## When to apply this skill

Apply this skill whenever an ERP, accounting, payroll, invoicing, or business-system question is scoped to Cambodia. The user may phrase the request in many ways — designing a new system, localizing a global ERP (Odoo, SAP, Oracle, QuickBooks, Xero, Microsoft Dynamics), evaluating vendors, writing an RFP, or auditing an existing setup for compliance. As long as Cambodia is the operating jurisdiction, the Cambodian regulatory requirements below must drive the answer.

## Core principles for every output

These principles cut across all ERP domains and must be reflected in any spec or checklist this skill produces:

1. **Bilingual + dual-currency by default.** Cambodian law requires accounting records in Khmer and Khmer Riel (KHR). Companies with foreign involvement may additionally keep records in English and USD if properly authorised, but Riel cannot be dropped — financial statements must present KHR as at least one currency, and tax filings to the GDT must ultimately be in KHR. The ERP must therefore support: (a) Khmer-language UI/printouts for statutory documents, (b) multi-currency with KHR as a presentation currency, and (c) NBC (National Bank of Cambodia) end-of-month exchange rate for FX translation.
2. **Calendar fiscal year (1 Jan – 31 Dec) is the default.** Any deviation requires Ministry of Economy and Finance (MEF) approval. The system should not assume a configurable fiscal year is automatically usable.
3. **Self-declaration regime.** Cambodia operates only one tax regime — self-declaration — with taxpayers classified as Small, Medium, or Large based on turnover, sector, and legal form. ERP configuration must align with the entity's classification because filing forms, deadlines, and audit thresholds differ.
4. **Monthly cadence is the heartbeat.** VAT, withholding tax, salary tax (ToS), prepayment of CIT (1% of monthly turnover), NSSF, and pension contributions are all monthly filings due by the **20th of the following month** for hard filing or the **25th** when using e-Filing. Late filing incurs 10% additional tax + 1.5% monthly interest minimum (rising for negligence). Reporting calendars and reminders in the ERP should target these dates.
5. **Accrual basis is mandatory for tax.** GDT requires accrual accounting per CIFRS. Cash-basis-only configurations are non-compliant for any entity above small-taxpayer status. ERPs must support accrued income/expense vouchers separately from actual invoices and reconcile timing differences for VAT input/output.
6. **Document integrity matters.** Tax invoices must be issued within 7 days of shipment, service rendering, or payment (whichever is earliest). Sequential invoice numbering, VATIN of buyer (for B2B input credit), and Khmer-language fields are mandatory. The ERP must enforce these — they cannot be optional fields.
7. **GDT e-Filing is the integration target.** Cambodia is digitising tax administration; monthly returns are filed through the GDT e-Filing portal and the GDT Taxpayer Application. An ERP that cannot export the required schedules (purchase ledger, sales ledger, WHT schedule, salary tax schedule) in a format compatible with e-Filing creates manual rework.
8. **NSSF is separate from tax.** Social security (occupational risk, healthcare, pension) is administered by the Ministry of Labour, not GDT. The ERP payroll module needs a parallel filing path to NSSF with its own employer/employee account numbers and contribution schedules.

## How to structure an output

When asked to produce a Cambodia ERP standard, spec, or checklist, follow this template unless the user asks for something narrower. Adapt depth to the user's stated scope.

```markdown
# Cambodia ERP Standard — [Project / Company Name]

## 1. Scope and entity profile
- Legal form, sector, taxpayer classification (Small / Medium / Large)
- Functional currency, presentation currencies
- Fiscal year, audit obligation (CIFRS vs CIFRS for SMEs)
- Number of employees, NSSF registration status
- ERP platform under consideration / in use

## 2. Finance & Accounting requirements
[Pull from references/finance-accounting.md]

## 3. Tax & GDT compliance requirements
[Pull from references/tax-gdt.md]

## 4. Payroll & HR requirements
[Pull from references/payroll-hr.md]

## 5. Inventory, Sales & Procurement requirements
[Pull from references/inventory-sales-procurement.md]

## 6. Tech stack and platform requirements
[Pull from references/tech-stack.md]

## 7. Cross-cutting requirements (language, currency, e-Filing, audit trail)

## 8. Compliance checklist (pass/fail items per domain)

## 9. Open questions / data needed from the customer
```

For shorter requests (e.g. "give me a checklist for GDT-compliant invoicing"), skip the section structure and produce just the relevant checklist drawn from the appropriate reference file.

## Reference files — when to read them

The detail for each domain lives in `references/`. Read the file relevant to the user's request before drafting. **Always read the reference file fresh; do not rely on memory of Cambodian rates or thresholds — they change annually.**

- `references/finance-accounting.md` — CIFRS vs CIFRS for SMEs, chart of accounts structure, statutory financial statements, ACAR filing, audit thresholds, accrual rules, FX translation.
- `references/tax-gdt.md` — VAT (10% standard, 0% / state-borne categories), CIT 20%, salary tax brackets, withholding tax rates (resident and non-resident), prepayment of CIT, e-Filing, monthly/annual deadlines, penalties, capital gains tax timing.
- `references/payroll-hr.md` — NSSF contribution rates (occupational risk 0.8% employer, healthcare 2.6%/2.6% employer/employee, pension Phase 1 2%/2% with KHR 400k–1.2m wage band, escalating to Phase 2 4%/4% from Oct 2027), Tax on Salary brackets in KHR, dependent allowances, fringe benefit tax, minimum wage, working hours, seniority indemnity, Labour Law touchpoints.
- `references/inventory-sales-procurement.md` — Tax invoice format and timing, purchase/sales ledger requirements for VAT return, WHT capture at procurement, customs (GDCE) interaction, e-commerce VAT, accrued expense vouchers, three-way matching with WHT logic.
- `references/tech-stack.md` — ERP platform selection (Odoo, SAP B1, Dynamics 365, NetSuite, QuickBooks, Xero, MAQSU, ERPNext, CUSCEN), Cambodian implementation partners, Khmer Unicode font and PDF rendering, ABA PayWay / KHQR / Bakong / ACLEDA bank integration, hosting and data residency, GDT e-Filing connectivity, NSSF file format, security/SoD/audit-log, recommended stack templates by company profile.
- `references/custom-build-laravel-nuxt.md` — **Read this instead of (or in addition to) `tech-stack.md` when the user has committed to building their own ERP on Laravel 13 + Nuxt 4 + PrimeVue with multi-tenancy.** Covers when to build vs buy, version/compatibility notes (Laravel 13 PHP 8.3+, Nuxt 4 + PrimeVue v4 with the known `@primevue/nuxt-module` Nuxt 4 caveat), multi-tenancy strategy choice (shared schema vs schema-per-tenant vs DB-per-tenant), `stancl/tenancy` integration, recommended Laravel and Nuxt packages, database design patterns (UUID v7, money columns, sequential invoice numbering, COA-as-data, tax-config-as-data), API design, hosting, security, and the must-build Cambodia-specific features (GDT e-Filing export, NSSF file, WHT engine, Khmer-safe PDF, NBC FX, seniority indemnity, ABA PayWay, KHQR).

If the user's request crosses domains, read multiple files. If the request is general ("build me an ERP standard for Cambodia"), read all five core references (`finance-accounting`, `tax-gdt`, `payroll-hr`, `inventory-sales-procurement`, `tech-stack`). Read `custom-build-laravel-nuxt.md` additionally when the user has chosen the build path on this stack.

## Style guidance for outputs

- **Use checklists for compliance items**, prose for context. Compliance items are verifiable; users need to tick them off.
- **State rates and thresholds with dates** ("as of 2026, the 0% bracket for Tax on Salary applies to monthly income up to KHR 1,500,000"). Dated facts let the reader know what to re-verify next year.
- **Distinguish "must," "should," and "nice-to-have"** so the user can prioritise. Statutory items are "must"; CIFRS judgement areas are "should"; convenience features are "nice-to-have".
- **Reference the governing instrument by name when known** — Prakas number, Sub-Decree, Law on Taxation article, NSSF directive — so the customer can verify with their auditor or tax advisor.
- **End with open questions.** Cambodian ERP work always depends on entity-specific facts (QIP status, sector, taxpayer size) the user may not have stated. Surface what you'd need to know to finalise the spec rather than guessing.
- **Don't pretend to be the auditor.** Recommend that the customer confirm final compliance positions with a Cambodian licensed accountant (KICPAA member) or tax agent. The skill produces design specs, not audit opinions.

## Things to avoid

- Don't copy generic IFRS/global-ERP requirements without filtering for Cambodian specifics. CIFRS = IFRS without modification, but Cambodia adds language, currency, ACAR filing, and tax-accounting linkage rules that pure IFRS doesn't cover.
- Don't assume USD-only operation is compliant. It isn't — KHR presentation is required.
- Don't quote tax rates or NSSF percentages from memory if the user is going to act on them; always confirm against the reference file in this skill, and remind the user to check the GDT website (`tax.gov.kh`) and NSSF announcements for any change after the skill's last update.
- Don't conflate "Tax on Salary" with personal income tax — Cambodia has no PIT in the conventional sense; ToS is collected at source by the employer.
- Don't recommend storing accounting records exclusively offshore. Cambodian law requires records to be available for GDT inspection in Cambodia.
- Don't recommend an ERP platform without considering local-partner availability in Cambodia. A platform with no local implementer (or only a remote Singapore/Bangkok team) is a much riskier choice than one with active Phnom Penh partners — even if the platform itself is technically stronger. Always pair platform recommendations with a note on the partner ecosystem.
- Don't assume modern PDF rendering "just works" for Khmer script. Validate the invoice template with real Khmer text before sign-off — older wkhtmltopdf and similar engines mis-render the script.
