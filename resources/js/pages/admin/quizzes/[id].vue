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
    <VCard v-if="!loading && report" class="mb-6">
      <VCardTitle>📊 تقرير الاختبار</VCardTitle>
      <VCardText>
        <p><strong>إجمالي الأسئلة:</strong> {{ report.total_questions }}</p>
        <p><strong>الإجابات الصحيحة:</strong> {{ report.correct_answers_count }}</p>
        <p><strong>الإجابات الخاطئة:</strong> {{ report.wrong_answers_count }}</p>
        <p><strong>النسبة المئوية:</strong> {{ report.score_percentage }}%</p>
      </VCardText>

      <VDivider class="my-4" />

      <!-- Tabs -->
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
              { title: 'إجابتك', key: 'your_answer' },
              { title: 'الإجابة الصحيحة', key: 'correct_answer' },
            ]"
            :items="report.correct_answers"
            class="text-no-wrap"
          />
        </VWindowItem>

        <!-- Tab: Wrong Answers -->
        <VWindowItem value="wrong">
          <VDataTable
            :headers="[
              { title: 'الكلمة', key: 'word' },
              { title: 'الإجابة الصحيحة', key: 'correct_translation' },
              { title: 'إجابتك', key: 'your_answer' },
            ]"
            :items="Object.values(report.wrong_answers)"
            class="text-no-wrap"
          />
        </VWindowItem>
      </VWindow>
    </VCard>

    <VAlert v-else-if="!loading && error" type="error" variant="outlined">
      ⚠️ {{ error }}
    </VAlert>

    <VProgressCircular v-else indeterminate color="primary" />
  </section>
</template>
