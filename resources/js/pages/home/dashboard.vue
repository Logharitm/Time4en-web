<script setup>
import LogisticsCardStatistics from '@/views/apps/logistics/LogisticsCardStatistics.vue'
import LogisticsVehicleOverview from '@/views/apps/logistics/LogisticsVehicleOverview.vue'

definePage({
  meta: {
    action: 'read',
    subject: 'Home',
  },
})
</script>

<template>
  <VRow class="match-height">
    <VCol cols="12">
      <LogisticsCardStatistics />
    </VCol>
    <VCol
      cols="12"
      md="12"
    >
      <LogisticsVehicleOverview />
    </VCol>
  </VRow>
</template>
