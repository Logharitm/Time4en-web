<script setup>
import { useGenerateImageVariant } from '@core/composable/useGenerateImageVariant'
import { VNodeRenderer } from '@layouts/components/VNodeRenderer'
import { themeConfig } from '@themeConfig'
import authV2ForgotPasswordIllustrationDark from '@images/pages/auth-v2-forgot-password-illustration-dark.png'
import authV2ForgotPasswordIllustrationLight from '@images/pages/auth-v2-forgot-password-illustration-light.png'
import authV2MaskDark from '@images/pages/misc-mask-dark.png'
import authV2MaskLight from '@images/pages/misc-mask-light.png'
import { ref } from 'vue'
import { useI18n } from 'vue-i18n'

const { t } = useI18n()

const email = ref('')
const message = ref('')
const loading = ref(false)

const authThemeImg = useGenerateImageVariant(authV2ForgotPasswordIllustrationLight, authV2ForgotPasswordIllustrationDark)
const authThemeMask = useGenerateImageVariant(authV2MaskLight, authV2MaskDark)

definePage({
  meta: {
    layout: 'blank',
    unauthenticatedOnly: true,
  },
})

const submitForgotPassword = async () => {
  loading.value = true
  message.value = ''
  try {
    const response = await $api('/forgot-password', {
      method: 'POST',
      body: {
        email: email.value,
      },
    })

    message.value = response.message || t('forgotPassword.success')
  } catch (error) {
    if (error?.errors?.email) {
      message.value = error.errors.email[0]
    } else {
      message.value = t('forgotPassword.unexpectedError')
    }
  } finally {
    loading.value = false
  }
}
</script>

<template>
  <RouterLink to="/">
    <div class="auth-logo d-flex align-center gap-x-3">
      <VNodeRenderer :nodes="themeConfig.app.logo" />
      <h1 class="auth-title">
        {{ themeConfig.app.title }}
      </h1>
    </div>
  </RouterLink>

  <VRow class="auth-wrapper bg-surface" no-gutters>
    <VCol md="8" class="d-none d-md-flex">
      <div class="position-relative bg-background w-100 me-0">
        <div
          class="d-flex align-center justify-center w-100 h-100"
          style="padding-inline: 150px;"
        >
          <VImg
            max-width="468"
            :src="authThemeImg"
            class="auth-illustration mt-16 mb-2"
          />
        </div>
        <img
          class="auth-footer-mask"
          :src="authThemeMask"
          alt="footer-mask"
          height="280"
          width="100"
        />
      </div>
    </VCol>

    <VCol cols="12" md="4" class="d-flex align-center justify-center">
      <VCard flat :max-width="500" class="mt-12 mt-sm-0 pa-4">
        <VCardText>
          <h4 class="text-h4 mb-1">
            {{ t('forgotPassword.title') }}
          </h4>
          <p class="mb-0">
            {{ t('forgotPassword.description') }}
          </p>
        </VCardText>

        <VCardText>
          <VForm @submit.prevent="submitForgotPassword">
            <VRow>
              <VCol cols="12">
                <AppTextField
                  v-model="email"
                  autofocus
                  :label="t('forgotPassword.emailLabel')"
                  type="email"
                  :placeholder="t('forgotPassword.emailPlaceholder')"
                />
              </VCol>

              <VCol cols="12">
                <VBtn block type="submit" :loading="loading">
                  {{ t('forgotPassword.submit') }}
                </VBtn>
              </VCol>

              <VCol v-if="message" cols="12">
                <p class="text-center text-success">
                  {{ message }}
                </p>
              </VCol>

              <VCol cols="12">
                <RouterLink
                  class="d-flex align-center justify-center"
                  :to="{ name: 'login' }"
                >
                  <VIcon
                    icon="tabler-chevron-left"
                    size="20"
                    class="me-1 flip-in-rtl"
                  />
                  <span>{{ t('forgotPassword.backToLogin') }}</span>
                </RouterLink>
              </VCol>
            </VRow>
          </VForm>
        </VCardText>
      </VCard>
    </VCol>
  </VRow>
</template>

<style lang="scss">
@use "@core-scss/template/pages/page-auth.scss";
</style>
