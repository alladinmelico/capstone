<template>
  <label class="block text-sm font-medium text-gray-700">
    <span v-if="value">
      {{ value }}
      <required-mark v-if="required" />
    </span>
    <span v-else><slot /></span>
  </label>
</template>

<script>
import RequiredMark from '~/Components/Inputs/RequiredMark.vue'

export default {
  components: {
    RequiredMark,
  },

  props: {
    value: {
      type: String,
      default: '',
    },

    required: {
      type: Boolean,
      default: false,
    },
  },
}
</script>
