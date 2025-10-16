<script setup>
import { ref, onMounted, nextTick, watch } from 'vue'
import { PerfectScrollbar } from 'vue3-perfect-scrollbar'
import { useI18n } from 'vue-i18n'

const { t, locale } = useI18n()

const props = defineProps({
  isDrawerOpen: { type: Boolean, required: true },
  folderId: { type: Number, default: null },
})

const emit = defineEmits(['update:isDrawerOpen', 'word-data'])

const isFormValid = ref(false)
const refForm = ref()

const userId = ref(null)
const folderId = ref(props.folderId)
const word = ref('')
const translation = ref('')
const audioFile = ref(null)
const audioError = ref(null)

const isRecording = ref(false)
let mediaRecorder = null
let audioChunks = []

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

watch(() => props.folderId, val => {
  folderId.value = val
})

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
    console.error('Error accessing microphone', err)
    if (err.name === 'NotAllowedError') {
      audioError.value = t('micPermissionDenied')
    } else if (err.name === 'NotFoundError') {
      audioError.value = t('micNotFound')
    } else if (location.protocol !== 'https:') {
      audioError.value = t('httpsRequired')
    } else {
      audioError.value = t('micAccessError')
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

// 📁 رفع ملف صوتي
const handleFileUpload = e => {
  const file = e.target.files[0]
  if (file) {
    if (!file.type.startsWith('audio/')) {
      audioError.value = t('fileMustBeAudio')
      return
    }
    audioFile.value = file
    audioError.value = null
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
    <AppDrawerHeaderSection :title="t('addWord')" @cancel="closeDrawer" />
    <VDivider />
    <PerfectScrollbar :options="{ wheelPropagation: false }">
      <VCard flat>
        <VCardText>
          <VForm ref="refForm" v-model="isFormValid" @submit.prevent="onSubmit">
            <VRow>
              <VCol v-if="!props.folderId && folders.length" cols="12">
                <AppSelect
                  v-model="folderId"
                  :items="folders.map(f => ({ value: f.id, title: f.name }))"
                  :label="t('folder')"
                  :rules="[requiredValidator]"
                />
              </VCol>

              <VCol cols="12">
                <AppTextField
                  v-model="word"
                  :label="t('word')"
                  :rules="[requiredValidator]"
                />
              </VCol>

              <VCol cols="12">
                <AppTextField
                  v-model="translation"
                  :label="t('translation')"
                  :rules="[requiredValidator]"
                />
              </VCol>

              <!-- 🎤 الصوت -->
              <VCol cols="12">
                <div class="mb-2 fw-bold">{{ t('audio') }}</div>
                <div class="d-flex flex-column gap-3">
                  <div>
                    <label class="form-label">{{ t('uploadAudio') }}</label>
                    <input
                      type="file"
                      accept="audio/*"
                      @change="handleFileUpload"
                      class="form-control"
                    />
                  </div>

                  <div class="text-center fw-bold">{{ t('or') }}</div>

                  <div class="d-flex gap-2 align-center">
                    <VBtn
                      variant="outlined"
                      color="primary"
                      v-if="!isRecording"
                      @click="startRecording"
                    >
                      {{ t('startRecording') }}
                    </VBtn>

                    <VBtn
                      variant="outlined"
                      color="error"
                      v-else
                      @click="stopRecording"
                    >
                      {{ t('stopRecording') }}
                    </VBtn>

                    <div v-if="audioFile" class="d-flex gap-2 align-center">
                      <audio :src="URL.createObjectURL(audioFile)" controls></audio>
                      <VBtn icon variant="text" @click="() => { audioFile = null }">
                        <VIcon icon="tabler-x" />
                      </VBtn>
                    </div>

                    <div v-else-if="!isRecording" class="text-muted small">
                      {{ t('noAudioSelected') }}
                    </div>
                  </div>

                  <div v-if="audioError" class="text-danger small mt-1">
                    {{ audioError }}
                  </div>
                </div>
              </VCol>

              <VCol cols="12">
                <VBtn type="submit" class="me-3">{{ t('save') }}</VBtn>
                <VBtn type="reset" variant="tonal" color="error" @click="closeDrawer">
                  {{ t('cancel') }}
                </VBtn>
              </VCol>
            </VRow>
          </VForm>
        </VCardText>
      </VCard>
    </PerfectScrollbar>
  </VNavigationDrawer>
</template>
