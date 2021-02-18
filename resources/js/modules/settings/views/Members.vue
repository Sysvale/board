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
		:items="members"
		sort-by="name"
		class="elevation-1"
	>
			<template v-slot:top>
				<v-toolbar
					flat
				>
					<v-toolbar-title>Membros</v-toolbar-title>
					<v-divider
						class="mx-4"
						inset
						vertical
					></v-divider>
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
								Novo membro
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
											:items="teams"
											:multiple="true"
											placeholder="Time(s)"
											return
											item-text="name"
											item-value="id"
										/>
									</v-row>
									<v-row>
										<v-text-field
											v-model="selectedItem.avatarUrl"
											label="URL do avatar"
										></v-text-field>
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
import SprintTabContent from '../components/SprintTabContent.vue';
import { mapActions, mapMutations, mapState } from 'vuex';
import convertKeysToSnakeCase from '../../../core/utils/convertKeysToSnakeCase';

export default {
	components: {
		SprintTabContent,
	},

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
					text: 'Time',
					align: 'start',
					sortable: true,
					value: 'teams',
				},
				{ text: 'Ações', value: 'actions', sortable: false },
			],
			editMode: false,
			selectedItem: {},
		}
	},

	computed: {
		formTitle () {
			return !this.editMode ? 'Novo membro' : 'Editar item';
		},

		formIsValid () {
			return !!this.selectedItem.name && this.selectedItem.name.trim();
		},

		...mapState('teams', {
			teams: 'items',
		}),

		...mapState('members', {
			members: state => state.items,
			isGetting: ({ getMembers }) => getMembers.isFetching,
			isCreating: ({ createMember }) => createMember.isFetching,
			isUpdating: ({ updateMember }) => updateMember.isFetching,
			isRemoving: ({ deleteMember }) => deleteMember.isFetching,
		}),

		loading() {
			return this.isGetting || this.isCreating || this.isUpdating || this.isRemoving;
		},

		teamName() {
			return item => item.teamId ? this.teams.filter((team) => team.id === item.teamId)[0].name : '--';
		}
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
		...mapActions('members', [
			'getMembers',
			'deleteMember',
			'updateMember',
			'createMember',
		]),

		...mapMutations('members', [
			'setItems',
		]),

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
			this.deleteMember(this.selectedItem.id)
				.then(() => {
					this.fetchMembers();
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
				this.updateMember(convertKeysToSnakeCase(this.selectedItem))
					.then((item) => {
						this.fetchMembers();
					});
			} else {
				this.createMember(convertKeysToSnakeCase(this.selectedItem))
					.then((item) => {
						this.fetchMembers();
					});
			}
			this.close();
		},

		fetchMembers() {
			this.getMembers().then((items) => {
				this.setItems(items);
			})
			.finally(() => {
				this.selectedItem = {};
			});
		},
	},
}
</script>