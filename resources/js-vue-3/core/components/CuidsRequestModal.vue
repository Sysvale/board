<template>
	<cds-modal
		v-model="internalShowModal"
		no-close-on-backdrop
		no-close-button
		no-close-ok-button
		action-button-variant="indigo"
		v-bind="$attrs"
		:disable-ok-button="disabled || requestProps.loading"
		:disable-cancel-button="disabled || requestProps.loading"
		:ok-button-text="requestProps.loading ? 'Carregando...' : 'Salvar'"
		@ok="handleOk"
		@close="handleClose"
	>
		<div>
			<slot/>
			<cds-alert
				v-if="!requestProps.loading && requestProps.failed"
				variant="danger"
				:text="requestProps.errorMessage"
			/>
		</div>
	</cds-modal>
</template>
<script>
export default {
	props: {
		modelValue: {
			type: Boolean,
			required: true,
		},
		disabled: {
			type: Boolean,
			default: false,
		},
		requestProps: {
			type: Object,
			default: () => ({}),
		},
	},
	data() {
		return  {
			internalShowModal: this.modelValue,
			showSlotController: false,
		};
	},

	watch: {
		internalShowModal(newValue, oldValue) {
			if(newValue !== oldValue) {
				this.$emit('update:modelValue', newValue);
			}
		},
		modelValue(newValue) {
			this.internalShowModal = newValue;
		},
	},

	methods: {
		handleOk() {
			this.$emit('save', this.requestProps.action);
		},
	},
};
</script>