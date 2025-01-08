const routes = [
  {
    path: '/',
    component: () => import('layouts/Default.vue'),
    children: [
      // {
      //   path: '',
      //   name: 'Home',
      //   component: () => import('pages/IndexPage.vue'),
      //   meta: { requiresAuth: true },
      // },
      {
        path: '/',
        name: 'ListaTarefas',
        component: () => import('pages/ListaTarefas.vue'),
        meta: { requiresAuth: true },
      },
    ]
  },
  {
    path: '/login',
    component: () => import('layouts/Auth.vue'),
    children: [
      {
        path: '',
        name: 'Login',
        component: () => import('pages/Login/Index.vue')
      },
      {
        path: '/criar',
        name: 'CriarConta',
        component: () => import('pages/Login/Criar.vue')
      },
    ]
  },

  // Always leave this as last one,
  // but you can also remove it
  {
    path: '/:catchAll(.*)*',
    component: () => import('pages/ErrorNotFound.vue')
  }
]

export default routes
