<template>
  <div class="min-h-screen bg-gray-50 flex items-center justify-center px-4">
    <div class="w-full max-w-md">
      <!-- Logo -->
      <div class="text-center mb-8">
        <div class="w-14 h-14 bg-blue-600 rounded-xl flex items-center justify-center mx-auto mb-4">
          <span class="text-white font-bold text-xl">KH</span>
        </div>
        <h1 class="text-2xl font-bold text-gray-900">Cambodia ERP</h1>
        <p class="text-sm text-gray-500 mt-1">Multi-Tenant ERP for Cambodian Businesses</p>
      </div>
      <slot />
    </div>
  </div>
</template>
