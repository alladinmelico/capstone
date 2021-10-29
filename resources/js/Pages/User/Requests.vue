<template>
	<breeze-authenticated-layout>
		<template #header>
			<h2 class="font-semibold text-xl text-gray-800 leading-tight">
				User Request for Profile changes
			</h2>
		</template>

		<div class="py-12">
			<div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
				<SimpleTable
					title="User Requests"
					resource="admin/user"
					model="User"
					:items="items"
					:headers="headers"
					:column-keys="columnKeys"
                    approval-action
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
			headers: ['ID', 'Name', 'Email','Year','Section', 'Actions'],
			columnKeys: ['school_id', 'name', 'email', 'year', 'section'],
		}
	},
    mounted() {
        //  Echo.private('user')
        //     .listen('UserCreated', e => {
        //         this.items.push(e.course)
        //     })
    },
}
</script>
