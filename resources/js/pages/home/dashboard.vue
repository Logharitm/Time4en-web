<script setup>
import LogisticsCardStatistics from '@/views/apps/logistics/LogisticsCardStatistics.vue'
import LogisticsShipmentStatistics from '@/views/apps/logistics/LogisticsShipmentStatistics.vue'
import LogisticsVehicleOverview from '@/views/apps/logistics/LogisticsVehicleOverview.vue'
</script>

<template>
  <VRow class="match-height">
    <VCol cols="12">
      <LogisticsCardStatistics />
    </VCol>
    <VCol
      cols="12"
      md="6"
    >
      <LogisticsVehicleOverview />
    </VCol>

    <VCol
      cols="12"
      md="6"
    >
      <LogisticsShipmentStatistics />
    </VCol>

  </VRow>
</template>
