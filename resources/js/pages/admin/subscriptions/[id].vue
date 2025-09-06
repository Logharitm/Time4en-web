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
    const response = await $api(`/subscriptions/${subscriptionId}`, { method: 'GET' })
    if (response.status === 'success') {
      subscriptionData.value = response.data
    } else {
      error.value = 'Failed to load subscription data'
    }
  } catch (err) {
    error.value = 'Server connection failed'
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
    return { color: 'warning', icon: 'tabler-user' }
  if (role === 'author')
    return { color: 'success', icon: 'tabler-circle-check' }
  if (role === 'maintainer')
    return { color: 'primary', icon: 'tabler-chart-pie-2' }
  if (role === 'editor')
    return { color: 'info', icon: 'tabler-pencil' }
  if (role === 'admin')
    return { color: 'secondary', icon: 'tabler-server-2' }

  return { color: 'primary', icon: 'tabler-user' }
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

      <!-- User Card -->
      <VCol cols="12" md="6">
        <VCard>
          <VCardText class="text-center pt-12">
            <VAvatar
              rounded
              :size="100"
              :color="!subscriptionData.user?.avatar ? 'primary' : undefined"
              :variant="!subscriptionData.user?.avatar ? 'tonal' : undefined"
            >
              <VImg v-if="subscriptionData.user?.avatar" :src="subscriptionData.user.avatar" />
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
              Subscriber
            </VChip>
          </VCardText>

          <VDivider class="my-4" />

          <VCardText>
            <VList dense>
              <VListItem>
                <VListItemTitle>
                  <h6 class="text-h6">ID:</h6>
                  <div class="d-inline-block text-body-1">{{ subscriptionData.user?.id }}</div>
                </VListItemTitle>
              </VListItem>
              <VListItem>
                <VListItemTitle>
                  <h6 class="text-h6">Name:</h6>
                  <div class="d-inline-block text-body-1">{{ subscriptionData.user?.name }}</div>
                </VListItemTitle>
              </VListItem>
            </VList>
          </VCardText>
        </VCard>
      </VCol>

      <!-- Subscription Card -->
      <VCol cols="12" md="6">
        <VCard>
          <VCardText class="text-h6 text-center">Subscription Details</VCardText>
          <VDivider class="my-2" />
          <VCardText>
            <VList dense>
              <VListItem>
                <VListItemTitle>ID: {{ subscriptionData.id }}</VListItemTitle>
              </VListItem>
              <VListItem>
                <VListItemTitle>Status: {{ subscriptionData.status }}</VListItemTitle>
              </VListItem>
              <VListItem>
                <VListItemTitle>Start Date: {{ subscriptionData.start_date }}</VListItemTitle>
              </VListItem>
              <VListItem>
                <VListItemTitle>End Date: {{ subscriptionData.end_date }}</VListItemTitle>
              </VListItem>
            </VList>
          </VCardText>
        </VCard>
      </VCol>

      <!-- Plan Card -->
      <VCol cols="12" md="6">
        <VCard>
          <VCardText class="text-h6 text-center">Plan Info</VCardText>
          <VDivider class="my-2" />
          <VCardText>
            <VList dense>
              <VListItem>
                <VListItemTitle>Plan Name: {{ subscriptionData.plan?.name }}</VListItemTitle>
              </VListItem>
              <VListItem>
                <VListItemTitle>Plan ID: {{ subscriptionData.plan?.id }}</VListItemTitle>
              </VListItem>
            </VList>
          </VCardText>
        </VCard>
      </VCol>

      <!-- Payments Card -->
      <VCol cols="12" md="6">
        <VCard>
          <VCardText class="text-h6 text-center">Payments</VCardText>
          <VDivider class="my-2" />
          <VCardText>
            <VList dense>
              <VListItem v-if="!subscriptionData.payments || subscriptionData.payments.length === 0">
                <VListItemTitle>No payments found.</VListItemTitle>
              </VListItem>

              <VListItem
                v-for="payment in subscriptionData.payments || []"
                :key="payment.id"
              >
                <VListItemTitle>
                  Amount: {{ payment.amount }} | Status: {{ payment.status }} | Method: {{ payment.payment_method }} | Paid At: {{ payment.paid_at }}
                </VListItemTitle>
              </VListItem>
            </VList>
          </VCardText>
        </VCard>
      </VCol>

    </VRow>

    <!-- Not Found -->
    <VAlert v-else type="error" variant="tonal" class="ma-6">
      Subscription with ID {{ subscriptionId }} not found!
    </VAlert>
  </div>
</template>

<style scoped>
.text-capitalize {
  text-transform: capitalize !important;
}
</style>
