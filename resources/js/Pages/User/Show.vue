<template>
	<breeze-authenticated-layout>
		<template #header>
			<h2 v-if="item.id !== $page.props.auth.user.id" class="font-semibold text-xl text-gray-800 leading-tight">
				<InertiaLink href="/admin/user" class="underline">User</InertiaLink>
                <chevron-double-right-icon class="inline-block h-5 w-5 text-primary mx-2" /> {{ item.name }}
			</h2>
            <h2 v-else class="font-semibold text-xl text-gray-800 leading-tight">
                My Profile
            </h2>
		</template>

		<div class="py-12 max-w-7xl mx-auto sm:px-6 lg:px-8">
            <profile-form :user="item" :courses="courses"/>
		</div>
	</breeze-authenticated-layout>
</template>

<script>
import BreezeAuthenticatedLayout from '@/Layouts/Authenticated'
import ProfileForm from '../Auth/Profile.vue'
import Card from './Card'
import { ChevronDoubleRightIcon } from '@heroicons/vue/solid'

export default {
	components: {
		BreezeAuthenticatedLayout,
        ProfileForm,
        ChevronDoubleRightIcon,
        Card
	},
	props: {
		item: {
			type: Object,
			default: {},
		},
        courses: {
            type: Array,
            default: []
        }
	},
	data() {
		return {
		}
	},
}
</script>
