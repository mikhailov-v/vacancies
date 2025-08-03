// https://nuxt.com/docs/api/configuration/nuxt-config
export default defineNuxtConfig({
  compatibilityDate: '2025-07-15',
  devtools: { enabled: true },

  css: [
    'vuetify/styles',
    '@mdi/font/css/materialdesignicons.css' // иконки
  ],

  build: {
    transpile: ['vuetify']
  },

  runtimeConfig: {
    public: {
      // Используется в браузере
      apiBaseUrl: process.env.PUBLIC_API_BASE_URL || 'http://localhost:8083'
    },
    // Используется внутри Nuxt при SSR или $fetch на сервере
    apiBaseUrl: process.env.API_BASE_URL || 'http://nginx'
  }
})
