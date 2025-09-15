<script setup>
import { defineAsyncComponent, ref, watch, onMounted } from 'vue'
import { useConfigStore } from '@core/stores/config'
import { AppContentLayoutNav } from '@layouts/enums'
import { switchToVerticalNavOnLtOverlayNavBreakpoint } from '@layouts/utils'
import { useCookies } from '@vueuse/integrations/useCookies'

const DefaultLayoutWithHorizontalNav = defineAsyncComponent(() => import('./components/DefaultLayoutWithHorizontalNav.vue'))
const DefaultLayoutWithVerticalNav = defineAsyncComponent(() => import('./components/DefaultLayoutWithVerticalNav.vue'))
const configStore = useConfigStore()

// 🟢 استخدام vueuse لقراءة الكوكيز
const cookies = useCookies()
const userData = cookies.get('userData') // بيرجع string أو object حسب ما تخزن

function applyLayoutByRole(role) {
  configStore.appContentLayoutNav = role === 'admin'
    ? AppContentLayoutNav.Vertical
    : AppContentLayoutNav.Horizontal
}

onMounted(() => {
  if (userData) {
    try {
      const user = typeof userData === 'string' ? JSON.parse(userData) : userData
      applyLayoutByRole(user?.role)
    } catch (e) {
      applyLayoutByRole(null)
    }
  } else {
    applyLayoutByRole(null)
  }
})

// لو الشاشة صغيرة أحيانًا بيحوّل لـ Vertical
switchToVerticalNavOnLtOverlayNavBreakpoint()

const { layoutAttrs, injectSkinClasses } = useSkins()
injectSkinClasses()

// SECTION: Loading Indicator
const isFallbackStateActive = ref(false)
const refLoadingIndicator = ref(null)

watch([isFallbackStateActive, refLoadingIndicator], () => {
  if (isFallbackStateActive.value && refLoadingIndicator.value)
    refLoadingIndicator.value.fallbackHandle()
  if (!isFallbackStateActive.value && refLoadingIndicator.value)
    refLoadingIndicator.value.resolveHandle()
}, { immediate: true })
</script>

<template>
  <Component
    v-bind="layoutAttrs"
    :is="configStore.appContentLayoutNav === AppContentLayoutNav.Vertical ? DefaultLayoutWithVerticalNav : DefaultLayoutWithHorizontalNav"
  >
    <AppLoadingIndicator ref="refLoadingIndicator" />

    <RouterView v-slot="{ Component }">
      <Suspense
        :timeout="0"
        @fallback="isFallbackStateActive = true"
        @resolve="isFallbackStateActive = false"
      >
        <Component :is="Component" />
      </Suspense>
    </RouterView>
  </Component>
</template>


<style lang="scss">
// As we are using `layouts` plugin we need its styles to be imported
@use "@layouts/styles/default-layout";
</style>
