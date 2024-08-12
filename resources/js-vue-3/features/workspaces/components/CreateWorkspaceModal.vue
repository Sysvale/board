<template>
	<cuids-request-modal
		v-model="internalShowModal"
		title="Adicionar entidade"
		v-bind="$attrs"
		:request-props="requestProps"
		@save="handleSave"
	>
		<workspace-form
			ref="workspaceForm"
			v-model="workspace"
			:disabled="requestProps.loading"
		/>
	</cuids-request-modal>
</template>
<script>
import WorkspaceForm from './WorkspaceForm.vue';
import { Workspace } from '../../../shared/domain/workspace';
import CuidsRequestModal from '../../../core/components/CuidsRequestModal.vue';
export default {
	props: {
		modelValue: {
			type: Boolean,
			required: true,
		},
		requestProps: {
			type: Object,
			default: () => ({}),
		},
	},
	components: {
		WorkspaceForm,
		CuidsRequestModal,
	},
	data() {
		return  {
			internalShowModal: this.modelValue,
			workspace: {},
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
		handleSave(callback) {
			this.$refs
				.workspaceForm
				.$refs
				.workspaceFormValidationProvider.validate()
				.then((result) => {
					if(result.valid) {
						callback((new Workspace(this.workspace)).asRequestPayload());
					}
				});
		},
	},
};
</script>
