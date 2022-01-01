<template>
  <breeze-authenticated-layout>
    <template #header>
      <h2 class="font-semibold text-xl text-gray-800 leading-tight">
        Dashboard
      </h2>
    </template>

    <div class="py-12">
      <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
        <div>
          <input
            id="date-select"
            type="date"
            name="date-select"
            class="
              p-4
              rounded-md
              bg-secondary-dark
              text-white
              border border-secondary
              mb-4
            "
          >
        </div>
        <div class="grid grid-cols-3 gap-4">
          <div class="grid grid-cols-2 gap-4">
            <div>
              <div
                class="
                  p-4
                  rounded-md
                  bg-primary
                  border border-primary-dark
                  mb-4
                  shadow-sm
                  hover:shadow-lg
                "
              >
                <UsersIcon
                  class="inline-block h-6 w-6 text-secondary-dark mx-2"
                />
                <p class="text-5xl mb-4 font-bold text-center text-black">
                  {{ number_of_schedules }}
                </p>
                <h2 class="text-xl text-black">Present Students</h2>
              </div>
              <div class="shadow-sm p-4 rounded-md">
                <BarChart :chart-data="testData" :options="options" height="900" />
              </div>
            </div>
            <div>
              <div
                class="
                  p-4
                  rounded-md
                  bg-accent
                  border border-primary-dark
                  mb-4
                  shadow-sm
                  hover:shadow-lg
                "
              >
                <CalendarIcon
                  class="inline-block h-6 w-6 text-secondary-dark mx-2"
                />
                <p class="text-5xl mb-4 font-bold text-center text-white">
                  {{ number_of_schedules }}
                </p>
                <h2 class="text-xl text-black">Scheduled Students</h2>
              </div>
              <div
                class="
                  p-4
                  rounded-md
                  bg-primary-light
                  border border-primary-dark
                  mb-4
                  shadow-sm
                  hover:shadow-lg
                "
              >
                <ClipboardCheckIcon
                  class="inline-block h-6 w-6 text-secondary-dark mx-2"
                />
                <p class="text-5xl mb-4 font-bold text-center text-black">
                  {{ number_of_schedules }}
                </p>
                <h2 class="text-xl text-black">Total Schedules</h2>
              </div>
              <div
                class="
                  p-4
                  rounded-md
                  bg-accent
                  border border-primary-dark
                  mb-4
                  shadow-sm
                  hover:shadow-lg
                "
              >
                <FireIcon
                  class="inline-block h-6 w-6 text-secondary-dark mx-2"
                />
                <p class="text-5xl mb-4 font-bold text-center text-white">
                  36.6
                </p>
                <h2 class="text-xl text-black">TUPT-18-0073</h2>
                <p class="text-sm text-secondary-dark">Latest Thermal Scan</p>
              </div>
            </div>
          </div>
          <div class="col-span-2" />
        </div>
      </div>
    </div>
  </breeze-authenticated-layout>
</template>

<script>
import BreezeAuthenticatedLayout from '@/Layouts/Authenticated'
import { BarChart } from 'vue-chart-3'
import { Chart, registerables } from 'chart.js'

import {
  UsersIcon,
  CalendarIcon,
  ClipboardCheckIcon,
  FireIcon,
} from '@heroicons/vue/outline'

Chart.register(...registerables)

export default {
  components: {
    BreezeAuthenticatedLayout,
    BarChart,
    UsersIcon,
    CalendarIcon,
    ClipboardCheckIcon,
    FireIcon,
  },
  props: {
    token: {
      type: String,
      default () {
        return ''
      },
    },
    number_of_schedules: {
      type: Number,
      default () {
        return 0
      },
    },
    number_of_users: {
      type: Number,
      default () {
        return 0
      },
    },
  },

  data: () => ({
    loaded: true,
    options: {
      responsive: true,
      plugins: {
        legend: {
          position: 'top',
        },
        title: {
          display: true,
          text: 'Overstayed Students',
        },
      },
    },
    testData: {
      labels: ['Paris', 'Nîmes', 'Toulon', 'Perpignan', 'Autre'],
      datasets: [
        {
          data: [30, 40, 60, 70, 5],
          backgroundColor: ['#77CEFF', '#0079AF', '#123E6B', '#97B0C4', '#A5C8ED'],
        },
      ],
    },
  }),
}
</script>
