<template>
  <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
        <h3 class="text-3xl font-semibold text-center text-gray-900">
            {{ title }}
        </h3>
        <div class="flex justify-between items-center">
            <FormInput
                v-model="searchTerm"
                class="col-4"
                label="Search"
                required
            />
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
        <table-lite
            :is-loading="isLoading"
            :columns="columns"
            :rows="table.rows"
            :total="table.totalRecordCount"
            :sortable="table.sortable"
            @do-search="doSearch"
            @is-finished="tableLoadingFinish"
        />
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
import TableLite from "vue3-table-lite"
import FormInput from '@/Components/FormInput'
import ConfirmsDelete from '@/Mixins/ConfirmsDelete'
import DeleteConfirmationModal from '@/Components/DeleteConfirmationModal'
import Toast from '@/Components/Notifications/Toast'
import { EyeIcon, PencilIcon, TrashIcon, BackspaceIcon } from '@heroicons/vue/outline'
import { useForm } from '@inertiajs/inertia-vue3'

export default {
    components: {
        FormInput,
        TableLite
	},
    setup () {
        const form = useForm({})

        return { form }
    },

    mixins: [ConfirmsDelete],

    emits: ['openModal', 'removeItem'],
	props: {
		items: {
			type: Array,
			default: [],
		},
        columns: {
            type: Array,
            default: []
        },
        meta: {
            type: Object,
            default: {
                total: 0,
                from: 0,
                to: 0,
                current_page: 0
            }
        },
        isLoading: {
            type: Boolean,
            default: false
        },
        resource: {
            type: String,
            default: null,
        },
        model: {
            type: String,
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
        }
	},
    data () {
        return {
            notification: null,
            searchTerm: '',
			table: {
				totalRecordCount: this.meta.total,
                rows: [],
				columns: this.columns,
				messages: {
					pagingInfo: `Showing ${this.meta.from}-${this.meta.to} of ${this.meta.current_page}`,
					pageSizeChangeLabel: 'Row count:' ,
					gotoPageLabel: 'Go to page:',
					noDataAvailable: 'No data',
				},
			},
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
    mounted() {
        this.table.rows = this.formatRows(this.items)
	},
    methods: {
        doSearch(offset, limit, order, sort) {
            this.$emit('doSearch', {offset, limit, order, sort})
        },
        formatRows (items) {
            return items.filter(
                (x) =>
                    x.name.toLowerCase().includes(this.searchTerm.toLowerCase()) ||
                    x.code.toLowerCase().includes(this.searchTerm.toLowerCase())
                )
        },
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
		tableLoadingFinish(elements) {
			this.table.isLoading = false
			Array.prototype.forEach.call(elements, function (element) {
				if (element.classList.contains('name-btn')) {
					element.addEventListener('click', function () {
						// do your click event
						console.log(this.dataset.id + ' name-btn click!!')
					})
				}
				if (element.classList.contains('quick-btn')) {
					// do your click event
					element.addEventListener('click', function () {
						console.log(this.dataset.id + ' quick-btn click!!')
					})
				}
			})
		},
	},
    watch: {
        searchTerm () {
            this.table.rows = this.formatRows(this.items)
        },
        items () {
            this.table.rows = this.formatRows(this.items)
        }
    }
}
</script>

<style scoped>
.card ::v-deep(.table .thead-dark th) {
  color: #fff;
  background-color: #00838f !important;
  border-color: #00838F !important;
}
.card ::v-deep(.table td), .card ::v-deep(.table tr) {
  border-color: #00838F;
}
</style>
