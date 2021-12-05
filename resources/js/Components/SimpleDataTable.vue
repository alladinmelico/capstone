<template>
  <div class="max-w-full mx-auto mb-12">
    <div class="flex justify-between pb-5 max-w-7xl mx-auto sm:px-6 lg:px-8">
      <h3 class="text-3xl font-semibold text-center text-gray-900">
        {{ title }}
      </h3>
      <div v-if="createModal">
        <button
          type="button"
          class="block px-3 py-2 text-sm leading-4 text-white transition bg-secondary border rounded-md hover:bg-secondary-dark focus:outline-none focus:ring-none"
          @click="$emit('openModal', $event.target.value)">
          Create New
        </button>
      </div>
      <InertiaLink v-else :href="`/${resource}/create`">
        <button type="button" class="block px-3 py-2 text-sm leading-4 text-white transition bg-secondary border rounded-md hover:bg-secondary-dark focus:outline-none focus:ring-none">
          Create New
        </button>
      </InertiaLink>
    </div>
    <div class="flex pb-5 max-w-7xl mx-auto sm:px-6 lg:px-8">
        <breeze-dropdown align="right" width="48">
            <template #trigger>
                <span class="inline-flex rounded-md border border-primary">
                    <button type="button" class="inline-flex items-center px-3 py-2 border border-transparent text-sm leading-4 font-medium rounded-md text-gray-500 bg-white hover:text-gray-700 focus:outline-none transition ease-in-out duration-150">
                        <div class="flex items-center">
                            Filter
                            <svg class="ml-1 -mr-0.5 h-4 w-4" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor">
                                <path fill-rule="evenodd" d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z" clip-rule="evenodd" />
                            </svg>
                        </div>
                    </button>
                </span>
            </template>

            <template #content>
                <breeze-dropdown-link href="#">
                    With Trashed
                </breeze-dropdown-link>
                <breeze-dropdown-link href="#">
                    Only Trashed
                </breeze-dropdown-link>
            </template>
        </breeze-dropdown>
        <div
             class="
                flex
                ml-4
                items-center
                border
                border-primary
                rounded-md
                shadow-sm
            "
        >
            <SearchIcon class="h-5 w-5 text-gray-400 mx-2"/>
            <input class="focus:outline-none" :value="searchTerm" />
        </div>
    </div>
    <div v-if="items.data.length > 0" class="w-full mx-auto overflow-hidden bg-white shadow sm:rounded-lg">
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
                    <tr v-for="(item, index) in items.data" :key="index" class="">
                      <td
                        v-for="columnKey in columnKeys"
                        :key="columnKey"
                        class="px-6 py-4 text-sm font-medium text-center text-gray-900 whitespace-nowrap truncate "
                      >
                        <img v-if="columnKey.toLowerCase() === 'avatar'" :src="item[columnKey]" :alt="item[columnKey]" class="max-w-xs h-auto rounded-lg">
                        <p v-else>
                          {{ item[columnKey] }}
                        </p>
                      </td>
                      <td v-if="approvalAction" class="mx-auto px-6 py-4 text-sm text-gray-500">
                        <a class="text-green-400 underline cursor-pointer mx-auto" @click="approve(item)" title="Aprove">
                          Approve
                        </a>
                      </td>
                      <td v-else-if="resource && !item.deleted_at" class="flex justify-center px-6 py-4 text-sm text-gray-500">
                        <InertiaLink :href="`/${resource.split('/').pop()}/${item.id}`" class="mr-4 text-green-600 underline">
                          <EyeIcon class="h-5 w-5 text-green-400"/>
                        </InertiaLink>
                        <InertiaLink :href="`/${resource}/${item.id}/edit`" class="mr-4 text-blue-600 underline">
                          <PencilIcon class="h-5 w-5 text-blue-400"/>
                        </InertiaLink>
                        <a class="text-red-600 underline cursor-pointer" @click="confirmDelete(item)">
                          <TrashIcon class="h-5 w-5 text-red-400"/>
                        </a>
                      </td>
                      <td v-else-if="item.deleted_at" class="flex justify-end px-6 py-4 text-sm text-gray-500">
                        <a class="text-red-600 underline cursor-pointer" @click="restore(item)" title="Restore">
                          <BackspaceIcon class="h-5 w-5 text-green-400"/>
                        </a>
                      </td>
                      <td v-else-if="removeOnly" class="flex justify-end px-6 py-4 text-sm text-gray-500">
                        <a class="text-red-600 underline cursor-pointer" @click="$emit('removeItem', item.id)">
                          <TrashIcon class="h-5 w-5 text-red-400"/>
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

    <Pagination class="mt-6" :links="items.links" />

    <DeleteConfirmationModal
      v-if="deleteEndpoint"
      :model="model"
      :show="!! deleting"
      :endpoint="deleteEndpoint"
      @close="deleting = null"
    />
    <Toast :notification="notification" />
  </div>
</template>

<script>
import ConfirmsDelete from '@/Mixins/ConfirmsDelete'
import DeleteConfirmationModal from '@/Components/DeleteConfirmationModal'
import Toast from '@/Components/Notifications/Toast'
import FormInput from '@/Components/FormInput'
import Pagination from '@/Components/Pagination'
import BreezeDropdown from '@/Components/Dropdown'
import BreezeDropdownLink from '@/Components/DropdownLink'
import { EyeIcon, PencilIcon, TrashIcon, BackspaceIcon, SearchIcon } from '@heroicons/vue/outline'
import { useForm } from '@inertiajs/inertia-vue3'
import throttle from 'lodash/throttle'

export default {
  components: {
    DeleteConfirmationModal,
    Toast,
    FormInput,
    Pagination,
    BreezeDropdown,
    BreezeDropdownLink,
    EyeIcon,
    PencilIcon,
    TrashIcon,
    BackspaceIcon,
    SearchIcon
  },

  setup () {
    const form = useForm({})

    return { form }
  },

  mixins: [ConfirmsDelete],

  emits: ['openModal', 'removeItem'],

  props: {
    items: {
      type: Object,
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
    approvalAction: {
        type: Boolean,
        default: false
    },
    createModal: {
        type: Boolean,
        default: false
    },
    removeOnly: {
        type: Boolean,
        default: false
    },
    indexRoute: {
        type: String,
        default: ''
    },
  },

  data() {
    return {
        searchTerm: '',
        isTableLoading: false,
        perPage: 10,
        notification: null,
    }
  },

  computed: {
    deleteEndpoint () {
      if (! this.deleting) {
        return ''
      }

      return `/${this.resource}/${this.deleting.id}`
    },
  },

  methods: {
    approve (item) {
        this.form.post(`/admin/user-approve/${item.id}`, {
            preserveScroll: true,
            onSuccess: () => {
                this.notification = {
                    type: 'success',
                    message: `${item.name}'s profile changes approved.`
                }
                setTimeout(() => {
                    this.notification = null
                }, 3000)
            }
        })
    },
    reset() {
      this.form = mapValues(this.form, () => null)
    },
  },
  watch: {
    searchTerm (value) {
        this.$inertia.get(this.indexRoute, {search: value.toLowerCase()}, { preserveState: true })
    },
  }
}
</script>
