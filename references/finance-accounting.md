# Finance & Accounting — Cambodia ERP Requirements

This reference encodes the accounting and financial reporting rules an ERP must support to be compliant in Cambodia. Last verified against public sources in May 2026; users should re-check ACAR (`acar.gov.kh`) and the MEF for any amendments.

## Regulatory framework

- **Governing law:** Law on Accounting and Auditing (2016), and Anukret (Sub-Decree) on the Organization and Functioning of ACAR.
- **Regulator:** Accounting and Auditing Regulator (ACAR), under the Non-Bank Financial Services Authority. ACAR replaced the National Accounting Council (NAC) in January 2021.
- **Accounting standards body:** Cambodian Accounting Standards Board, within ACAR. Adopts IFRS without modification as **CIFRS**, and IFRS for SMEs without modification as **CIFRS for SMEs**.
- **Profession:** Only KICPAA (Kampuchea Institute of Certified Public Accountants and Auditors) members may provide accounting and auditing services.

## Which standard applies to which entity

| Entity type | Required standard |
|---|---|
| Listed companies, banks, microfinance deposit-taking institutions (MDIs), insurance companies, large private companies | **CIFRS** (mandatory) |
| Non-deposit-taking microfinance companies | CIFRS for SMEs permitted; may opt for CIFRS |
| SMEs subject to statutory audit | **CIFRS for SMEs** (mandatory) — full IFRS not required, but permitted |
| Non-PIE SMEs without statutory audit | CIFRS for SMEs or CIFRS (choice) |

**Statutory audit triggers** (a private entity meeting two of three criteria must be audited):
- Annual turnover above the threshold set by MEF
- Total assets above the threshold set by MEF
- More than 100 employees

For not-for-profit entities, the third criterion drops to more than 20 employees.

## Language, currency, and records

- Records must be maintained in **Khmer language** and **Khmer Riel (KHR)**.
- Companies with foreign involvement may additionally keep records in English and USD if authorised, but KHR remains required.
- Tax must ultimately be paid in KHR; foreign currency amounts convert at the NBC exchange rate published on the last working day of the relevant month.
- Records must be retained and accessible for GDT and ACAR inspection within Cambodia.
- IFRS for SMEs is officially translated into Khmer by ACAR; full IFRS is accessed in English (no Khmer translation).

## Fiscal year

- Default: **1 January – 31 December**.
- For new companies: incorporation date to 31 December of the year of incorporation.
- Alternative year-end requires MEF approval — the ERP should treat this as the exception, not a freely configurable option.

## Chart of accounts (COA) design

CIFRS / CIFRS for SMEs does not prescribe a national COA, but the ERP COA must support:

1. **Statement of Financial Position** classification per IAS 1 / Section 4 of IFRS for SMEs — current vs non-current presentation.
2. **Statement of Comprehensive Income** — function-of-expense or nature-of-expense format (the choice must be documented and consistent).
3. **Cash flow statement** mapping (operating / investing / financing).
4. **Tax-account linkage** — separate GL accounts for:
   - Input VAT recoverable
   - Output VAT payable
   - WHT receivable / payable (split by category and rate)
   - CIT prepayment (1% monthly turnover)
   - Salary tax payable
   - NSSF payable (split: occupational risk, healthcare, pension — employer vs employee portions)
   - Deferred tax assets / liabilities
5. **Bilingual account names** — Khmer and English captions on each account.
6. **Multi-currency revaluation** at month-end using NBC closing rates with gain/loss to finance income/cost.

A typical structure for an SME uses a 6–8 digit hierarchical code with class digits aligned to: 1 = Equity, 2 = Non-current assets, 3 = Inventory, 4 = Receivables/Payables, 5 = Financial, 6 = Expenses, 7 = Revenue (this is the historic French-influenced PCG structure familiar to Cambodian accountants). The ERP need not adopt this exactly, but Cambodian-trained accountants will expect to recognise it.

## Mandatory financial statements

Per CIFRS / CIFRS for SMEs, a complete set comprises:
- Statement of Financial Position
- Statement of Comprehensive Income (or separate P&L + OCI)
- Statement of Changes in Equity
- Statement of Cash Flows
- Notes to the financial statements (accounting policies + breakdowns)

For first-time CIFRS for SMEs adopters, the date of transition is disclosed. Many SMEs use a transition date of 1 January 2019.

## ACAR filing

Enterprises submit financial statements to ACAR within the statutory deadline after year-end. The ERP should produce a clean export (PDF or structured form) of the audited financials in the ACAR-acceptable format. Confirm current deadline and submission portal — ACAR's e-filing requirements have been evolving.

## Accrual basis — tax / accounting alignment

The GDT (Law on Taxation) **requires** accrual accounting in accordance with CIFRS. Key implications for the ERP:

- Cash-basis-only operation is not compliant above small-taxpayer status.
- Accrued income / expense vouchers must be recorded **separately from actual invoices**, with sufficient information (voucher number, date, amount, description) to support monthly tax filing.
- When the actual invoice is received later, the system must reverse / replace the accrual without double-counting and re-tag the document for VAT input recognition (VAT input is only claimable on the actual invoice, not the accrual).
- WHT on accrued expenses must be declared and paid based on each accrued transaction — the ERP must produce a monthly WHT schedule from accruals, not just from paid invoices.
- A reconciliation between accrued declarations and invoice-based VAT / WHT declarations must be reproducible from the system.

## Specific CIFRS / CIFRS for SMEs areas the ERP must handle

- **Inventory:** Section 13 of IFRS for SMEs (or IAS 2). Cost using FIFO or weighted average; LIFO is prohibited. Write-down to lower of cost and selling price less costs to complete and sell, with impairment assessment at each year-end.
- **PPE and depreciation:** Cost model with component depreciation supported. Note that **tax depreciation rates** (per Law on Taxation) differ from accounting depreciation, so the ERP must track both and generate the deferred tax computation.
- **Leases:** IFRS 16 for CIFRS preparers; IFRS for SMEs Section 20 (operating/finance lease distinction retained) for SME preparers.
- **Revenue:** IFRS 15 (CIFRS) or Section 23 (CIFRS for SMEs).
- **Foreign currency:** Functional currency determination (often USD for foreign-invested companies); KHR remains a presentation currency.
- **Employee benefits / seniority indemnity:** Cambodia's mandated seniority benefit is a long-term employee benefit. The ERP should compute the present value of future payments using a discount rate disclosed in the financials (the illustrative ACAR example uses ~8.5%, but the rate is judgement-based).
- **Income tax:** Current tax (20% standard) and deferred tax on temporary differences (especially depreciation, accruals, provisions).

## Audit trail and document retention

- Every accounting entry must be traceable to a source document.
- Source documents (invoices, vouchers, contracts, bank statements) must be retained — the standard Cambodian retention period is **10 years** for accounting and tax records.
- The ERP must produce a tamper-evident audit log showing user, timestamp, before/after values for changes to posted transactions.
- Posted-period closing must be enforced — re-opening a closed period requires elevated permission and must be logged.

## Compliance checklist — Finance & Accounting

- [ ] System maintains records in Khmer and KHR (with optional English/USD)
- [ ] Calendar fiscal year is default; alternative requires MEF-approval workflow
- [ ] COA supports CIFRS / CIFRS for SMEs statement structures
- [ ] Tax accounts segregated (VAT in/out, WHT, CIT prepayment, salary tax, NSSF)
- [ ] Bilingual account names (Khmer + English)
- [ ] Multi-currency with month-end NBC rate revaluation
- [ ] Accrual basis supported with accrued voucher entity separate from invoice
- [ ] Tax depreciation tracked separately from book depreciation; deferred tax computed
- [ ] Seniority indemnity provision module (long-term employee benefit)
- [ ] Period close enforced; re-open requires authorisation and is logged
- [ ] Financial statement pack (5 statements + notes) generated from system
- [ ] 10-year electronic retention of source documents with controlled deletion
- [ ] Export format for ACAR submission
- [ ] User access control consistent with KICPAA professional independence requirements (if applicable)
