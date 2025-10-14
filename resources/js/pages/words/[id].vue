<script setup>
import { ref, onMounted, onBeforeUnmount, computed } from "vue"
import Footer from '@/views/front-pages/front-page-footer.vue'
import Navbar from '@/views/front-pages/front-page-navbar.vue'
import AddWordDrawer from "@/pages/admin/words/AddWordDrawer.vue"
import EditWordDrawer from "@/pages/admin/words/EditWordDrawer.vue"
import { useRoute } from "vue-router"
import { useConfigStore } from '@core/stores/config.js'

const store = useConfigStore()
const route = useRoute()

// ✅ المتغيرات
const activeSectionId = ref()
const words = ref([])
const loading = ref(false)
const folderName = ref('')

// ✅ الباجنيشن
const currentPage = ref(1)
const perPage = ref(20)
const totalPages = ref(1)

// Drawers
const isAddDrawerOpen = ref(false)
const isEditDrawerOpen = ref(false)
const selectedWord = ref(null)

// ✅ حوار الحذف
const isDeleteConfirmDialogVisible = ref(false)
const wordToDeleteId = ref(null)

// ✅ الصوت
const currentAudio = ref(null)
const playingWordId = ref(null)

// ✅ Toast
const showToast = ref(false)
const message = ref('')
const color = ref('success')

const triggerToast = (msg, type = 'success') => {
  message.value = msg
  color.value = type
  showToast.value = true
}

// ✅ جلب الكلمات
const fetchWords = async (page = 1) => {
  loading.value = true
  try {
    const res = await $api(`/words?folder_id=${route.params.id}&page=${page}&per_page=${perPage.value}`, { method: "GET" })

    if (res.status === "success" && res.data) {
      words.value = res.data || []

      if (res.meta) {
        currentPage.value = res.meta.current_page
        perPage.value = res.meta.per_page
        totalPages.value = res.meta.last_page
      }
    }
  } catch (err) {
    console.error("Error fetching words", err)
  } finally {
    loading.value = false
  }
}

// ✅ جلب اسم المجلد
const fetchFolder = async () => {
  try {
    const res2 = await $api(`/folders/${route.params.id}`, { method: "GET" })
    if (res2.status === "success" && res2.data) {
      folderName.value = res2.data.name
    }
  } catch (err) {
    console.error("Error fetching folder", err)
  } finally {
    loading.value = false
  }
}

// ✅ إضافة كلمة
const addWord = async formData => {
  try {
    const res = await $api("/words", { method: "POST", body: formData })

    if (res.status === "error") {
      triggerToast(res.message || $t('حدث خطأ أثناء إضافة الكلمة'), 'error')
      
      return
    }


    fetchWords(currentPage.value)
    triggerToast($t('تم إضافة الكلمة بنجاح'), 'success')

  } catch (err) {
    triggerToast($t('حدث خطأ غير متوقع'), 'error')
  }
}

// ✅ تعديل كلمة
const editWord = async (id, formData) => {
  try {
    await $api(`/words/${id}/update`, { method: "POST", body: formData })
    fetchWords(currentPage.value)
  } catch (err) {
    console.error("Error editing word", err)
  }
}

// ✅ حذف كلمة
const confirmDelete = id => {
  wordToDeleteId.value = id
  isDeleteConfirmDialogVisible.value = true
}

const executeDelete = async () => {
  if (!wordToDeleteId.value) return
  try {
    await $api(`/words/${wordToDeleteId.value}/delete`, { method: "POST" })
    fetchWords(currentPage.value)
  } catch (err) {
    console.error("Error deleting word", err)
  } finally {
    isDeleteConfirmDialogVisible.value = false
    wordToDeleteId.value = null
  }
}

// ✅ تشغيل / إيقاف الصوت + Text-to-Speech fallback
const toggleAudio = item => {
  // إيقاف أي صوت شغال
  if (currentAudio.value) {
    currentAudio.value.pause()
    currentAudio.value.currentTime = 0
    currentAudio.value = null
  }

  // لو ضغط على نفس الكلمة => إيقاف فقط
  if (playingWordId.value === item.id) {
    playingWordId.value = null
    
    return
  }

  playingWordId.value = item.id

  // لو عنده ملف صوتي
  if (item.audio_url && item.audio_url !== "null") {
    const audio = new Audio(item.audio_url)

    currentAudio.value = audio
    audio.play()
    audio.onended = () => {
      playingWordId.value = null
      currentAudio.value = null
    }
    audio.onerror = () => {
      currentAudio.value = null
      speakText(item.word)
    }
  } else {
    // fallback لو مفيش ملف صوتي
    speakText(item.word)
  }
}

// ✅ Text-to-Speech
function speakText(text) {
  if (!text) return
  const utterance = new SpeechSynthesisUtterance(text)

  utterance.lang = "en-US"
  utterance.rate = 1
  utterance.pitch = 1
  speechSynthesis.cancel()
  speechSynthesis.speak(utterance)
  utterance.onend = () => {
    playingWordId.value = null
  }
}

// ✅ قراءة الجملة
function readSentence(sentence) {
  if (!sentence) return
  const utterance = new SpeechSynthesisUtterance(sentence)

  utterance.lang = "en-US"
  speechSynthesis.cancel()
  speechSynthesis.speak(utterance)
}

// ✅ إيقاف الصوت عند مغادرة الصفحة
onBeforeUnmount(() => {
  if (currentAudio.value) {
    currentAudio.value.pause()
    currentAudio.value = null
  }
  speechSynthesis.cancel()
})

const breadcrumbs = computed(() => [
  { text: 'الرئيسية', to: '/', icon: 'tabler-home' },
  { text: 'المجلدات', to: '/folders', icon: 'tabler-folders' },
  { text: folderName.value || '...', to: null },
  { text: 'الكلمات', to: null },
])

store.skin = 'default'

definePage({
  meta: { layout: 'blank', public: true },
})

onMounted(() => {
  fetchFolder()
  fetchWords(currentPage.value)
})
</script>

<template>
  <div class="landing-page-wrapper">
    <Navbar :active-id="activeSectionId" />
    <VContainer
      class="page-content-wrapper"
      style="margin-top: 140px !important;"
    >
      <VBreadcrumbs
        :items="breadcrumbs"
        divider=">"
      >
        <template #item="{ item }">
          <RouterLink
            v-if="item.to"
            :to="item.to"
            class="d-flex align-center"
          >
            <VIcon
              v-if="item.icon"
              :icon="item.icon"
              size="18"
              class="mr-1"
            />
            {{ item.text }}
          </RouterLink>
          <span
            v-else
            class="d-flex align-center"
          >
            <VIcon
              v-if="item.icon"
              :icon="item.icon"
              size="18"
              class="mr-1"
            />
            {{ item.text }}
          </span>
        </template>
      </VBreadcrumbs>

      <VCol cols="12">
        <div id="words">
          <VContainer>
            <!-- Header -->
            <div class="d-flex justify-space-between align-center mb-6">
              <h4 class="text-h4">
                {{ $t("Words") }}
              </h4>
              <VBtn
                color="primary"
                @click="isAddDrawerOpen = true"
              >
                <VIcon class="tabler-plus ml-2" />
                {{ $t("Add Word") }}
              </VBtn>
            </div>

            <VRow>
              <VCol
                v-for="word in words"
                :key="word.id"
                cols="12"
                sm="6"
                lg="4"
              >
                <VCard class="word-card">
                  <!-- الكلمة -->
                  <div class="main-word-box d-flex align-center justify-space-between">
                    <span>{{ word.word }}</span>
                    <VBtn
                      icon
                      size="small"
                      color="white"
                      variant="text"
                      class="play-btn"
                      @click="toggleAudio(word)"
                    >
                      <VIcon :icon="playingWordId === word.id ? 'tabler-pause' : 'tabler-play'" />
                    </VBtn>
                  </div>

                  <!-- الترجمة -->
                  <VCardText>
                    <p class="translation-en">
                      {{ $t("Translation") }}: <b>{{ word.translation || "-" }}</b>
                    </p>
                  </VCardText>

                  <!-- المثال -->
                  <VCardText>
                    <div
                      class="example-box d-flex align-center justify-space-between"
                      style="direction: ltr"
                    >
                      <span class="sentence">{{ word.example_sentence || "-" }}</span>
                      <VBtn
                        icon
                        color="primary"
                        size="small"
                        @click="readSentence(word.example_sentence)"
                      >
                        <VIcon icon="tabler-volume" />
                      </VBtn>
                    </div>
                  </VCardText>

                  <!-- الأزرار -->
                  <div
                    class="v-row"
                    style="padding:10px"
                  >
                    <VCol>
                      <VBtn
                        color="primary"
                        variant="outlined"
                        block
                        @click="(selectedWord = word, isEditDrawerOpen = true)"
                      >
                        {{ $t("Edit") }}
                      </VBtn>
                    </VCol>
                    <VCol>
                      <VBtn
                        color="primary"
                        variant="outlined"
                        block
                        @click="confirmDelete(word.id)"
                      >
                        {{ $t("Delete") }}
                      </VBtn>
                    </VCol>
                  </div>
                </VCard>
              </VCol>
            </VRow>

            <div
              v-if="loading"
              class="text-center py-6"
            >
              {{ $t("Loading") }}...
            </div>

            <div
              v-if="!loading && totalPages > 1"
              class="d-flex justify-center my-6"
            >
              <VPagination
                v-model="currentPage"
                :length="totalPages"
                total-visible="7"
                @update:model-value="fetchWords"
              />
            </div>
          </VContainer>
        </div>
      </VCol>
    </VContainer>

    <Footer />

    <AddWordDrawer
      v-model:is-drawer-open="isAddDrawerOpen"
      :folder-id="route.params.id"
      @word-data="addWord"
    />
    <EditWordDrawer
      v-model:is-drawer-open="isEditDrawerOpen"
      :word-data="selectedWord"
      @submit-word="editWord"
    />

    <VDialog
      v-model="isDeleteConfirmDialogVisible"
      max-width="500px"
    >
      <VCard>
        <VCardTitle class="text-h6">
          تأكيد الحذف
        </VCardTitle>
        <VCardText>هل أنت متأكد أنك تريد حذف هذه الكلمة؟</VCardText>
        <VCardActions class="px-6 pb-4">
          <VSpacer />
          <VBtn
            color="error"
            variant="flat"
            @click="isDeleteConfirmDialogVisible = false"
          >
            إلغاء
          </VBtn>
          <VBtn
            color="success"
            variant="flat"
            @click="executeDelete"
          >
            موافق
          </VBtn>
        </VCardActions>
      </VCard>
    </VDialog>

    <VSnackbar
      v-model="showToast"
      :color="color"
      location="top end"
      timeout="5000"
    >
      <template #prepend>
        <VIcon
          v-if="color==='success'"
          icon="tabler-check"
        />
        <VIcon
          v-else-if="color==='error'"
          icon="tabler-alert-circle"
        />
      </template>
      {{ message }}
      <template #actions>
        <VBtn
          icon
          variant="text"
          color="white"
          @click="showToast=false"
        >
          <VIcon icon="tabler-x" />
        </VBtn>
      </template>
    </VSnackbar>
  </div>
</template>

<style lang="scss">
.v-navigation-drawer--temporary.v-navigation-drawer--active {
  left: 0px;
  z-index: 1025 !important;
  transform: translateX(0px);
  position: fixed;
  height: calc(100%) !important;
  top: 0px !important;
  bottom: 0px;
  width: 400px;
}

#words {
  border-radius: 2rem;
  background-color: rgb(var(--v-theme-background));
  padding: 2rem;
}

.word-card {
  box-shadow: 0 4px 14px rgba(0,0,0,0.08);
  overflow: hidden;
}

.main-word-box {
  background-color: rgb(var(--v-theme-primary));
  color: white;
  font-size: 1.2rem;
  font-weight: 700;
  text-align: center;
  padding: 7px 12px;
  border-bottom: 1px solid rgba(255, 255, 255, 0.15);

  .play-btn {
    color: white;
    transition: transform 0.2s;
    &:hover {
      transform: scale(1.1);
    }
  }
}

.translation-en {
  margin: 0.25rem 0;
  font-size: 1rem;
  color: rgba(var(--v-theme-on-surface), 0.8);
}

.example-box {
  margin-top: 0.75rem;
  padding: 0.75rem 1rem;
  border-radius: 0.75rem;
  background-color: rgba(var(--v-theme-primary), 0.05);
}

.sentence {
  font-weight: 500;
  color: rgba(var(--v-theme-on-surface), 0.9);
}

.front-page-navbar[data-v-bd06682f]::after {
  backdrop-filter: unset !important;
  height: 1px !important;
}
.v-card { border-radius: 0!important; }
.landing-page-wrapper {
  min-height: 100vh; /* ✅ طول الشاشة بالكامل */
  display: flex;
  flex-direction: column;
}

/* ✅ المحتوى الرئيسي ياخد المساحة المتبقية بين الهيدر والفوتر */
.page-content-wrapper {
  flex: 1;
  display: flex;
  flex-direction: column;
  justify-content: center; /* لو عايز المحتوى ييجي في النص عموديًا */
  padding-top: 2rem;
  padding-bottom: 2rem;
}
</style>
