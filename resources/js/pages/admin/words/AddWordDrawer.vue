<script setup>
import { ref, onMounted, nextTick } from 'vue'
import { PerfectScrollbar } from 'vue3-perfect-scrollbar'

const props = defineProps({
  isDrawerOpen: {
    type: Boolean,
    required: true,
  },
})

const emit = defineEmits(['update:isDrawerOpen', 'folder-data'])

// Form
const isFormValid = ref(false)
const refForm = ref()

// Fields
const userId = ref(null)
const name = ref('')
const description = ref('')

// Options
const users = ref([])

// Fetch users with role=user
onMounted(async () => {
  try {
    const resp = await $api('/users?role=user', { method: 'GET' })
    users.value = resp.data || []
  } catch (err) {
    console.error('Error fetching users', err)
  }
})

// Close drawer
const closeDrawer = () => {
  emit('update:isDrawerOpen', false)
  nextTick(() => {
    refForm.value?.reset()
    refForm.value?.resetValidation()
    userId.value = null
    name.value = ''
    description.value = ''
  })
}

// Submit
const onSubmit = () => {
  refForm.value?.validate().then(({ valid }) => {
    if (valid) {
      const formData = new FormData()
      formData.append('user_id', userId.value)
      formData.append('name', name.value)
      formData.append('description', description.value || '')

      emit('folder-data', formData)
      closeDrawer()
    }
  })
}
</script>

<template>
  <VNavigationDrawer
    temporary
    :width="400"
    location="end"
    class="scrollable-content"
    :model-value="props.isDrawerOpen"
    @update:model-value="val => emit('update:isDrawerOpen', val)"
  >
    <AppDrawerHeaderSection title="إضافة مجلد جديد" @cancel="closeDrawer" />
    <VDivider />
    <PerfectScrollbar :options="{ wheelPropagation: false }">
      <VCard flat>
        <VCardText>
          <VForm ref="refForm" v-model="isFormValid" @submit.prevent="onSubmit">
            <VRow>
              <VCol cols="12">
                <AppSelect
                  v-model="userId"
                  :items="users.map(u => ({ value: u.id, title: u.name }))"
                  label="العميل"
                  :rules="[requiredValidator]"
                />
              </VCol>

              <VCol cols="12">
                <AppTextField
                  v-model="name"
                  label="اسم المجلد"
                  :rules="[requiredValidator]"
                />
              </VCol>

              <VCol cols="12">
                <AppTextField
                  v-model="description"
                  label="الوصف"
                  type="text"
                />
              </VCol>

              <VCol cols="12">
                <VBtn type="submit" class="me-3">حفظ</VBtn>
                <VBtn type="reset" variant="tonal" color="error" @click="closeDrawer">
                  إلغاء
                </VBtn>
              </VCol>
            </VRow>
          </VForm>
        </VCardText>
      </VCard>
    </PerfectScrollbar>
  </VNavigationDrawer>
</template>
