<template>
  <form-input v-bind="{ label, name, required, error }">
    <template #default="{ computedName }">
      <input-datepicker
        :id="computedName"
        v-model="localValue"
        :label="computedName"
        :range="range"
        v-bind="{ required }"
      />
    </template>
  </form-input>
</template>

<script>
import InputDatepicker from '@/Components/Datepicker'
import FormInput from '@/Components/FormInput'
import FormInputMixin from '@/Mixins/FormInput'

export default {
  components: {
    InputDatepicker,
    FormInput,
  },

  mixins: [FormInputMixin],

  props: {
    range: {
      type: Boolean,
      default: false,
    },
  },
}
</script>
