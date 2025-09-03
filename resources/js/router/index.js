// resources/js/router/index.js
import { createRouter, createWebHistory } from 'vue-router'
import { useCookies } from '@vueuse/integrations/useCookies'
import { useAbility } from '@casl/vue'

// استيراد الصفحات
import Login from '@/pages/login.vue'
import Register from '@/pages/register.vue'
import Dashboard from '@/pages/dashboards/crm.vue' // صححت اسم الصفحة
import Users from '@/pages/apps/user/list/index.vue'

// إنشاء cookies instance
const cookies = useCookies(['accessToken', 'userData', 'userAbilityRules'])

// تعريف routes
const routes = [
  {
    path: '/login',
    name: 'login',
    component: Login,
    meta: { layout: 'blank', unauthenticatedOnly: true },
  },
  {
    path: '/register',
    name: 'register',
    component: Register,
    meta: { layout: 'blank', unauthenticatedOnly: true },
  },
  {
    path: '/dashboard',
    name: 'dashboard',
    component: Dashboard,
    meta: { layout: 'default', requiresAuth: true },
  },
  {
    path: '/users',
    name: 'users',
    component: Users,
    meta: { layout: 'default', requiresAuth: true },
  },
]

// إنشاء router
const router = createRouter({
  history: createWebHistory(),
  routes,
})

// 🔒 Auth Guard
router.beforeEach((to, from, next) => {
  const accessToken = cookies.get('accessToken') // استخدمنا get()

  if (to.meta.requiresAuth && !accessToken) {
    next({ path: '/login', query: { to: to.fullPath } })
  } else if (to.meta.unauthenticatedOnly && accessToken) {
    next('/dashboard')
  } else {
    next()
  }
})

// ⚡ Logout function
export const logout = async () => {
  try {
    // مسح جميع الكوكيز
    cookies.remove('accessToken')
    cookies.remove('userData')
    cookies.remove('userAbilityRules')

    // إعادة تعيين الصلاحيات
    const ability = useAbility()

    ability.update([])

    // إعادة التوجيه لصفحة login
    router.replace({ name: 'login' })
  } catch (error) {
    console.error('Logout failed:', error)
  }
}

export default router
