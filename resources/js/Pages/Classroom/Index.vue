<template>
	<breeze-authenticated-layout>
		<template #header>
			<h2 class="font-semibold text-xl text-gray-800 leading-tight">
				Schedules
			</h2>
		</template>

		<div class="py-12">
			<div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
				<SimpleTable
					title="Schedule"
					resource="schedule"
					model="Course"
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
				'User',
				'Start Time',
				'End Time',
				'Day',
				'Validity Date',
				'Note',
				'Facility',
				'Actions',
			],
			columnKeys: [
				'user_id',
				'start_at',
				'end_at',
				'day',
				'valid_until',
				'note',
				'facility_id',
			],
		}
	},
}
</script>
