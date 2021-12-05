<template>
  <div v-if="links.length > 3" class="max-w-7xl mx-auto sm:px-6 lg:px-8">
    <div class="flex flex-wrap -mb-1">
      <template v-for="(link, key) in links" :key="key">
        <!-- <div v-if="link.url === null" :key="key" class="mr-1 mb-1 px-4 py-3 text-sm leading-4 text-gray-400 border border-primary-dark rounded" v-html="link.label" /> -->
        <inertia-link v-if="link.label.includes('Previous')" class="mr-1 mb-1 px-4 py-3 text-sm leading-4 border rounded hover:bg-primary border-primary text-primary-dark"  :href="link.url ? link.url : '#'">
            <ArrowLeftIcon class="h-5 w-5 "/>
        </inertia-link>
        <inertia-link v-else-if="link.label.includes('Next')" class="mr-1 mb-1 px-4 py-3 text-sm leading-4 border rounded hover:bg-primary border-primary text-primary-dark"  :href="link.url ? link.url : '#'">
            <ArrowRightIcon class="h-5 w-5 "/>
        </inertia-link>
        <inertia-link v-else class="mr-1 mb-1 px-4 py-3 text-sm leading-4 border rounded hover:bg-primary-dark focus:border-primary-dark focus:text-indigo-500 bg-gray-50" :class="{ 'border-primary': link.active }" :href="link.url" v-html="link.label" />
      </template>
    </div>
  </div>
</template>

<script>
import { ArrowLeftIcon, ArrowRightIcon } from '@heroicons/vue/outline'

export default {
  components: {
    ArrowLeftIcon,
    ArrowRightIcon
  },
  props: {
    links: {
        type: Array,
        default: []
    },
  },
}
</script>
