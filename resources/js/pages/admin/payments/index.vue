<script setup>
import { ref, computed, onMounted, watch } from 'vue'
import { useRouter } from 'vue-router'
import AddPaymentDrawer from './AddPaymentDrawer.vue'
import EditPaymentDrawer from './EditPaymentDrawer.vue'

// Router
const router = useRouter()

// Filters
const searchQuery = ref('')
const filterPlan = ref(null)            // plan id
const filterStatus = ref(null)          // 'pending'|'completed'|'failed'
const filterPaymentMethod = ref(null)   // e.g. 'credit_card'
const filterPaidStart = ref(null)       // YYYY-MM-DD
const filterPaidEnd = ref(null)         // YYYY-MM-DD

// Plans data for the select field
const plans = ref([])
const fetchPlans = async () => {
  try {
    const plansResp = await $api('/plans', { method: 'GET', params: { per_page: 200 } })
    // handle paginated or raw array response
    if (plansResp && plansResp.status === 'success') {
      plans.value = Array.isArray(plansResp.data) ? plansResp.data : (plansResp.data?.data ?? plansResp.data ?? [])
    } else {
      plans.value = []
    }
  } catch (err) {
    console.error('Error fetching plans', err)
    plans.value = []
  }
}

// Data table
const itemsPerPage = ref(10)
const page = ref(1)
const sortBy = ref()
const orderBy = ref()
const selectedRows = ref([])

const updateOptions = options => {
  sortBy.value = options.sortBy?.[0]?.key
  orderBy.value = options.sortBy?.[0]?.order
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

// Delete dialog
const isDeleteConfirmDialogVisible = ref(false)
const paymentToDeleteId = ref(null)
const confirmDelete = id => {
  paymentToDeleteId.value = id
  isDeleteConfirmDialogVisible.value = true
}
const executeDelete = async () => {
  if (!paymentToDeleteId.value) return
  await deletePayment(paymentToDeleteId.value)
  isDeleteConfirmDialogVisible.value = false
  paymentToDeleteId.value = null
}

// Headers
const headers = [
  { title: 'المستخدم', key: 'user_name' },
  { title: 'الباقة', key: 'plan_name' },
  { title: 'المبلغ', key: 'amount' },
  { title: 'طريقة الدفع', key: 'payment_method' },
  { title: 'الحالة', key: 'status' },
  { title: 'تاريخ الدفع', key: 'paid_at' },
  { title: 'العمليات', key: 'actions', sortable: false },
]

// API
const paymentsData = ref([])
const totalPayments = ref(0)
const loading = ref(true)

const fetchPayments = async () => {
  loading.value = true
  try {
    const params = {
      search: searchQuery.value || undefined,
      plan_id: filterPlan.value || undefined,
      status: filterStatus.value || undefined,
      payment_method: filterPaymentMethod.value || undefined,
      paid_from: filterPaidStart.value || undefined,
      paid_to: filterPaidEnd.value || undefined,
      per_page: itemsPerPage.value,
      page: page.value,
      sort_by: sortBy.value || undefined,
      sort_order: orderBy.value || undefined,
    }

    const response = await $api('/payments', { method: 'GET', params })
    if (response && response.status === 'success') {
      // map to include user_name & plan_name for table convenience
      paymentsData.value = (response.data || []).map(p => ({
        ...p,
        user_name: p.subscription?.user?.name ?? p.user_name ?? '-',
        plan_name: p.subscription?.plan?.name ?? p.plan_name ?? '-'
      }))
      totalPayments.value = response.meta?.total || 0
    } else {
      paymentsData.value = []
      totalPayments.value = 0
    }
  } catch (err) {
    console.error('Error fetching payments', err)
    paymentsData.value = []
    totalPayments.value = 0
  } finally {
    loading.value = false
  }
}

onMounted(async () => {
  await fetchPlans()
  fetchPayments()
})

// watch filters and table controls -> backend fetch
watch(
  [searchQuery, filterPlan, filterStatus, filterPaymentMethod, filterPaidStart, filterPaidEnd, itemsPerPage, page, sortBy, orderBy],
  () => {
    page.value = 1
    fetchPayments()
  }
)

const payments = computed(() => paymentsData.value)

// Add
const isAddPaymentDrawerVisible = ref(false)
const addNewPayment = async paymentData => {
  try {
    await $api('/payments/store', { method: 'POST', body: paymentData })
    triggerToast('تم إضافة الدفع بنجاح', 'success')
    fetchPayments()
  } catch (err) {
    triggerToast('حدث خطأ، حاول مرة أخرى', 'error')
  }
}

// Edit
const isEditPaymentDrawerVisible = ref(false)
const paymentToEdit = ref(null)
const openEditDrawer = payment => {
  paymentToEdit.value = payment
  isEditPaymentDrawerVisible.value = true
}
const updatePayment = async (id, paymentData) => {
  try {
    await $api(`/payments/update/${id}`, { method: 'POST', body: paymentData })
    triggerToast('تم تعديل الدفع بنجاح', 'success')
    fetchPayments()
  } catch (err) {
    triggerToast('حدث خطأ، حاول مرة أخرى', 'error')
  }
}

// Delete function
const deletePayment = async id => {
  try {
    await $api(`/payments/delete/${id}`, { method: 'POST' })
    const index = selectedRows.value.findIndex(row => row === id)
    if (index !== -1) selectedRows.value.splice(index, 1)
    triggerToast('تم حذف الدفع بنجاح', 'success')
    fetchPayments()
  } catch (err) {
    triggerToast('حدث خطأ أثناء الحذف', 'error')
  }
}

const openView = (id) => {
  // open linked subscription view (if id is subscription id)
  router.push(`/admin/subscriptions/${id}`)
}

// Reset filters
const resetFilters = () => {
  searchQuery.value = ''
  filterPlan.value = null
  filterStatus.value = null
  filterPaymentMethod.value = null
  filterPaidStart.value = null
  filterPaidEnd.value = null
}
</script>

<template>
  <section>
    <VCard class="mb-6">
      <VCardItem class="pb-4">
        <VCardTitle>المدفوعات</VCardTitle>
      </VCardItem>

      <!-- Filters -->
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

        <div style="min-width: 200px;">
          <AppTextField
            v-model="searchQuery"
            placeholder="بحث باسم المستخدم"
            label="بحث"
          />
        </div>

        <div style="min-width: 180px;">
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

        <div style="min-width: 160px;">
          <AppSelect
            v-model="filterStatus"
            :items="[
              { value: 'pending', title: 'قيد الانتظار' },
              { value: 'completed', title: 'مكتمل' },
              { value: 'failed', title: 'فشل' }
            ]"
            item-value="value"
            item-title="title"
            label="الحالة"
            clearable
            placeholder="اختر حالة"
          />
        </div>

        <div style="min-width: 160px;">
          <AppSelect
            v-model="filterPaymentMethod"
            :items="[
              { value: 'credit_card', title: 'بطاقة ائتمان' },
              { value: 'bank_transfer', title: 'تحويل بنكي' },
              { value: 'paypal', title: 'باي بال' },
              { value: 'cash', title: 'نقداً' }
            ]"
            item-value="value"
            item-title="title"
            label="طريقة الدفع"
            clearable
            placeholder="اختر طريقة"
          />
        </div>

        <div>
          <AppDateTimePicker
            v-model="filterPaidStart"
            label="تاريخ الدفع من"
            placeholder="اختر التاريخ"
            style="min-width: 200px;"
            clearable
          />
        </div>

        <div>
          <AppDateTimePicker
            v-model="filterPaidEnd"
            label="تاريخ الدفع إلى"
            placeholder="اختر التاريخ"
            style="min-width: 200px;"
            clearable
          />
        </div>

        <VSpacer/>

        <VBtn prepend-icon="tabler-rotate-clockwise" @click="resetFilters" variant="outlined">
          إعادة تعيين
        </VBtn>

        <VBtn prepend-icon="tabler-plus" @click="isAddPaymentDrawerVisible = true">
          إضافة دفع جديد
        </VBtn>
      </VCardText>

      <VDivider/>

      <VDataTableServer
        v-model:items-per-page="itemsPerPage"
        v-model:model-value="selectedRows"
        v-model:page="page"
        :items="payments"
        item-value="id"
        :items-length="totalPayments"
        :headers="headers"
        class="text-no-wrap"
        :loading="loading"
        @update:options="updateOptions"
      >
        <template #item.user_name="{ item }">
          <a @click.prevent="router.push(`/admin/users/${item.subscription?.user?.id ?? item.subscription?.user_id}`)" class="text-primary cursor-pointer">
            {{ item.user_name }}
          </a>
        </template>

        <template #item.plan_name="{ item }">{{ item.plan_name }}</template>

        <template #item.actions="{ item }">
          <IconBtn @click="openView(item.subscription?.id)"><VIcon icon="tabler-eye"/></IconBtn>
          <IconBtn @click="openEditDrawer(item)"><VIcon icon="tabler-pencil"/></IconBtn>
          <IconBtn @click="confirmDelete(item.id)"><VIcon icon="tabler-trash"/></IconBtn>
        </template>
        <template #bottom>
          <TablePagination
            v-model:page="page"
            :items-per-page="itemsPerPage"
            :total-items="totalPayments"
          />
        </template>
      </VDataTableServer>
    </VCard>

    <AddPaymentDrawer
      v-model:is-drawer-open="isAddPaymentDrawerVisible"
      @plan-data="addNewPayment"
    />

    <EditPaymentDrawer
      v-model:is-drawer-open="isEditPaymentDrawerVisible"
      :plan-data="paymentToEdit"
      @plan-data="updatePayment"
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
        <VCardText>هل أنت متأكد أنك تريد حذف هذا الدفع؟ لا يمكن التراجع عن هذا الإجراء.</VCardText>
        <VCardActions class="px-6 pb-4">
          <VSpacer/>
          <VBtn color="error" variant="flat" @click="isDeleteConfirmDialogVisible=false">إلغاء</VBtn>
          <VBtn color="success" variant="flat" @click="executeDelete">موافق</VBtn>
        </VCardActions>
      </VCard>
    </VDialog>
  </section>
</template>

<style scoped>
/* صغير لتحسين مظهر حقول التاريخ */
.v-card-text .v-field input[type="date"] {
  height: 36px;
  padding: 6px 10px;
}
</style>
