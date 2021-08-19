<template>
	<breeze-authenticated-layout>
		<template #header>
			<h2 class="font-semibold text-xl text-gray-800 leading-tight">
				Dashboard
			</h2>
		</template>

		<div class="py-12">
			<div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
				<div class="grid grid-cols-3 gap-4">
					<div class="p-2 rounded-lg bg-blue-50 shadow-lg">
						<h2 class="text-2xl">Total Number of Schedules</h2>
                        <p class="text-5xl mt-4">{{ number_of_schedules }}</p>
					</div>
					<div class="p-2 rounded-lg bg-blue-50 shadow-lg">
						<h2 class="text-2xl">Total Number of Schedules Users</h2>
                        <p class="text-5xl mt-4">{{ number_of_users }}</p>
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
		token: '',
		number_of_schedules: 0,
		number_of_users: 0,
	},
}
</script>
