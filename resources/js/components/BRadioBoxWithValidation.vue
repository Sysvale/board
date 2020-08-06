<template>
	<ValidationProvider :name="label" :rules="rules">
		<b-form-group slot-scope="{ valid, errors }" v-bind="$attrs">
			<b-form-radio-group
				v-model="innerValue"
				:state="errors[0] ? false : null"
				stacked
			>
				<slot />
			</b-form-radio-group>

			<b-form-invalid-feedback>
				{{ errors[0] }}
			</b-form-invalid-feedback>
		</b-form-group>
	</ValidationProvider>
</template>


<script>
import { ValidationProvider } from "vee-validate";

export default {
	components: {
		ValidationProvider
	},
	props: {
		vid: {
			type: String
		},
		rules: {
			type: [Object, String],
			default: {}
		},
		// must be included in props
		value: {
			type: null
		}
	},
	data: () => ({
		innerValue: '',
	}),

	computed: {
		label() {
			return this.$attrs.name ? this.$attrs.name : this.$attrs.label;
		},
	},

	watch: {
		// Handles internal model changes.
		innerValue (newVal) {
			this.$emit('input', newVal);
		},
		// Handles external model changes.
		value (newVal) {
			this.innerValue = newVal;
		}
	},
	created () {
		if (this.value) {
			this.innerValue = this.value;
		}
	}
};
</script>
