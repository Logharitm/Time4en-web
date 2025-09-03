<script setup>
import { ref } from 'vue'

// 👉 Password fields state
const passwordFields = ref([
  { key: 'current_password', label: 'Current Password', value: '', visible: false },
  { key: 'new_password', label: 'New Password', value: '', visible: false },
  { key: 'new_password_confirmation', label: 'Confirm New Password', value: '', visible: false },
])

// 👉 Toast state
const showToast = ref(false)
const message = ref('')
const color = ref('success')


// 👉 Toast trigger
const triggerToast = (msg, type = 'success') => {
  message.value = msg
  color.value = type
  showToast.value = true
}

// 👉 Submit password change
const changePassword = async () => {
  try {
    const payload = {}

    passwordFields.value.forEach(field => {
      payload[field.key] = field.value
    })

    const res = await $api('/change-password', {
      method: 'POST',
      body: payload,
    })

    triggerToast(res.data.message || 'Password updated successfully!', 'success')

    // Reset fields after success
    passwordFields.value.forEach(f => f.value = '')
  } catch (err) {
    console.error('Error updating password:', err)
    triggerToast(err?.response?._data?.message || 'Failed to update password!', 'error')
  }
}
</script>

<template>
  <VSnackbar
    v-model="showToast"
    :color="color"
    location="top end"
    timeout="5000"
  >
    <template #prepend>
      <VIcon
        v-if="color === 'success'"
        icon="tabler-check"
      />
      <VIcon
        v-else-if="color === 'error'"
        icon="tabler-alert-circle"
      />
      <VIcon
        v-else
        icon="tabler-info-circle"
      />
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
      <VCard title="Change Password">
        <VForm @submit.prevent="changePassword">
          <VCardText class="pt-0">
            <VRow>
              <VCol
                v-for="field in passwordFields"
                :key="field.key"
                cols="12"
                md="6"
              >
                <AppTextField
                  v-model="field.value"
                  :type="field.visible ? 'text' : 'password'"
                  :append-inner-icon="field.visible ? 'tabler-eye-off' : 'tabler-eye'"
                  :label="field.label"
                  autocomplete="on"
                  placeholder="············"
                  @click:append-inner="field.visible = !field.visible"
                />
              </VCol>
            </VRow>
          </VCardText>

          <VCardText class="d-flex flex-wrap gap-4">
            <VBtn type="submit">
              Save changes
            </VBtn>
            <VBtn
              type="reset"
              color="secondary"
              variant="tonal"
            >
              Reset
            </VBtn>
          </VCardText>
        </VForm>
      </VCard>
    </VCol>
  </VRow>
</template>

<style scoped>
.card-list {
  --v-card-list-gap: 16px;
}
</style>
