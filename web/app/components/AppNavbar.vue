<script setup lang="ts">
const { locale, setLocale } = useI18n();
const auth = useAuthStore();

const toggleLocale = () => {
  setLocale(locale.value === "en" ? "km" : "en");
};
</script>

<template>
  <header
    class="h-14 bg-white border-b border-gray-200 flex items-center justify-between px-6"
  >
    <!-- Breadcrumb / Page title -->
    <div class="flex items-center gap-2">
      <slot name="title">
        <h2 class="text-lg font-semibold text-gray-900">Dashboard</h2>
      </slot>
    </div>

    <!-- Right side actions -->
    <div class="flex items-center gap-3">
      <!-- Language toggle -->
      <button
        @click="toggleLocale"
        class="px-3 py-1.5 text-xs font-medium border border-gray-300 rounded-md hover:bg-gray-50 transition-colors"
      >
        {{ locale === "en" ? "ភាសាខ្មែរ" : "English" }}
      </button>

      <!-- Notifications -->
      <button
        class="relative p-2 text-gray-500 hover:text-gray-700 hover:bg-gray-100 rounded-lg transition-colors"
      >
        <i class="pi pi-bell text-lg"></i>
        <span
          class="absolute top-1 right-1 w-2 h-2 bg-red-500 rounded-full"
        ></span>
      </button>

      <!-- User menu -->
      <div class="flex items-center gap-2 pl-3 border-l border-gray-200">
        <div
          class="w-8 h-8 bg-blue-100 rounded-full flex items-center justify-center"
        >
          <span class="text-blue-700 font-medium text-sm">
            {{ auth.user?.name?.charAt(0)?.toUpperCase() || "U" }}
          </span>
        </div>
        <div class="hidden md:block">
          <p class="text-sm font-medium text-gray-900">
            {{ auth.user?.name || "User" }}
          </p>
          <p class="text-xs text-gray-500">{{ auth.user?.email || "" }}</p>
        </div>
        <button
          @click="auth.logout()"
          class="ml-2 p-1.5 text-gray-400 hover:text-red-600 hover:bg-red-50 rounded-md transition-colors"
          title="Logout"
        >
          <i class="pi pi-sign-out text-sm"></i>
        </button>
      </div>
    </div>
  </header>
</template>
