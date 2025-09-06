<script setup>
import { ref, nextTick } from 'vue'
import { PerfectScrollbar } from 'vue3-perfect-scrollbar'

const props = defineProps({
  isDrawerOpen: { type: Boolean, required: true },
})

const emit = defineEmits(['update:isDrawerOpen', 'folder-data'])

const isFormValid = ref(false)
const refForm = ref()
const name = ref('')
const description = ref('')

const closeDrawer = () => {
  emit('update:isDrawerOpen', false)
  nextTick(() => {
    refForm.value?.reset()
    refForm.value?.resetValidation()
  })
}

const onSubmit = () => {
  refForm.value?.validate().then(({ valid }) => {
    if (valid) {
      const formData = new FormData()
      formData.append('name', name.value)
      formData.append('description', description.value || '')

      emit('folder-data', formData)
      closeDrawer()
    }
  })
}
</script>

<template>
  <VNavigationDrawer temporary :width="400" location="end" class="scrollable-content"
                     :model-value="props.isDrawerOpen"
                     @update:model-value="val => emit('update:isDrawerOpen', val)">
    <AppDrawerHeaderSection title="إضافة فولدر جديد" @cancel="closeDrawer"/>
    <VDivider/>
    <PerfectScrollbar>
      <VCard flat>
        <VCardText>
          <VForm ref="refForm" v-model="isFormValid" @submit.prevent="onSubmit">
            <VRow>
              <VCol cols="12">
                <AppTextField v-model="name" label="اسم الفولدر" :rules="[requiredValidator]"/>
              </VCol>
              <VCol cols="12">
                <AppTextField v-model="description" label="الوصف" type="text"/>
              </VCol>
              <VCol cols="12">
                <VBtn type="submit" class="me-3">إضافة</VBtn>
                <VBtn type="reset" variant="tonal" color="error" @click="closeDrawer">إلغاء</VBtn>
              </VCol>
            </VRow>
          </VForm>
        </VCardText>
      </VCard>
    </PerfectScrollbar>
  </VNavigationDrawer>
</template>
