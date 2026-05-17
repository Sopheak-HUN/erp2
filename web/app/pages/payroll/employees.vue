<template>
  <div class="min-h-screen bg-gray-50 p-6">
    <!-- Header -->
    <div class="mb-6 flex items-center justify-between">
      <div>
        <h1 class="text-2xl font-bold text-gray-900">{{ t('emp.title') }}</h1>
        <p class="mt-1 text-sm text-gray-500">{{ t('emp.subtitle') }}</p>
      </div>
      <button class="inline-flex items-center gap-2 rounded-lg bg-blue-600 px-4 py-2 text-sm font-medium text-white shadow-sm hover:bg-blue-700">
        <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
          <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M18 9v3m0 0v3m0-3h3m-3 0h-3m-2-5a4 4 0 11-8 0 4 4 0 018 0zM3 20a6 6 0 0112 0v1H3v-1z" />
        </svg>
        {{ t('emp.add_employee') }}
      </button>
    </div>

    <!-- Summary Stats -->
    <div class="mb-6 grid grid-cols-2 gap-4 sm:grid-cols-4">
      <div class="rounded-xl border border-blue-100 bg-blue-50 p-4 shadow-sm">
        <div class="flex items-center gap-3">
          <div class="flex h-10 w-10 items-center justify-center rounded-full bg-blue-100">
            <svg class="h-5 w-5 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0z" />
            </svg>
          </div>
          <div>
            <p class="text-xs font-medium text-blue-600">{{ t('emp.stat_total') }}</p>
            <p class="text-xl font-bold text-blue-900">{{ stats.total }}</p>
          </div>
        </div>
      </div>
      <div class="rounded-xl border border-green-100 bg-green-50 p-4 shadow-sm">
        <div class="flex items-center gap-3">
          <div class="flex h-10 w-10 items-center justify-center rounded-full bg-green-100">
            <svg class="h-5 w-5 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
            </svg>
          </div>
          <div>
            <p class="text-xs font-medium text-green-600">{{ t('emp.stat_monthly_payroll') }}</p>
            <p class="text-xl font-bold text-green-900">${{ formatNum(stats.monthly_payroll) }}</p>
          </div>
        </div>
      </div>
      <div class="rounded-xl border border-emerald-100 bg-emerald-50 p-4 shadow-sm">
        <div class="flex items-center gap-3">
          <div class="flex h-10 w-10 items-center justify-center rounded-full bg-emerald-100">
            <svg class="h-5 w-5 text-emerald-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z" />
            </svg>
          </div>
          <div>
            <p class="text-xs font-medium text-emerald-600">{{ t('emp.stat_nssf_cost') }}</p>
            <p class="text-xl font-bold text-emerald-900">${{ formatNum(stats.nssf_employer) }}</p>
          </div>
        </div>
      </div>
      <div class="rounded-xl border border-purple-100 bg-purple-50 p-4 shadow-sm">
        <div class="flex items-center gap-3">
          <div class="flex h-10 w-10 items-center justify-center rounded-full bg-purple-100">
            <svg class="h-5 w-5 text-purple-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2" />
            </svg>
          </div>
          <div>
            <p class="text-xs font-medium text-purple-600">{{ t('emp.stat_active') }}</p>
            <p class="text-xl font-bold text-purple-900">{{ stats.active }}</p>
          </div>
        </div>
      </div>
    </div>

    <!-- Search & Filter -->
    <div class="mb-4 rounded-xl border border-gray-200 bg-white p-4 shadow-sm">
      <div class="flex flex-col gap-3 sm:flex-row sm:items-center">
        <div class="relative flex-1">
          <svg class="absolute left-3 top-1/2 h-4 w-4 -translate-y-1/2 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
          </svg>
          <input v-model="searchQuery" type="text" :placeholder="t('emp.search_placeholder')" class="w-full rounded-lg border border-gray-300 py-2 pl-10 pr-4 text-sm focus:border-blue-500 focus:outline-none focus:ring-1 focus:ring-blue-500" />
        </div>
        <select v-model="filterContract" class="rounded-lg border border-gray-300 px-3 py-2 text-sm focus:border-blue-500 focus:outline-none">
          <option value="">{{ t('emp.all_contracts') }}</option>
          <option value="UDC">UDC</option>
          <option value="FDC">FDC</option>
        </select>
        <select v-model="filterDept" class="rounded-lg border border-gray-300 px-3 py-2 text-sm focus:border-blue-500 focus:outline-none">
          <option value="">{{ t('emp.all_depts') }}</option>
          <option value="Management">Management</option>
          <option value="Finance">Finance</option>
          <option value="Sales">Sales</option>
          <option value="Operations">Operations</option>
          <option value="IT">IT</option>
        </select>
      </div>
    </div>

    <!-- Employee Table -->
    <div class="rounded-xl border border-gray-200 bg-white shadow-sm overflow-x-auto">
      <table class="w-full text-sm min-w-[900px]">
        <thead class="bg-gray-50 border-b border-gray-200">
          <tr>
            <th class="px-4 py-3 text-left font-semibold text-gray-600 w-20">{{ t('emp.col_id') }}</th>
            <th class="px-4 py-3 text-left font-semibold text-gray-600">{{ t('emp.col_name') }}</th>
            <th class="px-4 py-3 text-left font-semibold text-gray-600 w-28">{{ t('emp.col_nssf') }}</th>
            <th class="px-4 py-3 text-left font-semibold text-gray-600 w-28">{{ t('emp.col_dept') }}</th>
            <th class="px-4 py-3 text-left font-semibold text-gray-600 w-20">{{ t('emp.col_contract') }}</th>
            <th class="px-4 py-3 text-right font-semibold text-gray-600 w-28">{{ t('emp.col_salary') }}</th>
            <th class="px-4 py-3 text-left font-semibold text-gray-600 w-24">{{ t('emp.col_start_date') }}</th>
            <th class="px-4 py-3 text-center font-semibold text-gray-600 w-20">{{ t('emp.col_status') }}</th>
            <th class="px-4 py-3 text-center font-semibold text-gray-600 w-16"></th>
          </tr>
        </thead>
        <tbody class="divide-y divide-gray-100">
          <tr v-for="emp in filteredEmployees" :key="emp.id" class="hover:bg-gray-50 transition-colors">
            <td class="px-4 py-3 font-mono text-xs text-gray-500">{{ emp.employee_id }}</td>
            <td class="px-4 py-3">
              <div class="flex items-center gap-3">
                <div class="flex h-8 w-8 flex-shrink-0 items-center justify-center rounded-full bg-blue-100 text-blue-700 text-xs font-bold">
                  {{ emp.name_en.charAt(0) }}
                </div>
                <div>
                  <div class="font-medium text-gray-900">{{ emp.name_en }}</div>
                  <div class="text-xs text-gray-400 font-khmer">{{ emp.name_km }}</div>
                </div>
              </div>
            </td>
            <td class="px-4 py-3 font-mono text-xs text-gray-500">{{ emp.nssf_id || '—' }}</td>
            <td class="px-4 py-3 text-gray-600">{{ emp.department }}</td>
            <td class="px-4 py-3">
              <span :class="emp.contract_type === 'UDC' ? 'bg-blue-100 text-blue-700' : 'bg-amber-100 text-amber-700'" class="inline-flex items-center rounded-full px-2.5 py-0.5 text-xs font-medium">
                {{ emp.contract_type }}
              </span>
            </td>
            <td class="px-4 py-3 text-right font-mono text-gray-900">${{ formatNum(emp.base_salary) }}</td>
            <td class="px-4 py-3 text-xs text-gray-500">{{ emp.start_date }}</td>
            <td class="px-4 py-3 text-center">
              <span :class="emp.is_active ? 'bg-green-100 text-green-700' : 'bg-gray-100 text-gray-500'" class="inline-flex items-center rounded-full px-2.5 py-0.5 text-xs font-medium">
                {{ emp.is_active ? t('emp.active') : t('emp.inactive') }}
              </span>
            </td>
            <td class="px-4 py-3 text-center">
              <button class="text-gray-400 hover:text-blue-600 p-1 transition-colors">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                  <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
                </svg>
              </button>
            </td>
          </tr>
        </tbody>
      </table>
      <div v-if="filteredEmployees.length === 0" class="py-12 text-center text-gray-400">{{ t('emp.no_results') }}</div>
    </div>
  </div>
</template>

<script setup lang="ts">
definePageMeta({ middleware: 'auth' })

const { t } = useI18n()

const searchQuery = ref('')
const filterContract = ref('')
const filterDept = ref('')

interface Employee {
  id: number
  employee_id: string
  name_en: string
  name_km: string
  nssf_id: string
  department: string
  contract_type: 'UDC' | 'FDC'
  base_salary: number
  start_date: string
  is_active: boolean
}

const employees = ref<Employee[]>([
  { id: 1, employee_id: 'EMP-001', name_en: 'Sopheak Chan', name_km: 'សុភ័ក្ត្រ ចាន់', nssf_id: 'NSSF-0001234', department: 'Management', contract_type: 'UDC', base_salary: 3500.00, start_date: '2020-01-15', is_active: true },
  { id: 2, employee_id: 'EMP-002', name_en: 'Dara Sok', name_km: 'ដារ៉ា សុខ', nssf_id: 'NSSF-0001235', department: 'Finance', contract_type: 'UDC', base_salary: 1800.00, start_date: '2021-03-01', is_active: true },
  { id: 3, employee_id: 'EMP-003', name_en: 'Sreymom Pich', name_km: 'ស្រីម៉ម ពេជ្រ', nssf_id: 'NSSF-0001236', department: 'Finance', contract_type: 'UDC', base_salary: 1500.00, start_date: '2021-06-15', is_active: true },
  { id: 4, employee_id: 'EMP-004', name_en: 'Virak Ung', name_km: 'វីរៈ អ៊ុង', nssf_id: 'NSSF-0001237', department: 'Sales', contract_type: 'UDC', base_salary: 1200.00, start_date: '2022-02-01', is_active: true },
  { id: 5, employee_id: 'EMP-005', name_en: 'Channary Keo', name_km: 'ចន្ទនារី កែវ', nssf_id: 'NSSF-0001238', department: 'Sales', contract_type: 'FDC', base_salary: 900.00, start_date: '2025-01-10', is_active: true },
  { id: 6, employee_id: 'EMP-006', name_en: 'Piseth Lim', name_km: 'ពិសិដ្ឋ លឹម', nssf_id: 'NSSF-0001239', department: 'Operations', contract_type: 'UDC', base_salary: 1100.00, start_date: '2022-09-01', is_active: true },
  { id: 7, employee_id: 'EMP-007', name_en: 'Bopha Meas', name_km: 'បុប្ផា មាស', nssf_id: 'NSSF-0001240', department: 'Operations', contract_type: 'UDC', base_salary: 950.00, start_date: '2023-01-16', is_active: true },
  { id: 8, employee_id: 'EMP-008', name_en: 'Rathana Heng', name_km: 'រតនា ហេង', nssf_id: 'NSSF-0001241', department: 'IT', contract_type: 'FDC', base_salary: 2200.00, start_date: '2025-06-01', is_active: true },
  { id: 9, employee_id: 'EMP-009', name_en: 'Kunthea Nhim', name_km: 'គន្ធា ញឹម', nssf_id: 'NSSF-0001242', department: 'Sales', contract_type: 'FDC', base_salary: 850.00, start_date: '2025-09-01', is_active: true },
  { id: 10, employee_id: 'EMP-010', name_en: 'Makara Touch', name_km: 'មករា ទូច', nssf_id: '', department: 'Management', contract_type: 'UDC', base_salary: 4500.00, start_date: '2019-07-01', is_active: false },
])

const filteredEmployees = computed(() => {
  return employees.value.filter(emp => {
    const matchSearch = searchQuery.value === '' ||
      emp.name_en.toLowerCase().includes(searchQuery.value.toLowerCase()) ||
      emp.name_km.includes(searchQuery.value) ||
      emp.employee_id.includes(searchQuery.value) ||
      emp.nssf_id.includes(searchQuery.value)
    const matchContract = filterContract.value === '' || emp.contract_type === filterContract.value
    const matchDept = filterDept.value === '' || emp.department === filterDept.value
    return matchSearch && matchContract && matchDept
  })
})

const stats = computed(() => {
  const active = employees.value.filter(e => e.is_active)
  const monthly = active.reduce((s, e) => s + e.base_salary, 0)
  const nssfBase = Math.min(1200, 0) // simplified: NSSF capped at $1,200
  const nssfEmployer = active.reduce((e, emp) => e + (Math.min(emp.base_salary, 1200) * 0.021), 0)
  return {
    total: employees.value.length,
    active: active.length,
    monthly_payroll: monthly,
    nssf_employer: nssfEmployer,
  }
})

function formatNum(n: number) {
  return new Intl.NumberFormat('en-US', { minimumFractionDigits: 2, maximumFractionDigits: 2 }).format(n)
}
</script>

<i18n lang="json">
{
  "en": {
    "emp": {
      "title": "Employees",
      "subtitle": "Payroll employee master data",
      "add_employee": "Add Employee",
      "search_placeholder": "Search by name, ID, NSSF…",
      "all_contracts": "All Contracts",
      "all_depts": "All Departments",
      "stat_total": "Total Employees",
      "stat_monthly_payroll": "Monthly Payroll",
      "stat_nssf_cost": "NSSF Employer Cost",
      "stat_active": "Active",
      "col_id": "ID",
      "col_name": "Employee",
      "col_nssf": "NSSF ID",
      "col_dept": "Department",
      "col_contract": "Contract",
      "col_salary": "Base Salary",
      "col_start_date": "Start Date",
      "col_status": "Status",
      "active": "Active",
      "inactive": "Inactive",
      "no_results": "No employees found."
    }
  },
  "km": {
    "emp": {
      "title": "បុគ្គលិក",
      "subtitle": "ទិន្នន័យបុគ្គលិកប្រាក់ខែ",
      "add_employee": "បន្ថែមបុគ្គលិក",
      "search_placeholder": "ស្វែងរកឈ្មោះ, ID, NSSF…",
      "all_contracts": "កិច្ចសន្យាទាំងអស់",
      "all_depts": "នាយកដ្ឋានទាំងអស់",
      "stat_total": "បុគ្គលិកសរុប",
      "stat_monthly_payroll": "ប្រាក់ខែប្រចាំខែ",
      "stat_nssf_cost": "ថ្លៃ NSSF ម្ចាស់ការ",
      "stat_active": "សកម្ម",
      "col_id": "ID",
      "col_name": "បុគ្គលិក",
      "col_nssf": "លេខ NSSF",
      "col_dept": "នាយកដ្ឋាន",
      "col_contract": "កិច្ចសន្យា",
      "col_salary": "ប្រាក់ខែមូលដ្ឋាន",
      "col_start_date": "ថ្ងៃចូលធ្វើការ",
      "col_status": "ស្ថានភាព",
      "active": "សកម្ម",
      "inactive": "អសកម្ម",
      "no_results": "មិនមានបុគ្គលិក"
    }
  }
}
</i18n>
