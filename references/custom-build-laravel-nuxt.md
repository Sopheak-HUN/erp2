# Custom Build — Laravel 13 + Nuxt 4 + PrimeVue + Multi-Tenancy

This reference is the architecture guide when the customer has decided to **build their own ERP** on Laravel 13 (API-only) and Nuxt 4 with PrimeVue, with multi-tenancy. It is the alternative path to the platform selection in `tech-stack.md` — read this file when the user has committed to this stack, and otherwise stay with the comparative content in `tech-stack.md`.

Verified May 2026.

## Why this stack — and when it's the wrong choice

Build-your-own makes sense when one or more of these hold:
- The customer is a software house or has experienced PHP/JS engineers in-house.
- The target market is **multiple Cambodian SMEs** (you're building a SaaS-style multi-tenant product), not a one-company internal system.
- The product needs Cambodia-specific behaviour (Khmer-first UI, ABA PayWay deep integration, GDT e-Filing export tuned to local accountants' workflow) that off-the-shelf ERPs handle awkwardly.
- A 12–24 month build runway and ongoing maintenance budget are acceptable.

Build-your-own is the **wrong** choice when:
- The customer wants the system live in 1–3 months. Buy Odoo with a local partner instead.
- It's one company internal use only. Custom build cost rarely justifies vs Odoo / ERPNext.
- The team has no senior Laravel + Vue background. The compliance complexity (CIFRS + GDT + NSSF + multi-tenancy + Khmer UX) will compound junior mistakes.

If the user has not stated which side of this they're on, flag it as an open question rather than silently committing them to a 12-month engineering project.

## Stack confirmation — versions and gotchas (as of May 2026)

- **Laravel 13** — released 17 March 2026. Requires **PHP 8.3+**. Bug fixes through Q3 2027, security patches through Q1 2028. Headline features: native PHP 8 Attributes for models, Cache::touch(), Reverb database driver for horizontal WebSocket scaling, stable Laravel AI SDK, typed configuration retrieval. Largely non-breaking from Laravel 12.
- **Nuxt 4** — stable. Vue 3 / Vite-based. Compatible with Vue 3 ecosystem.
- **PrimeVue v4** — current major. Use **`@primevue/nuxt-module`** for installation. ⚠ **Known gotcha:** the official `@primevue/nuxt-module` historically declared `^3.0.0` as its Nuxt peer dependency, so on Nuxt 4 you may see a warning like `Module @primevue/nuxt-module is disabled due to incompatibility issues`. The components themselves work — the workaround is either (a) wait for / use a version of the module with Nuxt 4 in peer-deps (check `@primevue/nuxt-module` >= 4.5 and the GitHub tracker), or (b) install PrimeVue directly (`primevue` + `@primeuix/themes`) and register it via a custom Nuxt plugin without the module. Either way, PrimeVue v4 itself is fully Vue-3 / Nuxt-4 compatible. Verify the current state of issue #7918 on `primefaces/primevue` before committing.
- **PrimeVue v4 renames:** Calendar → DatePicker, Dropdown → Select, InputSwitch → ToggleSwitch, OverlayPanel → Popover, Sidebar → Drawer, Tabs/Accordion redesigned. If you cargo-cult v3 docs you'll hit deprecation warnings.
- **PrimeFlex v3 is not compatible** with PrimeVue v4 — upgrade to PrimeFlex v4 or drop it in favour of Tailwind.
- **Theme:** Aura is the default PrimeVue v4 preset; theming reimplemented with CSS layers and design tokens. Use `@primeuix/themes`.

## Multi-tenancy strategy — pick before writing migrations

This decision is irreversible-ish, so make it explicitly with the customer before any schema work begins.

### Strategy comparison

| Strategy | Isolation | Infra cost | Operational complexity | Best fit for a Cambodian ERP SaaS |
|---|---|---|---|---|
| **Single DB, shared schema, `tenant_id` column** | Logical only | Lowest | Lowest infra; highest query-discipline burden | Many small SME tenants; tolerant of shared performance characteristics; cheap to host on one MySQL/Postgres instance |
| **Single DB, schema-per-tenant** (PostgreSQL) | Schema-level | Low | Medium — schema migrations multiply | Mid-size tenants needing logical separation without DB sprawl; PostgreSQL only |
| **Database-per-tenant** | Strongest | Highest | Highest — N migrations, N backups, connection pool sizing | Large tenants, regulated tenants (e.g. MFIs subject to NBC), or where the customer has explicit data-residency demands per tenant |

**Recommendation for a Cambodia ERP SaaS targeting SMEs:** start with **single DB + `tenant_id` scoping**, and design the data model so that migration to database-per-tenant is possible later (avoid cross-tenant foreign keys, use UUIDs not auto-increment IDs for tenant-owned entities, never put tenant data in central tables). Reserve database-per-tenant for enterprise customers who will pay for it.

### Package choice

The Cambodian Laravel community converges on two packages:

- **`stancl/tenancy` (Tenancy for Laravel v3)** — recommended. Supports all three strategies, automatic bootstrapping (DB connection switching, cache/filesystem/queue isolation), tested deeply, and has a known integration story for Spatie packages (laravel-permission, laravel-medialibrary, laravel-activitylog). Hostname/subdomain or header-based tenant identification.
- **`spatie/laravel-multitenancy`** — simpler, lighter, fewer features. Fine if you want explicit control and a less opinionated stack.

For an ERP that will integrate Spatie's permissions and audit log (you'll want both), `stancl/tenancy` is the safer pick because the integration patterns are documented.

### Tenant identification

Options, in rough preference order for a Cambodian B2B ERP:

1. **Subdomain** — `acmetrading.yourerp.com.kh`. Clean, professional, easy SSL with wildcard cert (Let's Encrypt or your provider). Standard with stancl/tenancy.
2. **Path prefix** — `yourerp.com.kh/acmetrading/...`. Simpler DNS but uglier URLs and harder to scope cookies.
3. **Header / JWT claim** — `X-Tenant-ID` or a claim in the access token. Works for API-only deployments without browser DNS. Often used **in addition** to subdomain identification so the Nuxt frontend can resolve tenancy at API time.

For a Laravel API + Nuxt SPA, the typical pattern is: Nuxt resolves tenant from the visited subdomain (`acmetrading.yourerp.com.kh`), authenticates the user, and includes the tenant context in every API call via the bearer token. Laravel verifies the token, resolves the tenant from the JWT claim or the `Host` header, and switches the tenancy context for the request.

## Layered architecture

```
┌──────────────────────────────────────────────────────────────┐
│ Nuxt 4 (SPA / SSR) + PrimeVue v4                             │
│  - Pinia for state, Vue Router (file-based via Nuxt)         │
│  - Khmer + English i18n (Nuxt i18n)                          │
│  - Auth via @sidebase/nuxt-auth or custom composable         │
└────────────────────────────┬─────────────────────────────────┘
                             │ HTTPS + Bearer JWT / Sanctum SPA
┌────────────────────────────▼─────────────────────────────────┐
│ Nginx / Caddy (TLS termination, Khmer font headers, CORS)    │
└────────────────────────────┬─────────────────────────────────┘
                             │
┌────────────────────────────▼─────────────────────────────────┐
│ Laravel 13 API (PHP 8.3, FPM or Octane)                      │
│  - stancl/tenancy bootstrap (subdomain / header identifier)  │
│  - Sanctum or Passport for auth; spatie/laravel-permission   │
│  - Domain modules: Accounting, Tax, Payroll, Inventory, etc. │
│  - Queue: Redis-backed Horizon                               │
│  - Reverb (WebSocket) for real-time POS / notifications      │
└────────────────────────────┬─────────────────────────────────┘
                             │
        ┌────────────────────┼──────────────────────┐
        │                    │                      │
┌───────▼────────┐  ┌────────▼────────┐  ┌──────────▼─────────┐
│ MySQL 8 / Pg   │  │ Redis           │  │ S3-compatible store│
│ central DB +   │  │ - cache         │  │ - invoices, docs,  │
│ tenant DBs (or │  │ - sessions      │  │ - exports          │
│ shared schema) │  │ - queues        │  │ tenant-prefixed    │
└────────────────┘  └─────────────────┘  └────────────────────┘
```

## Laravel side — package recommendations

These are the packages a Cambodia ERP build will almost certainly need. Keep the dependency list tight; every package is something to maintain.

### Core / cross-cutting
- **`stancl/tenancy` ^3.x** — multi-tenancy (chosen above).
- **`laravel/sanctum`** — SPA auth (Nuxt-friendly) for browser-based tenants. Use **`laravel/passport`** if you need full OAuth2 (third-party integrations).
- **`spatie/laravel-permission`** — RBAC. Critical for SoD enforcement (a sales clerk shouldn't post journals). With tenancy, scope permission cache per tenant.
- **`spatie/laravel-activitylog`** — audit log. Required for entities subject to statutory audit. Configure per-tenant DB connection.
- **`spatie/laravel-medialibrary`** — invoice PDFs, supplier documents, tax certificates. Custom URL generator for tenant-aware paths.
- **`laravel/horizon`** — queue dashboard.
- **`laravel/telescope`** — debug locally; **disable in production tenants**.
- **`laravel/reverb`** — WebSockets for POS, real-time inventory updates, notifications. Reverb database driver (new in Laravel 13) enables horizontal scaling.

### Domain-specific
- **`barryvdh/laravel-snappy`** ❌ avoid — wkhtmltopdf mis-renders Khmer script. Use one of:
  - **`spatie/browsershot`** (Chromium-based, renders Khmer correctly), or
  - **`mpdf/mpdf`** with a Khmer Unicode font embedded (Khmer OS, Battambang, Hanuman), or
  - **WeasyPrint** via a Python sidecar service if you want strong CSS-Paged-Media support.
- **`maatwebsite/excel`** (Laravel Excel) — for GDT e-Filing CSV/Excel exports.
- **`league/csv`** — simpler, faster CSV for monthly tax schedules.
- **`brick/money` or `moneyphp/money`** — money handling with KHR and USD. Don't use floats for amounts.
- **`nesbot/carbon`** — built into Laravel; configure Cambodia locale (`Asia/Phnom_Penh`).
- **`khmernlp/khmer-text`** or similar — optional, for Khmer text normalisation if doing search.

### Testing
- **PHPUnit 12** (Laravel 13 requires `phpunit/phpunit: ^12.0`) or **Pest 3**.
- **Spatie's `pest-plugin-snapshots`** for invoice PDF regression testing.
- Multi-tenant test harness — `stancl/tenancy` ships with test traits; use them or you will leak state between tests.

## Nuxt side — package recommendations

- **`@primevue/nuxt-module`** (with the Nuxt 4 caveat above) or manual plugin install.
- **`@primeuix/themes`** — Aura preset.
- **`@pinia/nuxt`** — state management.
- **`@vueuse/nuxt`** — utility composables.
- **`@sidebase/nuxt-auth`** or `nuxt-auth-utils` — Sanctum / JWT auth helpers. Many Laravel+Nuxt teams just write a thin custom composable instead.
- **`@nuxtjs/i18n`** — Khmer + English. Lazy-load locale messages; Khmer is large.
- **`@nuxtjs/tailwindcss`** — pair with PrimeVue (drop PrimeFlex). PrimeVue v4 ships its own utilities, and Tailwind covers everything else.
- **`@formkit/nuxt`** + `@formkit/primevue` — if you want declarative forms (optional; PrimeVue's own form components are fine too).
- **`@unhead/vue`** — meta tags (built into Nuxt 4).
- **`vue-i18n-routing`** — for `/km` vs `/en` URL paths if you want path-based locale switching.

### Khmer-first UX details

- **Font:** load `Noto Sans Khmer` or `Battambang` from Google Fonts (with `display: swap`) for the web, and the same fonts embedded in PDF generation so on-screen and printed documents match.
- **Locale:** `km-KH` for Khmer, `en-US` for English. Use ICU number/date formatting; Khmer numerals (០-៩) are optional — most Cambodian businesses use Arabic numerals (0-9) for accounting figures.
- **PrimeVue locale:** ship a `km` locale file (PrimeVue lets you override translation tokens for DataTable filters, calendar month names, validation messages).
- **Direction:** LTR (Khmer is left-to-right). No RTL concerns.
- **Buddhist calendar year:** offer a display toggle (Gregorian default, Buddhist Era 2569 in 2026 as alternate) for statutory documents that historically use BE.

## Database design — multi-tenant ERP specifics

A few patterns that come up repeatedly in Cambodian ERP work:

### Mandatory columns on every tenant-owned table
- `id` (UUID v7 recommended — sortable, no leakage, plays well with sharding later).
- `tenant_id` (FK to `tenants`).
- `created_at`, `updated_at`, `deleted_at` (soft deletes — never hard-delete posted accounting records).
- `created_by_user_id`, `updated_by_user_id` (audit).
- `version` (optimistic locking for concurrent edits, especially on invoices and journals).

### Invoice numbering
- Sequential per tenant per fiscal year per document type.
- Implement via a `document_sequences` table with row-level lock (`SELECT ... FOR UPDATE`) or a database sequence (Postgres) — **never** rely on `MAX(invoice_number) + 1`, which races under load and produces gaps.
- Voided invoices retain their number with `status = 'void'`. The audit trail must show the void event with timestamp and user.

### Money columns
- Store as `BIGINT` in minor units (KHR has no fractional unit, but USD has cents — use a single approach for both). Or use `DECIMAL(18, 4)` if you prefer the readability and accept the storage cost.
- Always store the currency code alongside the amount. Never store "amount in USD equivalent" alone — keep both original and KHR-presentation columns when FX is involved.

### Chart of accounts as data
- Don't hard-code the COA. Tenants will want to add/edit accounts. Model as `accounts` table with `code`, `name_km`, `name_en`, `parent_id`, `type` (Asset/Liability/Equity/Revenue/Expense), `is_tax_account` (flag), `is_postable` (leaf vs header).
- Ship a default Cambodian COA template as a seeder so tenants don't start from blank.

### Tax configuration as data
- VAT rate, WHT rates, ToS brackets, NSSF rates — all in versioned reference tables with `effective_from` and `effective_to`. When MEF changes a Sub-Decree, you add a new row; old transactions still resolve to historical rates.

### Period close
- A `posting_periods` table per tenant with status (Open / Soft-Closed / Hard-Closed). Posting into a closed period requires an explicit unlock action, logged in `activity_log`.

## API design

- **REST + JSON:API** or **GraphQL** — for a domain-rich ERP, REST with consistent resource conventions and an OpenAPI spec usually outperforms GraphQL operationally. Tools: `dedoc/scramble` for OpenAPI generation from Laravel.
- **Versioning:** `/api/v1/...` from day 1 even if there's only one version.
- **Tenant context:** include the tenant ID in every response envelope (`meta.tenant_id`) so the Nuxt client can detect drift.
- **Idempotency:** `Idempotency-Key` header on all POSTs that create transactions (invoices, payments). Critical for offline-tolerant POS.
- **Rate limiting:** per-tenant + per-user limits in Laravel rate limiter.
- **Pagination:** cursor-based for ledgers (large tables), page-based for masters.

## Hosting and deployment

For a Cambodian SaaS ERP, realistic 2026 options:

- **AWS Singapore (ap-southeast-1)** — best latency from Phnom Penh among major clouds; mature managed RDS, ElastiCache, S3, SES.
- **GCP Singapore / Jakarta** — comparable.
- **Local Cambodian data centres** (Telcotech, EZECOM Cloud, SmartCloud) — preferred if a tenant insists on data residency. Provision separate tenant DB there if needed.
- **Laravel Forge / Ploi / Laravel Vapor** — Forge / Ploi for VPS provisioning on Hetzner / DigitalOcean / AWS; Vapor for serverless. Vapor works with `stancl/tenancy` but adds complexity — only worth it if you expect very spiky load.

### Production checklist
- PHP 8.3+ with OPcache and JIT enabled.
- Laravel Octane (Swoole or RoadRunner) if request latency matters — caution: Octane changes some Laravel assumptions, test thoroughly with tenancy bootstrappers.
- Redis cluster for cache, sessions, queues, Reverb scaling.
- MySQL 8 with `utf8mb4` (Khmer works only with `utf8mb4`, not `utf8`) or PostgreSQL 16.
- Nginx or Caddy in front; Cloudflare for DDoS + CDN for Nuxt static assets (be careful caching anything tenant-scoped).
- Backups: daily logical (mysqldump / pg_dump), with retention matching the 10-year tax record requirement. Encrypted, off-site, restore-tested quarterly.
- Health checks: `/up` endpoint + tenant-aware probe.

## Security — minimum bar for an accounting system

- HTTPS everywhere; HSTS; secure cookies.
- MFA mandatory for users with posting permissions; recommended for all.
- Passkeys / WebAuthn — Laravel 13 has improved Passkey support; offer it as an option.
- All tenant data encrypted at rest (DB-level encryption for managed RDS; LUKS for self-hosted).
- Secrets in a vault (AWS Secrets Manager, Doppler, or HashiCorp Vault). Never in `.env` committed to git.
- Daily dependency scanning (`composer audit`, `npm audit`); patch within 7 days of CVE disclosure for high-severity.
- Penetration test before onboarding the first paying tenant; annually thereafter.
- Activity log on every posted-transaction mutation (write, void, period reopen, permission grant).

## Cambodia-specific build items

These are the must-build features that don't exist in any off-the-shelf Laravel package and you'll need to write yourself:

1. **GDT e-Filing export** — Maatwebsite Excel generators producing the exact column order and formats the GDT Taxpayer Application accepts for monthly VAT, WHT, ToS, PToI schedules.
2. **NSSF contribution file** — CSV in NSSF's column order, with employer/employee splits per scheme.
3. **WHT engine** — invoice-time rate selection based on supplier residency, registration status, and payment category; DTA treaty rate override with attached residency certificate.
4. **Tax invoice generator** — Khmer "Tax Invoice" heading, sequential number per tenant per fiscal year per document type, both parties' VATIN, KHR equivalent at NBC end-of-month rate.
5. **NBC FX rate ingestion** — daily scraper or manual entry of NBC published rates; month-end rate flagged for FX revaluation.
6. **Seniority indemnity accrual job** — monthly accrual per employee; June and December payment runs for UDC contracts.
7. **ABA PayWay integration** — payment links on invoices, KHQR generation for in-person, webhook reconciliation against AR.
8. **Bakong / KHQR generation** — EMVCo QR with Bakong merchant identifiers.
9. **Khmer-safe PDF rendering** — Browsershot or mPDF with embedded fonts; visual regression tests in CI.
10. **Period close / reopen workflow** — with mandatory reason, approver, and audit entry.

## Compliance checklist — Custom Laravel/Nuxt ERP for Cambodia

- [ ] Laravel 13 on PHP 8.3+; Nuxt 4 / Vue 3; PrimeVue v4 with Aura theme
- [ ] PrimeVue Nuxt module compatibility resolved (module or manual plugin install)
- [ ] Multi-tenancy strategy explicitly chosen and documented (recommend single-DB + `tenant_id` scoping for SME SaaS)
- [ ] `stancl/tenancy` integrated with subdomain identification and JWT tenant claim
- [ ] Cross-tenant data leakage tested (automated test: tenant A cannot read tenant B records via any route)
- [ ] Spatie permission package scoped per tenant
- [ ] Spatie activity log on every posted-transaction mutation
- [ ] UUID v7 IDs on tenant-owned entities
- [ ] Money stored as integer minor units or DECIMAL(18,4) with currency code; floats banned
- [ ] Sequential invoice numbering via locked sequence (no `MAX()+1` race)
- [ ] Soft deletes on accounting records; hard delete prohibited
- [ ] Posting periods with Open / Soft-Close / Hard-Close states
- [ ] Khmer-safe PDF rendering via Browsershot or mPDF with embedded fonts (no wkhtmltopdf)
- [ ] Khmer + English i18n in Nuxt with PrimeVue `km` locale file
- [ ] MySQL with `utf8mb4` everywhere (or PostgreSQL with UTF-8)
- [ ] Sanctum (or Passport) auth; MFA mandatory for accounting roles; Passkeys offered
- [ ] OpenAPI spec auto-generated; `/api/v1/...` versioning from day 1
- [ ] Idempotency-Key on transaction-creating endpoints
- [ ] Per-tenant rate limiting
- [ ] Redis-backed queues with Horizon; Reverb for real-time
- [ ] Tax configuration tables versioned with effective_from / effective_to
- [ ] Chart of accounts as data; seeder ships a default Cambodian COA template
- [ ] GDT e-Filing export modules (VAT, WHT, ToS, PToI) tested against current GDT schemas
- [ ] NSSF file export tested against current NSSF portal format
- [ ] WHT engine with resident / non-resident / DTA treaty logic
- [ ] NBC FX rate ingestion and month-end revaluation
- [ ] Seniority indemnity monthly accrual + bi-annual payout (UDC)
- [ ] ABA PayWay integration with webhook reconciliation
- [ ] KHQR generation for invoices and POS
- [ ] Hosting on AWS/GCP Singapore or local Cambodian DC; documented data-residency posture
- [ ] Daily encrypted off-site backups; 10-year retention; restore tested quarterly
- [ ] Per-tenant access to records on demand for GDT/ACAR inspection
- [ ] Composer + npm audit in CI; high-severity CVEs patched within 7 days
- [ ] Pen test scheduled before first paying tenant
