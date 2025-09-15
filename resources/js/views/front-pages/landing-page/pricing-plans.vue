<script setup>
import { ref, onMounted } from "vue"
import paperPlane from '@images/front-pages/icons/paper-airplane.png'
import plane from '@images/front-pages/icons/plane.png'
import shuttleRocket from '@images/front-pages/icons/shuttle-rocket.png'
import pricingPlanArrow from '@images/front-pages/icons/pricing-plans-arrow.png'

// بيانات الباقات
const pricingPlans = ref([])
const loading = ref(false)

// أيقونات احتياطية لكل خطة
const defaultIcons = [paperPlane, plane, shuttleRocket]

// جلب البيانات من API
const fetchPlans = async () => {
  loading.value = true
  try {
    const res = await $api("/plans", { method: "GET" })
    if (res.status === "success" && Array.isArray(res.data)) {
      pricingPlans.value = res.data.map((plan, index) => ({
        ...plan,
        image: defaultIcons[index] || paperPlane, // أيقونة احتياطية
        current: index === 0, // الخطة الأولى محددة افتراضياً
      }))
    }
  } catch (err) {
    console.error("Error fetching plans", err)
  } finally {
    loading.value = false
  }
}

onMounted(fetchPlans)
</script>

<template>
  <div id="pricing-plan">
    <VContainer>
      <div class="pricing-plans">
        <!-- Headers -->
        <div class="headers d-flex justify-center flex-column align-center flex-wrap">
          <VChip
            label
            color="primary"
            class="mb-4"
            size="small"
          >
            {{ $t("Pricing Plans") }}
          </VChip>
          <h4 class="d-flex align-center text-h4 mb-1 flex-wrap justify-center">
            <div class="position-relative me-2">
              <div class="section-title">
                {{ $t("Tailored plans designed for you") }}
              </div>
            </div>

          </h4>
        </div>

        <VRow class="mt-6">
          <VCol
            v-for="(plan, index) in pricingPlans"
            :key="plan.id"
            cols="12"
            sm="6"
            lg="4"
          >
            <VCard :style="plan.current ? 'border:2px solid rgb(var(--v-theme-primary))' : ''">
              <VCardText class="pa-8 pt-12">
                <VImg
                  :src="plan.image"
                  width="88"
                  height="88"
                  class="mx-auto mb-8"
                />
                <h4 class="text-h4 text-center">
                  {{ plan.name }}
                </h4>
                <p class="text-body-2 text-center mb-6 mt-6">
                  {{ plan.description }}
                </p>

                <!-- السعر -->
                <div class="d-flex justify-center mb-8">
                  <div class="pricing-title text-primary me-1">
                    {{ plan.price }}
                  </div>
                </div>

                <!-- تفاصيل الباقة -->
                <VList class="card-list">
                  <VListItem>
                    <template #prepend>
                      <VAvatar size="16" variant="tonal" color="primary" class="me-3">
                        <VIcon icon="tabler-check" size="12" color="primary" />
                      </VAvatar>
                      <h6 class="text-h6">
                        {{ $t("Duration") }}: {{ plan.duration_months }} {{ $t("Months") }}
                      </h6>
                    </template>
                  </VListItem>

                  <VListItem>
                    <template #prepend>
                      <VAvatar size="16" variant="tonal" color="primary" class="me-3">
                        <VIcon icon="tabler-check" size="12" color="primary" />
                      </VAvatar>
                      <h6 class="text-h6">
                        {{ $t("Words Limit") }}: {{ plan.words_limit }}
                      </h6>
                    </template>
                  </VListItem>

                </VList>

                <VBtn
                  block
                  :variant="plan.current ? 'elevated' : 'tonal'"
                  class="mt-8"
                  href="register"
                >
                  {{ $t("Get Started") }}
                </VBtn>
              </VCardText>
            </VCard>
          </VCol>
        </VRow>

        <!-- Loading -->
        <div v-if="loading" class="text-center py-6">
          {{ $t("Loading") }}...
        </div>
      </div>
    </VContainer>
  </div>
</template>

<style lang="scss" scoped>
.card-list {
  --v-card-list-gap: 12px;
}

#pricing-plan {
  border-radius: 3.75rem;
  background-color: rgb(var(--v-theme-background));
}

.pricing-title {
  font-size: 38px;
  font-weight: 800;
  line-height: 52px;
}

.pricing-plans {
  margin-block: 5.25rem;
}

@media (max-width: 600px) {
  .pricing-plans {
    margin-block: 4rem;
  }
}

.section-title {
  font-size: 24px;
  font-weight: 800;
  line-height: 36px;
}

.section-title::after {
  position: absolute;
  background: url("../../../assets/images/front-pages/icons/section-title-icon.png") no-repeat left bottom;
  background-size: contain;
  block-size: 100%;
  content: "";
  font-weight: 700;
  inline-size: 120%;
  inset-block-end: 0;
  inset-inline-start: -12%;
}
</style>
