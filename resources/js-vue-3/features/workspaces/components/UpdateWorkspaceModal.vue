<template>
	<cuids-request-modal
		v-model="internalShowModal"
		title="Editar entidade"
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
		selectedItem: {
			type: Object,
			required: true,
		}
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
		internalShowModal(newValue) {
			this.$emit('update:modelValue', newValue);
		},
		modelValue(newValue) {
			this.internalShowModal = newValue;
		},
		selectedItem(newValue) {
			Object.assign(this.workspace, newValue);
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
						const workspaceClassInstance = new Workspace(this.workspace);
						callback(workspaceClassInstance.asRequestPayload());
					}
				});
		},
	},
};
</script>