<template>
    <div class="flex justify-between min-h-screen w-full">
        <page-navigation />
        <div class="flex-1 h-full bg-white flex flex-col justify-between">
            <nav class="bg-white shadow-md border-b border-gray-100">
                <!-- Primary Navigation Menu -->
                <div class="w-full mx-auto px-4 sm:px-6 lg:px-8">
                    <div class="flex justify-between items-center h-16">
                        <slot name="header" />
                        <div class="hidden sm:flex sm:items-center sm:ml-6">
                            <div>
                                <notification-dropdown />
                            </div>
                            <!-- Settings Dropdown -->
                            <div class="ml-3 relative">
                                <breeze-dropdown align="right" width="48">
                                    <template #trigger>
                                        <span class="inline-flex rounded-md">
                                            <button type="button" class="inline-flex items-center px-3 py-2 border border-transparent text-sm leading-4 font-medium rounded-md text-gray-500 bg-white hover:text-gray-700 focus:outline-none transition ease-in-out duration-150">
                                                <div class="flex items-center">
                                                    {{ $page.props.auth.user.name }}
                                                    <svg class="ml-1 -mr-0.5 h-4 w-4" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor">
                                                        <path fill-rule="evenodd" d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z" clip-rule="evenodd" />
                                                    </svg>
                                                    <img :src="$page.props.auth.user.avatar" class="ml-2 rounded-lg h-11" :alt="$page.props.auth.user.name">
                                                </div>
                                            </button>
                                        </span>
                                    </template>

                                    <template #content>
                                        <breeze-dropdown-link :href="route('user.show', $page.props.auth.user.id)">
                                            Profile
                                        </breeze-dropdown-link>
                                        <breeze-dropdown-link :href="route('logout')" method="post" as="button">
                                            Log Out
                                        </breeze-dropdown-link>
                                    </template>
                                </breeze-dropdown>
                            </div>
                        </div>

                        <!-- Hamburger -->
                        <div class="-mr-2 flex items-center sm:hidden">
                            <button @click="showingNavigationDropdown = ! showingNavigationDropdown" class="inline-flex items-center justify-center p-2 rounded-md text-gray-400 hover:text-gray-500 hover:bg-gray-100 focus:outline-none focus:bg-gray-100 focus:text-gray-500 transition duration-150 ease-in-out">
                                <svg class="h-6 w-6" stroke="currentColor" fill="none" viewBox="0 0 24 24">
                                    <path :class="{'hidden': showingNavigationDropdown, 'inline-flex': ! showingNavigationDropdown }" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16" />
                                    <path :class="{'hidden': ! showingNavigationDropdown, 'inline-flex': showingNavigationDropdown }" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                                </svg>
                            </button>
                        </div>
                    </div>
                </div>

                <!-- Responsive Navigation Menu -->
                <div :class="{'block': showingNavigationDropdown, 'hidden': ! showingNavigationDropdown}" class="sm:hidden">
                    <div class="pt-2 pb-3 space-y-1">
                        <breeze-responsive-nav-link :href="route('dashboard')" :active="route().current('dashboard')">
                            Dashboard
                        </breeze-responsive-nav-link>
                        <breeze-responsive-nav-link :href="route('schedule.index')" :active="route().current('schedule.index')">
                            Schedule
                        </breeze-responsive-nav-link>
                        <breeze-responsive-nav-link :href="route('course.index')" :active="route().current('course.index')">
                            Course
                        </breeze-responsive-nav-link>
                        <breeze-responsive-nav-link v-if="isAdmin"
                            :href="route('temperature.index')" :active="route().current('temperature.index')">
                            Temperature
                        </breeze-responsive-nav-link>
                    </div>

                    <!-- Responsive Settings Options -->
                    <div class="pt-4 pb-1 border-t border-gray-200">
                        <div class="px-4">
                            <div class="font-medium text-base text-gray-800">{{ $page.props.auth.user.name }}</div>
                            <div class="font-medium text-sm text-gray-500">{{ $page.props.auth.user.email }}</div>
                        </div>

                        <div class="mt-3 space-y-1">
                            <breeze-responsive-nav-link :href="route('logout')" method="post" as="button">
                                Log Out
                            </breeze-responsive-nav-link>
                        </div>
                    </div>
                </div>
            </nav>

            <portal-target name="notification"/>
            <!-- Page Content -->
            <main class="flex-1">
                <slot />
            </main>

            <!-- <page-footer /> -->
        </div>
    </div>
</template>

<script>
    import BreezeApplicationLogo from '@/Components/ApplicationLogo'
    import BreezeDropdown from '@/Components/Dropdown'
    import BreezeDropdownLink from '@/Components/DropdownLink'
    import BreezeNavLink from '@/Components/NavLink'
    import BreezeResponsiveNavLink from '@/Components/ResponsiveNavLink'
    import NotificationDropdown from '@/Components/Notifications/Dropdown'
    import PageFooter from '@/Layouts/Partials/Footer'
    import PageNavigation from '@/Layouts/Partials/Navigation'
    import { HomeIcon, FireIcon, AcademicCapIcon, CalendarIcon, CreditCardIcon, UsersIcon, ChevronDownIcon, ChipIcon } from '@heroicons/vue/outline'
    export default {
        components: {
            BreezeApplicationLogo,
            BreezeDropdown,
            BreezeDropdownLink,
            BreezeNavLink,
            BreezeResponsiveNavLink,
            PageFooter,
            NotificationDropdown,
            PageNavigation,
            HomeIcon,
            FireIcon,
            AcademicCapIcon,
            CalendarIcon,
            CreditCardIcon,
            UsersIcon,
            ChevronDownIcon,
            ChipIcon
        },

        data() {
            return {
                showingNavigationDropdown: false,
                isAdmin: process.env.MIX_ADMINS.includes(this.$page.props.auth.user.email)
            }
        },
    }
</script>
