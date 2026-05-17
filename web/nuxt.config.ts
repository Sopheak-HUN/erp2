export default defineNuxtConfig({
  compatibilityDate: '2025-05-16',

  future: {
    compatibilityVersion: 4,
  },

  devServer: {
    port: 3001,
  },

  modules: [
    '@primevue/nuxt-module',
    '@pinia/nuxt',
    '@nuxtjs/i18n',
    '@nuxtjs/tailwindcss',
    '@vueuse/nuxt',
  ],

  primevue: {
    options: {
      ripple: true,
    },
    importTheme: { from: '@primevue/themes/aura' },
    darkModeSelector: '.dark',
  },

  css: [
    '~/assets/css/main.css',
    'primeicons/primeicons.css',
  ],

  i18n: {
    locales: [
      { code: 'en', name: 'English', file: 'en.json' },
      { code: 'km', name: 'ភាសាខ្មែរ', file: 'km.json' },
    ],
    defaultLocale: 'en',
    lazy: true,
    langDir: '../i18n',
    strategy: 'prefix_except_default',
    detectBrowserLanguage: {
      useCookie: true,
      cookieKey: 'i18n_redirected',
      redirectOn: 'root',
    },
  },

  runtimeConfig: {
    public: {
      apiBase: process.env.NUXT_PUBLIC_API_BASE || 'http://localhost:8000/api/v1',
    },
  },

  devtools: { enabled: true },
})
