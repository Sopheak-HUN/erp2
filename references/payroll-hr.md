# Payroll & HR — Cambodia ERP Requirements

This reference covers the payroll, social-security, and labour-law features an ERP must implement to operate in Cambodia. Verified May 2026; reconfirm NSSF rates and salary-tax brackets annually because both follow a multi-year escalation schedule.

## Regulators

- **Tax on Salary (ToS):** General Department of Taxation (GDT), Ministry of Economy and Finance.
- **NSSF (occupational risk, healthcare, pension):** National Social Security Fund, under the Ministry of Labour and Vocational Training (MoLVT).
- **Labour Law:** MoLVT enforces the 1997 Labour Law (as amended).

The payroll module must support **two parallel filing tracks** — one to GDT (ToS), one to NSSF (social security) — with different account numbers, schedules, and portals.

## Tax on Salary (ToS) — see also `tax-gdt.md`

Employer withholds and remits monthly.

### Resident monthly progressive brackets (2026)

| Monthly taxable salary (KHR) | Rate |
|---|---|
| 0 – 1,500,000 | 0% |
| 1,500,001 – 2,000,000 | 5% |
| 2,000,001 – 8,500,000 | 10% |
| 8,500,001 – 12,500,000 | 15% |
| Above 12,500,000 | 20% |

(The 0% threshold was set to KHR 1,500,000 by Sub-Decree 196 in 2022. Confirm current Sub-Decree each year — earlier figures of KHR 1.3m appear in older sources and must not be used.)

### Quick formulas (lump-sum method)

For a taxable salary `T` in KHR:

| Bracket | Tax payable |
|---|---|
| T ≤ 1,500,000 | 0 |
| 1,500,000 < T ≤ 2,000,000 | (T × 5%) − 75,000 |
| 2,000,000 < T ≤ 8,500,000 | (T × 10%) − 175,000 |
| 8,500,000 < T ≤ 12,500,000 | (T × 15%) − 600,000 |
| T > 12,500,000 | (T × 20%) − 1,225,000 |

(Constants derived from progressive bracket structure; the ERP should compute progressively rather than relying on these short-cuts, but they are useful for validation.)

### Non-resident

- Flat **20%** of Cambodian-sourced employment income, no progressive brackets, no dependent allowance.

### Dependent allowances (resident only)

Deducted from gross salary **before** applying the brackets:

- **KHR 150,000/month per dependent child** under 14, or under 25 if a full-time student at a recognised institution.
- **KHR 150,000/month for a non-working spouse.**

Documentation (birth certificates, school certificates, spouse income declaration) should be retained in the employee record.

### Fringe Benefits Tax

- **20% flat** on the value of fringe benefits granted to employees, **borne by the employer** (separate from the employee's ToS).
- Covers housing, vehicles, household staff, meals, education for family, low-interest loans, etc.
- Some benefits are exempt if provided "for the convenience of the employer" — narrow exemption, verify with tax advisor.

### Tax residency rule

An individual is a Cambodian tax resident if **any** of: domiciled in Cambodia, principal home in Cambodia, or present for more than 182 days in the calendar year. Both employee and employer must formally declare residency status with GDT; without declaration, the higher non-resident flat rate applies.

## NSSF — National Social Security Fund

NSSF administers three schemes that the ERP payroll must integrate:

### 1. Occupational Risk scheme

- **Rate:** 0.8% of gross salary, **employer-only**.
- Covers workplace injuries and occupational illness.
- Cap: contribution computed on gross salary up to an NSSF-defined ceiling — confirm current ceiling.

### 2. Healthcare scheme

- **Rate:** 2.6% employer + 2.6% employee = 5.2% combined.
- Some sources report a combined 1.3% + 1.3% — this reflects an earlier rate structure. The current standard rate is 2.6% each side; the ERP should make the rate configurable rather than hard-coded.
- Covers medical care, maternity, sickness benefits.

### 3. Pension scheme

The phased schedule per Sub-Decree 32 and Prakas 168/170 (launched 1 October 2022):

| Phase | Period | Total rate | Employer | Employee |
|---|---|---|---|---|
| Phase 1 | Oct 2022 – Sep 2027 (years 1–5) | 4% | 2% | 2% |
| Phase 2 | Oct 2027 – Sep 2032 (years 6–10) | 8% | 4% | 4% |
| Phase 3 | From Oct 2032 | 10.75% | split equally | split equally |
| Subsequent | Every 10 years after | +2.75% | split equally | split equally |

- **As of 2026 we are in Phase 1:** 2% employer + 2% employee = 4% combined.
- The ERP must store the schedule and auto-apply the next phase by effective date.
- **Contributable wage cap (pension scheme):** monthly earnings between **KHR 400,000 floor and KHR 1,200,000 ceiling** are subject to pension contribution. Earnings outside this band do not generate additional pension contribution — so the maximum monthly pension contribution per employee in 2026 is approximately KHR 24,000 employer + 24,000 employee.
- This cap is for pension only; occupational risk and healthcare contributions follow their own ceiling rules — verify current Prakas.

### Combined typical employer cost

For 2026 the typical employer-borne payroll cost beyond gross salary is approximately:

- Occupational risk: 0.8%
- Healthcare: 2.6%
- Pension: 2.0%
- **Total employer NSSF: ~5.4%**

Plus employee withheld portions (healthcare 2.6% + pension 2.0% = 4.6%) deducted from gross before computing ToS taxable base.

### Coverage threshold

NSSF registration is mandatory for any enterprise employing **at least 8 employees** (historical threshold — confirm current MoLVT Prakas, as expansion to smaller employers is in motion).

### NSSF filing

- **Deadline:** Monthly contributions due by the **15th of the following month** (note: earlier than the GDT 20th/25th — the ERP should not conflate the two).
- **Format:** NSSF online portal accepts CSV upload of employee list with NSSF member ID, salary, contribution split.
- Each employee must have an NSSF Member ID; the ERP must capture and validate this.

## Minimum wage

- **2026:** KHR 839,809/month (~USD 208) for the textile, garment, footwear, travel goods, and bag manufacturing sector — the only sector with a statutory cross-industry minimum wage.
- Other sectors negotiate collectively or follow industry custom (the practical floor is around USD 210 for general work).
- The minimum wage is reviewed annually; the ERP should let HR override the floor by sector and year.

## Working hours and overtime

- **Standard:** 8 hours/day, 48 hours/week, with one 1-hour lunch break per 8-hour shift.
- **Overtime rates:**
  - Regular weekday overtime: **150%** of normal hourly rate
  - Night work (10pm–5am): **200%**
  - Weekly day off / public holiday: **200%**
- **Weekly day off:** Minimum 24 consecutive hours per week, normally Sunday.
- **Annual leave:** 1.5 days per month of service = 18 days/year. Plus paid public holidays (~24 days per Royal Government Notification; varies year to year).
- **Sick leave:** Paid at 100% for first month, 60% for months 2–3, unpaid for month 4–6.
- **Maternity leave:** 90 days at 50% of salary (employer + NSSF contribution).

## Seniority indemnity (mandatory severance system)

This is one of the most distinctive features of Cambodian payroll and the ERP **must** support it.

Under the Labour Law (as amended in 2018 by Notification 443) and subsequent MoLVT instructions:

- **Undetermined Duration Contracts (UDC):** Employer pays seniority indemnity **twice yearly** — in June and December — equal to **7.5 days of average wage and benefits** each payment (15 days/year total).
- **Back-pay seniority (for service before 1 January 2019):** Payable in instalments per MoLVT schedule (varying by sector — textile/garment/footwear has a separate accelerated schedule).
- **Fixed Duration Contracts (FDC):** End-of-contract severance equal to **5%** of total wages paid during the contract.
- **Termination by employer (non-FDC):** Additional severance based on length of service.

The ERP must:
- Track service start date and accumulate seniority indemnity each month.
- Generate the June and December payment runs automatically.
- Maintain the FDC vs UDC distinction at contract level.
- Compute back-pay seniority where applicable.

For financial reporting, the accumulated obligation is a long-term employee benefit under IFRS for SMEs Section 28, present-valued at the disclosed discount rate.

## Payroll cycle requirements

A compliant Cambodian payroll run must:

1. Compute gross earnings (basic + allowances + overtime + bonuses + commissions).
2. Identify exempt allowances (per Tax on Salary regulations — meal allowance up to a cap, transport, uniform, etc.).
3. Compute fringe benefits and FBT 20% (employer-borne, separate).
4. Deduct employee NSSF contributions (healthcare 2.6%, pension 2%).
5. Apply dependent allowances (KHR 150,000 per qualifying dependent / spouse).
6. Compute taxable salary base.
7. Apply progressive ToS brackets (resident) or flat 20% (non-resident).
8. Compute employer NSSF (occupational 0.8% + healthcare 2.6% + pension 2%).
9. Accrue monthly seniority indemnity (UDC: 15 days/year ÷ 12 ≈ 1.25 days per month).
10. Produce payslip in Khmer (and English if applicable).
11. Generate GDT salary tax schedule, NSSF contribution file, and bank transfer instruction.

## Payslip mandatory content

- Employee name, NSSF Member ID, position, contract type (UDC/FDC).
- Pay period.
- Gross earnings breakdown (basic, OT, allowances, bonus).
- Fringe benefits value (if any).
- Employee NSSF deduction (healthcare + pension), with rates shown.
- Dependent allowances applied.
- Taxable salary and ToS withheld.
- Net pay.
- Employer NSSF contributions (informational).
- Year-to-date totals.
- Bilingual layout (Khmer + English) is the practical norm.

## Required ERP outputs for filings

- **Monthly to GDT:** ToS schedule (employee list + gross + taxable + tax withheld) for inclusion in monthly tax return by 20th/25th.
- **Monthly to NSSF:** Contribution file (member ID + gross subject to contribution + split) by 15th.
- **Annual to GDT:** ToS reconciliation, with details of fringe benefits and FBT.
- **On demand:** Seniority indemnity ledger per employee; service certificates; tax residency declarations.

## Compliance checklist — Payroll & HR

- [ ] Employee master with NSSF Member ID, GDT TIN, contract type (UDC/FDC), residency status
- [ ] Dependent register (children with DOB, school proof; spouse income status) with annual review
- [ ] Tax-residency declaration captured and filed for each employee
- [ ] Tax on Salary progressive brackets for residents (KHR 1.5m / 2m / 8.5m / 12.5m)
- [ ] Flat 20% for non-residents
- [ ] Fringe Benefit Tax 20% accrued separately, employer-borne
- [ ] NSSF occupational risk 0.8% (employer only)
- [ ] NSSF healthcare 2.6% employer + 2.6% employee (or current Prakas rate)
- [ ] NSSF pension Phase 1 rates (2%/2%) with KHR 400k–1.2m wage band cap, auto-escalation to Phase 2 (4%/4%) from Oct 2027
- [ ] Minimum wage enforced (KHR 839,809 garment sector; override per sector)
- [ ] Overtime rates 150% weekday, 200% night/weekend/holiday
- [ ] Annual leave accrual 1.5 days/month
- [ ] Seniority indemnity monthly accrual; bi-annual payout (June, December) for UDC
- [ ] FDC end-of-contract 5% severance
- [ ] Pre-2019 back-pay seniority schedule (where applicable)
- [ ] Bilingual payslips (Khmer + English)
- [ ] Monthly NSSF file due 15th; ToS schedule due 20th/25th — separate calendar reminders
- [ ] Year-end ToS reconciliation file
- [ ] Maternity 90 days at 50%; sick leave tiered (100/60/0)
- [ ] All wage amounts in KHR; if paid in USD, monthly NBC rate applied to KHR equivalent for tax/NSSF
