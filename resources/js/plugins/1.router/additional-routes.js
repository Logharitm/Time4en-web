const emailRouteComponent = () => import('@/pages/apps/email/index.vue')

export const redirects = [
  {
    path: '/',
    name: 'index',
    redirect: to => {
      const userData = useCookie('userData')
      const userRole = userData.value?.role
      if (userRole === 'admin')
        return { name: 'dashboards-crm' }
      if (userRole === 'user')
        return { name: 'access-control' }

      return { name: 'login', query: to.query }
    },
  },
]


export const routes = [
  {
    path: '/dashboard',
    name: 'dashboard',
    component: () => import('@/pages/dashboards/crm.vue'),
  },
  {
    path: '/users',
    name: 'users',
    component: () => import('@/pages/apps/user/list/index.vue'),
  },

]
