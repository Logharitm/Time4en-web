<script setup>
import { ref, nextTick, watch } from 'vue'
import { PerfectScrollbar } from 'vue3-perfect-scrollbar'

const props = defineProps({
  isDrawerOpen: { type: Boolean, required: true },
  folderData: { type: Object, default: () => ({}) },
})

const emit = defineEmits(['update:isDrawerOpen', 'folder-data'])

const isFormValid = ref(false)
const refForm = ref()

const name = ref('')
const description = ref('')

// لما الفولدر يتغير (لما نفتح drawer للتعديل) نعبي البيانات
watch(
  () => props.folderData,
  val => {
    if (val && Object.keys(val).length > 0) {
      name.value = val.name || ''
      description.value = val.description || ''
    }
  },
  { immediate: true }
)

const closeDrawer = () => {
  emit('update:isDrawerOpen', false)
  nextTick(() => {
    refForm.value?.reset()
    refForm.value?.resetValidation()
  })
}

const onSubmit = () => {
  refForm.value?.validate().then(({ valid }) => {
    if (valid && props.folderData?.id) {
      const formData = new FormData()
      formData.append('name', name.value)
      formData.append('description', description.value || '')

      // نبعث id مع البيانات
      emit('folder-data', props.folderData.id, formData)

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
    <AppDrawerHeaderSection title="تعديل الفولدر" @cancel="closeDrawer" />
    <VDivider />

    <PerfectScrollbar>
      <VCard flat>
        <VCardText>
          <VForm ref="refForm" v-model="isFormValid" @submit.prevent="onSubmit">
            <VRow>
              <VCol cols="12">
                <AppTextField
                  v-model="name"
                  label="اسم الفولدر"
                  :rules="[requiredValidator]"
                />
              </VCol>

              <VCol cols="12">
                <AppTextarea
                  v-model="description"
                  label="الوصف"
                  type="text"
                />
              </VCol>

              <VCol cols="12">
                <VBtn type="submit" class="me-3">تحديث</VBtn>
                <VBtn
                  type="reset"
                  variant="tonal"
                  color="error"
                  @click="closeDrawer"
                >
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
