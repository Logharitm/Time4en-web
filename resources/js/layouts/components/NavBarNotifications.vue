<script setup>
import { ref, onMounted } from 'vue'

const notifications = ref([])

const loadNotifications = async () => {
  try {
    // استدعاء الـ API
    const response = await $api('/user-notifications', { method: "GET" })
    console.log(response) // هتلاقي مكان array notifications

    // الداتا الأساسية في data.data (array of notifications)
    if (Array.isArray(response.data)) {
      notifications.value = response.data.map(item => ({
        id: item.id,
        title: item.message,
        subtitle: item.message_en,
        time: item.created_at,
        isSeen: item.is_read,
      }))
    }else {
      console.warn('No notifications found in API response')
    }
  } catch (error) {
    console.error('Error loading notifications:', error)
  }
}

const removeNotification = notificationId => {
  notifications.value = notifications.value.filter(item => item.id !== notificationId)
}

const markRead = ids => {
  notifications.value.forEach(item => {
    if (ids.includes(item.id)) item.isSeen = true
  })
}

const markUnRead = ids => {
  notifications.value.forEach(item => {
    if (ids.includes(item.id)) item.isSeen = false
  })
}

const handleNotificationClick = notification => {
  if (!notification.isSeen) markRead([notification.id])
}

onMounted(() => {
  loadNotifications()
})
</script>

<template>
  <Notifications
    :notifications="notifications"
    @remove="removeNotification"
    @read="markRead"
    @unread="markUnRead"
    @click:notification="handleNotificationClick"
  />
</template>
