<template>
	<cuids-request-modal
		v-model="internalShowModal"
		title="Editar entidade"
		v-bind="$attrs"
		:request-props="requestProps"
		@save="handleSave"
	>
		<team-form
			ref="teamForm"
			v-model="team"
			:disabled="requestProps.loading"
		/>
	</cuids-request-modal>
</template>
<script>
import TeamForm from './TeamForm.vue';
import { Team } from '../../../shared/domain/team';
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
		TeamForm,
		CuidsRequestModal,
	},
	data() {
		return  {
			internalShowModal: this.modelValue,
			team: {},
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
		selectedItem(newValue) {
			Object.assign(this.team, newValue);
		},
	},

	methods: {
		handleSave(callback) {
			this.$refs
				.teamForm
				.$refs
				.teamFormValidationProvider.validate()
				.then((result) => {
					if(result.valid) {
						const teamClassInstance = new Team(this.team);
						callback(teamClassInstance.asRequestPayload());
					}
				});
		},
	},
};
</script>