<script setup>
import { PerfectScrollbar } from 'vue3-perfect-scrollbar'
import { ref } from 'vue'

const props = defineProps({
  notifications: {
    type: Array,
    required: true,
  },
  badgeProps: {
    type: Object,
    required: false,
    default: undefined,
  },
  location: {
    type: null,
    required: false,
    default: 'bottom end',
  },
})

const emit = defineEmits([
  'read',
  'unread',
  'remove',
  'click:notification',
])

// دالة لتحويل الإشعار لمقروء
const markNotificationRead = async (notification) => {
  // لو هو أصلاً مقروء متعملش حاجة
  if (notification.isSeen) return

  try {
    // نرسل طلب للـ API
    await $api(`/notifications/${notification.id}/read`, { method: 'POST' })

    // نحدث الحالة محليًا عشان يظهر فورًا
    notification.isSeen = true

    // نرسل الحدث للأب لو محتاجين
    emit('read', [notification.id])
  } catch (error) {
    console.error('Error marking notification as read:', error)
  }
}

// الدالة اللي تنادي لما تضغط على الإشعار
const handleNotificationClick = (notification) => {
  markNotificationRead(notification)
  emit('click:notification', notification)
}

const isAllMarkRead = computed(() => {
  return props.notifications.some(item => item.isSeen === false)
})

const markAllReadOrUnread = () => {
  const allNotificationsIds = props.notifications.map(item => item.id)
  if (!isAllMarkRead.value)
    emit('unread', allNotificationsIds)
  else
    emit('read', allNotificationsIds)
}

const totalUnseenNotifications = computed(() => {
  return props.notifications.filter(item => item.isSeen === false).length
})

</script>

<template>
  <IconBtn id="notification-btn">
    <VBadge
      v-bind="props.badgeProps"
      :model-value="props.notifications.some(n => !n.isSeen)"
      color="error"
      dot
      offset-x="2"
      offset-y="3"
    >
      <VIcon icon="tabler-bell" />
    </VBadge>

    <VMenu
      activator="parent"
      width="380px"
      :location="props.location"
      offset="12px"
      :close-on-content-click="false"
    >
      <VCard class="d-flex flex-column">
        <!-- Header -->
        <VCardItem class="notification-section">
          <VCardTitle class="text-h6">{{ $t('Notifications') }}</VCardTitle>
          <template #append>
            <VChip
              v-show="props.notifications.some(n => !n.isSeen)"
              size="small"
              color="primary"
              class="me-2"
            >
              {{ totalUnseenNotifications }} New
            </VChip>
            <IconBtn
              v-show="props.notifications.length"
              size="34"
              @click="markAllReadOrUnread"
            >
              <VIcon
                size="20"
                color="high-emphasis"
                :icon="!isAllMarkRead ? 'tabler-mail' : 'tabler-mail-opened' "
              />
            </IconBtn>
          </template>
        </VCardItem>

        <VDivider />

        <PerfectScrollbar :options="{ wheelPropagation: false }" style="max-block-size: 23.75rem;">
          <VList class="notification-list rounded-0 py-0">
            <template v-for="(notification, index) in props.notifications" :key="notification.id">
              <VDivider v-if="index > 0" />
              <VListItem
                link
                lines="one"
                min-height="66px"
                class="list-item-hover-class"
                :class="{ 'notification-unread': !notification.isSeen }"
                @click="handleNotificationClick(notification)"
              >
                <div class="d-flex align-start gap-3">
                  <div>
                    <p class="text-sm font-weight-medium mb-1">{{ notification.title }}</p>
                    <p class="text-body-2 mb-2" style="letter-spacing: 0.4px !important; line-height: 18px;">
                      {{ notification.subtitle }}
                    </p>
                    <p class="text-sm text-disabled mb-0" style="letter-spacing: 0.4px !important; line-height: 18px;">
                      {{ notification.time }}
                    </p>
                  </div>
                  <VSpacer />
                </div>
              </VListItem>
            </template>

            <VListItem v-show="!props.notifications.length" class="text-center text-medium-emphasis" style="block-size: 56px;">
              <VListItemTitle>{{ $t('No Notification Found') }}!</VListItemTitle>
            </VListItem>
          </VList>
        </PerfectScrollbar>

        <VDivider />
      </VCard>
    </VMenu>
  </IconBtn>
</template>

<style lang="scss">
.notification-unread {
  background-color: #f0f5ff;
  font-weight: 600;
}
</style>
