<template>
  <breeze-authenticated-layout>
    <template #header>
      <h2 class="font-semibold text-xl text-gray-800 leading-tight">
        Courses
      </h2>
    </template>

    <div class="py-12">
      <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
        <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
          <div class="p-6 bg-white border-b border-gray-200">
              <ul v-for="item in items" :key="item.id">
                  <li>{{item.name}}</li>
              </ul>
          </div>
        </div>
      </div>
    </div>
  </breeze-authenticated-layout>
</template>

<script>
import BreezeAuthenticatedLayout from '@/Layouts/Authenticated'

export default {
  components: {
    BreezeAuthenticatedLayout,
  },
  props: {
    items: {
        type: Array,
        default: []
    },
  },
}
</script>
