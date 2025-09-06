<script setup>
import { ref, computed, watch, onMounted } from 'vue'

// Filters
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
const messageToDeleteId = ref(null)

const confirmDelete = msgId => {
  messageToDeleteId.value = msgId
  isDeleteConfirmDialogVisible.value = true
}

const executeDelete = async () => {
  if (messageToDeleteId.value) {
    await deleteMessage(messageToDeleteId.value)
    isDeleteConfirmDialogVisible.value = false
    messageToDeleteId.value = null
  }
}

const formatDate = dateStr => {
  if (!dateStr) return ''
  const date = new Date(dateStr)
  const y = date.getFullYear()
  const m = String(date.getMonth() + 1).padStart(2, '0')
  const d = String(date.getDate()).padStart(2, '0')

  let h = date.getHours()
  const min = String(date.getMinutes()).padStart(2, '0')
  const ampm = h >= 12 ? 'PM' : 'AM'
  h = h % 12
  h = h ? h : 12
  const hourStr = String(h).padStart(2, '0')

  return `${y}-${m}-${d} ${hourStr}:${min} ${ampm}`
}

// View full message dialog
const isViewDialogVisible = ref(false)
const selectedMessage = ref(null)

const viewMessage = msg => {
  selectedMessage.value = msg
  isViewDialogVisible.value = true
}

// Headers
const headers = [
  { title: 'الاسم', key: 'name' },
  { title: 'البريد الإلكتروني', key: 'email' },
  { title: 'الموضوع', key: 'subject' },
  { title: 'الرسالة', key: 'message' },
  { title: 'التاريخ', key: 'created_at' },
  { title: 'العمليات', key: 'actions', sortable: false },
]

// API
const messagesData = ref([])
const totalMessages = ref(0)
const loading = ref(true)

const fetchMessages = async () => {
  loading.value = true
  try {
    const response = await $api('/messages', {
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
      messagesData.value = response.data.data
      totalMessages.value = response.data.total
    }
  } catch (err) {
    console.error('Error fetching messages', err)
  } finally {
    loading.value = false
  }
}

onMounted(fetchMessages)
watch([searchQuery, itemsPerPage, page, sortBy, orderBy], fetchMessages)

const messages = computed(() => messagesData.value)

// Delete message
const deleteMessage = async id => {
  try {
    await $api(`/messages/${id}/delete`, { method: 'POST' })
    triggerToast('تم حذف الرسالة بنجاح', 'success')
    fetchMessages()
  } catch (err) {
    triggerToast('حدث خطأ أثناء الحذف', 'error')
  }
}

// Utility: truncate text
const truncateText = text => {
  if (!text) return ''
  const words = text.split(' ')
  return words.length > 5 ? words.slice(0,5).join(' ') + '...' : text
}

// Reply via email
const replyEmail = email => {
  window.location.href = `mailto:${email}`
}
</script>

<template>
  <section>
    <VCard class="mb-6">
      <VCardItem class="pb-4">
        <VCardTitle>الرسائل الواردة</VCardTitle>
      </VCardItem>

      <!-- Search -->
      <VCardText class="d-flex flex-wrap gap-4 align-center">
        <div style="inline-size: 15.625rem;">
          <AppTextField v-model="searchQuery" placeholder="بحث..." />
        </div>
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
      </VCardText>

      <VDivider />

      <VDataTableServer
        v-model:items-per-page="itemsPerPage"
        v-model:model-value="selectedRows"
        v-model:page="page"
        :items="messages"
        item-value="id"
        :items-length="totalMessages"
        :headers="headers"
        class="text-no-wrap"
        :loading="loading"
        @update:options="updateOptions"
      >
        <!-- Truncate message preview -->
        <template #item.message="{ item }">
          {{ truncateText(item.message) }}
        </template>

        <template #item.created_at="{ item }">
          {{ formatDate(item.created_at) }}
        </template>

        <!-- Actions -->
        <template #item.actions="{ item }">
          <IconBtn @click="viewMessage(item)">
            <VIcon icon="tabler-eye" />
          </IconBtn>
          <IconBtn @click="replyEmail(item.email)">
            <VIcon icon="tabler-mail" />
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
            :total-items="totalMessages"
          />
        </template>
      </VDataTableServer>
    </VCard>

    <!-- View full message -->
    <VDialog v-model="isViewDialogVisible" max-width="700px">
      <VCard>
        <VCardTitle class="text-h6">عرض الرسالة كاملة</VCardTitle>
        <VCardText>
          <p><strong>الاسم:</strong> {{ selectedMessage?.name }}</p>
          <p><strong>البريد الإلكتروني:</strong> {{ selectedMessage?.email }}</p>
          <p><strong>الموضوع:</strong> {{ selectedMessage?.subject || 'لا يوجد' }}</p>
          <VDivider class="my-2" />
          <p><strong>الرسالة:</strong></p>
          <p>{{ selectedMessage?.message }}</p>
        </VCardText>
        <VCardActions class="px-6 pb-4">
          <VSpacer />
          <VBtn color="primary" variant="flat" @click="isViewDialogVisible = false">إغلاق</VBtn>
        </VCardActions>
      </VCard>
    </VDialog>

    <!-- Delete confirm -->
    <VDialog v-model="isDeleteConfirmDialogVisible" max-width="500px">
      <VCard>
        <VCardTitle class="text-h6">تأكيد الحذف</VCardTitle>
        <VCardText>هل أنت متأكد أنك تريد حذف هذه الرسالة؟</VCardText>
        <VCardActions class="px-6 pb-4">
          <VSpacer />
          <VBtn color="error" variant="flat" @click="isDeleteConfirmDialogVisible = false">إلغاء</VBtn>
          <VBtn color="success" variant="flat" @click="executeDelete">موافق</VBtn>
        </VCardActions>
      </VCard>
    </VDialog>

    <!-- Snackbar -->
    <VSnackbar v-model="showToast" :color="color" location="top end" timeout="5000">
      <template #prepend>
        <VIcon v-if="color === 'success'" icon="tabler-check" />
        <VIcon v-else-if="color === 'error'" icon="tabler-alert-circle" />
        <VIcon v-else icon="tabler-info-circle" />
      </template>
      {{ message }}
    </VSnackbar>
  </section>
</template>
