export const useApi = () => {
  const config = useRuntimeConfig()
  const token = useCookie('auth_token')

  const apiFetch = $fetch.create({
    baseURL: config.public.apiBase as string,
    onRequest({ options }) {
      const headers = (options.headers = options.headers || {})
      if (headers instanceof Headers) {
        headers.set('Accept', 'application/json')
        if (token.value) {
          headers.set('Authorization', `Bearer ${token.value}`)
        }
      } else if (!Array.isArray(headers)) {
        (headers as Record<string, string>)['Accept'] = 'application/json'
        if (token.value) {
          (headers as Record<string, string>)['Authorization'] = `Bearer ${token.value}`
        }
      }
    },
    onResponseError({ response }) {
      if (response.status === 401) {
        token.value = null
        navigateTo('/login')
      }
    },
  })

  return { apiFetch, token }
}
