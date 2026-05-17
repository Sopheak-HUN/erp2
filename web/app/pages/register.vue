<script setup lang="ts">
definePageMeta({ layout: 'auth' })

const auth = useAuthStore()

const form = reactive({ name: '', email: '', password: '', password_confirmation: '' })
const loading = ref(false)
const error = ref('')

const handleRegister = async () => {
  loading.value = true
  error.value = ''
  try {
    await auth.register(form.name, form.email, form.password, form.password_confirmation)
    navigateTo('/')
  } catch (e: any) {
    error.value = e?.data?.message || 'Registration failed'
  } finally {
    loading.value = false
  }
}
</script>

<template>
  <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-8">
    <h2 class="text-xl font-semibold text-gray-900 mb-6">Create Account</h2>

    <div v-if="error" class="mb-4 p-3 bg-red-50 border border-red-200 rounded-lg text-sm text-red-700">{{ error }}</div>

    <form @submit.prevent="handleRegister" class="space-y-4">
      <div>
        <label class="block text-sm font-medium text-gray-700 mb-1">Full Name</label>
        <input v-model="form.name" type="text" required class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 outline-none" placeholder="Sopheak" />
      </div>
      <div>
        <label class="block text-sm font-medium text-gray-700 mb-1">Email</label>
        <input v-model="form.email" type="email" required class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 outline-none" placeholder="you@company.com" />
      </div>
      <div>
        <label class="block text-sm font-medium text-gray-700 mb-1">Password</label>
        <input v-model="form.password" type="password" required class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 outline-none" placeholder="••••••••" />
      </div>
      <div>
        <label class="block text-sm font-medium text-gray-700 mb-1">Confirm Password</label>
        <input v-model="form.password_confirmation" type="password" required class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 outline-none" placeholder="••••••••" />
      </div>
      <button type="submit" :disabled="loading"
        class="w-full py-2.5 bg-blue-600 text-white font-medium rounded-lg hover:bg-blue-700 disabled:opacity-50 transition-colors">
        {{ loading ? 'Creating...' : 'Create Account' }}
      </button>
    </form>

    <p class="mt-6 text-center text-sm text-gray-500">
      Already have an account? <NuxtLink to="/login" class="text-blue-600 hover:underline font-medium">Sign In</NuxtLink>
    </p>
  </div>
</template>
