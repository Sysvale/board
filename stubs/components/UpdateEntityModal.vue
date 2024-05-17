<template>
	<cuids-request-modal
		v-model="internalShowModal"
		title="Editar entidade"
		v-bind="$attrs"
		:request-props="requestProps"
		@save="handleSave"
	>
		<entity-form
			ref="entityForm"
			v-model="entity"
			:disabled="requestProps.loading"
		/>
	</cuids-request-modal>
</template>
<script>
import EntityForm from './EntityForm.vue';
import { Entity } from '../../../shared/domain/entity';
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
		EntityForm,
		CuidsRequestModal,
	},
	data() {
		return  {
			internalShowModal: this.modelValue,
			entity: {},
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
			Object.assign(this.entity, newValue);
		},
	},

	methods: {
		handleSave(callback) {
			this.$refs
				.entityForm
				.$refs
				.entityFormValidationProvider.validate()
				.then((result) => {
					if(result.valid) {
						const entityClassInstance = new Entity(this.entity);
						callback(entityClassInstance.asRequestPayload());
					}
				});
		},
	},
};
</script>