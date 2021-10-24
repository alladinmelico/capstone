<template>
  <select
    class="w-full border-gray-300 rounded-md shadow-sm focus:border-primary focus:ring focus:ring-primary-light focus:ring-opacity-50"
    :value="modelValue"
    @input="$emit('update:modelValue', $event.target.value)"
  >
    <option :value="null" disabled>-- Select --</option>
    <option v-for="o in options" :key="o[valueKey]" :value="o[valueKey]">{{ o[labelKey] }}</option>
  </select>
</template>

<script>
import HasOptions from '@/Mixins/HasOptions'

export default {
  mixins: [HasOptions],

  props: {
    modelValue: {
      type: [String, Number],
      default: '',
    },
  },

  emits: ['update:modelValue'],

  methods: {
    focus () {
      this.$refs.input.focus()
    },
  },
}
</script>
