<template>
	<show-request-provider
		v-slot="{ data: options, loading }"
		:initial-data="[]"
		:service="service"
		:options="options"
		immediate
	>
		<div v-if="loading || !options || !options.length">
			Carregando...
		</div>
		<cds-multiselect
			v-else
			v-bind="$attrs"
			v-model="selectedValue"
			:options="options"
		/>
	</show-request-provider>
</template>
<script>
export default {
	props: {
		modelValue: {
			type: Array,
			default: () => [],
		},
		service: {
			type: Promise,
			required: true,
		}
	},

	data() {
		return {
			selectedValue: this.modelValue,
		};
	},

	watch: {
		selectedValue() {
			this.$emit('update:modelValue', this.selectedValue);
		},

		modelValue(newValue) {
			this.selectedValue = newValue;
		},
	}
}
</script>