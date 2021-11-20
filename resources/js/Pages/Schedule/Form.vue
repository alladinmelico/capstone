<template>
  <breeze-authenticated-layout>
    <template #header>
      <h2 class="font-semibold text-xl text-gray-800 leading-tight">
        <InertiaLink href="/schedule" class="underline">Schedule</InertiaLink>
        <chevron-double-right-icon
          class="inline-block h-5 w-5 text-primary mx-2"
        />
        {{ item ? 'Update Schedule' : 'Create New Schedule' }}
      </h2>
    </template>
    <div class="py-10 mx-auto max-w-7xl sm:px-6 lg:px-8 shadow-none">
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
            @click="activeStep = step.isDone ? index : activeStep"
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
                index === activeStep || step.isDone
                  ? 'text-white'
                  : 'text-gray-400'
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
      <JetFormSection @submitted="save">
        <template #form>
          <div
            v-if="activeStep === 0"
            class="col-span-6 grid grid-cols-6 gap-4  mb-8"
          >
            <div class="col-span-6 flex justify-around">
                <div v-for="(type, index) in purposes"
                    :key="index"
                    class="relative p-2 w-32 h-32 rounded-lg text-center cursor-pointer shadow-md"
                    :class="{ 'bg-secondary text-white shadow-lg' : purpose === index }"
                    @click="purpose = index"
                >
                    <img :src="`/storage/${ type.image }.svg`" :alt="type.name" class="-mt-8">
                    <p class="absolute bottom-0 inset-x-0 mb-2">{{ type.name }}</p>
                </div>
            </div>
            <label class="col-span-3 flex items-center">
              <breeze-checkbox
                name="has_gclassroom"
                v-model:checked="hasGClassroom"
              />
              <span class="ml-2 text-sm text-gray-600"
                >Select from Google Classroom</span
              >
            </label>
            <FormSelect
              v-model="form.google_classroom_id"
              :options="classes"
              class="col-span-6"
              :error="form.errors.google_classroom_id"
              :disabled="!hasGClassroom"
            />
            <FormInput
              v-model="form.name"
              class="col-span-3"
              :error="form.errors.name"
              label="Name"
              required
            />
            <FormInput
              v-model="form.description_heading"
              class="col-span-3"
              :error="form.errors.description_heading"
              label="Description Heading"
              required
            />
            <FormInput
              v-model="form.description"
              class="col-span-3"
              :error="form.errors.description"
              label="Description"
              required
            />
            <FormInput
              v-model="form.section"
              class="col-span-3"
              :error="form.errors.section"
              label="Section"
              :disabled="!!section"
              required
            />
          </div>
          <div
            v-else-if="activeStep === 1"
            class="col-span-6 grid grid-cols-6 gap-4"
          >
            <div class="col-span-6 flex justify-around">
                <div v-for="(type, index) in facilityTypes"
                    :key="index"
                    class="relative p-2 w-32 h-32 rounded-lg text-center cursor-pointer shadow-md"
                    :class="{ 'bg-secondary text-white shadow-lg' : facilityType === index }"
                    @click="facilityType = index"
                >
                    <img :src="`/storage/${ type.image }.svg`" :alt="type.name" class="-mt-8">
                    <p class="absolute bottom-0 inset-x-0 mb-2">{{ type.name }}</p>
                </div>
            </div>
            <FormInput
              v-model="form.start_at"
              :max="form.end_at"
              type="time"
              class="col-span-3"
              :error="form.errors.start_at"
              label="Start Time"
              required
            />
            <FormInput
              v-model="form.end_at"
              :min="form.start_at"
              type="time"
              class="col-span-3"
              :error="form.errors.end_at"
              label="End Time"
              required
            />
            <FormSelect
              v-model="form.day"
              :options="days"
              class="col-span-3"
              :error="form.errors.day"
              label="Day"
              required
            />
            <FormDatepicker
              v-model="form.valid_until"
              type="date"
              class="col-span-3"
              :error="form.errors.valid_until"
              label="Valid Schedule after:"
              required
            />
            <FormSelect
              v-if="!!form.valid_until"
              v-model="form.facility_id"
              :options="facilities"
              class="col-span-3"
              :error="form.errors.facility_id"
              label="Facility"
              required
            />
            <FormSelect
              v-model="form.subject_id"
              :options="subjects"
              class="col-span-3"
              :error="form.errors.subject_id"
              label="Subject"
            />
            <FormInput
              v-model="facilityCapacity"
              class="col-span-3"
              label="Facility Capacity"
              disabled
            />
          </div>
          <div
            v-else-if="activeStep === 2"
            class="col-span-6 grid grid-cols-6 gap-4"
          >
            <div class="col-span-6 align-items-end">
                <switch-component v-model:checked="peopleInvolved" label="People Involved" />
            </div>
            <div v-if="peopleInvolved" class="col-span-6 grid grid-cols-6 gap-4">
              <FormSelect
                v-model="form.classroom_id"
                :options="classes"
                class="col-span-3"
                :error="form.errors.classroom_id"
                label="Classroom"
              />
              <FormInput
                v-model="facilityCapacity"
                class="col-span-3"
                label="Facility Capacity"
                disabled
              />
              <div class="col-span-6 mt-8">
                  <SimpleTable
                    title="List of People Involved"
                    :items="involvedPeople"
                    :headers="involvedPeopleHeaders"
                    :column-keys="involvedPeopleColumnKeys"
                    create-modal
                    remove-only
                    @openModal="isAddingPeople = true"
                    @removeItem="removePerson"
                  />
                  <SimpleModal
                      :show="isAddingPeople"
                      @close="isAddingPeople = false"
                  >
                    <template #title>
                        Invite
                    </template>
                    <template #content>
                        <div class="w-full">
                            <FormInput
                                v-model="searchUser"
                                class="col-span-3"
                                label="TUP Email"
                                type="text"
                            />
                            <ul v-if="filteredUsers.length > 0" class="h-full overflow-scroll m-4 mx-auto w-full">
                                <li v-for="user in filteredUsers" :key="user.id" @click="searchUser = user.email" class="cursor-pointer my-2">
                                {{ user.name }} <span class="text-sm p-1 rounded-lg ml-2 bg-gray-200 text-secondary-dark">{{ user.email }}</span>
                                </li>
                            </ul>
                        </div>
                    </template>
                    <template #footer>
                        <div>
                            <jet-secondary-button class="mr-4" @click="isAddingPeople = false">Cancel</jet-secondary-button>
                            <jet-button @click="addToPeople">Add</jet-button>
                        </div>
                    </template>
                  </SimpleModal>
              </div>
            </div>
          </div>
          <div
            v-else-if="activeStep === 3"
            class="col-span-6 grid grid-cols-6 gap-4"
          >
            <InputTextArea
              v-model="form.note"
              class="col-span-6"
              :error="form.errors.note"
              label="Note"
              required
            />
            <FormInput
              class="col-span-3"
              :error="form.errors.attachment"
              label="Attachment"
            >
            <div class="col-span-3 flex justify-content-center items-center">
              <div class="w-64 h-28 rounded-lg border-2 border-secondary flex justify-center items-center text-center">
                {{ form.attachment ? form.attachment.name : 'No attachment uploaded' }}
              </div>
              <div class="inline cursor-pointer relative overflow-hidden w-7 h-7 ml-4">
                <input
                  id="attachment"
                  class="block absolute opacity-0 pin-r pin-t w-full"
                  type="file"
                  @change="previewFiles"
                  ref="fileAttachment"
                />
                <document-add-icon class="w-7 h-7 text-secondary"/>
              </div>
            </div>
            </FormInput>
          </div>
           <div
            v-else-if="activeStep === 4"
            class="col-span-6 flex flex-col justify-center items-center"
           >
            <div class="rounded-lg border-2 border-secondary p-4">
                <img :src="'https://api.qrserver.com/v1/create-qr-code/?size=300x300&data=http://localhost:8000/schedule/'+ schedule_id" alt="" srcset="">
            </div>
           </div>
        </template>

        <template #actions>
          <InertiaLink href="/schedule" class="mr-2">
            <JetSecondaryButton class="capitalize text-lg"> Cancel </JetSecondaryButton>
          </InertiaLink>

          <JetButton
            class="capitalize text-lg"
            :class="{ 'opacity-25': form.processing }"
            :disabled="form.processing"
          >
            {{ activeStep === 4 ? 'Done' : activeStep === 3 ? 'Save' : 'Next' }}
          </JetButton>
        </template>
      </JetFormSection>
    </div>
  </breeze-authenticated-layout>
</template>

<script>
import BreezeAuthenticatedLayout from '@/Layouts/Authenticated'
import JetFormSection from '@/Components/FormSection'
import JetButton from '@/Components/Button'
import JetSecondaryButton from '@/Components/SecondaryButton'
import FormDatepicker from '@/Components/FormDatepicker'
import FormInput from '@/Components/FormInput'
import FormTextarea from '@/Components/FormTextarea'
import FormSelect from '@/Components/FormSelect'
import InputTextArea from '@/Components/FormTextArea'
import BreezeCheckbox from '@/Components/Checkbox'
import SwitchComponent from '@/Components/Switch'
import CustomLabel from '@/Components/Label'
import SimpleTable from '@/Components/SimpleTable'
import SimpleModal from '@/Components/SimpleModal'
import { ChevronDoubleRightIcon } from '@heroicons/vue/solid'
import {
  LightBulbIcon,
  LibraryIcon,
  UsersIcon,
  PaperClipIcon,
  CheckIcon,
  DocumentAddIcon
} from '@heroicons/vue/outline'
import { Inertia } from '@inertiajs/inertia'
const dayjs = require('dayjs')

export default {
  components: {
    JetFormSection,
    JetButton,
    JetSecondaryButton,
    FormInput,
    FormTextarea,
    BreezeAuthenticatedLayout,
    FormDatepicker,
    FormSelect,
    InputTextArea,
    CustomLabel,
    BreezeCheckbox,
    SwitchComponent,
    SimpleTable,
    SimpleModal,
    ChevronDoubleRightIcon,
    LightBulbIcon,
    LibraryIcon,
    UsersIcon,
    PaperClipIcon,
    CheckIcon,
    DocumentAddIcon,
  },

  props: {
    item: {
      type: Object,
      required: false,
    },
    facilities: {
      type: Array,
      required: true,
    },
    subjects: {
      type: Array,
      required: true,
    },
    existing_classrooms: {
      type: Array,
      required: true,
      default: [],
    },
    schedule_classroom: {
      type: Object,
      default: {
        google_classroom_id: '',
        subject_id: '',
      },
    },
    token: {
      type: String,
      default: '',
    },
    section: {
      type: String,
      default: '',
    },
    users: {
      type: Array,
      default: []
    }
  },
  mounted() {
    this.getClasses()
  },
  data() {
    const item = this.item
    if (item) {
      item.valid_until = new Date(item.valid_until)
    }
    return {
      form: this.$inertia.form({
        _method: item ? 'PUT' : 'POST',
        name: '',
        description_heading: '',
        description: '',
        section: this.section,
        start_at: '',
        end_at: '',
        day: '',
        valid_until: new Date(),
        note: '',
        facility_id: null,
        attachment: null,
        classroom_id: null,
        google_classroom_id:
          this.schedule_classroom && this.schedule_classroom.google_classroom_id
            ? this.schedule_classroom.google_classroom_id
            : null,
        subject_id:
          this.schedule_classroom && this.schedule_classroom.subject_id
            ? this.schedule_classroom.subject_id
            : null,
        user_id: this.$page.props.auth.user.id,
        ...item,
      }),
      days: [
        {
          id: 'monday',
          name: 'Monday',
        },
        {
          id: 'tuesday',
          name: 'Tuesday',
        },
        {
          id: 'wednesday',
          name: 'Wednesday',
        },
        {
          id: 'thursday',
          name: 'Thursday',
        },
        {
          id: 'friday',
          name: 'Friday',
        },
        {
          id: 'saturday',
          name: 'Saturday',
        },
      ],
      classes: [],
      hasGClassroom: false,
      activeStep: 0,
      steps: [
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
      facilityCapacity: 10,
      facilityType: 0,
      facilityTypes: [
        {
            name: 'Classroom',
            image: 'professor'
        },
        {
            name: 'Working',
            image: 'working'
        },
        {
            name: 'Others',
            image: 'more'
        }
      ],
      purpose: 0,
      purposes: [
        {
            name: 'Create a schedule for a whole class',
            image: 'learning'
        },
        {
            name: 'Course Subject Related',
            image: 'education'
        },
        {
            name: 'Personal Visit',
            image: 'location'
        }
      ],
      peopleInvolved: false,
      isAddingPeople: false,
      involvedPeople: [],
      involvedPeopleHeaders: [
          'Photo',
          'TUPT ID',
          'Name',
          'Actions',
      ],
      involvedPeopleColumnKeys: [
          'avatar',
          'school_id',
          'name',
      ],
      searchUser: '',
      filteredUsers: [],
      schedule_id: null
    }
  },

  computed: {
    isEditing() {
      return !!this.item?.id
    },
    gClassrooms() {
      // this.classes.map(() => )
    },
    selectedClassroom() {
      return this.classes.find((cl) => this.form.google_classroom_id == cl.id)
    },
    section() {
      return this.$page.props.auth.user.name
    },
  },

  methods: {
    save() {
      if (this.activeStep !== 3) {
        this.steps[this.activeStep].isDone = true
        this.activeStep++
        return
      }
      this.form.valid_until = dayjs(this.form.valid_until).format(
        'YYYY-MM-D hh:mm:ss'
      )
      const url = this.isEditing ? `/schedule/${this.item.id}` : '/schedule'
      this.form.post(url, {
        onSuccess: (response) => {
            this.activeStep++
            console.log(response)
        }
      })
    },
    async getClasses() {
      const axios = window.axios
      this.classes = []
      const response = await axios
        .get('https://classroom.googleapis.com/v1/courses', {
          params: {
            access_token: this.token,
          },
        })
        .catch(function (error) {
          if (error.response.status === 401) {
            axios.post('/logout')
            window.location.reload()
          }
        })
      this.classes = response.data.courses
      console.log(this.classes)
    },
    previewFiles() {
        this.form.attachment = this.$refs.fileAttachment.files[0]
    },
    addToPeople(){
        this.involvedPeople.push(this.filteredUsers[0])
        this.isAddingPeople = false
        this.searchUser = ''
    },
    removePerson(id){
        this.involvedPeople = this.involvedPeople.filter(person => person.id !== id)
    }
  },

  watch: {
    'form.google_classroom_id'() {
      this.form.name = this.selectedClassroom.name
      this.form.description = this.selectedClassroom.description
      this.form.description_heading = this.selectedClassroom.descriptionHeading
    },
    hasGClassroom(value) {
      if (!value) {
        this.form.name = ''
        this.form.description = ''
        this.form.description_heading = ''
        this.form.section = ''
      }
    },
    searchUser(value){
      this.filteredUsers = []
      if(value.length > 1){
        this.filteredUsers = this.users.filter(user => user.email.includes(value.toLowerCase()))
      }
      console.log(this.filteredUsers)
    }
  },
}
</script>
