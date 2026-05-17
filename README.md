# Cambodia ERP — Multi-Tenant SaaS

A multi-tenant ERP system built for Cambodian businesses, compliant with GDT, NSSF, and CIFRS requirements.

## Tech Stack

- **Backend:** Laravel 13 (PHP 8.4) — API-only
- **Frontend:** Nuxt 4 + PrimeVue v4 + Tailwind CSS
- **Database:** PostgreSQL 16
- **Cache/Queue:** Redis 7
- **Multi-tenancy:** stancl/tenancy (single DB + tenant_id scoping)

## Quick Start

### Prerequisites

- Docker & Docker Compose

### Setup

```bash
# 1. Clone and enter the project
cd cambodia-erp

# 2. Start all services
docker compose up -d

# 3. Install frontend dependencies
docker compose exec web npm install

# 4. Run migrations
docker compose exec app php artisan migrate

# 5. Seed default data (tax rates, COA template)
docker compose exec app php artisan db:seed
```

### Access

| Service | URL |
|---------|-----|
| API | http://localhost:8000/api/v1/health |
| Frontend | http://localhost:3001 |
| Mailpit | http://localhost:8025 |
| PostgreSQL | localhost:5432 |

## Project Structure

```
cambodia-erp/
├── api/                        # Laravel 13 API
│   ├── app/
│   │   ├── Models/
│   │   │   ├── Accounting/     # Account, JournalEntry, PostingPeriod
│   │   │   ├── Tax/            # TaxRate, Invoice
│   │   │   ├── Payroll/        # Employee, Payslip
│   │   │   ├── Inventory/      # Product
│   │   │   ├── Tenant/         # Tenant model
│   │   │   └── Concerns/       # BelongsToTenant, HasUuidV7
│   │   ├── Http/Controllers/Api/V1/
│   │   └── Services/           # DocumentSequenceService, etc.
│   ├── database/migrations/
│   │   └── tenant/             # Tenant-scoped migrations
│   └── routes/
│       ├── api.php             # API v1 routes
│       └── tenant.php          # Tenant routes
├── web/                        # Nuxt 4 Frontend
│   ├── pages/
│   ├── locales/                # en.json, km.json
│   ├── plugins/primevue.ts
│   └── nuxt.config.ts
├── docker/
│   ├── app/Dockerfile          # PHP 8.4
│   └── web/Dockerfile          # Node 20
└── docker-compose.yml
```

## Modules

- **Finance & Accounting** — Chart of Accounts, GL, Journal Entries, Posting Periods, Financial Statements
- **Tax & GDT** — VAT, CIT, WHT, Tax Invoices, GDT e-Filing export
- **Payroll & HR** — Employees, Tax on Salary, NSSF contributions, Seniority Indemnity
- **Inventory & Sales** — Products, Stock, Sales Orders, Purchase Orders

## Cambodia Compliance

- Bilingual (Khmer + English) throughout
- Dual-currency (USD + KHR) with NBC exchange rates
- GDT e-Filing export (VAT, WHT, ToS schedules)
- NSSF contribution file generation
- Sequential tax invoice numbering (database-locked)
- Khmer-safe PDF rendering (Browsershot)
- 10-year record retention
