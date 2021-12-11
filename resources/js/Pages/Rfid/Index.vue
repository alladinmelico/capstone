<template>
  <breeze-authenticated-layout>
    <template #header>
      <div class="flex justify-between">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">RFIDs</h2>
        <div>
          <p class="text-sm text-gray-500">
            New RFIDs from Raspberry API are
            <strong>{{ allowNewRfids ? '' : 'Not' }} Allowed</strong>
          </p>
        </div>
      </div>
    </template>

    <div class="py-12">
      <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
        <SimpleDataTable
          title="RFID"
          resource="admin/rfid"
          model="Rfid"
          :items="items"
          :headers="headers"
          :column-keys="columnKeys"
          :has-create="false"
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
    allowNewRfids: {
      type: Boolean,
      default: true,
    },
  },
  data() {
    return {
      headers: [
        'ID',
        'RFID',
        'User',
        'Inside the campus',
        'Last Checked',
        'Actions',
      ],
      columnKeys: ['id', 'value', 'user', 'is_logged', 'updated_at'],
    }
  },
  mounted () {
    Echo.private('course').listen('CourseCreated', (e) => {
      this.items.push(e.course)
    })
  },
  methods: {
    fetchData() {
      Echo.private('course').listen('CourseCreated', (e) => {
        console.log(e)
        this.items.shift(e.course)
      })
    },
  },
}
</script>
