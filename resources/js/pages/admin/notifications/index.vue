<script setup>
import { ref, computed, watch, onMounted } from 'vue'

const searchQuery = ref('')
const filterIsRead = ref(null)
const filterRecipient = ref(null) // New filter for recipient
const filterStartDate = ref(null)
const filterEndDate = ref(null)

const itemsPerPage = ref(10)
const page = ref(1)
const sortBy = ref()
const orderBy = ref()
const selectedRows = ref([])

const updateOptions = options => {
  sortBy.value = options.sortBy?.[0]?.key
  orderBy.value = options.sortBy?.[0]?.order
}

const showToast = ref(false)
const message = ref('')
const color = ref('success')

const triggerToast = (msg, type = 'success') => {
  message.value = msg
  color.value = type
  showToast.value = true
}

const isDeleteConfirmDialogVisible = ref(false)
const notificationToDeleteId = ref(null)

const confirmDelete = notificationId => {
  notificationToDeleteId.value = notificationId
  isDeleteConfirmDialogVisible.value = true
}

const executeDelete = async () => {
  if (notificationToDeleteId.value) {
    await deleteNotification(notificationToDeleteId.value)
    isDeleteConfirmDialogVisible.value = false
    notificationToDeleteId.value = null
  }
}

const headers = [
  { title: 'نص الإشعار', key: 'message' },
  { title: 'الحالة', key: 'status', sortable: false },
  { title: 'المُستلم', key: 'recipient' },
  { title: 'تاريخ الإرسال', key: 'created_at' },
  // { title: 'العمليات', key: 'actions', sortable: false },
]

const notificationsData = ref([])
const totalNotifications = ref(0)
const loading = ref(true)

const fetchNotifications = async () => {
  loading.value = true
  try {
    const params = {
      search: searchQuery.value || undefined,
      is_read: filterIsRead.value || undefined,
      recipient: filterRecipient.value || undefined,
      start_date: filterStartDate.value || undefined,
      end_date: filterEndDate.value || undefined,
      per_page: itemsPerPage.value,
      page: page.value,
      sort_by: sortBy.value || undefined,
      sort_order: orderBy.value || undefined,
    }

    const response = await $api('/notifications', { method: 'GET', params })
    if (response && response.status === 'success') {
      notificationsData.value = response.data
      totalNotifications.value = response.meta?.total || (Array.isArray(response.data) ? response.data.length : 0)
    } else {
      notificationsData.value = []
      totalNotifications.value = 0
    }
  } catch (err) {
    console.error('Error fetching notifications', err)
    notificationsData.value = []
    totalNotifications.value = 0
  } finally {
    loading.value = false
  }
}

const resetFilters = () => {
  searchQuery.value = ''
  filterIsRead.value = null
  filterRecipient.value = null
  filterStartDate.value = null
  filterEndDate.value = null
}

onMounted(() => {
  fetchNotifications()
})

watch(
  [searchQuery, filterIsRead, filterRecipient, filterStartDate, filterEndDate, itemsPerPage, sortBy, orderBy],
  () => {
    page.value = 1
    fetchNotifications()
  },
)

watch(page, () => {
  fetchNotifications()
})

const deleteNotification = async id => {
  try {
    await $api(`/notifications/${id}`, { method: 'DELETE' })
    triggerToast('تم الحذف بنجاح', 'success')
    fetchNotifications()
  } catch (err) {
    triggerToast('حدث خطأ أثناء الحذف', 'error')
  }
}
</script>

<template>
  <section>
    <VCard class="mb-6">
      <VCardItem class="pb-4">
        <VCardTitle>الاشعارات</VCardTitle>
      </VCardItem>

      <VCardText class="d-flex flex-wrap gap-4 align-center">
        <div class="me-3 d-flex gap-3">
          <AppSelect
            label="عرض"
            :model-value="itemsPerPage"
            :items="[10, 25, 50, 100].map(i => ({ value: i, title: i }))"
            style="inline-size: 6.25rem;"
            @update:model-value="itemsPerPage = parseInt($event, 10)"
          />
        </div>

        <div style="min-width: 200px;">
          <AppTextField
            v-model="searchQuery"
            placeholder="بحث بنص الإشعار"
            label="بحث"
          />
        </div>

        <div style="min-width: 200px;">
          <AppSelect
            v-model="filterIsRead"
            :items="[
              { value: false, title: 'غير مقروءة' },
              { value: true, title: 'مقروءة' },
            ]"
            label="الحالة"
            clearable
          />
        </div>

        <div style="min-width: 200px;">
          <AppTextField
            v-model="filterRecipient"
            placeholder="بحث باسم أو ايميل المستلم"
            label="المستلم"
            clearable
          />
        </div>

        <div style="min-width: 200px;">
          <AppDateTimePicker
            v-model="filterStartDate"
            label="تاريخ البداية"
            placeholder="اختر التاريخ"
            clearable
          />
        </div>

        <div style="min-width: 200px;">
          <AppDateTimePicker
            v-model="filterEndDate"
            label="تاريخ النهاية"
            placeholder="اختر التاريخ"
            clearable
          />
        </div>

        <VSpacer />

        <VBtn
          prepend-icon="tabler-rotate-clockwise"
          variant="outlined"
          @click="resetFilters"
        >
          إعادة تعيين
        </VBtn>
      </VCardText>

      <VDivider />

      <VDataTableServer
        v-model:items-per-page="itemsPerPage"
        v-model:page="page"
        :items="notificationsData"
        item-value="id"
        :items-length="totalNotifications"
        :headers="headers"
        class="text-no-wrap"
        :loading="loading"
        @update:options="updateOptions"
      >
        <template #item.message="{ item }">
          <div class="text-truncate" style="max-width: 300px;">
            {{ item.message }}
          </div>
        </template>

        <template #item.status="{ item }">
          <VChip
            :color="item.is_read ? 'success' : 'warning'"
            size="small"
            label
          >
            {{ item.is_read ? 'مقروءة' : 'غير مقروءة' }}
          </VChip>
        </template>

        <template #item.recipient="{ item }">
          <div class="d-flex align-center gap-x-4">
            <VAvatar
              size="34"
              :variant="!item.user?.avatar ? 'tonal' : undefined"
              :color="!item.user?.avatar ? 'primary' : undefined"
            >
              <VImg
                v-if="item.user?.avatar"
                :src="item.user.avatar"
              />
              <span v-else>{{ item.user?.name?.charAt(0).toUpperCase() }}</span>
            </VAvatar>
            <div class="d-flex flex-column">
              <h6 class="text-base">
                {{ item.user?.name }}
              </h6>
              <div class="text-sm">
                {{ item.user?.email }}
              </div>
            </div>
          </div>
        </template>

        <template #item.created_at="{ item }">
          {{ new Date(item.created_at).toLocaleString('ar-EG', { dateStyle: 'short', timeStyle: 'short' }) }}
        </template>

<!--        <template #item.actions="{ item }">-->
<!--          <IconBtn @click="confirmDelete(item.id)">-->
<!--            <VIcon icon="tabler-trash" />-->
<!--          </IconBtn>-->
<!--        </template>-->

        <template #bottom>
          <TablePagination
            v-model:page="page"
            :items-per-page="itemsPerPage"
            :total-items="totalNotifications"
          />
        </template>
      </VDataTableServer>
    </VCard>

    <VSnackbar
      v-model="showToast"
      :color="color"
      location="top end"
      timeout="5000"
    >
      <template #prepend>
        <VIcon
          v-if="color === 'success'"
          icon="tabler-check"
        />
        <VIcon
          v-else-if="color === 'error'"
          icon="tabler-alert-circle"
        />
      </template>
      {{ message }}
      <template #actions>
        <VBtn
          icon
          variant="text"
          color="white"
          @click="showToast = false"
        >
          <VIcon icon="tabler-x" />
        </VBtn>
      </template>
    </VSnackbar>

    <VDialog
      v-model="isDeleteConfirmDialogVisible"
      max-width="500px"
    >
      <VCard>
        <VCardTitle class="text-h6">
          تأكيد الحذف
        </VCardTitle>
        <VCardText>هل أنت متأكد أنك تريد حذف هذا الإشعار؟ لا يمكن التراجع عن هذا الإجراء.</VCardText>
        <VCardActions class="px-6 pb-4">
          <VSpacer />
          <VBtn
            color="error"
            variant="flat"
            @click="isDeleteConfirmDialogVisible = false"
          >
            إلغاء
          </VBtn>
          <VBtn
            color="success"
            variant="flat"
            @click="executeDelete"
          >
            موافق
          </VBtn>
        </VCardActions>
      </VCard>
    </VDialog>
  </section>
</template>

<style scoped>
.v-card-text .v-field input[type="date"] {
  height: 36px;
  padding: 6px 10px;
}
</style>
