<template>
    <div class="rounded-lg bg-secondary p-1 grid gap-x-4 grid-cols-4">
        <div>
            <img :src="user.avatar" class="rounded-lg w-auto" :alt="user.name">
        </div>
        <div class="col-span-3">
            <p class="text-lg font-bold mt-4 text-gray-50" v-text="user.name"/>
            <p class="mt-4  text-white" v-text="user.email"/>
        </div>
    </div>
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
            <breeze-label for="section_code" value="Section Code" />
            <breeze-input id="section_code" type="text" class="mt-1 block w-full" :value="sectionCode" disabled />
        </div>

        <div class="flex items-center justify-end mt-4">
            <breeze-button class="ml-4" :class="{ 'opacity-25': form.processing }" :disabled="form.processing">
                Submit
            </breeze-button>
        </div>
    </form>
</template>

<script>
    import BreezeButton from '@/Components/Button'
    import BreezeGuestLayout from "@/Layouts/Guest"
    import BreezeInput from '@/Components/Input'
    import BreezeCheckbox from '@/Components/Checkbox'
    import BreezeLabel from '@/Components/Label'
    import BreezeValidationErrors from '@/Components/ValidationErrors'

    export default {
        layout: BreezeGuestLayout,

        components: {
            BreezeButton,
            BreezeInput,
            BreezeCheckbox,
            BreezeLabel,
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
                        label: 'First Year'
                    },
                    {
                        value: 2,
                        label: 'Second Year'
                    },
                    {
                        value: 3,
                        label: 'Third Year'
                    },
                    {
                        value: 4,
                        label: 'Fourth Year'
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
