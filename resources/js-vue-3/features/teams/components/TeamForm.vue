<template>
	<Form
		ref="teamFormValidationProvider"
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
				v-model="team.name"
				:state="errors[0] ? 'invalid' : 'default'"
				:error-message="errors[0]"
				placeholder="Digite o nome"
				label="Nome"
				:disabled="disabled"
				required
			/>
		</Field>
	</Form>
	<label>
		Listas de trabalho:
	</label>
	<small>Clique duas vezes para editar</small>
	<div class="board-list-container">
		<div
			v-for="boardList in team.boardLists"
		>
			<cds-box
				v-if="!boardList['toggleEdit']"	
				@dblclick="boardList['toggleEdit'] = !boardList['toggleEdit']"
			>
				{{ boardList.name }}
			</cds-box>
			<cds-box
				v-else
				variant="amber"
			>
				<span class="board-list-edit-item">
					<cds-text-input
						v-model="boardList.name"
						label=""
						fluid
					/>
					<cds-icon-button
						icon="check-outline"
						@click="boardList['toggleEdit'] = !boardList['toggleEdit']"
					/>
					<cds-icon-button
						icon="trash-outline"
						@click="boardList['toggleEdit'] = !boardList['toggleEdit']"
					/>
				</span>
			</cds-box>
		</div>
		
		<span v-if="createBoardList">
			<cds-box
				variant="green"
			>
				<span class="board-list-edit-item">
					<cds-text-input
						v-model="newBoardList"
						label=""
						fluid
					/>
					<cds-icon-button
						icon="check-outline"
						@click="addBoardList"
					/>
				</span>
			</cds-box>
		</span>
		<cds-icon-button
			size="sm"
			icon="plus-outline"
			:tooltip-text="'Excluir'"
			@click="createBoardList = true"
		/>
	</div>
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
			team: this.modelValue,
			newBoardList: '',
			createBoardList: false,
		};
	},

	watch: {
		team(newValue) {
			this.$emit('update:modelValue', newValue);
		},
		modelValue(newValue) {
			this.team = newValue;
		}
	},

	mounted() {
		this.$refs.teamFormValidationProvider.resetForm({
			values: this.modelValue,
		});
	},

	methods: {
		addBoardList() {
			this.team.boardLists.push({
				name: this.newBoardList,
				position: this.team.boardLists.length,
			});
			this.createBoardList = false;
		}
	},
}
</script>
<style lang="scss">

.board-list-container {
	display: flex;
	flex-direction: row;
	flex-wrap: wrap;
	gap: 10px;
	margin-top: 10px;
}


.board-list-edit-item {
	display: flex;
	flex-direction: row;
	gap: 10px;
	align-items: center;
}
</style>