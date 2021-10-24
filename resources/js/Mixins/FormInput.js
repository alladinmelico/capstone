import HasLocalValue from './HasLocalValue'
import _ from 'lodash'

export default {
  mixins: [HasLocalValue],

  props: {
    label: {
      type: String,
      default: '',
    },
    name: {
      type: String,
      default: '',
    },
    required: {
      type: Boolean,
      default: false,
    },
    disabled: {
      type: Boolean,
      default: false,
    },
    error: {
      type: [String, Array],
      default: '',
    },
    helper: {
      type: String,
      default: '',
    },
    form: {
      type: Object,
      default () {
        return null
      },
    },
  },

  created () {
    // instantiate localValue
    if (this.form && this.modelValue == null) {
      this.localValue = this.form[this.computedName]
    }
  },

  computed: {
    computedLabel () {
      return this.label || _.startCase(this.name) || ''
    },

    computedName () {
      return this.name || _.snakeCase(this.label) || 'form-input-' + Math.floor(Math.random() * 1000)
    },

    computedError () {
      if (this.error) {
        return this.error
      }

      return this.form ? this.form.errors[this.computedName] : ''
    },
  },

  watch: {
    localValue (newValue) {
      const computedName = this.computedName
      if (this.form) {
        this.form.clearErrors(computedName)

        if (this.modelValue == null) {
          this.form[computedName] = newValue
        }
      }
    },
  },
}
