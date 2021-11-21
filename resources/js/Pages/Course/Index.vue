<template>
	<breeze-authenticated-layout>
		<template #header>
			<h2 class="font-semibold text-xl text-gray-800 leading-tight">Courses</h2>
		</template>

		<div class="py-12">
			<div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
				<!-- <vuetable
					ref="vuetable"
					:fields="columnKeys"
					:api-mode="false"
					:data="items"
				></vuetable> -->
			</div>
		</div>
	</breeze-authenticated-layout>
</template>

<script>
import BreezeAuthenticatedLayout from '@/Layouts/Authenticated'
import SimpleTable from '@/Components/SimpleTable'

export default {
	components: {
		BreezeAuthenticatedLayout,
		SimpleTable,
	},
	props: {
		items: {
			type: Array,
			default: [],
		},
	},
	data() {
		return {
			headers: ['Course Name', 'Code', 'Department', 'Actions'],
			columnKeys: ['name', 'code', 'department'],
			table: {
				isLoading: false,
				isReSearch: false,
				totalRecordCount: 2,
				rows: this.items,
				sortable: {
					order: 'id',
					sort: 'asc',
				},
				columns: [
					{
						label: 'ID',
						field: 'id',
						width: '3%',
						sortable: true,
						isKey: true,
					},
					{
						label: 'Name',
						field: 'name',
						width: '27%',
						sortable: true,
					},
					{
						label: 'Code',
						field: 'code',
						width: '10%',
						sortable: true,
					},
					{
						label: 'Department',
						field: 'department',
						width: '20%',
						sortable: true,
					},
					{
						label: 'Actions',
						field: '',
						width: '20%',
						display: function (row) {
							return (
								'<button type="button" data-id="' +
								row.id +
								'" class="is-rows-el quick-btn">Button</button>'
							)
						},
					},
				],
				messages: {
					pagingInfo: 'Showing {0}-{1} of {2}',
					pageSizeChangeLabel: 'Row count:',
					gotoPageLabel: 'Go to page:',
					noDataAvailable: 'No data',
				},
			},
		}
	},
	mounted() {
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
		doSearch(offset, limit, order, sort, table) {
			table = this.table
			table.isLoading = true
			table.isReSearch = offset == undefined ? true : false
			console.log(offset, limit, order, sort)
			// do your search event to get newRows and new Total
			// this.table.rows = newRows
			// this.table.totalRecordCount = newTotal
			// this.table.sortable.order = order
			// this.table.sortable.sort = sort
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
		updateCheckedRows(rowsKey) {
			// do your checkbox click event
			console.log(rowsKey)
		},
	},
}
</script>

<style>
.thead-dark th {
	background-color: rebeccapurple !important;
}
</style>
