<template>
  <div v-if="error">
    <div v-if="Array.isArray(error)">
      <jet-input-error v-for="e in error" :key="e" :message="e" class="mt-2" />
    </div>
    <jet-input-error v-else :message="error" class="mt-2" />
  </div>
</template>

<script>
import JetInputError from '@/Components/InputError'

export default {
  components: {
    JetInputError,
  },

  props: {
    error: {
      type: [Array, String],
      default () {
        return null
      },
    },
  },
}
</script>
