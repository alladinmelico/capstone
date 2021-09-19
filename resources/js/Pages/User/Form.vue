<template>
    <breeze-authenticated-layout>
  <div class="py-10 mx-auto max-w-7xl sm:px-6 lg:px-8">
    <JetFormSection @submitted="save">
      <template #title> User </template>

      <template #description> Create/Update the user here. </template>

      <template #form>
        <FormInput v-model="form.name" class="col-span-6 sm:col-span-4" :error="form.errors.name" label="Name" required />
        <FormInput v-model="form.email" type="email" class="col-span-6 sm:col-span-4" :error="form.errors.email" label="Email" required />
      </template>

      <template #actions>
        <InertiaLink href="/admin/user" class="mr-2">
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
  },

  methods: {
    save () {
      const url = this.isEditing
        ? `/admin/user/${this.item.id}`
        : '/admin/user'
      this.form.post(url)
    },
  },
}
</script>
