<script setup>
import { VForm } from 'vuetify/components/VForm'
import { VNodeRenderer } from '@layouts/components/VNodeRenderer'
import { themeConfig } from '@themeConfig'
import { useGenerateImageVariant } from '@core/composable/useGenerateImageVariant'
import authV2RegisterIllustrationBorderedDark from '@images/pages/auth-v2-register-illustration-bordered-dark.png'
import authV2RegisterIllustrationBorderedLight from '@images/pages/auth-v2-register-illustration-bordered-light.png'
import authV2RegisterIllustrationDark from '@images/pages/auth-v2-register-illustration-dark.png'
import authV2RegisterIllustrationLight from '@images/pages/auth-v2-register-illustration-light.png'
import authV2MaskDark from '@images/pages/misc-mask-dark.png'
import authV2MaskLight from '@images/pages/misc-mask-light.png'

const imageVariant = useGenerateImageVariant(
  authV2RegisterIllustrationLight,
  authV2RegisterIllustrationDark,
  authV2RegisterIllustrationBorderedLight,
  authV2RegisterIllustrationBorderedDark,
  true,
)

const authThemeMask = useGenerateImageVariant(authV2MaskLight, authV2MaskDark)

definePage({
  meta: {
    layout: 'blank',
    unauthenticatedOnly: true,
  },
})

const form = ref({
  name: '',
  email: '',
  password: '',
  password_confirmation: '',
  privacyPolicies: false,
})

const isPasswordVisible = ref(false)
const refVForm = ref()
const router = useRouter()

const errors = ref({
  name: undefined,
  email: undefined,
  password: undefined,
})

const generalError = ref(null)

const register = async () => {
  try {
    const res = await $api('/register', {
      method: 'POST',
      body: {
        name: form.value.name,
        email: form.value.email,
        password: form.value.password,
        password_confirmation: form.value.password_confirmation,
      },
      onResponseError({ response }) {
        if (response._data?.errors) {
          // السيرفر بيرجع errors كمصفوفة [{field, message}]
          const fieldErrors = {}
          response._data.errors.forEach(e => {
            if (!fieldErrors[e.field]) fieldErrors[e.field] = []
            fieldErrors[e.field].push(e.message)
          })
          errors.value = fieldErrors
        } else if (response._data?.message) {
          errors.value = { email: [response._data.message] }
        }
      },
    })

    // دعم الحالتين: res أو res.data
    const payload = res?.status ?? res
    if (payload === 'success') {
      router.replace('/login')
    }
  } catch (err) {
    console.error('Register error:', err)
    errors.value = { email: ['Something went wrong, please try again.'] }
  }
}



const onSubmit = () => {
  refVForm.value?.validate().then(({ valid: isValid }) => {
    if (isValid && form.value.privacyPolicies)
      register()
  })
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

  <VRow
    no-gutters
    class="auth-wrapper bg-surface"
  >
    <VCol
      md="8"
      class="d-none d-md-flex"
    >
      <div class="position-relative bg-background w-100 me-0">
        <div
          class="d-flex align-center justify-center w-100 h-100"
          style="padding-inline: 100px;"
        >
          <VImg
            max-width="500"
            :src="imageVariant"
            class="auth-illustration mt-16 mb-2"
          />
        </div>
        <img
          class="auth-footer-mask"
          :src="authThemeMask"
          alt="auth-footer-mask"
          height="280"
          width="100"
        >
      </div>
    </VCol>

    <VCol
      cols="12"
      md="4"
      class="auth-card-v2 d-flex align-center justify-center"
    >
      <VCard
        flat
        :max-width="500"
        class="mt-12 mt-sm-0 pa-4"
      >
        <VCardText>
          <h4 class="text-h4 mb-1">
            تسجيل حساب جديد
          </h4>
        </VCardText>

        <VCardText>
          <VForm
            ref="refVForm"
            @submit.prevent="onSubmit"
          >
            <VRow>
              <VCol cols="12">
                <AppTextField
                  v-model="form.name"
                  label="الاسم"
                  :rules="[requiredValidator]"
                  :error-messages="errors.name"
                  autofocus
                />
              </VCol>

              <VCol cols="12">
                <AppTextField
                  v-model="form.email"
                  label="البريد الإلكتروني"
                  type="email"
                  :rules="[requiredValidator, emailValidator]"
                  :error-messages="errors.email"
                />
              </VCol>

              <VCol cols="12">
                <AppTextField
                  v-model="form.password"
                  label="كلمة المرور"
                  :type="isPasswordVisible ? 'text' : 'password'"
                  :rules="[requiredValidator]"
                  :error-messages="errors.password"
                  autocomplete="new-password"
                  placeholder="••••••••"
                  :append-inner-icon="isPasswordVisible ? 'tabler-eye-off' : 'tabler-eye'"
                  @click:append-inner="isPasswordVisible = !isPasswordVisible"
                />
              </VCol>

              <VCol cols="12">
                <AppTextField
                  v-model="form.password_confirmation"
                  label="تأكيد كلمة المرور"
                  :type="isPasswordVisible ? 'text' : 'password'"
                  :rules="[requiredValidator]"
                  autocomplete="new-password"
                  placeholder="••••••••"
                />
              </VCol>

              <!-- اتفاقية الخصوصية -->
              <VCol cols="12">
                <div class="d-flex align-center my-4">
                  <VCheckbox
                    v-model="form.privacyPolicies"
                    inline
                  />
                  <VLabel for="privacy-policy">
                    <span class="me-1">أوافق على</span>
                    <a
                      href="javascript:void(0)"
                      class="text-primary"
                    >سياسة الخصوصية والشروط</a>
                  </VLabel>
                </div>
              </VCol>

              <!-- عرض رسالة الخطأ العامة -->
              <VCol
                v-if="generalError"
                cols="12"
              >
                <VAlert
                  type="error"
                  border="start"
                  class="mb-4"
                >
                  {{ generalError }}
                </VAlert>
              </VCol>

              <VCol cols="12">
                <VBtn
                  block
                  type="submit"
                >
                  تسجيل
                </VBtn>
              </VCol>

              <VCol
                cols="12"
                class="text-center text-base"
              >
                <span>لديك حساب بالفعل؟</span>
                <RouterLink
                  class="text-primary ms-1"
                  :to="{ name: 'login' }"
                >
                  تسجيل الدخول
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
@use "@core-scss/template/pages/page-auth";
</style>
