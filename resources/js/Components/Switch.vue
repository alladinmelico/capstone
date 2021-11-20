<template>
  <label for="toggle" class="text-xl text-gray-700 mr-4"> {{ label }}</label>
  <div
    class="
      relative
      inline-block
      w-10
      mr-2
      align-middle
      select-none
      transition
      duration-200
      ease-in
    "
  >
    <input
      type="checkbox"
      name="toggle"
      id="toggle"
      class="
        toggle-checkbox
        absolute
        block
        w-6
        h-6
        rounded-full
        bg-white
        border-4
        appearance-none
        cursor-pointer
      "
      :value="value"
      v-model="proxyChecked"
      :disabled="disabled"
    />
    <label
      for="toggle"
      class="
        toggle-label
        block
        overflow-hidden
        h-6
        rounded-full
        bg-gray-300
        cursor-pointer
      "
    ></label>
  </div>
</template>

<script>
export default {
  emits: ['update:checked'],

  props: {
    label: {
      type: Object,
      required: false,
      default: 'Toggle',
    },
    checked: {
      type: [Array, Boolean],
      default: false,
    },
    value: {
      default: null,
    },
  },

  computed: {
    proxyChecked: {
      get() {
        return this.checked
      },

      set(val) {
        this.$emit('update:checked', val)
      },
    },
  },
}
</script>

<style>
.toggle-checkbox:checked {
  @apply: right-0 border-green-400;
  right: 0;
  border-color: #00838f;
}
.toggle-checkbox:checked + .toggle-label {
  @apply: bg-green-400;
  background-color: #00838f;
}
</style>
