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

// جلب اليوزرز أول ما يفتح
onMounted(async () => {
  try {
    const usersResp = await $api('/users?role=user', { method: 'GET' })
    users.value = usersResp.data || []

    // لو الكلمة فيها يوزر متخزن مسبقًا
    if (props.wordData?.user_id) {
      userId.value = props.wordData.user_id
      await fetchFolders(userId.value)
    }
  } catch (err) {
    console.error('Error fetching users', err)
  }
})

// دالة تجيب فولدرات حسب اليوزر
const fetchFolders = async (uid) => {
  if (!uid) {
    folders.value = []
    return
  }
  try {
    const foldersResp = await $api(`/folders?user_id=${uid}`, { method: 'GET' })
    folders.value = foldersResp.data || []
  } catch (err) {
    console.error('Error fetching folders', err)
  }
}

// مراقبة اختيار اليوزر
watch(userId, (newVal) => {
  folderId.value = null // تصفير الفولدر عند تغيير اليوزر
  fetchFolders(newVal)
})

// ملئ البيانات وقت التعديل
watch(() => props.wordData, val => {
  if (val && Object.keys(val).length > 0) {
    userId.value = val.user_id || null
    folderId.value = val.folder_id || null
    word.value = val.word || ''
    translation.value = val.translation || ''
    exampleSentence.value = val.example_sentence || ''
  }
}, {immediate: true})

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
