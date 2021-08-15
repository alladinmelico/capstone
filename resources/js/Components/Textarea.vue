<template>
  <textarea
    ref="input"
    class="h-auto border-gray-300 rounded-md shadow-sm input-textarea focus:border-primary focus:ring focus:ring-primary-light focus:ring-opacity-50"
    :value="value"
    @input="input"
  />
</template>

<script>
export default {
  props: {
    value: {
      type: String,
      required: true,
    },
  },

  methods: {
    focus () {
      this.$refs.input.focus()
    },

    input (event) {
      this.$emit('input', event.target.value)

      this.$nextTick(() => {
        const element = event.target
        element.style.height = '5px'
        element.style.height = (element.scrollHeight + 10) + 'px'
      })
    },
  },
}
</script>

<style scoped>
    .input-textarea {
        min-height: 50px;
        max-height: 600px;
    }
</style>
