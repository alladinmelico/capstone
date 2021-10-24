<template>
    <div class="grid sm:grid-cols-2 gap-4">
        <div v-if="showForm && user.id === $page.props.auth.user.id">

            <breeze-validation-errors class="mb-4" />

            <form @submit.prevent="submit">
                <div class="mt-4">
                    <breeze-label for="name" value="Fullname" />
                    <breeze-input id="name" type="text" class="mt-1 block w-full" v-model="form.name" required />
                    <small class="ml-4 text-gray-500 font-thin font-italic text-xs">Firstname M.I. Lastname</small>
                </div>

                <div class="mt-4">
                    <breeze-label for="email" value="Email" />
                    <breeze-input id="email" type="email" class="mt-1 block w-full" v-model="form.email" placeholder="***@tup.edu.ph" required />
                    <small class="ml-4 text-gray-500 font-thin font-italic text-xs">Use the email provided by TUP</small>
                </div>

                <div class="mt-4">
                    <breeze-label for="school_id" value="School ID" />
                    <breeze-input id="school_id" type="text" class="mt-1 block w-full" v-model="form.school_id" placeholder="TUPT-**-****" required />
                    <small class="ml-4 text-gray-500 font-thin font-italic text-xs">Format: TUPT-**-****</small>
                </div>

                <div class="mt-4">
                    <breeze-label for="course" value="Course" />
                    <select v-model="form.course_id" name="course" id="course" class="border-gray-300 focus:border-primary focus:ring focus:ring-primary-light focus:ring-opacity-50 rounded-md shadow-sm w-full" required>
                        <option v-for="course in courses" :key="course.id" :value="course.id">{{ course.name }}</option>
                    </select>
                </div>

                <div class="grid gap-x-4 grid-cols-2 mt-4">
                    <div>
                        <breeze-label for="year" value="Year Level" />
                        <select v-model="form.year" name="year" id="year" class="border-gray-300 focus:border-primary focus:ring focus:ring-primary-light focus:ring-opacity-50 rounded-md shadow-sm w-full" required>
                            <option v-for="year in years" :key="year.value" :value="year.value">{{ year.label }}</option>
                        </select>
                    </div>

                    <div>
                        <breeze-label for="section" value="Section" />
                        <select v-model="form.section" name="section" id="section" class="border-gray-300 focus:border-primary focus:ring focus:ring-primary-light focus:ring-opacity-50 rounded-md shadow-sm w-full" required>
                            <option v-for="section in sections" :key="section.value" :value="section.value">{{ section.label }}</option>
                        </select>
                    </div>
                </div>

                <div class="mt-4">
                    <breeze-checkbox v-model:checked="agree"/>
                    <span class="text-sm ml-2">I agree to the <a href="/tems-of-service" class="underline">Terms of Service</a> and <a href="privacy-policy" class="underline">Privacy Policy</a></span>
                </div>

                <div class="flex justify-end">
                    <secondary-button class="mt-8" @click="showForm = false">
                        Cancel
                    </secondary-button>

                    <breeze-button class="mt-8 mx-4" :class="{ 'opacity-25': form.processing, 'cursor-not-allowed': !agree }" :disabled="form.processing || !agree">
                        Submit
                    </breeze-button>
                </div>
            </form>
        </div>
        <div v-else>
            <secondary-button v-if="user.id === $page.props.auth.user.id" class="mb-2 hover:text-primary-dark" @click="showForm = true">
                <pencil-icon class="mr-2 h-5 w-6"/> Edit
            </secondary-button>
            <table class="w-full min-h-full">
                <tr class="border-b-1 border-primary">
                    <td class="font-bold">Fullname: </td>
                    <td>{{ user.name }}</td>
                </tr>
                <tr class="border-b-1 border-primary">
                    <td class="font-bold">Email: </td>
                    <td>{{ user.email }}</td>
                </tr>
                <tr class="border-b-1 border-primary">
                    <td class="font-bold">School ID: </td>
                    <td>{{ user.school_id.toUpperCase() }}</td>
                </tr>
                <tr class="border-b-1 border-primary">
                    <td class="font-bold">Course: </td>
                    <td>{{ courseName }}</td>
                </tr>
                <tr class="border-b-1 border-primary">
                    <td class="font-bold">Year and Section: </td>
                    <td>{{ `${user.year} - ${user.section.toUpperCase()}` }}</td>
                </tr>
            </table>
        </div>
        <div class="min-w-min max-w-xs mx-auto max-h-96	">
            <div class="ring-4 ring-gray-200 rounded-lg bg-gradient-to-t from-secondary-dark to-secondary p-2 d-flex flex-row justify-around h-full text-center text-xs text-white min-w-min">
                <div class="mx-auto w-8 h-2 my-3 rounded-full bg-secondary-dark"></div>
                <p>Republic of the Philippines</p>
                <p>Technological University of the Philippines</p>
                <p>Taguig Campus</p>
                <small>Km 14 East Service Road, Western Bicutan Taguig City 1630</small>
                <div class="grid grid-cols-2">
                    <breeze-application-logo class="w-20 h-20 text-gray-500 mx-auto my-auto" />
                    <img :src="user.avatar_original ?? 'https://ui-avatars.com/api/?name='+user.name" class="rounded-lg w-auto mx-auto my-auto mt-4" :alt="user.name">
                </div>
                <div class="mb-3">
                    <p class="text-lg font-bold mt-4" v-text="form.name ?? user.name"/>
                    <p class="text-sm" v-text="form.email ?? user.email"/>
                </div>
                <p class="font-bold	text-lg">{{ form.school_id.toUpperCase() }}</p>
                <p class="font-sm font-bold">{{ sectionCode }}</p>
            </div>
        </div>
    </div>

</template>

<script>
    import BreezeButton from '@/Components/Button'
    import SecondaryButton from '@/Components/SecondaryButton'
    import BreezeGuestLayout from "@/Layouts/Guest"
    import BreezeInput from '@/Components/Input'
    import BreezeCheckbox from '@/Components/Checkbox'
    import BreezeLabel from '@/Components/Label'
    import BreezeApplicationLogo from '@/Components/ApplicationLogo'
    import BreezeValidationErrors from '@/Components/ValidationErrors'
    import { PencilIcon } from '@heroicons/vue/outline'

    export default {
        layout: BreezeGuestLayout,

        components: {
            BreezeButton,
            SecondaryButton,
            BreezeInput,
            BreezeCheckbox,
            BreezeLabel,
            BreezeApplicationLogo,
            BreezeValidationErrors,
            PencilIcon
        },

        props: {
            user: Object,
            courses: Array,
        },

        data() {
            return {
                form: this.$inertia.form({
                    school_id: '',
                    course_id: '',
                    year: null,
                    section: null,
                    ...this.user
                }),
                agree: false,
                sections: [
                    {
                        value: 'a',
                        label: 'A'
                    },
                    {
                        value: 'b',
                        label: 'B'
                    },
                    {
                        value: 'c',
                        label: 'C'
                    },
                    {
                        value: 'd',
                        label: 'D'
                    }
                ],
                years: [
                    {
                        value: 1,
                        label: '1st Year'
                    },
                    {
                        value: 2,
                        label: '2nd Year'
                    },
                    {
                        value: 3,
                        label: '3rd Year'
                    },
                    {
                        value: 4,
                        label: '4th Year'
                    },

                ],
                showForm: false
            }
        },

        computed: {
            sectionCode(){
                return this.selectedCourseCode + '-' + (this.form.year ? this.form.year : '') + (this.form.section ? this.form.section.toUpperCase() : '')
            },
            selectedCourseCode(){
                const selectedCourse = this.courses.find(course => course.id == this.form.course_id)
                return selectedCourse ? selectedCourse.code : ''
            },
            courseName(){
                return this.courses.find(course => course.id == this.user.course_id).name
            }
        },

        watch:{
            showForm(value) {
                if(!value){
                    this.resetForm()
                }
            }
        },

        methods: {
            submit() {
                this.form.post(this.route('store-profile'))
            },
            resetForm() {
                this.form.name = this.user.name
                this.form.email = this.user.email
                this.form.school_id = this.user.school_id
                this.form.course_id = this.user.course_id
                this.form.year = this.user.year
                this.form.section = this.user.section
                this.$page.props.errors = {}
            }
        },
    }
</script>
