<script setup>
import { ref, onMounted } from 'vue'

const userData = ref(null)
const subscriptionPlan = ref(null)
const daysRemaining = ref(0)
const isLoading = ref(true)

// تحميل بيانات المستخدم من الـ API
const fetchUserData = async () => {
  isLoading.value = true
  try {
    const res = await $api('/user', { method: 'GET' })
    const user = res?.data?.user
    if (user) {
      userData.value = user
      subscriptionPlan.value = user.subscription?.plan || null

      if (user.subscription?.end_date) {
        const endDate = new Date(user.subscription.end_date)
        const today = new Date()
        daysRemaining.value = Math.max(
          0,
          Math.ceil((endDate - today) / (1000 * 60 * 60 * 24))
        )
      }
    }
  } catch (err) {
    console.error('Error fetching user data:', err)
  } finally {
    isLoading.value = false
  }
}

onMounted(() => {
  fetchUserData()
})
</script>

<template>
  <VRow>
    <VCol cols="12">
      <VCard :title="$t('Current Plan')">
        <VCardText v-if="isLoading">
          <VProgressCircular indeterminate color="primary" />
        </VCardText>

        <VCardText v-else>
          <VRow>
            <VCol cols="12" md="6">
              <div>
                <div class="mb-6">
                  <h3 class="text-body-1 text-high-emphasis font-weight-medium mb-1">
                    <span v-if="subscriptionPlan">{{ subscriptionPlan.name }}</span>
                    <span v-else>{{ $t('None') }}</span>

                    <p class="text-base mb-0">
                      <span v-if="subscriptionPlan">{{ subscriptionPlan.description }}</span>
                      <span v-else>{{ $t('No active subscription') }}</span>
                    </p>
                  </h3>
                </div>

                <div class="mb-6" v-if="userData?.subscription?.end_date">
                  <h3 class="text-body-1 text-high-emphasis font-weight-medium mb-1">
                    {{ $t('Active until') }} : {{ new Date(userData.subscription.end_date).toLocaleDateString() }}
                  </h3>
                </div>

                <div class="mb-6" v-if="userData?.subscription?.end_date">
                  <h3 class="text-body-1 text-high-emphasis font-weight-medium mb-1">
                    {{ $t('Words Count') }} : {{ subscriptionPlan.words_limit }}
                  </h3>
                </div>

                <div v-if="subscriptionPlan">
                  <h3 class="text-body-1 text-high-emphasis font-weight-medium mb-1">
                    {{ $t('Price') }} : <span class="me-2">${{ subscriptionPlan.price }} {{ $t('Per Month') }}</span>
                  </h3>
                </div>
              </div>
            </VCol>

            <VCol cols="12" md="6" v-if="subscriptionPlan">
              <VAlert icon="tabler-alert-triangle" type="warning" variant="tonal">
                <VAlertTitle class="mb-1">{{ $t('We need your attention') }}!</VAlertTitle>
                <span>{{ $t('Your plan requires update') }}</span>

                <h6 class="d-flex font-weight-medium text-body-1 text-high-emphasis mt-6 mb-1">
                  <span>{{ $t('Days') }}</span>
                  <VSpacer />
                  <span>{{ daysRemaining }} {{ $t('of') }} {{ subscriptionPlan.duration_months * 30 }} {{ $t('Days') }}</span>
                </h6>

                <VProgressLinear
                  color="primary"
                  rounded
                  :model-value="100 - (daysRemaining / (subscriptionPlan.duration_months * 30) * 100)"
                />
                <p class="text-body-2 mt-1 mb-0">
                  {{ daysRemaining }} {{ $t('days remaining until your plan requires update') }}
                </p>
              </VAlert>
            </VCol>
          </VRow>
        </VCardText>
      </VCard>
    </VCol>
  </VRow>
</template>
