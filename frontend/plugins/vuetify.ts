import { createVuetify } from 'vuetify'
import * as components from 'vuetify/components'
import * as directives from 'vuetify/directives'

// Подключаем стили Vuetify
import 'vuetify/styles'

// Импортируем русский перевод
import { ru } from 'vuetify/locale'

export default defineNuxtPlugin((nuxtApp) => {
    const vuetify = createVuetify({
        locale: {
            locale: 'ru',
            messages: { ru }
        },
        theme: {
            defaultTheme: 'dark',
        },
        components,
        directives
    })

    nuxtApp.vueApp.use(vuetify)
})
