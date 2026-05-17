<template>
  <div class="min-h-screen bg-gray-50 p-6">
    <!-- Header -->
    <div class="mb-6 flex items-center justify-between">
      <div>
        <h1 class="text-2xl font-bold text-gray-900">{{ t('pp.title') }}</h1>
        <p class="mt-1 text-sm text-gray-500">{{ t('pp.subtitle') }}</p>
      </div>
      <div class="flex items-center gap-3">
        <select v-model="selectedYear" class="rounded-lg border border-gray-300 px-3 py-2 text-sm font-medium focus:border-blue-500 focus:outline-none">
          <option v-for="y in [2024, 2025, 2026, 2027]" :key="y" :value="y">{{ y }}</option>
        </select>
      </div>
    </div>

    <!-- Legend -->
    <div class="mb-5 flex flex-wrap gap-3">
      <div class="flex items-center gap-2 rounded-lg border border-gray-200 bg-white px-3 py-2 text-xs shadow-sm">
        <span class="h-3 w-3 rounded-full bg-green-500"></span>
        {{ t('pp.status_open') }}
      </div>
      <div class="flex items-center gap-2 rounded-lg border border-gray-200 bg-white px-3 py-2 text-xs shadow-sm">
        <span class="h-3 w-3 rounded-full bg-yellow-400"></span>
        {{ t('pp.status_soft_closed') }}
      </div>
      <div class="flex items-center gap-2 rounded-lg border border-gray-200 bg-white px-3 py-2 text-xs shadow-sm">
        <span class="h-3 w-3 rounded-full bg-red-500"></span>
        {{ t('pp.status_hard_closed') }}
      </div>
      <div class="flex items-center gap-2 rounded-lg border border-gray-200 bg-white px-3 py-2 text-xs shadow-sm">
        <span class="h-3 w-3 rounded-full bg-gray-300"></span>
        {{ t('pp.status_future') }}
      </div>
    </div>

    <!-- Month Grid -->
    <div class="grid grid-cols-1 gap-4 sm:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4">
      <div
        v-for="period in periods"
        :key="period.month"
        :class="[
          'rounded-xl border bg-white shadow-sm overflow-hidden transition-shadow hover:shadow-md',
          borderColorClass(period.status),
        ]"
      >
        <!-- Card Header -->
        <div :class="['px-4 py-3 flex items-center justify-between', headerBgClass(period.status)]">
          <div>
            <p class="text-sm font-semibold text-gray-900">{{ period.month_label }}</p>
            <p class="text-xs text-gray-500">{{ selectedYear }}</p>
          </div>
          <span :class="['inline-flex items-center rounded-full px-2.5 py-0.5 text-xs font-medium', statusBadgeClass(period.status)]">
            {{ statusLabel(period.status) }}
          </span>
        </div>

        <!-- Card Body -->
        <div class="px-4 py-3 space-y-2">
          <div class="flex justify-between text-xs text-gray-500">
            <span>{{ t('pp.entries') }}</span>
            <span class="font-medium text-gray-800">{{ period.entry_count }}</span>
          </div>
          <div class="flex justify-between text-xs text-gray-500">
            <span>{{ t('pp.net_movement') }}</span>
            <span class="font-medium text-gray-800">{{ period.status === 'future' ? '—' : formatCurrency(period.net_movement) }}</span>
          </div>
          <div v-if="period.closed_by" class="flex justify-between text-xs text-gray-500">
            <span>{{ t('pp.closed_by') }}</span>
            <span class="font-medium text-gray-700">{{ period.closed_by }}</span>
          </div>
          <div v-if="period.closed_at" class="flex justify-between text-xs text-gray-500">
            <span>{{ t('pp.closed_at') }}</span>
            <span class="font-medium text-gray-700">{{ period.closed_at }}</span>
          </div>
        </div>

        <!-- Card Actions -->
        <div class="border-t border-gray-100 px-4 py-3 flex gap-2 flex-wrap">
          <template v-if="period.status === 'open'">
            <button
              @click="updateStatus(period, 'soft_closed')"
              class="flex-1 rounded-lg border border-yellow-300 bg-yellow-50 px-2 py-1.5 text-xs font-medium text-yellow-700 hover:bg-yellow-100 transition-colors"
            >
              {{ t('pp.action_soft_close') }}
            </button>
            <button
              @click="updateStatus(period, 'hard_closed')"
              class="flex-1 rounded-lg border border-red-300 bg-red-50 px-2 py-1.5 text-xs font-medium text-red-700 hover:bg-red-100 transition-colors"
            >
              {{ t('pp.action_hard_close') }}
            </button>
          </template>
          <template v-else-if="period.status === 'soft_closed'">
            <button
              @click="updateStatus(period, 'open')"
              class="flex-1 rounded-lg border border-green-300 bg-green-50 px-2 py-1.5 text-xs font-medium text-green-700 hover:bg-green-100 transition-colors"
            >
              {{ t('pp.action_reopen') }}
            </button>
            <button
              @click="updateStatus(period, 'hard_closed')"
              class="flex-1 rounded-lg border border-red-300 bg-red-50 px-2 py-1.5 text-xs font-medium text-red-700 hover:bg-red-100 transition-colors"
            >
              {{ t('pp.action_hard_close') }}
            </button>
          </template>
          <template v-else-if="period.status === 'hard_closed'">
            <button
              disabled
              class="flex-1 rounded-lg border border-gray-200 bg-gray-50 px-2 py-1.5 text-xs font-medium text-gray-400 cursor-not-allowed"
            >
              {{ t('pp.hard_closed_locked') }}
            </button>
          </template>
          <template v-else>
            <span class="flex-1 text-center text-xs text-gray-400 py-1.5">{{ t('pp.not_yet_open') }}</span>
          </template>
        </div>
      </div>
    </div>

    <!-- Confirmation Modal -->
    <div v-if="confirmModal.show" class="fixed inset-0 z-50 flex items-center justify-center bg-black/50 p-4">
      <div class="w-full max-w-sm rounded-2xl bg-white shadow-2xl p-6">
        <div class="flex items-start gap-3 mb-4">
          <div :class="['flex-shrink-0 rounded-full p-2', confirmModal.targetStatus === 'hard_closed' ? 'bg-red-100' : confirmModal.targetStatus === 'soft_closed' ? 'bg-yellow-100' : 'bg-green-100']">
            <svg class="w-5 h-5" :class="confirmModal.targetStatus === 'hard_closed' ? 'text-red-600' : confirmModal.targetStatus === 'soft_closed' ? 'text-yellow-600' : 'text-green-600'" fill="none" stroke="currentColor" viewBox="0 0 24 24">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z" />
            </svg>
          </div>
          <div>
            <h3 class="text-base font-semibold text-gray-900">{{ confirmModal.title }}</h3>
            <p class="mt-1 text-sm text-gray-500">{{ confirmModal.message }}</p>
          </div>
        </div>
        <div class="flex gap-3">
          <button @click="confirmModal.show = false" class="flex-1 rounded-lg border border-gray-300 px-4 py-2 text-sm font-medium text-gray-700 hover:bg-gray-50">
            {{ t('common.cancel') }}
          </button>
          <button @click="confirmAction" :class="['flex-1 rounded-lg px-4 py-2 text-sm font-medium text-white', confirmModal.targetStatus === 'hard_closed' ? 'bg-red-600 hover:bg-red-700' : confirmModal.targetStatus === 'soft_closed' ? 'bg-yellow-500 hover:bg-yellow-600' : 'bg-green-600 hover:bg-green-700']">
            {{ t('common.confirm') }}
          </button>
        </div>
      </div>
    </div>
  </div>
</template>

<script setup lang="ts">
definePageMeta({ middleware: 'auth' })

const { t } = useI18n()

const selectedYear = ref(2026)

type PeriodStatus = 'open' | 'soft_closed' | 'hard_closed' | 'future'

interface Period {
  month: number
  month_label: string
  status: PeriodStatus
  entry_count: number
  net_movement: number
  closed_by?: string
  closed_at?: string
}

const periods = ref<Period[]>([
  { month: 1, month_label: 'January', status: 'hard_closed', entry_count: 48, net_movement: 125430.50, closed_by: 'admin', closed_at: '2026-02-05' },
  { month: 2, month_label: 'February', status: 'hard_closed', entry_count: 39, net_movement: 98220.00, closed_by: 'admin', closed_at: '2026-03-04' },
  { month: 3, month_label: 'March', status: 'hard_closed', entry_count: 52, net_movement: 142680.75, closed_by: 'admin', closed_at: '2026-04-03' },
  { month: 4, month_label: 'April', status: 'soft_closed', entry_count: 44, net_movement: 110950.25, closed_by: 'sopheak', closed_at: '2026-05-02' },
  { month: 5, month_label: 'May', status: 'open', entry_count: 12, net_movement: 38400.00 },
  { month: 6, month_label: 'June', status: 'future', entry_count: 0, net_movement: 0 },
  { month: 7, month_label: 'July', status: 'future', entry_count: 0, net_movement: 0 },
  { month: 8, month_label: 'August', status: 'future', entry_count: 0, net_movement: 0 },
  { month: 9, month_label: 'September', status: 'future', entry_count: 0, net_movement: 0 },
  { month: 10, month_label: 'October', status: 'future', entry_count: 0, net_movement: 0 },
  { month: 11, month_label: 'November', status: 'future', entry_count: 0, net_movement: 0 },
  { month: 12, month_label: 'December', status: 'future', entry_count: 0, net_movement: 0 },
])

const confirmModal = ref({
  show: false,
  title: '',
  message: '',
  targetStatus: '' as PeriodStatus,
  periodMonth: 0,
})

function updateStatus(period: Period, targetStatus: PeriodStatus) {
  const labels: Record<PeriodStatus, string> = {
    open: 'Reopen',
    soft_closed: 'Soft Close',
    hard_closed: 'Hard Close',
    future: '',
  }
  const warnings: Record<PeriodStatus, string> = {
    open: `Period ${period.month_label} will be reopened for posting.`,
    soft_closed: `Period ${period.month_label} will be soft-closed. Adjustments by authorized users will still be permitted.`,
    hard_closed: `Period ${period.month_label} will be permanently hard-closed. No further postings will be allowed.`,
    future: '',
  }
  confirmModal.value = {
    show: true,
    title: `${labels[targetStatus]} – ${period.month_label} ${selectedYear.value}`,
    message: warnings[targetStatus],
    targetStatus,
    periodMonth: period.month,
  }
}

function confirmAction() {
  const period = periods.value.find(p => p.month === confirmModal.value.periodMonth)
  if (period) {
    period.status = confirmModal.value.targetStatus
    if (confirmModal.value.targetStatus !== 'open') {
      period.closed_by = 'current_user'
      period.closed_at = new Date().toISOString().split('T')[0]
    } else {
      delete period.closed_by
      delete period.closed_at
    }
  }
  confirmModal.value.show = false
}

function statusLabel(status: PeriodStatus) {
  const map: Record<PeriodStatus, string> = {
    open: 'Open',
    soft_closed: 'Soft Closed',
    hard_closed: 'Hard Closed',
    future: 'Future',
  }
  return map[status]
}

function statusBadgeClass(status: PeriodStatus) {
  const map: Record<PeriodStatus, string> = {
    open: 'bg-green-100 text-green-700',
    soft_closed: 'bg-yellow-100 text-yellow-700',
    hard_closed: 'bg-red-100 text-red-700',
    future: 'bg-gray-100 text-gray-500',
  }
  return map[status]
}

function borderColorClass(status: PeriodStatus) {
  const map: Record<PeriodStatus, string> = {
    open: 'border-green-200',
    soft_closed: 'border-yellow-200',
    hard_closed: 'border-red-200',
    future: 'border-gray-200',
  }
  return map[status]
}

function headerBgClass(status: PeriodStatus) {
  const map: Record<PeriodStatus, string> = {
    open: 'bg-green-50',
    soft_closed: 'bg-yellow-50',
    hard_closed: 'bg-red-50',
    future: 'bg-gray-50',
  }
  return map[status]
}

function formatCurrency(amount: number) {
  return '$' + new Intl.NumberFormat('en-US', { minimumFractionDigits: 2 }).format(amount)
}
</script>

<i18n lang="json">
{
  "en": {
    "pp": {
      "title": "Posting Periods",
      "subtitle": "Manage accounting period open/close status",
      "status_open": "Open",
      "status_soft_closed": "Soft Closed",
      "status_hard_closed": "Hard Closed",
      "status_future": "Future",
      "entries": "Journal Entries",
      "net_movement": "Net Movement",
      "closed_by": "Closed By",
      "closed_at": "Closed At",
      "action_soft_close": "Soft Close",
      "action_hard_close": "Hard Close",
      "action_reopen": "Reopen",
      "hard_closed_locked": "Locked",
      "not_yet_open": "Not yet open"
    },
    "common": { "cancel": "Cancel", "confirm": "Confirm" }
  },
  "km": {
    "pp": {
      "title": "រយៈពេលបញ្ចូល",
      "subtitle": "គ្រប់គ្រងស្ថានភាពបើក/បិទរយៈពេលគណនេយ្យ",
      "status_open": "បើក",
      "status_soft_closed": "បិទទន់",
      "status_hard_closed": "បិទរឹង",
      "status_future": "អនាគត",
      "entries": "ធាតុទិនានុប្បវត្តិ",
      "net_movement": "ចលនាសុទ្ធ",
      "closed_by": "បិទដោយ",
      "closed_at": "បិទនៅ",
      "action_soft_close": "បិទទន់",
      "action_hard_close": "បិទរឹង",
      "action_reopen": "បើកឡើងវិញ",
      "hard_closed_locked": "ចាក់សោ",
      "not_yet_open": "មិនទាន់បើក"
    },
    "common": { "cancel": "បោះបង់", "confirm": "បញ្ជាក់" }
  }
}
</i18n>
