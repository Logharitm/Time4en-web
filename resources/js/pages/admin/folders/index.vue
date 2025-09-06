<script setup>
import {ref, computed, watch, onMounted} from 'vue'
import {useRouter} from 'vue-router'
import AddFolderDrawer from './AddFolderDrawer.vue'
import EditFolderDrawer from './EditFolderDrawer.vue'

// Router
const router = useRouter()

// Filters
const searchQuery = ref('')

// Data table
const itemsPerPage = ref(10)
const page = ref(1)
const sortBy = ref()
const orderBy = ref()
const selectedRows = ref([])

const updateOptions = options => {
  sortBy.value = options.sortBy[0]?.key
  orderBy.value = options.sortBy[0]?.order
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

// Delete
const isDeleteConfirmDialogVisible = ref(false)
const folderToDeleteId = ref(null)
const confirmDelete = id => {
  folderToDeleteId.value = id
  isDeleteConfirmDialogVisible.value = true
}
const executeDelete = async () => {
  if (!folderToDeleteId.value) return
  await deleteFolder(folderToDeleteId.value)
  isDeleteConfirmDialogVisible.value = false
  folderToDeleteId.value = null
}

// Headers
const headers = [
  {title: 'اسم الفولدر', key: 'name'},
  {title: 'الوصف', key: 'description'},
  {title: 'المستخدم', key: 'user_name'},
  {title: 'عدد الكلمات', key: 'words_count'},
  {title: 'تاريخ الإنشاء', key: 'created_at'},
  {title: 'العمليات', key: 'actions', sortable: false},
]

// API
const foldersData = ref([])
const totalFolders = ref(0)
const loading = ref(true)

// Fetch data with backend filtering
const fetchFolders = async () => {
  loading.value = true
  try {
    const params = {
      search: searchQuery.value || undefined,
      per_page: itemsPerPage.value,
      page: page.value,
      sort_by: sortBy.value,
      sort_order: orderBy.value,
    }

    const response = await $api('/folders', {method: 'GET', params})
    if (response.status === 'success') {
      foldersData.value = response.data
      totalFolders.value = response.meta?.total || 0
    }
  } catch (err) {
    console.error('Error fetching folders', err)
  } finally {
    loading.value = false
  }
}

// Reset filters function
const resetFilters = () => {
  searchQuery.value = ''
}

onMounted(() => {
  fetchFolders()
})

watch(
  [searchQuery, itemsPerPage, page, sortBy, orderBy],
  fetchFolders
)

const folders = computed(() => foldersData.value)

// Add / Edit
const isAddFolderDrawerVisible = ref(false)
const isEditFolderDrawerVisible = ref(false)
const folderToEdit = ref(null)

const openEditDrawer = folder => {
  folderToEdit.value = folder
  isEditFolderDrawerVisible.value = true
}
const openView = (id) => router.push(`/admin/folders/${id}`)

const addNewFolder = async formData => {
  try {
    await $api('/folders/store', {method: 'POST', body: formData})
    triggerToast('تم إضافة الفولدر بنجاح', 'success')
    fetchFolders()
  } catch (err) {
    triggerToast('حدث خطأ، حاول مرة أخرى', 'error')
  }
}

const updateFolder = async (id, formData) => {
  try {
    await $api(`/folders/${id}/update`, {method: 'POST', body: formData})
    triggerToast('تم تعديل الفولدر بنجاح', 'success')
    fetchFolders()
  } catch (err) {
    triggerToast('حدث خطأ، حاول مرة أخرى', 'error')
  }
}

const deleteFolder = async id => {
  try {
    await $api(`/folders/${id}/delete`, {method: 'POST'})
    triggerToast('تم حذف الفولدر بنجاح', 'success')
    fetchFolders()
  } catch (err) {
    triggerToast('حدث خطأ أثناء الحذف', 'error')
  }
}
</script>

<template>
  <section>
    <VCard class="mb-6">
      <VCardItem class="pb-4">
        <VCardTitle>الفولدرات</VCardTitle>
      </VCardItem>

      <VCardText class="d-flex flex-wrap gap-4 align-center">

        <div class="me-3 d-flex gap-3">
          <AppSelect
            label="عرض"
            :model-value="itemsPerPage"
            :items="[10,25,50,100].map(i=>({ value:i, title:i }))"
            style="inline-size: 6.25rem;"
            @update:model-value="itemsPerPage = parseInt($event, 10)"
          />
        </div>

        <div style="min-width: 200px;">
          <AppTextField
            v-model="searchQuery"
            placeholder="بحث باسم الفولدر"
            label="اسم الفولدر"
          />
        </div>

        <VSpacer/>

        <VBtn
          prepend-icon="tabler-rotate-clockwise"
          @click="resetFilters"
          variant="outlined"
        >
          إعادة تعيين
        </VBtn>

        <VBtn prepend-icon="tabler-plus" @click="isAddFolderDrawerVisible = true">
          إضافة فولدر جديد
        </VBtn>
      </VCardText>

      <VDivider/>

      <VDataTableServer
        v-model:items-per-page="itemsPerPage"
        v-model:model-value="selectedRows"
        v-model:page="page"
        :items="folders"
        item-value="id"
        :items-length="totalFolders"
        :headers="headers"
        class="text-no-wrap"
        :loading="loading"
        @update:options="updateOptions"
      >
        <template #item.user_name="{ item }">
          <a @click.prevent="router.push(`/admin/users/${item.user.id}`)" class="text-primary cursor-pointer">
            {{ item.user_name }}
          </a>
        </template>
        <template #item.actions="{ item }">
          <IconBtn @click="openView(item.id)">
            <VIcon icon="tabler-eye"/>
          </IconBtn>
          <IconBtn @click="openEditDrawer(item)">
            <VIcon icon="tabler-pencil"/>
          </IconBtn>
          <IconBtn @click="confirmDelete(item.id)">
            <VIcon icon="tabler-trash"/>
          </IconBtn>
        </template>
        <template #bottom>
          <TablePagination
            v-model:page="page"
            :items-per-page="itemsPerPage"
            :total-items="totalFolders"
          />
        </template>
      </VDataTableServer>
    </VCard>

    <AddFolderDrawer
      v-model:is-drawer-open="isAddFolderDrawerVisible"
      @folder-data="addNewFolder"
    />

    <EditFolderDrawer
      v-model:is-drawer-open="isEditFolderDrawerVisible"
      :folder-data="folderToEdit"
      @folder-data="updateFolder"
    />

    <VSnackbar v-model="showToast" :color="color" location="top end" timeout="5000">
      <template #prepend>
        <VIcon v-if="color==='success'" icon="tabler-check"/>
        <VIcon v-else-if="color==='error'" icon="tabler-alert-circle"/>
      </template>
      {{ message }}
      <template #actions>
        <VBtn icon variant="text" color="white" @click="showToast=false">
          <VIcon icon="tabler-x"/>
        </VBtn>
      </template>
    </VSnackbar>

    <VDialog v-model="isDeleteConfirmDialogVisible" max-width="500px">
      <VCard>
        <VCardTitle class="text-h6">تأكيد الحذف</VCardTitle>
        <VCardText>هل أنت متأكد أنك تريد حذف هذا الفولدر؟ لا يمكن التراجع عن هذا الإجراء.</VCardText>
        <VCardActions class="px-6 pb-4">
          <VSpacer/>
          <VBtn color="error" variant="flat" @click="isDeleteConfirmDialogVisible=false">إلغاء</VBtn>
          <VBtn color="success" variant="flat" @click="executeDelete">موافق</VBtn>
        </VCardActions>
      </VCard>
    </VDialog>
  </section>
</template>
