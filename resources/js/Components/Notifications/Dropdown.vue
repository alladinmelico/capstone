<template>
  <jet-dropdown align="right" width="96">
    <template #trigger>
      <span class="inline-flex rounded-md">
        <button type="button" class="inline-flex items-center px-3 py-2 text-sm font-medium leading-4 text-gray-500 transition duration-150 ease-in-out bg-white border border-transparent rounded-md hover:text-gray-700 focus:outline-none">
          <div class="relative w-5 h-5">
                <BellIcon class="h-5 w-5 text-secondary absolute top-0 left-0"/>
                <span class="h-2 w-2 rounded-full bg-red-500 absolute top-0 right-0"/>
          </div>

          <svg class="ml-2 -mr-0.5 h-4 w-4" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor">
            <path fill-rule="evenodd" d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z" clip-rule="evenodd" />
          </svg>
        </button>
      </span>
    </template>

    <template #content>
      <jet-dropdown-link v-for="(n, index) in notifications" :key="n.id" :href="`/notifications/${n.id}`" class="flex items-center justify-between" :class="{ 'bg-blue-50': ! n.read_at }">
        {{ n.data.message }}
        <button type="button" class="px-2 py-1" @click.stop.prevent="remove(index)">
          <XIcon class="h-3 w-3 text-secondary-light"/>
        </button>
      </jet-dropdown-link>

      <div v-if="notifications.length == 0" class="block px-4 py-2 text-sm text-gray-700">
        You currently have no notifications.
      </div>

      <notification-toast :notification="toastingNotification" />
    </template>
  </jet-dropdown>
</template>

<script>
import axios from 'axios'
import dayjs from 'dayjs'
import JetDropdown from '@/Components/Dropdown'
import JetDropdownLink from '@/Components/DropdownLink'
import NotificationToast from '@/Components/Notifications/Toast'
import { BellIcon, XIcon } from '@heroicons/vue/solid'

export default {
  components: {
    JetDropdown,
    JetDropdownLink,
    NotificationToast,
    BellIcon,
    XIcon
  },

  data () {
    return {
    //   notifications: window.NOTIFICATIONS ? window.NOTIFICATIONS : [],
        notifications: [
            {
                id: 1,
                read_at: '',
                data: {
                    message: 'foobar lorem fdsaoifsdaofndasofjaofjdsaofidsfjaosdifhjasdiofhawsdiofhasdiof'
                }
            },
            {
                id: 1,
                read_at: '',
                data: {
                    message: 'foobar'
                }
            }
        ],
      toastingNotification: null,
    }
  },

  mounted () {
    window.Echo.private(`App.Models.User.${this.$page.props.auth.user.id}`)
      .notification(notification => {
        if (notification.type === 'NotificationRead') {
          const readNotification = this.notifications.find(n => n.id === notification.read_id)
          readNotification.read_at = dayjs().toISOString()
        } else if (notification.type === 'NotificationDeleted') {
          const deletedNotificationIndex = this.notifications.findIndex(n => n.id === notification.deleted_id)
          if (deletedNotificationIndex >= 0) {
            this.notifications.splice(deletedNotificationIndex, 1)
          }
        } else {
          this.toast(notification)
          this.notifications.unshift({
            id: notification.id,
            read_at: null,
            data: { message: notification.message },
          })

            console.log(' added' , notification)
        }
      })
  },

  methods: {
    toast (notification) {
      this.toastingNotification = notification

      setTimeout(() => {
        this.toastingNotification = null
      }, 3000)
    },

    remove (index) {
      const notificationToBeRemoved = this.notifications[index]
      this.notifications.splice(index, 1)
      axios.delete(`/notifications/${notificationToBeRemoved.id}`)
    },
  },
}
</script>
