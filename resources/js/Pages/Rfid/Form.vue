<template>
    <breeze-authenticated-layout>
  <div class="py-10 mx-auto max-w-7xl sm:px-6 lg:px-8">
    <JetFormSection @submitted="save">
      <template #title> RFID </template>

      <template #description> Create/Update the RFID here. </template>

      <template #form>
        <FormInput v-model="form.value" class="col-span-6 sm:col-span-4" :error="form.errors.value" label="Value" required />
      </template>

      <template #actions>
        <InertiaLink href="/admin/rfid" class="mr-2">
          <JetSecondaryButton>
            Cancel
          </JetSecondaryButton>
        </InertiaLink>

        <JetButton :class="{'opacity-25': form.processing}" :disabled="form.processing">
          Save
        </JetButton>
      </template>
    </JetFormSection>
  </div>
  </breeze-authenticated-layout>
</template>

<script>
import BreezeAuthenticatedLayout from '@/Layouts/Authenticated'
import JetFormSection from '@/Components/FormSection'
import JetButton from '@/Components/Button'
import JetSecondaryButton from '@/Components/SecondaryButton'
import FormInput from '@/Components/FormInput'
import FormTextarea from '@/Components/FormTextarea'
import FormSelect from '@/Components/FormSelect'

export default {
  components: {
    JetFormSection,
    JetButton,
    JetSecondaryButton,
    FormInput,
    FormTextarea,
    BreezeAuthenticatedLayout,
    FormSelect,
  },

  props: {
    item: {
      type: Object,
      required: false,
    },
    departments: {
      type: Array,
      required: true,
    },
  },

  data () {
    const item = this.item
    return {
      form: this.$inertia.form({
        _method: item ? 'PUT' : 'POST',
        name: '',
        code: '',
        department_id: 1,
        ...item,
      }),
    }
  },

  computed: {
    isEditing () {
      return !! this.item?.id
    },
    departmentList () {
        return Object.entries(this.departments).map(obj => {
            return { id: obj[0], name: obj[1]}
        })
    }
  },

  methods: {
    save () {
      const url = this.isEditing
        ? `/admin/rfid/${this.item.id}`
        : '/admin/rfid'
      this.form.post(url)
    },
  },
}
</script>
