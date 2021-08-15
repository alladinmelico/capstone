<template>
  <div class="w-full p-4 border border-red-700 rounded-md bg-red-50">
    <div class="flex">
      <div class="flex-shrink-0">
        <InformationCircleIcon class="w-5 h-5 text-red-600" aria-hidden="true" />
      </div>
      <div class="flex-1 ml-3 md:flex md:justify-between">
        <p class="text-sm text-red-800">
          {{ message }}
        </p>
      </div>
    </div>
  </div>
</template>

<script>
import { InformationCircleIcon } from '@heroicons/vue/solid'

export default {
  components: {
    InformationCircleIcon,
  },

  props: {
    message: {
      type: String,
      required: true,
    },
  },
}
</script>
