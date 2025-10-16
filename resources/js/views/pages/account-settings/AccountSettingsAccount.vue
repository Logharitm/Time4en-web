<script setup>
import { ref, onMounted } from 'vue'
import { useI18n } from 'vue-i18n'

const { t } = useI18n()
const refInputEl = ref()
const isLoading = ref(false)
const isSaving = ref(false)

// 👉 Toast state
const showToast = ref(false)
const message = ref('')
const color = ref('success')

const accountDataLocal = ref({
  name: '',
  email: '',
  language: '',
  role: '',
  avatar: null,
  avatarPreview: null,
})

const languages = ['en', 'ar']

// 👉 Fetch user data
const fetchUser = async () => {
  isLoading.value = true
  try {
    const res = await $api('/user', { method: 'GET' })
    const user = res?.data?.user

    if (user) {
      accountDataLocal.value.name = user.name
      accountDataLocal.value.email = user.email
      accountDataLocal.value.language = user.language || 'en'
      accountDataLocal.value.role = user.role
      accountDataLocal.value.avatar = user.avatar
    }
  } catch (err) {
    console.error('Error fetching user:', err)
  } finally {
    isLoading.value = false
  }
}

// 👉 Toast trigger
const triggerToast = (msg, type = 'success') => {
  message.value = msg
  color.value = type
  showToast.value = true
}

// 👉 Save changes
const saveProfile = async () => {
  isSaving.value = true
  try {
    const formData = new FormData()

    formData.append('name', accountDataLocal.value.name)
    formData.append('email', accountDataLocal.value.email)
    formData.append('language', 'ar')

    if (accountDataLocal.value.avatar instanceof File) {
      formData.append('avatar', accountDataLocal.value.avatar)
    } else {
      formData.append('avatar', '')
    }

    const res = await $api('/update-profile', {
      method: 'POST',
      body: formData,
    })

    triggerToast(t('profile.successMessage'), 'success')

    if (res?.data?.user) {
      useCookie('userData').value = {
        name: res.data.user.name,
        role: res.data.user.role,
        email: res.data.user.email,
        avatar: res.data.user.avatar || null,
      }
    }

  } catch (err) {
    console.error('Error updating profile:', err)
    triggerToast(t('profile.errorMessage'), 'error')
  } finally {
    isSaving.value = false
  }
}

// 👉 Handle avatar upload
const changeAvatar = file => {
  const { files } = file.target
  if (files && files.length) {
    accountDataLocal.value.avatar = files[0]
    accountDataLocal.value.avatarPreview = URL.createObjectURL(files[0])
  }
}

// 👉 Reset avatar preview
const resetAvatar = () => {
  accountDataLocal.value.avatar = null
  accountDataLocal.value.avatarPreview = null
  refInputEl.value.value = ''
}

onMounted(() => {
  fetchUser()
})
</script>

<template>
  <VSnackbar
    v-model="showToast"
    :color="color"
    location="top end"
    timeout="5000"
  >
    <template #prepend>
      <VIcon v-if="color === 'success'" icon="tabler-check" />
      <VIcon v-else-if="color === 'error'" icon="tabler-alert-circle" />
      <VIcon v-else icon="tabler-info-circle" />
    </template>

    {{ message }}

    <template #actions>
      <VBtn
        icon
        variant="text"
        color="white"
        @click="showToast = false"
      >
        <VIcon icon="tabler-x" />
      </VBtn>
    </template>
  </VSnackbar>

  <VRow>
    <VCol cols="12">
      <VCard>
        <VCardText v-if="isLoading">
          <VProgressCircular indeterminate color="primary" />
        </VCardText>

        <VCardText v-else class="d-flex">
          <!-- 👉 Avatar -->
          <VAvatar
            rounded
            size="100"
            class="me-6"
            :image="accountDataLocal.avatarPreview || accountDataLocal.avatar || '/default-avatar.png'"
          />

          <!-- 👉 Upload Photo -->
          <form class="d-flex flex-column justify-center gap-4">
            <div class="d-flex flex-wrap gap-4">
              <VBtn color="primary" size="small" @click="refInputEl?.click()">
                <VIcon icon="tabler-cloud-upload" class="d-sm-none" />
                <span class="d-none d-sm-block">{{ t('profile.uploadPhoto') }}</span>
              </VBtn>

              <input
                ref="refInputEl"
                type="file"
                accept=".jpeg,.png,.jpg,.gif"
                hidden
                @input="changeAvatar"
              >

              <VBtn
                type="reset"
                size="small"
                color="secondary"
                variant="tonal"
                @click="resetAvatar"
              >
                <span class="d-none d-sm-block">{{ t('profile.cancel') }}</span>
                <VIcon icon="tabler-refresh" class="d-sm-none" />
              </VBtn>
            </div>
            <p class="text-body-1 mb-0">
              {{ t('profile.allowedFormats') }}
            </p>
          </form>
        </VCardText>

        <VCardText class="pt-2">
          <!-- 👉 Form -->
          <VForm class="mt-3" @submit.prevent="saveProfile">
            <VRow>
              <!-- 👉 Name -->
              <VCol cols="12" md="6">
                <AppTextField
                  v-model="accountDataLocal.name"
                  :label="t('profile.name')"
                  :placeholder="t('profile.enterName')"
                />
              </VCol>

              <!-- 👉 Email -->
              <VCol cols="12" md="6">
                <AppTextField
                  v-model="accountDataLocal.email"
                  :label="t('profile.email')"
                  :placeholder="t('profile.enterEmail')"
                  type="email"
                />
              </VCol>

              <!-- 👉 Form Actions -->
              <VCardText cols="12" class="d-flex flex-wrap gap-4">
                <VBtn type="submit" :loading="isSaving">
                  {{ t('profile.saveChanges') }}
                </VBtn>
                <VBtn
                  color="secondary"
                  variant="tonal"
                  :disabled="isSaving"
                  @click.prevent="fetchUser"
                >
                  {{ t('profile.cancelChanges') }}
                </VBtn>
              </VCardText>
            </VRow>
          </VForm>
        </VCardText>
      </VCard>
    </VCol>
  </VRow>
</template>
