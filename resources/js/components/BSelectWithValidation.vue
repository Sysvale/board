<template>
	<ValidationProvider
		:vid="vid"
		:name="label"
		:rules="rules"
	>
		<b-form-group
			slot-scope="{ errors }"
			v-bind="attrsValidate"
			:description="labelDescription"
		>
			<b-form-select
				v-model="innerValue"
				v-bind="$attrs"
				v-on="$listeners"
				:state="errors[0] ? false : null"
			>
			</b-form-select>
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
			type: String,
		},
		rules: {
			type: [Object, String],
			default: '',
		},
		// must be included in props
		value: {
			type: null,
		},
		showRequiredDescription: {
			type: Boolean,
			default: false,
		},
	},

	data: () => ({
		innerValue: ''
	}),

	computed: {
		attrsValidate() {
			let temp = this.$attrs;
			temp.name = temp['name-label'];
			return this.$attrs;
		},

		label() {
			return this.$attrs.name ? this.$attrs.name : this.$attrs.label;
		},

		labelDescription() {
			if (this.$attrs.description) {
				return this.$attrs.description;
			}

			if (this.showRequiredDescription) {
				return 'Campo obrigatório';
			}

			return '';
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
