<script setup>
import {
  ref,
  computed,
  watch,
  onMounted
} from 'vue';
import {
  useRouter
} from 'vue-router'; // ✅ استيراد useRouter
import AddNewUserDrawer from './AddNewUserDrawer.vue';
import EditUserDrawer from './EditUserDrawer.vue';

// Router instance
const router = useRouter(); // ✅ الحصول على كائن router

// filters
const searchQuery = ref('');

// Data table options
const itemsPerPage = ref(10);
const page = ref(1);
const sortBy = ref();
const orderBy = ref();
const selectedRows = ref([]);

const updateOptions = options => {
  sortBy.value = options.sortBy[0]?.key;
  orderBy.value = options.sortBy[0]?.order;
};

// 👉 Toast state
const showToast = ref(false);
const message = ref('');
const color = ref('success');

const triggerToast = (msg, type = 'success') => {
  message.value = msg;
  color.value = type;
  showToast.value = true;
};

// 👈 إضافة متغيرات ودوال نافذة التأكيد
const isDeleteConfirmDialogVisible = ref(false);
const userToDeleteId = ref(null);

const confirmDelete = (userId) => {
  userToDeleteId.value = userId;
  isDeleteConfirmDialogVisible.value = true;
};

const executeDelete = async () => {
  if (userToDeleteId.value) {
    await deleteUser(userToDeleteId.value);
    isDeleteConfirmDialogVisible.value = false;
    userToDeleteId.value = null;
  }
};

const calculateRemainingDays = (endDateString) => {
  if (!endDateString) return 'غير محدد';

  const today = new Date();
  const todayMidnight = new Date(today.getFullYear(), today.getMonth(), today.getDate());

  const endDate = new Date(endDateString);
  const endDateMidnight = new Date(endDate.getFullYear(), endDate.getMonth(), endDate.getDate());

  const differenceInTime = endDateMidnight.getTime() - todayMidnight.getTime();
  const differenceInDays = Math.ceil(differenceInTime / (1000 * 60 * 60 * 24));

  if (differenceInDays > 0) {
    return `متبقي ${differenceInDays} يوم`;
  } else if (differenceInDays === 0) {
    return 'ينتهي اليوم';
  } else {
    return 'منتهية';
  }
};


// Headers
const headers = [
  { title: 'الادمن', key: 'user' },
  { title: 'النوع', key: 'role' },
  { title: 'البريد الالكتروني', key: 'email' },
  { title: 'العمليات', key: 'actions', sortable: false },
];

// API
const usersData = ref([]);
const totalUsers = ref(0);
const loading = ref(true);

const fetchUsers = async () => {
  loading.value = true;
  try {
    const response = await $api('/users', {
      method: 'GET',
      params: {
        role: 'admin',
        search: searchQuery.value,
        per_page: itemsPerPage.value,
        page: page.value,
        sort_by: sortBy.value,
        sort_order: orderBy.value,
      },
    });

    if (response.status === 'success') {
      usersData.value = response.data;
      totalUsers.value = response.meta?.total || 0;
    }
  } catch (err) {
    console.error('Error fetching users', err);
  } finally {
    loading.value = false;
  }
};

onMounted(fetchUsers);

watch([searchQuery, itemsPerPage, page, sortBy, orderBy], fetchUsers);

const users = computed(() => usersData.value);

// helpers
const resolveUserRoleVariant = role => {
  const roleLowerCase = role?.toLowerCase();
  if (roleLowerCase === 'subscriber') return { color: 'success', icon: 'tabler-user' };
  if (roleLowerCase === 'author') return { color: 'error', icon: 'tabler-device-desktop' };
  if (roleLowerCase === 'maintainer') return { color: 'info', icon: 'tabler-chart-pie' };
  if (roleLowerCase === 'editor') return { color: 'warning', icon: 'tabler-edit' };
  if (roleLowerCase === 'admin') return { color: 'primary', icon: 'tabler-crown' };

  return { color: 'primary', icon: 'tabler-user' };
};

const prefixWithPlus = value => (value > 0 ? `+${value}` : value);

const isAddNewUserDrawerVisible = ref(false);

const addNewUser = async userData => {
  try {
    await $api('/users/store', {
      method: 'POST',
      body: userData,
    });
    triggerToast('تم اضافة البيانات بنجاح', 'success');
    fetchUsers();
  } catch (err) {
    triggerToast('حدث خطأ من فضلك حاول في وقت اخر', 'error');
  }
};

const deleteUser = async id => {
  try {
    await $api(`/users/delete/${id}`, {
      method: 'POST',
    });
    const index = selectedRows.value.findIndex(row => row === id);
    if (index !== -1) selectedRows.value.splice(index, 1);

    triggerToast('تم الحذف بنجاح', 'success');
    fetchUsers();
  } catch (err) {
    console.error('Error deleting user:', err);
    triggerToast('حدث خطأ أثناء الحذف', 'error');
  }
};

// 👈 إضافة متغيرات ودوال التعديل
const isEditUserDrawerVisible = ref(false);
const userToEdit = ref(null);

const openEditDrawer = user => {
  userToEdit.value = user;
  isEditUserDrawerVisible.value = true;
};

const updateUser = async (id, userData) => {
  try {
    await $api(`/users/update/${id}`, {
      method: 'POST',
      body: userData,
    });
    triggerToast('تم تعديل البيانات بنجاح', 'success');
    fetchUsers();
  } catch (err) {
    triggerToast('حدث خطأ من فضلك حاول في وقت اخر', 'error');
  }
};

// ✅ الدالة الجديدة للتوجيه إلى صفحة الادمن
const viewUser = (userId) => {
  router.push({ name: 'admin-users-id', params: { id: userId } });
};
</script>

<template>
  <section>
    <VCard class="mb-6">
      <VCardItem class="pb-4">
        <VCardTitle>الادمن</VCardTitle>
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
            <AppTextField
              v-model="searchQuery"
              placeholder="بحث"
            />
          </div>

          <VBtn
            prepend-icon="tabler-plus"
            @click="isAddNewUserDrawerVisible = true"
          >
            اضافة ادمن جديد
          </VBtn>
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
              <VImg
                v-if="item.avatar"
                :src="item.avatar"
              />
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
              <div class="text-sm">
                {{ item.email }}
              </div>
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
            <div class="text-capitalize text-high-emphasis text-body-1">
              {{
                item.role == 'user' ? 'عميل' : 'ادمن'
              }}
            </div>
          </div>
        </template>

        <template #item.plan="{ item }">
          <div class="text-body-1 text-high-emphasis text-capitalize">
            {{ item.subscription?.plan?.name || 'غير مشترك' }}
          </div>
        </template>

        <template #item.start_at="{ item }">
          <div class="text-body-1 text-high-emphasis">
            {{ item.subscription?.start_date ?? 'غير محدد' }}
          </div>
        </template>

        <template #item.expires_at="{ item }">
          <div class="text-body-1 text-high-emphasis">
            {{ item.subscription?.end_date ?? 'غير محدد' }}
          </div>
        </template>

        <template #item.remain="{ item }">
          <div class="text-body-1 text-high-emphasis">
            {{ calculateRemainingDays(item.subscription?.end_date) }}
          </div>
        </template>

        <template #item.actions="{ item }">
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
            :total-items="totalUsers"
          />
        </template>
      </VDataTableServer>
    </VCard>

    <AddNewUserDrawer
      v-model:is-drawer-open="isAddNewUserDrawerVisible"
      @user-data="addNewUser"
    />

    <EditUserDrawer
      v-model:is-drawer-open="isEditUserDrawerVisible"
      :user-data="userToEdit"
      @user-data="updateUser"
    />

    <VSnackbar
      v-model="showToast"
      :color="color"
      location="top end"
      timeout="5000"
    >
      <template #prepend>
        <VIcon v-if="color === 'success'" icon="tabler-check"/>
        <VIcon v-else-if="color === 'error'" icon="tabler-alert-circle"/>
        <VIcon v-else icon="tabler-info-circle"/>
      </template>

      {{ message }}

      <template #actions>
        <VBtn
          icon
          variant="text"
          color="white"
          @click="showToast = false"
        >
          <VIcon icon="tabler-x"/>
        </VBtn>
      </template>
    </VSnackbar>

    <VDialog
      v-model="isDeleteConfirmDialogVisible"
      max-width="500px"
    >
      <VCard>
        <VCardTitle class="text-h6">
          تأكيد الحذف
        </VCardTitle>
        <VCardText>
          هل أنت متأكد أنك تريد حذف هذا الادمن؟ لا يمكن التراجع عن هذا الإجراء.
        </VCardText>
        <VCardActions class="px-6 pb-4">
          <VSpacer/>
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
  </section>
</template>
