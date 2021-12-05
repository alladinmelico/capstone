<template>
  <breeze-authenticated-layout>
    <template #header>
      <h2 class="font-semibold text-xl text-gray-800 leading-tight">Courses</h2>
    </template>

    <div class="py-12">
      <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
        <SimpleDataTable
          title="Courses"
          resource="admin/course"
          indexRoute="/course"
          model="Company"
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
      default: [],
    },
  },
  data() {
    return {
      headers: ['Course Name', 'Code', 'Department', 'Actions'],
      columnKeys: ['name', 'code', 'department'],
    }
  },
  mounted() {
    console.log(this.items)
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
  }
}
</script>
