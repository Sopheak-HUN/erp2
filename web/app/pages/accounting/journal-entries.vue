<template>
  <div class="min-h-screen bg-gray-50 p-6">
    <!-- Header -->
    <div class="mb-6 flex items-center justify-between">
      <div>
        <h1 class="text-2xl font-bold text-gray-900">{{ t('je.title') }}</h1>
        <p class="mt-1 text-sm text-gray-500">{{ t('je.subtitle') }}</p>
      </div>
      <button class="inline-flex items-center gap-2 rounded-lg bg-blue-600 px-4 py-2 text-sm font-medium text-white shadow-sm hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-blue-500">
        <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
          <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" />
        </svg>
        {{ t('je.new_entry') }}
      </button>
    </div>

    <!-- Stats Row -->
    <div class="mb-6 grid grid-cols-2 gap-4 sm:grid-cols-4">
      <div class="rounded-xl border border-gray-200 bg-white p-4 shadow-sm">
        <p class="text-xs font-medium text-gray-500 uppercase tracking-wide">{{ t('je.stat_total') }}</p>
        <p class="mt-1 text-2xl font-bold text-gray-900">{{ entries.length }}</p>
      </div>
      <div class="rounded-xl border border-gray-200 bg-white p-4 shadow-sm">
        <p class="text-xs font-medium text-gray-500 uppercase tracking-wide">{{ t('je.stat_posted') }}</p>
        <p class="mt-1 text-2xl font-bold text-green-600">{{ entries.filter(e => e.status === 'posted').length }}</p>
      </div>
      <div class="rounded-xl border border-gray-200 bg-white p-4 shadow-sm">
        <p class="text-xs font-medium text-gray-500 uppercase tracking-wide">{{ t('je.stat_draft') }}</p>
        <p class="mt-1 text-2xl font-bold text-gray-400">{{ entries.filter(e => e.status === 'draft').length }}</p>
      </div>
      <div class="rounded-xl border border-gray-200 bg-white p-4 shadow-sm">
        <p class="text-xs font-medium text-gray-500 uppercase tracking-wide">{{ t('je.stat_void') }}</p>
        <p class="mt-1 text-2xl font-bold text-red-500">{{ entries.filter(e => e.status === 'void').length }}</p>
      </div>
    </div>

    <!-- Filter Bar -->
    <div class="mb-4 rounded-xl border border-gray-200 bg-white p-4 shadow-sm">
      <div class="flex flex-col gap-3 sm:flex-row sm:items-center">
        <div class="relative flex-1">
          <svg class="absolute left-3 top-1/2 h-4 w-4 -translate-y-1/2 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
          </svg>
          <input
            v-model="searchQuery"
            type="text"
            :placeholder="t('je.search_placeholder')"
            class="w-full rounded-lg border border-gray-300 py-2 pl-10 pr-4 text-sm focus:border-blue-500 focus:outline-none focus:ring-1 focus:ring-blue-500"
          />
        </div>
        <div class="flex gap-2">
          <select v-model="filterStatus" class="rounded-lg border border-gray-300 px-3 py-2 text-sm focus:border-blue-500 focus:outline-none">
            <option value="">{{ t('je.all_status') }}</option>
            <option value="posted">Posted</option>
            <option value="draft">Draft</option>
            <option value="void">Void</option>
          </select>
          <input type="month" v-model="filterMonth" class="rounded-lg border border-gray-300 px-3 py-2 text-sm focus:border-blue-500 focus:outline-none" />
        </div>
      </div>
    </div>

    <!-- Table -->
    <div class="rounded-xl border border-gray-200 bg-white shadow-sm overflow-hidden">
      <table class="w-full text-sm">
        <thead class="bg-gray-50 border-b border-gray-200">
          <tr>
            <th class="px-4 py-3 text-left font-semibold text-gray-600 w-32">{{ t('je.col_entry_no') }}</th>
            <th class="px-4 py-3 text-left font-semibold text-gray-600 w-28">{{ t('je.col_date') }}</th>
            <th class="px-4 py-3 text-left font-semibold text-gray-600">{{ t('je.col_description') }}</th>
            <th class="px-4 py-3 text-right font-semibold text-gray-600 w-32">{{ t('je.col_debit') }}</th>
            <th class="px-4 py-3 text-right font-semibold text-gray-600 w-32">{{ t('je.col_credit') }}</th>
            <th class="px-4 py-3 text-center font-semibold text-gray-600 w-24">{{ t('je.col_status') }}</th>
            <th class="px-4 py-3 text-center font-semibold text-gray-600 w-20">{{ t('je.col_actions') }}</th>
          </tr>
        </thead>
        <tbody class="divide-y divide-gray-100">
          <tr v-for="entry in filteredEntries" :key="entry.id" class="hover:bg-gray-50 transition-colors">
            <td class="px-4 py-3 font-mono text-xs font-medium text-blue-600">
              <a href="#" class="hover:underline">{{ entry.entry_no }}</a>
            </td>
            <td class="px-4 py-3 text-gray-600">{{ entry.date }}</td>
            <td class="px-4 py-3 text-gray-800">
              <div>{{ entry.description }}</div>
              <div class="text-xs text-gray-400">{{ entry.reference }}</div>
            </td>
            <td class="px-4 py-3 text-right font-mono text-gray-800">{{ formatCurrency(entry.total_debit) }}</td>
            <td class="px-4 py-3 text-right font-mono text-gray-800">{{ formatCurrency(entry.total_credit) }}</td>
            <td class="px-4 py-3 text-center">
              <span :class="statusBadgeClass(entry.status)" class="inline-flex items-center rounded-full px-2.5 py-0.5 text-xs font-medium">
                {{ entry.status }}
              </span>
            </td>
            <td class="px-4 py-3 text-center">
              <div class="flex items-center justify-center gap-1">
                <button class="text-gray-400 hover:text-blue-600 transition-colors p-1">
                  <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" /><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                  </svg>
                </button>
                <button v-if="entry.status === 'draft'" class="text-gray-400 hover:text-green-600 transition-colors p-1" :title="t('je.action_post')">
                  <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                  </svg>
                </button>
              </div>
            </td>
          </tr>
        </tbody>
      </table>
      <div v-if="filteredEntries.length === 0" class="py-12 text-center text-gray-400">
        {{ t('je.no_results') }}
      </div>
    </div>

    <!-- Pagination -->
    <div class="mt-4 flex items-center justify-between text-sm text-gray-500">
      <span>{{ t('je.showing', { count: filteredEntries.length, total: entries.length }) }}</span>
      <div class="flex gap-1">
        <button class="rounded border border-gray-300 px-3 py-1 hover:bg-gray-50">{{ t('je.prev') }}</button>
        <button class="rounded border border-blue-500 bg-blue-50 px-3 py-1 text-blue-600 font-medium">1</button>
        <button class="rounded border border-gray-300 px-3 py-1 hover:bg-gray-50">{{ t('je.next') }}</button>
      </div>
    </div>
  </div>
</template>

<script setup lang="ts">
definePageMeta({ middleware: 'auth' })

const { t } = useI18n()

const searchQuery = ref('')
const filterStatus = ref('')
const filterMonth = ref('2026-05')

interface JournalEntry {
  id: number
  entry_no: string
  date: string
  description: string
  reference: string
  total_debit: number
  total_credit: number
  status: 'posted' | 'draft' | 'void'
}

const entries = ref<JournalEntry[]>([
  { id: 1, entry_no: 'JE-2026-0001', date: '2026-05-01', description: 'Opening cash balance', reference: 'OPEN-2026', total_debit: 50000.00, total_credit: 50000.00, status: 'posted' },
  { id: 2, entry_no: 'JE-2026-0002', date: '2026-05-02', description: 'Sales invoice INV-0041 to ABC Co.', reference: 'INV-0041', total_debit: 11000.00, total_credit: 11000.00, status: 'posted' },
  { id: 3, entry_no: 'JE-2026-0003', date: '2026-05-03', description: 'Purchase of office supplies', reference: 'PO-0015', total_debit: 450.00, total_credit: 450.00, status: 'posted' },
  { id: 4, entry_no: 'JE-2026-0004', date: '2026-05-05', description: 'Salary payment – April 2026', reference: 'PAY-APR-26', total_debit: 18500.00, total_credit: 18500.00, status: 'posted' },
  { id: 5, entry_no: 'JE-2026-0005', date: '2026-05-06', description: 'NSSF contribution employer + employee', reference: 'NSSF-APR-26', total_debit: 925.00, total_credit: 925.00, status: 'posted' },
  { id: 6, entry_no: 'JE-2026-0006', date: '2026-05-08', description: 'Rent expense – May 2026', reference: 'RENT-MAY-26', total_debit: 3200.00, total_credit: 3200.00, status: 'posted' },
  { id: 7, entry_no: 'JE-2026-0007', date: '2026-05-10', description: 'Customer payment received – XYZ Ltd', reference: 'REC-0022', total_debit: 5500.00, total_credit: 5500.00, status: 'posted' },
  { id: 8, entry_no: 'JE-2026-0008', date: '2026-05-12', description: 'VAT return payment to GDT', reference: 'GDT-VAT-APR', total_debit: 2200.00, total_credit: 2200.00, status: 'posted' },
  { id: 9, entry_no: 'JE-2026-0009', date: '2026-05-14', description: 'Accrual – seniority indemnity May', reference: 'ACCR-SEN-05', total_debit: 308.33, total_credit: 308.33, status: 'draft' },
  { id: 10, entry_no: 'JE-2026-0010', date: '2026-05-15', description: 'Depreciation expense – May 2026', reference: 'DEP-MAY-26', total_debit: 750.00, total_credit: 750.00, status: 'draft' },
  { id: 11, entry_no: 'JE-2026-0011', date: '2026-05-15', description: 'Sales return – INV-0038 reversal', reference: 'CR-NOTE-003', total_debit: 550.00, total_credit: 550.00, status: 'void' },
  { id: 12, entry_no: 'JE-2026-0012', date: '2026-05-16', description: 'Bank charges – ABA April statement', reference: 'BANK-APR-26', total_debit: 45.00, total_credit: 45.00, status: 'draft' },
])

const filteredEntries = computed(() => {
  return entries.value.filter(e => {
    const matchSearch = searchQuery.value === '' ||
      e.entry_no.toLowerCase().includes(searchQuery.value.toLowerCase()) ||
      e.description.toLowerCase().includes(searchQuery.value.toLowerCase()) ||
      e.reference.toLowerCase().includes(searchQuery.value.toLowerCase())
    const matchStatus = filterStatus.value === '' || e.status === filterStatus.value
    const matchMonth = filterMonth.value === '' || e.date.startsWith(filterMonth.value)
    return matchSearch && matchStatus && matchMonth
  })
})

function statusBadgeClass(status: string) {
  const map: Record<string, string> = {
    posted: 'bg-green-100 text-green-700',
    draft: 'bg-gray-100 text-gray-600',
    void: 'bg-red-100 text-red-700',
  }
  return map[status] ?? 'bg-gray-100 text-gray-600'
}

function formatCurrency(amount: number) {
  return new Intl.NumberFormat('en-US', { minimumFractionDigits: 2, maximumFractionDigits: 2 }).format(amount)
}
</script>

<i18n lang="json">
{
  "en": {
    "je": {
      "title": "Journal Entries",
      "subtitle": "General ledger journal entries",
      "new_entry": "New Entry",
      "search_placeholder": "Search entry#, description, reference…",
      "all_status": "All Status",
      "stat_total": "Total Entries",
      "stat_posted": "Posted",
      "stat_draft": "Draft",
      "stat_void": "Void",
      "col_entry_no": "Entry #",
      "col_date": "Date",
      "col_description": "Description",
      "col_debit": "Total Debit",
      "col_credit": "Total Credit",
      "col_status": "Status",
      "col_actions": "Actions",
      "action_post": "Post Entry",
      "no_results": "No journal entries found.",
      "showing": "Showing {count} of {total} entries",
      "prev": "Prev",
      "next": "Next"
    }
  },
  "km": {
    "je": {
      "title": "ធាតុទិនានុប្បវត្តិ",
      "subtitle": "ធាតុទិនានុប្បវត្តិទូទៅ",
      "new_entry": "ធាតុថ្មី",
      "search_placeholder": "ស្វែងរក…",
      "all_status": "ស្ថានភាពទាំងអស់",
      "stat_total": "ធាតុសរុប",
      "stat_posted": "បានបើក",
      "stat_draft": "ព្រាង",
      "stat_void": "លុបចោល",
      "col_entry_no": "លេខធាតុ",
      "col_date": "កាលបរិច្ឆេទ",
      "col_description": "ការពណ៌នា",
      "col_debit": "ចំណាយសរុប",
      "col_credit": "ចំណូលសរុប",
      "col_status": "ស្ថានភាព",
      "col_actions": "សកម្មភាព",
      "action_post": "បញ្ជាក់ធាតុ",
      "no_results": "មិនមានធាតុទិនានុប្បវត្តិ",
      "showing": "បង្ហាញ {count} នៃ {total}",
      "prev": "មុន",
      "next": "បន្ទាប់"
    }
  }
}
</i18n>
