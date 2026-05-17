<script setup lang="ts">
definePageMeta({ layout: 'auth' })

const auth = useAuthStore()

const form = reactive({ email: '', password: '' })
const loading = ref(false)
const error = ref('')

const handleLogin = async () => {
  loading.value = true
  error.value = ''
  try {
    await auth.login(form.email, form.password)
    navigateTo('/')
  } catch (e: any) {
    error.value = e?.data?.message || 'Invalid credentials'
  } finally {
    loading.value = false
  }
}
</script>

<template>
  <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-8">
    <h2 class="text-xl font-semibold text-gray-900 mb-6">Sign In</h2>

    <div v-if="error" class="mb-4 p-3 bg-red-50 border border-red-200 rounded-lg text-sm text-red-700">{{ error }}</div>

    <form @submit.prevent="handleLogin" class="space-y-4">
      <div>
        <label class="block text-sm font-medium text-gray-700 mb-1">Email</label>
        <input v-model="form.email" type="email" required
          class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 outline-none"
          placeholder="you@company.com" />
      </div>
      <div>
        <label class="block text-sm font-medium text-gray-700 mb-1">Password</label>
        <input v-model="form.password" type="password" required
          class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 outline-none"
          placeholder="••••••••" />
      </div>
      <button type="submit" :disabled="loading"
        class="w-full py-2.5 bg-blue-600 text-white font-medium rounded-lg hover:bg-blue-700 disabled:opacity-50 transition-colors">
        {{ loading ? 'Signing in...' : 'Sign In' }}
      </button>
    </form>

    <p class="mt-6 text-center text-sm text-gray-500">
      Don't have an account? <NuxtLink to="/register" class="text-blue-600 hover:underline font-medium">Register</NuxtLink>
    </p>
  </div>
</template>
