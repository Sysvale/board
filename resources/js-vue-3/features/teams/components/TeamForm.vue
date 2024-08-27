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
	<cds-spacer
		:margin-bottom="4"
	/>
	<span class="board-list-edit-item">
		<cds-text-input
			v-model="newBoardList"
			label="Listas de trabalho"
			fluid
			required
			@keyup.enter="addBoardList"
		/>
		<cds-icon-button
			icon="plus-outline"
			@click="addBoardList"
		/>
	</span>
	<cds-spacer
		:margin-bottom="2"
	/>
	<div class="board-list-legend">
		<small>Listas já adicionadas (clique duas vezes para editar):</small>
	</div>
	<cds-spacer
		:margin-bottom="2"
	/>
	<div class="board-list-container">
		<VueDraggable
			ref="el"
			v-model="team.boardLists"
			:animation="150"
		>
			<div
				v-for="(boardList, index) in team.boardLists"
			>
				<cds-box
					v-if="selectedBoardListIndex === null || selectedBoardListIndex !== index"
					@dblclick="selectedBoardListIndex = index"
				>
					<small>
						{{ boardList.name }}
					</small>
				</cds-box>
				<cds-box
					v-else
					class="dashed-box"
					variant="amber"
				>
					<span class="board-list-edit-item--editable">
						<cds-text-input
							v-model="boardList.name"
							label=""
							fluid
							autofocus
							@keyup.enter="selectedBoardListIndex = null"
							@blur="selectedBoardListIndex = null"
						/>
						<cds-icon-button
							icon="check-outline"
							@click="selectedBoardListIndex = null"
						/>
						<cds-icon-button
							icon="trash-outline"
							@click="removeBoardListConfirmation(index)"
						/>
					</span>
				</cds-box>
			</div>
		</VueDraggable>
	</div>
	<cds-dialog-modal
		v-model="showDeleteListModal"
		title="Tem certeza que deseja deletar essa lista?"
		description="Ao realizar esta ação, todos os cards dessa lista serão permanentemente excluídos"
		action-button-variant="red"
		ok-button-text="Sim, excluir"
		@close="cancelRemoveBoardList"
		@ok="removeBoardList(selectedBoardListIndex)"
	/>
</template>
<script>
import { Form, Field } from 'vee-validate';
import { VueDraggable } from 'vue-draggable-plus';
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
		VueDraggable,
	},

	data() {
		return {
			team: this.modelValue,
			newBoardList: '',
			selectedBoardListIndex: null,
			showDeleteListModal: false,
			selectedIndexToEdit: null,
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
			this.newBoardList = '';
		},

		removeBoardListConfirmation(index) {
			this.selectedBoardListIndex = index;	
			this.showDeleteListModal = true;		
		},

		removeBoardList() {
			this.team.boardLists.splice(this.selectedBoardListIndex, 1);
			this.showDeleteListModal = false;
			this.selectedBoardListIndex = null;
		},
		cancelRemoveBoardList() {
			this.selectedBoardListIndex = null;
			this.showDeleteListModal = false;
		},
	},
}
</script>
<style lang="scss">
@import 'node_modules/@sysvale/cuida/dist/@sysvale/tokens.scss';

.board-list-container > div {
	display: flex;
	flex-direction: row;
	flex-wrap: wrap;
	gap: 10px;
	margin-top: 10px;
	height: auto;
}


.board-list-edit-item {
	display: flex;
	flex-direction: row;
	gap: 10px;
	align-items: end;

	&--editable {
		@extend .board-list-edit-item;
		margin-top: -18px;
		margin-bottom:-10px;
		margin-left: -10px;
		margin-right: -10px;
	}
}

.board-list-legend {
	color: $n-700;
}

.dashed-box {
	outline-style: dashed!important;
}
</style>