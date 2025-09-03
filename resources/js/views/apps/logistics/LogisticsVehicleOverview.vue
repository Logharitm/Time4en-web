<script setup>
import { ref, onMounted } from 'vue'

const vehicleData = ref([])

const fetchPlans = async () => {
  try {
    const res = await $api('/plans/statistics', {
      method: 'GET',
      onResponseError({ response }) {
        console.error('API Error:', response._data)
      },
    })

    // لو الريسبونس بيرجع مصفوفة مباشرة
    vehicleData.value = res?._data ?? res
  } catch (err) {
    console.error('Unexpected error:', err)
  }
}

onMounted(() => {
  fetchPlans()
})
</script>

<template>
  <VCard>
    <VCardItem title="Plans Overview">
      <template #append>
        <MoreBtn />
      </template>
    </VCardItem>
    <VCardText>
      <VTable class="text-no-wrap">
        <tbody>
        <tr
          v-for="(plan, index) in vehicleData"
          :key="index"
        >
          <td
            width="70%"
            style="padding-inline-start: 0 !important;"
          >
            <div class="d-flex align-center gap-x-2">
              <VIcon
                :icon="plan.icon"
                size="24"
                class="text-high-emphasis"
              />
              <div class="text-body-1 text-high-emphasis">
                {{ plan.title }}
              </div>
            </div>
          </td>
          <td>
            <h6 class="text-h6">
              {{ plan.subscribers }} مشترك
            </h6>
          </td>
          <td>
            <div class="text-body-1">
              {{ plan.percentage }}%
            </div>
          </td>
        </tr>
        </tbody>
      </VTable>
    </VCardText>
  </VCard>
</template>

<style lang="scss" scoped>
.vehicle-progress-label {
  padding-block-end: 1rem;

  &::after {
    position: absolute;
    display: inline-block;
    background-color: rgba(var(--v-theme-on-surface), var(--v-border-opacity));
    block-size: 10px;
    content: "";
    inline-size: 2px;
    inset-block-end: 0;
    inset-inline-start: 0;

    [dir="rtl"] & {
      inset-inline: unset 0;
    }
  }
}
</style>

<style lang="scss">
.v-progress-linear__content {
  justify-content: start;
  padding-inline-start: 1rem;
}
</style>
