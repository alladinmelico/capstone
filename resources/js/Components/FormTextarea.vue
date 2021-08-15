<template>
  <form-input v-bind="{ name, label, required, error }">
    <template #default="{ computedName }">
      <textarea :id="computedName" v-model="localValue" :name="computedName" rows="5" class="block w-full mt-1 border-gray-300 rounded-md shadow-sm focus:border-primary focus:ring focus:ring-primary-light focus:ring-opacity-50" :required="required" />
    </template>
  </form-input>
</template>

<script>
import FormInput from '@/Components/FormInput'
import FormInputMixin from '@/Mixins/FormInput'

export default {
    components: {
        FormInput
    },
    mixins: [FormInputMixin],
}
</script>
