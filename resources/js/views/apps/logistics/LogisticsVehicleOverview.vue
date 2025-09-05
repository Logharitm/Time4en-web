<script setup>
import { ref, onMounted } from 'vue'

const plansData = ref([])

const fetchPlans = async () => {
  try {
    const res = await $api('/plans/statistics', {
      method: 'GET',
      onResponseError({ response }) {
        console.error('API Error:', response._data)
      },
    })

    plansData.value = res?._data ?? res
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
    <VCardItem title="باقات الاشتراك">
      <template #append>
        <MoreBtn />
      </template>
    </VCardItem>
    <VCardText>
      <!-- Progress Bars Overview -->
      <div class="d-flex mb-6">
        <div
          v-for="(plan, index) in plansData"
          :key="index"
          :style="`inline-size: ${plan.percentage}%;`"
        >
          <div class="vehicle-progress-label position-relative mb-6 text-body-1 d-none d-sm-block">
            {{ plan.title }}
          </div>
          <VProgressLinear
            :color="index === 0 ? 'rgb(var(--v-theme-primary))'
                    : index === 1 ? 'rgb(var(--v-theme-info))'
                    : index === 2 ? 'rgb(var(--v-theme-success))'
                    : 'rgba(var(--v-theme-on-surface), var(--v-hover-opacity))'"
            model-value="100"
            height="46"
            class="rounded-0"
          >
            <div
              class="text-sm font-weight-medium text-start"
              :class="index <= 2 ? 'text-white' : 'text-surface'"
            >
              {{ plan.percentage }}%
            </div>
          </VProgressLinear>
        </div>
      </div>

      <!-- Detailed Table -->
      <VTable class="text-no-wrap">
        <tbody>
        <tr
          v-for="(plan, index) in plansData"
          :key="index"
        >
          <td width="70%" style="padding-inline-start: 0 !important;">
            <div class="d-flex align-center gap-x-2">
              <VIcon
                icon="tabler-plant-2"
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
              {{ plan.subscribers }}
              <VIcon
              icon="tabler-user-hexagon"
              size="24"
              class="text-high-emphasis"
            />
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
