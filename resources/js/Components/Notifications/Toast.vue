<template>
  <portal to="notification">
    <transition name="slide-fade">
      <inertia-link v-if="notification" :href="`/notifications/${notification.id}`" class="fixed text-sm top-20 right-4">
        <div class="flex w-full max-w-lg mx-auto overflow-hidden bg-white rounded-lg shadow-md dark:bg-gray-800 ">
            <div class="flex items-center justify-center w-12" :class="getColor">
                <bell-icon class="w-6 h-6 text-white" />
            </div>

            <div class="px-4 py-2 -mx-3">
                <div class="mx-3">
                    <span class="font-semibold text-green-500 dark:text-green-400">{{ typeStartCased }}</span>
                    <p class="text-sm text-gray-600 dark:text-gray-200">{{ notification.message }}</p>
                </div>
            </div>
        </div>
      </inertia-link>
    </transition>
  </portal>
</template>

<script>
import _ from 'lodash'
import {BellIcon} from '@heroicons/vue/outline'

export default {
  components: {
    BellIcon
  },

  props: {
    notification: {
      type: Object,
      default () {
        return null
      },
    },
  },

  computed: {
    typeStartCased() {
        return _.startCase(this.notification.type)
    },
    getColor() {
        switch (this.notification.type) {
            case 'success':
                return 'bg-green-400'
            case 'notice':
                return 'bg-blue-400'
            case 'warning':
                return 'bg-yellow-400'
            case 'danger':
                return 'bg-red-400'
            default:
                return 'bg-gray-400';
        }
    }
  }
}
</script>

<style scoped>
/* Enter and leave animations can use different */
/* durations and timing functions.              */
.slide-fade-enter-active {
  transition: all .3s ease;
}
.slide-fade-leave-active {
  transition: all .8s cubic-bezier(1.0, 0.5, 0.8, 1.0);
}
.slide-fade-enter, .slide-fade-leave-to
/* .slide-fade-leave-active below version 2.1.8 */ {
  transform: translateX(10px);
  opacity: 0;
}
</style>
