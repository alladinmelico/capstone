<template>
    <breeze-authenticated-layout>
  <div class="py-10 mx-auto max-w-7xl sm:px-6 lg:px-8">
    <JetFormSection @submitted="save">
      <template #title> Course </template>

      <template #description> Update the course here. </template>

      <template #form>
        <FormInput v-model="form.name" class="col-span-6 sm:col-span-4" :error="form.errors.name" label="Course" required />
        <FormInput v-model="form.code" class="col-span-6 sm:col-span-4" :error="form.errors.code" label="Code" required />
      </template>

      <template #actions>
        <InertiaLink href="/course" class="mr-2">
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

export default {
  components: {
    JetFormSection,
    JetButton,
    JetSecondaryButton,
    FormInput,
    FormTextarea,
    BreezeAuthenticatedLayout,
  },

  props: {
    item: {
      type: Object,
      required: false,
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
        ? `/admin/course/${this.item.id}`
        : '/admin/course'
      this.form.post(url)
    },
  },
}
</script>
