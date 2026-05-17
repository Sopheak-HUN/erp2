<script setup lang="ts">
const auth = useAuthStore()
</script>

<template>
  <div class="flex h-screen bg-gray-50">
    <AppSidebar />
    <div class="flex-1 flex flex-col overflow-hidden">
      <AppNavbar />
      <main class="flex-1 overflow-y-auto p-6">
        <slot />
      </main>
    </div>
  </div>
</template>
