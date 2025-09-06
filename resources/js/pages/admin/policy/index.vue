<script setup>
import { ref, onMounted, watch } from 'vue'

// Toast Management
const showToast = ref(false)
const message = ref('')
const color = ref('success')

const triggerToast = (msg, type = 'success') => {
  message.value = msg
  color.value = type
  showToast.value = true
}

// Active Tab and Data
const activeTab = ref('privacy')
const policies = ref({
  privacy: { content: '', content_en: '' },
  terms: { content: '', content_en: '' }
})
const loading = ref(false)

// Function to fetch policy data
const fetchPolicy = async (type) => {
  loading.value = true
  try {
    const response = await $api(`/policies/${type}`, { method: 'GET' })
    if (response.status === 'success') {
      policies.value[type] = response.data
    }
  } catch (err) {
    triggerToast('حدث خطأ أثناء جلب البيانات', 'error')
    console.error(err)
  } finally {
    loading.value = false
  }
}

// Function to update policy data
const updatePolicy = async (type) => {
  try {
    const { content, content_en } = policies.value[type]
    await $api(`/policies/${type}`, { method: 'POST', body: { content, content_en } })
    triggerToast('تم تعديل البيانات بنجاح', 'success')
  } catch (err) {
    triggerToast('حدث خطأ أثناء تعديل البيانات', 'error')
    console.error(err)
  }
}

// Fetch data on component mount and tab change
onMounted(() => fetchPolicy(activeTab.value))
watch(activeTab, (newTab) => fetchPolicy(newTab))
</script>

<template>
  <section>
    <VCard class="mb-6">
      <VCardItem class="pb-4">
        <VCardTitle>الشروط والسياسات</VCardTitle>
      </VCardItem>

      <VTabs v-model="activeTab" grow>
        <VTab value="privacy">سياسة الخصوصية</VTab>
        <VTab value="terms">الشروط والأحكام</VTab>
      </VTabs>

      <VCardText>
        <VForm>
          <VTextarea
            v-model="policies[activeTab].content"
            label="المحتوى (AR)"
            rows="10"
            class="mb-4"
            :disabled="loading"
          />
          <VTextarea
            v-model="policies[activeTab].content_en"
            label="المحتوى (EN)"
            rows="10"
            :disabled="loading"
          />
        </VForm>
      </VCardText>

      <VCardActions class="px-6 pb-4">
        <VSpacer />
        <VBtn
          color="primary"
          variant="flat"
          @click="updatePolicy(activeTab)"
          :loading="loading"
        >
          تعديل
        </VBtn>
      </VCardActions>
    </VCard>

    <VSnackbar v-model="showToast" :color="color" location="top end" timeout="5000">
      <template #prepend>
        <VIcon v-if="color === 'success'" icon="tabler-check" />
        <VIcon v-else-if="color === 'error'" icon="tabler-alert-circle" />
        <VIcon v-else icon="tabler-info-circle" />
      </template>
      {{ message }}
    </VSnackbar>
  </section>
</template>
