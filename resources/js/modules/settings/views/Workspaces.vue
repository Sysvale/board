<template>
	<v-container>
		<v-skeleton-loader
			v-if="loading"
			class="mx-auto"
			type="table"
		></v-skeleton-loader>

		<v-data-table
			v-else
			:headers="headers"
			:items="workspaces"
			sort-by="name"
			class="elevation-1"
		>
			<template v-slot:top>
				<v-toolbar
					flat
				>
					<v-toolbar-title>Workspaces</v-toolbar-title>

					<v-spacer></v-spacer>

					<v-dialog
						v-model="dialog"
						max-width="500px"
					>
						<template v-slot:activator="{ on, attrs }">
							<v-btn
								color="primary"
								dark
								class="mb-2"
								v-bind="attrs"
								v-on="on"
							>
								Novo workspace
							</v-btn>
						</template>

						<v-card>
							<v-card-title>
								<span class="headline">{{ formTitle }}</span>
							</v-card-title>

							<v-card-text>
								<v-container>
									<v-row>
										<v-text-field
											v-model="selectedItem.name"
											label="Nome"
										></v-text-field>
									</v-row>
									<v-row>
										<v-select
											v-model="selectedItem.teamIds"
											:items="computedTeams"
											:multiple="true"
											placeholder="Time(s)"
											return
											chips
											item-text="name"
											item-value="id"
										/>
									</v-row>
									<v-row>
										<v-select
											v-model="selectedItem.labelIds"
											:items="computedLabels"
											:multiple="true"
											placeholder="Categorias(s)"
											return
											chips
											item-text="name"
											item-value="id"
										/>
									</v-row>
									<v-row>
										<v-text-field
											v-model="selectedItem.lottieFile"
											label="URL do Lottie File"
										></v-text-field>
									</v-row>
									<v-row>
										<v-switch
											v-model="selectedItem.settings.noPlanningProblems"
											label="Esconder board Suporte, issues e débitos técnicos"
										></v-switch>

										<v-switch
											v-model="selectedItem.settings.noSprintDevlog"
											label="Esconder board Sprint Devlog"
										></v-switch>
									</v-row>
								</v-container>
							</v-card-text>

							<v-card-actions>
								<v-spacer></v-spacer>
								<v-btn
									color="blue darken-1"
									text
									@click="close"
								>
									Cancelar
								</v-btn>

								<v-btn
									color="blue darken-1"
									text
									:disabled="!formIsValid"
									@click="save"
								>
									Salvar
								</v-btn>
							</v-card-actions>
						</v-card>
					</v-dialog>

					<v-dialog v-model="dialogDelete" max-width="500px">
						<v-card>
							<v-card-title class="headline">Deseja mesmo deletar {{ selectedItem.name }}?</v-card-title>
							<v-card-actions>
								<v-spacer></v-spacer>
								<v-btn color="blue darken-1" text @click="closeDelete">Cancelar</v-btn>
								<v-btn color="blue darken-1" text @click="deleteItemConfirm">Sim</v-btn>
								<v-spacer></v-spacer>
							</v-card-actions>
						</v-card>
					</v-dialog>
				</v-toolbar>
			</template>

			<template v-slot:item.actions="{ item }">
				<v-btn
					icon
					@click="editItem(item)"
				>
					<v-icon
						small
						class="mr-2"
					>
						edit
					</v-icon>
				</v-btn>

				<v-btn
					icon
					@click="deleteItem(item)"
				>
					<v-icon
						small
					>
						delete
					</v-icon>
				</v-btn>
			</template>
		</v-data-table>
	</v-container>
</template>

<script>
import { mapActions, mapMutations, mapState } from 'vuex';
import convertKeysToSnakeCase from '../../../core/utils/convertKeysToSnakeCase';

export default {
	props: {
		teamId: {
			type: String,
			default: null,
		}
	},

	data() {
		return {
			dialog: false,
			dialogDelete: false,
			headers: [
				{
					text: 'Nome',
					align: 'start',
					sortable: true,
					value: 'name',
				},
				{
					text: 'Times',
					align: 'start',
					sortable: true,
					value: 'teamNames',
				},
				{ text: 'Ações', value: 'actions', sortable: false },
			],
			editMode: false,
			selectedItem: {
				settings: {},
			},
			defaultItem: {
				settings: {},
			},
		}
	},

	computed: {
		formTitle () {
			return !this.editMode ? 'Novo workspace' : 'Editar workspace';
		},

		formIsValid () {
			return !!this.selectedItem.name && this.selectedItem.name.trim();
		},

		...mapState('teams', {
			teams: 'items',
		}),

		...mapState('labels', {
			labels: 'items',
		}),

		...mapState('workspaces', {
			workspaces: state => state.items,
			isGetting: ({ getWorkspaces }) => getWorkspaces.isFetching,
			isCreating: ({ createWorkspace }) => createWorkspace.isFetching,
			isUpdating: ({ updateWorkspace }) => updateWorkspace.isFetching,
			isRemoving: ({ deleteWorkspace }) => deleteWorkspace.isFetching,
		}),

		loading() {
			return this.isGetting || this.isCreating || this.isUpdating || this.isRemoving;
		},

		computedTeams() {
			if (this.editMode) {
				return this.teams.filter(({ workspaceId }) => {
					return !workspaceId || workspaceId === this.selectedItem.id;
				});
			}

			return this.teams.filter(({ workspaceId }) => !workspaceId);
		},

		computedLabels() {
			if (this.editMode) {
				return this.labels.filter(({ workspaceId }) => {
					return !workspaceId || workspaceId === this.selectedItem.id;
				});
			}

			return this.labels.filter(({ workspaceId }) => !workspaceId);
		},
	},

	watch: {
		dialog (val) {
			val || this.close();
		},

		dialogDelete (val) {
			val || this.closeDelete();
		},
	},

	methods: {
		isEmpty: (arg) => _.isEmpty(arg),
		...mapActions('workspaces', [
			'getWorkspaces',
			'deleteWorkspace',
			'updateWorkspace',
			'createWorkspace',
		]),

		...mapActions('teams', [
			'getTeams',
		]),

		...mapMutations('teams', {
			setTeams: 'setItems',
		}),

		...mapActions('labels', [
			'getLabels',
		]),

		...mapMutations('labels', {
			setLabels: 'setItems',
		}),

		...mapMutations('workspaces', {
			setWorkspaces: 'setItems',
		}),

		editItem (item) {
			this.editMode = true;
			this.selectedItem = Object.assign({}, item);
			this.dialog = true;
		},

		deleteItem (item) {
			this.selectedItem = Object.assign({}, item);
			this.dialogDelete = true;
		},

		deleteItemConfirm () {
			const deletedId = _.clone(this.selectedItem.id);
			this.deleteWorkspace(this.selectedItem.id)
				.then(() => {
					this.reloadData();
				})
			this.closeDelete();
		},

		close () {
			this.dialog = false;
			this.$nextTick(() => {
				this.selectedItem = Object.assign({}, this.defaultItem);
				this.editMode = false;
			});
		},

		closeDelete () {
			this.dialogDelete = false;
			this.$nextTick(() => {
				this.selectedItem = Object.assign({}, this.defaultItem);
				this.editMode = false;
			});
		},

		save () {
			if (this.editMode) {
				this.updateWorkspace(convertKeysToSnakeCase(this.selectedItem))
					.then((item) => {
						this.reloadData();
					});
			} else {
				this.createWorkspace(convertKeysToSnakeCase(this.selectedItem))
					.then((item) => {
						this.reloadData();
					});
			}
			this.close();
		},

		reloadData() {
			this.fetchWorkspaces();
			this.fetchTeams();
			this.fetchLabels();
		},

		fetchWorkspaces() {
			this.getWorkspaces().then((items) => {
				this.setWorkspaces(items);
			})
			.finally(() => {
				this.selectedItem = {};
			});
		},

		fetchTeams() {
			this.getTeams().then((items) => {
				this.setTeams(items);
			});
		},

		fetchLabels() {
			this.getLabels().then((items) => {
				this.setLabels(items);
			});
		},
	},
}
</script>
