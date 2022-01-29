// Import modules...
import { createApp, h } from 'vue'
import { createInertiaApp } from '@inertiajs/inertia-vue3'
import { InertiaProgress } from '@inertiajs/progress'
import PortalVue from 'portal-vue'

require('./bootstrap')

const el = document.getElementById('app')

createInertiaApp({
  resolve: (name) => require(`./Pages/${name}`),
  setup ({ el, app, props, plugin }) {
    createApp({ render: () => h(app, props) })
      .mixin({ methods: { route } })
      .use(plugin)
      .use(PortalVue)
      .mount(el)
  },
})

InertiaProgress.init({ color: '#4B5563' })
