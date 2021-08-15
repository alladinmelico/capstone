<template>
	<breeze-authenticated-layout>
		<template #header>
			<h2 class="font-semibold text-xl text-gray-800 leading-tight">
				<InertiaLink href="/course" class="underline">
                    Course
                </InertiaLink>
                <chevron-double-right-icon class="inline-block h-5 w-5 text-primary mx-2" /> {{ item.name }}
			</h2>

            <div class="flex justify-around mt-4">
                <p> <strong>Code: </strong> {{ item.code }}</p>
                <p> <strong>Department: </strong> {{ item.department }}</p>
            </div>
		</template>

		<div class="py-12">
			<div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
			</div>
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
		return {
		}
	},
}
</script>
