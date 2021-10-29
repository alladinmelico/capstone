<template>
    <inertia-link :href="href" class="p-4 w-full inline-flex items-center text-sm font-medium leading-5 rounded-md" :class="classes">
        <slot />
    </inertia-link>
</template>

<script>
    export default {
        props: ['href', 'active'],

        computed: {
            classes() {
                return this.active
                    ? 'border-primary-light bg-secondary  text-white focus:outline-none focus:border-primary-light transition duration-150 ease-in-out'
                    : 'border-transparent hover:bg-secondary text-gray-100 hover:text-gray-50 hover:border-gray-300 focus:outline-none focus:text-gray-100 focus:border-gray-300 transition duration-150 ease-in-out'
            }
        }
    }
</script>
