<script setup>
import {ref, computed, watch, onMounted} from 'vue'
import {useRouter} from 'vue-router'

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
const practiceToDeleteId = ref(null)
const confirmDelete = id => {
  practiceToDeleteId.value = id
  isDeleteConfirmDialogVisible.value = true
}
const executeDelete = async () => {
  if (!practiceToDeleteId.value) return
  await deletePractice(practiceToDeleteId.value)
  isDeleteConfirmDialogVisible.value = false
  practiceToDeleteId.value = null
}

// Headers
const headers = [
  {title: 'ID', key: 'id'},
  {title: 'المستخدم', key: 'user_name'},
  {title: 'المجلد', key: 'folder_name'},
  {title: 'عدد الأسئلة', key: 'total_words'},
  {title: 'الإجابات الصحيحة', key: 'correct_answers'},
  {title: 'الإجابات الخاطئة', key: 'wrong_answers'},
  {title: 'النسبة %', key: 'score_percentage'},
  {title: 'تاريخ الإنشاء', key: 'created_at'},
  {title: 'العمليات', key: 'actions', sortable: false},
]

// API
const practicesData = ref([])
const totalPractices = ref(0)
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
const fetchPractices = async () => {
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

    const response = await $api('/practices', {method: 'GET', params})
    if (response.status === 'success') {
      practicesData.value = response.data
      totalPractices.value = response.meta?.total || 0
    }
  } catch (err) {
    console.error('Error fetching practices', err)
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
  fetchPractices()
})

watch(
  [searchQuery, filterUserName, filterFolderName, filterCreatedFrom, filterCreatedTo, itemsPerPage, page, sortBy, orderBy],
  fetchPractices
)

const practices = computed(() => practicesData.value)

// API Actions
const deletePractice = async id => {
  try {
    await $api(`/practices/${id}/delete`, {method: 'POST'})
    triggerToast('تم حذف الاختبار بنجاح', 'success')
    fetchPractices()
  } catch (err) {
    triggerToast('حدث خطأ أثناء الحذف', 'error')
  }
}
</script>

<template>
  <section>
    <VCard class="mb-6">
      <VCardItem class="pb-4">
        <VCardTitle>الاختبارات</VCardTitle>
      </VCardItem>

      <VCardText class="d-flex flex-wrap gap-4 align-center">
        <!-- الفلاتر -->
        <div class="d-flex flex-wrap gap-4">
          <div class="me-3 d-flex gap-3">
            <AppSelect
              label="عرض"
              :model-value="itemsPerPage"
              :items="[10,25,50,100].map(i=>({ value:i, title:i }))"
              style="inline-size: 6.25rem;"
              @update:model-value="itemsPerPage = parseInt($event, 10)"
            />
          </div>

          <div style="min-width: 200px;">
            <AppTextField v-model="filterUserName" placeholder="بحث باسم المستخدم" label="اسم المستخدم"/>
          </div>

          <div style="min-width: 200px;">
            <AppTextField v-model="filterFolderName" placeholder="بحث باسم المجلد" label="اسم المجلد"/>
          </div>

          <div style="min-width: 160px;">
            <AppDateTimePicker v-model="filterCreatedFrom" label="تاريخ الإنشاء من"
                               :config="{ enableTime: false, dateFormat: 'Y-m-d' }"/>
          </div>

          <div style="min-width: 160px;">
            <AppDateTimePicker v-model="filterCreatedTo" label="تاريخ الإنشاء إلى"
                               :config="{ enableTime: false, dateFormat: 'Y-m-d' }"/>
          </div>
        </div>

        <!-- أزرار -->
        <div class="d-flex gap-3 mt-4">
          <VBtn prepend-icon="tabler-rotate-clockwise" @click="resetFilters" variant="outlined">إعادة تعيين</VBtn>
        </div>
      </VCardText>

      <VDivider/>

      <VDataTableServer
        v-model:items-per-page="itemsPerPage"
        v-model:model-value="selectedRows"
        v-model:page="page"
        :items="practices"
        item-value="id"
        :items-length="totalPractices"
        :headers="headers"
        class="text-no-wrap"
        :loading="loading"
        @update:options="updateOptions"
      >
        <template #item.user_name="{ item }">
          <a v-if="item.user_id" @click.prevent="router.push(`/admin/users/${item.user_id}`)"
             class="text-primary cursor-pointer">
            {{ item.user_name }}
          </a>
          <span v-else>-</span>
        </template>

        <template #item.folder_name="{ item }">
          <a v-if="item.folder_id" @click.prevent="router.push(`/admin/folders/${item.folder_id}`)"
             class="text-primary cursor-pointer">
            {{ item.folder_name }}
          </a>
          <span v-else>-</span>
        </template>

        <template #item.score_percentage="{ item }">
          {{ item.total_words ? Math.round((item.correct_answers / item.total_words) * 100) : 0 }}%
        </template>

        <template #item.created_at="{ item }">{{ formatDateTime(item.created_at) }}</template>

        <template #item.actions="{ item }">
          <VBtn variant="outlined" size="small" color="primary"
                @click="router.push(`/admin/practices/${item.id}/report`)">
            عرض التقرير
          </VBtn>
          <IconBtn @click="confirmDelete(item.id)">
            <VIcon icon="tabler-trash"/>
          </IconBtn>
        </template>

        <template #bottom>
          <TablePagination v-model:page="page" :items-per-page="itemsPerPage" :total-items="totalPractices"/>
        </template>
      </VDataTableServer>
    </VCard>

    <VSnackbar v-model="showToast" :color="color" location="top end" timeout="5000">
      <template #prepend>
        <VIcon v-if="color==='success'" icon="tabler-check"/>
        <VIcon v-else-if="color==='error'" icon="tabler-alert-circle"/>
      </template>
      {{ message }}
      <template #actions>
        <VBtn icon variant="text" color="white" @click="showToast=false">
          <VIcon icon="tabler-x"/>
        </VBtn>
      </template>
    </VSnackbar>

    <VDialog v-model="isDeleteConfirmDialogVisible" max-width="500px">
      <VCard>
        <VCardTitle class="text-h6">تأكيد الحذف</VCardTitle>
        <VCardText>هل أنت متأكد أنك تريد حذف هذا الاختبار؟ لا يمكن التراجع عن هذا الإجراء.</VCardText>
        <VCardActions class="px-6 pb-4">
          <VSpacer/>
          <VBtn color="error" variant="flat" @click="isDeleteConfirmDialogVisible=false">إلغاء</VBtn>
          <VBtn color="success" variant="flat" @click="executeDelete">موافق</VBtn>
        </VCardActions>
      </VCard>
    </VDialog>
  </section>
</template>
