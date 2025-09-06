<script setup>
import { ref, computed, watch, onMounted } from 'vue'
import { useRouter } from 'vue-router'
import AddNewPlanDrawer from './AddNewPlanDrawer.vue'
import EditPlanDrawer from './EditPlanDrawer.vue'

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
const planToDeleteId = ref(null)
const confirmDelete = id => {
  planToDeleteId.value = id
  isDeleteConfirmDialogVisible.value = true
}
const executeDelete = async () => {
  if (!planToDeleteId.value) return
  await deletePlan(planToDeleteId.value)
  isDeleteConfirmDialogVisible.value = false
  planToDeleteId.value = null
}

// Headers
const headers = [
  { title: 'اسم باقة الاشتراك', key: 'name' },
  { title: 'عدد الكلمات', key: 'words_limit' },
  { title: 'السعر', key: 'price' },
  { title: 'المدة (شهور)', key: 'duration_months' },
  { title: 'عدد المشتركين', key: 'subscriptions' },
  { title: 'الوصف', key: 'description' },
  { title: 'العمليات', key: 'actions', sortable: false },
]

// API
const plansData = ref([])
const totalPlans = ref(0)
const loading = ref(true)

const fetchPlans = async () => {
  loading.value = true
  try {
    const response = await $api('/plans', {
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
      plansData.value = response.data
      totalPlans.value = response.meta?.total || 0
    }
  } catch (err) {
    console.error('Error fetching plans', err)
  } finally {
    loading.value = false
  }
}

onMounted(fetchPlans)
watch([searchQuery, itemsPerPage, page, sortBy, orderBy], fetchPlans)
const plans = computed(() => plansData.value)

// Add
const isAddNewPlanDrawerVisible = ref(false)
const addNewPlan = async planData => {
  try {
    await $api('/plans/store', {
      method: 'POST',
      body: planData,
    })
    triggerToast('تم إضافة باقة الاشتراك بنجاح', 'success')
    fetchPlans()
  } catch (err) {
    triggerToast('حدث خطأ، حاول مرة أخرى', 'error')
  }
}

// Edit
const isEditPlanDrawerVisible = ref(false)
const planToEdit = ref(null)
const openEditDrawer = plan => {
  planToEdit.value = plan
  isEditPlanDrawerVisible.value = true
}
const updatePlan = async (id, planData) => {
  try {
    await $api(`/plans/update/${id}`, {
      method: 'POST',
      body: planData,
    })
    triggerToast('تم تعديل باقة الاشتراك بنجاح', 'success')
    fetchPlans()
  } catch (err) {
    triggerToast('حدث خطأ، حاول مرة أخرى', 'error')
  }
}

// Delete plan
const deletePlan = async id => {
  try {
    await $api(`/plans/delete/${id}`, { method: 'POST' })
    const index = selectedRows.value.findIndex(row => row === id)
    if (index !== -1) selectedRows.value.splice(index, 1)
    triggerToast('تم حذف باقة الاشتراك بنجاح', 'success')
    fetchPlans()
  } catch (err) {
    triggerToast('حدث خطأ أثناء الحذف', 'error')
  }
}
</script>

<template>
  <section>
    <VCard class="mb-6">
      <VCardItem class="pb-4">
        <VCardTitle>باقات الاشتراك</VCardTitle>
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

          <VBtn prepend-icon="tabler-plus" @click="isAddNewPlanDrawerVisible = true">
            اضافة خطة جديدة
          </VBtn>
        </div>
      </VCardText>

      <VDivider/>

      <VDataTableServer
        v-model:items-per-page="itemsPerPage"
        v-model:model-value="selectedRows"
        v-model:page="page"
        :items="plans"
        item-value="id"
        :items-length="totalPlans"
        :headers="headers"
        class="text-no-wrap"
        :loading="loading"
        @update:options="updateOptions"
      >
        <template #item.actions="{ item }">
          <IconBtn @click="openEditDrawer(item)">
            <VIcon icon="tabler-pencil"/>
          </IconBtn>
          <IconBtn @click="confirmDelete(item.id)">
            <VIcon icon="tabler-trash"/>
          </IconBtn>
        </template>
      </VDataTableServer>
    </VCard>

    <AddNewPlanDrawer
      v-model:is-drawer-open="isAddNewPlanDrawerVisible"
      @plan-data="addNewPlan"
    />

    <EditPlanDrawer
      v-model:is-drawer-open="isEditPlanDrawerVisible"
      :plan-data="planToEdit"
      @plan-data="updatePlan"
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
        <VCardText>هل أنت متأكد أنك تريد حذف هذه باقة الاشتراك؟ لا يمكن التراجع عن هذا الإجراء.</VCardText>
        <VCardActions class="px-6 pb-4">
          <VSpacer/>
          <VBtn color="error" variant="flat" @click="isDeleteConfirmDialogVisible=false">إلغاء</VBtn>
          <VBtn color="success" variant="flat" @click="executeDelete">موافق</VBtn>
        </VCardActions>
      </VCard>
    </VDialog>
  </section>
</template>
