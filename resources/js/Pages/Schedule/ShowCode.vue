<template>
  <breeze-authenticated-layout>
    <template #header>
      <h2 class="font-semibold text-xl text-gray-800 leading-tight">
        <InertiaLink href="/schedule" class="underline">Schedule</InertiaLink>
        <chevron-double-right-icon
          class="inline-block h-5 w-5 text-primary mx-2"
        />
        Schedule QR Code
      </h2>
    </template>
    <div class="py-10 mx-auto max-w-7xl sm:px-6 lg:px-8 shadow-none">
      <steps :active-step="4" />
      <div class="col-span-6 flex flex-col justify-center items-center">
        <div class="flex items-end content-end">
          <div class="rounded-lg border-2 border-secondary p-8">
            <img
              :src="qrCodeUrl"
              alt="qr-code"
              class="min-w-full min-h-full cursor-pointer"
              @click="downloadImage"
            />
          </div>
          <button class="p-4 bg-secondary text-white rounded-lg ml-4" @click="downloadImage">
            <download-icon class="h-5 w-5" />
          </button>
        </div>
        <div
          class="
            w-full
            flex
            items-center
            justify-end
            px-4
            py-3
            text-right
            sm:px-6 sm:rounded-bl-md sm:rounded-br-md
          "
        >
          <InertiaLink href="/schedule" class="mr-2">
            <JetButton class="capitalize text-lg"> Done </JetButton>
          </InertiaLink>
        </div>
      </div>
    </div>
    <notification-toast :notification="toastingNotification" />
  </breeze-authenticated-layout>
</template>

<script>
import BreezeAuthenticatedLayout from '@/Layouts/Authenticated'
import Steps from './Components/Steps.vue'
import JetButton from '@/Components/Button'
import NotificationToast from '@/Components/Notifications/Toast'
import { ChevronDoubleRightIcon, DownloadIcon } from '@heroicons/vue/solid'

export default {
  components: {
    BreezeAuthenticatedLayout,
    Steps,
    JetButton,
    NotificationToast,
    ChevronDoubleRightIcon,
    DownloadIcon,
  },
  props: {
    url: {
      type: String,
      default: '',
    },
  },

  data() {
    return {
      qrCodeUrl:
        'https://api.qrserver.com/v1/create-qr-code/?size=300x300&color=00838f&data=' +
        this.url,
      toastingNotification: null,
    }
  },

  methods: {
    async downloadImage() {
      const image = await fetch(this.qrCodeUrl)
      const imageBlog = await image.blob()
      const imageURL = URL.createObjectURL(imageBlog)

      const link = document.createElement('a')
      link.href = imageURL
      link.download = 'image file name here'
      document.body.appendChild(link)
      link.click()
      document.body.removeChild(link)
      this.toastingNotification = {
        type: 'success',
        message: 'QR Code downloaded',
      }
      setTimeout(() => {
        this.toastingNotification = null
      }, 3000)
    },
  },
}
</script>

<style></style>
