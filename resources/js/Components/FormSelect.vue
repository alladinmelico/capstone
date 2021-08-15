<template>
  <form-input v-bind="{ label, name, required, error }">
    <template #default="{ computedName }">
      <input-select :id="computedName" :label="computedName" v-model="localValue" v-bind="{ options, required, valueKey, labelKey }" />
    </template>
  </form-input>
</template>

<script>
import InputSelect from '@/Components/Select'
import HasOptions from '@/Mixins/HasOptions'
import HasLocalValue from '@/Mixins/HasLocalValue'
import FormInput from '@/Components/FormInput'
import FormInputMixin from '@/Mixins/FormInput'

export default {
  components: {
    InputSelect,
    FormInput,
  },

  mixins: [FormInputMixin, HasOptions, HasLocalValue],
}
</script>
