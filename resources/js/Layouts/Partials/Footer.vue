<template>
  <footer class="bg-secondary-dark text-gray-50 p-8 w-full mb-0">
      <div class="max-w-5xl mx-auto  flex justify-between">
        <div>
            <p class="font-italic text-sm">© 2021 Safe and Smart Campus:<br> Scheduling and Monitoring System </p>
        </div>
        <div>
            <p class="text-lg font-bold mb-2">Contact Us</p>
            <div>
                <MailIcon class="h6 w-6 text-primary-light"/>
            </div>
        </div>
      </div>
  </footer>
</template>

<script>
import { MailIcon } from '@heroicons/vue/outline'
export default {
    components: {
        MailIcon
    }
}
</script>
