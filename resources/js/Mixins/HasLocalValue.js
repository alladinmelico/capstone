export default {
  emits: ['update:modelValue'],

  props: {
    modelValue: {
      type: [Number, String, Array, Boolean],
      default: null,
    },
  },

  data () {
    return {
      localValue: this.modelValue,
    }
  },

  watch: {
    localValue (newValue) {
      this.$emit('update:modelValue', newValue)
    },

    modelValue (newValue) {
      this.localValue = newValue
    },
  },
}
