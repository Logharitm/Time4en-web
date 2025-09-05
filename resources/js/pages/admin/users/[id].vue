<script setup>
import { ref, onMounted } from 'vue'
import { useRoute } from 'vue-router'

import UserBioPanel from './UserBioPanel.vue'
import UserTabAccount from './UserTabAccount.vue'
import UserTabBillingsPlans from './UserTabBillingsPlans.vue'
import UserTabSecurity from './UserTabSecurity.vue'

const route = useRoute()
const userId = route.params.id

const userTab = ref(0)
const userData = ref(null)
const loading = ref(true)
const error = ref(null)

const tabs = [
  { icon: 'tabler-users', title: 'المجلدات' },
  { icon: 'tabler-users', title: 'الكلمات' },
  { icon: 'tabler-users', title: 'الاشتراك' },
  { icon: 'tabler-users', title: 'الباقات' },
  { icon: 'tabler-lock', title: 'المدفوعات' },
  { icon: 'tabler-bookmark', title: 'تعديل البيانات' },
]

// ✅ جلب بيانات المستخدم من الـ API باستخدام $api
const fetchUser = async () => {
  try {
    const response = await $api(`/users/${userId}`, { method: 'GET' })
    if (response.status === 'success') {
      userData.value = response.data
    } else {
      error.value = 'فشل تحميل بيانات المستخدم'
    }
  } catch (err) {
    error.value = 'فشل الاتصال بالسيرفر'
  } finally {
    loading.value = false
  }
}

onMounted(fetchUser)
</script>

<template>
  <div>
    <!-- Loader -->
    <VProgressCircular
      v-if="loading"
      indeterminate
      color="primary"
      size="32"
      class="ma-6"
    />

    <!-- Error -->
    <VAlert
      v-else-if="error"
      type="error"
      variant="tonal"
      class="ma-6"
    >
      {{ error }}
    </VAlert>

    <!-- User Data -->
    <VRow v-else-if="userData">
      <!-- Bio Panel -->
      <VCol
        cols="12"
        md="5"
        lg="4"
      >
        <UserBioPanel :user-data="userData" />
      </VCol>

      <!-- Tabs -->
      <VCol
        cols="12"
        md="7"
        lg="8"
      >
        <VTabs
          v-model="userTab"
          class="v-tabs-pill"
        >
          <VTab
            v-for="(tab, index) in tabs"
            :key="index"
          >
            <VIcon
              :size="18"
              :icon="tab.icon"
              class="me-1"
            />
            <span>{{ tab.title }}</span>
          </VTab>
        </VTabs>

        <VWindow
          v-model="userTab"
          class="mt-6 disable-tab-transition"
          :touch="false"
        >
          <VWindowItem>
            <UserTabAccount :user-data="userData" />
          </VWindowItem>

          <VWindowItem>
            <UserTabSecurity :user-data="userData" />
          </VWindowItem>

          <VWindowItem>
            <UserTabBillingsPlans :user-data="userData" />
          </VWindowItem>
        </VWindow>
      </VCol>
    </VRow>

    <!-- Not Found -->
    <div v-else>
      <VAlert
        type="error"
        variant="tonal"
        class="ma-6"
      >
        User with ID {{ route.params.id }} not found!
      </VAlert>
    </div>
  </div>
</template>
