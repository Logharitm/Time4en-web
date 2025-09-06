<script setup>
import {ref, onMounted} from 'vue'
import {useRoute} from 'vue-router'
import {
  VRow,
  VCol,
  VCard,
  VCardText,
  VAvatar,
  VImg,
  VChip,
  VDivider,
  VList,
  VListItem,
  VListItemTitle,
  VProgressCircular,
  VAlert
} from 'vuetify/components'

// قراءة ID الاشتراك من الرابط
const route = useRoute()
const subscriptionId = route.params.id

const subscriptionData = ref(null)
const loading = ref(true)
const error = ref(null)

// Fetch subscription details by ID
const fetchSubscriptionDetails = async () => {
  loading.value = true
  try {
    const response = await $api(`/subscriptions/${subscriptionId}`, {method: 'GET'})
    if (response.status === 'success') {
      subscriptionData.value = response.data
    } else {
      error.value = 'فشل في تحميل بيانات الاشتراك'
    }
  } catch (err) {
    error.value = 'فشل الاتصال بالخادم'
    console.error(err)
  } finally {
    loading.value = false
  }
}

// توليد أول حرفين لعرضهم في الافاتار
const avatarText = name => {
  if (!name) return '?'
  return name.split(' ').map(n => n[0]).join('').toUpperCase()
}

const resolveUserRoleVariant = role => {
  if (role === 'subscriber')
    return {color: 'warning', icon: 'tabler-user'}
  return {color: 'primary', icon: 'tabler-user'}
}

onMounted(fetchSubscriptionDetails)
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

    <!-- Subscription Data -->
    <VRow v-else-if="subscriptionData" class="ma-2" dense>

      <!-- العمود الأيمن: كارد المستخدم -->
      <VCol cols="12" md="4">
        <VCard>
          <VCardText class="text-center pt-12">
            <VAvatar
              rounded
              :size="100"
              :color="!subscriptionData.user?.avatar ? 'primary' : undefined"
              :variant="!subscriptionData.user?.avatar ? 'tonal' : undefined"
            >
              <VImg v-if="subscriptionData.user?.avatar" :src="subscriptionData.user.avatar"/>
              <span v-else class="text-5xl font-weight-medium">
                {{ avatarText(subscriptionData.user?.name) }}
              </span>
            </VAvatar>

            <h5 class="text-h5 mt-4">{{ subscriptionData.user?.name }}</h5>

            <VChip
              label
              :color="resolveUserRoleVariant('subscriber').color"
              size="small"
              class="text-capitalize mt-4"
            >
              مشترك
            </VChip>
          </VCardText>

          <VDivider class="my-4"/>

          <VCardText>
            <VList dense>
              <VListItem>
                <VListItemTitle>الاسم : {{ subscriptionData.user?.name }}</VListItemTitle>
              </VListItem>
              <VListItem>
                <VListItemTitle>البريد الإلكتروني: {{ subscriptionData.user?.email }}</VListItemTitle>
              </VListItem>
              <VListItem>
                <VListItemTitle>تاريخ التسجيل : {{ subscriptionData.user?.email }}</VListItemTitle>
              </VListItem>
            </VList>
          </VCardText>
        </VCard>
      </VCol>

      <!-- العمود الأيسر: باقي الكروت -->
      <VCol cols="12" md="8">
        <!-- كارد الاشتراك -->
        <VRow v-else-if="subscriptionData" class="ma-2" dense>
        <VCol cols="6" md="6">
          <VCard class="mb-4">
            <VCardText class="text-h6 text-center">تفاصيل الاشتراك</VCardText>
            <VDivider class="my-2"/>
            <VCardText>
              <VList dense>
                <VListItem>رقم الاشتراك: {{ subscriptionData.id }}</VListItem>
                <VListItem>الحالة: {{ subscriptionData.status }}</VListItem>
                <VListItem>تاريخ البدء: {{ subscriptionData.start_date }}</VListItem>
                <VListItem>تاريخ الانتهاء: {{ subscriptionData.end_date }}</VListItem>
                <VListItem>المبلغ المدفوع: {{ subscriptionData.amount_paid }}</VListItem>
              </VList>
            </VCardText>
          </VCard>
        </VCol>

        <VCol cols="6" md="6">
          <!-- كارد باقة الاشتراك -->
          <VCard class="mb-4">
            <VCardText class="text-h6 text-center">معلومات باقة الاشتراك</VCardText>
            <VDivider class="my-2"/>
            <VCardText>
              <VList dense>
                <VListItem>اسم باقة الاشتراك: {{ subscriptionData.plan?.name }}</VListItem>
                <VListItem>السعر: {{ subscriptionData.plan?.price }}</VListItem>
                <VListItem>المدة: {{ subscriptionData.plan?.duration_months }} شهر</VListItem>
                <VListItem>الوصف: {{ subscriptionData.plan?.description }}</VListItem>
                <VListItem>حد الكلمات: {{ subscriptionData.plan?.words_limit }}</VListItem>
              </VList>
            </VCardText>
          </VCard>
        </VCol>

        <VCol cols="6" md="6">
          <!-- كارد الدفع -->
          <VCard>
            <VCardText class="text-h6 text-center">معلومات الدفع</VCardText>
            <VDivider class="my-2"/>
            <VCardText>
              <VList dense>
                <VListItem>رقم الدفع: {{ subscriptionData.payment?.id }}</VListItem>
                <VListItem>المبلغ: {{ subscriptionData.payment?.amount }}</VListItem>
                <VListItem>الحالة: {{ subscriptionData.payment?.status }}</VListItem>
                <VListItem>طريقة الدفع: {{ subscriptionData.payment?.payment_method }}</VListItem>
                <VListItem>تاريخ الدفع: {{ subscriptionData.payment?.paid_at }}</VListItem>
              </VList>
            </VCardText>
          </VCard>
        </VCol>
        </VRow>
      </VCol>
    </VRow>

    <!-- Not Found -->
    <VAlert v-else type="error" variant="tonal" class="ma-6">
      الاشتراك برقم {{ subscriptionId }} غير موجود!
    </VAlert>
  </div>
</template>

<style scoped>
.text-capitalize {
  text-transform: capitalize !important;
}
</style>
