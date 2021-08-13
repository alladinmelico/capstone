<template>
  <div>
    <magis-label :for="computedName" :value="computedLabel" :required="required" />
    <slot name="default" :computed-name="computedName">
      <jet-input
        :id="computedName"
        v-model="localValue"
        :type="type"
        class="block w-full mt-1"
        :autocomplete="computedName"
        :required="required"
        :autofocus="autofocus"
      />
    </slot>
    <input-error :error="computedError" />
    <p v-if="helper" class="mt-1 text-xs italic text-gray-400">{{ helper }}</p>
  </div>
</template>

<script>
import JetInput from '@/Components/Input'
import InputError from '@/Components/Error'
import MagisLabel from '@/Components/Label'
import FormInput from '@/Mixins/FormInput'

export default {
  components: { JetInput, MagisLabel, InputError },

  mixins: [FormInput],

  props: {
    type: {
      type: String,
      default: 'text',
    },
    autofocus: {
      type: Boolean,
      default: false,
    },
  },
}
</script>
