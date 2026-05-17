<template>
  <div class="min-h-screen bg-gray-50 p-6">
    <!-- Header -->
    <div class="mb-6 flex items-center justify-between">
      <div>
        <h1 class="text-2xl font-bold text-gray-900">{{ t('prod.title') }}</h1>
        <p class="mt-1 text-sm text-gray-500">{{ t('prod.subtitle') }}</p>
      </div>
      <div class="flex items-center gap-3">
        <div class="flex items-center rounded-lg border border-gray-300 bg-white overflow-hidden shadow-sm">
          <button @click="viewMode = 'grid'" :class="['px-3 py-2 text-sm transition-colors', viewMode === 'grid' ? 'bg-blue-600 text-white' : 'text-gray-500 hover:bg-gray-50']">
            <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2H6a2 2 0 01-2-2V6zM14 6a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2V6zM4 16a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2H6a2 2 0 01-2-2v-2zM14 16a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2v-2z" /></svg>
          </button>
          <button @click="viewMode = 'list'" :class="['px-3 py-2 text-sm transition-colors', viewMode === 'list' ? 'bg-blue-600 text-white' : 'text-gray-500 hover:bg-gray-50']">
            <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 10h16M4 14h16M4 18h16" /></svg>
          </button>
        </div>
        <button class="inline-flex items-center gap-2 rounded-lg bg-blue-600 px-4 py-2 text-sm font-medium text-white shadow-sm hover:bg-blue-700">
          <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" /></svg>
          {{ t('prod.add_product') }}
        </button>
      </div>
    </div>

    <!-- Stats -->
    <div class="mb-6 grid grid-cols-2 gap-4 sm:grid-cols-4">
      <div class="rounded-xl border border-gray-200 bg-white p-4 shadow-sm">
        <p class="text-xs font-medium text-gray-500 uppercase tracking-wide">{{ t('prod.stat_total') }}</p>
        <p class="mt-1 text-xl font-bold text-gray-900">{{ products.length }}</p>
      </div>
      <div class="rounded-xl border border-gray-200 bg-white p-4 shadow-sm">
        <p class="text-xs font-medium text-gray-500 uppercase tracking-wide">{{ t('prod.stat_active') }}</p>
        <p class="mt-1 text-xl font-bold text-green-600">{{ products.filter(p => p.is_active).length }}</p>
      </div>
      <div class="rounded-xl border border-amber-100 bg-amber-50 p-4 shadow-sm">
        <p class="text-xs font-medium text-amber-600 uppercase tracking-wide">{{ t('prod.stat_low_stock') }}</p>
        <p class="mt-1 text-xl font-bold text-amber-700">{{ products.filter(p => p.stock_qty <= p.reorder_point && p.is_active).length }}</p>
      </div>
      <div class="rounded-xl border border-gray-200 bg-white p-4 shadow-sm">
        <p class="text-xs font-medium text-gray-500 uppercase tracking-wide">{{ t('prod.stat_out_of_stock') }}</p>
        <p class="mt-1 text-xl font-bold text-red-600">{{ products.filter(p => p.stock_qty === 0 && p.is_active).length }}</p>
      </div>
    </div>

    <!-- Search & Filter Bar -->
    <div class="mb-5 rounded-xl border border-gray-200 bg-white p-4 shadow-sm">
      <div class="flex flex-col gap-3 sm:flex-row sm:items-center">
        <div class="relative flex-1">
          <svg class="absolute left-3 top-1/2 h-4 w-4 -translate-y-1/2 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" /></svg>
          <input v-model="searchQuery" type="text" :placeholder="t('prod.search_placeholder')" class="w-full rounded-lg border border-gray-300 py-2 pl-10 pr-4 text-sm focus:border-blue-500 focus:outline-none focus:ring-1 focus:ring-blue-500" />
        </div>
        <select v-model="filterCategory" class="rounded-lg border border-gray-300 px-3 py-2 text-sm focus:border-blue-500 focus:outline-none">
          <option value="">{{ t('prod.all_categories') }}</option>
          <option v-for="cat in categories" :key="cat" :value="cat">{{ cat }}</option>
        </select>
        <label class="flex items-center gap-2 rounded-lg border border-gray-300 px-3 py-2 text-sm cursor-pointer hover:bg-gray-50">
          <input v-model="showLowStockOnly" type="checkbox" class="rounded" />
          {{ t('prod.low_stock_only') }}
        </label>
      </div>
    </div>

    <!-- Grid View -->
    <div v-if="viewMode === 'grid'" class="grid grid-cols-1 gap-4 sm:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4">
      <div
        v-for="product in filteredProducts"
        :key="product.id"
        :class="[
          'rounded-xl border bg-white shadow-sm hover:shadow-md transition-shadow overflow-hidden',
          product.stock_qty === 0 ? 'border-red-200' : product.stock_qty <= product.reorder_point ? 'border-amber-200' : 'border-gray-200',
        ]"
      >
        <!-- Product Image Placeholder -->
        <div class="relative h-36 bg-gradient-to-br from-gray-100 to-gray-200 flex items-center justify-center">
          <div class="flex h-16 w-16 items-center justify-center rounded-2xl bg-white shadow-sm">
            <svg class="h-8 w-8 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4" /></svg>
          </div>
          <!-- Stock Warning Badge -->
          <div v-if="product.stock_qty === 0" class="absolute top-2 right-2 rounded-full bg-red-500 px-2 py-0.5 text-xs font-medium text-white">
            {{ t('prod.out_of_stock') }}
          </div>
          <div v-else-if="product.stock_qty <= product.reorder_point" class="absolute top-2 right-2 rounded-full bg-amber-500 px-2 py-0.5 text-xs font-medium text-white">
            {{ t('prod.low_stock') }}
          </div>
          <!-- VAT Badge -->
          <div v-if="product.vat_applicable" class="absolute top-2 left-2 rounded-full bg-blue-100 border border-blue-200 px-2 py-0.5 text-xs font-medium text-blue-700">
            VAT 10%
          </div>
        </div>

        <div class="p-4">
          <!-- SKU + Category -->
          <div class="mb-1 flex items-center justify-between">
            <span class="font-mono text-xs text-gray-400">{{ product.sku }}</span>
            <span class="rounded-full bg-gray-100 px-2 py-0.5 text-xs text-gray-500">{{ product.category }}</span>
          </div>

          <!-- Name -->
          <h3 class="font-semibold text-gray-900 leading-tight">{{ product.name_en }}</h3>
          <p class="text-xs text-gray-400 font-khmer mt-0.5">{{ product.name_km }}</p>

          <!-- Price / Cost -->
          <div class="mt-3 flex items-center justify-between">
            <div>
              <p class="text-xs text-gray-400">{{ t('prod.price') }}</p>
              <p class="font-bold text-gray-900">${{ formatNum(product.price) }}</p>
            </div>
            <div class="text-right">
              <p class="text-xs text-gray-400">{{ t('prod.cost') }}</p>
              <p class="font-medium text-gray-600">${{ formatNum(product.cost) }}</p>
            </div>
          </div>

          <!-- Stock Level Bar -->
          <div class="mt-3">
            <div class="flex items-center justify-between mb-1">
              <span class="text-xs text-gray-500">{{ t('prod.stock') }}</span>
              <span :class="[
                'text-xs font-medium',
                product.stock_qty === 0 ? 'text-red-600' : product.stock_qty <= product.reorder_point ? 'text-amber-600' : 'text-green-600',
              ]">{{ product.stock_qty }} {{ product.unit }}</span>
            </div>
            <div class="h-1.5 rounded-full bg-gray-100 overflow-hidden">
              <div
                :class="[
                  'h-full rounded-full transition-all',
                  product.stock_qty === 0 ? 'bg-red-500' : product.stock_qty <= product.reorder_point ? 'bg-amber-400' : 'bg-green-500',
                ]"
                :style="{ width: `${Math.min(100, (product.stock_qty / (product.max_stock || 100)) * 100)}%` }"
              ></div>
            </div>
            <p class="mt-0.5 text-xs text-gray-400">{{ t('prod.reorder_at') }}: {{ product.reorder_point }} {{ product.unit }}</p>
          </div>

          <!-- Actions -->
          <div class="mt-3 flex gap-2">
            <button class="flex-1 rounded-lg border border-gray-200 py-1.5 text-xs font-medium text-gray-600 hover:bg-gray-50 transition-colors">
              {{ t('prod.edit') }}
            </button>
            <button class="flex-1 rounded-lg bg-blue-50 border border-blue-200 py-1.5 text-xs font-medium text-blue-700 hover:bg-blue-100 transition-colors">
              {{ t('prod.adjust_stock') }}
            </button>
          </div>
        </div>
      </div>
    </div>

    <!-- List View -->
    <div v-if="viewMode === 'list'" class="rounded-xl border border-gray-200 bg-white shadow-sm overflow-x-auto">
      <table class="w-full text-sm min-w-[900px]">
        <thead class="bg-gray-50 border-b border-gray-200">
          <tr>
            <th class="px-4 py-3 text-left font-semibold text-gray-600 w-28">SKU</th>
            <th class="px-4 py-3 text-left font-semibold text-gray-600">{{ t('prod.col_name') }}</th>
            <th class="px-4 py-3 text-left font-semibold text-gray-600 w-28">{{ t('prod.col_category') }}</th>
            <th class="px-4 py-3 text-right font-semibold text-gray-600 w-24">{{ t('prod.col_price') }}</th>
            <th class="px-4 py-3 text-right font-semibold text-gray-600 w-24">{{ t('prod.col_cost') }}</th>
            <th class="px-4 py-3 text-right font-semibold text-gray-600 w-24">{{ t('prod.col_stock') }}</th>
            <th class="px-4 py-3 text-center font-semibold text-gray-600 w-20">VAT</th>
            <th class="px-4 py-3 text-center font-semibold text-gray-600 w-24">{{ t('prod.col_status') }}</th>
          </tr>
        </thead>
        <tbody class="divide-y divide-gray-100">
          <tr v-for="product in filteredProducts" :key="product.id" class="hover:bg-gray-50">
            <td class="px-4 py-3 font-mono text-xs text-gray-500">{{ product.sku }}</td>
            <td class="px-4 py-3">
              <div class="font-medium text-gray-900">{{ product.name_en }}</div>
              <div class="text-xs text-gray-400 font-khmer">{{ product.name_km }}</div>
            </td>
            <td class="px-4 py-3 text-gray-600">{{ product.category }}</td>
            <td class="px-4 py-3 text-right font-mono text-gray-900">${{ formatNum(product.price) }}</td>
            <td class="px-4 py-3 text-right font-mono text-gray-600">${{ formatNum(product.cost) }}</td>
            <td class="px-4 py-3 text-right">
              <span :class="[
                'font-medium',
                product.stock_qty === 0 ? 'text-red-600' : product.stock_qty <= product.reorder_point ? 'text-amber-600' : 'text-green-600',
              ]">{{ product.stock_qty }} {{ product.unit }}</span>
            </td>
            <td class="px-4 py-3 text-center">
              <span v-if="product.vat_applicable" class="inline-flex items-center rounded-full bg-blue-100 px-2 py-0.5 text-xs font-medium text-blue-700">10%</span>
              <span v-else class="text-gray-300 text-xs">—</span>
            </td>
            <td class="px-4 py-3 text-center">
              <span :class="product.stock_qty === 0 ? 'bg-red-100 text-red-700' : product.stock_qty <= product.reorder_point ? 'bg-amber-100 text-amber-700' : 'bg-green-100 text-green-700'" class="inline-flex items-center rounded-full px-2 py-0.5 text-xs font-medium">
                {{ product.stock_qty === 0 ? t('prod.out_of_stock') : product.stock_qty <= product.reorder_point ? t('prod.low_stock') : t('prod.in_stock') }}
              </span>
            </td>
          </tr>
        </tbody>
      </table>
    </div>

    <div v-if="filteredProducts.length === 0" class="mt-4 py-12 text-center text-gray-400">{{ t('prod.no_results') }}</div>
  </div>
</template>

<script setup lang="ts">
definePageMeta({ middleware: 'auth' })

const { t } = useI18n()

const searchQuery = ref('')
const filterCategory = ref('')
const showLowStockOnly = ref(false)
const viewMode = ref<'grid' | 'list'>('grid')

interface Product {
  id: number
  sku: string
  name_en: string
  name_km: string
  category: string
  price: number
  cost: number
  stock_qty: number
  unit: string
  reorder_point: number
  max_stock: number
  vat_applicable: boolean
  is_active: boolean
}

const products = ref<Product[]>([
  { id: 1, sku: 'PRD-0001', name_en: 'Office Chair (Ergonomic)', name_km: 'កៅអីការិយាល័យ', category: 'Furniture', price: 189.00, cost: 120.00, stock_qty: 24, unit: 'pcs', reorder_point: 5, max_stock: 50, vat_applicable: true, is_active: true },
  { id: 2, sku: 'PRD-0002', name_en: 'A4 Paper (500 sheets)', name_km: 'ក្រដាស A4 (500 ស្លឹក)', category: 'Stationery', price: 8.50, cost: 5.00, stock_qty: 3, unit: 'ream', reorder_point: 10, max_stock: 100, vat_applicable: true, is_active: true },
  { id: 3, sku: 'PRD-0003', name_en: 'HP LaserJet Toner 85A', name_km: 'ថ្នាំ HP LaserJet 85A', category: 'Electronics', price: 45.00, cost: 30.00, stock_qty: 0, unit: 'pcs', reorder_point: 3, max_stock: 20, vat_applicable: true, is_active: true },
  { id: 4, sku: 'PRD-0004', name_en: 'Bottled Water 600ml (Case)', name_km: 'ទឹក 600ml (ប្រអប់)', category: 'Beverages', price: 3.50, cost: 2.20, stock_qty: 120, unit: 'case', reorder_point: 20, max_stock: 200, vat_applicable: false, is_active: true },
  { id: 5, sku: 'PRD-0005', name_en: 'USB-C Hub 7-in-1', name_km: 'USB-C Hub 7-in-1', category: 'Electronics', price: 38.00, cost: 22.00, stock_qty: 8, unit: 'pcs', reorder_point: 5, max_stock: 30, vat_applicable: true, is_active: true },
  { id: 6, sku: 'PRD-0006', name_en: 'Standing Desk (Electric)', name_km: 'តុឈរ (អគ្គិសនី)', category: 'Furniture', price: 450.00, cost: 280.00, stock_qty: 2, unit: 'pcs', reorder_point: 3, max_stock: 15, vat_applicable: true, is_active: true },
  { id: 7, sku: 'PRD-0007', name_en: 'Whiteboard Marker Set', name_km: 'ប៊ិចសរសេរក្ដារសេសសំណ', category: 'Stationery', price: 6.00, cost: 3.50, stock_qty: 45, unit: 'set', reorder_point: 10, max_stock: 80, vat_applicable: true, is_active: true },
  { id: 8, sku: 'PRD-0008', name_en: 'Instant Coffee 200g', name_km: 'កាហ្វេ 200g', category: 'Beverages', price: 5.50, cost: 3.20, stock_qty: 60, unit: 'box', reorder_point: 15, max_stock: 100, vat_applicable: false, is_active: true },
  { id: 9, sku: 'PRD-0009', name_en: 'Desk Lamp LED', name_km: 'ភ្លើងតុ LED', category: 'Electronics', price: 22.00, cost: 14.00, stock_qty: 1, unit: 'pcs', reorder_point: 4, max_stock: 20, vat_applicable: true, is_active: true },
  { id: 10, sku: 'PRD-0010', name_en: 'File Cabinet 4-Drawer', name_km: 'ទូរឯកសារ 4 ថត', category: 'Furniture', price: 280.00, cost: 175.00, stock_qty: 7, unit: 'pcs', reorder_point: 2, max_stock: 10, vat_applicable: true, is_active: true },
  { id: 11, sku: 'PRD-0011', name_en: 'Ballpoint Pen Box', name_km: 'ប្រអប់ប៊ិច', category: 'Stationery', price: 4.00, cost: 2.00, stock_qty: 0, unit: 'box', reorder_point: 5, max_stock: 50, vat_applicable: true, is_active: true },
  { id: 12, sku: 'PRD-0012', name_en: 'Wireless Mouse', name_km: 'ម៉ៅស៍មានខ្សែ', category: 'Electronics', price: 28.00, cost: 18.00, stock_qty: 15, unit: 'pcs', reorder_point: 5, max_stock: 40, vat_applicable: true, is_active: true },
])

const categories = computed(() => [...new Set(products.value.map(p => p.category))])

const filteredProducts = computed(() => {
  return products.value.filter(p => {
    const matchSearch = searchQuery.value === '' ||
      p.sku.toLowerCase().includes(searchQuery.value.toLowerCase()) ||
      p.name_en.toLowerCase().includes(searchQuery.value.toLowerCase()) ||
      p.name_km.includes(searchQuery.value)
    const matchCat = filterCategory.value === '' || p.category === filterCategory.value
    const matchLowStock = !showLowStockOnly.value || p.stock_qty <= p.reorder_point
    return matchSearch && matchCat && matchLowStock && p.is_active
  })
})

function formatNum(n: number) {
  return new Intl.NumberFormat('en-US', { minimumFractionDigits: 2, maximumFractionDigits: 2 }).format(n)
}
</script>

<i18n lang="json">
{
  "en": {
    "prod": {
      "title": "Products",
      "subtitle": "Inventory product catalog",
      "add_product": "Add Product",
      "search_placeholder": "Search by SKU, name EN or KM…",
      "all_categories": "All Categories",
      "low_stock_only": "Low stock only",
      "stat_total": "Total Products",
      "stat_active": "Active",
      "stat_low_stock": "Low Stock",
      "stat_out_of_stock": "Out of Stock",
      "price": "Price",
      "cost": "Cost",
      "stock": "Stock",
      "reorder_at": "Reorder at",
      "edit": "Edit",
      "adjust_stock": "Adjust",
      "out_of_stock": "Out of Stock",
      "low_stock": "Low Stock",
      "in_stock": "In Stock",
      "col_name": "Product",
      "col_category": "Category",
      "col_price": "Price",
      "col_cost": "Cost",
      "col_stock": "Stock",
      "col_status": "Stock Status",
      "no_results": "No products found."
    }
  },
  "km": {
    "prod": {
      "title": "ផលិតផល",
      "subtitle": "កាតាឡុករបស់ការ",
      "add_product": "បន្ថែមផលិតផល",
      "search_placeholder": "ស្វែងរក SKU ឬឈ្មោះ…",
      "all_categories": "ប្រភេទទាំងអស់",
      "low_stock_only": "ស្ត็រទាប",
      "stat_total": "ផលិតផលសរុប",
      "stat_active": "សកម្ម",
      "stat_low_stock": "ស្ត็រទាប",
      "stat_out_of_stock": "អស់ស្ត็រ",
      "price": "តម្លៃ",
      "cost": "តម្លៃដើម",
      "stock": "ស្ត็រ",
      "reorder_at": "បញ្ជាទិញនៅ",
      "edit": "កែ",
      "adjust_stock": "តម្រូវ",
      "out_of_stock": "អស់ស្ត់",
      "low_stock": "ស្ត់ទាប",
      "in_stock": "មានស្ត់",
      "col_name": "ផលិតផល",
      "col_category": "ប្រភេទ",
      "col_price": "តម្លៃ",
      "col_cost": "តម្លៃដើម",
      "col_stock": "ស្ត់",
      "col_status": "ស្ថានភាពស្ត់",
      "no_results": "មិនមានផលិតផល"
    }
  }
}
</i18n>
