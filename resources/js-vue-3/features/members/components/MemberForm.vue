<template>
	<Form
		ref="memberFormValidationProvider"
	>
		<Field
			v-slot="{
				field,
				errors
			}"
			name="email"
			label="e-mail"
			rules="required|email"
		>
			<cds-text-input
				v-bind="field"
				v-model="member.email"
				:error-message="errors[0]"
				:state="errors[0] ? 'invalid' : 'default'"
				placeholder="Digite o e-mail"
				label="E-mail"
				:disabled="disabled"
				required
				fluid
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
			name="name"
			label="nome"
			rules="required|min:3"
		>
			<cds-text-input
				v-bind="field"
				v-model="member.name"
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
				v-model="member.teams"
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
		<Field
			v-slot="{
				field,
				errors
			}"
			name="avatarUrl"
			label="avatar"
		>
			<cds-text-input
				v-bind="field"
				v-model="member.avatarUrl"
				:error-message="errors[0]"
				:state="errors[0] ? 'invalid' : 'default'"
				placeholder="Url de uma imagem"
				label="Url do avatar"
				:disabled="disabled"
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
import { TeamService } from '../../../shared/domain/team';
import EntityMultiselect from '../../../core/components/EntityMultiselect.vue';
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
			member: this.modelValue,
			TeamService,
		};
	},

	watch: {
		member(newValue) {
			this.$emit('update:modelValue', newValue);
		},
		modelValue(newValue) {
			this.member = newValue;
		}
	},

	mounted() {
		this.$refs.memberFormValidationProvider.resetForm({
			values: this.modelValue,
		});
	},
}
</script>