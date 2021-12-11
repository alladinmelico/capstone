<template>
  <breeze-authenticated-layout>
    <template #header>
      <h2 class="font-semibold text-xl text-gray-800 leading-tight">Users</h2>
    </template>

    <div class="py-12">
      <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
        <SimpleDataTable
          title="Users"
          resource="admin/user"
          model="User"
          index-route="/admin/user"
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
      headers: ['ID', 'Name', 'Email', 'Year', 'Section', 'Actions'],
      columnKeys: ['school_id', 'name', 'email', 'year', 'section'],
    }
  },
  mounted () {
    //  Echo.private('user')
    //     .listen('UserCreated', e => {
    //         this.items.push(e.course)
    //     })
  },
}
</script>
