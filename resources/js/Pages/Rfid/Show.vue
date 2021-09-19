<template>
	<breeze-authenticated-layout>
		<template #header>
			<h2 class="font-semibold text-xl text-gray-800 leading-tight">
				<InertiaLink href="/admin/rfid" class="underline">RFID</InertiaLink>
				<chevron-double-right-icon
					class="inline-block h-5 w-5 text-primary mx-2"
				/>
				{{ item.value }}
			</h2>

			<div class="flex justify-around mt-4" v-if="item.user !== null">
				<p><strong>User: </strong> {{ item.user.name }}</p>
				<p><strong>User ID: </strong> {{ item.user.school_id }}</p>
			</div>
		</template>

		<div class="py-12">
			<div class="max-w-7xl mx-auto sm:px-6 lg:px-8"></div>
		</div>
	</breeze-authenticated-layout>
</template>

<script>
import BreezeAuthenticatedLayout from '@/Layouts/Authenticated'
import { ChevronDoubleRightIcon } from '@heroicons/vue/solid'

export default {
	components: {
		BreezeAuthenticatedLayout,
		ChevronDoubleRightIcon,
	},
	props: {
		item: {
			type: Object,
			default: {},
		},
	},
	data() {
		return {}
	},
}
</script>
