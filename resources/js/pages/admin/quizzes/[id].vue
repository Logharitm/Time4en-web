<script setup>
import { ref, onMounted } from "vue"
import { useRoute } from "vue-router"

const route = useRoute()
const practiceId = route.params.id

const report = ref(null)
const loading = ref(true)
const error = ref(null)
const tab = ref("correct") // التبويب الافتراضي

onMounted(async () => {
  try {
    const response = await $api(`/practice/report/${practiceId}`, { method: "GET" })
    report.value = response.report
  } catch (err) {
    console.error("Error fetching report", err)
    error.value = err.response?.data?.message || "تعذر تحميل التقرير"
  } finally {
    loading.value = false
  }
})
</script>

<template>
  <section>
    <div v-if="!loading && report">
      <!-- البطاقات -->
      <VRow class="mb-6">
        <!-- إجمالي الأسئلة -->
        <VCol cols="12" sm="6" md="3">
          <VCard
            class="logistics-card-statistics"
            :style="`border-block-end-color: rgba(var(--v-theme-blue),0.38)`"
          >
            <VCardText>
              <div class="d-flex align-center gap-x-4 mb-1">
                <VAvatar variant="tonal" color="blue" rounded>
                  <VIcon icon="tabler-list-check" size="28" />
                </VAvatar>
                <h4 class="text-h4">{{ report.total_questions }}</h4>
              </div>
              <div class="text-body-1">إجمالي الأسئلة</div>
            </VCardText>
          </VCard>
        </VCol>

        <!-- الصحيحة -->
        <VCol cols="12" sm="6" md="3">
          <VCard
            class="logistics-card-statistics"
            :style="`border-block-end-color: rgba(var(--v-theme-green),0.38)`"
          >
            <VCardText>
              <div class="d-flex align-center gap-x-4 mb-1">
                <VAvatar variant="tonal" color="green" rounded>
                  <VIcon icon="tabler-check" size="28" />
                </VAvatar>
                <h4 class="text-h4">{{ report.correct_answers_count }}</h4>
              </div>
              <div class="text-body-1">الإجابات الصحيحة</div>
            </VCardText>
          </VCard>
        </VCol>

        <!-- الخاطئة -->
        <VCol cols="12" sm="6" md="3">
          <VCard
            class="logistics-card-statistics"
            :style="`border-block-end-color: rgba(var(--v-theme-red),0.38)`"
          >
            <VCardText>
              <div class="d-flex align-center gap-x-4 mb-1">
                <VAvatar variant="tonal" color="red" rounded>
                  <VIcon icon="tabler-x" size="28" />
                </VAvatar>
                <h4 class="text-h4">{{ report.wrong_answers_count }}</h4>
              </div>
              <div class="text-body-1">الإجابات الخاطئة</div>
            </VCardText>
          </VCard>
        </VCol>

        <!-- النسبة المئوية -->
        <VCol cols="12" sm="6" md="3">
          <VCard
            class="logistics-card-statistics"
            :style="`border-block-end-color: rgba(var(--v-theme-purple),0.38)`"
          >
            <VCardText>
              <div class="d-flex align-center gap-x-4 mb-1">
                <VAvatar variant="tonal" color="purple" rounded>
                  <VIcon icon="tabler-percentage" size="28" />
                </VAvatar>
                <h4 class="text-h4">{{ report.score_percentage }}%</h4>
              </div>
              <div class="text-body-1">النسبة المئوية</div>
            </VCardText>
          </VCard>
        </VCol>
      </VRow>

      <!-- Tabs -->
      <VCard>
        <VCardTitle class="text-h6">📊 تفاصيل الإجابات</VCardTitle>

        <VTabs v-model="tab" background-color="primary" dark>
          <VTab value="correct">✅ الإجابات الصحيحة</VTab>
          <VTab value="wrong">❌ الإجابات الخاطئة</VTab>
        </VTabs>

        <VWindow v-model="tab" class="mt-4">
          <!-- Tab: Correct Answers -->
          <VWindowItem value="correct">
            <VDataTable
              :headers="[
                { title: 'السؤال', key: 'question' },
                { title: 'الإجابة الصحيحة', key: 'correct_answer' },
                { title: 'إجابتك', key: 'your_answer' },
              ]"
              :items="report.correct_answers"
              class="text-no-wrap"
            />
          </VWindowItem>

          <!-- Tab: Wrong Answers -->
          <VWindowItem value="wrong">
            <VDataTable
              :headers="[
                { title: 'السؤال', key: 'word' },
                { title: 'الإجابة الصحيحة', key: 'correct_translation' },
                { title: 'إجابتك', key: 'your_answer' },
              ]"
              :items="Object.values(report.wrong_answers)"
              class="text-no-wrap"
            />
          </VWindowItem>
        </VWindow>
      </VCard>
    </div>

    <!-- خطأ -->
    <VAlert v-else-if="!loading && error" type="error" variant="outlined">
      ⚠️ {{ error }}
    </VAlert>

    <!-- لودينج -->
    <VProgressCircular v-else indeterminate color="primary" />
  </section>
</template>

<style scoped>
.logistics-card-statistics {
  border-block-end-style: solid;
  border-block-end-width: 2px;
  transition: all 0.2s ease-in-out;
}

.logistics-card-statistics:hover {
  border-block-end-width: 3px;
  margin-block-end: -1px;
  transform: translateY(-4px);
  cursor: pointer;
}
</style>
