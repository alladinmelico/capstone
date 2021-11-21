<template>
  <div class="flex justify-center items-center mb-8">
    <div
      v-for="(step, index) in steps"
      :key="index"
      class="flex justify-center items-center"
    >
      <div
        class="rounded-lg shadow-md cursor-pointer p-4 bg-gray-50"
        :class="{
          'p-6 bg-secondary': index === activeStep,
          'bg-secondary': step.isDone,
        }"
        @click="$emit('changed', activeStep = step.isDone ? index : activeStep)"
      >
        <component
          :is="step.icon"
          class="inline h-5 w-5 mr-2"
          :class="
            index === activeStep || step.isDone
              ? 'fill-current text-white'
              : 'text-gray-400'
          "
        />
        <p
          class="inline"
          :class="
            index === activeStep || step.isDone ? 'text-white' : 'text-gray-400'
          "
        >
          {{ step.name }}
        </p>
      </div>
      <span
        v-if="index !== 4"
        class="h-1 w-14"
        :class="step.isDone ? 'bg-secondary' : 'bg-gray-300'"
      />
    </div>
  </div>
</template>

<script>
import {
  LightBulbIcon,
  LibraryIcon,
  UsersIcon,
  PaperClipIcon,
  CheckIcon,
} from '@heroicons/vue/outline'

export default {
    components: {
        LightBulbIcon,
        LibraryIcon,
        UsersIcon,
        PaperClipIcon,
        CheckIcon,
    },

    props: {
        activeStep: {
            type: Number,
            default: 0
        },
        steps: {
            type: Array,
            default: [
                {
                name: 'Purpose',
                icon: 'light-bulb-icon',
                isDone: false,
                },
                {
                name: 'Facility',
                icon: 'library-icon',
                isDone: false,
                },
                {
                name: 'People Involved',
                icon: 'users-icon',
                isDone: false,
                },
                {
                name: 'Notes & Attachments',
                icon: 'paper-clip-icon',
                isDone: false,
                },
                {
                name: 'Finish',
                icon: 'check-icon',
                isDone: false,
                },
            ],
        }
    },
}
</script>

<style></style>
