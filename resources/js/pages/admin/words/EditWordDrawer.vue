<script setup>
import { ref, watch, nextTick, onMounted } from 'vue'
import { PerfectScrollbar } from 'vue3-perfect-scrollbar'

const props = defineProps({
  isDrawerOpen: { type: Boolean, required: true },
  wordData: { type: Object, default: () => ({}) },
})

const emit = defineEmits(['update:isDrawerOpen', 'submit-word'])

// Form
const isFormValid = ref(false)
const refForm = ref()
const isSubmitting = ref(false)

// Fields
const userId = ref(null)
const folderId = ref(null)
const word = ref('')
const translation = ref('')
const exampleSentence = ref('')
const audioFile = ref(null)
const audioError = ref(null)

// Options
const users = ref([])
const folders = ref([])

// refs
const audioInput = ref(null)

// Flag لتجنب إعادة تعيين folderId عند فتح drawer
let initialFolderSet = false

// Fetch users on mounted
onMounted(async () => {
  try {
    const resp = await $api('/users?role=user', { method: 'GET' })
    users.value = resp.data || []

    if (props.wordData?.id) {
      userId.value = props.wordData.user_id
      word.value = props.wordData.word || ''
      translation.value = props.wordData.translation || ''
      exampleSentence.value = props.wordData.example_sentence || ''

      if (userId.value) {
        await fetchFolders(userId.value, props.wordData.folder_id)
        initialFolderSet = true
      }
    }
  } catch (err) {
    console.error('Error fetching users', err)
  }
})

// Fetch folders by user
const fetchFolders = async (uid, initialFolderId = null) => {
  if (!uid) {
    folders.value = []
    folderId.value = null
    return
  }
  try {
    const resp = await $api(`/folders?user_id=${uid}`, { method: 'GET' })
    folders.value = resp.data || []

    if (initialFolderId && folders.value.find(f => f.id === initialFolderId) && !initialFolderSet) {
      folderId.value = initialFolderId
    } else if (!initialFolderSet) {
      folderId.value = null
    }
  } catch (err) {
    console.error('Error fetching folders', err)
    folders.value = []
    folderId.value = null
  }
}

// Watch userId => fetch folders
watch(userId, async val => {
  if (!val) {
    folders.value = []
    folderId.value = null
    return
  }
  initialFolderSet = false
  await fetchFolders(val)
})

// Watch wordData changes
watch(() => props.wordData, async val => {
  if (val && Object.keys(val).length > 0) {
    userId.value = val.user_id || null
    word.value = val.word || ''
    translation.value = val.translation || ''
    exampleSentence.value = val.example_sentence || ''

    if (userId.value) {
      initialFolderSet = false
      await fetchFolders(userId.value, val.folder_id)
      initialFolderSet = true
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
  userId.value = null
  folderId.value = null
  word.value = ''
  translation.value = ''
  exampleSentence.value = ''
  audioFile.value = null
  audioError.value = null
  if (audioInput.value) audioInput.value.value = null
  folders.value = []
  initialFolderSet = false
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
  formData.append('user_id', userId.value)
  formData.append('folder_id', folderId.value)
  formData.append('word', word.value)
  formData.append('translation', translation.value)
  formData.append('example_sentence', exampleSentence.value)
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
              <VCol cols="12">
                <AppSelect
                  v-model="userId"
                  :items="users.map(u => ({ value: u.id, title: u.name }))"
                  label="العميل"
                  :rules="[v => !!v || 'هذا الحقل مطلوب']"
                />
              </VCol>

              <VCol v-if="folders.length" cols="12">
                <AppSelect
                  v-model="folderId"
                  :items="folders.map(f => ({ value: f.id, title: f.name }))"
                  label="المجلد"
                  :rules="[v => !!v || 'هذا الحقل مطلوب']"
                />
              </VCol>

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
                <AppTextarea
                  v-model="exampleSentence"
                  label="الجملة"
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
