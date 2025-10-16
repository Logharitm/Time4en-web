<script setup>
import { useWindowScroll } from '@vueuse/core'
import { PerfectScrollbar } from 'vue3-perfect-scrollbar'
import { useDisplay } from 'vuetify'
import navImg from '@images/front-pages/misc/nav-item-col-img.png'
import NavbarThemeSwitcher from '@/layouts/components/NavbarThemeSwitcher.vue'
import { VNodeRenderer } from '@layouts/components/VNodeRenderer'
import { themeConfig } from '@themeConfig'
import NavBarNotifications from '@/layouts/components/NavBarNotifications.vue'
import LanguageSwitcher from '@core/components/I18n.vue'

const props = defineProps({ activeId: String })

const display = useDisplay()
const { y } = useWindowScroll()
const route = useRoute()
const router = useRouter()
const sidebar = ref(false)

const languages = [
  { i18nLang: 'en', label: 'English' },
  { i18nLang: 'ar', label: 'العربية' },
]

watch(() => display, () => {
  return display.mdAndUp ? sidebar.value = false : sidebar.value
}, { deep: true })

const isMenuOpen = ref(false)

// حالة المستخدم
const isLoggedIn = computed(() => {
  const userData = useCookie('userData').value

  return !!userData
})

const userData = computed(() => {
  return useCookie('userData').value
})

const menuItems = ref([
  {
    listTitle: 'Authentication',
    listIcon: 'tabler-lock',
    navItems: [
      { name: 'Login', to: { name: 'login' } },
      { name: 'Register', to: { name: 'register' } },
    ],
  },
])

const isCurrentRoute = to => {
  return route.matched.some(_route => _route.path.startsWith(router.resolve(to).path))
}

// دالة تسجيل الخروج
const logout = async () => {
  try {
    // إرسال طلب تسجيل الخروج إلى الـ API
    await $api('/logout', {
      method: 'POST',
    })
  } catch (error) {
    console.error('Logout error:', error)
  } finally {
    // مسح البيانات المحلية بغض النظر عن استجابة الـ API
    useCookie('accessToken').value = null
    useCookie('userData').value = null
    useCookie('userAbilityRules').value = null

    // إعادة التوجيه إلى الصفحة الرئيسية
    await router.replace('/')

    // إعادة تحميل الصفحة لتحديث الحالة
    window.location.reload()
  }
}
</script>

<template>
  <!-- 👉 Navigation drawer for mobile devices  -->
  <VNavigationDrawer
    v-model="sidebar"
    width="275"
    data-allow-mismatch
    disable-resize-watcher
  >
    <PerfectScrollbar
      :options="{ wheelPropagation: false }"
      class="h-100"
    >
      <!-- Nav items -->
      <div>
        <div class="d-flex flex-column gap-y-4 pa-4">
          <RouterLink
            v-for="(item, index) in [
              { label:$t('home'), hash: 'home' },
              { label:$t('pricing-plan') , hash: 'pricing-plan' },
              { label:$t('faq'), hash: 'faq' },
              { label:$t('contact-us') , hash: 'contact-us' },
            ]"
            :key="index"
            :to="{ name: 'home', hash: `#${item.hash}` }"
            class="nav-link font-weight-medium"
            :class="[props.activeId === item.hash ? 'active-link' : '']"
          >
            {{ item.label }}
          </RouterLink>

          <!-- 👉 قوائم المستخدم المسجل -->
          <div
            v-if="isLoggedIn"
            class="d-flex flex-column gap-y-4"
          >
            <RouterLink
              to="/profile"
              class="nav-link font-weight-medium"
              @click="sidebar = false"
            >
              {{ $t('Profile') }}
            </RouterLink>
            <RouterLink
              to="/folders"
              class="nav-link font-weight-medium"
              @click="sidebar = false"
            >
              {{ $t('my subscriptions') }}
            </RouterLink>
            <RouterLink
              to="/folders"
              class="nav-link font-weight-medium"
              @click="sidebar = false"
            >
              {{ $t('my folders') }}
            </RouterLink>
            <VBtn
              color="primary"
              variant="flat"
              block
              class="mt-2"
              @click="logout"
            >
              <VIcon
                icon="tabler-logout"
                class="me-2"
              />
              {{ $t('logout') }}
            </VBtn>
          </div>
        </div>
      </div>

      <!-- Navigation drawer close icon -->
      <VIcon
        id="navigation-drawer-close-btn"
        icon="tabler-x"
        size="20"
        @click="sidebar = !sidebar"
      />
    </PerfectScrollbar>
  </VNavigationDrawer>

  <!-- 👉 Navbar for desktop devices  -->
  <div class="front-page-navbar">
    <div class="front-page-navbar">
      <VAppBar
        :color="$vuetify.theme.current.dark ? 'rgba(var(--v-theme-surface),0.38)' : 'rgba(var(--v-theme-surface), 0.38)'"
        :class="y > 10 ? 'app-bar-scrolled' : [$vuetify.theme.current.dark ? 'app-bar-dark' : 'app-bar-light', 'elevation-0']"
        class="navbar-blur"
      >
        <!-- toggle icon for mobile device -->
        <IconBtn
          id="vertical-nav-toggle-btn"
          class="ms-n3 me-2 d-inline-block d-md-none"
          @click="sidebar = !sidebar"
        >
          <VIcon
            size="26"
            icon="tabler-menu-2"
            color="rgba(var(--v-theme-on-surface))"
          />
        </IconBtn>
        <!-- Title and Landing page sections -->
        <div class="d-flex align-center">
          <VAppBarTitle class="me-6">
            <RouterLink
              to="/home"
              class="d-flex gap-x-4"
              :class="$vuetify.display.mdAndUp ? 'd-none' : 'd-block'"
            >
              <div class="app-logo">
                <VNodeRenderer :nodes="themeConfig.app.logo" />
                <h1 class="app-logo-title">
                  {{ themeConfig.app.title }}
                </h1>
              </div>
            </RouterLink>
          </VAppBarTitle>

          <!-- landing page sections -->
          <div class="text-base align-center d-none d-md-flex">
            <RouterLink
              v-for="(item, index) in [
                { label:$t('home'), hash: 'home' },
                { label:$t('pricing-plan') , hash: 'pricing-plan' },
                { label:$t('faq'), hash: 'faq' },
                { label:$t('contact-us') , hash: 'contact-us' },
              ]"
              :key="index"
              :to="{ name: 'home', hash: `#${item.hash}` }"
              class="nav-link font-weight-medium py-2 px-2 px-lg-4"
              :class="[props.activeId === item.hash ? 'active-link' : '']"
            >
              {{ item.label }}
            </RouterLink>
          </div>
        </div>

        <VSpacer />
        <LanguageSwitcher :languages="languages" />
        <NavBarNotifications v-if="isLoggedIn" />
        <div class="d-flex gap-x-4 align-center">
          <!-- 👉 زر تسجيل الخروج للمستخدم المسجل -->
          <template v-if="isLoggedIn">
            <!-- 👉 قوائم المستخدم المسجل في شريط التنقل العلوي -->
            <IconBtn>
              <!-- معلومات المستخدم -->
              <VAvatar
                v-if="userData?.avatar"
                size="36"
                :image="userData.avatar"
              />
              <VAvatar
                v-else
                size="36"
                color="primary"
                variant="tonal"
              >
                <span class="text-caption">
                  {{ userData?.name?.charAt(0) || userData?.email?.charAt(0) || 'U' }}
                </span>
              </VAvatar>
              <!-- القائمة -->
              <VMenu
                activator="parent"
                offset="12px"
                width="200"
                open-on-hover
              >
                <VList color="primary">
                  <VListItem @click="$router.push('/profile')"  v-if="userData?.role != 'admin'">
                    <VListItemTitle>{{ $t('Profile') }}</VListItemTitle>
                  </VListItem>
                  <VListItem @click="$router.push('/profile')" v-if="userData?.role != 'admin'">
                    <VListItemTitle>{{ $t('my subscriptions') }}</VListItemTitle>
                  </VListItem>

                  <VListItem @click="$router.push('/folders')" v-if="userData?.role != 'admin'">
                    <VListItemTitle>{{ $t('my folders') }}</VListItemTitle>
                  </VListItem>
                  <VListItem @click="$router.push('/pages/account-settings/account')"  v-if="userData?.role == 'admin'">
                    <VListItemTitle>{{ $t('Profile') }}</VListItemTitle>
                  </VListItem>
                  <VListItem @click="logout">
                    <VListItemTitle>{{ $t('logout') }}</VListItemTitle>
                  </VListItem>
                </VList>
              </VMenu>
            </IconBtn>
          </template>

          <!-- 👉 زر الاشتراك للمستخدم غير المسجل -->
          <VBtn
            v-else-if="$vuetify.display.lgAndUp"
            prepend-icon="tabler-lock"
            variant="elevated"
            color="primary"
            :to="{ name: 'login' }"
          >
            {{ $t('Login') }}
          </VBtn>
        </div>
      </VAppBar>
    </div>
  </div>
</template>

<style lang="scss" scoped>
.nav-menu {
  display: flex;
  gap: 2rem;
}

.nav-link {
  &:not(:hover) {
    color: rgb(var(--v-theme-on-surface));
  }
}

.page-link {
  &:hover {
    color: rgb(var(--v-theme-primary)) !important;
  }
}

@media (max-width: 1280px) {
  .nav-menu {
    gap: 2.25rem;
  }
}

@media (min-width: 1920px) {
  .front-page-navbar {
    .v-toolbar {
      max-inline-size: calc(1440px - 32px);
    }
  }
}

@media (min-width: 1280px) and (max-width: 1919px) {
  .front-page-navbar {
    .v-toolbar {
      max-inline-size: calc(1200px - 32px);
    }
  }
}

@media (min-width: 960px) and (max-width: 1279px) {
  .front-page-navbar {
    .v-toolbar {
      max-inline-size: calc(900px - 32px);
    }
  }
}

@media (min-width: 600px) and (max-width: 959px) {
  .front-page-navbar {
    .v-toolbar {
      max-inline-size: calc(100% - 64px);
    }
  }
}

@media (max-width: 600px) {
  .front-page-navbar {
    .v-toolbar {
      max-inline-size: calc(100% - 32px);
    }
  }
}

.nav-item-img {
  border: 10px solid rgb(var(--v-theme-background));
  border-radius: 10px;
}

.active-link {
  color: rgb(var(--v-theme-primary)) !important;
}

.app-bar-light {
  border: 2px solid rgba(var(--v-theme-surface), 68%);
  border-radius: 0.5rem;
  background-color: rgba(var(--v-theme-surface), 38%);
  transition: all 0.1s ease-in-out;
}

.app-bar-dark {
  border: 2px solid rgba(var(--v-theme-surface), 68%);
  border-radius: 0.5rem;
  background-color: rgba(255, 255, 255, 4%);
  transition: all 0.1s ease-in-out;
}

.app-bar-scrolled {
  border: 2px solid rgb(var(--v-theme-surface));
  border-radius: 0.5rem;
  background-color: rgb(var(--v-theme-surface)) !important;
  transition: all 0.1s ease-in-out;
}

.front-page-navbar::after {
  position: fixed;
  z-index: 2;
  block-size: 5rem;
  content: "";
  inline-size: 100%;
}
</style>

<style lang="scss">
@use "@layouts/styles/mixins" as layoutMixins;

.mega-menu {
  position: fixed !important;
  inset-block-start: 5.4rem;
  inset-inline-start: 50%;
  transform: translateX(-50%);

  @include layoutMixins.rtl {
    transform: translateX(50%);
  }
}

.front-page-navbar {
  .v-toolbar__content {
    padding-inline: 30px !important;
  }

  .v-toolbar {
    inset-inline: 0 !important;
    margin-block-start: 1rem !important;
    margin-inline: auto !important;
  }
}

.mega-menu-item {
  &:hover {
    color: rgb(var(--v-theme-primary)) !important;
  }
}

#navigation-drawer-close-btn {
  position: absolute;
  cursor: pointer;
  inset-block-start: 0.5rem;
  inset-inline-end: 1rem;
}
.front-page-navbar:after{
  display: none !important;
}
</style>
