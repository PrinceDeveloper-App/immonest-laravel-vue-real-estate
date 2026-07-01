import { createI18n } from 'vue-i18n'
import en from './lang/en.js'
import de from './lang/de.js'

const savedLocale = localStorage.getItem('locale') || 'en'

export const i18n = createI18n({
  legacy: false,
  locale: savedLocale,
  fallbackLocale: 'en',
  messages: { en, de },
})
