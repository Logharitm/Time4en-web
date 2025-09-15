<script setup>
import successImg from '@images/pages/auth-v2-two-step-illustration-light.png' // هات صورة مناسبة للنجاح
import miscMaskDark from '@images/pages/misc-mask-dark.png'
import miscMaskLight from '@images/pages/misc-mask-light.png'
import { useGenerateImageVariant } from '@core/composable/useGenerateImageVariant'

definePage({
  alias: '/verified-success',
  meta: {
    layout: 'blank',
    public: true,
  },
})

const authThemeMask = useGenerateImageVariant(miscMaskLight, miscMaskDark)
</script>

<template>
  <div class="misc-wrapper">
    <!-- 👉 Success Header -->
    <ErrorHeader
      status-code="✅"
      title="تم تفعيل بريدك الإلكتروني بنجاح 🎉"
      description="يمكنك الآن استخدام حسابك بشكل كامل."
    />

    <!-- 👉 Back to Home -->
    <VBtn
      class="mb-11"
      color="success"
      to="/home/dashboard"
    >
      الذهاب إلى الرئيسية
    </VBtn>

    <!-- 👉 Image -->
    <div class="misc-avatar w-100 text-center">
      <VImg
        :src="successImg"
        alt="email verified"
        :max-height="$vuetify.display.smAndDown ? 300 : 450"
        class="mx-auto"
      />
    </div>

    <img
      class="misc-footer-img d-none d-md-block"
      :src="authThemeMask"
      alt="misc-footer-img"
      height="320"
    >
  </div>
</template>

<style lang="scss">
@use "@core-scss/template/pages/misc.scss";
</style>
