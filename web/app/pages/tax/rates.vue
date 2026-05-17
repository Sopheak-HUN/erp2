<template>
  <div class="min-h-screen bg-gray-50 p-6">
    <!-- Header -->
    <div class="mb-6 flex items-center justify-between">
      <div>
        <h1 class="text-2xl font-bold text-gray-900">{{ t('tr.title') }}</h1>
        <p class="mt-1 text-sm text-gray-500">{{ t('tr.subtitle') }}</p>
      </div>
      <button class="inline-flex items-center gap-2 rounded-lg bg-blue-600 px-4 py-2 text-sm font-medium text-white shadow-sm hover:bg-blue-700">
        <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
          <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" />
        </svg>
        {{ t('tr.add_rate') }}
      </button>
    </div>

    <!-- Versioning Note -->
    <div class="mb-5 rounded-xl border border-amber-200 bg-amber-50 p-4 flex gap-3">
      <svg class="h-5 w-5 text-amber-500 flex-shrink-0 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
      </svg>
      <div>
        <p class="text-sm font-medium text-amber-800">{{ t('tr.versioning_title') }}</p>
        <p class="mt-0.5 text-sm text-amber-700">{{ t('tr.versioning_note') }}</p>
      </div>
    </div>

    <!-- Rate Group: VAT -->
    <div class="mb-6">
      <h2 class="mb-3 text-base font-semibold text-gray-800 flex items-center gap-2">
        <span class="inline-flex h-6 w-6 items-center justify-center rounded-full bg-blue-100 text-blue-700 text-xs font-bold">V</span>
        {{ t('tr.group_vat') }}
      </h2>
      <div class="rounded-xl border border-gray-200 bg-white shadow-sm overflow-hidden">
        <table class="w-full text-sm">
          <thead class="bg-gray-50 border-b border-gray-200">
            <tr>
              <th class="px-4 py-3 text-left font-semibold text-gray-600">{{ t('tr.col_name') }}</th>
              <th class="px-4 py-3 text-left font-semibold text-gray-600 w-28">{{ t('tr.col_type') }}</th>
              <th class="px-4 py-3 text-right font-semibold text-gray-600 w-24">{{ t('tr.col_rate') }}</th>
              <th class="px-4 py-3 text-left font-semibold text-gray-600 w-28">{{ t('tr.col_effective') }}</th>
              <th class="px-4 py-3 text-left font-semibold text-gray-600 w-24">{{ t('tr.col_expires') }}</th>
              <th class="px-4 py-3 text-center font-semibold text-gray-600 w-20">{{ t('tr.col_active') }}</th>
              <th class="px-4 py-3 text-left font-semibold text-gray-600">{{ t('tr.col_notes') }}</th>
            </tr>
          </thead>
          <tbody class="divide-y divide-gray-100">
            <tr v-for="rate in vatRates" :key="rate.id" class="hover:bg-gray-50">
              <td class="px-4 py-3 font-medium text-gray-900">{{ rate.name }}</td>
              <td class="px-4 py-3"><span :class="typeBadgeClass(rate.type)" class="inline-flex items-center rounded-full px-2.5 py-0.5 text-xs font-medium">{{ rate.type }}</span></td>
              <td class="px-4 py-3 text-right font-mono font-semibold text-gray-900">{{ rate.rate }}%</td>
              <td class="px-4 py-3 text-gray-600 text-xs">{{ rate.effective_from }}</td>
              <td class="px-4 py-3 text-gray-400 text-xs">{{ rate.expires || '—' }}</td>
              <td class="px-4 py-3 text-center">
                <span v-if="rate.is_active" class="inline-flex items-center justify-center w-5 h-5 rounded-full bg-green-100 text-green-600">
                  <svg class="w-3 h-3" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd" /></svg>
                </span>
                <span v-else class="text-gray-300">✕</span>
              </td>
              <td class="px-4 py-3 text-xs text-gray-500">{{ rate.notes }}</td>
            </tr>
          </tbody>
        </table>
      </div>
    </div>

    <!-- Rate Group: CIT -->
    <div class="mb-6">
      <h2 class="mb-3 text-base font-semibold text-gray-800 flex items-center gap-2">
        <span class="inline-flex h-6 w-6 items-center justify-center rounded-full bg-purple-100 text-purple-700 text-xs font-bold">C</span>
        {{ t('tr.group_cit') }}
      </h2>
      <div class="rounded-xl border border-gray-200 bg-white shadow-sm overflow-hidden">
        <table class="w-full text-sm">
          <thead class="bg-gray-50 border-b border-gray-200">
            <tr>
              <th class="px-4 py-3 text-left font-semibold text-gray-600">{{ t('tr.col_name') }}</th>
              <th class="px-4 py-3 text-left font-semibold text-gray-600 w-28">{{ t('tr.col_type') }}</th>
              <th class="px-4 py-3 text-right font-semibold text-gray-600 w-24">{{ t('tr.col_rate') }}</th>
              <th class="px-4 py-3 text-left font-semibold text-gray-600 w-28">{{ t('tr.col_effective') }}</th>
              <th class="px-4 py-3 text-center font-semibold text-gray-600 w-20">{{ t('tr.col_active') }}</th>
              <th class="px-4 py-3 text-left font-semibold text-gray-600">{{ t('tr.col_notes') }}</th>
            </tr>
          </thead>
          <tbody class="divide-y divide-gray-100">
            <tr v-for="rate in citRates" :key="rate.id" class="hover:bg-gray-50">
              <td class="px-4 py-3 font-medium text-gray-900">{{ rate.name }}</td>
              <td class="px-4 py-3"><span :class="typeBadgeClass(rate.type)" class="inline-flex items-center rounded-full px-2.5 py-0.5 text-xs font-medium">{{ rate.type }}</span></td>
              <td class="px-4 py-3 text-right font-mono font-semibold text-gray-900">{{ rate.rate }}%</td>
              <td class="px-4 py-3 text-gray-600 text-xs">{{ rate.effective_from }}</td>
              <td class="px-4 py-3 text-center">
                <span class="inline-flex items-center justify-center w-5 h-5 rounded-full bg-green-100 text-green-600">
                  <svg class="w-3 h-3" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd" /></svg>
                </span>
              </td>
              <td class="px-4 py-3 text-xs text-gray-500">{{ rate.notes }}</td>
            </tr>
          </tbody>
        </table>
      </div>
    </div>

    <!-- Rate Group: WHT -->
    <div class="mb-6">
      <h2 class="mb-3 text-base font-semibold text-gray-800 flex items-center gap-2">
        <span class="inline-flex h-6 w-6 items-center justify-center rounded-full bg-orange-100 text-orange-700 text-xs font-bold">W</span>
        {{ t('tr.group_wht') }}
      </h2>
      <div class="rounded-xl border border-gray-200 bg-white shadow-sm overflow-hidden">
        <table class="w-full text-sm">
          <thead class="bg-gray-50 border-b border-gray-200">
            <tr>
              <th class="px-4 py-3 text-left font-semibold text-gray-600">{{ t('tr.col_name') }}</th>
              <th class="px-4 py-3 text-left font-semibold text-gray-600 w-28">{{ t('tr.col_type') }}</th>
              <th class="px-4 py-3 text-right font-semibold text-gray-600 w-24">{{ t('tr.col_rate') }}</th>
              <th class="px-4 py-3 text-left font-semibold text-gray-600 w-28">{{ t('tr.col_effective') }}</th>
              <th class="px-4 py-3 text-center font-semibold text-gray-600 w-20">{{ t('tr.col_active') }}</th>
              <th class="px-4 py-3 text-left font-semibold text-gray-600">{{ t('tr.col_notes') }}</th>
            </tr>
          </thead>
          <tbody class="divide-y divide-gray-100">
            <tr v-for="rate in whtRates" :key="rate.id" class="hover:bg-gray-50">
              <td class="px-4 py-3 font-medium text-gray-900">{{ rate.name }}</td>
              <td class="px-4 py-3"><span :class="typeBadgeClass(rate.type)" class="inline-flex items-center rounded-full px-2.5 py-0.5 text-xs font-medium">{{ rate.type }}</span></td>
              <td class="px-4 py-3 text-right font-mono font-semibold text-gray-900">{{ rate.rate }}%</td>
              <td class="px-4 py-3 text-gray-600 text-xs">{{ rate.effective_from }}</td>
              <td class="px-4 py-3 text-center">
                <span class="inline-flex items-center justify-center w-5 h-5 rounded-full bg-green-100 text-green-600">
                  <svg class="w-3 h-3" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd" /></svg>
                </span>
              </td>
              <td class="px-4 py-3 text-xs text-gray-500">{{ rate.notes }}</td>
            </tr>
          </tbody>
        </table>
      </div>
    </div>

    <!-- Rate Group: NSSF -->
    <div class="mb-6">
      <h2 class="mb-3 text-base font-semibold text-gray-800 flex items-center gap-2">
        <span class="inline-flex h-6 w-6 items-center justify-center rounded-full bg-emerald-100 text-emerald-700 text-xs font-bold">N</span>
        {{ t('tr.group_nssf') }}
      </h2>
      <div class="rounded-xl border border-gray-200 bg-white shadow-sm overflow-hidden">
        <table class="w-full text-sm">
          <thead class="bg-gray-50 border-b border-gray-200">
            <tr>
              <th class="px-4 py-3 text-left font-semibold text-gray-600">{{ t('tr.col_name') }}</th>
              <th class="px-4 py-3 text-left font-semibold text-gray-600 w-28">{{ t('tr.col_type') }}</th>
              <th class="px-4 py-3 text-right font-semibold text-gray-600 w-24">{{ t('tr.col_rate') }}</th>
              <th class="px-4 py-3 text-left font-semibold text-gray-600 w-28">{{ t('tr.col_effective') }}</th>
              <th class="px-4 py-3 text-center font-semibold text-gray-600 w-20">{{ t('tr.col_active') }}</th>
              <th class="px-4 py-3 text-left font-semibold text-gray-600">{{ t('tr.col_notes') }}</th>
            </tr>
          </thead>
          <tbody class="divide-y divide-gray-100">
            <tr v-for="rate in nssfRates" :key="rate.id" class="hover:bg-gray-50">
              <td class="px-4 py-3 font-medium text-gray-900">{{ rate.name }}</td>
              <td class="px-4 py-3"><span :class="typeBadgeClass(rate.type)" class="inline-flex items-center rounded-full px-2.5 py-0.5 text-xs font-medium">{{ rate.type }}</span></td>
              <td class="px-4 py-3 text-right font-mono font-semibold text-gray-900">{{ rate.rate }}%</td>
              <td class="px-4 py-3 text-gray-600 text-xs">{{ rate.effective_from }}</td>
              <td class="px-4 py-3 text-center">
                <span class="inline-flex items-center justify-center w-5 h-5 rounded-full bg-green-100 text-green-600">
                  <svg class="w-3 h-3" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd" /></svg>
                </span>
              </td>
              <td class="px-4 py-3 text-xs text-gray-500">{{ rate.notes }}</td>
            </tr>
          </tbody>
        </table>
      </div>
    </div>
  </div>
</template>

<script setup lang="ts">
definePageMeta({ middleware: 'auth' })

const { t } = useI18n()

interface TaxRate {
  id: number
  name: string
  type: string
  rate: number
  effective_from: string
  expires?: string
  is_active: boolean
  notes: string
}

const vatRates = ref<TaxRate[]>([
  { id: 1, name: 'VAT – Standard Rate', type: 'VAT', rate: 10, effective_from: '2000-01-01', is_active: true, notes: 'Standard VAT rate per Prakas 346 MEF' },
  { id: 2, name: 'VAT – Zero Rate (Export)', type: 'VAT', rate: 0, effective_from: '2000-01-01', is_active: true, notes: 'Export of goods and services' },
  { id: 3, name: 'VAT – Exempt', type: 'VAT', rate: 0, effective_from: '2000-01-01', is_active: true, notes: 'Exempt supplies per VAT Law Art. 63' },
])

const citRates = ref<TaxRate[]>([
  { id: 10, name: 'CIT – Standard', type: 'CIT', rate: 20, effective_from: '2017-01-01', is_active: true, notes: 'Standard corporate income tax rate' },
  { id: 11, name: 'CIT – SME (Qualified)', type: 'CIT', rate: 0, effective_from: '2017-01-01', is_active: true, notes: 'QIP incentive – tax holiday period' },
  { id: 12, name: 'CIT – Insurance', type: 'CIT', rate: 5, effective_from: '2017-01-01', is_active: true, notes: 'Insurance companies gross premium' },
  { id: 13, name: 'CIT – Oil & Gas', type: 'CIT', rate: 30, effective_from: '2017-01-01', is_active: true, notes: 'Natural resource extraction' },
])

const whtRates = ref<TaxRate[]>([
  { id: 20, name: 'WHT – Residents (Services)', type: 'WHT', rate: 15, effective_from: '2017-01-01', is_active: true, notes: 'Withholding on management fees, royalties' },
  { id: 21, name: 'WHT – Residents (Interest)', type: 'WHT', rate: 6, effective_from: '2017-01-01', is_active: true, notes: 'Interest paid to residents' },
  { id: 22, name: 'WHT – Non-Residents', type: 'WHT', rate: 14, effective_from: '2017-01-01', is_active: true, notes: 'Income paid to non-resident entities' },
  { id: 23, name: 'WHT – Rent (Residents)', type: 'WHT', rate: 10, effective_from: '2017-01-01', is_active: true, notes: 'Rental payments to resident individuals' },
  { id: 24, name: 'WHT – Dividends', type: 'WHT', rate: 0, effective_from: '2017-01-01', is_active: true, notes: 'Dividends from resident companies exempt' },
])

const nssfRates = ref<TaxRate[]>([
  { id: 30, name: 'NSSF – Occupational Risk (Employer)', type: 'NSSF', rate: 0.8, effective_from: '2008-01-01', is_active: true, notes: 'Employer contribution – Occupational Risk branch' },
  { id: 31, name: 'NSSF – Health Care (Employer)', type: 'NSSF', rate: 1.3, effective_from: '2016-01-01', is_active: true, notes: 'Employer contribution – Health Care branch' },
  { id: 32, name: 'NSSF – Health Care (Employee)', type: 'NSSF', rate: 1.3, effective_from: '2016-01-01', is_active: true, notes: 'Employee contribution – Health Care branch' },
  { id: 33, name: 'NSSF – Pension (Employer)', type: 'NSSF', rate: 4, effective_from: '2024-01-01', is_active: true, notes: 'Pension scheme – employer share (phased)' },
  { id: 34, name: 'NSSF – Pension (Employee)', type: 'NSSF', rate: 4, effective_from: '2024-01-01', is_active: true, notes: 'Pension scheme – employee share (phased)' },
])

function typeBadgeClass(type: string) {
  const map: Record<string, string> = {
    VAT: 'bg-blue-100 text-blue-700',
    CIT: 'bg-purple-100 text-purple-700',
    WHT: 'bg-orange-100 text-orange-700',
    NSSF: 'bg-emerald-100 text-emerald-700',
  }
  return map[type] ?? 'bg-gray-100 text-gray-700'
}
</script>

<i18n lang="json">
{
  "en": {
    "tr": {
      "title": "Tax Rates",
      "subtitle": "Cambodia tax rates – VAT, CIT, WHT, NSSF",
      "add_rate": "Add Rate",
      "versioning_title": "Rate Versioning",
      "versioning_note": "Historical rate records are preserved with effective dates. Deactivating a rate retains it for audit and historical transaction reference. Never delete rate records.",
      "group_vat": "Value Added Tax (VAT)",
      "group_cit": "Corporate Income Tax (CIT)",
      "group_wht": "Withholding Tax (WHT)",
      "group_nssf": "NSSF Contributions",
      "col_name": "Tax Name",
      "col_type": "Type",
      "col_rate": "Rate",
      "col_effective": "Effective From",
      "col_expires": "Expires",
      "col_active": "Active",
      "col_notes": "Notes"
    }
  },
  "km": {
    "tr": {
      "title": "អត្រាពន្ធ",
      "subtitle": "អត្រាពន្ធកម្ពុជា – VAT, CIT, WHT, NSSF",
      "add_rate": "បន្ថែមអត្រា",
      "versioning_title": "កំណត់ត្រាអត្រា",
      "versioning_note": "កំណត់ត្រាអត្រាប្រវត្តិការ​ត្រូវបានរក្សាទុក",
      "group_vat": "អាករលើតម្លៃបន្ថែម (VAT)",
      "group_cit": "ពន្ធលើប្រាក់ចំណេញសាជីវកម្ម (CIT)",
      "group_wht": "ពន្ធកាត់ទុក (WHT)",
      "group_nssf": "វិភាគទាន NSSF",
      "col_name": "ឈ្មោះពន្ធ",
      "col_type": "ប្រភេទ",
      "col_rate": "អត្រា",
      "col_effective": "ចាប់ផ្តើម",
      "col_expires": "ផុតកំណត់",
      "col_active": "សកម្ម",
      "col_notes": "កំណត់ចំណាំ"
    }
  }
}
</i18n>
