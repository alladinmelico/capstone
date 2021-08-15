<template>
	<form-input v-bind="{ label, name, required, error }">
		<template #default="{ computedName }">
			<input-datepicker
				:id="computedName"
				:label="computedName"
				v-model="localValue"
				v-bind="{ required }"
			/>
		</template>
	</form-input>
</template>

<script>
import InputDatepicker from '@/Components/Datepicker'
import HasLocalValue from '@/Mixins/HasLocalValue'
import FormInput from '@/Components/FormInput'
import FormInputMixin from '@/Mixins/FormInput'

export default {
	components: {
		InputDatepicker,
		FormInput,
	},

	mixins: [FormInputMixin, HasLocalValue],
}
</script>
