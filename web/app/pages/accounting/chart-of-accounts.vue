<template>
  <div class="min-h-screen bg-gray-50 p-6">
    <!-- Header -->
    <div class="mb-6 flex items-center justify-between">
      <div>
        <h1 class="text-2xl font-bold text-gray-900">{{ t("coa.title") }}</h1>
        <p class="mt-1 text-sm text-gray-700">{{ t("coa.subtitle") }}</p>
      </div>
      <button
        @click="showAddModal = true"
        class="inline-flex items-center gap-2 rounded-lg bg-blue-600 px-4 py-2 text-sm font-medium text-white shadow-sm hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-blue-500"
      >
        <svg
          class="h-4 w-4"
          fill="none"
          stroke="currentColor"
          viewBox="0 0 24 24"
        >
          <path
            stroke-linecap="round"
            stroke-linejoin="round"
            stroke-width="2"
            d="M12 4v16m8-8H4"
          />
        </svg>
        {{ t("coa.add_account") }}
      </button>
    </div>

    <!-- Search & Filter Bar -->
    <div class="mb-4 rounded-xl border border-gray-200 bg-white p-4 shadow-sm">
      <div class="flex flex-col gap-3 sm:flex-row sm:items-center">
        <div class="relative flex-1">
          <svg
            class="absolute left-3 top-1/2 h-4 w-4 -translate-y-1/2 text-gray-400"
            fill="none"
            stroke="currentColor"
            viewBox="0 0 24 24"
          >
            <path
              stroke-linecap="round"
              stroke-linejoin="round"
              stroke-width="2"
              d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"
            />
          </svg>
          <input
            v-model="searchQuery"
            type="text"
            :placeholder="t('coa.search_placeholder')"
            class="w-full rounded-lg border border-gray-300 py-2 pl-10 pr-4 text-sm focus:border-blue-500 focus:outline-none focus:ring-1 focus:ring-blue-500"
          />
        </div>
        <div class="flex gap-2">
          <select
            v-model="filterType"
            class="rounded-lg border border-gray-300 px-3 py-2 text-sm focus:border-blue-500 focus:outline-none focus:ring-1 focus:ring-blue-500"
          >
            <option value="">{{ t("coa.all_types") }}</option>
            <option value="asset">Asset</option>
            <option value="liability">Liability</option>
            <option value="equity">Equity</option>
            <option value="revenue">Revenue</option>
            <option value="expense">Expense</option>
          </select>
          <label
            class="flex items-center gap-2 rounded-lg border border-gray-300 px-3 py-2 text-sm cursor-pointer hover:bg-gray-50"
          >
            <input v-model="showPostableOnly" type="checkbox" class="rounded" />
            {{ t("coa.postable_only") }}
          </label>
        </div>
      </div>
    </div>

    <!-- COA Tree Table -->
    <div
      class="rounded-xl border border-gray-200 bg-white shadow-sm overflow-hidden"
    >
      <table class="w-full text-sm">
        <thead class="bg-gray-50 border-b border-gray-200">
          <tr>
            <th class="px-4 py-3 text-left font-bold text-gray-800 w-32">
              {{ t("coa.col_code") }}
            </th>
            <th class="px-4 py-3 text-left font-bold text-gray-800">
              {{ t("coa.col_name_en") }}
            </th>
            <th class="px-4 py-3 text-left font-bold text-gray-800">
              {{ t("coa.col_name_km") }}
            </th>
            <th class="px-4 py-3 text-left font-bold text-gray-800 w-28">
              {{ t("coa.col_type") }}
            </th>
            <th class="px-4 py-3 text-center font-bold text-gray-800 w-24">
              {{ t("coa.col_postable") }}
            </th>
            <th class="px-4 py-3 text-center font-bold text-gray-800 w-20">
              {{ t("coa.col_actions") }}
            </th>
          </tr>
        </thead>
        <tbody class="divide-y divide-gray-100">
          <template v-for="account in filteredAccounts" :key="account.code">
            <tr
              :class="[
                'hover:bg-gray-50 transition-colors',
                account.level === 0
                  ? 'bg-gray-50 font-bold text-slate-900'
                  : '',
                account.level === 1
                  ? 'bg-white font-semibold text-slate-800'
                  : '',
                account.level === 2 ? 'bg-white text-slate-700' : '',
              ]"
            >
              <td
                class="px-4 py-2.5 font-mono text-xs text-gray-700 font-medium"
              >
                {{ account.code }}
              </td>
              <td class="px-4 py-2.5">
                <span
                  :style="{ paddingLeft: `${account.level * 20}px` }"
                  class="flex items-center gap-1"
                >
                  <span v-if="account.level === 0" class="text-gray-400 mr-1"
                    >▼</span
                  >
                  <span
                    v-else-if="account.level === 1"
                    class="text-gray-300 mr-1"
                    >▸</span
                  >
                  {{ account.name_en }}
                </span>
              </td>
              <td class="px-4 py-2.5 font-khmer text-slate-900">
                {{ account.name_km }}
              </td>
              <td class="px-4 py-2.5">
                <span
                  :class="typeBadgeClass(account.type)"
                  class="inline-flex items-center rounded-full px-2.5 py-0.5 text-xs font-medium"
                >
                  {{ account.type }}
                </span>
              </td>
              <td class="px-4 py-2.5 text-center">
                <span
                  v-if="account.is_postable"
                  class="inline-flex items-center justify-center w-5 h-5 rounded-full bg-green-100 text-green-600"
                >
                  <svg class="w-3 h-3" fill="currentColor" viewBox="0 0 20 20">
                    <path
                      fill-rule="evenodd"
                      d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z"
                      clip-rule="evenodd"
                    />
                  </svg>
                </span>
                <span v-else class="text-gray-300">—</span>
              </td>
              <td class="px-4 py-2.5 text-center">
                <button
                  class="text-gray-400 hover:text-blue-600 transition-colors"
                >
                  <svg
                    class="w-4 h-4"
                    fill="none"
                    stroke="currentColor"
                    viewBox="0 0 24 24"
                  >
                    <path
                      stroke-linecap="round"
                      stroke-linejoin="round"
                      stroke-width="2"
                      d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"
                    />
                  </svg>
                </button>
              </td>
            </tr>
          </template>
        </tbody>
      </table>
      <div
        v-if="filteredAccounts.length === 0"
        class="py-12 text-center text-gray-400"
      >
        <svg
          class="mx-auto h-10 w-10 mb-2"
          fill="none"
          stroke="currentColor"
          viewBox="0 0 24 24"
        >
          <path
            stroke-linecap="round"
            stroke-linejoin="round"
            stroke-width="1.5"
            d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"
          />
        </svg>
        {{ t("coa.no_results") }}
      </div>
    </div>

    <!-- Summary Stats -->
    <div class="mt-4 grid grid-cols-2 gap-3 sm:grid-cols-5">
      <div
        v-for="stat in typeSummary"
        :key="stat.type"
        class="rounded-xl border border-gray-200 bg-white p-3 text-center shadow-sm"
      >
        <span
          :class="typeBadgeClass(stat.type)"
          class="inline-flex items-center rounded-full px-2.5 py-0.5 text-xs font-medium mb-1"
        >
          {{ stat.type }}
        </span>
        <p class="text-xl font-bold text-gray-900">{{ stat.count }}</p>
        <p class="text-xs text-gray-500">accounts</p>
      </div>
    </div>

    <!-- Add Account Modal -->
    <div
      v-if="showAddModal"
      class="fixed inset-0 z-50 flex items-center justify-center bg-black/50 p-4"
    >
      <div class="w-full max-w-lg rounded-2xl bg-white shadow-2xl">
        <div
          class="flex items-center justify-between border-b border-gray-200 p-5"
        >
          <h2 class="text-lg font-semibold text-gray-900">
            {{ t("coa.modal_title") }}
          </h2>
          <button
            @click="showAddModal = false"
            class="text-gray-400 hover:text-gray-600"
          >
            <svg
              class="h-5 w-5"
              fill="none"
              stroke="currentColor"
              viewBox="0 0 24 24"
            >
              <path
                stroke-linecap="round"
                stroke-linejoin="round"
                stroke-width="2"
                d="M6 18L18 6M6 6l12 12"
              />
            </svg>
          </button>
        </div>
        <form @submit.prevent="saveAccount" class="p-5 space-y-4">
          <div class="grid grid-cols-2 gap-4">
            <div>
              <label class="block text-xs font-medium text-gray-700 mb-1"
                >{{ t("coa.field_code") }} *</label
              >
              <input
                v-model="newAccount.code"
                type="text"
                placeholder="e.g. 1111"
                class="w-full rounded-lg border border-gray-300 px-3 py-2 text-sm focus:border-blue-500 focus:outline-none focus:ring-1 focus:ring-blue-500"
                required
              />
            </div>
            <div>
              <label class="block text-xs font-medium text-gray-700 mb-1"
                >{{ t("coa.field_type") }} *</label
              >
              <select
                v-model="newAccount.type"
                class="w-full rounded-lg border border-gray-300 px-3 py-2 text-sm focus:border-blue-500 focus:outline-none focus:ring-1 focus:ring-blue-500"
                required
              >
                <option value="">Select type</option>
                <option value="asset">Asset</option>
                <option value="liability">Liability</option>
                <option value="equity">Equity</option>
                <option value="revenue">Revenue</option>
                <option value="expense">Expense</option>
              </select>
            </div>
          </div>
          <div>
            <label class="block text-xs font-medium text-gray-700 mb-1"
              >{{ t("coa.field_name_en") }} *</label
            >
            <input
              v-model="newAccount.name_en"
              type="text"
              placeholder="Account name in English"
              class="w-full rounded-lg border border-gray-300 px-3 py-2 text-sm focus:border-blue-500 focus:outline-none focus:ring-1 focus:ring-blue-500"
              required
            />
          </div>
          <div>
            <label class="block text-xs font-medium text-gray-700 mb-1">{{
              t("coa.field_name_km")
            }}</label>
            <input
              v-model="newAccount.name_km"
              type="text"
              placeholder="ឈ្មោះគណនីជាភាសាខ្មែរ"
              class="w-full rounded-lg border border-gray-300 px-3 py-2 text-sm font-khmer focus:border-blue-500 focus:outline-none focus:ring-1 focus:ring-blue-500"
            />
          </div>
          <div class="flex items-center gap-3">
            <label class="flex items-center gap-2 cursor-pointer">
              <input
                v-model="newAccount.is_postable"
                type="checkbox"
                class="rounded text-blue-600"
              />
              <span class="text-sm text-gray-700">{{
                t("coa.field_postable")
              }}</span>
            </label>
          </div>
          <div class="flex justify-end gap-3 pt-2">
            <button
              type="button"
              @click="showAddModal = false"
              class="rounded-lg border border-gray-300 px-4 py-2 text-sm font-medium text-gray-700 hover:bg-gray-50"
            >
              {{ t("common.cancel") }}
            </button>
            <button
              type="submit"
              class="rounded-lg bg-blue-600 px-4 py-2 text-sm font-medium text-white hover:bg-blue-700"
            >
              {{ t("common.save") }}
            </button>
          </div>
        </form>
      </div>
    </div>
  </div>
</template>

<script setup lang="ts">
definePageMeta({ middleware: "auth" });

const { t } = useI18n();

const searchQuery = ref("");
const filterType = ref("");
const showPostableOnly = ref(false);
const showAddModal = ref(false);

const newAccount = ref({
  code: "",
  name_en: "",
  name_km: "",
  type: "",
  is_postable: true,
});

interface Account {
  code: string;
  name_en: string;
  name_km: string;
  type: "asset" | "liability" | "equity" | "revenue" | "expense";
  is_postable: boolean;
  level: number;
}

const accounts = ref<Account[]>([
  // ASSETS
  {
    code: "1000",
    name_en: "Assets",
    name_km: "ទ្រព្យសម្បត្តិ",
    type: "asset",
    is_postable: false,
    level: 0,
  },
  {
    code: "1100",
    name_en: "Current Assets",
    name_km: "ទ្រព្យចរន្ត",
    type: "asset",
    is_postable: false,
    level: 1,
  },
  {
    code: "1110",
    name_en: "Cash & Cash Equivalents",
    name_km: "សាច់ប្រាក់ និងសមមូលសាច់ប្រាក់",
    type: "asset",
    is_postable: false,
    level: 2,
  },
  {
    code: "1111",
    name_en: "Cash on Hand (USD)",
    name_km: "សាច់ប្រាក់ (ដុល្លារ)",
    type: "asset",
    is_postable: true,
    level: 2,
  },
  {
    code: "1112",
    name_en: "Cash on Hand (KHR)",
    name_km: "សាច់ប្រាក់ (រៀល)",
    type: "asset",
    is_postable: true,
    level: 2,
  },
  {
    code: "1120",
    name_en: "Bank Accounts",
    name_km: "គណនីធនាគារ",
    type: "asset",
    is_postable: false,
    level: 2,
  },
  {
    code: "1121",
    name_en: "ABA Bank – USD",
    name_km: "ធនាគារ ABA – ដុល្លារ",
    type: "asset",
    is_postable: true,
    level: 2,
  },
  {
    code: "1122",
    name_en: "ABA Bank – KHR",
    name_km: "ធនាគារ ABA – រៀល",
    type: "asset",
    is_postable: true,
    level: 2,
  },
  {
    code: "1200",
    name_en: "Accounts Receivable",
    name_km: "គណនីត្រូវទទួល",
    type: "asset",
    is_postable: true,
    level: 1,
  },
  {
    code: "1300",
    name_en: "Prepaid Expenses",
    name_km: "ការចំណាយជាមុន",
    type: "asset",
    is_postable: true,
    level: 1,
  },
  {
    code: "1400",
    name_en: "Inventory",
    name_km: "សារពើភ័ណ្ឌ",
    type: "asset",
    is_postable: true,
    level: 1,
  },
  {
    code: "1500",
    name_en: "Fixed Assets",
    name_km: "ទ្រព្យថេរ",
    type: "asset",
    is_postable: false,
    level: 1,
  },
  {
    code: "1510",
    name_en: "Property, Plant & Equipment",
    name_km: "អចលនទ្រព្យ និងបរិក្ខារ",
    type: "asset",
    is_postable: true,
    level: 2,
  },
  {
    code: "1520",
    name_en: "Accumulated Depreciation",
    name_km: "ការរំលស់សន្សំ",
    type: "asset",
    is_postable: true,
    level: 2,
  },
  // LIABILITIES
  {
    code: "2000",
    name_en: "Liabilities",
    name_km: "បំណុល",
    type: "liability",
    is_postable: false,
    level: 0,
  },
  {
    code: "2100",
    name_en: "Current Liabilities",
    name_km: "បំណុលចរន្ត",
    type: "liability",
    is_postable: false,
    level: 1,
  },
  {
    code: "2110",
    name_en: "Accounts Payable",
    name_km: "គណនីត្រូវសង",
    type: "liability",
    is_postable: true,
    level: 2,
  },
  {
    code: "2120",
    name_en: "VAT Output Payable",
    name_km: "អាករលើតម្លៃបន្ថែម",
    type: "liability",
    is_postable: true,
    level: 2,
  },
  {
    code: "2130",
    name_en: "WHT Payable",
    name_km: "ពន្ធកាត់ទុក",
    type: "liability",
    is_postable: true,
    level: 2,
  },
  {
    code: "2140",
    name_en: "ToS Payable",
    name_km: "ពន្ធប្រាក់ខែ",
    type: "liability",
    is_postable: true,
    level: 2,
  },
  {
    code: "2150",
    name_en: "NSSF Payable",
    name_km: "មូលនិធិសន្តិសុខសង្គម",
    type: "liability",
    is_postable: true,
    level: 2,
  },
  {
    code: "2160",
    name_en: "Seniority Indemnity Payable",
    name_km: "សំណងជូនដំណឹង",
    type: "liability",
    is_postable: true,
    level: 2,
  },
  {
    code: "2170",
    name_en: "Accrued Expenses",
    name_km: "ការចំណាយជំពាក់",
    type: "liability",
    is_postable: true,
    level: 2,
  },
  {
    code: "2200",
    name_en: "Long-term Liabilities",
    name_km: "បំណុលរយៈពេលវែង",
    type: "liability",
    is_postable: false,
    level: 1,
  },
  {
    code: "2210",
    name_en: "Bank Loans",
    name_km: "ប្រាក់កម្ចីធនាគារ",
    type: "liability",
    is_postable: true,
    level: 2,
  },
  // EQUITY
  {
    code: "3000",
    name_en: "Equity",
    name_km: "មូលធន",
    type: "equity",
    is_postable: false,
    level: 0,
  },
  {
    code: "3100",
    name_en: "Share Capital",
    name_km: "មូលធនភាគហ៊ុន",
    type: "equity",
    is_postable: true,
    level: 1,
  },
  {
    code: "3200",
    name_en: "Retained Earnings",
    name_km: "ប្រាក់ចំណេញរក្សាទុក",
    type: "equity",
    is_postable: true,
    level: 1,
  },
  {
    code: "3300",
    name_en: "Current Year Profit / Loss",
    name_km: "ចំណេញ/ខាត ឆ្នាំបច្ចុប្បន្ន",
    type: "equity",
    is_postable: true,
    level: 1,
  },
  // REVENUE
  {
    code: "4000",
    name_en: "Revenue",
    name_km: "ចំណូល",
    type: "revenue",
    is_postable: false,
    level: 0,
  },
  {
    code: "4100",
    name_en: "Sales Revenue",
    name_km: "ចំណូលពីការលក់",
    type: "revenue",
    is_postable: true,
    level: 1,
  },
  {
    code: "4200",
    name_en: "Service Revenue",
    name_km: "ចំណូលពីសេវាកម្ម",
    type: "revenue",
    is_postable: true,
    level: 1,
  },
  {
    code: "4300",
    name_en: "Other Income",
    name_km: "ចំណូលផ្សេងៗ",
    type: "revenue",
    is_postable: true,
    level: 1,
  },
  // EXPENSES
  {
    code: "5000",
    name_en: "Cost of Goods Sold",
    name_km: "តម្លៃទំនិញលក់",
    type: "expense",
    is_postable: false,
    level: 0,
  },
  {
    code: "5100",
    name_en: "COGS – Products",
    name_km: "តម្លៃទំនិញ",
    type: "expense",
    is_postable: true,
    level: 1,
  },
  {
    code: "5200",
    name_en: "COGS – Services",
    name_km: "តម្លៃសេវាកម្ម",
    type: "expense",
    is_postable: true,
    level: 1,
  },
  {
    code: "6000",
    name_en: "Operating Expenses",
    name_km: "ចំណាយប្រតិបត្តិការ",
    type: "expense",
    is_postable: false,
    level: 0,
  },
  {
    code: "6100",
    name_en: "Salaries & Wages",
    name_km: "ប្រាក់បៀវត្សន៍",
    type: "expense",
    is_postable: true,
    level: 1,
  },
  {
    code: "6110",
    name_en: "NSSF Employer Contribution",
    name_km: "វិភាគទានម្ចាស់ការ NSSF",
    type: "expense",
    is_postable: true,
    level: 2,
  },
  {
    code: "6120",
    name_en: "Seniority Indemnity Expense",
    name_km: "ការចំណាយជូនដំណឹង",
    type: "expense",
    is_postable: true,
    level: 2,
  },
  {
    code: "6200",
    name_en: "Rent Expense",
    name_km: "ការចំណាយជួល",
    type: "expense",
    is_postable: true,
    level: 1,
  },
  {
    code: "6300",
    name_en: "Utilities",
    name_km: "ថ្លៃប្រើប្រាស់",
    type: "expense",
    is_postable: true,
    level: 1,
  },
  {
    code: "6400",
    name_en: "Depreciation Expense",
    name_km: "ការចំណាយរំលស់",
    type: "expense",
    is_postable: true,
    level: 1,
  },
  {
    code: "6500",
    name_en: "Bank Charges & Interest",
    name_km: "ថ្លៃសេវាធនាគារ",
    type: "expense",
    is_postable: true,
    level: 1,
  },
  {
    code: "6600",
    name_en: "Tax Expense – CIT",
    name_km: "ការចំណាយពន្ធ – ពន្ធអាករ",
    type: "expense",
    is_postable: true,
    level: 1,
  },
]);

const filteredAccounts = computed(() => {
  return accounts.value.filter((a) => {
    const matchSearch =
      searchQuery.value === "" ||
      a.code.includes(searchQuery.value) ||
      a.name_en.toLowerCase().includes(searchQuery.value.toLowerCase()) ||
      a.name_km.includes(searchQuery.value);
    const matchType = filterType.value === "" || a.type === filterType.value;
    const matchPostable = !showPostableOnly.value || a.is_postable;
    return matchSearch && matchType && matchPostable;
  });
});

const typeSummary = computed(() => {
  const types = ["asset", "liability", "equity", "revenue", "expense"];
  return types.map((type) => ({
    type,
    count: accounts.value.filter((a) => a.type === type && a.is_postable)
      .length,
  }));
});

function typeBadgeClass(type: string) {
  const map: Record<string, string> = {
    asset: "bg-blue-100 text-blue-700",
    liability: "bg-purple-100 text-purple-700",
    equity: "bg-green-100 text-green-700",
    revenue: "bg-emerald-100 text-emerald-700",
    expense: "bg-red-100 text-red-700",
  };
  return map[type] ?? "bg-gray-100 text-gray-700";
}

function saveAccount() {
  if (
    !newAccount.value.code ||
    !newAccount.value.name_en ||
    !newAccount.value.type
  )
    return;
  accounts.value.push({
    ...newAccount.value,
    type: newAccount.value.type as Account["type"],
    level: 2,
  });
  newAccount.value = {
    code: "",
    name_en: "",
    name_km: "",
    type: "",
    is_postable: true,
  };
  showAddModal.value = false;
}
</script>

<i18n lang="json">
{
  "en": {
    "coa": {
      "title": "Chart of Accounts",
      "subtitle": "Cambodian Standard Chart of Accounts (CSCA)",
      "add_account": "Add Account",
      "search_placeholder": "Search by code, name EN or KM…",
      "all_types": "All Types",
      "postable_only": "Postable only",
      "col_code": "Code",
      "col_name_en": "Name (EN)",
      "col_name_km": "Name (KM)",
      "col_type": "Type",
      "col_postable": "Postable",
      "col_actions": "Actions",
      "no_results": "No accounts match your search.",
      "modal_title": "Add New Account",
      "field_code": "Account Code",
      "field_type": "Account Type",
      "field_name_en": "Name (English)",
      "field_name_km": "Name (Khmer)",
      "field_postable": "Allow direct posting"
    },
    "common": { "cancel": "Cancel", "save": "Save" }
  },
  "km": {
    "coa": {
      "title": "តារាងគណនី",
      "subtitle": "តារាងគណនីស្តង់ដារកម្ពុជា",
      "add_account": "បន្ថែមគណនី",
      "search_placeholder": "ស្វែងរកតាមលេខ ឈ្មោះ…",
      "all_types": "ប្រភេទទាំងអស់",
      "postable_only": "តែជាប្រភេទបញ្ចូល",
      "col_code": "លេខ",
      "col_name_en": "ឈ្មោះ (អង់គ្លេស)",
      "col_name_km": "ឈ្មោះ (ខ្មែរ)",
      "col_type": "ប្រភេទ",
      "col_postable": "បញ្ចូលបាន",
      "col_actions": "សកម្មភាព",
      "no_results": "មិនមានគណនីដែលត្រូវគ្នា",
      "modal_title": "បន្ថែមគណនីថ្មី",
      "field_code": "លេខគណនី",
      "field_type": "ប្រភេទគណនី",
      "field_name_en": "ឈ្មោះជាអង់គ្លេស",
      "field_name_km": "ឈ្មោះជាខ្មែរ",
      "field_postable": "អនុញ្ញាតឱ្យបញ្ចូលដោយផ្ទាល់"
    },
    "common": { "cancel": "បោះបង់", "save": "រក្សាទុក" }
  }
}
</i18n>
