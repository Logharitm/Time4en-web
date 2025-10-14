<script setup>
import { ref, onMounted, nextTick, watch } from 'vue'
import { PerfectScrollbar } from 'vue3-perfect-scrollbar'

const props = defineProps({
  isDrawerOpen: { type: Boolean, required: true },
  folderId: { type: Number, default: null },
})

const emit = defineEmits(['update:isDrawerOpen', 'word-data'])

// Form
const isFormValid = ref(false)
const refForm = ref()

// Fields
const userId = ref(null)
const folderId = ref(props.folderId)
const word = ref('')
const translation = ref('')
const audioFile = ref(null)
const audioError = ref(null)

// Recording
const isRecording = ref(false)
let mediaRecorder = null
let audioChunks = []

// Options
const users = ref([])
const folders = ref([])

onMounted(async () => {
  try {
    const resp = await $api('/users?role=user', { method: 'GET' })
    users.value = resp.data || []
  } catch (err) {
    console.error('Error fetching users', err)
  }
})

// لو الـ prop اتغير
watch(() => props.folderId, val => {
  folderId.value = val
})

// Watch userId => fetch folders
watch(userId, async val => {
  if (val) {
    try {
      const resp = await $api(`/folders?user_id=${val}`, { method: 'GET' })
      folders.value = resp.data || []
      folderId.value = null
    } catch (err) {
      console.error('Error fetching folders', err)
      folders.value = []
      folderId.value = null
    }
  } else {
    folders.value = []
    folderId.value = null
  }
})

// 🎤 بدء التسجيل
const startRecording = async () => {
  audioError.value = null

  try {
    const stream = await navigator.mediaDevices.getUserMedia({ audio: true })
    mediaRecorder = new MediaRecorder(stream)
    audioChunks = []

    mediaRecorder.ondataavailable = e => {
      if (e.data.size > 0) audioChunks.push(e.data)
    }

    mediaRecorder.onstop = () => {
      const blob = new Blob(audioChunks, { type: 'audio/webm' })
      const file = new File([blob], 'recorded_audio.webm', { type: 'audio/webm' })
      audioFile.value = file
    }

    mediaRecorder.start()
    isRecording.value = true
  } catch (err) {
    audioError.value = 'حدث خطأ أثناء محاولة الوصول إلى الميكروفون'
    console.error('Error accessing microphone', err)
  }
}

// 🛑 إيقاف التسجيل
const stopRecording = () => {
  if (mediaRecorder && isRecording.value) {
    mediaRecorder.stop()
    isRecording.value = false
  }
}

const closeDrawer = () => {
  emit('update:isDrawerOpen', false)
  nextTick(() => {
    refForm.value?.reset()
    refForm.value?.resetValidation()
    if (!props.folderId) folderId.value = null
    word.value = ''
    translation.value = ''
    audioFile.value = null
    audioError.value = null
    if (!props.folderId) folders.value = []
  })
}

const onSubmit = () => {
  refForm.value?.validate().then(({ valid }) => {
    if (valid) {
      const formData = new FormData()
      formData.append('folder_id', folderId.value)
      formData.append('word', word.value)
      formData.append('translation', translation.value)
      if (audioFile.value) formData.append('audio_file', audioFile.value)

      emit('word-data', formData)
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
    <AppDrawerHeaderSection
      title="إضافة كلمة جديدة"
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
              <!-- اختيار المجلد -->
              <VCol
                v-if="!props.folderId && folders.length"
                cols="12"
              >
                <AppSelect
                  v-model="folderId"
                  :items="folders.map(f => ({ value: f.id, title: f.name }))"
                  label="المجلد"
                  :rules="[requiredValidator]"
                />
              </VCol>

              <VCol cols="12">
                <AppTextField
                  v-model="word"
                  label="الكلمة"
                  :rules="[requiredValidator]"
                />
              </VCol>

              <VCol cols="12">
                <AppTextField
                  v-model="translation"
                  label="الترجمة"
                  :rules="[requiredValidator]"
                />
              </VCol>

              <!-- 🎤 تسجيل الصوت -->
              <VCol cols="12">
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
                      @click="() => { audioFile = null }"
                    >
                      <VIcon icon="tabler-x" />
                    </VBtn>
                  </div>

                  <div v-else-if="!isRecording" class="text-muted small">
                    لم يتم تسجيل صوت بعد
                  </div>
                </div>

                <div
                  v-if="audioError"
                  class="text-danger small mt-1"
                >
                  {{ audioError }}
                </div>
              </VCol>

              <VCol cols="12">
                <VBtn type="submit" class="me-3">
                  حفظ
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
