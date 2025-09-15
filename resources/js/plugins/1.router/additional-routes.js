const emailRouteComponent = () => import('@/pages/apps/email/index.vue')

export const redirects = [
  {
    path: '/',
    name: 'index',
    redirect: to => {
      const userData = useCookie('userData')

      // لو الكوكيز مش موجودة، نعيد للتسجيل
      if (!userData.value) return { name: 'login', query: to.query }

      const userRole = userData.value.role

      if (userRole === 'admin') return { name: 'home-dashboard' }
      if (userRole === 'user') return { name: 'access-control' }

      // أي حالة أخرى
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
