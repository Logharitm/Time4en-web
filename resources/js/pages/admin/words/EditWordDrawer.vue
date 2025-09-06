<script setup>
import { ref, watch, nextTick, onMounted } from 'vue'
import { PerfectScrollbar } from 'vue3-perfect-scrollbar'

const props = defineProps({
  isDrawerOpen: { type: Boolean, required: true },
  wordData: { type: Object, default: () => ({}) },
})

const emit = defineEmits(['update:isDrawerOpen', 'word-data'])

const isFormValid = ref(false)
const refForm = ref()

const userId = ref(null)
const folderId = ref(null)
const word = ref('')
const translation = ref('')
const exampleSentence = ref('')
const audioFile = ref(null)

const users = ref([])
const folders = ref([])

onMounted(async () => {
  try {
    const usersResp = await $api('/users?role=user', { method: 'GET' })
    users.value = usersResp.data || []

    const foldersResp = await $api('/folders', { method: 'GET' })
    folders.value = foldersResp.data || []
  } catch (err) {
    console.error('Error fetching users/folders', err)
  }
})

// Fill data when editing
watch(() => props.wordData, val => {
  if (val && Object.keys(val).length > 0) {
    userId.value = val.user_id || null
    folderId.value = val.folder_id || null
    word.value = val.word || ''
    translation.value = val.translation || ''
    exampleSentence.value = val.example_sentence || ''
  }
}, { immediate: true })

const closeDrawer = () => {
  emit('update:isDrawerOpen', false)
  nextTick(() => {
    refForm.value?.reset()
    refForm.value?.resetValidation()
  })
}

const onSubmit = () => {
  refForm.value?.validate().then(({valid}) => {
    if (valid && props.wordData?.id) {
      const formData = new FormData()
      formData.append('user_id', userId.value)
      formData.append('folder_id', folderId.value)
      formData.append('word', word.value)
      formData.append('translation', translation.value)
      formData.append('example_sentence', exampleSentence.value)
      if (audioFile.value) formData.append('audio', audioFile.value)

      emit('word-data', props.wordData.id, formData)
      closeDrawer()
    }
  })
}
</script>

<template>
  <VNavigationDrawer temporary :width="400" location="end" class="scrollable-content"
                     :model-value="props.isDrawerOpen"
                     @update:model-value="val => emit('update:isDrawerOpen', val)">
    <AppDrawerHeaderSection title="تعديل الكلمة" @cancel="closeDrawer"/>
    <VDivider/>
    <PerfectScrollbar>
      <VCard flat>
        <VCardText>
          <VForm ref="refForm" v-model="isFormValid" @submit.prevent="onSubmit">
            <VRow>
              <VCol cols="12">
                <AppSelect v-model="userId" :items="users.map(u => ({ value: u.id, title: u.name }))" label="العميل"
                           :rules="[requiredValidator]"/>
              </VCol>

              <VCol cols="12">
                <AppSelect v-model="folderId" :items="folders.map(f => ({ value: f.id, title: f.name }))" label="المجلد"
                           :rules="[requiredValidator]"/>
              </VCol>

              <VCol cols="12">
                <AppTextField v-model="word" label="الكلمة" :rules="[requiredValidator]"/>
              </VCol>

              <VCol cols="12">
                <AppTextField v-model="translation" label="الترجمة" :rules="[requiredValidator]"/>
              </VCol>

              <VCol cols="12">
                <AppTextarea v-model="exampleSentence" label="جملة توضيحية"/>
              </VCol>

              <VCol cols="12">
                <input type="file" @change="e => audioFile.value = e.target.files[0]" accept="audio/*"/>
              </VCol>

              <VCol cols="12">
                <VBtn type="submit" class="me-3">تحديث</VBtn>
                <VBtn type="reset" variant="tonal" color="error" @click="closeDrawer">إلغاء</VBtn>
              </VCol>
            </VRow>
          </VForm>
        </VCardText>
      </VCard>
    </PerfectScrollbar>
  </VNavigationDrawer>
</template>
