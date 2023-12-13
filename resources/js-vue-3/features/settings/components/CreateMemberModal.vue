<template>
	<cuids-request-modal
		v-model="internalShowModal"
		title="Adicionar membro"
		v-bind="$attrs"
		:request-props="requestProps"
		@save="handleSave"
	>
		<member-form
			ref="memberForm"
			v-model="member"
			:disabled="requestProps.loading"
		/>
	</cuids-request-modal>
</template>
<script>
import MemberForm from './MemberForm.vue';
import { Member } from '../../../shared/domain/member';
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
		MemberForm,
		CuidsRequestModal,
	},
	data() {
		return  {
			internalShowModal: this.modelValue,
			member: {
				teams: [],
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
				.memberForm
				.$refs
				.memberFormValidationProvider.validate()
				.then((result) => {
					if(result.valid) {
						callback((new Member(this.member)).asRequestPayload());
					}
				});
		},
	},
};
</script>
