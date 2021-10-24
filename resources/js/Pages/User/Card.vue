<template>
    <div class="ring-4 ring-gray-200 rounded-lg bg-gradient-to-t from-secondary-dark to-secondary p-2 d-flex flex-row justify-around h-full text-center text-xs text-white min-w-min max-w-xs">
        <div class="mx-auto w-8 h-2 my-3 rounded-full bg-secondary-dark"></div>
        <p>Republic of the Philippines</p>
        <p>Technological University of the Philippines</p>
        <p>Taguig Campus</p>
        <small>Km 14 East Service Road, Western Bicutan Taguig City 1630</small>
        <div class="grid grid-cols-2">
            <breeze-application-logo class="w-20 h-20 text-gray-500 mx-auto my-auto" />
            <img :src="avatar" class="rounded-lg w-auto mx-auto my-auto mt-4" :alt="name">
        </div>
        <div class="mb-3">
            <p class="text-lg font-bold mt-4" v-text="name"/>
            <p class="text-sm" v-text="email"/>
        </div>
        <p class="font-bold	text-lg">{{ schoolId.toUpperCase() }}</p>
        <p class="font-sm font-bold">{{ sectionCode }}</p>
    </div>
</template>

<script>
import BreezeApplicationLogo from '@/Components/ApplicationLogo'
    export default {
        components: {
            BreezeApplicationLogo
        },
        props: {
            name: {
                type: String,
                default: ''
            },
            avatar: {
                type: String,
                default: ''
            },
            email: {
                type: String,
                default: ''
            },
            schoolId: {
                type: String,
                default: ''
            },
            courseCode: {
                type: String,
                default: ''
            },
            section: {
                type: String,
                default: ''
            },
            year: {
                type: Number,
                default: 1
            },
        },
        computed: {
            sectionCode(){
                console.log(this.courseCode + '-' + (this.year ? this.year : '') + (this.section ? this.section.toUpperCase() : ''))
                return this.courseCode + '-' + (this.year ? this.year : '') + (this.section ? this.section.toUpperCase() : '')
            },
        },
    }
</script>
