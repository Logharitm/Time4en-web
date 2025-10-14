<script setup>
import { ref, onMounted, computed, nextTick } from "vue"
import Footer from '@/views/front-pages/front-page-footer.vue'
import Navbar from '@/views/front-pages/front-page-navbar.vue'
import { useConfigStore } from '@core/stores/config'
import AddFolderDrawer from "@/pages/admin/folders/AddFolderDrawer.vue"
import EditFolderDrawer from "@/pages/admin/folders/EditFolderDrawer.vue"

const store = useConfigStore()

store.skin = 'default'

definePage({
  meta: { layout: 'blank', public: true },
})

// ✅ Navbar refs
const activeSectionId = ref()
const refHome = ref(), refFeatures = ref(), refTeam = ref(), refContact = ref(), refFaq = ref()

useIntersectionObserver(
  [refHome, refFeatures, refTeam, refContact, refFaq],
  ([{ isIntersecting, target }]) => {
    if (isIntersecting) activeSectionId.value = target.id
  },
  { threshold: 0.25 },
)

// ✅ Data
const folders = ref([])
const loading = ref(false)

// Drawers
const isAddDrawerOpen = ref(false)
const isEditDrawerOpen = ref(false)
const selectedFolder = ref(null)

// ✅ Delete dialog
const isDeleteConfirmDialogVisible = ref(false)
const folderToDeleteId = ref(null)

const confirmDelete = id => {
  folderToDeleteId.value = id
  isDeleteConfirmDialogVisible.value = true
}

// ✅ Pagination refs
const currentPage = ref(1)
const perPage = ref(20)
const total = ref(0)

// حساب عدد الصفحات
const totalPages = computed(() => Math.ceil(total.value / perPage.value))

const executeDelete = async () => {
  if (!folderToDeleteId.value) return
  try {
    await $api(`/folders/${folderToDeleteId.value}/delete`, { method: "POST" })
    fetchFolders(currentPage.value)
  } catch (err) {
    console.error("Error deleting folder", err)
  } finally {
    isDeleteConfirmDialogVisible.value = false
    folderToDeleteId.value = null
  }
}

// Fetch folders
const fetchFolders = async (page = 1) => {
  loading.value = true
  try {
    const res = await $api(`/folders?page=${page}`, { method: "GET" })
    if (res.status === "success" && res.data) {
      folders.value = res.data

      // ✅ بيانات الباجنيشن من الـ meta
      if (res.meta) {
        currentPage.value = res.meta.current_page
        perPage.value = res.meta.per_page
        total.value = res.meta.total
      }
    }
  } catch (err) {
    console.error("Error fetching folders", err)
  } finally {
    loading.value = false
  }
}

import { useRouter } from 'vue-router'

const router = useRouter()


// Add folder
const addFolder = async formData => {
  try {
    const response = await $api("/folders", {
      method: "POST",
      body: formData,
    })

    const folderId = response.data.id // أو response.data.id حسب شكل الـ API

    fetchFolders(currentPage.value)

    if (folderId) {
      const target = `/words/${folderId}`

      if (router.currentRoute.value.fullPath !== target) {
        await nextTick(() => router.replace(target))
      }
    }
  } catch (err) {
    console.error("Error adding folder", err)
  }
}

// Edit folder
const editFolder = async (id, formData) => {
  try {
    await $api(`/folders/${id}/update`, { method: "POST", body: formData })
    fetchFolders(currentPage.value)
  } catch (err) {
    console.error("Error editing folder", err)
  }
}

onMounted(() => {
  fetchFolders(currentPage.value)
})
</script>

<template>
  <div class="landing-page-wrapper">
    <Navbar :active-id="activeSectionId" />

    <!-- 👉 المحتوى الرئيسي -->
    <VContainer class="page-content-wrapper mt-16">
      <VCol cols="12 mt-8">
        <div id="folders">
          <VContainer>
            <!-- Header + Add button -->
            <div class="d-flex justify-space-between align-center mb-6">
              <h4 class="text-h4">
                {{ $t("Folders") }}
              </h4>
              <VBtn
                color="primary"
                @click="isAddDrawerOpen = true"
              >
                <VIcon class="tabler-folder-plus ml-2" />
                {{ $t("Add Folder") }}
              </VBtn>
            </div>

            <VRow>
              <VCol
                v-for="folder in folders"
                :key="folder.id"
                cols="12"
                sm="6"
                lg="4"
              >
                <VCard>
                  <!-- ✅ عنوان الكارد بخلفية primary -->
                  <div class="card-title-bar">
                    <VIcon class="tabler-folder ml-2" />
                    {{ folder.name }}
                  </div>

                  <VCardText class="pa-6">
                    <p
                      class="text-body-6 font-bold mb-4"
                      style="font-size: 18px;line-height: 30px;"
                    >
                      {{ folder.description }}
                    </p>
                  </VCardText>

                  <!-- ✅ الأزرار أسفل الكارد -->
                  <VCardActions class="pa-0 card-actions-bordered">
                    <VRow
                      no-gutters
                      class="w-100"
                    >
                      <VCol cols="4">
                        <VMenu
                          location="top"
                          open-on-hover
                        >
                          <template #activator="{ props }">
                            <VBtn
                              block
                              color="primary"
                              variant="outlined"
                              v-bind="props"
                            >
                              ادارة المجلد
                            </VBtn>
                          </template>

                          <VList>
                            <VListItem @click="(selectedFolder = folder, isEditDrawerOpen = true)">
                              <VListItemTitle>{{ $t("Edit") }}</VListItemTitle>
                            </VListItem>

                            <VListItem @click="confirmDelete(folder.id)">
                              <VListItemTitle>{{ $t("Delete") }}</VListItemTitle>
                            </VListItem>
                          </VList>
                        </VMenu>
                      </VCol>

                      <VCol
                        cols="4"
                        class="btn-divider"
                      >
                        <RouterLink :to="`/words/${folder.id}`">
                          <VBtn
                            block
                            color="primary"
                            variant="outlined"
                          >
                            {{ $t("Words") }} ( {{ folder.words_count }} )
                          </VBtn>
                        </RouterLink>
                      </VCol>

                      <VCol
                        cols="4"
                        class="btn-divider"
                      >
                        <VBtn
                          block
                          color="primary"
                          variant="outlined"
                        >
                          {{ $t("Practice") }}
                        </VBtn>
                      </VCol>
                    </VRow>
                  </VCardActions>
                </VCard>
              </VCol>
            </VRow>

            <!-- Loading -->
            <div
              v-if="loading"
              class="text-center py-6"
            >
              {{ $t("Loading") }}...
            </div>

            <!-- ✅ Pagination -->
            <div
              v-if="!loading && totalPages > 1"
              class="d-flex justify-center my-6"
            >
              <VPagination
                v-model="currentPage"
                :length="totalPages"
                total-visible="7"
                @update:model-value="fetchFolders"
              />
            </div>
          </VContainer>
        </div>
      </VCol>
    </VContainer>

    <Footer />

    <!-- Drawers -->
    <AddFolderDrawer
      v-model:is-drawer-open="isAddDrawerOpen"
      @folder-data="addFolder"
    />
    <EditFolderDrawer
      v-model:is-drawer-open="isEditDrawerOpen"
      :folder-data="selectedFolder"
      @folder-data="editFolder"
    />

    <!-- ✅ Dialog تأكيد الحذف -->
    <VDialog
      v-model="isDeleteConfirmDialogVisible"
      max-width="500px"
    >
      <VCard>
        <VCardTitle class="text-h6">
          تأكيد الحذف
        </VCardTitle>
        <VCardText>
          هل أنت متأكد أنك تريد حذف هذا المجلد؟ لا يمكن التراجع عن هذا الإجراء.
        </VCardText>
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
  </div>
</template>

<style lang="scss">
#folders {
  border-radius: 2rem;
  background-color: rgb(var(--v-theme-background));
  padding: 2rem;
}

.landing-page-wrapper {
  min-height: 100vh;
  display: flex;
  flex-direction: column;
}

.page-content-wrapper {
  flex: 1;
  display: flex;
  flex-direction: column;
  padding-top: 2rem;
  padding-bottom: 2rem;
}

/* ✅ تنسيق عنوان الكارد */
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

.card-actions-bordered .v-col:last-child {
  border-right: none;
}

.v-navigation-drawer--temporary.v-navigation-drawer--active {
  left: 0px;
  z-index: 1024 !important;
  transform: translateX(0px);
  position: fixed;
  height: 100% !important;
  top: 0 !important;
  bottom: 0px;
  width: 400px;
}
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
.v-col{display: block;}
</style>
