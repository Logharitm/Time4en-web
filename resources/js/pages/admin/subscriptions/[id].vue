<script setup>
import { ref, onMounted } from 'vue'
import { useRoute } from 'vue-router'
import {
  VRow,
  VCol,
  VCard,
  VCardText,
  VAvatar,
  VImg,
  VChip,
  VDivider,
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
    const response = await $api(`/subscriptions/${subscriptionId}`, { method: 'GET' })
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

onMounted(fetchSubscriptionDetails)
</script>

<template>
  <div>
    <!-- Loader -->
    <VProgressCircular
      v-if="loading"
      indeterminate
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
            >
              <VImg v-if="subscriptionData.user?.avatar" :src="subscriptionData.user.avatar" />
              <span v-else class="text-5xl font-weight-medium">
                {{ avatarText(subscriptionData.user?.name) }}
              </span>
            </VAvatar>

            <h5 class="text-h5 mt-4">{{ subscriptionData.user?.name }}</h5>

            <VChip
              label
              size="small"
              class="mt-4"
            >
              مشترك
            </VChip>
          </VCardText>

          <VDivider class="my-4"/>

          <VCardText>
            <table class="info-table">
              <tbody>
              <tr><td><strong>الاسم</strong></td><td>{{ subscriptionData.user?.name }}</td></tr>
              <tr><td><strong>البريد الإلكتروني</strong></td><td>{{ subscriptionData.user?.email }}</td></tr>
              <tr><td><strong>تاريخ التسجيل</strong></td><td>{{ subscriptionData.created_at }}</td></tr>
              </tbody>
            </table>
          </VCardText>
        </VCard>
      </VCol>

      <!-- العمود الأيسر: باقي الكروت -->
      <VCol cols="12" md="8">

        <VRow dense>
          <!-- كارد الاشتراك -->
          <VCol cols="12" md="6">
            <VCard class="mb-4">
              <VCardText class="text-h6 text-center">تفاصيل الاشتراك</VCardText>
              <VDivider class="my-2"/>
              <VCardText>
                <table class="info-table">
                  <tbody>
                  <tr><td><strong>رقم الاشتراك</strong></td><td>{{ subscriptionData.id }}</td></tr>
                  <tr><td><strong>الحالة</strong></td><td>{{ subscriptionData.status }}</td></tr>
                  <tr><td><strong>تاريخ البدء</strong></td><td>{{ subscriptionData.start_date }}</td></tr>
                  <tr><td><strong>تاريخ الانتهاء</strong></td><td>{{ subscriptionData.end_date }}</td></tr>
                  <tr><td><strong>المبلغ المدفوع</strong></td><td>{{ subscriptionData.amount_paid }}</td></tr>
                  </tbody>
                </table>
              </VCardText>
            </VCard>
          </VCol>

          <!-- كارد باقة الاشتراك -->
          <VCol cols="12" md="6">
            <VCard class="mb-4">
              <VCardText class="text-h6 text-center">معلومات باقة الاشتراك</VCardText>
              <VDivider class="my-2"/>
              <VCardText>
                <table class="info-table">
                  <tbody>
                  <tr><td><strong>اسم الباقة</strong></td><td>{{ subscriptionData.plan?.name }}</td></tr>
                  <tr><td><strong>السعر</strong></td><td>{{ subscriptionData.plan?.price }}</td></tr>
                  <tr><td><strong>المدة</strong></td><td>{{ subscriptionData.plan?.duration_months }} شهر</td></tr>
                  <tr><td><strong>الوصف</strong></td><td>{{ subscriptionData.plan?.description }}</td></tr>
                  <tr><td><strong>عدد الكلمات</strong></td><td>{{ subscriptionData.plan?.words_limit }}</td></tr>
                  </tbody>
                </table>
              </VCardText>
            </VCard>
          </VCol>

          <!-- كارد الدفع -->
          <VCol cols="12" md="12">
            <VCard>
              <VCardText class="text-h6 text-center">معلومات الدفع</VCardText>
              <VDivider class="my-2"/>
              <VCardText>
                <table class="info-table">
                  <tbody>
                  <tr><td><strong>رقم الدفع</strong></td><td>{{ subscriptionData.payment?.id }}</td></tr>
                  <tr><td><strong>المبلغ</strong></td><td>{{ subscriptionData.payment?.amount }}</td></tr>
                  <tr><td><strong>الحالة</strong></td><td>{{ subscriptionData.payment?.status }}</td></tr>
                  <tr><td><strong>طريقة الدفع</strong></td><td>{{ subscriptionData.payment?.payment_method }}</td></tr>
                  <tr><td><strong>تاريخ الدفع</strong></td><td>{{ subscriptionData.payment?.paid_at }}</td></tr>
                  </tbody>
                </table>
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
.info-table {
  width: 100%;
  border-collapse: collapse;
}

.info-table td {
  padding: 8px 12px;
  border-bottom: 1px solid #ddd;
}
</style>
