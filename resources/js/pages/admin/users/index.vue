<script setup>
import { ref, computed, watch, onMounted } from 'vue'
import { useRouter } from 'vue-router'
import AddNewUserDrawer from './AddNewUserDrawer.vue'
import EditUserDrawer from './EditUserDrawer.vue'

// Router instance
const router = useRouter()

// Filters
const searchQuery = ref('')
const filterRole = ref(null)
const filterPlan = ref(null)
const filterStartDate = ref(null)
const filterEndDate = ref(null)

// Plans list (جلب من API احتياطياً)
const plans = ref([])
const fetchPlans = async () => {
  try {
    const resp = await $api('/plans', { method: 'GET', params: { per_page: 200 } })
    if (resp && resp.status === 'success') {
      // handle both paginated and raw array
      plans.value = Array.isArray(resp.data) ? resp.data : (resp.data?.data ?? resp.data ?? [])
    } else {
      plans.value = []
    }
  } catch (e) {
    console.error('Error fetching plans', e)
    plans.value = []
  }
}

// Data table options
const itemsPerPage = ref(10)
const page = ref(1)
const sortBy = ref()
const orderBy = ref()
const selectedRows = ref([])

const updateOptions = options => {
  sortBy.value = options.sortBy?.[0]?.key
  orderBy.value = options.sortBy?.[0]?.order
}

// Toast state
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
const userToDeleteId = ref(null)
const confirmDelete = userId => {
  userToDeleteId.value = userId
  isDeleteConfirmDialogVisible.value = true
}
const executeDelete = async () => {
  if (userToDeleteId.value) {
    await deleteUser(userToDeleteId.value)
    isDeleteConfirmDialogVisible.value = false
    userToDeleteId.value = null
  }
}

// Helpers
const calculateRemainingDays = endDateString => {
  if (!endDateString) return 'غير محدد'
  const today = new Date()
  const todayMidnight = new Date(today.getFullYear(), today.getMonth(), today.getDate())
  const endDate = new Date(endDateString)
  const endDateMidnight = new Date(endDate.getFullYear(), endDate.getMonth(), endDate.getDate())
  const differenceInTime = endDateMidnight.getTime() - todayMidnight.getTime()
  const differenceInDays = Math.ceil(differenceInTime / (1000 * 60 * 60 * 24))
  if (differenceInDays > 0) return `متبقي ${differenceInDays} يوم`
  if (differenceInDays === 0) return 'ينتهي اليوم'
  return 'منتهية'
}

// Headers
const headers = [
  { title: 'المستخدم', key: 'user' },
  { title: 'النوع', key: 'role' },
  { title: 'عدد الفولدرات', key: 'folders' },
  { title: 'عدد الكلمات', key: 'words' },
  { title: 'باقة الاشتراك', key: 'plan' },
  { title: 'بداية الاشتراك', key: 'start_at' },
  { title: 'نهاية الاشتراك', key: 'expires_at' },
  { title: 'الوقت المتبقي', key: 'remain' },
  { title: 'العمليات', key: 'actions', sortable: false },
]

// API
const usersData = ref([])
const totalUsers = ref(0)
const loading = ref(true)

const fetchUsers = async () => {
  loading.value = true
  try {
    const params = {
      role: filterRole.value || undefined,
      search: searchQuery.value || undefined,
      plan_id: filterPlan.value || undefined,
      start_date: filterStartDate.value || undefined,
      end_date: filterEndDate.value || undefined,
      per_page: itemsPerPage.value,
      page: page.value,
      sort_by: sortBy.value || undefined,
      sort_order: orderBy.value || undefined,
    }

    const response = await $api('/users', { method: 'GET', params })
    if (response && response.status === 'success') {
      usersData.value = response.data
      totalUsers.value = response.meta?.total || 0
    } else {
      usersData.value = []
      totalUsers.value = 0
    }
  } catch (err) {
    console.error('Error fetching users', err)
    usersData.value = []
    totalUsers.value = 0
  } finally {
    loading.value = false
  }
}

// Reset filters
const resetFilters = () => {
  searchQuery.value = ''
  filterRole.value = null
  filterPlan.value = null
  filterStartDate.value = null
  filterEndDate.value = null
}

onMounted(async () => {
  await fetchPlans()
  fetchUsers()
})

// Watch filters and table controls -> backend fetch
watch(
  [searchQuery, filterRole, filterPlan, filterStartDate, filterEndDate, itemsPerPage, sortBy, orderBy],
  () => {
    page.value = 1    // Reset فقط مع الفلاتر
    fetchUsers()
  }
)

// مراقبة تغيير الصفحة فقط
watch(page, () => {
  fetchUsers()
})

const users = computed(() => usersData.value)

// Role display
const resolveUserRoleVariant = role => {
  const roleLowerCase = role?.toLowerCase()
  if (roleLowerCase === 'subscriber') return { color: 'success', icon: 'tabler-user' }
  if (roleLowerCase === 'author') return { color: 'error', icon: 'tabler-device-desktop' }
  if (roleLowerCase === 'maintainer') return { color: 'info', icon: 'tabler-chart-pie' }
  if (roleLowerCase === 'editor') return { color: 'warning', icon: 'tabler-edit' }
  if (roleLowerCase === 'admin') return { color: 'primary', icon: 'tabler-crown' }
  return { color: 'primary', icon: 'tabler-user' }
}

// Add / Edit / Delete
const isAddNewUserDrawerVisible = ref(false)
const isEditUserDrawerVisible = ref(false)
const userToEdit = ref(null)

const addNewUser = async userData => {
  try {
    await $api('/users/store', { method: 'POST', body: userData })
    triggerToast('تم اضافة البيانات بنجاح', 'success')
    fetchUsers()
  } catch (err) {
    triggerToast('حدث خطأ من فضلك حاول في وقت اخر', 'error')
  }
}

const updateUser = async (id, userData) => {
  try {
    await $api(`/users/update/${id}`, { method: 'POST', body: userData })
    triggerToast('تم تعديل البيانات بنجاح', 'success')
    fetchUsers()
  } catch (err) {
    triggerToast('حدث خطأ من فضلك حاول في وقت اخر', 'error')
  }
}

const deleteUser = async id => {
  try {
    await $api(`/users/delete/${id}`, { method: 'POST' })
    triggerToast('تم الحذف بنجاح', 'success')
    fetchUsers()
  } catch (err) {
    triggerToast('حدث خطأ أثناء الحذف', 'error')
  }
}

const openEditDrawer = user => {
  userToEdit.value = user
  isEditUserDrawerVisible.value = true
}

const viewUser = userId => {
  router.push({ name: 'admin-users-id', params: { id: userId } })
}
</script>

<template>
  <section>
    <VCard class="mb-6">
      <VCardItem class="pb-4">
        <VCardTitle>المستخدمين</VCardTitle>
      </VCardItem>

      <!-- Filters -->
      <VCardText class="d-flex flex-wrap gap-4 align-center">
        <!-- عرض كل صفحة -->
        <div class="me-3 d-flex gap-3">
          <AppSelect
            label="عرض"
            :model-value="itemsPerPage"
            :items="[10,25,50,100].map(i=>({ value:i, title:i }))"
            style="inline-size: 6.25rem;"
            @update:model-value="itemsPerPage = parseInt($event, 10)"
          />
        </div>

        <!-- بحث -->
        <div style="min-width: 200px;">
          <AppTextField v-model="searchQuery" placeholder="بحث بالاسم أو الايميل" label="بحث" />
        </div>

        <!-- نوع المستخدم -->
        <div style="min-width: 200px;">
          <AppSelect
            v-model="filterRole"
            :items="[
              { value: 'user', title: 'مستخدم' },
              { value: 'admin', title: 'مدير' }
            ]"
            label="النوع"
            clearable
          />
        </div>

        <!-- باقة الاشتراك -->
        <div style="min-width: 200px;">
          <AppSelect
            v-model="filterPlan"
            :items="plans"
            item-value="id"
            item-title="name"
            label="الباقة"
            clearable
            placeholder="اختر باقة"
          />
        </div>

        <!-- تاريخ البداية -->
        <div style="min-width: 200px;">
          <!-- استخدم AppDateTimePicker إن متوفر عندك، وإلا غيّره إلى AppTextField type="date" -->
          <AppDateTimePicker
            v-model="filterStartDate"
            label="تاريخ البداية"
            placeholder="اختر التاريخ"
            clearable
          />
        </div>

        <!-- تاريخ النهاية -->
        <div style="min-width: 200px;">
          <AppDateTimePicker
            v-model="filterEndDate"
            label="تاريخ النهاية"
            placeholder="اختر التاريخ"
            clearable
          />
        </div>

        <VSpacer />

        <VBtn prepend-icon="tabler-rotate-clockwise" @click="resetFilters" variant="outlined">
          إعادة تعيين
        </VBtn>

        <VBtn prepend-icon="tabler-plus" @click="isAddNewUserDrawerVisible = true">
          إضافة مستخدم جديد
        </VBtn>
      </VCardText>

      <VDivider />

      <!-- Data table -->
      <VDataTableServer
        v-model:items-per-page="itemsPerPage"
        v-model:model-value="selectedRows"
        v-model:page="page"
        :items="users"
        item-value="id"
        :items-length="totalUsers"
        :headers="headers"
        class="text-no-wrap"
        :loading="loading"
        @update:options="updateOptions"
      >
        <template #item.user="{ item }">
          <div class="d-flex align-center gap-x-4">
            <VAvatar size="34" :variant="!item.avatar ? 'tonal' : undefined" :color="!item.avatar ? resolveUserRoleVariant(item.role).color : undefined">
              <VImg v-if="item.avatar" :src="item.avatar" />
              <span v-else>{{ item.name?.charAt(0).toUpperCase() }}</span>
            </VAvatar>
            <div class="d-flex flex-column">
              <h6 class="text-base">
                <RouterLink :to="{ name: 'apps-user-view-id', params: { id: item.id } }" class="font-weight-medium text-link">
                  {{ item.name }}
                </RouterLink>
              </h6>
              <div class="text-sm">{{ item.email }}</div>
            </div>
          </div>
        </template>

        <template #item.role="{ item }">
          <div class="d-flex align-center gap-x-2">
            <VIcon :size="22" :icon="resolveUserRoleVariant(item.role).icon" :color="resolveUserRoleVariant(item.role).color" />
            <div class="text-capitalize text-high-emphasis text-body-1">
              {{ item.role == 'user' ? 'عميل' : 'ادمن' }}
            </div>
          </div>
        </template>

        <template #item.plan="{ item }">
          {{ item.subscription?.plan?.name || 'غير مشترك' }}
        </template>

        <template #item.start_at="{ item }">
          {{ item.subscription?.start_date ?? 'غير محدد' }}
        </template>

        <template #item.expires_at="{ item }">
          {{ item.subscription?.end_date ?? 'غير محدد' }}
        </template>

        <template #item.remain="{ item }">
          {{ calculateRemainingDays(item.subscription?.end_date) }}
        </template>

        <template #item.actions="{ item }">
          <IconBtn @click="openEditDrawer(item)"><VIcon icon="tabler-pencil" /></IconBtn>
          <IconBtn @click="confirmDelete(item.id)"><VIcon icon="tabler-trash" /></IconBtn>
          <IconBtn @click="viewUser(item.id)"><VIcon icon="tabler-eye" /></IconBtn>
        </template>

        <!-- استخدم نفس نظام الباجنيشن القديم -->
        <template #bottom>
          <TablePagination v-model:page="page" :items-per-page="itemsPerPage" :total-items="totalUsers" />
        </template>
      </VDataTableServer>
    </VCard>

    <!-- Drawers -->
    <AddNewUserDrawer v-model:is-drawer-open="isAddNewUserDrawerVisible" @user-data="addNewUser" />
    <EditUserDrawer v-model:is-drawer-open="isEditUserDrawerVisible" :user-data="userToEdit" @user-data="updateUser" />

    <!-- Snackbar -->
    <VSnackbar v-model="showToast" :color="color" location="top end" timeout="5000">
      <template #prepend>
        <VIcon v-if="color === 'success'" icon="tabler-check" />
        <VIcon v-else-if="color === 'error'" icon="tabler-alert-circle" />
      </template>
      {{ message }}
      <template #actions>
        <VBtn icon variant="text" color="white" @click="showToast=false"><VIcon icon="tabler-x" /></VBtn>
      </template>
    </VSnackbar>

    <!-- Delete dialog -->
    <VDialog v-model="isDeleteConfirmDialogVisible" max-width="500px">
      <VCard>
        <VCardTitle class="text-h6">تأكيد الحذف</VCardTitle>
        <VCardText>هل أنت متأكد أنك تريد حذف هذا المستخدم؟ لا يمكن التراجع عن هذا الإجراء.</VCardText>
        <VCardActions class="px-6 pb-4">
          <VSpacer />
          <VBtn color="error" variant="flat" @click="isDeleteConfirmDialogVisible=false">إلغاء</VBtn>
          <VBtn color="success" variant="flat" @click="executeDelete">موافق</VBtn>
        </VCardActions>
      </VCard>
    </VDialog>
  </section>
</template>

<style scoped>
/* تحسين مظهر مدخلات التاريخ إن استعملت AppTextField type="date" كبديل */
.v-card-text .v-field input[type="date"] {
  height: 36px;
  padding: 6px 10px;
}
</style>
