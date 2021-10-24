<template>
	<breeze-authenticated-layout>
		<template #header>
			<h2 class="font-semibold text-xl text-gray-800 leading-tight">
				Sections
			</h2>
		</template>

		<div class="py-12">
			<div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
				<SimpleTable
					title="Section"
					resource="section"
					model="Section"
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
			headers: [
				'ID',
				'Name',
				'President',
				'Faculty',
				'Actions',
			],
			columnKeys: [
				'id',
				'name',
				'president_id',
				'faculty_id',
			],
		}
	},
}
</script>
