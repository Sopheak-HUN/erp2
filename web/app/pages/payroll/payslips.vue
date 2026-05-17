<template>
  <div class="min-h-screen bg-gray-50 p-6">
    <!-- Header -->
    <div class="mb-6 flex items-center justify-between">
      <div>
        <h1 class="text-2xl font-bold text-gray-900">{{ t('ps.title') }}</h1>
        <p class="mt-1 text-sm text-gray-500">{{ t('ps.subtitle') }}</p>
      </div>
      <div class="flex items-center gap-3">
        <input type="month" v-model="selectedPeriod" class="rounded-lg border border-gray-300 px-3 py-2 text-sm font-medium focus:border-blue-500 focus:outline-none focus:ring-1 focus:ring-blue-500" />
        <button
          @click="runPayroll"
          :disabled="payrollRun"
          :class="[
            'inline-flex items-center gap-2 rounded-lg px-4 py-2 text-sm font-medium shadow-sm focus:outline-none focus:ring-2',
            payrollRun
              ? 'bg-green-600 text-white cursor-default'
              : 'bg-blue-600 text-white hover:bg-blue-700 focus:ring-blue-500',
          ]"
        >
          <svg v-if="!payrollRun" class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14.752 11.168l-3.197-2.132A1 1 0 0010 9.87v4.263a1 1 0 001.555.832l3.197-2.132a1 1 0 000-1.664z" /><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
          </svg>
          <svg v-else class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
          </svg>
          {{ payrollRun ? t('ps.payroll_ran') : t('ps.run_payroll') }}
        </button>
      </div>
    </div>

    <!-- Period Summary -->
    <div class="mb-6 grid grid-cols-2 gap-4 sm:grid-cols-5">
      <div class="rounded-xl border border-gray-200 bg-white p-4 shadow-sm">
        <p class="text-xs font-medium text-gray-500 uppercase tracking-wide">{{ t('ps.stat_employees') }}</p>
        <p class="mt-1 text-xl font-bold text-gray-900">{{ periodSummary.employee_count }}</p>
      </div>
      <div class="rounded-xl border border-gray-200 bg-white p-4 shadow-sm">
        <p class="text-xs font-medium text-gray-500 uppercase tracking-wide">{{ t('ps.stat_gross') }}</p>
        <p class="mt-1 text-xl font-bold text-gray-900">${{ formatNum(periodSummary.gross_salary) }}</p>
      </div>
      <div class="rounded-xl border border-gray-200 bg-white p-4 shadow-sm">
        <p class="text-xs font-medium text-gray-500 uppercase tracking-wide">{{ t('ps.stat_nssf_deduct') }}</p>
        <p class="mt-1 text-xl font-bold text-orange-600">${{ formatNum(periodSummary.nssf_employee) }}</p>
      </div>
      <div class="rounded-xl border border-gray-200 bg-white p-4 shadow-sm">
        <p class="text-xs font-medium text-gray-500 uppercase tracking-wide">{{ t('ps.stat_tos') }}</p>
        <p class="mt-1 text-xl font-bold text-purple-600">${{ formatNum(periodSummary.tos_total) }}</p>
      </div>
      <div class="rounded-xl border border-blue-100 bg-blue-50 p-4 shadow-sm">
        <p class="text-xs font-medium text-blue-600 uppercase tracking-wide">{{ t('ps.stat_net') }}</p>
        <p class="mt-1 text-xl font-bold text-blue-900">${{ formatNum(periodSummary.net_total) }}</p>
      </div>
    </div>

    <!-- NSSF Employer Side -->
    <div class="mb-5 rounded-xl border border-emerald-200 bg-emerald-50 p-4 flex items-center justify-between">
      <div class="flex items-center gap-3">
        <svg class="h-5 w-5 text-emerald-600 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
          <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z" />
        </svg>
        <div>
          <p class="text-sm font-medium text-emerald-800">{{ t('ps.nssf_employer_label') }}</p>
          <p class="text-xs text-emerald-600">{{ t('ps.nssf_employer_note') }}</p>
        </div>
      </div>
      <div class="text-right">
        <p class="text-lg font-bold text-emerald-900">${{ formatNum(periodSummary.nssf_employer) }}</p>
        <p class="text-xs text-emerald-600">{{ t('ps.nssf_employer_rate') }}</p>
      </div>
    </div>

    <!-- Payslip Table -->
    <div class="rounded-xl border border-gray-200 bg-white shadow-sm overflow-x-auto">
      <table class="w-full text-sm min-w-[1000px]">
        <thead class="bg-gray-50 border-b border-gray-200">
          <tr>
            <th class="px-4 py-3 text-left font-semibold text-gray-600">{{ t('ps.col_employee') }}</th>
            <th class="px-4 py-3 text-left font-semibold text-gray-600 w-20">{{ t('ps.col_contract') }}</th>
            <th class="px-4 py-3 text-right font-semibold text-gray-600 w-28">{{ t('ps.col_base_salary') }}</th>
            <th class="px-4 py-3 text-right font-semibold text-gray-600 w-24">{{ t('ps.col_nssf_ee') }}</th>
            <th class="px-4 py-3 text-right font-semibold text-gray-600 w-24">{{ t('ps.col_tos') }}</th>
            <th class="px-4 py-3 text-right font-semibold text-gray-600 w-24">{{ t('ps.col_other_deduct') }}</th>
            <th class="px-4 py-3 text-right font-semibold text-gray-600 w-28">{{ t('ps.col_net_salary') }}</th>
            <th class="px-4 py-3 text-right font-semibold text-gray-600 w-28">{{ t('ps.col_nssf_er') }}</th>
            <th class="px-4 py-3 text-center font-semibold text-gray-600 w-24">{{ t('ps.col_status') }}</th>
            <th class="px-4 py-3 text-center font-semibold text-gray-600 w-16"></th>
          </tr>
        </thead>
        <tbody class="divide-y divide-gray-100">
          <tr v-for="slip in payslips" :key="slip.id" class="hover:bg-gray-50 transition-colors">
            <td class="px-4 py-3">
              <div class="flex items-center gap-3">
                <div class="flex h-7 w-7 flex-shrink-0 items-center justify-center rounded-full bg-blue-100 text-blue-700 text-xs font-bold">
                  {{ slip.name_en.charAt(0) }}
                </div>
                <div>
                  <div class="font-medium text-gray-900">{{ slip.name_en }}</div>
                  <div class="text-xs text-gray-400 font-khmer">{{ slip.name_km }}</div>
                </div>
              </div>
            </td>
            <td class="px-4 py-3">
              <span :class="slip.contract_type === 'UDC' ? 'bg-blue-100 text-blue-700' : 'bg-amber-100 text-amber-700'" class="inline-flex items-center rounded-full px-2 py-0.5 text-xs font-medium">
                {{ slip.contract_type }}
              </span>
            </td>
            <td class="px-4 py-3 text-right font-mono text-gray-800">${{ formatNum(slip.base_salary) }}</td>
            <td class="px-4 py-3 text-right font-mono text-orange-600">-${{ formatNum(slip.nssf_employee) }}</td>
            <td class="px-4 py-3 text-right font-mono text-purple-600">-${{ formatNum(slip.tos) }}</td>
            <td class="px-4 py-3 text-right font-mono text-red-500">{{ slip.other_deductions > 0 ? '-$' + formatNum(slip.other_deductions) : '—' }}</td>
            <td class="px-4 py-3 text-right font-mono font-semibold text-gray-900">${{ formatNum(slip.net_salary) }}</td>
            <td class="px-4 py-3 text-right font-mono text-emerald-600">${{ formatNum(slip.nssf_employer) }}</td>
            <td class="px-4 py-3 text-center">
              <span :class="slip.status === 'paid' ? 'bg-green-100 text-green-700' : slip.status === 'processed' ? 'bg-blue-100 text-blue-700' : 'bg-gray-100 text-gray-500'" class="inline-flex items-center rounded-full px-2.5 py-0.5 text-xs font-medium">
                {{ slip.status }}
              </span>
            </td>
            <td class="px-4 py-3 text-center">
              <button class="text-gray-400 hover:text-blue-600 p-1 transition-colors" :title="t('ps.view_payslip')">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                  <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                </svg>
              </button>
            </td>
          </tr>
        </tbody>
        <!-- Totals Row -->
        <tfoot class="bg-gray-50 border-t-2 border-gray-200">
          <tr>
            <td colspan="2" class="px-4 py-3 text-sm font-semibold text-gray-700">{{ t('ps.totals') }}</td>
            <td class="px-4 py-3 text-right font-mono font-bold text-gray-900">${{ formatNum(periodSummary.gross_salary) }}</td>
            <td class="px-4 py-3 text-right font-mono font-bold text-orange-600">-${{ formatNum(periodSummary.nssf_employee) }}</td>
            <td class="px-4 py-3 text-right font-mono font-bold text-purple-600">-${{ formatNum(periodSummary.tos_total) }}</td>
            <td class="px-4 py-3 text-right font-mono font-bold text-red-500">—</td>
            <td class="px-4 py-3 text-right font-mono font-bold text-gray-900">${{ formatNum(periodSummary.net_total) }}</td>
            <td class="px-4 py-3 text-right font-mono font-bold text-emerald-600">${{ formatNum(periodSummary.nssf_employer) }}</td>
            <td colspan="2"></td>
          </tr>
        </tfoot>
      </table>
    </div>
  </div>
</template>

<script setup lang="ts">
definePageMeta({ middleware: 'auth' })

const { t } = useI18n()

const selectedPeriod = ref('2026-05')
const payrollRun = ref(false)

interface Payslip {
  id: number
  name_en: string
  name_km: string
  contract_type: 'UDC' | 'FDC'
  base_salary: number
  nssf_employee: number
  tos: number
  other_deductions: number
  net_salary: number
  nssf_employer: number
  status: 'draft' | 'processed' | 'paid'
}

// NSSF capped at $1,200 gross; employee rate 1.3% health + 4% pension = 5.3%; employer 2.1% health + occupational 0.8% + 4% pension
function calcNSSFEmployee(salary: number) {
  const base = Math.min(salary, 1200)
  return parseFloat((base * 0.053).toFixed(2))
}

function calcNSSFEmployer(salary: number) {
  const base = Math.min(salary, 1200)
  return parseFloat((base * 0.021).toFixed(2))
}

// Simplified progressive ToS (Tax on Salary) calculation
function calcToS(gross: number, nssfEe: number): number {
  const taxable = gross - nssfEe
  if (taxable <= 1300000 / 4100) return 0
  if (taxable <= 2000000 / 4100) return parseFloat(((taxable - (1300000 / 4100)) * 0.05).toFixed(2))
  return parseFloat(((700000 / 4100) * 0.05 + (taxable - (2000000 / 4100)) * 0.1).toFixed(2))
}

const payslips = ref<Payslip[]>([
  { id: 1, name_en: 'Sopheak Chan', name_km: 'សុភ័ក្ត្រ ចាន់', contract_type: 'UDC', base_salary: 3500 },
  { id: 2, name_en: 'Dara Sok', name_km: 'ដារ៉ា សុខ', contract_type: 'UDC', base_salary: 1800 },
  { id: 3, name_en: 'Sreymom Pich', name_km: 'ស្រីម៉ម ពេជ្រ', contract_type: 'UDC', base_salary: 1500 },
  { id: 4, name_en: 'Virak Ung', name_km: 'វីរៈ អ៊ុង', contract_type: 'UDC', base_salary: 1200 },
  { id: 5, name_en: 'Channary Keo', name_km: 'ចន្ទនារី កែវ', contract_type: 'FDC', base_salary: 900 },
  { id: 6, name_en: 'Piseth Lim', name_km: 'ពិសិដ្ឋ លឹម', contract_type: 'UDC', base_salary: 1100 },
  { id: 7, name_en: 'Bopha Meas', name_km: 'បុប្ផា មាស', contract_type: 'UDC', base_salary: 950 },
  { id: 8, name_en: 'Rathana Heng', name_km: 'រតនា ហេង', contract_type: 'FDC', base_salary: 2200 },
  { id: 9, name_en: 'Kunthea Nhim', name_km: 'គន្ធា ញឹម', contract_type: 'FDC', base_salary: 850 },
].map((e, i) => {
  const nssfEe = calcNSSFEmployee(e.base_salary)
  const nssfEr = calcNSSFEmployer(e.base_salary)
  const tos = calcToS(e.base_salary, nssfEe)
  const otherDeductions = 0
  const net = parseFloat((e.base_salary - nssfEe - tos - otherDeductions).toFixed(2))
  return {
    ...e,
    nssf_employee: nssfEe,
    nssf_employer: nssfEr,
    tos,
    other_deductions: otherDeductions,
    net_salary: net,
    status: i < 2 ? 'paid' : i < 5 ? 'processed' : 'draft',
  } as Payslip
}))

const periodSummary = computed(() => {
  return {
    employee_count: payslips.value.length,
    gross_salary: payslips.value.reduce((s, p) => s + p.base_salary, 0),
    nssf_employee: payslips.value.reduce((s, p) => s + p.nssf_employee, 0),
    nssf_employer: payslips.value.reduce((s, p) => s + p.nssf_employer, 0),
    tos_total: payslips.value.reduce((s, p) => s + p.tos, 0),
    net_total: payslips.value.reduce((s, p) => s + p.net_salary, 0),
  }
})

function runPayroll() {
  payslips.value.forEach(p => {
    if (p.status === 'draft') p.status = 'processed'
  })
  payrollRun.value = true
}

function formatNum(n: number) {
  return new Intl.NumberFormat('en-US', { minimumFractionDigits: 2, maximumFractionDigits: 2 }).format(n)
}
</script>

<i18n lang="json">
{
  "en": {
    "ps": {
      "title": "Payslips",
      "subtitle": "Monthly payroll processing and payslip management",
      "run_payroll": "Run Payroll",
      "payroll_ran": "Payroll Processed",
      "stat_employees": "Employees",
      "stat_gross": "Gross Salary",
      "stat_nssf_deduct": "NSSF Deduction",
      "stat_tos": "Tax on Salary",
      "stat_net": "Net Payroll",
      "nssf_employer_label": "NSSF Employer Contribution",
      "nssf_employer_note": "Employer's share – not deducted from employee salary",
      "nssf_employer_rate": "2.1% health + 0.8% occ. risk",
      "col_employee": "Employee",
      "col_contract": "Contract",
      "col_base_salary": "Base Salary",
      "col_nssf_ee": "NSSF (EE)",
      "col_tos": "ToS",
      "col_other_deduct": "Other Deductions",
      "col_net_salary": "Net Salary",
      "col_nssf_er": "NSSF (ER)",
      "col_status": "Status",
      "view_payslip": "View Payslip",
      "totals": "Totals"
    }
  },
  "km": {
    "ps": {
      "title": "ប្រាក់ខែ",
      "subtitle": "ដំណើរការប្រាក់ខែប្រចាំខែ",
      "run_payroll": "ដំណើរការប្រាក់ខែ",
      "payroll_ran": "ប្រាក់ខែបានដំណើរការ",
      "stat_employees": "បុគ្គលិក",
      "stat_gross": "ប្រាក់ខែសរុប",
      "stat_nssf_deduct": "កាត់ NSSF",
      "stat_tos": "ពន្ធប្រាក់ខែ",
      "stat_net": "ប្រាក់ខែសុទ្ធ",
      "nssf_employer_label": "វិភាគទាន NSSF ម្ចាស់ការ",
      "nssf_employer_note": "ចំណែកម្ចាស់ការ – មិនកាត់ពីប្រាក់ខែ",
      "nssf_employer_rate": "2.1% សុខភាព + 0.8% ហានិភ័យ",
      "col_employee": "បុគ្គលិក",
      "col_contract": "កិច្ចសន្យា",
      "col_base_salary": "ប្រាក់ខែមូលដ្ឋាន",
      "col_nssf_ee": "NSSF (EE)",
      "col_tos": "ToS",
      "col_other_deduct": "ការកាត់ផ្សេងៗ",
      "col_net_salary": "ប្រាក់ខែសុទ្ធ",
      "col_nssf_er": "NSSF (ER)",
      "col_status": "ស្ថានភាព",
      "view_payslip": "មើលប្រាក់ខែ",
      "totals": "សរុប"
    }
  }
}
</i18n>
