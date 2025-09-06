<script setup>
import { ref, onMounted } from 'vue'

// Toast
const showToast = ref(false)
const message = ref('')
const color = ref('success')

const triggerToast = (msg, type = 'success') => {
  message.value = msg
  color.value = type
  showToast.value = true
}

// Contact info
const contactInfo = ref({
  id: null,
  address: '',
  address_en: '',
  phone: '',
  email: '',
  facebook: '',
  twitter: '',
  instagram: '',
  whatsapp: ''
})

const loading = ref(true)

// Fetch contact info
const fetchContactInfo = async () => {
  loading.value = true
  try {
    const response = await $api('/contact-info', { method: 'GET' })
    if (response.status === 'success') {
      contactInfo.value = response.data
    }
  } catch (err) {
    triggerToast('حدث خطأ أثناء جلب بيانات الاتصال', 'error')
    console.error(err)
  } finally {
    loading.value = false
  }
}

// Update contact info
const updateContactInfo = async () => {
  try {
    await $api('/contact-info', {
      method: 'POST',
      body: contactInfo.value
    })
    triggerToast('تم تعديل بيانات الاتصال بنجاح', 'success')
  } catch (err) {
    triggerToast('حدث خطأ أثناء تعديل بيانات الاتصال', 'error')
    console.error(err)
  }
}

onMounted(fetchContactInfo)
</script>

<template>
  <section>
    <VCard class="mb-6">
      <VCardItem class="pb-4">
        <VCardTitle>بيانات الاتصال</VCardTitle>
      </VCardItem>

      <VCardText>
        <VForm>
          <VTextField v-model="contactInfo.address" label="العنوان (AR)" />
          <VTextField v-model="contactInfo.address_en" label="العنوان (EN)" />
          <VTextField v-model="contactInfo.phone" label="رقم الهاتف" />
          <VTextField v-model="contactInfo.email" label="البريد الإلكتروني" />
          <VTextField v-model="contactInfo.facebook" label="Facebook" />
          <VTextField v-model="contactInfo.twitter" label="Twitter" />
          <VTextField v-model="contactInfo.instagram" label="Instagram" />
          <VTextField v-model="contactInfo.whatsapp" label="WhatsApp" />
        </VForm>
      </VCardText>

      <VCardActions class="px-6 pb-4">
        <VSpacer />
        <VBtn color="primary" variant="flat" @click="updateContactInfo">تعديل</VBtn>
      </VCardActions>
    </VCard>

    <!-- Snackbar -->
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
<style>
.v-input--horizontal
{
  grid-template-areas:
        "prepend control append"
        "a messages b";
  grid-template-columns: max-content minmax(0, 1fr) max-content;
  grid-template-rows: 1fr auto;
  margin: 20px 0 !important;
}
</style>
