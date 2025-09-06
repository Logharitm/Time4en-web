<script setup>
import { ref, nextTick, onMounted } from 'vue'
import { PerfectScrollbar } from 'vue3-perfect-scrollbar'
import AppSelect from '@core/components/app-form-elements/AppSelect.vue'
import AppTextField from '@core/components/app-form-elements/AppTextField.vue'

const props = defineProps({
  isDrawerOpen: { type: Boolean, required: true },
})

const emit = defineEmits(['update:isDrawerOpen', 'plan-data'])

const isFormValid = ref(false)
const refForm = ref()

// Form fields
const subscription_id = ref('')
const amount = ref('')
const status = ref('completed')
const payment_method = ref('')
const paid_at = ref('')

// Subscriptions list for select
const subscriptions = ref([])

const fetchSubscriptions = async () => {
  try {
    const res = await $api('/subscriptions', { method: 'GET', params: { per_page: 100 } })
    if (res.status === 'success') subscriptions.value = res.data
  } catch (err) {
    console.error(err)
  }
}

onMounted(fetchSubscriptions)

// Close drawer
const closeDrawer = () => {
  emit('update:isDrawerOpen', false)
  nextTick(() => refForm.value?.resetValidation())
}

// Submit
const onSubmit = () => {
  refForm.value?.validate().then(({ valid }) => {
    if (!valid) return
    const formData = new FormData()

    formData.append('subscription_id', subscription_id.value)
    formData.append('amount', amount.value)
    formData.append('status', status.value)
    formData.append('payment_method', payment_method.value)
    if (paid_at.value) formData.append('paid_at', paid_at.value)

    emit('plan-data', formData)
    closeDrawer()
  })
}

const handleDrawerModelValueUpdate = val => emit('update:isDrawerOpen', val)
</script>

<template>
  <VNavigationDrawer
    temporary
    :width="400"
    location="end"
    :model-value="props.isDrawerOpen"
    @update:model-value="handleDrawerModelValueUpdate"
  >
    <AppDrawerHeaderSection
      title="إضافة دفع جديد"
      @cancel="closeDrawer"
    />
    <VDivider />
    <PerfectScrollbar :options="{ wheelPropagation: false }">
      <VCard flat>
        <VCardText>
          <VForm
            ref="refForm"
            v-model="isFormValid"
            @submit.prevent="onSubmit"
          >
            <VRow>
              <VCol cols="12">
                <AppSelect
                  v-model="subscription_id"
                  :items="subscriptions.map(s => ({ value: s.id, title: s.user.name + ' - ' + s.plan.name }))"
                  label="الاشتراك"
                  placeholder="اختر الاشتراك"
                  :rules="[v => !!v || 'مطلوب']"
                />
              </VCol>

              <VCol cols="12">
                <AppTextField
                  v-model="amount"
                  type="number"
                  label="المبلغ"
                  placeholder="أدخل المبلغ"
                  :rules="[v => !!v || 'مطلوب']"
                />
              </VCol>

              <VCol cols="12">
                <AppSelect
                  v-model="status"
                  :items="[{value:'completed',title:'مكتمل'},{value:'pending',title:'معلق'},{value:'failed',title:'فشل'}]"
                  label="الحالة"
                />
              </VCol>

              <VCol cols="12">
                <AppTextField
                  v-model="payment_method"
                  label="طريقة الدفع"
                  placeholder="مثال: بطاقة ائتمان"
                />
              </VCol>

              <VCol cols="12">
                <AppTextField
                  v-model="paid_at"
                  type="date"
                  label="تاريخ الدفع"
                />
              </VCol>

              <VCol cols="12">
                <VBtn
                  type="submit"
                  class="me-3"
                >
                  حفظ
                </VBtn>
                <VBtn
                  type="reset"
                  variant="tonal"
                  color="error"
                  @click="closeDrawer"
                >
                  إلغاء
                </VBtn>
              </VCol>
            </VRow>
          </VForm>
        </VCardText>
      </VCard>
    </PerfectScrollbar>
  </VNavigationDrawer>
</template>
