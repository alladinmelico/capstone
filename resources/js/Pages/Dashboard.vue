<template>
	<breeze-authenticated-layout>
		<template #header>
			<h2 class="font-semibold text-xl text-gray-800 leading-tight">
				Dashboard
			</h2>
		</template>

		<div class="py-12">
			<div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
				<div class="grid grid-cols-6 gap-4">
					<div class="p-4 rounded-md bg-indigo-50 border border-indigo-300">
                        <p class="text-5xl mb-4 text-indigo-900 font-bold">{{ number_of_schedules }}</p>
						<h2 class="text-lg text-indigo-700">Total Number of Schedules</h2>
					</div>
					<div class="p-4 rounded-md bg-pink-50 border border-pink-300">
                        <p class="text-5xl mb-4 text-pink-900 font-bold">{{ number_of_users }}</p>
						<h2 class="text-lg text-pink-700">Total Number of Schedules Users</h2>
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
