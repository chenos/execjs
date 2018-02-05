import Vue from 'vue'
import App from './components/App.vue'
import { createRouter } from './router'

export function createApp () {
  const router = createRouter()
  const app = new Vue({
    router,
    render: h => h(App)
  })
  return { app, router }
}
