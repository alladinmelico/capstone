<template>
    <div class="max-w-4xl mx-a mt-5 md:mt-0 md:col-span-2 mx-auto">
        <form @submit.prevent="$emit('submitted')">
            <div class="px-4 py-5 bg-white sm:p-6"
                :class="hasActions ? 'sm:rounded-tl-md sm:rounded-tr-md' : 'sm:rounded-md'">
                <jet-section-title>
                    <template #title><slot name="title"></slot></template>
                    <template #description><slot name="description"></slot></template>
                </jet-section-title>
                <div class="grid grid-cols-6 gap-6 mt-8">
                    <slot name="form"></slot>
                </div>
            </div>

            <div class="flex items-center justify-end px-4 py-3 text-right sm:px-6 sm:rounded-bl-md sm:rounded-br-md" v-if="hasActions">
                <slot name="actions"></slot>
            </div>
        </form>
    </div>
</template>

<script>
    import JetSectionTitle from './SectionTitle'

    export default {
        emits: ['submitted'],

        components: {
            JetSectionTitle,
        },

        computed: {
            hasActions() {
                return !! this.$slots.actions
            }
        }
    }
</script>
