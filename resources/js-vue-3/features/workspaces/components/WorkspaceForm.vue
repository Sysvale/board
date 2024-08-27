<template>
	<Form
		ref="workspaceFormValidationProvider"
	>
		<Field
			v-slot="{
				field,
				errors
			}"
			name="name"
			label="nome"
			rules="required|min:3"
		>
			<cds-text-input
				v-bind="field"
				v-model="workspace.name"
				:state="errors[0] ? 'invalid' : 'default'"
				:error-message="errors[0]"
				fluid
				placeholder="Digite o nome"
				label="Nome"
				:disabled="disabled"
				required
			/>
		</Field>
		<cds-spacer
			:margin-bottom="4"
		/>
		<Field
			v-slot="{
				field,
				errors
			}"
			name="teams"
			label="times"
			rules="not_empty"
		>
			<entity-multiselect
				v-bind="field"
				v-model="workspace.teams"
				:service="TeamService.index"
				:state="errors[0] ? 'invalid' : 'default'"
				:error-message="errors[0]"
				placeholder="Times dos quais esse membro faz parte"
				label="Times"
				track-by="id"
				options-field="name"
				:disabled="disabled"
				required
				fluid
			/>
		</Field>
		<cds-spacer
			:margin-bottom="4"
		/>
	</Form>
</template>
<script>
import { Form, Field } from 'vee-validate';
import EntityMultiselect from '../../../core/components/EntityMultiselect.vue';
import { TeamService } from '../../../shared/domain/team';
export default {
	props: {
		modelValue: {
			type: Object,
			required: true,
		},
		disabled: {
			type: Boolean,
			default: false,
		},
	},

	components: {
		// eslint-disable-next-line vue/no-reserved-component-names
		Form,
		Field,
		EntityMultiselect,
	},

	data() {
		return {
			workspace: this.modelValue,
			TeamService,
		};
	},

	watch: {
		workspace(newValue) {
			this.$emit('update:modelValue', newValue);
		},
		modelValue(newValue) {
			this.workspace = newValue;
		}
	},

	mounted() {
		this.$refs.workspaceFormValidationProvider.resetForm({
			values: this.modelValue,
		});
	},
}
</script>