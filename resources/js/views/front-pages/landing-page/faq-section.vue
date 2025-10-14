<script setup>
import { ref, onMounted, computed, watch } from "vue"
import { useI18n } from "vue-i18n"
import boyWithLogo from "@images/front-pages/landing-page/faq.webp"

// i18n
const { locale } = useI18n()

// Refs
const faqsData = ref([])
const loading = ref(false)
const searchQuery = ref("")
const itemsPerPage = ref(20)
const page = ref(1)
const sortBy = ref("id")
const orderBy = ref("asc")
const totalFaqs = ref(0)

// 👉 Fetch FAQs
const fetchFAQs = async () => {
  loading.value = true
  try {
    const response = await $api("/faqs", {
      method: "GET",
      params: {
        search: searchQuery.value,
        per_page: itemsPerPage.value,
        page: page.value,
        sort_by: sortBy.value,
        sort_order: orderBy.value,
      },
    })

    if (response.status === "success") {
      faqsData.value = response.data.data
      totalFaqs.value = response.data.total
    }
  } catch (err) {
    console.error("Error fetching FAQs", err)
  } finally {
    loading.value = false
  }
}

// Load on mounted
onMounted(fetchFAQs)

// 👉 Localized FAQs based on current language
const localizedFaqs = computed(() =>
  faqsData.value.map((faq) => ({
    id: faq.id,
    question: locale.value === "en" ? faq.question_en : faq.question,
    answer: locale.value === "en" ? faq.answer_en : faq.answer,
  }))
)

// Optional: refetch when language changes
watch(locale, () => {
  // يمكن إعادة المعالجة إذا لزم الأمر
})
</script>

<template>
  <div id="faq">
    <VContainer>
      <div class="faq-section">
        <!-- Header -->
        <div class="headers d-flex justify-center flex-column align-center">
          <VChip label color="primary" size="small" class="mb-4">
            {{ $t("FAQ") }}
          </VChip>

          <h4 class="d-flex align-center text-h4 mb-1 flex-wrap justify-center">
            <div class="position-relative ms-2">
              <div class="section-title">
                {{ $t("Frequently Asked questions") }}
              </div>
            </div>
          </h4>

          <p class="text-body-1 mb-0">
            {{ $t("Browse through these FAQs to find answers to commonly asked questions") }}
          </p>
        </div>

        <VRow>
          <!-- Image -->
          <VCol cols="12" md="5">
            <div class="pt-10 d-flex align-center justify-center h-100">
              <VImg :src="boyWithLogo" height="330" width="330" />
            </div>
          </VCol>

          <!-- FAQs -->
          <VCol cols="12" md="7">
            <VExpansionPanels class="pt-16">
              <VExpansionPanel
                v-for="faq in localizedFaqs"
                :key="faq.id"
              >
                <VExpansionPanelTitle>{{ faq.question }}</VExpansionPanelTitle>
                <VExpansionPanelText>{{ faq.answer }}</VExpansionPanelText>
              </VExpansionPanel>

              <!-- Loading state -->
              <div v-if="loading" class="text-center py-6">
                {{ $t("Loading FAQs...") }}
              </div>

              <!-- Empty state -->
              <div v-if="!loading && localizedFaqs.length === 0" class="text-center py-6">
                {{ $t("No FAQs found") }}
              </div>
            </VExpansionPanels>
          </VCol>
        </VRow>
      </div>
    </VContainer>
  </div>
</template>

<style scoped lang="scss">
.faq-section {
  margin-block: 5.25rem;
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
  font-weight: 800;
  inline-size: 120%;
  inset-block-end: 12%;
  inset-inline-start: -12%;
}
</style>




<style lang="scss" scoped>
.faq-section {
  margin-block: 5.25rem;
}

@media (max-width: 600px) {
  .faq-section {
    margin-block: 4rem;
  }
}

#faq {
  border-radius: 3.75rem 3.75rem 0 0;
  background-color: rgba(var(--v-theme-background));
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
  font-weight: 800;
  inline-size: 130%;
  inset-block-end: 12%;
  inset-inline-start: -12%;
}
</style>
