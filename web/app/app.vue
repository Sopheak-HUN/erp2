<script setup lang="ts">
const auth = useAuthStore()

// Fetch user on app load if token exists
onMounted(async () => {
  if (auth.token) {
    await auth.fetchUser()
  }
})
</script>

<template>
  <NuxtLayout>
    <NuxtPage />
  </NuxtLayout>
</template>

<style>
@import url('https://fonts.googleapis.com/css2?family=Noto+Sans+Khmer:wght@300;400;500;600;700&family=Inter:wght@300;400;500;600;700&display=swap');

body {
  font-family: 'Inter', 'Noto Sans Khmer', sans-serif;
}

/* Khmer locale override */
:lang(km) {
  font-family: 'Noto Sans Khmer', 'Inter', sans-serif;
}
</style>
