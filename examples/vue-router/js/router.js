import Vue from 'vue'
import Router from 'vue-router'
// import Home from './components/Home.vue'
// import About from './components/About.vue'

Vue.use(Router)

export function createRouter () {
  return new Router({
    mode: 'history',
    routes: [
      {
        path: '/vue-router/',
        component: () => import('./components/Home.vue')
      },
      {
        path: '/vue-router/about',
        component: () => import('./components/About.vue')
      },
      // { path: '/vue/demo/', component: Home },
      // { path: '/vue/demo/about', component: About },
    ]
  })
}
