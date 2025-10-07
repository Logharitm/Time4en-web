<script setup>
import { ref, onMounted, nextTick, watch } from 'vue'
import { PerfectScrollbar } from 'vue3-perfect-scrollbar'

const props = defineProps({
  isDrawerOpen: { type: Boolean, required: true },
  folderId: { type: Number, default: null },   // ✅ استقبل folderId
})

const emit = defineEmits(['update:isDrawerOpen', 'word-data'])

// Form
const isFormValid = ref(false)
const refForm = ref()

// Fields
const userId = ref(null)
const folderId = ref(props.folderId)   // ✅ يبدأ بالـ prop
const word = ref('')
const translation = ref('')
const audioFile = ref(null)
const audioError = ref(null)

// Options
const users = ref([])
const folders = ref([])

// refs
const audioInput = ref(null)

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

// handle audio file
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
    if (audioInput.value) audioInput.value.value = null
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
              <!-- ✅ Dropdown يظهر فقط لو مفيش folderId جاي من الـ prop -->
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

              <!-- ملف الصوت -->
              <VCol cols="12">
                <div class="d-flex gap-2 align-center">
                  <input
                    ref="audioInput"
                    type="file"
                    accept="audio/*"
                    style="display:none"
                    @change="onAudioChange"
                  >
                  <VBtn
                    variant="outlined"
                    @click="audioInput.click()"
                  >
                    رفع ملف صوت
                  </VBtn>

                  <div
                    v-if="audioFile"
                    class="d-flex gap-2 align-center"
                  >
                    <span class="small">{{ audioFile.name }}</span>
                    <VBtn
                      icon
                      variant="text"
                      @click="() => { audioFile = null; audioInput.value.value = null }"
                    >
                      <VIcon icon="tabler-x" />
                    </VBtn>
                  </div>
                  <div
                    v-else
                    class="text-muted small"
                  >
                    لم يتم اختيار ملف
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
                <VBtn
                  type="submit"
                  class="me-3"
                >
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
