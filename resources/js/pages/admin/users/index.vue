<script setup>
import AddNewUserDrawer from './AddNewUserDrawer.vue'
import EditUserDrawer from './EditUserDrawer.vue' // 👈 إضافة مكوّن التعديل

// filters
const searchQuery = ref('')

// Data table options
const itemsPerPage = ref(10)
const page = ref(1)
const sortBy = ref()
const orderBy = ref()
const selectedRows = ref([])

const updateOptions = options => {
  sortBy.value = options.sortBy[0]?.key
  orderBy.value = options.sortBy[0]?.order
}

// Headers
const headers = [
  {title: 'المستخدم', key: 'user'},
  {title: 'النوع', key: 'role'},
  {title: 'باقة الاشتراك', key: 'plan'},
  {title: 'الانتهاء في', key: 'expires_at'},
  {title: 'العمليات', key: 'actions', sortable: false},
]

// API
const usersData = ref([])
const totalUsers = ref(0)
const loading = ref(true)

const fetchUsers = async () => {
  loading.value = true
  try {
    const response = await $api('/users', {
      method: 'GET',
      params: {
        role: 'user',
        search: searchQuery.value,
        per_page: itemsPerPage.value,
        page: page.value,
        sort_by: sortBy.value,
        sort_order: orderBy.value,
      },
    })

    if (response.status === 'success') {
      usersData.value = response.data
      totalUsers.value = response.meta?.total || 0
    }
  } catch (err) {
    console.error('Error fetching users', err)
  } finally {
    loading.value = false
  }
}

onMounted(fetchUsers)

watch([searchQuery, itemsPerPage, page, sortBy, orderBy], fetchUsers)

const users = computed(() => usersData.value)

// helpers
const resolveUserRoleVariant = role => {
  const roleLowerCase = role?.toLowerCase()
  if (roleLowerCase === 'subscriber') return {color: 'success', icon: 'tabler-user'}
  if (roleLowerCase === 'author') return {color: 'error', icon: 'tabler-device-desktop'}
  if (roleLowerCase === 'maintainer') return {color: 'info', icon: 'tabler-chart-pie'}
  if (roleLowerCase === 'editor') return {color: 'warning', icon: 'tabler-edit'}
  if (roleLowerCase === 'admin') return {color: 'primary', icon: 'tabler-crown'}
  return {color: 'primary', icon: 'tabler-user'}
}

const prefixWithPlus = value => (value > 0 ? `+${value}` : value)

const isAddNewUserDrawerVisible = ref(false)

const addNewUser = async userData => {
  try {
    await $api('/users/store', {
      method: 'POST',
      body: userData,
    })
    fetchUsers()
  } catch (err) {
    console.error('Error adding user:', err)
  }
}

const deleteUser = async id => {
  await $api(`/users/delete/${id}`, {
    method: 'DELETE',
  })

  const index = selectedRows.value.findIndex(row => row === id)
  if (index !== -1) selectedRows.value.splice(index, 1)

  fetchUsers()
}

// 👈 إضافة متغيرات ودوال التعديل
const isEditUserDrawerVisible = ref(false)
const userToEdit = ref(null)

const openEditDrawer = (user) => {
  userToEdit.value = user
  isEditUserDrawerVisible.value = true
}

const updateUser = async (id, userData) => {
  try {
    // يمكنك تعديل مسار الـ API حسب المسار الصحيح في الباك إند
    await $api(`/users/update/${id}`, {
      method: 'POST',
      body: userData,
    })
    fetchUsers()
  } catch (err) {
    console.error('Error updating user:', err)
  }
}

</script>

<template>
  <section>
    <VCard class="mb-6">
      <VCardItem class="pb-4">
        <VCardTitle>المستخدمين</VCardTitle>
      </VCardItem>

      <VCardText class="d-flex flex-wrap gap-4">
        <div class="me-3 d-flex gap-3">
          <AppSelect
            :model-value="itemsPerPage"
            :items="[
              { value: 10, title: '10' },
              { value: 25, title: '25' },
              { value: 50, title: '50' },
              { value: 100, title: '100' },
              { value: -1, title: 'All' },
            ]"
            style="inline-size: 6.25rem;"
            @update:model-value="itemsPerPage = parseInt($event, 10)"
          />
        </div>
        <VSpacer/>
        <div class="app-user-search-filter d-flex align-center flex-wrap gap-4">
          <div style="inline-size: 15.625rem;">
            <AppTextField v-model="searchQuery" placeholder="بحث"/>
          </div>

          <VBtn prepend-icon="tabler-plus" @click="isAddNewUserDrawerVisible = true">اضافة مستخدم جديد</VBtn>
        </div>
      </VCardText>

      <VDivider/>

      <VDataTableServer
        v-model:items-per-page="itemsPerPage"
        v-model:model-value="selectedRows"
        v-model:page="page"
        :items="users"
        item-value="id"
        :items-length="totalUsers"
        :headers="headers"
        class="text-no-wrap"
        :loading="loading"
        @update:options="updateOptions"
      >
        <template #item.user="{ item }">
          <div class="d-flex align-center gap-x-4">
            <VAvatar
              size="34"
              :variant="!item.avatar ? 'tonal' : undefined"
              :color="!item.avatar ? resolveUserRoleVariant(item.role).color : undefined"
            >
              <VImg v-if="item.avatar" :src="item.avatar"/>
              <span v-else>{{ item.name?.charAt(0).toUpperCase() }}</span>
            </VAvatar>
            <div class="d-flex flex-column">
              <h6 class="text-base">
                <RouterLink
                  :to="{ name: 'apps-user-view-id', params: { id: item.id } }"
                  class="font-weight-medium text-link"
                >
                  {{ item.name }}
                </RouterLink>
              </h6>
              <div class="text-sm">{{ item.email }}</div>
            </div>
          </div>
        </template>

        <template #item.role="{ item }">
          <div class="d-flex align-center gap-x-2">
            <VIcon
              :size="22"
              :icon="resolveUserRoleVariant(item.role).icon"
              :color="resolveUserRoleVariant(item.role).color"
            />
            <div class="text-capitalize text-high-emphasis text-body-1">{{
                item.role == 'user' ? 'عميل' : 'ادمن'
              }}
            </div>
          </div>
        </template>

        <template #item.plan="{ item }">
          <div class="text-body-1 text-high-emphasis text-capitalize">{{ item.subscription_plan || 'غير مشترك' }}</div>
        </template>

        <template #item.expires_at="{ item }">
          <div class="text-body-1 text-high-emphasis">{{ item.subscription_expires_at ?? 'غير محدد' }}</div>
        </template>

        <template #item.actions="{ item }">
          <IconBtn @click="openEditDrawer(item)">
            <VIcon icon="tabler-pencil"/>
          </IconBtn>
          <IconBtn @click="deleteUser(item.id)">
            <VIcon icon="tabler-trash"/>
          </IconBtn>
          <IconBtn>
            <VIcon icon="tabler-eye"/>
          </IconBtn>
        </template>

        <template #bottom>
          <TablePagination v-model:page="page" :items-per-page="itemsPerPage" :total-items="totalUsers"/>
        </template>
      </VDataTableServer>
    </VCard>

    <AddNewUserDrawer v-model:is-drawer-open="isAddNewUserDrawerVisible" @user-data="addNewUser"/>

    <EditUserDrawer
      v-model:is-drawer-open="isEditUserDrawerVisible"
      :user-data="userToEdit"
      @user-data="updateUser"
    />
  </section>
</template>
