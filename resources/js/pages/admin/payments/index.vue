<script setup>
import {ref, computed, onMounted, watch} from 'vue'
import {useRouter} from 'vue-router'
import AddPaymentDrawer from './AddPaymentDrawer.vue'
import EditPaymentDrawer from './EditPaymentDrawer.vue'

// Router
const router = useRouter()

// Filters
const searchQuery = ref('')

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
  {title: 'المستخدم', key: 'user_name'},
  {title: 'الباقة', key: 'plan_name'},
  {title: 'المبلغ', key: 'amount'},
  {title: 'طريقة الدفع', key: 'payment_method'},
  {title: 'الحالة', key: 'status'},
  {title: 'تاريخ الدفع', key: 'paid_at'},
  {title: 'العمليات', key: 'actions', sortable: false},
]

// API
const paymentsData = ref([])
const totalPayments = ref(0)
const loading = ref(true)

const fetchPayments = async () => {
  loading.value = true
  try {
    const response = await $api('/payments', {
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
      paymentsData.value = response.data.map(p => ({
        ...p,
        user_name: p.subscription.user.name,
        plan_name: p.subscription.plan.name
      }))
      totalPayments.value = response.meta?.total || 0
    }
  } catch (err) {
    console.error('Error fetching payments', err)
  } finally {
    loading.value = false
  }
}

onMounted(fetchPayments)
watch([searchQuery, itemsPerPage, page, sortBy, orderBy], fetchPayments)
const payments = computed(() => paymentsData.value)

// Add
const isAddPaymentDrawerVisible = ref(false)
const addNewPayment = async paymentData => {
  try {
    await $api('/payments/store', {method: 'POST', body: paymentData})
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
    await $api(`/payments/update/${id}`, {method: 'POST', body: paymentData})
    triggerToast('تم تعديل الدفع بنجاح', 'success')
    fetchPayments()
  } catch (err) {
    triggerToast('حدث خطأ، حاول مرة أخرى', 'error')
  }
}

// Delete
const deletePayment = async id => {
  try {
    await $api(`/payments/delete/${id}`, {method: 'POST'})
    const index = selectedRows.value.findIndex(row => row === id)
    if (index !== -1) selectedRows.value.splice(index, 1)
    triggerToast('تم حذف الدفع بنجاح', 'success')
    fetchPayments()
  } catch (err) {
    triggerToast('حدث خطأ أثناء الحذف', 'error')
  }
}

const openView = (id) => {
  router.push(`/admin/subscriptions/${id}`)
}

</script>

<template>
  <section>
    <VCard class="mb-6">
      <VCardItem class="pb-4">
        <VCardTitle>المدفوعات</VCardTitle>
      </VCardItem>

      <VCardText class="d-flex flex-wrap gap-4">
        <div class="me-3 d-flex gap-3">
          <AppSelect
            :model-value="itemsPerPage"
            :items="[10,25,50,100].map(i=>({ value:i, title:i }))"
            style="inline-size: 6.25rem;"
            @update:model-value="itemsPerPage = parseInt($event, 10)"
          />
        </div>
        <VSpacer/>
        <div class="app-plan-search-filter d-flex align-center flex-wrap gap-4">
          <div style="inline-size: 15.625rem;">
            <AppTextField v-model="searchQuery" placeholder="بحث"/>
          </div>

          <VBtn prepend-icon="tabler-plus" @click="isAddPaymentDrawerVisible = true">
            اضافة دفع جديد
          </VBtn>
        </div>
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
          <a @click.prevent="router.push(`/pages/admin/users/${item.subscription.user.id}`)">{{ item.user_name }}</a>
        </template>

        <template #item.actions="{ item }">

          <IconBtn @click="openView(item.subscription?.id)">
            <VIcon icon="tabler-eye"/>
          </IconBtn>

          <IconBtn @click="openEditDrawer(item)">
            <VIcon icon="tabler-pencil"/>
          </IconBtn>
          <IconBtn @click="confirmDelete(item.id)">
            <VIcon icon="tabler-trash"/>
          </IconBtn>
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
