<template>
<div class="min-h-full p-3 space-y-2 bg-secondary-dark text-white transition duration-150 ease-in-out" :class="showFull ? 'w-60' : 'w-24'">
    <div class="flex items-center p-2 space-x-4">
        <inertia-link :href="route('dashboard')">
            <breeze-application-logo class="block w-full h-auto" />
        </inertia-link>
    </div>
    <div class="flex justify-end">
        <chevron-double-left-icon v-if="showFull" class="h-5 w-5 text-gray-100 mr-0 cursor-pointer hover:text-white" @click="showFull = false"/>
        <chevron-double-right-icon v-else class="h-5 w-5 text-gray-100 mr-0 cursor-pointer hover:text-white" @click="showFull = true"/>
    </div>
    <div class="divide-y divide-gray-300">
        <ul v-if="showFull" class="pt-2 pb-4 space-y-1 text-sm">
            <li>
                <breeze-nav-link :href="route('dashboard')" :active="route().current('dashboard')">
                    <cube-icon class="h-5 w-5 mr-4" :class="route().current('dashboard') ? 'text-primary-light': 'text-gray-100'"/>
                    Dashboard
                </breeze-nav-link>
            </li>
            <li>
                <breeze-nav-link :href="route('schedule.index')" :active="route().current('schedule.index')">
                    <calendar-icon class="h-5 w-5 mr-4" :class="route().current('schedule.index') ? 'text-primary-light': 'text-gray-100'"/>
                    Schedule
                </breeze-nav-link>
            </li>
            <li v-if="isAdmin">
                <nav-dropdown>
                    <template #trigger>
                        <academic-cap-icon class="h-5 w-5 mr-4" :class="route().current('course.index') ? 'text-primary-light': 'text-gray-100'"/>
                        <span class="flex-1">Classrooms & Courses</span>
                        <chevron-down-icon class="h-5 w-5 ml-2 text-gray-100"/>
                    </template>

                    <template #content>
                        <li>
                            <breeze-nav-link :href="route('classroom.index')" :active="route().current('classroom.index')">
                                My Classrooms
                            </breeze-nav-link>
                        </li>
                        <li>
                            <breeze-nav-link :href="route('course.index')" :active="route().current('course.index')">
                                All Courses
                            </breeze-nav-link>
                        </li>
                    </template>
                </nav-dropdown>
            </li>
            <li v-if="isAdmin" >
                <nav-dropdown>
                    <template #trigger>
                        <chip-icon class="h-5 w-5 mr-4" :class="(route().current('temperature.index') || route().current('admin.rfid.index')) ? 'text-primary-light': 'text-gray-100'"/>
                        <span class="flex-1">Raspberry Pi</span>
                        <chevron-down-icon class="h-5 w-5 ml-2 text-gray-100"/>
                    </template>

                    <template #content>
                        <li>
                            <breeze-nav-link :href="route('temperature.index')" :active="route().current('temperature.index')">
                                <fire-icon class="h-5 w-5 mr-2 " :class="route().current('temperature.index') ? 'text-primary-light': 'text-gray-100'"/>
                                Temperature
                            </breeze-nav-link>
                        </li>
                        <li>
                            <breeze-nav-link :href="route('admin.rfid.index')" :active="route().current('admin.rfid.index')">
                                <credit-card-icon class="h-5 w-5 mr-2" :class="route().current('admin.rfid.index') ? 'text-primary-light': 'text-gray-100'"/>
                                RFID
                            </breeze-nav-link>
                        </li>
                    </template>
                </nav-dropdown>
            </li>
            <li v-if="isAdmin">
                <nav-dropdown>
                    <template #trigger>
                        <users-icon class="h-5 w-5 mr-4" :class="route().current('admin.user.index') ? 'text-primary-light': 'text-gray-100'"/>
                        <span class="flex-1">Users</span>
                        <chevron-down-icon class="h-5 w-5 ml-2 text-gray-100"/>
                    </template>

                    <template #content>
                        <breeze-nav-link :href="route('admin.user.index')" :active="route().current('admin.user.index')">
                            All Users
                        </breeze-nav-link>
                        <breeze-nav-link  :href="route('admin.section.index')" :active="route().current('admin.section.index')">
                            Section
                        </breeze-nav-link>
                    </template>
                </nav-dropdown>
            </li>
        </ul>
        <ul v-else class="pt-2 pb-4 space-y-1 text-sm">
            <li class="mb-4 p-4 relative nav-icons cursor-pointer">
                <inertia-link :href="route('dashboard')">
                    <cube-icon class="h-10 w-10" :class="route().current('dashboard') ? 'text-white fill-current': 'text-gray-200'"/>
                    <span class="absolute bg-secondary-light py-1 px-4 rounded-lg top-0 left-0 ml-10"> Dashboard</span>
                </inertia-link>
            </li>
            <li class="mb-4 p-4 relative nav-icons cursor-pointer">
                 <inertia-link :href="route('schedule.index')">
                    <calendar-icon  class="h-10 w-10" :class="route().current('schedule.index') ? 'text-white fill-current': 'text-gray-200'"/>
                    <span class="absolute bg-secondary-light py-1 px-4 rounded-lg top-0 left-0 ml-10"> Schedule</span>
                 </inertia-link>
            </li>
            <li v-if="isAdmin" class="mb-4 p-4 relative nav-icons cursor-pointer">
                <span class="absolute bg-secondary-light py-1 px-4 rounded-lg top-0 left-0 ml-10"> Course</span>
                <breeze-dropdown v-if="isAdmin" align="left">
                    <template #trigger>
                        <academic-cap-icon  class="h-10 w-10" :class="(route().current('classroom.index') || route().current('course.index')) ? 'text-white fill-current': 'text-gray-200'"/>
                    </template>

                    <template #content>
                        <breeze-dropdown-link class="flex hover:text-primary-dark" :href="route('classroom.index')" :active="route().current('classroom.index')">
                            Classrooms
                        </breeze-dropdown-link>
                        <breeze-dropdown-link class="flex hover:text-primary-dark" :href="route('course.index')" :active="route().current('course.index')">
                            Courses
                        </breeze-dropdown-link>
                    </template>
                </breeze-dropdown>
            </li>
            <li v-if="isAdmin" class="mb-4 p-4 relative nav-icons cursor-pointer">
                <span class="absolute bg-secondary-light py-1 px-4 rounded-lg top-0 left-0 ml-10"> Raspberry Pi</span>
                <breeze-dropdown v-if="isAdmin" align="left">
                    <template #trigger>
                        <chip-icon class="h-10 w-10" :class="(route().current('temperature.index') || route().current('admin.rfid.index')) ? 'text-white fill-current': 'text-gray-200'"/>
                    </template>

                    <template #content>
                        <breeze-dropdown-link class="flex hover:text-primary-dark" :href="route('temperature.index')" :active="route().current('temperature.index')">
                            Temperature
                        </breeze-dropdown-link>
                        <breeze-dropdown-link class="flex hover:text-primary-dark" :href="route('admin.rfid.index')" :active="route().current('admin.rfid.index.index')">
                            RFID
                        </breeze-dropdown-link>
                    </template>
                </breeze-dropdown>
            </li>
            <li v-if="isAdmin" class="mb-4 p-4 relative nav-icons cursor-pointer">
                <span class="absolute bg-secondary-light py-1 px-4 rounded-lg top-0 left-0 ml-10"> Users</span>
                <breeze-dropdown v-if="isAdmin" align="left">
                    <template #trigger>
                        <users-icon class="h-10 w-10" :class="route().current('admin.user.index') ? 'text-white fill-current': 'text-gray-200'"/>
                    </template>

                    <template #content>
                        <breeze-dropdown-link class="flex hover:text-primary-dark" :href="route('admin.user.index')" :active="route().current('admin.user.index')">
                            All Users
                        </breeze-dropdown-link>
                        <breeze-dropdown-link class="flex hover:text-primary-dark" :href="route('course.index')" :active="route().current('course.index')">
                            Courses
                        </breeze-dropdown-link>
                    </template>
                </breeze-dropdown>
            </li>
        </ul>
    </div>
</div>
</template>

<script>
import BreezeApplicationLogo from '@/Components/ApplicationLogo'
import BreezeNavLink from '@/Components/NavLink'
import NavDropdown from '@/Components/NavigationDropdown'
import BreezeDropdown from '@/Components/Dropdown'
import BreezeDropdownLink from '@/Components/DropdownLink'
import { CubeIcon, ChevronDoubleRightIcon, ChevronDoubleLeftIcon, FireIcon, AcademicCapIcon, CalendarIcon, CreditCardIcon, UsersIcon, ChevronDownIcon, ChipIcon } from '@heroicons/vue/outline'

export default {
    components: {
        BreezeNavLink,
        BreezeApplicationLogo,
        NavDropdown,
        BreezeDropdown,
        BreezeDropdownLink,
        CubeIcon,
        FireIcon,
        AcademicCapIcon,
        CalendarIcon,
        CreditCardIcon,
        UsersIcon,
        ChevronDownIcon,
        ChipIcon,
        ChevronDoubleRightIcon,
        ChevronDoubleLeftIcon,
    },

    data () {
        return {
            showFull: false,
            showingNavigationDropdown: false,
            isAdmin: process.env.MIX_ADMINS.includes(this.$page.props.auth.user.email)
        }
    },

    methods: {
        mouseover(e){
            this.showFull = true
        },
        mouseleave(e){
            this.showFull = false
        }
    }
}
</script>

<style>
.nav-icons span{
    display: none;
}
.nav-icons:hover span{
    display: block;
}
</style>
