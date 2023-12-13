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
			<cds-multiselect
				v-bind="field"
				v-model="member.teams"
				:options="teams"
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
	},

	data() {
		return {
			member: this.modelValue,
			teams: [
				{
					"id": "65024ee3aebd961f6b73bab2",
					"name": "Time 1",
					"extended_task_flow": null,
					"key": "65024ee3aebd961f6b73bab2",
					"workspace_id": "65024f1c61902e29d869db42",
					"board_lists": [
						{
							"name": "todo",
							"position": 0,
							"team_id": "65024ee3aebd961f6b73bab2",
							"accepts_card_type": "task",
							"updated_at": "2023-09-14T00:08:03.513000Z",
							"created_at": "2023-09-14T00:08:03.513000Z",
							"id": "65024ee3aebd961f6b73bab5"
						},
						{
							"name": "doing",
							"position": 1,
							"team_id": "65024ee3aebd961f6b73bab2",
							"accepts_card_type": "task",
							"updated_at": "2023-09-27T12:22:34.542000Z",
							"created_at": "2023-09-27T12:22:34.542000Z",
							"id": "65141e8a6f8cc600cf21b462"
						}
					]
				},
				{
					"id": "65024eefd066ac60ee58e112",
					"name": "Time 2",
					"extended_task_flow": null,
					"key": "65024eefd066ac60ee58e112",
					"workspace_id": "65024f26846d5a06ed384532",
					"board_lists": [
						{
							"name": "todo",
							"position": 0,
							"team_id": "65024eefd066ac60ee58e112",
							"accepts_card_type": "task",
							"updated_at": "2023-09-14T00:08:15.505000Z",
							"created_at": "2023-09-14T00:08:15.505000Z",
							"id": "65024eefd066ac60ee58e115"
						}
					]
				},
				{
					"id": "65024efbab547525d04ab7c2",
					"name": "Time A",
					"extended_task_flow": null,
					"key": "65024efbab547525d04ab7c2",
					"workspace_id": "65024f2e0392ae7f5b086122",
					"board_lists": [
						{
							"name": "todo",
							"position": 0,
							"team_id": "65024efbab547525d04ab7c2",
							"accepts_card_type": "task",
							"updated_at": "2023-09-14T00:08:27.824000Z",
							"created_at": "2023-09-14T00:08:27.824000Z",
							"id": "65024efbab547525d04ab7c5"
						}
					]
				},
				{
					"id": "65024f06c27c6867f33814e2",
					"name": "Time B",
					"extended_task_flow": null,
					"key": "65024f06c27c6867f33814e2",
					"workspace_id": "65024f369747c65b21740c32",
					"board_lists": [
						{
							"name": "todo",
							"position": 0,
							"team_id": "65024f06c27c6867f33814e2",
							"accepts_card_type": "task",
							"updated_at": "2023-09-14T00:08:38.364000Z",
							"created_at": "2023-09-14T00:08:38.364000Z",
							"id": "65024f06c27c6867f33814e5"
						}
					]
				}
			]
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