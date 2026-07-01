import { createApp, h } from 'vue'
import { createInertiaApp } from '@inertiajs/vue3'
import MainLayout from '@/Layouts/MainLayout.vue'
import { ZiggyVue } from 'ziggy-js'
import { i18n } from './i18n.js'
import '../css/app.css'

createInertiaApp({
  progress: {
    color: '#4f46e5',
    delay: 250,
  },
  resolve: async (name) => {
    const pages = import.meta.glob('./Pages/**/*.vue')
    const page = await pages[`./Pages/${name}.vue`]()
    page.default.layout = page.default.layout || MainLayout

    return page    },
  setup({ el, App, props, plugin }) {
    createApp({ render: () => h(App, props) })
      .use(plugin)
      .use(ZiggyVue)
      .use(i18n)
      .mount(el)
  },
})