<script setup lang="ts">
const { t, locale } = useI18n();
const localePath = useLocalePath();
const route = useRoute();

const menuItems = computed(() => [
  {
    label: t("nav.dashboard"),
    icon: "pi pi-home",
    to: "/",
  },
  {
    label: t("nav.accounting"),
    icon: "pi pi-book",
    items: [
      {
        label: t("accounting.chart_of_accounts"),
        icon: "pi pi-sitemap",
        to: "/accounting/chart-of-accounts",
      },
      {
        label: t("accounting.journal_entries"),
        icon: "pi pi-file-edit",
        to: "/accounting/journal-entries",
      },
      {
        label: t("accounting.posting_periods"),
        icon: "pi pi-calendar",
        to: "/accounting/posting-periods",
      },
    ],
  },
  {
    label: t("nav.tax"),
    icon: "pi pi-percentage",
    items: [
      { label: t("tax.invoices"), icon: "pi pi-file", to: "/tax/invoices" },
      { label: t("tax.tax_rates"), icon: "pi pi-sliders-h", to: "/tax/rates" },
      {
        label: t("tax.gdt_export"),
        icon: "pi pi-download",
        to: "/tax/gdt-export",
      },
    ],
  },
  {
    label: t("nav.payroll"),
    icon: "pi pi-users",
    items: [
      {
        label: t("payroll.employees"),
        icon: "pi pi-user",
        to: "/payroll/employees",
      },
      {
        label: t("payroll.payslips"),
        icon: "pi pi-wallet",
        to: "/payroll/payslips",
      },
      {
        label: t("payroll.nssf_export"),
        icon: "pi pi-download",
        to: "/payroll/nssf-export",
      },
    ],
  },
  {
    label: t("nav.inventory"),
    icon: "pi pi-box",
    items: [
      {
        label: t("inventory.products"),
        icon: "pi pi-tag",
        to: "/inventory/products",
      },
      {
        label: t("inventory.stock"),
        icon: "pi pi-warehouse",
        to: "/inventory/stock",
      },
    ],
  },
  {
    label: t("nav.sales"),
    icon: "pi pi-shopping-cart",
    to: "/sales",
  },
  {
    label: t("nav.procurement"),
    icon: "pi pi-truck",
    to: "/procurement",
  },
]);

const isActive = (item: any) => {
  if (item.to) return route.path === localePath(item.to);
  if (item.items)
    return item.items.some((sub: any) => route.path === localePath(sub.to));
  return false;
};
</script>

<template>
  <aside
    class="w-64 bg-white border-r border-gray-200 h-screen overflow-y-auto flex flex-col"
  >
    <!-- Logo -->
    <div class="p-4 border-b border-gray-200">
      <div class="flex items-center gap-3">
        <div
          class="w-8 h-8 bg-blue-600 rounded-lg flex items-center justify-center"
        >
          <span class="text-white font-bold text-sm">KH</span>
        </div>
        <div>
          <h1 class="font-bold text-gray-900 text-sm">Cambodia ERP</h1>
          <p class="text-xs text-gray-500">
            {{ locale === "km" ? "ប្រព័ន្ធ ERP" : "Multi-Tenant SaaS" }}
          </p>
        </div>
      </div>
    </div>

    <!-- Navigation -->
    <nav class="flex-1 p-3 space-y-1">
      <template v-for="item in menuItems" :key="item.label">
        <!-- Simple link -->
        <NuxtLink
          v-if="item.to && !item.items"
          :to="localePath(item.to)"
          class="flex items-center gap-3 px-3 py-2 rounded-lg text-sm transition-colors"
          :class="
            isActive(item)
              ? 'bg-blue-50 !text-blue-700 font-semibold'
              : '!text-slate-900 hover:bg-gray-100'
          "
        >
          <i :class="item.icon" class="text-base"></i>
          <span>{{ item.label }}</span>
        </NuxtLink>

        <!-- Expandable group -->
        <div v-else-if="item.items">
          <div
            class="flex items-center gap-3 px-3 py-2 rounded-lg text-sm !text-slate-900 font-semibold"
            :class="isActive(item) ? '!text-blue-700' : ''"
          >
            <i :class="item.icon" class="text-base"></i>
            <span>{{ item.label }}</span>
          </div>
          <div class="ml-6 space-y-0.5">
            <NuxtLink
              v-for="sub in item.items"
              :key="sub.to"
              :to="localePath(sub.to)"
              class="flex items-center gap-2 px-3 py-1.5 rounded-md text-xs transition-colors"
              :class="
                route.path === sub.to
                  ? 'bg-blue-50 !text-blue-700 font-semibold'
                  : '!text-slate-700 hover:bg-gray-50'
              "
            >
              <i :class="sub.icon" class="text-xs"></i>
              <span>{{ sub.label }}</span>
            </NuxtLink>
          </div>
        </div>
      </template>
    </nav>

    <!-- Settings link -->
    <div class="p-3 border-t border-gray-200">
      <NuxtLink
        :to="localePath('/settings')"
        class="flex items-center gap-3 px-3 py-2 rounded-lg text-sm text-slate-800 font-semibold hover:bg-gray-100 transition-colors"
      >
        <i class="pi pi-cog text-base"></i>
        <span>{{ t("nav.settings") }}</span>
      </NuxtLink>
    </div>
  </aside>
</template>
