<script setup>
import { ref, computed, watch, onMounted } from 'vue'
import { useRouter } from 'vue-router'
import AddSubscriptionDrawer from './AddSubscriptionDrawer.vue'
import EditSubscriptionDrawer from './EditSubscriptionDrawer.vue'

// Router
const router = useRouter()

// Filters
const searchQuery = ref('')
const filterPlan = ref(null)
const filterStatus = ref(null)
const filterStartDate = ref(null)
const filterEndDate = ref(null)

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
const subscriptionToDeleteId = ref(null)
const confirmDelete = id => {
  subscriptionToDeleteId.value = id
  isDeleteConfirmDialogVisible.value = true
}
const executeDelete = async () => {
  if (!subscriptionToDeleteId.value) return
  await deleteSubscription(subscriptionToDeleteId.value)
  isDeleteConfirmDialogVisible.value = false
  subscriptionToDeleteId.value = null
}

// Headers
const headers = [
  { title: 'اسم المستخدم', key: 'user_name' },
  { title: 'الباقة', key: 'plan_name' },
  { title: 'تاريخ البداية', key: 'start_date' },
  { title: 'تاريخ النهاية', key: 'end_date' },
  { title: 'الوقت المتبقي', key: 'time_left' },
  { title: 'الحالة', key: 'status' },
  { title: 'المبلغ المدفوع', key: 'amount_paid' },
  { title: 'العمليات', key: 'actions', sortable: false },
]

// API
const subscriptionsData = ref([])
const totalSubscriptions = ref(0)
const loading = ref(true)

// Fetch data with backend filtering
// Vue.js code
const fetchSubscriptions = async () => {
  loading.value = true;
  try {
    const params = {
      search: searchQuery.value || undefined,
      // الحل هنا: قم بإرسال معرف الباقة مباشرة
      plan_id: filterPlan.value || undefined,
      status: filterStatus.value || undefined,
      start_date: filterStartDate.value || undefined,
      end_date: filterEndDate.value || undefined,
      per_page: itemsPerPage.value,
      page: page.value,
      sort_by: sortBy.value,
      sort_order: orderBy.value,
    };

    const response = await $api('/subscriptions', { method: 'GET', params });
    if (response.status === 'success') {
      subscriptionsData.value = response.data;
      totalSubscriptions.value = response.meta?.total || 0;
    }
  } catch (err) {
    console.error('Error fetching subscriptions', err);
  } finally {
    loading.value = false;
  }
};

onMounted(fetchSubscriptions)
watch(
  [searchQuery, filterPlan, filterStatus, filterStartDate, filterEndDate, itemsPerPage, page, sortBy, orderBy],
  fetchSubscriptions
)

const subscriptions = computed(() => subscriptionsData.value)

// حساب الوقت المتبقي
const getTimeLeft = endDate => {
  const end = new Date(endDate)
  const now = new Date()
  const diffMs = end - now
  if (diffMs <= 0) return 'انتهى'
  const diffDays = Math.floor(diffMs / (1000 * 60 * 60 * 24))
  const diffHours = Math.floor((diffMs / (1000 * 60 * 60)) % 24)
  return `${diffDays} يوم ${diffHours} ساعة`
}

// Add / Edit
const isAddSubscriptionDrawerVisible = ref(false)
const isEditSubscriptionDrawerVisible = ref(false)
const subscriptionToEdit = ref(null)

const openEditDrawer = subscription => {
  subscriptionToEdit.value = subscription
  isEditSubscriptionDrawerVisible.value = true
}
const openView = (id) => router.push(`/admin/subscriptions/${id}`)

const addNewSubscription = async formData => {
  try {
    await $api('/subscriptions/store', { method: 'POST', body: formData })
    triggerToast('تم إضافة الاشتراك بنجاح', 'success')
    fetchSubscriptions()
  } catch (err) {
    triggerToast('حدث خطأ، حاول مرة أخرى', 'error')
  }
}

const updateSubscription = async (id, formData) => {
  try {
    await $api(`/subscriptions/update/${id}`, { method: 'POST', body: formData })
    triggerToast('تم تعديل الاشتراك بنجاح', 'success')
    fetchSubscriptions()
  } catch (err) {
    triggerToast('حدث خطأ، حاول مرة أخرى', 'error')
  }
}

const deleteSubscription = async id => {
  try {
    await $api(`/subscriptions/delete/${id}`, { method: 'POST' })
    triggerToast('تم حذف الاشتراك بنجاح', 'success')
    fetchSubscriptions()
  } catch (err) {
    triggerToast('حدث خطأ أثناء الحذف', 'error')
  }
}
</script>

<template>
  <section>
    <VCard class="mb-6">
      <VCardItem class="pb-4">
        <VCardTitle>الاشتراكات</VCardTitle>
      </VCardItem>

      <!-- Filters -->
      <!-- Filters -->
      <VCardText class="d-flex flex-wrap gap-4 align-center">

        <!-- حقل البحث مع ليبل -->
        <div style="min-width: 200px;">
          <AppTextField
            v-model="searchQuery"
            placeholder="بحث بالاسم"
            label="اسم المستخدم"
          />
        </div>

        <!-- اختيار الباقة -->
        <AppSelect
          v-model="filterPlan"
          :items="subscriptionsData.map(s => ({ value: s.plan.id, title: s.plan.name }))"
          label="الباقة"
          item-value="value"
          item-title="title"
        />

        <!-- اختيار الحالة -->
        <div style="min-width: 150px;">
          <AppSelect
            v-model="filterStatus"
            :items="[
        { value: 'active', title: 'نشط' },
        { value: 'expired', title: 'منتهي' },
        { value: 'canceled', title: 'ملغى' }
      ]"
            label="الحالة"
          />
        </div>

        <!-- تاريخ البداية -->
        <div>
          <label for="startDate">تاريخ البداية</label>
          <input
            id="startDate"
            type="date"
            v-model="filterStartDate"
            class="form-control"
          />
        </div>

        <!-- تاريخ النهاية -->
        <div>
          <label for="endDate">تاريخ النهاية</label>
          <input
            id="endDate"
            type="date"
            v-model="filterEndDate"
            class="form-control"
          />
        </div>

        <VSpacer/>

        <VBtn prepend-icon="tabler-plus" @click="isAddSubscriptionDrawerVisible = true">
          إضافة اشتراك جديد
        </VBtn>

      </VCardText>


      <VDivider/>

      <VDataTableServer
        v-model:items-per-page="itemsPerPage"
        v-model:model-value="selectedRows"
        v-model:page="page"
        :items="subscriptions"
        item-value="id"
        :items-length="totalSubscriptions"
        :headers="headers"
        class="text-no-wrap"
        :loading="loading"
        @update:options="updateOptions"
      >
        <template #item.user_name="{ item }">
          <a @click.prevent="router.push(`/admin/users/${item.user.id}`)" class="text-primary cursor-pointer">
            {{ item.user.name }}
          </a>
        </template>
        <template #item.plan_name="{ item }">{{ item.plan.name }}</template>
        <template #item.time_left="{ item }">{{ getTimeLeft(item.end_date) }}</template>
        <template #item.actions="{ item }">
          <IconBtn @click="openView(item.id)"><VIcon icon="tabler-eye"/></IconBtn>
          <IconBtn @click="openEditDrawer(item)"><VIcon icon="tabler-pencil"/></IconBtn>
          <IconBtn @click="confirmDelete(item.id)"><VIcon icon="tabler-trash"/></IconBtn>
        </template>
      </VDataTableServer>
    </VCard>

    <AddSubscriptionDrawer
      v-model:is-drawer-open="isAddSubscriptionDrawerVisible"
      @plan-data="addNewSubscription"
    />

    <EditSubscriptionDrawer
      v-model:is-drawer-open="isEditSubscriptionDrawerVisible"
      :plan-data="subscriptionToEdit"
      @plan-data="updateSubscription"
    />

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
        <VCardText>هل أنت متأكد أنك تريد حذف هذا الاشتراك؟ لا يمكن التراجع عن هذا الإجراء.</VCardText>
        <VCardActions class="px-6 pb-4">
          <VSpacer/>
          <VBtn color="error" variant="flat" @click="isDeleteConfirmDialogVisible=false">إلغاء</VBtn>
          <VBtn color="success" variant="flat" @click="executeDelete">موافق</VBtn>
        </VCardActions>
      </VCard>
    </VDialog>
  </section>
</template>
