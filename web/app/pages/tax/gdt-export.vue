<template>
  <div class="min-h-screen bg-gray-50 p-6">
    <!-- Header -->
    <div class="mb-6">
      <h1 class="text-2xl font-bold text-gray-900">{{ t('gdt.title') }}</h1>
      <p class="mt-1 text-sm text-gray-500">{{ t('gdt.subtitle') }}</p>
    </div>

    <!-- Global Period Picker -->
    <div class="mb-6 rounded-xl border border-gray-200 bg-white p-4 shadow-sm flex flex-col sm:flex-row sm:items-center gap-4">
      <div class="flex items-center gap-3">
        <label class="text-sm font-medium text-gray-700 whitespace-nowrap">{{ t('gdt.period') }}</label>
        <input
          type="month"
          v-model="globalPeriod"
          class="rounded-lg border border-gray-300 px-3 py-2 text-sm font-medium focus:border-blue-500 focus:outline-none focus:ring-1 focus:ring-blue-500"
        />
      </div>
      <div class="flex items-center gap-2 text-sm text-gray-500">
        <svg class="h-4 w-4 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
          <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
        </svg>
        {{ t('gdt.period_note') }}
      </div>
    </div>

    <!-- Export Cards Grid -->
    <div class="grid grid-cols-1 gap-5 sm:grid-cols-2 xl:grid-cols-2">
      <!-- VAT Return -->
      <div class="rounded-xl border border-gray-200 bg-white shadow-sm overflow-hidden hover:shadow-md transition-shadow">
        <div class="border-b border-gray-100 bg-blue-50 px-5 py-4 flex items-center gap-3">
          <div class="flex h-10 w-10 items-center justify-center rounded-xl bg-blue-600 text-white font-bold text-sm">VAT</div>
          <div>
            <h3 class="font-semibold text-gray-900">{{ t('gdt.vat_return.title') }}</h3>
            <p class="text-xs text-gray-500">{{ t('gdt.vat_return.form_no') }}</p>
          </div>
          <span :class="['ml-auto inline-flex items-center rounded-full px-2.5 py-0.5 text-xs font-medium', exportStatuses.vat === 'ready' ? 'bg-green-100 text-green-700' : exportStatuses.vat === 'exported' ? 'bg-blue-100 text-blue-700' : 'bg-gray-100 text-gray-500']">
            {{ exportStatuses.vat === 'ready' ? t('gdt.status_ready') : exportStatuses.vat === 'exported' ? t('gdt.status_exported') : t('gdt.status_pending') }}
          </span>
        </div>
        <div class="px-5 py-4 space-y-3">
          <div class="grid grid-cols-2 gap-3 text-sm">
            <div class="rounded-lg bg-gray-50 p-3">
              <p class="text-xs text-gray-500">{{ t('gdt.vat_return.output_vat') }}</p>
              <p class="mt-0.5 font-semibold text-gray-900">${{ formatNum(vatSummary.output_vat) }}</p>
            </div>
            <div class="rounded-lg bg-gray-50 p-3">
              <p class="text-xs text-gray-500">{{ t('gdt.vat_return.input_vat') }}</p>
              <p class="mt-0.5 font-semibold text-gray-900">${{ formatNum(vatSummary.input_vat) }}</p>
            </div>
            <div class="rounded-lg bg-gray-50 p-3">
              <p class="text-xs text-gray-500">{{ t('gdt.vat_return.net_vat') }}</p>
              <p class="mt-0.5 font-semibold" :class="vatSummary.net_vat >= 0 ? 'text-red-600' : 'text-green-600'">${{ formatNum(Math.abs(vatSummary.net_vat)) }} {{ vatSummary.net_vat >= 0 ? t('gdt.payable') : t('gdt.refund') }}</p>
            </div>
            <div class="rounded-lg bg-gray-50 p-3">
              <p class="text-xs text-gray-500">{{ t('gdt.vat_return.invoice_count') }}</p>
              <p class="mt-0.5 font-semibold text-gray-900">{{ vatSummary.invoice_count }}</p>
            </div>
          </div>
          <div class="flex gap-2 pt-1">
            <button @click="previewExport('vat')" class="flex-1 inline-flex items-center justify-center gap-2 rounded-lg border border-gray-300 px-3 py-2 text-sm font-medium text-gray-700 hover:bg-gray-50 transition-colors">
              <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" /><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" /></svg>
              {{ t('gdt.btn_preview') }}
            </button>
            <button @click="downloadExport('vat', 'xlsx')" class="flex-1 inline-flex items-center justify-center gap-2 rounded-lg bg-green-600 px-3 py-2 text-sm font-medium text-white hover:bg-green-700 transition-colors">
              <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4" /></svg>
              {{ t('gdt.btn_download_xlsx') }}
            </button>
            <button @click="downloadExport('vat', 'xml')" class="flex-1 inline-flex items-center justify-center gap-2 rounded-lg bg-blue-600 px-3 py-2 text-sm font-medium text-white hover:bg-blue-700 transition-colors">
              <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4" /></svg>
              {{ t('gdt.btn_download_xml') }}
            </button>
          </div>
        </div>
      </div>

      <!-- WHT Return -->
      <div class="rounded-xl border border-gray-200 bg-white shadow-sm overflow-hidden hover:shadow-md transition-shadow">
        <div class="border-b border-gray-100 bg-orange-50 px-5 py-4 flex items-center gap-3">
          <div class="flex h-10 w-10 items-center justify-center rounded-xl bg-orange-600 text-white font-bold text-sm">WHT</div>
          <div>
            <h3 class="font-semibold text-gray-900">{{ t('gdt.wht.title') }}</h3>
            <p class="text-xs text-gray-500">{{ t('gdt.wht.form_no') }}</p>
          </div>
          <span class="ml-auto inline-flex items-center rounded-full px-2.5 py-0.5 text-xs font-medium bg-green-100 text-green-700">
            {{ t('gdt.status_ready') }}
          </span>
        </div>
        <div class="px-5 py-4 space-y-3">
          <div class="grid grid-cols-2 gap-3 text-sm">
            <div class="rounded-lg bg-gray-50 p-3">
              <p class="text-xs text-gray-500">{{ t('gdt.wht.total_payments') }}</p>
              <p class="mt-0.5 font-semibold text-gray-900">${{ formatNum(whtSummary.total_payments) }}</p>
            </div>
            <div class="rounded-lg bg-gray-50 p-3">
              <p class="text-xs text-gray-500">{{ t('gdt.wht.wht_withheld') }}</p>
              <p class="mt-0.5 font-semibold text-red-600">${{ formatNum(whtSummary.wht_amount) }}</p>
            </div>
            <div class="rounded-lg bg-gray-50 p-3">
              <p class="text-xs text-gray-500">{{ t('gdt.wht.transactions') }}</p>
              <p class="mt-0.5 font-semibold text-gray-900">{{ whtSummary.transaction_count }}</p>
            </div>
            <div class="rounded-lg bg-gray-50 p-3">
              <p class="text-xs text-gray-500">{{ t('gdt.wht.payees') }}</p>
              <p class="mt-0.5 font-semibold text-gray-900">{{ whtSummary.payee_count }}</p>
            </div>
          </div>
          <div class="flex gap-2 pt-1">
            <button @click="previewExport('wht')" class="flex-1 inline-flex items-center justify-center gap-2 rounded-lg border border-gray-300 px-3 py-2 text-sm font-medium text-gray-700 hover:bg-gray-50 transition-colors">
              <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" /><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" /></svg>
              {{ t('gdt.btn_preview') }}
            </button>
            <button @click="downloadExport('wht', 'xlsx')" class="flex-1 inline-flex items-center justify-center gap-2 rounded-lg bg-green-600 px-3 py-2 text-sm font-medium text-white hover:bg-green-700 transition-colors">
              <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4" /></svg>
              {{ t('gdt.btn_download_xlsx') }}
            </button>
          </div>
        </div>
      </div>

      <!-- ToS (Tax on Salary) -->
      <div class="rounded-xl border border-gray-200 bg-white shadow-sm overflow-hidden hover:shadow-md transition-shadow">
        <div class="border-b border-gray-100 bg-purple-50 px-5 py-4 flex items-center gap-3">
          <div class="flex h-10 w-10 items-center justify-center rounded-xl bg-purple-600 text-white font-bold text-sm">ToS</div>
          <div>
            <h3 class="font-semibold text-gray-900">{{ t('gdt.tos.title') }}</h3>
            <p class="text-xs text-gray-500">{{ t('gdt.tos.form_no') }}</p>
          </div>
          <span class="ml-auto inline-flex items-center rounded-full px-2.5 py-0.5 text-xs font-medium bg-green-100 text-green-700">
            {{ t('gdt.status_ready') }}
          </span>
        </div>
        <div class="px-5 py-4 space-y-3">
          <div class="grid grid-cols-2 gap-3 text-sm">
            <div class="rounded-lg bg-gray-50 p-3">
              <p class="text-xs text-gray-500">{{ t('gdt.tos.total_salary') }}</p>
              <p class="mt-0.5 font-semibold text-gray-900">${{ formatNum(tosSummary.total_salary) }}</p>
            </div>
            <div class="rounded-lg bg-gray-50 p-3">
              <p class="text-xs text-gray-500">{{ t('gdt.tos.tos_deducted') }}</p>
              <p class="mt-0.5 font-semibold text-red-600">${{ formatNum(tosSummary.tos_amount) }}</p>
            </div>
            <div class="rounded-lg bg-gray-50 p-3">
              <p class="text-xs text-gray-500">{{ t('gdt.tos.employees') }}</p>
              <p class="mt-0.5 font-semibold text-gray-900">{{ tosSummary.employee_count }}</p>
            </div>
            <div class="rounded-lg bg-gray-50 p-3">
              <p class="text-xs text-gray-500">{{ t('gdt.tos.exempt_employees') }}</p>
              <p class="mt-0.5 font-semibold text-gray-900">{{ tosSummary.exempt_count }}</p>
            </div>
          </div>
          <div class="flex gap-2 pt-1">
            <button @click="previewExport('tos')" class="flex-1 inline-flex items-center justify-center gap-2 rounded-lg border border-gray-300 px-3 py-2 text-sm font-medium text-gray-700 hover:bg-gray-50 transition-colors">
              <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" /><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" /></svg>
              {{ t('gdt.btn_preview') }}
            </button>
            <button @click="downloadExport('tos', 'xlsx')" class="flex-1 inline-flex items-center justify-center gap-2 rounded-lg bg-green-600 px-3 py-2 text-sm font-medium text-white hover:bg-green-700 transition-colors">
              <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4" /></svg>
              {{ t('gdt.btn_download_xlsx') }}
            </button>
          </div>
        </div>
      </div>

      <!-- PToI (Prepayment of Tax on Income) -->
      <div class="rounded-xl border border-gray-200 bg-white shadow-sm overflow-hidden hover:shadow-md transition-shadow">
        <div class="border-b border-gray-100 bg-emerald-50 px-5 py-4 flex items-center gap-3">
          <div class="flex h-10 w-10 items-center justify-center rounded-xl bg-emerald-600 text-white font-bold text-xs">PToI</div>
          <div>
            <h3 class="font-semibold text-gray-900">{{ t('gdt.ptoi.title') }}</h3>
            <p class="text-xs text-gray-500">{{ t('gdt.ptoi.form_no') }}</p>
          </div>
          <span class="ml-auto inline-flex items-center rounded-full px-2.5 py-0.5 text-xs font-medium bg-gray-100 text-gray-500">
            {{ t('gdt.status_pending') }}
          </span>
        </div>
        <div class="px-5 py-4 space-y-3">
          <div class="grid grid-cols-2 gap-3 text-sm">
            <div class="rounded-lg bg-gray-50 p-3">
              <p class="text-xs text-gray-500">{{ t('gdt.ptoi.gross_revenue') }}</p>
              <p class="mt-0.5 font-semibold text-gray-900">${{ formatNum(ptoiSummary.gross_revenue) }}</p>
            </div>
            <div class="rounded-lg bg-gray-50 p-3">
              <p class="text-xs text-gray-500">{{ t('gdt.ptoi.ptoi_1pct') }}</p>
              <p class="mt-0.5 font-semibold text-red-600">${{ formatNum(ptoiSummary.ptoi_amount) }}</p>
            </div>
            <div class="rounded-lg bg-gray-50 p-3">
              <p class="text-xs text-gray-500">{{ t('gdt.ptoi.due_date') }}</p>
              <p class="mt-0.5 font-semibold text-gray-900">{{ ptoiSummary.due_date }}</p>
            </div>
            <div class="rounded-lg bg-gray-50 p-3">
              <p class="text-xs text-gray-500">{{ t('gdt.ptoi.method') }}</p>
              <p class="mt-0.5 font-semibold text-gray-900">{{ ptoiSummary.method }}</p>
            </div>
          </div>
          <div class="flex gap-2 pt-1">
            <button @click="previewExport('ptoi')" class="flex-1 inline-flex items-center justify-center gap-2 rounded-lg border border-gray-300 px-3 py-2 text-sm font-medium text-gray-700 hover:bg-gray-50 transition-colors">
              <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" /><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" /></svg>
              {{ t('gdt.btn_preview') }}
            </button>
            <button @click="downloadExport('ptoi', 'xlsx')" class="flex-1 inline-flex items-center justify-center gap-2 rounded-lg bg-emerald-600 px-3 py-2 text-sm font-medium text-white hover:bg-emerald-700 transition-colors">
              <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4" /></svg>
              {{ t('gdt.btn_download_xlsx') }}
            </button>
          </div>
        </div>
      </div>
    </div>

    <!-- Export Log -->
    <div class="mt-6 rounded-xl border border-gray-200 bg-white shadow-sm overflow-hidden">
      <div class="border-b border-gray-100 px-5 py-4">
        <h3 class="font-semibold text-gray-900">{{ t('gdt.export_log') }}</h3>
      </div>
      <table class="w-full text-sm">
        <thead class="bg-gray-50 border-b border-gray-200">
          <tr>
            <th class="px-4 py-3 text-left font-semibold text-gray-600">{{ t('gdt.log_type') }}</th>
            <th class="px-4 py-3 text-left font-semibold text-gray-600">{{ t('gdt.log_period') }}</th>
            <th class="px-4 py-3 text-left font-semibold text-gray-600">{{ t('gdt.log_format') }}</th>
            <th class="px-4 py-3 text-left font-semibold text-gray-600">{{ t('gdt.log_exported_by') }}</th>
            <th class="px-4 py-3 text-left font-semibold text-gray-600">{{ t('gdt.log_exported_at') }}</th>
            <th class="px-4 py-3 text-center font-semibold text-gray-600">{{ t('gdt.log_download') }}</th>
          </tr>
        </thead>
        <tbody class="divide-y divide-gray-100">
          <tr v-for="log in exportLogs" :key="log.id" class="hover:bg-gray-50">
            <td class="px-4 py-3">
              <span :class="logTypeBadge(log.type)" class="inline-flex items-center rounded-full px-2.5 py-0.5 text-xs font-medium">{{ log.type }}</span>
            </td>
            <td class="px-4 py-3 text-gray-600">{{ log.period }}</td>
            <td class="px-4 py-3 font-mono text-xs text-gray-500 uppercase">{{ log.format }}</td>
            <td class="px-4 py-3 text-gray-600">{{ log.exported_by }}</td>
            <td class="px-4 py-3 text-xs text-gray-500">{{ log.exported_at }}</td>
            <td class="px-4 py-3 text-center">
              <button class="text-blue-600 hover:text-blue-800 text-xs font-medium hover:underline">
                {{ t('gdt.btn_redownload') }}
              </button>
            </td>
          </tr>
        </tbody>
      </table>
    </div>

    <!-- Toast Notification -->
    <div v-if="toast.show" class="fixed bottom-6 right-6 z-50 rounded-xl border border-green-200 bg-green-50 p-4 shadow-lg flex items-center gap-3">
      <svg class="h-5 w-5 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
      </svg>
      <span class="text-sm font-medium text-green-800">{{ toast.message }}</span>
    </div>
  </div>
</template>

<script setup lang="ts">
definePageMeta({ middleware: 'auth' })

const { t } = useI18n()

const globalPeriod = ref('2026-04')

const exportStatuses = ref({
  vat: 'ready' as 'ready' | 'exported' | 'pending',
  wht: 'ready',
  tos: 'ready',
  ptoi: 'pending',
})

const vatSummary = ref({ output_vat: 4520.00, input_vat: 1230.00, net_vat: 3290.00, invoice_count: 41 })
const whtSummary = ref({ total_payments: 18500.00, wht_amount: 2775.00, transaction_count: 14, payee_count: 8 })
const tosSummary = ref({ total_salary: 22000.00, tos_amount: 1105.00, employee_count: 15, exempt_count: 3 })
const ptoiSummary = ref({ gross_revenue: 45200.00, ptoi_amount: 452.00, due_date: '2026-05-20', method: '1% of Revenue' })

const exportLogs = ref([
  { id: 1, type: 'VAT', period: '2026-03', format: 'xlsx', exported_by: 'sopheak', exported_at: '2026-04-10 09:32' },
  { id: 2, type: 'WHT', period: '2026-03', format: 'xlsx', exported_by: 'sopheak', exported_at: '2026-04-10 09:45' },
  { id: 3, type: 'ToS', period: '2026-03', format: 'xlsx', exported_by: 'admin', exported_at: '2026-04-10 10:02' },
  { id: 4, type: 'PToI', period: '2026-03', format: 'xlsx', exported_by: 'admin', exported_at: '2026-04-10 10:15' },
  { id: 5, type: 'VAT', period: '2026-02', format: 'xml', exported_by: 'sopheak', exported_at: '2026-03-09 14:22' },
])

const toast = ref({ show: false, message: '' })

function showToast(msg: string) {
  toast.value = { show: true, message: msg }
  setTimeout(() => { toast.value.show = false }, 3000)
}

function previewExport(type: string) {
  showToast(`Preview for ${type.toUpperCase()} – ${globalPeriod.value}`)
}

function downloadExport(type: string, format: string) {
  const filename = `${type.toUpperCase()}_${globalPeriod.value}.${format}`
  showToast(`Downloaded: ${filename}`)
  if (exportStatuses.value[type as keyof typeof exportStatuses.value] === 'ready') {
    exportLogs.value.unshift({
      id: Date.now(),
      type: type.toUpperCase(),
      period: globalPeriod.value,
      format,
      exported_by: 'current_user',
      exported_at: new Date().toLocaleString(),
    })
  }
}

function logTypeBadge(type: string) {
  const map: Record<string, string> = {
    VAT: 'bg-blue-100 text-blue-700',
    WHT: 'bg-orange-100 text-orange-700',
    ToS: 'bg-purple-100 text-purple-700',
    PToI: 'bg-emerald-100 text-emerald-700',
  }
  return map[type] ?? 'bg-gray-100 text-gray-700'
}

function formatNum(n: number) {
  return new Intl.NumberFormat('en-US', { minimumFractionDigits: 2 }).format(n)
}
</script>

<i18n lang="json">
{
  "en": {
    "gdt": {
      "title": "GDT Export",
      "subtitle": "Generate and download General Department of Taxation reports",
      "period": "Reporting Period",
      "period_note": "Exports will be generated for the selected period.",
      "status_ready": "Ready",
      "status_exported": "Exported",
      "status_pending": "Pending",
      "payable": "Payable",
      "refund": "Credit",
      "btn_preview": "Preview",
      "btn_download_xlsx": "XLSX",
      "btn_download_xml": "XML",
      "btn_redownload": "Download",
      "export_log": "Export History",
      "log_type": "Type",
      "log_period": "Period",
      "log_format": "Format",
      "log_exported_by": "Exported By",
      "log_exported_at": "Exported At",
      "log_download": "Re-download",
      "vat_return": { "title": "VAT Return", "form_no": "Form VAT-02", "output_vat": "Output VAT", "input_vat": "Input VAT (Credit)", "net_vat": "Net VAT Due", "invoice_count": "Invoices" },
      "wht": { "title": "Withholding Tax", "form_no": "Form WHT-01", "total_payments": "Total Payments", "wht_withheld": "WHT Withheld", "transactions": "Transactions", "payees": "Payees" },
      "tos": { "title": "Tax on Salary", "form_no": "Form ToS-01", "total_salary": "Total Salary", "tos_deducted": "ToS Deducted", "employees": "Employees", "exempt_employees": "Exempt" },
      "ptoi": { "title": "Prepayment of Tax on Income", "form_no": "Form PToI-01", "gross_revenue": "Gross Revenue", "ptoi_1pct": "PToI (1%)", "due_date": "Due Date", "method": "Calculation Method" }
    }
  },
  "km": {
    "gdt": {
      "title": "នាំចេញ GDT",
      "subtitle": "បង្កើតនិងទាញយករបាយការណ៍ពន្ធ",
      "period": "រយៈពេលរបាយការណ៍",
      "period_note": "ការនាំចេញនឹងត្រូវបានបង្កើតសម្រាប់រយៈពេលដែលបានជ្រើស",
      "status_ready": "រួចរាល់",
      "status_exported": "បាននាំចេញ",
      "status_pending": "កំពុងរង់ចាំ",
      "payable": "ត្រូវបង់",
      "refund": "ដែលអាចទាមទារ",
      "btn_preview": "មើលជាមុន",
      "btn_download_xlsx": "XLSX",
      "btn_download_xml": "XML",
      "btn_redownload": "ទាញយកម្តងទៀត",
      "export_log": "ប្រវត្តិការនាំចេញ",
      "log_type": "ប្រភេទ",
      "log_period": "រយៈពេល",
      "log_format": "ទម្រង់",
      "log_exported_by": "នាំចេញដោយ",
      "log_exported_at": "នាំចេញនៅ",
      "log_download": "ទាញយក",
      "vat_return": { "title": "របាយការណ៍ VAT", "form_no": "ទម្រង់ VAT-02", "output_vat": "VAT លក់", "input_vat": "VAT ទិញ", "net_vat": "VAT ត្រូវបង់", "invoice_count": "វិក្កយបត្រ" },
      "wht": { "title": "ពន្ធកាត់ទុក", "form_no": "ទម្រង់ WHT-01", "total_payments": "ការទូទាត់សរុប", "wht_withheld": "WHT កាត់ទុក", "transactions": "ប្រតិបត្តិការ", "payees": "អ្នកទទួល" },
      "tos": { "title": "ពន្ធលើប្រាក់ខែ", "form_no": "ទម្រង់ ToS-01", "total_salary": "ប្រាក់ខែសរុប", "tos_deducted": "ToS ដែលកាត់", "employees": "បុគ្គលិក", "exempt_employees": "ត្រូវបានលើកលែង" },
      "ptoi": { "title": "ការបង់ជាមុននៃពន្ធ", "form_no": "ទម្រង់ PToI-01", "gross_revenue": "ចំណូលសរុប", "ptoi_1pct": "PToI (1%)", "due_date": "ថ្ងៃកំណត់", "method": "វិធីគណនា" }
    }
  }
}
</i18n>
