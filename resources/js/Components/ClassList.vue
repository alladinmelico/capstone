<template>
  <div class="grid grid-cols-1 gap-1 sm:grid-cols-2 md:grid-cols-3 lg:grid-cols-4">
    <div v-for="item in classes" :key="item.id" class="bg-red-100 p-4 rounded-lg" @click="getStudents(item.id)">
        <p>{{ item.name }}</p>
        <p>{{ item.descriptionHeading }}</p>
        <ul v-if="item.id === selectedClass">
            <li v-for="student in students" :key="student.userId">
                <p>{{ student.profile.name.fullName }}</p>
            </li>
        </ul>
    </div>
  </div>
</template>

<script>
export default {
  props: {
    token: '',
  },
  data() {
    return {
      classes: [],
      selectedClass: '',
      students: []
    }
  },
  mounted() {
    this.getClasses()
  },
  methods: {
    async getClasses() {
      const axios = window.axios
      const response = await axios.get(
        'https://classroom.googleapis.com/v1/courses',
        {
          params: {
            access_token: this.token,
          },
        }
      )
      this.classes = response.data.courses
    },
    async getStudents(classId) {
      if(classId !== this.selectedClass){
          this.students = []
          const axios = window.axios
          this.selectedClass = classId
          const response = await axios.get(
            `https://classroom.googleapis.com/v1/courses/${classId}/students`,
            {
              params: {
                access_token: this.token,
              },
            }
          )
          console.log(response)
          this.students = response.data.students
      }
    },
  },
}
</script>

<style></style>
