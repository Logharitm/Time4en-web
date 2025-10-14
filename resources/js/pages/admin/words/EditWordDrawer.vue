<script setup>
import {ref, watch, nextTick, onMounted} from 'vue'
import {PerfectScrollbar} from 'vue3-perfect-scrollbar'

const props = defineProps({
  isDrawerOpen: {type: Boolean, required: true},
  wordData: {type: Object, default: () => ({})},
  folderId: {type: Number, required: true},
})

const emit = defineEmits(['update:isDrawerOpen', 'submit-word'])

// Form
const isFormValid = ref(false)
const refForm = ref()
const isSubmitting = ref(false)

// Fields
const folderId = ref(props.folderId)
const word = ref('')
const translation = ref('')
const exampleSentence = ref('')
const audioFile = ref(null)
const audioError = ref(null)

// Recording
const isRecording = ref(false)
let mediaRecorder = null
let audioChunks = []

// refs
const audioInput = ref(null)

// On mounted => edit mode
onMounted(() => {
  if (props.wordData?.id) {
    word.value = props.wordData.word || ''
    translation.value = props.wordData.translation || ''
    exampleSentence.value = props.wordData.example_sentence || ''
    if (props.wordData.folder_id) folderId.value = props.wordData.folder_id
  }
})

// Watch for changes in wordData
watch(() => props.wordData, val => {
  if (val && Object.keys(val).length > 0) {
    word.value = val.word || ''
    translation.value = val.translation || ''
    exampleSentence.value = val.example_sentence || ''
    if (val.folder_id) folderId.value = val.folder_id
  } else {
    resetForm()
  }
})

// 🎤 بدء التسجيل
const startRecording = async () => {
  audioError.value = null

  try {
    const stream = await navigator.mediaDevices.getUserMedia({audio: true})
    mediaRecorder = new MediaRecorder(stream)
    audioChunks = []

    mediaRecorder.ondataavailable = e => {
      if (e.data.size > 0) audioChunks.push(e.data)
    }

    mediaRecorder.onstop = () => {
      const blob = new Blob(audioChunks, {type: 'audio/webm'})
      const file = new File([blob], 'recorded_audio.webm', {type: 'audio/webm'})
      audioFile.value = file
    }

    mediaRecorder.start()
    isRecording.value = true
  } catch (err) {
    console.error('Error accessing microphone', err)
    if (err.name === 'NotAllowedError') {
      audioError.value = 'تم رفض الإذن باستخدام الميكروفون. يرجى السماح به من إعدادات المتصفح.'
    } else if (err.name === 'NotFoundError') {
      audioError.value = 'لم يتم العثور على ميكروفون متصل بالجهاز.'
    } else if (location.protocol !== 'https:') {
      audioError.value = 'يجب أن يتم التسجيل من موقع يستخدم HTTPS.'
    } else {
      audioError.value = 'حدث خطأ أثناء محاولة الوصول إلى الميكروفون.'
    }
  }
}

// 🛑 إيقاف التسجيل
const stopRecording = () => {
  if (mediaRecorder && isRecording.value) {
    mediaRecorder.stop()
    isRecording.value = false
  }
}

// 📁 رفع ملف صوتي يدويًا
const onAudioChange = e => {
  const file = e.target.files[0]
  if (file) {
    if (!file.type.startsWith('audio/')) {
      audioError.value = 'الملف يجب أن يكون صوتياً'
      e.target.value = ''
      return
    }
    audioFile.value = file
    audioError.value = null
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
  isRecording.value = false
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
    closeDrawer()
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
    <VDivider/>

    <PerfectScrollbar :options="{ wheelPropagation: false }">
      <VCard flat>
        <VCardText>
          <VForm ref="refForm" v-model="isFormValid" @submit.prevent="onSubmit">
            <VRow>
              <input type="hidden" :value="folderId"/>

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

              <!-- 🎤 رفع أو تسجيل صوت -->
              <VCol cols="12">
                <div class="mb-2 fw-bold">الصوت</div>

                <div class="d-flex flex-column gap-3">
                  <!-- اختيار ملف -->
                  <div>
                    <label class="form-label">رفع ملف صوتي</label>
                    <input
                      ref="audioInput"
                      type="file"
                      accept="audio/*"
                      class="form-control"
                      @change="onAudioChange"
                    />
                  </div>

                  <div class="text-center fw-bold">أو</div>

                  <!-- التسجيل -->
                  <div class="d-flex gap-2 align-center">
                    <VBtn
                      variant="outlined"
                      color="primary"
                      v-if="!isRecording"
                      @click="startRecording"
                    >
                      بدء التسجيل
                    </VBtn>

                    <VBtn
                      variant="outlined"
                      color="error"
                      v-else
                      @click="stopRecording"
                    >
                      إيقاف التسجيل
                    </VBtn>

                    <div v-if="audioFile" class="d-flex gap-2 align-center">
                      <audio :src="URL.createObjectURL(audioFile)" controls></audio>
                      <VBtn
                        icon
                        variant="text"
                        @click="() => { audioFile = null; if (audioInput.value) audioInput.value.value = null }"
                      >
                        <VIcon icon="tabler-x"/>
                      </VBtn>
                    </div>

                    <div v-else-if="!isRecording" class="text-muted small">
                      لم يتم اختيار أو تسجيل صوت بعد
                    </div>
                  </div>

                  <div
                    v-if="audioError"
                    class="text-danger small mt-1"
                  >
                    {{ audioError }}
                  </div>
                </div>
              </VCol>

              <VCol cols="12">
                <VBtn
                  type="submit"
                  class="me-3"
                  :loading="isSubmitting"
                  :disabled="isSubmitting"
                >
                  {{ isSubmitting ? 'جاري الحفظ...' : 'حفظ التعديل' }}
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
