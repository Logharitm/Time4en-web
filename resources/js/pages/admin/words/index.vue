<script setup>
import { ref, computed, watch, onMounted } from 'vue'
import { useRouter } from 'vue-router'
import AddWordDrawer from './AddWordDrawer.vue'
import EditWordDrawer from './EditWordDrawer.vue'

// Router
const router = useRouter()

// Filters
const searchQuery = ref('')
const filterUserName = ref('')
const filterFolderName = ref('')
const filterCreatedFrom = ref('')
const filterCreatedTo = ref('')

// Data table
const itemsPerPage = ref(10)
const page = ref(1)
const sortBy = ref()
const orderBy = ref()
const selectedRows = ref([])

const updateOptions = options => {
  sortBy.value = options.sortBy[0]?.key
  orderBy.value = options.sortBy[0]?.order
}

// Toast
const showToast = ref(false)
const message = ref('')
const color = ref('success')
const triggerToast = (msg, type = 'success') => {
  message.value = msg
  color.value = type
  showToast.value = true
}

// Delete
const isDeleteConfirmDialogVisible = ref(false)
const wordToDeleteId = ref(null)
const confirmDelete = id => {
  wordToDeleteId.value = id
  isDeleteConfirmDialogVisible.value = true
}
const executeDelete = async () => {
  if (!wordToDeleteId.value) return
  await deleteWord(wordToDeleteId.value)
  isDeleteConfirmDialogVisible.value = false
  wordToDeleteId.value = null
}

// Headers
const headers = [
  { title: 'الكلمة', key: 'word' },
  { title: 'الترجمة', key: 'translation' },
  { title: 'الجملة', key: 'example_sentence' },
  { title: 'الصوت', key: 'audio', sortable: false },
  { title: 'المجلد', key: 'folder_name' },
  { title: 'المستخدم', key: 'user_name' },
  { title: 'تاريخ الإنشاء', key: 'created_at' },
  { title: 'العمليات', key: 'actions', sortable: false },
]

// API
const wordsData = ref([])
const totalWords = ref(0)
const loading = ref(true)

// Format date
const formatDateTime = dateStr => {
  if (!dateStr) return ''
  const date = new Date(dateStr)
  return date.toLocaleString('en-US', {
    year: 'numeric',
    month: '2-digit',
    day: '2-digit',
    hour: '2-digit',
    minute: '2-digit',
    second: '2-digit',
    hour12: true,
  })
}

// Fetch data
const fetchWords = async () => {
  loading.value = true
  try {
    const params = {
      search: searchQuery.value || undefined,
      user_name: filterUserName.value || undefined,
      folder_name: filterFolderName.value || undefined,
      created_from: filterCreatedFrom.value || undefined,
      created_to: filterCreatedTo.value || undefined,
      per_page: itemsPerPage.value,
      page: page.value,
      sort_by: sortBy.value,
      sort_order: orderBy.value,
    }

    const response = await $api('/words', { method: 'GET', params })
    if (response.status === 'success') {
      wordsData.value = response.data
      totalWords.value = response.meta?.total || 0
    }
  } catch (err) {
    console.error('Error fetching words', err)
  } finally {
    loading.value = false
  }
}

// Reset filters
const resetFilters = () => {
  searchQuery.value = ''
  filterUserName.value = ''
  filterFolderName.value = ''
  filterCreatedFrom.value = ''
  filterCreatedTo.value = ''
}

onMounted(() => {
  fetchWords()
})

watch(
  [searchQuery, filterUserName, filterFolderName, filterCreatedFrom, filterCreatedTo, itemsPerPage, page, sortBy, orderBy],
  fetchWords
)

const words = computed(() => wordsData.value)

// Add / Edit
const isAddWordDrawerVisible = ref(false)
const isEditWordDrawerVisible = ref(false)
const wordToEdit = ref(null)

const openEditDrawer = word => {
  wordToEdit.value = word
  isEditWordDrawerVisible.value = true
}

// API Actions
const addNewWord = async formData => {
  try {
    await $api('/words', { method: 'POST', body: formData })
    triggerToast('تم إضافة الكلمة بنجاح', 'success')
    fetchWords()
  } catch (err) {
    triggerToast('حدث خطأ، حاول مرة أخرى', 'error')
  }
}

const updateWord = async (id, formData) => {
  try {
    await $api(`/words/${id}/update`, { method: 'POST', body: formData })
    triggerToast('تم تعديل الكلمة بنجاح', 'success')
    fetchWords()
  } catch (err) {
    triggerToast('حدث خطأ، حاول مرة أخرى', 'error')
  }
}

const deleteWord = async id => {
  try {
    await $api(`/words/${id}/delete`, { method: 'POST' })
    triggerToast('تم حذف الكلمة بنجاح', 'success')
    fetchWords()
  } catch (err) {
    triggerToast('حدث خطأ أثناء الحذف', 'error')
  }
}

// ✅ التحكم في تشغيل الصوت
const currentAudio = ref(null)
const playingWordId = ref(null)

const toggleAudio = (item) => {
  if (!item.audio_url) return

  // لو فيه صوت شغال
  if (currentAudio.value) {
    currentAudio.value.pause()
    currentAudio.value.currentTime = 0
  }

  if (playingWordId.value === item.id) {
    // كان شغال ونوقفه
    playingWordId.value = null
    currentAudio.value = null
  } else {
    // نشغل الجديد
    const audio = new Audio(item.audio_url)
    currentAudio.value = audio
    playingWordId.value = item.id

    audio.play()
    audio.onended = () => {
      playingWordId.value = null
      currentAudio.value = null
    }
  }
}
</script>

<template>
  <section>
    <VCard class="mb-6">
      <VCardItem class="pb-4">
        <VCardTitle>الكلمات</VCardTitle>
      </VCardItem>

      <VCardText class="d-flex flex-wrap gap-4 align-center">
        <div class="me-3 d-flex gap-3">
          <AppSelect
            label="عرض"
            :model-value="itemsPerPage"
            :items="[10,25,50,100].map(i=>({ value:i, title:i }))"
            style="inline-size: 6.25rem;"
            @update:model-value="itemsPerPage = parseInt($event, 10)"
          />
        </div>

        <div style="min-width: 220px;">
          <AppTextField v-model="searchQuery" placeholder="بحث بالكلمة أو الترجمة" label="بحث بالكلمة أو الترجمة" />
        </div>

        <div style="min-width: 200px;">
          <AppTextField v-model="filterUserName" placeholder="بحث باسم المستخدم" label="اسم المستخدم" />
        </div>

        <div style="min-width: 200px;">
          <AppTextField v-model="filterFolderName" placeholder="بحث باسم المجلد" label="اسم المجلد" />
        </div>

        <div style="min-width: 160px;">
          <AppDateTimePicker v-model="filterCreatedFrom" label="تاريخ الإنشاء من" :config="{ enableTime: false, dateFormat: 'Y-m-d' }" />
        </div>
        <div style="min-width: 160px;">
          <AppDateTimePicker v-model="filterCreatedTo" label="تاريخ الإنشاء إلى" :config="{ enableTime: false, dateFormat: 'Y-m-d' }" />
        </div>

        <VSpacer />

        <VBtn prepend-icon="tabler-rotate-clockwise" @click="resetFilters" variant="outlined">إعادة تعيين</VBtn>

        <VBtn prepend-icon="tabler-plus" @click="isAddWordDrawerVisible = true">إضافة كلمة جديدة</VBtn>
      </VCardText>

      <VDivider />

      <VDataTableServer
        v-model:items-per-page="itemsPerPage"
        v-model:model-value="selectedRows"
        v-model:page="page"
        :items="words"
        item-value="id"
        :items-length="totalWords"
        :headers="headers"
        class="text-no-wrap"
        :loading="loading"
        @update:options="updateOptions"
      >
        <template #item.word="{ item }">{{ item.word }}</template>
        <template #item.translation="{ item }">{{ item.translation }}</template>
        <template #item.example_sentence="{ item }">{{ item.example_sentence }}</template>

        <!-- ✅ Play / Pause audio -->
        <template #item.audio="{ item }">
          <IconBtn v-if="item.audio_url" @click="toggleAudio(item)" title="تشغيل/إيقاف">
            <VIcon :icon="playingWordId === item.id ? 'tabler-pause' : 'tabler-play'" />
          </IconBtn>
          <span v-else>-</span>
        </template>

        <template #item.folder_name="{ item }">
          <a v-if="item.folder_id" @click.prevent="router.push(`/admin/folders/${item.folder_id}`)" class="text-primary cursor-pointer">
            {{ item.folder_name }}
          </a>
          <span v-else>-</span>
        </template>

        <template #item.user_name="{ item }">
          <a v-if="item.user_id" @click.prevent="router.push(`/admin/users/${item.user_id}`)" class="text-primary cursor-pointer">
            {{ item.user_name }}
          </a>
          <span v-else>-</span>
        </template>

        <template #item.created_at="{ item }">{{ formatDateTime(item.created_at) }}</template>

        <template #item.actions="{ item }">
          <IconBtn @click="openEditDrawer(item)"><VIcon icon="tabler-pencil"/></IconBtn>
          <IconBtn @click="confirmDelete(item.id)"><VIcon icon="tabler-trash"/></IconBtn>
        </template>

        <template #bottom>
          <TablePagination v-model:page="page" :items-per-page="itemsPerPage" :total-items="totalWords" />
        </template>
      </VDataTableServer>
    </VCard>

    <AddWordDrawer v-model:is-drawer-open="isAddWordDrawerVisible" @word-data="addNewWord" />
    <EditWordDrawer v-model:is-drawer-open="isEditWordDrawerVisible" :word-data="wordToEdit" @word-data="updateWord" />

    <VSnackbar v-model="showToast" :color="color" location="top end" timeout="5000">
      <template #prepend>
        <VIcon v-if="color==='success'" icon="tabler-check"/>
        <VIcon v-else-if="color==='error'" icon="tabler-alert-circle"/>
      </template>
      {{ message }}
      <template #actions>
        <VBtn icon variant="text" color="white" @click="showToast=false"><VIcon icon="tabler-x"/></VBtn>
      </template>
    </VSnackbar>

    <VDialog v-model="isDeleteConfirmDialogVisible" max-width="500px">
      <VCard>
        <VCardTitle class="text-h6">تأكيد الحذف</VCardTitle>
        <VCardText>هل أنت متأكد أنك تريد حذف هذه الكلمة؟ لا يمكن التراجع عن هذا الإجراء.</VCardText>
        <VCardActions class="px-6 pb-4">
          <VSpacer/>
          <VBtn color="error" variant="flat" @click="isDeleteConfirmDialogVisible=false">إلغاء</VBtn>
          <VBtn color="success" variant="flat" @click="executeDelete">موافق</VBtn>
        </VCardActions>
      </VCard>
    </VDialog>
  </section>
</template>
