<script setup>
import { ref, onMounted } from "vue"
import { useI18n } from "vue-i18n"

// i18n
const { locale } = useI18n()

// Refs
const statData = ref([])
const loading = ref(false)

// Fetch statistics
const fetchStatistics = async () => {
  loading.value = true
  try {
    const response = await $api("/statistics", { method: "GET" })
    if (Array.isArray(response)) {
      // خذ فقط الأربع عناصر الأولى
      statData.value = response.slice(0, 4)
    }
  } catch (err) {
    console.error("Error fetching statistics", err)
  } finally {
    loading.value = false
  }
}

// Load on mounted
onMounted(fetchStatistics)
</script>

<template>
  <div :style="{ 'background-color': 'rgb(var(--v-theme-surface))' }">
    <VContainer>
      <div class="py-12">
        <VRow>
          <VCol
            v-for="(item, index) in statData"
            :key="index"
            cols="12"
            sm="6"
            lg="3"
          >
            <VCard
              flat
              :style="{ border: `3px solid #44158f` }"
            >
              <VCardText class="text-center">
                <VIcon
                  color="#44158f"
                  :icon="item.icon"
                  size="64"
                  class="mb-4"
                />
                <h3 class="text-h3" style="color:#44158f">
                  {{ item.value }}
                </h3>
                <p class="text-body-1 font-weight-medium mb-0 text-wrap" style="color:#44158f">
                  {{ item.title }}
                </p>
              </VCardText>
            </VCard>
          </VCol>
        </VRow>

        <!-- Loading state -->
        <div v-if="loading" class="text-center py-6">
          {{ $t("Loading statistics...") }}
        </div>
      </div>
    </VContainer>
  </div>
</template>
