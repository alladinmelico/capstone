<template>
    <div class="min-h-screen flex flex-col sm:justify-center items-center pt-6 sm:pt-0 bg-gray-100">
        <div class="w-full sm:max-w-xl md:max-w-3xl mt-6 px-6 py-4 bg-white shadow-md overflow-hidden sm:rounded-lg">
            <slot />
        </div>
    </div>
    <page-footer />
</template>

<script>
    import PageFooter from '@/Layouts/Partials/Footer'

    export default {
        components: {
            PageFooter,
        },
    }
</script>
