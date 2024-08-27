<template>
	<cuids-request-modal
		v-model="internalShowModal"
		title="Adicionar time"
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
	},
	components: {
		TeamForm,
		CuidsRequestModal,
	},
	data() {
		return  {
			internalShowModal: this.modelValue,
			team: {
				boardLists: [
					{
						position: 0,
						name: 'To Do',
					},
					{
						position: 1,
						name: 'Doing',
					},
					{
						position: 2,
						name: 'Done',
					},
				]
			},
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
				.teamForm
				.$refs
				.teamFormValidationProvider.validate()
				.then((result) => {
					if(result.valid) {
						callback((new Team(this.team)).asRequestPayload());
					}
				});
		},
	},
};
</script>
