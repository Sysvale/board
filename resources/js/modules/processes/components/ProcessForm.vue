<template>
	<v-card>
		<v-card-title>
			<span class="headline">{{ formTitle }}</span>
		</v-card-title>
		<v-card-text>
			<v-container>
				<v-row>
					<v-text-field
						v-model="form.name"
						label="Nome"
					></v-text-field>
				</v-row>
				<v-row>
					<v-select
						v-model="form.teamIds"
						:items="teams"
						:multiple="true"
						placeholder="Time(s)"
						return
						chips
						item-text="name"
						item-value="id"
					/>
				</v-row>

				<v-row>
					<v-container>
						<div class="mb-3">Checklists</div>
						<div v-if="form.checklists.length === 0" class="grey--text caption">
							Você ainda não adicionou nenhuma checklist
						</div>
						<v-expansion-panels
							v-else
							flat
						>
							<v-expansion-panel
								v-for="(item, i) in form.checklists"
								:key="i"
							>
								<v-expansion-panel-header>
									{{ item.title }}
								</v-expansion-panel-header>
								<v-expansion-panel-content>
									<checklist-form
										v-model="item.items"
									/>
								</v-expansion-panel-content>
							</v-expansion-panel>
						</v-expansion-panels>
						<v-dialog
							v-model="dialog"
							max-width="750px"
						>
							<template v-slot:activator="{ on, attrs }">
								<v-btn
									color="primary"
									dark
									class="my-2 text-left"
									v-bind="attrs"
									v-on="on"
								>
									Adicionar checklist
								</v-btn>
							</template>
							<v-card>
								<v-card-title>
									Nova checklist
								</v-card-title>
								<v-card-text>
									<v-container>
										<v-text-field
											v-model="newChecklistForm.title"
											label="Título"
										></v-text-field>
										<checklist-form
											v-model="newChecklistForm.items"
										/>
									</v-container>
								</v-card-text>
								<v-card-actions>
									<v-spacer></v-spacer>
									<v-btn
										color="blue darken-1"
										text
										@click="resetChecklistForm"
									>
										Cancelar
									</v-btn>

									<v-btn
										color="blue darken-1"
										text
										:disabled="!checklistFormIsValid"
										@click="saveNewChecklist"
									>
										Salvar
									</v-btn>
								</v-card-actions>
							</v-card>
						</v-dialog>
					</v-container>
				</v-row>
			</v-container>
		</v-card-text>

		<v-card-actions>
			<v-spacer></v-spacer>
			<v-btn
				color="blue darken-1"
				text
				to="/processesy"
			>
				Cancelar
			</v-btn>

			<v-btn
				color="blue darken-1"
				text
				:disabled="!formIsValid"
				@click="$emit('save')"
			>
				Salvar
			</v-btn>
		</v-card-actions>
	</v-card>
</template>
<script>
import { mapState } from 'vuex';
import ChecklistForm from '../../board/components/ChecklistForm.vue';

export default {
	components: { ChecklistForm },
	props: {
		value: {
			type: Object,
			default: () => ({
				name: null,
				teamIds: [],
				checklists: [],
			}),
		},
		editing: {
			type: Boolean,
			default: false,
		}
	},

	data() {
		return {
			dialog: false,
			form: this.value,
			newChecklistForm: {
				title: null,
				items: [],
			},
		};
	},

	computed: {
		formTitle () {
			return !this.editing ? 'Novo processo' : 'Editar processo';
		},

		formIsValid () {
			return !!this.form.name && this.form.name?.trim()
				&& this.form.teamIds.length > 0
				&& this.form.checklists.length > 0;
		},

		checklistFormIsValid() {
			return !!this.newChecklistForm.title && this.newChecklistForm.title.trim() && this.newChecklistForm.items.length > 0;
		},

		...mapState('teams', {
			teams: 'items',
		}),
	},

	watch: {
		dialog (val) {
			val || this.resetChecklistForm();
		},
		form: {
			handler(newValue) {
				this.$emit('input', newValue);
			},
			deep: true,
		},
	},

	methods: {
		saveNewChecklist() {
			this.form.checklists = [
				...this.form.checklists,
				this.newChecklistForm,
			];
			this.$nextTick().then(() => {
				this.resetChecklistForm();
			})
		},

		resetChecklistForm() {
			this.newChecklistForm = {
				title: null,
				items: [],
			};
			this.dialog = false;
		}
	},
}
</script>
