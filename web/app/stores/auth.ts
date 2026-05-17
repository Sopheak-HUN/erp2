import { defineStore } from 'pinia'

interface User {
  id: string
  name: string
  email: string
}

export const useAuthStore = defineStore('auth', () => {
  const user = ref<User | null>(null)
  const token = useCookie('auth_token')
  const isAuthenticated = computed(() => !!token.value)

  const { apiFetch } = useApi()

  async function login(email: string, password: string) {
    const response = await apiFetch<{ user: User; token: string }>('/auth/login', {
      method: 'POST',
      body: { email, password },
    })
    user.value = response.user
    token.value = response.token
  }

  async function register(name: string, email: string, password: string, password_confirmation: string) {
    const response = await apiFetch<{ user: User; token: string }>('/auth/register', {
      method: 'POST',
      body: { name, email, password, password_confirmation },
    })
    user.value = response.user
    token.value = response.token
  }

  async function fetchUser() {
    if (!token.value) return
    try {
      user.value = await apiFetch<User>('/me')
    } catch {
      token.value = null
      user.value = null
    }
  }

  async function logout() {
    try {
      await apiFetch('/auth/logout', { method: 'POST' })
    } finally {
      token.value = null
      user.value = null
      navigateTo('/login')
    }
  }

  return { user, token, isAuthenticated, login, register, fetchUser, logout }
})
