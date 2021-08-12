<template>
  <JetConfirmationModal :show="show" @close="close">
    <template #title>
      {{ action }} {{ model }}
    </template>

    <template #content>
      <slot v-bind="{ form, errors }">
        Are you sure you would like to {{ lowercase(action) }} this {{ model }}?
      </slot>
    </template>

    <template #footer>
      <JetSecondaryButton @click="close">
        Cancel
      </JetSecondaryButton>

      <component
        :is="buttonComponent"
        class="ml-2"
        :class="{ 'opacity-25': processing }"
        :disabled="processing"
        @click="confirm"
      >
        {{ action }}
      </component>
    </template>
  </JetConfirmationModal>
</template>

<script>
import JetDangerButton from '@/Components/DangerButton'
import JetConfirmationModal from '@/Components/ConfirmationModal'
import JetSecondaryButton from '@/Components/SecondaryButton'
import JetButton from '@/Components/Button'

export default {
  components: {
    JetConfirmationModal,
    JetSecondaryButton,
    JetDangerButton,
    JetButton,
  },

  props: {
    model: {
      type: String,
      required: true,
    },
    show: {
      type: Boolean,
      default: false,
    },
    endpoint: {
      type: String,
      required: true,
    },
    method: {
      type: String,
      default: 'delete',
    },
    action: {
      type: String,
      default: 'Deactivate',
    },
    buttonComponent: {
      type: String,
      default: 'jet-danger-button',
    },
    dontRefreshOnSuccess: {
      type: Boolean,
      default: false,
    },
  },

  emits: ['close', 'confirmed'],

  data () {
    return {
      form: {},
      processing: false,
      errors: {},
    }
  },

  methods: {
    close () {
      this.$emit('close')
    },

    lowercase (value) {
      return value.toLowerCase()
    },

    submit () {
      this.$refs.form.submit()
    },

    confirm () {
      this.processing = true
      this.$inertia.delete(this.endpoint, {
        onSuccess: () => {
          this.$emit('confirmed')
          this.close()
          this.$nextTick(() => {
            document.getElementsByTagName('body')[0].style.overflow = 'scroll'
          })
        },
        onFinish: () => {
          this.close()
        },
      })
    },
  },
}
</script>
