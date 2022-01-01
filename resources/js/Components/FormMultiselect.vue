<template>
  <form-input v-bind="{ label, name, required, error }">
    <template #default="{ computedName }">
      <VueMultiselect
        :id="computedName"
        v-model="localValue"
        :label="computedName"
        :multiple="true"
        :options="['Monday', 'Tuesday', 'Wednesday', 'Thursday', 'Friday', 'Saturday', 'Sunday']"
        :searchable="false"
        :close-on-select="false"
        v-bind="{ required }"
      />
    </template>
  </form-input>
</template>

<script>
import FormInput from '@/Components/FormInput'
import FormInputMixin from '@/Mixins/FormInput'
import VueMultiselect from 'vue-multiselect'

export default {
  components: {
    FormInput,
    VueMultiselect,
  },

  mixins: [FormInputMixin]
}
</script>
