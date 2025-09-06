<script setup>
import { ref, onMounted, nextTick } from 'vue'
import { PerfectScrollbar } from 'vue3-perfect-scrollbar'

const props = defineProps({
  isDrawerOpen: {
    type: Boolean,
    required: true
  }
})

const emit = defineEmits(['update:isDrawerOpen', 'plan-data'])

// Form
const isFormValid = ref(false)
const refForm = ref()

// Fields
const userId = ref(null)
const planId = ref(null)
const startDate = ref('')
const endDate = ref('')
const status = ref('active')
const amountPaid = ref('')

// Options
const users = ref([])
const plans = ref([])
const statuses = ref([
  { value: 'active', title: 'Active' },
  { value: 'expired', title: 'Expired' },
  { value: 'canceled', title: 'Canceled' }
])

// Fetch users & plans
onMounted(async () => {
  try {
    const usersResp = await $api('/users', { method: 'GET' })
    users.value = usersResp.data || []

    const plansResp = await $api('/plans', { method: 'GET' })
    plans.value = plansResp.data || []
  } catch (err) {
    console.error('Error fetching users/plans', err)
  }
})

// Close drawer
const closeDrawer = () => {
  emit('update:isDrawerOpen', false)
  nextTick(() => {
    refForm.value?.reset()
    refForm.value?.resetValidation()
  })
}

// Submit
const onSubmit = () => {
  refForm.value?.validate().then(({ valid }) => {
    if (valid) {
      const formData = new FormData()
      formData.append('user_id', userId.value)
      formData.append('plan_id', planId.value)
      formData.append('start_date', startDate.value)
      formData.append('end_date', endDate.value)
      formData.append('status', status.value)
      formData.append('amount_paid', amountPaid.value)

      emit('plan-data', formData)
      closeDrawer()
    }
  })
}

</script>

<template>
  <VNavigationDrawer
    temporary
    :width="400"
    location="end"
    class="scrollable-content"
    :model-value="props.isDrawerOpen"
    @update:model-value="val => emit('update:isDrawerOpen', val)"
  >
    <AppDrawerHeaderSection title="إضافة اشتراك جديد" @cancel="closeDrawer" />
    <VDivider />
    <PerfectScrollbar :options="{ wheelPropagation: false }">
      <VCard flat>
        <VCardText>
          <VForm ref="refForm" v-model="isFormValid" @submit.prevent="onSubmit">
            <VRow>
              <VCol cols="12">
                <AppSelect v-model="userId" :items="users.map(u => ({ value: u.id, title: u.name }))" label="المستخدم" :rules="[requiredValidator]" />
              </VCol>

              <VCol cols="12">
                <AppSelect v-model="planId" :items="plans.map(p => ({ value: p.id, title: p.name }))" label="الباقة" :rules="[requiredValidator]" />
              </VCol>

              <VCol cols="12">
                <AppTextField v-model="startDate" label="تاريخ البداية" type="date" :rules="[requiredValidator]" />
              </VCol>

              <VCol cols="12">
                <AppTextField v-model="endDate" label="تاريخ النهاية" type="date" :rules="[requiredValidator]" />
              </VCol>

              <VCol cols="12">
                <AppSelect v-model="status" :items="statuses" label="الحالة" :rules="[requiredValidator]" />
              </VCol>

              <VCol cols="12">
                <AppTextField v-model="amountPaid" label="المبلغ المدفوع" type="number" />
              </VCol>

              <VCol cols="12">
                <VBtn type="submit" class="me-3">حفظ</VBtn>
                <VBtn type="reset" variant="tonal" color="error" @click="closeDrawer">إلغاء</VBtn>
              </VCol>
            </VRow>
          </VForm>
        </VCardText>
      </VCard>
    </PerfectScrollbar>
  </VNavigationDrawer>
</template>
