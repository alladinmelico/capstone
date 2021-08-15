<template>
  <label class="block text-sm font-medium text-gray-700">
    <span v-if="value">
      {{ value }}
      <span v-if="required" class="text-red-700">*</span>
    </span>
    <span v-else><slot /></span>
  </label>
</template>

<script>

export default {
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
