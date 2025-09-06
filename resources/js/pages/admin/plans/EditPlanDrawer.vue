<script setup>
import { ref, watch, nextTick } from 'vue'
import { PerfectScrollbar } from 'vue3-perfect-scrollbar'
import AppTextarea from "@core/components/app-form-elements/AppTextarea.vue";

const props = defineProps({
  isDrawerOpen: {
    type: Boolean,
    required: true,
  },
  planData: {
    type: Object,
    default: null,
  },
})

const emit = defineEmits([
  'update:isDrawerOpen',
  'plan-data',
])

const isFormValid = ref(false)
const refForm = ref()

// form fields
const planId = ref(null)
const name = ref('')
const words_limit = ref('')
const price = ref('')
const duration_months = ref('')
const description = ref('')

// مراقبة تغييرات البيانات
watch(() => props.planData, newVal => {
  if (newVal) {
    planId.value = newVal.id
    name.value = newVal.name
    words_limit.value = newVal.words_limit
    price.value = newVal.price
    duration_months.value = newVal.duration_months
    description.value = newVal.description
  }
})

// drawer close
const closeNavigationDrawer = () => {
  emit('update:isDrawerOpen', false)
  nextTick(() => {
    refForm.value?.reset()
    refForm.value?.resetValidation()
  })
}

// submit
const onSubmit = () => {
  refForm.value?.validate().then(({ valid }) => {
    if (valid && planId.value) {
      const formData = new FormData()
      formData.append('_method', 'POST')
      formData.append('name', name.value)
      formData.append('words_limit', words_limit.value)
      formData.append('price', price.value)
      formData.append('duration_months', duration_months.value)
      formData.append('description', description.value)

      emit('plan-data', planId.value, formData)

      emit('update:isDrawerOpen', false)
      nextTick(() => {
        refForm.value?.reset()
        refForm.value?.resetValidation()
      })
    }
  })
}

const handleDrawerModelValueUpdate = val => {
  emit('update:isDrawerOpen', val)
}
</script>

<template>
  <VNavigationDrawer
      data-allow-mismatch
      temporary
      :width="400"
      location="end"
      class="scrollable-content"
      :model-value="props.isDrawerOpen"
      @update:model-value="handleDrawerModelValueUpdate"
  >
    <AppDrawerHeaderSection
        title="تعديل باقة الاشتراك"
        @cancel="closeNavigationDrawer"
    />

    <VDivider />

    <PerfectScrollbar :options="{ wheelPropagation: false }">
      <VCard flat>
        <VCardText>
          <VForm ref="refForm" v-model="isFormValid" @submit.prevent="onSubmit">
            <VRow>
              <VCol cols="12">
                <AppTextField v-model="name" :rules="[requiredValidator]" label="اسم باقة الاشتراك" placeholder="أدخل اسم باقة الاشتراك" />
              </VCol>

              <VCol cols="12">
                <AppTextField type="number" v-model="words_limit" :rules="[requiredValidator]" label="عدد الكلمات" placeholder="مثال: شهري، سنوي" />
              </VCol>

              <VCol cols="12">
                <AppTextField type="number" v-model="price" :rules="[requiredValidator]" label="السعر" placeholder="أدخل السعر" />
              </VCol>

              <VCol cols="12">
                <AppTextField type="number" v-model="duration_months" :rules="[requiredValidator]" label="المدة (شهور)" placeholder="أدخل مدة باقة الاشتراك بالأيام" />
              </VCol>

              <VCol cols="12">
                <AppTextarea v-model="description" label="الوصف" placeholder="أدخل وصف باقة الاشتراك"></AppTextarea>
              </VCol>

              <VCol cols="12">
                <VBtn type="submit" class="me-3">تحديث</VBtn>
                <VBtn type="reset" variant="tonal" color="error" @click="closeNavigationDrawer">إلغاء</VBtn>
              </VCol>
            </VRow>
          </VForm>
        </VCardText>
      </VCard>
    </PerfectScrollbar>
  </VNavigationDrawer>
</template>
