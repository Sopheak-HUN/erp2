<script setup lang="ts">
definePageMeta({ middleware: 'auth' })
const { t } = useI18n()

const stats = ref([
  { label: t('dashboard.revenue'), value: '$12,450', change: '+12%', icon: 'pi pi-dollar', color: 'blue' },
  { label: t('dashboard.expenses'), value: '$8,230', change: '-3%', icon: 'pi pi-credit-card', color: 'red' },
  { label: t('dashboard.invoices_due'), value: '7', change: '+2', icon: 'pi pi-file', color: 'orange' },
  { label: t('dashboard.employees'), value: '24', change: '+1', icon: 'pi pi-users', color: 'green' },
])

const recentInvoices = ref([
  { number: 'INV-2026-000042', customer: 'ABC Trading Co.', amount: '$2,500.00', status: 'paid', date: '2026-05-15' },
  { number: 'INV-2026-000041', customer: 'Phnom Penh Hotel', amount: '$1,800.00', status: 'issued', date: '2026-05-14' },
  { number: 'INV-2026-000040', customer: 'CamTech Solutions', amount: '$3,200.00', status: 'issued', date: '2026-05-12' },
  { number: 'INV-2026-000039', customer: 'Green Farm Co.', amount: '$950.00', status: 'draft', date: '2026-05-10' },
  { number: 'INV-2026-000038', customer: 'Mekong Logistics', amount: '$4,100.00', status: 'paid', date: '2026-05-08' },
])

const taxDeadlines = ref([
  { name: 'Monthly VAT Return', due: '2026-06-20' },
  { name: 'WHT Declaration', due: '2026-06-20' },
  { name: 'Tax on Salary', due: '2026-06-20' },
  { name: 'NSSF Contribution', due: '2026-06-20' },
  { name: 'Prepayment of CIT (1%)', due: '2026-06-20' },
])

const statusColor = (status: string) => {
  switch (status) {
    case 'paid': return 'bg-green-100 text-green-700'
    case 'issued': return 'bg-blue-100 text-blue-700'
    case 'draft': return 'bg-gray-100 text-gray-700'
    default: return 'bg-gray-100 text-gray-700'
  }
}
</script>

<template>
  <div class="space-y-6">
    <div class="flex items-center justify-between">
      <div>
        <h1 class="text-2xl font-bold text-gray-900">{{ t('nav.dashboard') }}</h1>
        <p class="text-sm text-gray-500 mt-1">{{ t('dashboard.overview') }}</p>
      </div>
      <div class="text-sm text-gray-500">NBC Rate: 1 USD = 4,100 KHR</div>
    </div>

    <!-- Stats -->
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-4">
      <div v-for="stat in stats" :key="stat.label" class="bg-white rounded-xl border border-gray-200 p-5">
        <div class="flex items-center justify-between mb-3">
          <span class="text-sm text-gray-500">{{ stat.label }}</span>
          <div class="w-9 h-9 rounded-lg flex items-center justify-center"
            :class="{ 'bg-blue-100': stat.color === 'blue', 'bg-red-100': stat.color === 'red', 'bg-orange-100': stat.color === 'orange', 'bg-green-100': stat.color === 'green' }">
            <i :class="stat.icon" class="text-sm"
              :style="{ color: stat.color === 'blue' ? '#2563eb' : stat.color === 'red' ? '#dc2626' : stat.color === 'orange' ? '#ea580c' : '#16a34a' }"></i>
          </div>
        </div>
        <p class="text-2xl font-bold text-gray-900">{{ stat.value }}</p>
        <p class="text-xs mt-1" :class="stat.change.startsWith('+') ? 'text-green-600' : 'text-red-600'">
          {{ stat.change }} from last month
        </p>
      </div>
    </div>

    <!-- Content grid -->
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
      <!-- Recent Invoices -->
      <div class="lg:col-span-2 bg-white rounded-xl border border-gray-200">
        <div class="p-5 border-b border-gray-100 flex items-center justify-between">
          <h3 class="font-semibold text-gray-900">{{ t('dashboard.recent_invoices') }}</h3>
          <NuxtLink to="/tax/invoices" class="text-sm text-blue-600 hover:underline">View all</NuxtLink>
        </div>
        <table class="w-full">
          <thead>
            <tr class="border-b border-gray-100">
              <th class="px-5 py-3 text-left text-xs font-medium text-gray-500 uppercase">Invoice #</th>
              <th class="px-5 py-3 text-left text-xs font-medium text-gray-500 uppercase">Customer</th>
              <th class="px-5 py-3 text-right text-xs font-medium text-gray-500 uppercase">Amount</th>
              <th class="px-5 py-3 text-center text-xs font-medium text-gray-500 uppercase">Status</th>
            </tr>
          </thead>
          <tbody>
            <tr v-for="inv in recentInvoices" :key="inv.number" class="border-b border-gray-50 hover:bg-gray-50">
              <td class="px-5 py-3 text-sm font-mono text-gray-900">{{ inv.number }}</td>
              <td class="px-5 py-3 text-sm text-gray-700">{{ inv.customer }}</td>
              <td class="px-5 py-3 text-sm text-right font-medium text-gray-900">{{ inv.amount }}</td>
              <td class="px-5 py-3 text-center">
                <span class="px-2 py-0.5 rounded-full text-xs font-medium" :class="statusColor(inv.status)">{{ inv.status }}</span>
              </td>
            </tr>
          </tbody>
        </table>
      </div>

      <!-- Tax Deadlines -->
      <div class="bg-white rounded-xl border border-gray-200">
        <div class="p-5 border-b border-gray-100">
          <h3 class="font-semibold text-gray-900">{{ t('dashboard.tax_deadlines') }}</h3>
          <p class="text-xs text-gray-500 mt-1">Due by 20th (hard) / 25th (e-Filing)</p>
        </div>
        <div class="p-3 space-y-2">
          <div v-for="d in taxDeadlines" :key="d.name" class="flex items-center justify-between p-3 rounded-lg bg-orange-50 border border-orange-100">
            <div>
              <p class="text-sm font-medium text-gray-900">{{ d.name }}</p>
              <p class="text-xs text-gray-500">Due: {{ d.due }}</p>
            </div>
            <i class="pi pi-clock text-orange-500"></i>
          </div>
        </div>
      </div>
    </div>
  </div>
</template>
