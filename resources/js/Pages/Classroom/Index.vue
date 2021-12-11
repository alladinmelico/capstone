<template>
  <breeze-authenticated-layout>
    <template #header>
      <h2 class="font-semibold text-xl text-gray-800 leading-tight">
        Classrooms
      </h2>
    </template>

    <div class="py-12">
      <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
        <SimpleDataTable
          title="Classroom"
          resource="classroom"
          model="Classroom"
          index-route="/classroom"
          :items="items"
          :headers="headers"
          :column-keys="columnKeys"
        />
      </div>
    </div>
  </breeze-authenticated-layout>
</template>

<script>
import BreezeAuthenticatedLayout from '@/Layouts/Authenticated'
import SimpleDataTable from '@/Components/SimpleDataTable'

export default {
  components: {
    BreezeAuthenticatedLayout,
    SimpleDataTable,
  },
  props: {
    items: {
      type: Object,
      default () {
        return {}
      },
    },
  },
  data () {
    return {
      headers: [
        'Name',
        'Description Heading',
        'Description',
        'Section',
        'Invite Code',
        'Subject',
        'Actions',
      ],
      columnKeys: [
        'name',
        'description_heading',
        'description',
        'section',
        'invite_code',
        'subject.name',
      ],
    }
  },
}
</script>
