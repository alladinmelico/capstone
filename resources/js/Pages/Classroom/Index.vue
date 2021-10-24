<template>
	<breeze-authenticated-layout>
		<template #header>
			<h2 class="font-semibold text-xl text-gray-800 leading-tight">
				Classrooms
			</h2>
		</template>

		<div class="py-12">
			<div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
				<SimpleTable
					title="Classroom"
					resource="classroom"
					model="Classroom"
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
				'Name',
				'Description Heading',
				'Description',
				'Section',
				'Invite Code',
				'Subject',
				'Actions',
			],
			columnKeys: [
				'name',
				'description_heading',
				'description',
				'section',
				'invite_code',
				'subject.name',
			],
		}
	},
}
</script>
