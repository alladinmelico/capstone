<template>
  <breeze-authenticated-layout>
    <template #header>
      <h2 class="font-semibold text-xl text-gray-800 leading-tight">
        Schedules
      </h2>
    </template>

    <div class="py-12">
      <div class="w-full mx-auto sm:px-6 lg:px-8">
        <SimpleDataTable
          title="Schedule"
          resource="schedule"
          model="Schedule"
          index-route="/schedule"
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
        'User',
        'Start Time',
        'End Time',
        'Day',
        'Validity Date',
        'Note',
        'Facility',
        'Actions',
      ],
      columnKeys: [
        'user.name',
        'start_at',
        'end_at',
        'day',
        'valid_until',
        'note',
        'facility.code',
      ],
    }
  },

  mounted () {
    console.log(this.items)
  },
}
</script>
