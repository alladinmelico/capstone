<template>
  <portal to="notification">
    <transition name="slide-fade">
      <inertia-link v-if="notification" :href="`/notifications/${notification.id}`" class="fixed text-sm top-20 right-4">
        <div class="p-3 bg-white border border-gray-300 rounded-lg shadow-lg">
          <div class="flex flex-row">
            <div class="px-2">
              <font-awesome-icon :icon="['fas', 'bell']" class="text-gray-600" />
            </div>
            <div class="ml-2 mr-6">
              <span class="font-semibold text-gray-600">{{ notification.type }}</span>
              <span class="block text-gray-500">{{ notification.message }}</span>
            </div>
          </div>
        </div>
      </inertia-link>
    </transition>
  </portal>
</template>

<script>
import _ from 'lodash'

export default {
  filters: {
    startCase (value) {
      return _.startCase(value)
    },
  },

  props: {
    notification: {
      type: Object,
      default () {
        return null
      },
    },
  },
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
