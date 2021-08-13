export default {
  props: {
    options: {
      type: Array,
      required: true,
    },
    valueKey: {
      type: String,
      default: 'id',
    },
    labelKey: {
      type: String,
      default: 'name',
    },
  },
}
