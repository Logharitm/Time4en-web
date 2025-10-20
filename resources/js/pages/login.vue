<script setup>
import { VForm } from 'vuetify/components/VForm'
import AuthProvider from '@/views/pages/authentication/AuthProvider.vue'
import { useGenerateImageVariant } from '@core/composable/useGenerateImageVariant'
import authV2LoginIllustrationBorderedDark from '@images/pages/auth-v2-login-illustration-bordered-dark.png'
import authV2LoginIllustrationBorderedLight from '@images/pages/Logo.png'
import authV2LoginIllustrationDark from '@images/pages/auth-v2-login-illustration-dark.png'
import authV2LoginIllustrationLight from '@images/pages/Logo.png'
import authV2MaskDark from '@images/pages/misc-mask-dark.png'
import authV2MaskLight from '@images/pages/misc-mask-light.png'
import { VNodeRenderer } from '@layouts/components/VNodeRenderer'
import { themeConfig } from '@themeConfig'
import { useAbility } from '@casl/vue'

const authThemeImg = useGenerateImageVariant(
  authV2LoginIllustrationLight,
  authV2LoginIllustrationDark,
  authV2LoginIllustrationBorderedLight,
  authV2LoginIllustrationBorderedDark,
  true,
)

const authThemeMask = useGenerateImageVariant(authV2MaskLight, authV2MaskDark)

definePage({
  meta: {
    layout: 'blank',
    unauthenticatedOnly: true,
  },
})

const isPasswordVisible = ref(false)
const route = useRoute()
const router = useRouter()
const ability = useAbility()

// أخطاء الحقول
const errors = ref({
  email: undefined,
  password: undefined,
})

// رسالة عامة
const generalError = ref('')
const isLoading = ref(false)

const refVForm = ref()

const credentials = ref({
  email: '',
  password: '',
})

const rememberMe = ref(false)

const login = async () => {
  // إعادة تعيين الأخطاء قبل كل محاولة تسجيل دخول
  errors.value = { email: undefined, password: undefined }
  generalError.value = ''
  isLoading.value = true

  try {
    const res = await $api('/login', {
      method: 'POST',
      body: {
        email: credentials.value.email,
        password: credentials.value.password,
      },
    })

    const payload = res?.data ?? res

    // التحقق من وجود token في الرد
    if (!payload.accessToken) {
      generalError.value = 'Login failed. No access token received.'

      return
    }
    if (payload.role === 'admin') {
      
    } else {
      return
    }
    const accessToken = payload.accessToken

    const userFromApi = payload.userData ?? {
      name: payload.name,
      role: payload.role,
      avatar: payload.avatar ?? null,
      email: credentials.value.email,
    }

    // ضبط الصلاحيات
    let userAbilities = []
    if (userFromApi.role === 'admin') {
      userAbilities = [{ action: 'manage', subject: 'all' }]
    } else {
      userAbilities = [{ action: 'read', subject: 'Home' }]
    }

    // حفظ الحالة
    useCookie('accessToken').value = accessToken
    useCookie('userData').value = userFromApi
    useCookie('userAbilityRules').value = userAbilities
    ability.update(userAbilities)

    // إعادة التوجيه
    const target = route.query.to ? String(route.query.to) : '/'
    if (router.currentRoute.value.fullPath !== target) {
      await nextTick(() => router.replace(target))
    }
  } catch (err) {
    console.error('Login error:', err)

    // معالجة الأخطاء من الـ API
    if (err.response?._data) {
      const errorData = err.response._data

      // إذا كان هناك رسالة خطأ من الـ API
      if (errorData.message) {
        generalError.value = errorData.message
      }

      // إذا كان هناك أخطاء في الحقول
      if (errorData.errors && Object.keys(errorData.errors).length > 0) {
        errors.value = { ...errors.value, ...errorData.errors }
      }

      // إذا كان status هو error وبه message
      if (errorData.status === 'error' && errorData.message) {
        generalError.value = errorData.message
      }
    } else if (err.message) {
      generalError.value = err.message
    } else {
      generalError.value = 'Something went wrong, please try again.'
    }
  } finally {
    isLoading.value = false
  }
}

const onSubmit = () => {
  // إعادة تعيين الأخطاء قبل التحقق
  errors.value = { email: undefined, password: undefined }
  generalError.value = ''

  refVForm.value?.validate().then(({ valid: isValid }) => {
    if (isValid) {
      login()
    }
  })
}

// إعادة تعيين الأخطاء عند تغيير المدخلات
watch([() => credentials.value.email, () => credentials.value.password], () => {
  if (generalError.value) {
    generalError.value = ''
  }
  if (errors.value.email || errors.value.password) {
    errors.value = { email: undefined, password: undefined }
  }
})
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
          style="padding-inline: 6.25rem;"
        >
          <VImg
            max-width="613"
            :src="authThemeImg"
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
           {{ $t('welcome_en')}}
            <span class="text-capitalize">{{ themeConfig.app.title }}</span>
          </h4>
        </VCardText>

        <VCardText>
          <VForm
            ref="refVForm"
            @submit.prevent="onSubmit"
          >
            <VRow>
              <!-- email -->
              <VCol cols="12">
                <AppTextField
                  v-model="credentials.email"
                  :label="$t('email')"
                  placeholder="johndoe@email.com"
                  type="email"
                  autofocus
                  :rules="[requiredValidator, emailValidator]"
                  :error-messages="errors.email"
                />
              </VCol>

              <!-- password -->
              <VCol cols="12">
                <AppTextField
                  v-model="credentials.password"
                  :label="$t('password')"
                  placeholder="············"
                  :rules="[requiredValidator]"
                  :type="isPasswordVisible ? 'text' : 'password'"
                  autocomplete="password"
                  :error-messages="errors.password"
                  :append-inner-icon="isPasswordVisible ? 'tabler-eye-off' : 'tabler-eye'"
                  @click:append-inner="isPasswordVisible = !isPasswordVisible"
                />

                <!-- ✅ عرض رسالة الخطأ العامة -->
                <VAlert
                  v-if="generalError"
                  color="error"
                  variant="tonal"
                  class="mt-4"
                >
                  <span class="text-error">{{ generalError }}</span>
                </VAlert>

                <div class="d-flex align-center flex-wrap justify-space-between my-6">
                  <VCheckbox
                    v-model="rememberMe"
                    :label="$t('remember_me')"
                  />
                  <!-- <RouterLink
                    class="text-primary ms-2 mb-1"
                    :to="{ name: 'forgot-password' }"
                  >
                    {{ $t('forget_password')}}
                  </RouterLink> -->
                </div>

                <VBtn
                  block
                  type="submit"
                  :loading="isLoading"
                  :disabled="isLoading"
                >
                  {{ isLoading ? $t('Register') : $t('Login') }}
                </VBtn>
              </VCol>

              <!-- create account -->
              <!-- <VCol
                cols="12"
                class="text-center text-base"
              >
                <span class="d-inline-block">{{ $t('dont have an account') }}</span>
                <RouterLink
                  class="text-primary ms-1 d-inline-block"
                  :to="{ name: 'register' }"
                >
                  {{ $t('Sign up') }}
                </RouterLink>
              </VCol> -->
            </VRow>
          </VForm>
        </VCardText>
      </VCard>
    </VCol>
  </VRow>
</template>

<style lang="scss">
@use "@core-scss/template/pages/page-auth";

// تحسينات للرسائل
.text-error {
  color: rgb(var(--v-theme-error));
}

.auth-card-v2 {
  .v-alert {
    margin-top: 1rem;
  }
}
</style>
