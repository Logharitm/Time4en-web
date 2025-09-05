<script setup>
import { ref, computed, watch, onMounted } from 'vue'
import AddNewFAQDrawer from './AddNewFAQDrawer.vue'
import EditFAQDrawer from './EditFAQDrawer.vue'

// filters
const searchQuery = ref('')
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

// Delete confirm dialog
const isDeleteConfirmDialogVisible = ref(false)
const faqToDeleteId = ref(null)

const confirmDelete = faqId => {
  faqToDeleteId.value = faqId
  isDeleteConfirmDialogVisible.value = true
}

const executeDelete = async () => {
  if (faqToDeleteId.value) {
    await deleteFAQ(faqToDeleteId.value)
    isDeleteConfirmDialogVisible.value = false
    faqToDeleteId.value = null
  }
}

// View full FAQ dialog
const isViewDialogVisible = ref(false)
const selectedFAQ = ref(null)

const viewFAQ = faq => {
  selectedFAQ.value = faq
  isViewDialogVisible.value = true
}

// Headers
const headers = [
  { title: 'السؤال (AR)', key: 'question' },
  { title: 'الإجابة (AR)', key: 'answer' },
  { title: 'Question (EN)', key: 'question_en' },
  { title: 'Answer (EN)', key: 'answer_en' },
  { title: 'العمليات', key: 'actions', sortable: false },
]

// API
const faqsData = ref([])
const totalFaqs = ref(0)
const loading = ref(true)

const fetchFAQs = async () => {
  loading.value = true
  try {
    const response = await $api('/faqs', {
      method: 'GET',
      params: {
        search: searchQuery.value,
        per_page: itemsPerPage.value,
        page: page.value,
        sort_by: sortBy.value,
        sort_order: orderBy.value,
      },
    })

    if (response.status === 'success') {
      faqsData.value = response.data.data
      totalFaqs.value = response.data.total
    }
  } catch (err) {
    console.error('Error fetching FAQs', err)
  } finally {
    loading.value = false
  }
}

onMounted(fetchFAQs)
watch([searchQuery, itemsPerPage, page, sortBy, orderBy], fetchFAQs)

const faqs = computed(() => faqsData.value)

// CRUD
const isAddNewFAQDrawerVisible = ref(false)
const isEditFAQDrawerVisible = ref(false)
const faqToEdit = ref(null)

const addNewFAQ = async faqData => {
  try {
    await $api('/faqs', { method: 'POST', body: faqData })
    triggerToast('تم إضافة السؤال بنجاح', 'success')
    fetchFAQs()
  } catch (err) {
    triggerToast('حدث خطأ أثناء الإضافة', 'error')
  }
}

const deleteFAQ = async id => {
  try {
    await $api(`/faqs/${id}/delete`, { method: 'POST' })
    triggerToast('تم الحذف بنجاح', 'success')
    fetchFAQs()
  } catch (err) {
    triggerToast('حدث خطأ أثناء الحذف', 'error')
  }
}

const openEditDrawer = faq => {
  faqToEdit.value = faq
  isEditFAQDrawerVisible.value = true
}

const updateFAQ = async (id, faqData) => {
  try {
    await $api(`/faqs/${id}`, { method: 'POST', body: faqData })
    triggerToast('تم تعديل السؤال بنجاح', 'success')
    fetchFAQs()
  } catch (err) {
    triggerToast('حدث خطأ أثناء التعديل', 'error')
  }
}

// Utility: show first 5 words only
const truncateText = text => {
  if (!text) return ''
  const words = text.split(' ')
  return words.length > 5 ? words.slice(0, 5).join(' ') + '...' : text
}
</script>

<template>
  <section>
    <VCard class="mb-6">
      <VCardItem class="pb-4">
        <VCardTitle>الأسئلة المتكررة</VCardTitle>
      </VCardItem>

      <!-- Search + Add -->
      <VCardText class="d-flex flex-wrap gap-4">
        <div class="me-3 d-flex gap-3">
          <AppSelect
            :model-value="itemsPerPage"
            :items="[
              { value: 10, title: '10' },
              { value: 25, title: '25' },
              { value: 50, title: '50' },
              { value: 100, title: '100' },
              { value: -1, title: 'All' },
            ]"
            style="inline-size: 6.25rem;"
            @update:model-value="itemsPerPage = parseInt($event, 10)"
          />
        </div>
        <VSpacer />
        <div class="d-flex align-center flex-wrap gap-4">
          <div style="inline-size: 15.625rem;">
            <AppTextField v-model="searchQuery" placeholder="بحث" />
          </div>

          <VBtn prepend-icon="tabler-plus" @click="isAddNewFAQDrawerVisible = true">
            إضافة سؤال جديد
          </VBtn>
        </div>
      </VCardText>

      <VDivider />

      <VDataTableServer
        v-model:items-per-page="itemsPerPage"
        v-model:model-value="selectedRows"
        v-model:page="page"
        :items="faqs"
        item-value="id"
        :items-length="totalFaqs"
        :headers="headers"
        class="text-no-wrap"
        :loading="loading"
        @update:options="updateOptions"
      >
        <!-- Truncate -->
        <template #item.answer="{ item }">
          {{ truncateText(item.answer) }}
        </template>
        <template #item.answer_en="{ item }">
          {{ truncateText(item.answer_en) }}
        </template>

        <!-- Actions -->
        <template #item.actions="{ item }">
          <IconBtn @click="viewFAQ(item)">
            <VIcon icon="tabler-eye" />
          </IconBtn>
          <IconBtn @click="openEditDrawer(item)">
            <VIcon icon="tabler-pencil" />
          </IconBtn>
          <IconBtn @click="confirmDelete(item.id)">
            <VIcon icon="tabler-trash" />
          </IconBtn>
        </template>

        <!-- Pagination -->
        <template #bottom>
          <TablePagination
            v-model:page="page"
            :items-per-page="itemsPerPage"
            :total-items="totalFaqs"
          />
        </template>
      </VDataTableServer>
    </VCard>

    <!-- Add Drawer -->
    <AddNewFAQDrawer
      v-model:is-drawer-open="isAddNewFAQDrawerVisible"
      @faq-data="addNewFAQ"
    />

    <!-- Edit Drawer -->
    <EditFAQDrawer
      v-model:is-drawer-open="isEditFAQDrawerVisible"
      :faq-data="faqToEdit"
      @faq-data="updateFAQ"
    />

    <!-- Snackbar -->
    <VSnackbar v-model="showToast" :color="color" location="top end" timeout="5000">
      <template #prepend>
        <VIcon v-if="color === 'success'" icon="tabler-check" />
        <VIcon v-else-if="color === 'error'" icon="tabler-alert-circle" />
        <VIcon v-else icon="tabler-info-circle" />
      </template>
      {{ message }}
    </VSnackbar>

    <!-- Delete confirm -->
    <VDialog v-model="isDeleteConfirmDialogVisible" max-width="500px">
      <VCard>
        <VCardTitle class="text-h6">تأكيد الحذف</VCardTitle>
        <VCardText>هل أنت متأكد أنك تريد حذف هذا السؤال؟</VCardText>
        <VCardActions class="px-6 pb-4">
          <VSpacer />
          <VBtn color="error" variant="flat" @click="isDeleteConfirmDialogVisible = false">
            إلغاء
          </VBtn>
          <VBtn color="success" variant="flat" @click="executeDelete">
            موافق
          </VBtn>
        </VCardActions>
      </VCard>
    </VDialog>

    <!-- View full FAQ -->
    <VDialog v-model="isViewDialogVisible" max-width="700px">
      <VCard>
        <VCardTitle class="text-h6">عرض السؤال والإجابة</VCardTitle>
        <VCardText>
          <p><strong>السؤال (AR):</strong> {{ selectedFAQ?.question }}</p>
          <p><strong>الإجابة (AR):</strong> {{ selectedFAQ?.answer }}</p>
          <VDivider class="my-2" />
          <p><strong>Question (EN):</strong> {{ selectedFAQ?.question_en }}</p>
          <p><strong>Answer (EN):</strong> {{ selectedFAQ?.answer_en }}</p>
        </VCardText>
        <VCardActions class="px-6 pb-4">
          <VSpacer />
          <VBtn color="primary" variant="flat" @click="isViewDialogVisible = false">إغلاق</VBtn>
        </VCardActions>
      </VCard>
    </VDialog>
  </section>
</template>
