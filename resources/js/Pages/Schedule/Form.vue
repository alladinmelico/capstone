<template>
	<breeze-authenticated-layout>
        <template #header>
			<h2 class="font-semibold text-xl text-gray-800 leading-tight">
                <InertiaLink href="/schedule" class="underline">Schedule</InertiaLink>
				<chevron-double-right-icon class="inline-block h-5 w-5 text-primary mx-2" />
                {{ item ? 'Update Schedule' : 'Create New Schedule' }}
			</h2>
		</template>
		<div class="py-10 mx-auto max-w-7xl sm:px-6 lg:px-8">
			<JetFormSection @submitted="save">
				<template #title> Schedule </template>

				<template #form>
					<FormInput
						v-model="form.start_at"
                        :max="form.end_at"
						type="time"
						class="col-span-3"
						:error="form.errors.start_at"
						label="Start Time"
						required
					/>
					<FormInput
						v-model="form.end_at"
                        :min="form.start_at"
						type="time"
						class="col-span-3"
						:error="form.errors.end_at"
						label="End Time"
						required
					/>
					<FormSelect
						v-model="form.day"
						:options="days"
						class="col-span-3"
						:error="form.errors.day"
						label="Day"
						required
					/>
					<FormDatepicker
						v-model="form.valid_until"
                        type="date"
						class="col-span-3"
						:error="form.errors.valid_until"
						label="Valid Schedule after:"
						required
					/>
                    <FormSelect
						v-model="form.facility_id"
						:options="facilities"
						class="col-span-3"
						:error="form.errors.facility_id"
						label="Facility"
						required
					/>
                    <FormSelect
						v-model="form.subject_id"
						:options="subjects"
						class="col-span-3"
						:error="form.errors.subject_id"
						label="Subject"
					/>
                    <div class="mt-4 col-span-6 border-t-2 border-gray-100 pt-4">
                        <custom-label value="Google Classroom"/>
                    </div>
                    <div class="col-span-6 grid grid-cols-3 gap-4">
                       <div
                            v-for="classroom in classes"
                            :key="classroom.id"
                            class="p-2 hover:border-primary hover:ring hover:ring-primary-light hover:ring-opacity-50 rounded-md shadow-md"
                            :class="(form.google_classroom_id == classroom.id) ? 'border-secondary-dark ring ring-secondary shadow-lg': 'border-gray-300' "
                            @click="form.google_classroom_id = classroom.id"
                        >
                           <p>{{ classroom.name }}</p>
                           <p class="text-xs font-italic mt-2 text-gray-500">{{ classroom.descriptionHeading }}</p>
                        </div>
                    </div>
					<InputTextArea
						v-model="form.note"
						class="col-span-6"
						:error="form.errors.note"
						label="Note"
					/>
				</template>

				<template #actions>
					<InertiaLink href="/schedule" class="mr-2">
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
import hasGClassroom from '@/Mixins/hasGClassroom'
import CustomLabel from '@/Components/Label'
import { ChevronDoubleRightIcon } from '@heroicons/vue/solid'
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
        CustomLabel,
        ChevronDoubleRightIcon
	},


	props: {
		item: {
			type: Object,
			required: false,
		},
        facilities: {
            type: Array,
            required: true
        },
        subjects: {
            type: Array,
            required: true
        },
        existing_classrooms: {
            type: Array,
            required: true,
            default: []
        },
        schedule_classroom: {
            type: Object,
            default: {
                google_classroom_id: '',
                subject_id: ''
            }
        },
        token: {
            type: String,
            default: ''
        }
	},
mounted() {
		this.getClasses()
	},
	data() {
		const item = this.item
        if(item){
            item.valid_until = new Date(item.valid_until)
        }
		return {
			form: this.$inertia.form({
				_method: item ? 'PUT' : 'POST',
				start_at: '',
				end_at: '',
				day: '',
				valid_until: new Date(),
				note: '',
				facility_id: null,
				google_classroom_id: this.schedule_classroom && this.schedule_classroom.google_classroom_id ? this.schedule_classroom.google_classroom_id:'',
				subject_id: this.schedule_classroom && this.schedule_classroom.subject_id ? this.schedule_classroom.subject_id:null,
				user_id: this.$page.props.auth.user.id,
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
            classes: []
		}
	},

	computed: {
		isEditing() {
			return !!this.item?.id
		},
        gClassrooms() {
            console.log(this.classes)
            return
        }
	},

	methods: {
		save() {
            this.form.valid_until = dayjs(this.form.valid_until).format('YYYY-MM-D hh:mm:ss')
			const url = this.isEditing ? `/schedule/${this.item.id}` : '/schedule'
			this.form.post(url)
		},
        async getClasses() {
			const axios = window.axios
			const response = await axios
				.get('https://classroom.googleapis.com/v1/courses', {
					params: {
						access_token: this.token,
					},
				})
				.catch(function (error) {
					if (error.response.status === 401) {
                        axios.post('/logout')
                        window.location.reload()
					}
				})
			this.classes = response.data.courses
		},
	},
}
</script>
