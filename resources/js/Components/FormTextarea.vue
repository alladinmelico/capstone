<template>
  <form-input v-bind="{ name, label, required, error }">
    <template #default="{ computedName }">
      <textarea :id="computedName" v-model="localValue" :name="computedName" rows="5" class="block w-full mt-1 border-gray-300 rounded-md shadow-sm focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50" :required="required" />
    </template>
  </form-input>
</template>

<script>
import FormInput from '@/Mixins/FormInput'

export default {
  mixins: [FormInput],
}
</script>
