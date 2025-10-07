<script setup>
import { ref, onMounted, computed } from "vue"
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

// ✅ تشغيل الصوت
const currentAudio = ref(null)
const playingWordId = ref(null)

// جلب الكلمات
const fetchWords = async (page = 1) => {
  loading.value = true
  try {
    const res = await $api(`/words?folder_id=${route.params.id}&page=${page}&per_page=${perPage.value}`, { method: "GET" })

    if (res.status === "success" && res.data) {
      // ✅ الكلمات نفسها
      words.value = res.data || []

      // ✅ بيانات الباجنيشن من meta
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

// Toast
const showToast = ref(false)
const message = ref('')
const color = ref('success')
const triggerToast = (msg, type = 'success') => {
  message.value = msg
  color.value = type
  showToast.value = true
}

// إضافة كلمة
const addWord = async formData => {
  try {
    const res = await $api("/words", { method: "POST", body: formData })

    if (res.status === "error") {
      // السيرفر رجّع استجابة خطأ
      triggerToast(res.message || 'حدث خطأ أثناء إضافة الكلمة', 'error')
      return
    }

    // نجاح
    fetchWords(currentPage.value)
    triggerToast('تم إضافة الكلمة بنجاح', 'success')

  } catch (err) {
    // لو حصل خطأ من الـ fetch أو الـ API (403, 500, ...)
    console.error(err)

    // حاول تطلع الرسالة من السيرفر لو موجودة
    if (err.response && err.response._data && err.response._data.message) {
      triggerToast(err.response._data.message, 'error')
    } else {
      triggerToast('حدث خطأ غير متوقع', 'error')
    }
  }
}


// تعديل كلمة
const editWord = async (id, formData) => {
  try {
    await $api(`/words/${id}/update`, { method: "POST", body: formData })
    fetchWords(currentPage.value)
  } catch (err) {
    console.error("Error editing word", err)
  }
}

// حذف كلمة
const confirmDelete = id => {
  wordToDeleteId.value = id
  isDeleteConfirmDialogVisible.value = true
}

store.skin = 'default'

definePage({
  meta: { layout: 'blank', public: true },
})

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

// ✅ تشغيل / إيقاف الصوت
const toggleAudio = item => {
  if (!item.audio_url) return
  if (currentAudio.value) {
    currentAudio.value.pause()
    currentAudio.value.currentTime = 0
  }
  if (playingWordId.value === item.id) {
    playingWordId.value = null
    currentAudio.value = null
  } else {
    const audio = new Audio(item.audio_url)
    currentAudio.value = audio
    playingWordId.value = item.id
    audio.play()
    audio.onended = () => {
      playingWordId.value = null
      currentAudio.value = null
    }
  }
}

function readSentence(sentence) {
  if (!sentence) return
  const utterance = new SpeechSynthesisUtterance(sentence)
  utterance.lang = "en-US"
  speechSynthesis.speak(utterance)
}

const folderName = ref('')

const breadcrumbs = computed(() => [
  { text: 'الرئيسية', to: '/', icon: 'tabler-home' },
  { text: 'المجلدات', to: '/folders', icon: 'tabler-folders' },
  { text: folderName.value || '...', to: null },
  { text: 'الكلمات', to: null },
])

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
      <VCol cols="12 ">
        <div id="words">
          <VContainer>
            <!-- Header + Add button -->
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
                  <!-- الكلمة الأساسية + زر الصوت -->
                  <div class="main-word-box d-flex align-center justify-space-between">
                    <span>{{ word.word }}</span>
                    <VBtn
                      v-if="word.audio_url"
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
                      <span class="sentence">
                        {{ word.example_sentence || "-" }}
                      </span>
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
                  <div class="v-row" style="padding:10px">
                    <VCol class="v-col">
                      <VBtn
                        color="primary"
                        variant="outlined"
                        block
                        @click="(selectedWord = word, isEditDrawerOpen = true)"
                      >
                        {{ $t("Edit") }}
                      </VBtn>
                    </VCol>
                    <VCol  class="v-col">
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

            <!-- Loading -->
            <div v-if="loading" class="text-center py-6">
              {{ $t("Loading") }}...
            </div>

            <!-- ✅ Pagination -->
            <div v-if="!loading && totalPages > 1" class="d-flex justify-center my-6">
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

    <!-- Drawers -->
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

    <!-- Dialog -->
    <VDialog
      v-model="isDeleteConfirmDialogVisible"
      max-width="500px"
    >
      <VCard>
        <VCardTitle class="text-h6">تأكيد الحذف</VCardTitle>
        <VCardText>هل أنت متأكد أنك تريد حذف هذه الكلمة؟</VCardText>
        <VCardActions class="px-6 pb-4">
          <VSpacer />
          <VBtn color="error" variant="flat" @click="isDeleteConfirmDialogVisible = false">
            إلغاء
          </VBtn>
          <VBtn color="success" variant="flat" @click="executeDelete">
            موافق
          </VBtn>
        </VCardActions>
      </VCard>
    </VDialog>

    <VSnackbar v-model="showToast" :color="color" location="top end" timeout="5000">
      <template #prepend>
        <VIcon v-if="color==='success'" icon="tabler-check"/>
        <VIcon v-else-if="color==='error'" icon="tabler-alert-circle"/>
      </template>
      {{ message }}
      <template #actions>
        <VBtn icon variant="text" color="white" @click="showToast=false"><VIcon icon="tabler-x"/></VBtn>
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
.card-title-bar {
  width: 100%;
  background-color: rgb(var(--v-theme-primary));
  color: #fff;
  font-weight: 600;
  font-size: 1.1rem;
  padding: 0.75rem 1rem;
}
.card-actions-bordered .v-col {
  padding: 7px !important;
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

.translation-ar,
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

.example-box .label {
  font-weight: 600;
  color: rgb(var(--v-theme-primary));
  margin-right: 0.5rem;
}

.example-box .sentence {
  font-weight: 500;
  color: rgba(var(--v-theme-on-surface), 0.9);
}

.card-actions {
  display: flex;
  gap: 0.75rem;
  justify-content: flex-end;
}
.front-page-navbar[data-v-bd06682f]::after {
  backdrop-filter: unset !important;
  height: 1px !important;
}
.v-card{border-radius: 0!important;}
</style>
