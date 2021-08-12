export default {
  data () {
    return {
      deleting: null,
    }
  },

  methods: {
    confirmDelete (row) {
      this.deleting = row
    },
  },
}
