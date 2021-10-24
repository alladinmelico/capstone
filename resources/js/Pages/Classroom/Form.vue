<template>
	<breeze-authenticated-layout>
		<div class="py-10 mx-auto max-w-7xl sm:px-6 lg:px-8">
			<JetFormSection @submitted="save">
				<template #title> Classroom </template>

				<template #description> Update the classroom here. </template>

				<template #form>
					<FormInput
						v-model="form.start_at"
                        :max="form.end_at"
						type="time"
						class="col-span-6 sm:col-span-4"
						:error="form.errors.start_at"
						label="Start Time"
						required
					/>
					<FormInput
						v-model="form.end_at"
                        :min="form.start_at"
						type="time"
						class="col-span-6 sm:col-span-4"
						:error="form.errors.end_at"
						label="End Time"
						required
					/>
					<FormSelect
						v-model="form.day"
						:options="days"
						class="col-span-6 sm:col-span-4"
						:error="form.errors.day"
						label="Day"
						required
					/>
					<FormDatepicker
						v-model="form.valid_until"
                        type="date"
						class="col-span-6 sm:col-span-4 "
						:error="form.errors.valid_until"
						label="Valid classroom after:"
						required
					/>
					<InputTextArea
						v-model="form.note"
						class="col-span-6 sm:col-span-4 "
						:error="form.errors.note"
						label="Note"
					/>
                    <FormSelect
						v-model="form.facility_id"
						:options="facilities"
						class="col-span-6 sm:col-span-4"
						:error="form.errors.facility_id"
						label="Facility"
						required
					/>
				</template>

				<template #actions>
					<InertiaLink href="/classroom" class="mr-2">
						<JetSecondaryButton> Cancel </JetSecondaryButton>
					</InertiaLink>

					<JetButton
						:class="{ 'opacity-25': form.processing }"
						:disabled="form.processing"
					>
						Save
					</JetButton>
				</template>
			</JetFormSection>
		</div>
	</breeze-authenticated-layout>
</template>

<script>
import BreezeAuthenticatedLayout from '@/Layouts/Authenticated'
import JetFormSection from '@/Components/FormSection'
import JetButton from '@/Components/Button'
import JetSecondaryButton from '@/Components/SecondaryButton'
import FormDatepicker from '@/Components/FormDatepicker'
import FormInput from '@/Components/FormInput'
import FormTextarea from '@/Components/FormTextarea'
import FormSelect from '@/Components/FormSelect'
import InputTextArea from '@/Components/FormTextArea'
const dayjs = require('dayjs')

export default {
	components: {
		JetFormSection,
		JetButton,
		JetSecondaryButton,
		FormInput,
		FormTextarea,
		BreezeAuthenticatedLayout,
		FormDatepicker,
		FormSelect,
        InputTextArea,
	},

	props: {
		item: {
			type: Object,
			required: false,
		},
        facilities: {
            type: Array,
            required: true
        }
	},

	data() {
		const item = this.item
        if(item){
            item.valid_until = new Date(item.valid_until)
        }
		return {
			form: this.$inertia.form({
				_method: item ? 'PUT' : 'POST',
				google_classroom_id: '',
				subject_id: '',
				schedule_id: '',
				...item,
			}),
			days: [
				{
					id: 'monday',
					name: 'Monday',
				},
				{
					id: 'tuesday',
					name: 'Tuesday',
				},
				{
					id: 'wednesday',
					name: 'Wednesday',
				},
				{
					id: 'thursday',
					name: 'Thursday',
				},
				{
					id: 'friday',
					name: 'Friday',
				},
				{
					id: 'saturday',
					name: 'Saturday',
				},
			],
		}
	},

	computed: {
		isEditing() {
			return !!this.item?.id
		},
	},

	methods: {
		save() {
            this.form.valid_until = dayjs(this.form.valid_until).format('YYYY-MM-D hh:mm:ss')
			const url = this.isEditing ? `/classroom/${this.item.id}` : '/classroom'
			this.form.post(url)
		},
	},
}
</script>
