import { route } from 'quasar/wrappers'
import { createRouter, createMemoryHistory, createWebHistory, createWebHashHistory } from 'vue-router'
import routes from './routes'
import getCookie from 'src/utils/cookies'

let Router = null

export default route(function (/* { store, ssrContext } */) {
  const createHistory = process.env.SERVER
    ? createMemoryHistory
    : (process.env.VUE_ROUTER_MODE === 'history' ? createWebHistory : createWebHashHistory)

  Router = createRouter({
    scrollBehavior: () => ({ left: 0, top: 0 }),
    routes,
    history: createHistory(process.env.VUE_ROUTER_BASE)
  })

  // Middleware para checar a autenticação
  Router.beforeEach((to, from, next) => {
    if (to.meta.requiresAuth) {
      const token = getCookie('token')

      if (!token) {
        return next({ name: 'Login' })
      }
    }
    next()
  })

  return Router
})

// Exporta a instância do roteador
export { Router }
