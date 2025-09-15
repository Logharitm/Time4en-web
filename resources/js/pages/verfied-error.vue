<script setup>
import errorImg from '@images/pages/auth-v2-two-step-illustration-light.png'
import miscMaskDark from '@images/pages/misc-mask-dark.png'
import miscMaskLight from '@images/pages/misc-mask-light.png'
import { useGenerateImageVariant } from '@core/composable/useGenerateImageVariant'
import { ref } from 'vue'
import axios from 'axios'
import { useRouter } from "vue-router"

const router = useRouter()

definePage({
  alias: '/verified-error',
  meta: {
    layout: 'blank',
    public: true,
  },
})

const authThemeMask = useGenerateImageVariant(miscMaskLight, miscMaskDark)
const loading = ref(false)
const message = ref('')

// 👉 Resend verification email
const resendVerification = async () => {
  loading.value = true
  message.value = ''
  try {
    const response = await axios.post('/api/email/verification-notification')

    message.value = response.data.message || 'تم إرسال رابط التفعيل مرة أخرى ✅'
  } catch (err) {
    message.value = 'حدث خطأ أثناء إعادة الإرسال ❌'
  } finally {
    loading.value = false
  }
}

const openView = () => {
  console.log('Going to login...')
  router.push({ name: 'home-dashboard' })
}
</script>

<template>
  <div class="misc-wrapper">
    <!-- 👉 Error Header -->
    <ErrorHeader
      status-code="❌"
      title="فشل في تفعيل البريد الإلكتروني"
      description="الرابط غير صالح أو منتهي الصلاحية."
    />

    <!-- 👉 Resend Button -->
    <VBtn
      class="mb-6"
      color="error"
      :loading="loading"
      @click="resendVerification"
    >
      إعادة إرسال رابط التفعيل
    </VBtn>

    <!-- 👉 Back Home -->
    <VBtn
      class="mb-11"
      @click="openView"
    >
      العودة للرئيسية
    </VBtn>

    <!-- 👉 Message -->
    <p
      v-if="message"
      class="text-center mt-4"
    >
      {{ message }}
    </p>

    <!-- 👉 Image -->
    <div class="misc-avatar w-100 text-center">
      <VImg
        :src="errorImg"
        alt="verification failed"
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
