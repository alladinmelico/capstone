<template>
  <div class="max-w-5xl mx-auto mb-12">
    <div class="flex justify-between pb-10">
      <h3 class="text-3xl font-semibold text-center text-gray-900">
        {{ title }}
      </h3>
      <InertiaLink :href="`/${resource}/create`">
        <button type="button" class="block px-3 py-2 text-sm leading-4 text-white transition bg-blue-800 border rounded-md hover:bg-blue-900 focus:outline-none focus:ring-none">
          Create New
        </button>
      </InertiaLink>
    </div>
    <div v-if="items.length > 0" class="mx-auto overflow-hidden bg-white shadow sm:rounded-lg">
      <div class="px-4 py-5 border-t border-gray-200 sm:p-0">
        <div class="flex flex-col">
          <div class="-my-2 overflow-x-auto sm:-mx-6 lg:-mx-8">
            <div class="inline-block min-w-full py-2 align-middle sm:px-6 lg:px-8">
              <div class="overflow-hidden sm:rounded-lg">
                <table class="min-w-full">
                  <thead class="bg-gray-50">
                    <tr>
                      <th
                        v-for="(header, index) in headers"
                        :key="index"
                        scope="col"
                        class="px-6 py-3 text-xs font-medium tracking-wider text-center text-gray-500 uppercase"
                        v-text="header"
                      />
                    </tr>
                  </thead>
                  <tbody class="divide-y divide-gray-200">
                    <tr v-for="(item, index) in items" :key="index" class="">
                      <td
                        v-for="columnKey in columnKeys"
                        :key="columnKey"
                        class="px-6 py-4 text-sm font-medium text-center text-gray-900 whitespace-nowrap"
                        v-text="item[columnKey]"
                      />
                      <td v-if="resource" class="flex justify-center px-6 py-4 text-sm text-gray-500">
                        <InertiaLink :href="`/${resource}/${item.id}/edit`" class="mr-4 text-blue-600 underline">
                          Edit
                        </InertiaLink>
                        <a class="text-red-600 underline cursor-pointer" @click="confirmDelete(item)">
                          Deactivate
                        </a>
                      </td>
                    </tr>
                  </tbody>
                </table>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
    <p v-else class="text-center">
      There are no items yet in this table. Click "Create New" to create a new item.
    </p>

    <DeleteConfirmationModal
      v-if="deleteEndpoint"
      :model="model"
      :show="!! deleting"
      :endpoint="deleteEndpoint"
      @close="deleting = null"
    />
  </div>
</template>

<script>
import ConfirmsDelete from '@/Mixins/ConfirmsDelete'
import DeleteConfirmationModal from '@/Components/DeleteConfirmationModal'

export default {
  components: {
    DeleteConfirmationModal,
  },

  mixins: [ConfirmsDelete],

  props: {
    items: {
      type: Array,
      default: null,
    },
    headers: {
      type: Array,
      default: null,
    },
    resource: {
      type: String,
      default: null,
    },
    model: {
      type: String,
      default: null,
    },
    columnKeys: {
      type: Array,
      default: null,
    },
    title: {
      type: String,
      default: '',
    },
  },

  computed: {
    deleteEndpoint () {
      if (! this.deleting) {
        return ''
      }

      return `/${this.resource}/${this.deleting.id}`
    },
  },
}
</script>
