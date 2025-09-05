<script setup>
import { PerfectScrollbar } from 'vue3-perfect-scrollbar'

const props = defineProps({
  isDrawerOpen: {
    type: Boolean,
    required: true,
  },
})

const emit = defineEmits([
  'update:isDrawerOpen',
  'faqData',
])

const isFormValid = ref(false)
const refForm = ref()

// form fields
const question = ref('')
const answer = ref('')
const questionEn = ref('')
const answerEn = ref('')

// 👉 drawer close
const closeNavigationDrawer = () => {
  emit('update:isDrawerOpen', false)
  nextTick(() => {
    refForm.value?.reset()
    refForm.value?.resetValidation()
  })
}

// 👉 submit
const onSubmit = () => {
  refForm.value?.validate().then(({ valid }) => {
    if (valid) {
      const formData = new FormData()
      formData.append('question', question.value)
      formData.append('answer', answer.value)
      formData.append('question_en', questionEn.value)
      formData.append('answer_en', answerEn.value)

      emit('faqData', formData)
      closeNavigationDrawer()
    }
  })
}

const handleDrawerModelValueUpdate = val => {
  emit('update:isDrawerOpen', val)
}
</script>

<template>
  <VNavigationDrawer
    data-allow-mismatch
    temporary
    :width="400"
    location="end"
    class="scrollable-content"
    :model-value="props.isDrawerOpen"
    @update:model-value="handleDrawerModelValueUpdate"
  >
    <AppDrawerHeaderSection
      title="إضافة سؤال جديد"
      @cancel="closeNavigationDrawer"
    />

    <VDivider />

    <PerfectScrollbar :options="{ wheelPropagation: false }">
      <VCard flat>
        <VCardText>
          <VForm ref="refForm" v-model="isFormValid" @submit.prevent="onSubmit">
            <VRow>
              <VCol cols="12">
                <AppTextField v-model="question" :rules="[requiredValidator]" label="السؤال (AR)" />
              </VCol>

              <VCol cols="12">
                <AppTextField v-model="answer" :rules="[requiredValidator]" label="الإجابة (AR)" />
              </VCol>

              <VCol cols="12">
                <AppTextField v-model="questionEn" :rules="[requiredValidator]" label="Question (EN)" />
              </VCol>

              <VCol cols="12">
                <AppTextField v-model="answerEn" :rules="[requiredValidator]" label="Answer (EN)" />
              </VCol>

              <VCol cols="12">
                <VBtn type="submit" class="me-3">حفظ</VBtn>
                <VBtn type="reset" variant="tonal" color="error" @click="closeNavigationDrawer">إلغاء</VBtn>
              </VCol>
            </VRow>
          </VForm>
        </VCardText>
      </VCard>
    </PerfectScrollbar>
  </VNavigationDrawer>
</template>
