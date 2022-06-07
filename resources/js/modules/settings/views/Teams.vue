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
			:items="teams"
			sort-by="name"
			class="elevation-1"
		>
			<template v-slot:top>
				<v-toolbar
					flat
				>
					<v-toolbar-title>Times</v-toolbar-title>

					<v-spacer></v-spacer>

					<v-dialog
						v-model="dialog"
						max-width="750px"
					>
						<template v-slot:activator="{ on, attrs }">
							<v-btn
								color="primary"
								dark
								class="mb-2"
								v-bind="attrs"
								v-on="on"
							>
								Novo time
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
										/>
									</v-row>
									<v-row>
										<div v-if="!selectedItem.boardLists || selectedItem.boardLists.length === 0">
											Nenhuma lista adicionada
										</div>
										<div v-else>
											<draggable
												v-model="selectedItem.boardLists"
												class="d-flex"
											>
												<div
													v-for="(item, i) in selectedItem.boardLists"
													:key="`$${item.name}-{i}`"
													class="mr-1"
												>
													<v-chip
														close
														label
														@click:close="handleRemoveBoardList(i)"
													>
														{{ item.name }}
													</v-chip>
												</div>
											</draggable>
										</div>
									</v-row>
									<v-row class="mt-3">
										<v-text-field
											v-model="selectedItem.newBoardListItem"
											label="Lista"
										/>
										<v-btn
											@click="addBoardList"
										>
											Adicionar
										</v-btn>
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

					<v-dialog
						v-model="dialogDelete"
						max-width="500px"
					>
						<v-card>
							<v-card-title class="headline">
								Deseja mesmo deletar {{ selectedItem.name }}?
							</v-card-title>
							<v-card-actions>
								<v-spacer />
								<v-btn
									color="blue darken-1"
									text
									@click="closeDelete"
								>
									Cancelar
								</v-btn>
								<v-btn
									color="blue darken-1"
									text
									@click="deleteItemConfirm"
								>
									Sim
								</v-btn>
								<v-spacer />
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
		},
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
				{ text: 'Ações', value: 'actions', sortable: false },
			],
			editMode: false,
			selectedItem: {
				newBoardListItem: '',
			},
			defaultItem: {
				settings: {},
			},
		};
	},

	computed: {
		formTitle() {
			return !this.editMode ? 'Novo time' : 'Editar time';
		},

		formIsValid() {
			return !!this.selectedItem.name && this.selectedItem.name.trim();
		},

		...mapState('teams', {
			teams: 'items',
			isGetting: ({ getTeams }) => getTeams.isFetching,
			isCreating: ({ createTeam }) => createTeam.isFetching,
			isUpdating: ({ updateTeam }) => updateTeam.isFetching,
			isRemoving: ({ deleteTeam }) => deleteTeam.isFetching,
		}),

		loading() {
			return this.isGetting || this.isCreating || this.isUpdating || this.isRemoving;
		},
	},

	watch: {
		dialog(val) {
			val || this.close();
		},

		dialogDelete(val) {
			val || this.closeDelete();
		},
	},

	methods: {
		isEmpty: (arg) => _.isEmpty(arg),

		...mapActions('teams', [
			'getTeams',
			'deleteTeam',
			'updateTeam',
			'createTeam',
		]),

		...mapMutations('teams', {
			setTeams: 'setItems',
		}),

		editItem(item) {
			this.editMode = true;
			this.selectedItem = Object.assign({}, item);
			this.dialog = true;
		},

		deleteItem(item) {
			this.selectedItem = Object.assign({}, item);
			this.dialogDelete = true;
		},

		deleteItemConfirm() {
			this.deleteTeam(this.selectedItem.id)
				.then(() => {
					this.reloadData();
				});
			this.closeDelete();
		},

		close() {
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

		save() {
			if (this.editMode) {
				this.selectedItem = {
					...this.selectedItem,
					boardLists: this.selectedItem
						.boardLists.map((i, position) => ({ ...i, position })),
				};

				this.updateTeam(convertKeysToSnakeCase(this.selectedItem))
					.then(() => {
						this.reloadData();
					});
			} else {
				this.createTeam(convertKeysToSnakeCase(this.selectedItem))
					.then(() => {
						this.reloadData();
					});
			}
			this.close();
		},

		reloadData() {
			this.fetchTeams();
		},

		fetchTeams() {
			this.getTeams().then((items) => {
				this.setTeams(items);
			}).finally(() => {
				this.selectedItem = {};
			});
		},

		addBoardList() {
			this.selectedItem.boardLists = [
				...(this.selectedItem.boardLists || []),
				{
					name: this.selectedItem.newBoardListItem,
					position: (this.selectedItem.boardLists?.length || 0),
				},
			];

			this.selectedItem.newBoardListItem = '';
		},

		handleRemoveBoardList(i) {
			this.selectedItem = {
				...this.selectedItem,
				boardLists: this.selectedItem
					.boardLists
					.filter((_, index) => index !== i)
					.map((item, index) => ({ ...item, position: index })),
			};
		},
	},
};
</script>
