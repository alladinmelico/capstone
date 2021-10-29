<template>
    <div class="relative">
        <div @click="open = ! open" class="inline-flex rounded-md p-4 w-full text-sm hover:bg-secondary font-medium leading-5 cursor-pointer text-gray-50 hover:text-white focus:outline-none focus:text-white focus:bg-secondary transition duration-150 ease-in-out">
            <slot name="trigger" />
        </div>

        <transition
            enter-active-class="transition ease-out duration-200"
            enter-from-class="transform opacity-0 scale-95"
            enter-to-class="transform opacity-100 scale-100"
            leave-active-class="transition ease-in duration-75"
            leave-from-class="transform opacity-100 scale-100"
            leave-to-class="transform opacity-0 scale-95">
            <ul v-show="open"
                class="ml-5 rounded-md"
                style="display: none;"
                @click="open = false">
                <slot name="content" />
            </ul>
        </transition>
    </div>
</template>

<script>
import { onMounted, onUnmounted, ref } from "vue";

export default {
    setup() {
        let open = ref(false)

        const closeOnEscape = (e) => {
            if (open.value && e.keyCode === 27) {
                open.value = false
            }
        }

        onMounted(() => document.addEventListener('keydown', closeOnEscape))
        onUnmounted(() => document.removeEventListener('keydown', closeOnEscape))

        return {
            open,
        }
    },
}
</script>
