<template>
	<cuids-request-modal
		v-model="internalShowModal"
		title="Editar membro"
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
		selectedItem: {
			type: Object,
			required: true,
		}
	},
	components: {
		MemberForm,
		CuidsRequestModal,
	},
	data() {
		return  {
			internalShowModal: this.modelValue,
			member: {},
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
			Object.assign(this.member, newValue);
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
						const memberClassInstance = new Member(this.member);
						callback(memberClassInstance.asRequestPayload());
					}
				});
		},
	},
};
</script>