<template>
  <div>
    <custom-label :for="computedName" :value="computedLabel" :required="required" />
    <slot name="default" :computed-name="computedName">
      <jet-input
        :id="computedName"
        v-model="localValue"
        :type="type"
        class="block w-full mt-1"
        :autocomplete="computedName"
        :required="required"
        :autofocus="autofocus"
        :min="min"
        :max="max"
      />
    </slot>
    <input-error :error="computedError" />
    <p v-if="helper" class="mt-1 text-xs italic text-gray-400">{{ helper }}</p>
  </div>
</template>

<script>
import JetInput from '@/Components/Input'
import InputError from '@/Components/Error'
import CustomLabel from '@/Components/Label'
import FormInput from '@/Mixins/FormInput'

export default {
  components: { JetInput, CustomLabel, InputError },

  mixins: [FormInput],

  props: {
    type: {
      type: String,
      default: 'text',
    },
    min: {
        type: [String, Number],
        default: ''
    },
    max: {
        type: [String, Number],
        default: ''
    },
    autofocus: {
      type: Boolean,
      default: false,
    },
  },
}
</script>
