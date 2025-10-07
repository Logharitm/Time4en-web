<script setup>
import { ref, watch, nextTick, onMounted } from 'vue'
import { PerfectScrollbar } from 'vue3-perfect-scrollbar'

const props = defineProps({
  isDrawerOpen: { type: Boolean, required: true },
  wordData: { type: Object, default: () => ({}) },
  folderId: { type: Number, required: true }, // 👈 نستقبل المجلد من الصفحة اللي قبلها
})

const emit = defineEmits(['update:isDrawerOpen', 'submit-word'])

// Form
const isFormValid = ref(false)
const refForm = ref()
const isSubmitting = ref(false)

// Fields
const folderId = ref(props.folderId) // 👈 ناخد القيمة من props
const word = ref('')
const translation = ref('')
const exampleSentence = ref('')
const audioFile = ref(null)
const audioError = ref(null)

// refs
const audioInput = ref(null)

// On mounted => لو wordData فيه بيانات (edit mode)
onMounted(async () => {
  if (props.wordData?.id) {
    word.value = props.wordData.word || ''
    translation.value = props.wordData.translation || ''
    exampleSentence.value = props.wordData.example_sentence || ''
    if (props.wordData.folder_id) {
      folderId.value = props.wordData.folder_id
    }
  }
})

// Watch wordData changes
watch(() => props.wordData, async val => {
  if (val && Object.keys(val).length > 0) {
    word.value = val.word || ''
    translation.value = val.translation || ''
    exampleSentence.value = val.example_sentence || ''
    if (val.folder_id) {
      folderId.value = val.folder_id
    }
  } else {
    resetForm()
  }
})

// Handle audio file
const onAudioChange = e => {
  const file = e.target.files[0]
  if (file) {
    if (file.type.startsWith('audio/')) {
      audioFile.value = file
      audioError.value = null
    } else {
      audioError.value = 'الملف يجب أن يكون صوتياً'
      audioFile.value = null
      e.target.value = null
    }
  }
}

// Reset form
const resetForm = () => {
  refForm.value?.reset()
  refForm.value?.resetValidation()
  folderId.value = props.folderId
  word.value = ''
  translation.value = ''
  audioFile.value = null
  audioError.value = null
  if (audioInput.value) audioInput.value.value = null
  isSubmitting.value = false
}

// Close drawer
const closeDrawer = () => {
  emit('update:isDrawerOpen', false)
  nextTick(() => resetForm())
}

// Submit form
const onSubmit = async () => {
  const valid = await refForm.value?.validate()
  if (!valid || !props.wordData?.id) return

  const formData = new FormData()
  formData.append('folder_id', folderId.value)
  formData.append('word', word.value)
  formData.append('translation', translation.value)
  if (audioFile.value) formData.append('audio_file', audioFile.value)

  isSubmitting.value = true

  try {
    await emit('submit-word', props.wordData.id, formData)
    closeDrawer();
  } catch (err) {
    console.error(err)
  } finally {
    isSubmitting.value = false
  }
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
    <AppDrawerHeaderSection
      title="تعديل الكلمة"
      @cancel="closeDrawer"
    />
    <VDivider />
    <PerfectScrollbar :options="{ wheelPropagation: false }">
      <VCard flat>
        <VCardText>
          <VForm
            ref="refForm"
            v-model="isFormValid"
            @submit.prevent="onSubmit"
          >
            <VRow>
              <!-- المجلد (مخفي أو read-only حسب رغبتك) -->
              <input type="hidden" :value="folderId" />

              <VCol cols="12">
                <AppTextField
                  v-model="word"
                  label="الكلمة"
                  :rules="[v => !!v || 'هذا الحقل مطلوب']"
                />
              </VCol>

              <VCol cols="12">
                <AppTextField
                  v-model="translation"
                  label="الترجمة"
                  :rules="[v => !!v || 'هذا الحقل مطلوب']"
                />
              </VCol>


              <VCol cols="12">
                <div class="d-flex gap-2 align-center">
                  <input
                    ref="audioInput"
                    type="file"
                    accept="audio/*"
                    @change="onAudioChange"
                    style="display:none"
                  />
                  <VBtn variant="outlined" @click="audioInput.click()">
                    رفع ملف صوت
                  </VBtn>

                  <div v-if="audioFile" class="d-flex gap-2 align-center">
                    <span class="small">{{ audioFile.name }}</span>
                    <VBtn
                      icon
                      variant="text"
                      @click="() => { audioFile = null; audioInput.value.value = null }"
                    >
                      <VIcon icon="tabler-x" />
                    </VBtn>
                  </div>

                  <div v-else class="text-muted small">لم يتم اختيار ملف</div>
                </div>

                <div v-if="audioError" class="text-danger small mt-1">
                  {{ audioError }}
                </div>
              </VCol>

              <VCol cols="12">
                <VBtn
                  type="submit"
                  class="me-3"
                  :loading="isSubmitting"
                  :disabled="isSubmitting"
                >
                  {{ isSubmitting ? 'جاري التعديل...' : 'حفظ' }}
                </VBtn>

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
