<template>
    <div class="grid sm:grid-cols-2 gap-4">
        <div>

            <breeze-validation-errors class="mb-4" />

            <form @submit.prevent="submit">
                <div class="mt-4">
                    <breeze-label for="school_id" value="School ID" />
                    <breeze-input id="school_id" type="text" class="mt-1 block w-full" v-model="form.school_id" placeholder="TUPT-XX-XXXX" required />
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

                <breeze-button class="mt-8 w-full" :class="{ 'opacity-25': form.processing }" :disabled="form.processing || !agree">
                    Submit
                </breeze-button>
            </form>
        </div>
        <div>
            <div class="ring-4 ring-gray-200 rounded-lg bg-gradient-to-t from-secondary-dark to-secondary p-2 d-flex flex-row justify-around h-full text-center text-xs text-white min-w-min">
                <div class="mx-auto w-8 h-2 my-3 rounded-full bg-secondary-dark"></div>
                <p>Republic of the Philippines</p>
                <p>Technological University of the Philippines</p>
                <p>Taguig Campus</p>
                <small>Km 14 East Service Road, Western Bicutan Taguig City 1630</small>
                <div class="grid grid-cols-2">
                    <breeze-application-logo class="w-20 h-20 text-gray-500 mx-auto my-auto" />
                    <img :src="user.avatar_original" class="rounded-lg w-auto mx-auto my-auto mt-4" :alt="user.name">
                </div>
                <div class="mb-3">
                    <p class="text-lg font-bold mt-4" v-text="user.name"/>
                    <p class="text-sm" v-text="user.email"/>
                </div>
                <p class="font-bold	text-lg">{{ form.school_id.toUpperCase() }}</p>
                <p class="font-sm font-bold">{{ sectionCode }}</p>
            </div>
        </div>
    </div>

</template>

<script>
    import BreezeButton from '@/Components/Button'
    import BreezeGuestLayout from "@/Layouts/Guest"
    import BreezeInput from '@/Components/Input'
    import BreezeCheckbox from '@/Components/Checkbox'
    import BreezeLabel from '@/Components/Label'
    import BreezeApplicationLogo from '@/Components/ApplicationLogo'
    import BreezeValidationErrors from '@/Components/ValidationErrors'

    export default {
        layout: BreezeGuestLayout,

        components: {
            BreezeButton,
            BreezeInput,
            BreezeCheckbox,
            BreezeLabel,
            BreezeApplicationLogo,
            BreezeValidationErrors
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
                    section: null
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

                ]
            }
        },

        computed: {
            sectionCode(){
                return this.selectedCourseCode + '-' + (this.form.year ? this.form.year : '') + (this.form.section ? this.form.section.toUpperCase() : '')
            },
            selectedCourseCode(){
                const selectedCourse = this.courses.find(course => course.id == this.form.course_id)
                return selectedCourse ? selectedCourse.code : ''
            }
        },

        methods: {
            submit() {
                console.log(this.form)
                this.form.post(this.route('store-profile'))
            }
        },
    }
</script>
