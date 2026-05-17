<template>
  <div class="min-h-screen bg-gray-50 p-6">
    <!-- Header -->
    <div class="mb-6 flex items-center justify-between">
      <div>
        <h1 class="text-2xl font-bold text-gray-900">{{ t('settings.title') }}</h1>
        <p class="mt-1 text-sm text-gray-500">{{ t('settings.subtitle') }}</p>
      </div>
      <div class="flex items-center gap-3">
        <button @click="saveSettings" class="inline-flex items-center gap-2 rounded-lg bg-blue-600 px-4 py-2 text-sm font-medium text-white shadow-sm hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-blue-500">
          <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" /></svg>
          {{ t('settings.save_changes') }}
        </button>
      </div>
    </div>

    <!-- Success Toast -->
    <div v-if="savedToast" class="mb-5 rounded-xl border border-green-200 bg-green-50 p-4 flex items-center gap-3">
      <svg class="h-5 w-5 text-green-600 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" /></svg>
      <span class="text-sm font-medium text-green-800">{{ t('settings.saved_success') }}</span>
    </div>

    <div class="grid grid-cols-1 gap-6 lg:grid-cols-3">
      <!-- Left Column: Company Profile -->
      <div class="lg:col-span-2 space-y-6">

        <!-- Company Profile Card -->
        <div class="rounded-xl border border-gray-200 bg-white shadow-sm overflow-hidden">
          <div class="border-b border-gray-100 bg-gray-50 px-5 py-4 flex items-center gap-3">
            <div class="flex h-8 w-8 items-center justify-center rounded-lg bg-blue-100">
              <svg class="h-4 w-4 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4" /></svg>
            </div>
            <h2 class="font-semibold text-gray-900">{{ t('settings.company_profile') }}</h2>
          </div>
          <div class="p-5 space-y-4">
            <div class="grid grid-cols-1 gap-4 sm:grid-cols-2">
              <div>
                <label class="block text-xs font-medium text-gray-700 mb-1.5">{{ t('settings.company_name_en') }} *</label>
                <input v-model="form.company_name_en" type="text" class="w-full rounded-lg border border-gray-300 px-3 py-2 text-sm focus:border-blue-500 focus:outline-none focus:ring-1 focus:ring-blue-500" />
              </div>
              <div>
                <label class="block text-xs font-medium text-gray-700 mb-1.5">{{ t('settings.company_name_km') }}</label>
                <input v-model="form.company_name_km" type="text" class="w-full rounded-lg border border-gray-300 px-3 py-2 text-sm font-khmer focus:border-blue-500 focus:outline-none focus:ring-1 focus:ring-blue-500" />
              </div>
            </div>
            <div class="grid grid-cols-1 gap-4 sm:grid-cols-2">
              <div>
                <label class="block text-xs font-medium text-gray-700 mb-1.5">{{ t('settings.vatin') }}</label>
                <div class="relative">
                  <input v-model="form.vatin" type="text" placeholder="K001-900000000" class="w-full rounded-lg border border-gray-300 px-3 py-2 text-sm font-mono focus:border-blue-500 focus:outline-none focus:ring-1 focus:ring-blue-500" />
                  <div class="absolute right-3 top-1/2 -translate-y-1/2">
                    <span v-if="form.vatin" class="inline-flex items-center rounded-full bg-green-100 px-2 py-0.5 text-xs font-medium text-green-700">
                      {{ t('settings.verified') }}
                    </span>
                  </div>
                </div>
              </div>
              <div>
                <label class="block text-xs font-medium text-gray-700 mb-1.5">{{ t('settings.taxpayer_classification') }}</label>
                <select v-model="form.taxpayer_type" class="w-full rounded-lg border border-gray-300 px-3 py-2 text-sm focus:border-blue-500 focus:outline-none focus:ring-1 focus:ring-blue-500">
                  <option value="medium">Medium Taxpayer</option>
                  <option value="large">Large Taxpayer</option>
                  <option value="small">Small Taxpayer</option>
                  <option value="sme">SME</option>
                </select>
              </div>
            </div>
            <div class="grid grid-cols-1 gap-4 sm:grid-cols-2">
              <div>
                <label class="block text-xs font-medium text-gray-700 mb-1.5">{{ t('settings.nssf_employer_id') }}</label>
                <input v-model="form.nssf_employer_id" type="text" placeholder="ER-000000" class="w-full rounded-lg border border-gray-300 px-3 py-2 text-sm font-mono focus:border-blue-500 focus:outline-none focus:ring-1 focus:ring-blue-500" />
              </div>
              <div>
                <label class="block text-xs font-medium text-gray-700 mb-1.5">{{ t('settings.registration_no') }}</label>
                <input v-model="form.registration_no" type="text" class="w-full rounded-lg border border-gray-300 px-3 py-2 text-sm focus:border-blue-500 focus:outline-none focus:ring-1 focus:ring-blue-500" />
              </div>
            </div>
            <div>
              <label class="block text-xs font-medium text-gray-700 mb-1.5">{{ t('settings.address') }}</label>
              <textarea v-model="form.address" rows="2" class="w-full rounded-lg border border-gray-300 px-3 py-2 text-sm focus:border-blue-500 focus:outline-none focus:ring-1 focus:ring-blue-500"></textarea>
            </div>
            <div class="grid grid-cols-1 gap-4 sm:grid-cols-3">
              <div>
                <label class="block text-xs font-medium text-gray-700 mb-1.5">{{ t('settings.phone') }}</label>
                <input v-model="form.phone" type="text" class="w-full rounded-lg border border-gray-300 px-3 py-2 text-sm focus:border-blue-500 focus:outline-none focus:ring-1 focus:ring-blue-500" />
              </div>
              <div>
                <label class="block text-xs font-medium text-gray-700 mb-1.5">{{ t('settings.email') }}</label>
                <input v-model="form.email" type="email" class="w-full rounded-lg border border-gray-300 px-3 py-2 text-sm focus:border-blue-500 focus:outline-none focus:ring-1 focus:ring-blue-500" />
              </div>
              <div>
                <label class="block text-xs font-medium text-gray-700 mb-1.5">{{ t('settings.website') }}</label>
                <input v-model="form.website" type="text" class="w-full rounded-lg border border-gray-300 px-3 py-2 text-sm focus:border-blue-500 focus:outline-none focus:ring-1 focus:ring-blue-500" />
              </div>
            </div>
          </div>
        </div>

        <!-- Currency & FX Card -->
        <div class="rounded-xl border border-gray-200 bg-white shadow-sm overflow-hidden">
          <div class="border-b border-gray-100 bg-gray-50 px-5 py-4 flex items-center gap-3">
            <div class="flex h-8 w-8 items-center justify-center rounded-lg bg-green-100">
              <svg class="h-4 w-4 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z" /></svg>
            </div>
            <h2 class="font-semibold text-gray-900">{{ t('settings.currency_fx') }}</h2>
          </div>
          <div class="p-5 space-y-4">
            <div class="grid grid-cols-1 gap-4 sm:grid-cols-3">
              <div>
                <label class="block text-xs font-medium text-gray-700 mb-1.5">{{ t('settings.functional_currency') }}</label>
                <select v-model="form.functional_currency" class="w-full rounded-lg border border-gray-300 px-3 py-2 text-sm focus:border-blue-500 focus:outline-none">
                  <option value="USD">USD – US Dollar</option>
                  <option value="KHR">KHR – Cambodian Riel</option>
                </select>
              </div>
              <div>
                <label class="block text-xs font-medium text-gray-700 mb-1.5">{{ t('settings.reporting_currency') }}</label>
                <select v-model="form.reporting_currency" class="w-full rounded-lg border border-gray-300 px-3 py-2 text-sm focus:border-blue-500 focus:outline-none">
                  <option value="USD">USD – US Dollar</option>
                  <option value="KHR">KHR – Cambodian Riel</option>
                </select>
              </div>
              <div>
                <label class="block text-xs font-medium text-gray-700 mb-1.5">{{ t('settings.fx_rate_source') }}</label>
                <select v-model="form.fx_rate_source" class="w-full rounded-lg border border-gray-300 px-3 py-2 text-sm focus:border-blue-500 focus:outline-none">
                  <option value="nbc">NBC (National Bank of Cambodia)</option>
                  <option value="manual">Manual</option>
                </select>
              </div>
            </div>

            <!-- NBC Rate Display -->
            <div class="rounded-xl border border-blue-200 bg-blue-50 p-4">
              <div class="flex items-start justify-between">
                <div>
                  <p class="text-sm font-semibold text-blue-900">{{ t('settings.nbc_rate_label') }}</p>
                  <p class="text-xs text-blue-600 mt-0.5">{{ t('settings.nbc_rate_note') }}</p>
                </div>
                <button class="text-xs text-blue-600 font-medium hover:underline">{{ t('settings.refresh_rate') }}</button>
              </div>
              <div class="mt-3 grid grid-cols-3 gap-3">
                <div v-for="rate in nbcRates" :key="rate.pair" class="rounded-lg bg-white border border-blue-100 p-3">
                  <p class="text-xs text-gray-500 font-medium">{{ rate.pair }}</p>
                  <p class="mt-0.5 text-base font-bold text-gray-900">{{ formatNum(rate.rate) }}</p>
                  <p class="text-xs text-gray-400 mt-0.5">{{ rate.updated }}</p>
                </div>
              </div>
            </div>

            <!-- Manual Rate Override -->
            <div v-if="form.fx_rate_source === 'manual'">
              <label class="block text-xs font-medium text-gray-700 mb-1.5">{{ t('settings.manual_rate_usd_khr') }}</label>
              <div class="flex items-center gap-3">
                <span class="text-sm text-gray-500">1 USD =</span>
                <input v-model.number="form.manual_rate" type="number" class="w-32 rounded-lg border border-gray-300 px-3 py-2 text-sm focus:border-blue-500 focus:outline-none" />
                <span class="text-sm text-gray-500">KHR</span>
              </div>
            </div>
          </div>
        </div>

        <!-- Fiscal Year Card -->
        <div class="rounded-xl border border-gray-200 bg-white shadow-sm overflow-hidden">
          <div class="border-b border-gray-100 bg-gray-50 px-5 py-4 flex items-center gap-3">
            <div class="flex h-8 w-8 items-center justify-center rounded-lg bg-purple-100">
              <svg class="h-4 w-4 text-purple-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" /></svg>
            </div>
            <h2 class="font-semibold text-gray-900">{{ t('settings.fiscal_year') }}</h2>
          </div>
          <div class="p-5 space-y-4">
            <div class="grid grid-cols-1 gap-4 sm:grid-cols-2">
              <div>
                <label class="block text-xs font-medium text-gray-700 mb-1.5">{{ t('settings.fy_start_month') }}</label>
                <select v-model="form.fy_start_month" class="w-full rounded-lg border border-gray-300 px-3 py-2 text-sm focus:border-blue-500 focus:outline-none">
                  <option v-for="(month, i) in months" :key="i" :value="i + 1">{{ month }}</option>
                </select>
              </div>
              <div>
                <label class="block text-xs font-medium text-gray-700 mb-1.5">{{ t('settings.accounting_basis') }}</label>
                <select v-model="form.accounting_basis" class="w-full rounded-lg border border-gray-300 px-3 py-2 text-sm focus:border-blue-500 focus:outline-none">
                  <option value="accrual">Accrual Basis</option>
                  <option value="cash">Cash Basis</option>
                </select>
              </div>
            </div>
            <div class="rounded-lg bg-gray-50 border border-gray-200 p-3 text-sm text-gray-600">
              {{ t('settings.fy_note', { year: new Date().getFullYear(), start: months[form.fy_start_month - 1], end: months[(form.fy_start_month + 10) % 12] }) }}
            </div>
          </div>
        </div>
      </div>

      <!-- Right Column: Tax Config Summary -->
      <div class="space-y-5">

        <!-- Tax Configuration Summary -->
        <div class="rounded-xl border border-gray-200 bg-white shadow-sm overflow-hidden">
          <div class="border-b border-gray-100 bg-gray-50 px-5 py-4 flex items-center gap-3">
            <div class="flex h-8 w-8 items-center justify-center rounded-lg bg-orange-100">
              <svg class="h-4 w-4 text-orange-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 7h6m0 10v-3m-3 3h.01M9 17h.01M9 14h.01M12 14h.01M15 11h.01M12 11h.01M9 11h.01M7 21h10a2 2 0 002-2V5a2 2 0 00-2-2H7a2 2 0 00-2 2v14a2 2 0 002 2z" /></svg>
            </div>
            <h2 class="font-semibold text-gray-900">{{ t('settings.tax_config') }}</h2>
          </div>
          <div class="p-4 space-y-3">
            <div v-for="item in taxConfigSummary" :key="item.label" class="flex items-center justify-between rounded-lg bg-gray-50 px-3 py-2.5">
              <div>
                <p class="text-xs font-medium text-gray-700">{{ item.label }}</p>
                <p class="text-xs text-gray-400">{{ item.sublabel }}</p>
              </div>
              <span :class="item.badgeClass" class="inline-flex items-center rounded-full px-2.5 py-0.5 text-xs font-medium">
                {{ item.value }}
              </span>
            </div>
          </div>
        </div>

        <!-- NSSF Config -->
        <div class="rounded-xl border border-gray-200 bg-white shadow-sm overflow-hidden">
          <div class="border-b border-gray-100 bg-gray-50 px-5 py-4 flex items-center gap-3">
            <div class="flex h-8 w-8 items-center justify-center rounded-lg bg-emerald-100">
              <svg class="h-4 w-4 text-emerald-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z" /></svg>
            </div>
            <h2 class="font-semibold text-gray-900">{{ t('settings.nssf_config') }}</h2>
          </div>
          <div class="p-4 space-y-3">
            <div v-for="item in nssfConfig" :key="item.label" class="flex items-center justify-between rounded-lg bg-gray-50 px-3 py-2.5">
              <p class="text-xs font-medium text-gray-700">{{ item.label }}</p>
              <span class="text-xs font-bold text-emerald-700">{{ item.value }}</span>
            </div>
            <p class="text-xs text-gray-400 px-1">{{ t('settings.nssf_cap_note') }}</p>
          </div>
        </div>

        <!-- Localization -->
        <div class="rounded-xl border border-gray-200 bg-white shadow-sm overflow-hidden">
          <div class="border-b border-gray-100 bg-gray-50 px-5 py-4 flex items-center gap-3">
            <div class="flex h-8 w-8 items-center justify-center rounded-lg bg-blue-100">
              <svg class="h-4 w-4 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 5h12M9 3v2m1.048 9.5A18.022 18.022 0 016.412 9m6.088 9h7M11 21l5-10 5 10M12.751 5C11.783 10.77 8.07 15.61 3 18.129" /></svg>
            </div>
            <h2 class="font-semibold text-gray-900">{{ t('settings.localization') }}</h2>
          </div>
          <div class="p-4 space-y-3">
            <div>
              <label class="block text-xs font-medium text-gray-700 mb-1.5">{{ t('settings.default_language') }}</label>
              <select v-model="form.default_language" class="w-full rounded-lg border border-gray-300 px-3 py-2 text-sm focus:border-blue-500 focus:outline-none">
                <option value="en">English</option>
                <option value="km">ខ្មែរ (Khmer)</option>
              </select>
            </div>
            <div>
              <label class="block text-xs font-medium text-gray-700 mb-1.5">{{ t('settings.date_format') }}</label>
              <select v-model="form.date_format" class="w-full rounded-lg border border-gray-300 px-3 py-2 text-sm focus:border-blue-500 focus:outline-none">
                <option value="DD/MM/YYYY">DD/MM/YYYY</option>
                <option value="YYYY-MM-DD">YYYY-MM-DD</option>
                <option value="MM/DD/YYYY">MM/DD/YYYY</option>
              </select>
            </div>
            <div>
              <label class="block text-xs font-medium text-gray-700 mb-1.5">{{ t('settings.timezone') }}</label>
              <select v-model="form.timezone" class="w-full rounded-lg border border-gray-300 px-3 py-2 text-sm focus:border-blue-500 focus:outline-none">
                <option value="Asia/Phnom_Penh">Asia/Phnom_Penh (UTC+7)</option>
                <option value="UTC">UTC</option>
              </select>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
</template>

<script setup lang="ts">
definePageMeta({ middleware: 'auth' })

const { t } = useI18n()

const savedToast = ref(false)

const form = ref({
  company_name_en: 'Cambodia ERP Solutions Co., Ltd.',
  company_name_km: 'ក្រុមហ៊ុន Cambodia ERP Solutions',
  vatin: 'K001-900123456',
  taxpayer_type: 'medium',
  nssf_employer_id: 'ER-001234',
  registration_no: 'Co-1234/2020',
  address: 'No. 88, Street 19, BKK1, Chamkarmon, Phnom Penh, Cambodia',
  phone: '+855 23 123 456',
  email: 'finance@cambodia-erp.com',
  website: 'https://cambodia-erp.com',
  functional_currency: 'USD',
  reporting_currency: 'USD',
  fx_rate_source: 'nbc',
  manual_rate: 4100,
  fy_start_month: 1,
  accounting_basis: 'accrual',
  default_language: 'en',
  date_format: 'DD/MM/YYYY',
  timezone: 'Asia/Phnom_Penh',
})

const nbcRates = ref([
  { pair: 'USD/KHR', rate: 4106.00, updated: '2026-05-16' },
  { pair: 'EUR/KHR', rate: 4650.00, updated: '2026-05-16' },
  { pair: 'THB/KHR', rate: 118.50, updated: '2026-05-16' },
])

const months = ['January', 'February', 'March', 'April', 'May', 'June', 'July', 'August', 'September', 'October', 'November', 'December']

const taxConfigSummary = computed(() => [
  { label: 'VAT Rate', sublabel: 'Standard rate', value: '10%', badgeClass: 'bg-blue-100 text-blue-700' },
  { label: 'CIT Rate', sublabel: 'Corporate income tax', value: '20%', badgeClass: 'bg-purple-100 text-purple-700' },
  { label: 'WHT – Services', sublabel: 'Resident payees', value: '15%', badgeClass: 'bg-orange-100 text-orange-700' },
  { label: 'PToI', sublabel: 'Prepayment of Tax on Income', value: '1%', badgeClass: 'bg-emerald-100 text-emerald-700' },
  { label: 'VAT Registration', sublabel: form.value.vatin, value: 'Registered', badgeClass: 'bg-green-100 text-green-700' },
  { label: 'Taxpayer Class', sublabel: 'GDT classification', value: form.value.taxpayer_type === 'medium' ? 'Medium' : form.value.taxpayer_type === 'large' ? 'Large' : 'Small', badgeClass: 'bg-gray-100 text-gray-700' },
])

const nssfConfig = ref([
  { label: 'Health Care – Employer', value: '1.3%' },
  { label: 'Health Care – Employee', value: '1.3%' },
  { label: 'Occupational Risk – Employer', value: '0.8%' },
  { label: 'Pension – Employer', value: '4.0%' },
  { label: 'Pension – Employee', value: '4.0%' },
  { label: 'Salary Ceiling (NSSF)', value: '$1,200 / mo' },
])

function saveSettings() {
  savedToast.value = true
  setTimeout(() => { savedToast.value = false }, 4000)
}

function formatNum(n: number) {
  return new Intl.NumberFormat('en-US', { minimumFractionDigits: 2, maximumFractionDigits: 2 }).format(n)
}
</script>

<i18n lang="json">
{
  "en": {
    "settings": {
      "title": "Settings",
      "subtitle": "Company profile, tax configuration, currency and localization",
      "save_changes": "Save Changes",
      "saved_success": "Settings saved successfully.",
      "company_profile": "Company Profile",
      "company_name_en": "Company Name (English)",
      "company_name_km": "Company Name (Khmer)",
      "vatin": "VATIN (VAT Identification No.)",
      "verified": "Verified",
      "taxpayer_classification": "Taxpayer Classification (GDT)",
      "nssf_employer_id": "NSSF Employer ID",
      "registration_no": "MOC Registration No.",
      "address": "Registered Address",
      "phone": "Phone",
      "email": "Email",
      "website": "Website",
      "currency_fx": "Currency & FX Rates",
      "functional_currency": "Functional Currency",
      "reporting_currency": "Reporting Currency",
      "fx_rate_source": "FX Rate Source",
      "nbc_rate_label": "NBC Official Rates",
      "nbc_rate_note": "Rates sourced from National Bank of Cambodia (NBC)",
      "refresh_rate": "Refresh",
      "manual_rate_usd_khr": "Manual Rate (USD → KHR)",
      "fiscal_year": "Fiscal Year",
      "fy_start_month": "Fiscal Year Start Month",
      "accounting_basis": "Accounting Basis",
      "fy_note": "Current FY runs {start} – {end} {year}",
      "tax_config": "Tax Configuration Summary",
      "nssf_config": "NSSF Configuration",
      "nssf_cap_note": "NSSF contributions are calculated on capped gross salary of $1,200/month.",
      "localization": "Localization",
      "default_language": "Default Language",
      "date_format": "Date Format",
      "timezone": "Timezone"
    }
  },
  "km": {
    "settings": {
      "title": "ការកំណត់",
      "subtitle": "ប្រូហ្វីលក្រុមហ៊ុន ពន្ធ រូបិយប័ណ្ណ និងការកំណត់ប្រព័ន្ធ",
      "save_changes": "រក្សាទុកការផ្លាស់ប្តូរ",
      "saved_success": "ការកំណត់ត្រូវបានរក្សាទុក",
      "company_profile": "ប្រូហ្វីលក្រុមហ៊ុន",
      "company_name_en": "ឈ្មោះក្រុមហ៊ុន (អង់គ្លេស)",
      "company_name_km": "ឈ្មោះក្រុមហ៊ុន (ខ្មែរ)",
      "vatin": "VATIN",
      "verified": "បានផ្ទៀងផ្ទាត់",
      "taxpayer_classification": "ចំណាត់ថ្នាក់អ្នកបង់ពន្ធ",
      "nssf_employer_id": "លេខ NSSF ម្ចាស់ការ",
      "registration_no": "លេខចុះបញ្ជីក្រសួងពាណិជ្ជកម្ម",
      "address": "អាស័យដ្ឋាន",
      "phone": "ទូរស័ព្ទ",
      "email": "អ៊ីមែល",
      "website": "គេហទំព័រ",
      "currency_fx": "រូបិយប័ណ្ណ និងអត្រាប្ដូប្រាក់",
      "functional_currency": "រូបិយប័ណ្ណមុខងារ",
      "reporting_currency": "រូបិយប័ណ្ណរបាយការណ៍",
      "fx_rate_source": "ប្រភពអត្រាប្ដូប្រាក់",
      "nbc_rate_label": "អត្រាផ្លូវការ NBC",
      "nbc_rate_note": "អត្រាពីធនាគារជាតិកម្ពុជា",
      "refresh_rate": "ធ្វើបច្ចុប្បន្នភាព",
      "manual_rate_usd_khr": "អត្រាកំណត់ (USD → KHR)",
      "fiscal_year": "ឆ្នាំហិរញ្ញវត្ថុ",
      "fy_start_month": "ខែចាប់ផ្ដើមឆ្នាំ",
      "accounting_basis": "គ្រឹះគណនេយ្យ",
      "fy_note": "ឆ្នាំហិរញ្ញវត្ថុ {start} – {end} {year}",
      "tax_config": "សង្ខេបការកំណត់ពន្ធ",
      "nssf_config": "ការកំណត់ NSSF",
      "nssf_cap_note": "NSSF គណនាលើប្រាក់ខែអតិបរមា $1,200/ខែ",
      "localization": "ការបង្ហាញភាសា",
      "default_language": "ភាសាលំនាំដើម",
      "date_format": "ទម្រង់កាលបរិច្ឆេទ",
      "timezone": "តំបន់ម៉ោង"
    }
  }
}
</i18n>
