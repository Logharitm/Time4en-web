<script setup>
import Footer from '@/views/front-pages/front-page-footer.vue'
import AccountSettingsAccount from '@/views/pages/account-settings/AccountSettingsAccount.vue'
import AccountSettingsSecurity from '@/views/pages/account-settings/AccountSettingsSecurity.vue'
import AccountSettingsBillingAndPlans from '@/views/pages/account-settings/AccountSettingsBillingAndPlans.vue'
import Navbar from '@/views/front-pages/front-page-navbar.vue'
import { useConfigStore } from '@core/stores/config'
import PricingPlans from "@/views/front-pages/landing-page/pricing-plans.vue"

const store = useConfigStore()

store.skin = 'default'
definePage({
  meta: {
    layout: 'blank',
    public: true,
  },
})

const activeSectionId = ref()
const refHome = ref()
const refFeatures = ref()
const refTeam = ref()
const refContact = ref()
const refFaq = ref()

useIntersectionObserver([
  refHome,
  refFeatures,
  refTeam,
  refContact,
  refFaq,
], ([{ isIntersecting, target }]) => {
  if (isIntersecting)
    activeSectionId.value = target.id
}, { threshold: 0.25 })
</script>

<template>
  <div class="landing-page-wrapper">
    <Navbar :active-id="activeSectionId" />

    <!-- 👉 المحتوى الرئيسي -->
    <VContainer class="page-content-wrapper mt-16">
      <VCol cols="12 mt-8">
        <AccountSettingsAccount />
      </VCol>
      <VCol cols="12">
        <AccountSettingsSecurity />
      </VCol>
      <VCol cols="12">
        <AccountSettingsBillingAndPlans></AccountSettingsBillingAndPlans>
      </VCol>
    </VContainer>

    <Footer />
  </div>
</template>

<style lang="scss">
.landing-page-wrapper {
  min-height: 100vh;
  display: flex;
  flex-direction: column;
}

.page-content-wrapper {
  flex: 1;
  display: flex;
  flex-direction: column;
  padding-top: 2rem;
  padding-bottom: 2rem;
}

.content-section {
  background: rgb(var(--v-theme-surface));
  border-radius: 8px;
  padding: 2rem;
  margin-bottom: 2rem;
  box-shadow: 0 2px 12px rgba(0, 0, 0, 0.08);

  &:last-child {
    margin-bottom: 0;
  }
}

// تحسينات للشاشات الصغيرة
@media (max-width: 960px) and (min-width: 600px) {
  .landing-page-wrapper {
    .v-container {
      padding-inline: 2rem !important;
    }
  }

  .content-section {
    padding: 1.5rem;
    margin-bottom: 1.5rem;
  }
}

@media (max-width: 599px) {
  .content-section {
    padding: 1rem;
    margin-bottom: 1rem;
    border-radius: 6px;
  }

  .page-content-wrapper {
    padding-top: 1rem;
    padding-bottom: 1rem;
  }
}

// تحسينات إضافية للتنسيق
.v-container {
  max-width: 1280px !important;
}

// لضمان أن المحتوى في المنتصف تماماً
.v-row {
  min-height: 100%;
}

.v-col {
  display: flex;
  flex-direction: column;
}
</style>
