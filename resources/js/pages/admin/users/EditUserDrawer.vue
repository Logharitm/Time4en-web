<script setup>
import { PerfectScrollbar } from 'vue3-perfect-scrollbar'

const props = defineProps({
  isDrawerOpen: {
    type: Boolean,
    required: true,
  },
  userData: { // 👈 يستقبل بيانات المستخدم
    type: Object,
    default: null,
  },
})

const emit = defineEmits([
  'update:isDrawerOpen',
  'userData', // هذا الـ emit سيُستخدم لتحديث المستخدم
])

const isFormValid = ref(false)
const refForm = ref()

// form fields
const userId = ref(null)
const name = ref('')
const email = ref('')
const password = ref('')
const role = ref('')
const language = ref('')
const avatar = ref(null)
const subscriptionPlan = ref('')

// plans list from API
const plans = ref([])

const fetchPlans = async () => {
  try {
    const response = await $api('/plans', { method: 'GET' })
    if (response.status === 'success') {
      plans.value = response.data
    }
  } catch (e) {
    console.error('Error fetching plans', e)
  }
}

onMounted(fetchPlans)

// 👈 مراقبة تغييرات المستخدم وتعبئة الحقول
watch(() => props.userData, (newVal) => {
  if (newVal) {
    userId.value = newVal.id
    name.value = newVal.name
    email.value = newVal.email
    password.value = '' // لا تعرض كلمة المرور
    role.value = newVal.role
    language.value = newVal.language
    subscriptionPlan.value = newVal.subscription_plan
    avatar.value = null
  }
})

// 👉 drawer close
const closeNavigationDrawer = () => {
  emit('update:isDrawerOpen', false)
  nextTick(() => {
    refForm.value?.reset()
    refForm.value?.resetValidation()
  })
}

// 👉 submit
const onSubmit = () => {
  refForm.value?.validate().then(({ valid }) => {
    if (valid) {
      const formData = new FormData()
      formData.append('_method', 'POST') // 👈 لبعض الـ APIs، قد تحتاج هذه الطريقة لتمرير POST
      formData.append('name', name.value)
      formData.append('email', email.value)
      if (password.value) {
        formData.append('password', password.value)
      }
      formData.append('role', role.value)
      formData.append('language', language.value)

      if (avatar.value instanceof File) {
        formData.append('avatar', avatar.value)
      } else {
        // إذا لم يتم اختيار صورة جديدة، أرسل قيمة فارغة أو لا ترسلها
        // formData.append('avatar', '');
      }

      if (subscriptionPlan.value) {
        formData.append('subscription_plan', subscriptionPlan.value)
      }

      // إرسال الـ formData مع الـ userId
      emit('userData', userId.value, formData)

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
      title="تعديل مستخدم"
      @cancel="closeNavigationDrawer"
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
                <AppTextField
                  v-model="name"
                  :rules="[requiredValidator]"
                  label="الاسم"
                  placeholder="أدخل الاسم"
                />
              </VCol>

              <VCol cols="12">
                <AppTextField
                  v-model="email"
                  :rules="[requiredValidator, emailValidator]"
                  label="البريد الإلكتروني"
                  placeholder="example@email.com"
                />
              </VCol>

              <VCol cols="12">
                <AppTextField
                  v-model="password"
                  type="password"
                  label="كلمة المرور (اتركه فارغاً للتجاهل)"
                  placeholder="********"
                />
              </VCol>

              <VCol cols="12">
                <AppSelect
                  v-model="role"
                  label="الدور"
                  placeholder="اختر الدور"
                  :rules="[requiredValidator]"
                  :items="[{ title: 'مدير', value: 'admin' }, { title: 'مستخدم', value: 'user' }]"
                />
              </VCol>

              <VCol cols="12">
                <AppSelect
                  v-model="language"
                  label="اللغة"
                  placeholder="اختر اللغة"
                  :items="[
                    { title: 'العربية', value: 'ar' },
                    { title: 'الإنجليزية', value: 'en' }
                  ]"
                />
              </VCol>

              <VCol cols="12">
                <label class="text-sm mb-1 block">الصورة الرمزية</label>
                <input
                  type="file"
                  accept="image/*"
                  @change="e => avatar = e.target.files[0]"
                  class="border rounded p-2 w-full"
                />
              </VCol>

              <VCol cols="12">
                <AppSelect
                  v-model="subscriptionPlan"
                  label="الباقة"
                  placeholder="اختر الباقة"
                  :items="plans.map(p => ({ title: p.name, value: p.id }))"
                />
              </VCol>

              <VCol cols="12">
                <VBtn
                  type="submit"
                  class="me-3"
                >
                  تحديث
                </VBtn>
                <VBtn
                  type="reset"
                  variant="tonal"
                  color="error"
                  @click="closeNavigationDrawer"
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
