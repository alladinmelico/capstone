<template>
  <Datepicker
    :value="modelValue"
    class="
        w-full
        border-gray-300
        focus:border-primary
        focus:ring
        focus:ring-primary-light
        focus:ring-opacity-50
        rounded-md
        shadow-sm
	"
    :lower-limit="new Date()"
    input-format="MM/dd/yyyy"
    :range="range"
    @input="$emit('update:modelValue', $event.target.value)"
  />
</template>

<script>
import Datepicker from 'vue3-datepicker'

export default {
  components: {
    Datepicker,
  },

  props: {
    modelValue: {
      type: [String, Date, Array],
      default: '',
    },
    range: {
      type: Boolean,
      default: false,
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
