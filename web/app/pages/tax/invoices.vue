<template>
  <div class="min-h-screen bg-gray-50 p-6">
    <!-- Header -->
    <div class="mb-6 flex items-center justify-between">
      <div>
        <h1 class="text-2xl font-bold text-gray-900">{{ t('inv.title') }}</h1>
        <p class="mt-1 text-sm text-gray-500">{{ t('inv.subtitle') }}</p>
      </div>
      <button class="inline-flex items-center gap-2 rounded-lg bg-blue-600 px-4 py-2 text-sm font-medium text-white shadow-sm hover:bg-blue-700">
        <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
          <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" />
        </svg>
        {{ t('inv.new_invoice') }}
      </button>
    </div>

    <!-- Summary Bar -->
    <div class="mb-6 grid grid-cols-2 gap-4 sm:grid-cols-4">
      <div class="rounded-xl border border-gray-200 bg-white p-4 shadow-sm">
        <p class="text-xs font-medium text-gray-500 uppercase tracking-wide">{{ t('inv.total_invoiced') }}</p>
        <p class="mt-1 text-xl font-bold text-gray-900">${{ formatNum(summaryTotals.subtotal) }}</p>
        <p class="text-xs text-gray-400 mt-0.5">≈ ៛{{ formatNum(summaryTotals.subtotal * 4100) }}</p>
      </div>
      <div class="rounded-xl border border-gray-200 bg-white p-4 shadow-sm">
        <p class="text-xs font-medium text-gray-500 uppercase tracking-wide">{{ t('inv.total_vat') }}</p>
        <p class="mt-1 text-xl font-bold text-blue-600">${{ formatNum(summaryTotals.vat) }}</p>
        <p class="text-xs text-gray-400 mt-0.5">≈ ៛{{ formatNum(summaryTotals.vat * 4100) }}</p>
      </div>
      <div class="rounded-xl border border-gray-200 bg-white p-4 shadow-sm">
        <p class="text-xs font-medium text-gray-500 uppercase tracking-wide">{{ t('inv.total_gross') }}</p>
        <p class="mt-1 text-xl font-bold text-gray-900">${{ formatNum(summaryTotals.total) }}</p>
        <p class="text-xs text-gray-400 mt-0.5">≈ ៛{{ formatNum(summaryTotals.total * 4100) }}</p>
      </div>
      <div class="rounded-xl border border-gray-200 bg-white p-4 shadow-sm">
        <p class="text-xs font-medium text-gray-500 uppercase tracking-wide">{{ t('inv.outstanding') }}</p>
        <p class="mt-1 text-xl font-bold text-orange-500">${{ formatNum(summaryTotals.outstanding) }}</p>
        <p class="text-xs text-gray-400 mt-0.5">≈ ៛{{ formatNum(summaryTotals.outstanding * 4100) }}</p>
      </div>
    </div>

    <!-- Filter Tabs + Search -->
    <div class="mb-4 rounded-xl border border-gray-200 bg-white shadow-sm overflow-hidden">
      <!-- Status Tabs -->
      <div class="flex border-b border-gray-200">
        <button
          v-for="tab in statusTabs"
          :key="tab.value"
          @click="activeTab = tab.value"
          :class="[
            'px-4 py-3 text-sm font-medium border-b-2 transition-colors',
            activeTab === tab.value
              ? 'border-blue-600 text-blue-600'
              : 'border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300',
          ]"
        >
          {{ tab.label }}
          <span :class="['ml-1.5 inline-flex items-center rounded-full px-1.5 py-0.5 text-xs', activeTab === tab.value ? 'bg-blue-100 text-blue-700' : 'bg-gray-100 text-gray-500']">
            {{ tab.count }}
          </span>
        </button>
      </div>
      <!-- Search Row -->
      <div class="p-4 flex gap-3">
        <div class="relative flex-1">
          <svg class="absolute left-3 top-1/2 h-4 w-4 -translate-y-1/2 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
          </svg>
          <input v-model="searchQuery" type="text" :placeholder="t('inv.search_placeholder')" class="w-full rounded-lg border border-gray-300 py-2 pl-10 pr-4 text-sm focus:border-blue-500 focus:outline-none focus:ring-1 focus:ring-blue-500" />
        </div>
        <input type="month" v-model="filterMonth" class="rounded-lg border border-gray-300 px-3 py-2 text-sm focus:border-blue-500 focus:outline-none" />
      </div>
    </div>

    <!-- Table -->
    <div class="rounded-xl border border-gray-200 bg-white shadow-sm overflow-x-auto">
      <table class="w-full text-sm min-w-[900px]">
        <thead class="bg-gray-50 border-b border-gray-200">
          <tr>
            <th class="px-4 py-3 text-left font-semibold text-gray-600 w-32">{{ t('inv.col_invoice_no') }}</th>
            <th class="px-4 py-3 text-left font-semibold text-gray-600">{{ t('inv.col_customer') }}</th>
            <th class="px-4 py-3 text-left font-semibold text-gray-600 w-32">{{ t('inv.col_vatin') }}</th>
            <th class="px-4 py-3 text-left font-semibold text-gray-600 w-24">{{ t('inv.col_date') }}</th>
            <th class="px-4 py-3 text-right font-semibold text-gray-600 w-28">{{ t('inv.col_subtotal') }}</th>
            <th class="px-4 py-3 text-right font-semibold text-gray-600 w-24">{{ t('inv.col_vat') }}</th>
            <th class="px-4 py-3 text-right font-semibold text-gray-600 w-28">{{ t('inv.col_total') }}</th>
            <th class="px-4 py-3 text-center font-semibold text-gray-600 w-24">{{ t('inv.col_status') }}</th>
            <th class="px-4 py-3 text-center font-semibold text-gray-600 w-16">{{ t('inv.col_actions') }}</th>
          </tr>
        </thead>
        <tbody class="divide-y divide-gray-100">
          <tr v-for="inv in filteredInvoices" :key="inv.id" class="hover:bg-gray-50 transition-colors">
            <td class="px-4 py-3 font-mono text-xs font-medium text-blue-600">
              <a href="#" class="hover:underline">{{ inv.invoice_no }}</a>
            </td>
            <td class="px-4 py-3">
              <div class="font-medium text-gray-900">{{ inv.customer_en }}</div>
              <div class="text-xs text-gray-400 font-khmer">{{ inv.customer_km }}</div>
            </td>
            <td class="px-4 py-3 font-mono text-xs text-gray-500">{{ inv.vatin || '—' }}</td>
            <td class="px-4 py-3 text-gray-600">{{ inv.date }}</td>
            <td class="px-4 py-3 text-right font-mono text-gray-800">${{ formatNum(inv.subtotal) }}</td>
            <td class="px-4 py-3 text-right font-mono text-blue-600">${{ formatNum(inv.vat) }}</td>
            <td class="px-4 py-3 text-right font-mono font-semibold text-gray-900">${{ formatNum(inv.total) }}</td>
            <td class="px-4 py-3 text-center">
              <span :class="statusBadgeClass(inv.status)" class="inline-flex items-center rounded-full px-2.5 py-0.5 text-xs font-medium">
                {{ inv.status }}
              </span>
            </td>
            <td class="px-4 py-3 text-center">
              <button class="text-gray-400 hover:text-blue-600 p-1 transition-colors">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                  <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" /><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                </svg>
              </button>
            </td>
          </tr>
        </tbody>
      </table>
      <div v-if="filteredInvoices.length === 0" class="py-12 text-center text-gray-400">{{ t('inv.no_results') }}</div>
    </div>
  </div>
</template>

<script setup lang="ts">
definePageMeta({ middleware: 'auth' })

const { t } = useI18n()

const searchQuery = ref('')
const filterMonth = ref('2026-05')
const activeTab = ref('')

type InvoiceStatus = 'draft' | 'issued' | 'paid' | 'void'

interface Invoice {
  id: number
  invoice_no: string
  customer_en: string
  customer_km: string
  vatin: string
  date: string
  subtotal: number
  vat: number
  total: number
  status: InvoiceStatus
}

const invoices = ref<Invoice[]>([
  { id: 1, invoice_no: 'INV-2026-0041', customer_en: 'ABC Trading Co., Ltd.', customer_km: 'ក្រុមហ៊ុន ABC ជំនួញ', vatin: 'K001-900123456', date: '2026-05-02', subtotal: 5000.00, vat: 500.00, total: 5500.00, status: 'paid' },
  { id: 2, invoice_no: 'INV-2026-0042', customer_en: 'XYZ Logistics', customer_km: 'XYZ ដឹកជញ្ជូន', vatin: 'K001-900234567', date: '2026-05-04', subtotal: 2800.00, vat: 280.00, total: 3080.00, status: 'paid' },
  { id: 3, invoice_no: 'INV-2026-0043', customer_en: 'Phnom Penh Retail Group', customer_km: 'ក្រុមលក់រាយភ្នំពេញ', vatin: 'K001-900345678', date: '2026-05-06', subtotal: 9200.00, vat: 920.00, total: 10120.00, status: 'issued' },
  { id: 4, invoice_no: 'INV-2026-0044', customer_en: 'Siem Reap Hotels Co.', customer_km: 'ក្រុមហ៊ុនសណ្ឋាគារសៀមរាប', vatin: '', date: '2026-05-08', subtotal: 1500.00, vat: 150.00, total: 1650.00, status: 'issued' },
  { id: 5, invoice_no: 'INV-2026-0045', customer_en: 'Mekong Exports Ltd.', customer_km: 'ក្រុមហ៊ុន Mekong នាំចេញ', vatin: 'K001-900456789', date: '2026-05-10', subtotal: 15000.00, vat: 1500.00, total: 16500.00, status: 'issued' },
  { id: 6, invoice_no: 'INV-2026-0046', customer_en: 'Golden Star Enterprise', customer_km: 'សហគ្រាស Golden Star', vatin: 'K001-900567890', date: '2026-05-12', subtotal: 3300.00, vat: 330.00, total: 3630.00, status: 'draft' },
  { id: 7, invoice_no: 'INV-2026-0047', customer_en: 'Kampot Fresh Produce', customer_km: 'ផ្លែឈើស្រស់កំពត', vatin: '', date: '2026-05-13', subtotal: 780.00, vat: 78.00, total: 858.00, status: 'draft' },
  { id: 8, invoice_no: 'INV-2026-0038', customer_en: 'Bayon Tech Solutions', customer_km: 'ដំណោះស្រាយបច្ចេកវិទ្យាបាយ័ន', vatin: 'K001-900678901', date: '2026-04-28', subtotal: 500.00, vat: 50.00, total: 550.00, status: 'void' },
])

const statusTabs = computed(() => {
  const all = invoices.value
  return [
    { value: '', label: 'All', count: all.length },
    { value: 'draft', label: 'Draft', count: all.filter(i => i.status === 'draft').length },
    { value: 'issued', label: 'Issued', count: all.filter(i => i.status === 'issued').length },
    { value: 'paid', label: 'Paid', count: all.filter(i => i.status === 'paid').length },
    { value: 'void', label: 'Void', count: all.filter(i => i.status === 'void').length },
  ]
})

const filteredInvoices = computed(() => {
  return invoices.value.filter(inv => {
    const matchTab = activeTab.value === '' || inv.status === activeTab.value
    const matchSearch = searchQuery.value === '' ||
      inv.invoice_no.toLowerCase().includes(searchQuery.value.toLowerCase()) ||
      inv.customer_en.toLowerCase().includes(searchQuery.value.toLowerCase()) ||
      inv.customer_km.includes(searchQuery.value) ||
      inv.vatin.includes(searchQuery.value)
    const matchMonth = filterMonth.value === '' || inv.date.startsWith(filterMonth.value)
    return matchTab && matchSearch && matchMonth
  })
})

const summaryTotals = computed(() => {
  const active = invoices.value.filter(i => i.status !== 'void')
  return {
    subtotal: active.reduce((s, i) => s + i.subtotal, 0),
    vat: active.reduce((s, i) => s + i.vat, 0),
    total: active.reduce((s, i) => s + i.total, 0),
    outstanding: active.filter(i => i.status === 'issued').reduce((s, i) => s + i.total, 0),
  }
})

function statusBadgeClass(status: InvoiceStatus) {
  const map: Record<InvoiceStatus, string> = {
    draft: 'bg-gray-100 text-gray-600',
    issued: 'bg-blue-100 text-blue-700',
    paid: 'bg-green-100 text-green-700',
    void: 'bg-red-100 text-red-700',
  }
  return map[status]
}

function formatNum(n: number) {
  return new Intl.NumberFormat('en-US', { minimumFractionDigits: 2, maximumFractionDigits: 2 }).format(n)
}
</script>

<i18n lang="json">
{
  "en": {
    "inv": {
      "title": "Tax Invoices",
      "subtitle": "VAT invoices issued to customers",
      "new_invoice": "New Invoice",
      "search_placeholder": "Search invoice#, customer, VATIN…",
      "total_invoiced": "Total Subtotal",
      "total_vat": "Total VAT (10%)",
      "total_gross": "Total Gross",
      "outstanding": "Outstanding",
      "col_invoice_no": "Invoice #",
      "col_customer": "Customer",
      "col_vatin": "VATIN",
      "col_date": "Date",
      "col_subtotal": "Subtotal",
      "col_vat": "VAT 10%",
      "col_total": "Total",
      "col_status": "Status",
      "col_actions": "Actions",
      "no_results": "No invoices found."
    }
  },
  "km": {
    "inv": {
      "title": "វិក្កយបត្រអាករ",
      "subtitle": "វិក្កយបត្រ VAT ដែលបានចេញ",
      "new_invoice": "វិក្កយបត្រថ្មី",
      "search_placeholder": "ស្វែងរក…",
      "total_invoiced": "សរុបរង",
      "total_vat": "VAT សរុប (10%)",
      "total_gross": "សរុបរួម",
      "outstanding": "ជំពាក់",
      "col_invoice_no": "លេខវិក្កយបត្រ",
      "col_customer": "អតិថិជន",
      "col_vatin": "VATIN",
      "col_date": "កាលបរិច្ឆេទ",
      "col_subtotal": "សរុបរង",
      "col_vat": "VAT 10%",
      "col_total": "សរុបរួម",
      "col_status": "ស្ថានភាព",
      "col_actions": "សកម្មភាព",
      "no_results": "មិនមានវិក្កយបត្រ"
    }
  }
}
</i18n>
