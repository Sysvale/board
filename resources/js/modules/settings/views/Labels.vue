<template>
	<v-container>
		<v-select
			v-model="selectedWorkspaceId"
			:items="workspaces"
			placeholder="Workspace"
			return
			item-text="name"
			item-value="id"
			label="Selecione um workspace"
		/>
		<div v-if="selectedWorkspaceId !== null">
			<v-skeleton-loader
				v-if="loading"
				class="mx-auto"
				type="table"
			/>
			<v-data-table
				v-else
				:headers="headers"
				:items="labels"
				sort-by="name"
				class="elevation-1"
			>
				<template v-slot:top>
					<v-toolbar
						flat
					>
						<v-toolbar-title>Categorias</v-toolbar-title>
						<v-divider
							class="mx-4"
							inset
							vertical
						/>
						<v-spacer />
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
									Nova categoria
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
											<v-text-field
												v-model="selectedItem.color"
												label="Cor de fundo (#XXXXXX)"
											/>
										</v-row>
										<v-row>
											<v-text-field
												v-model="selectedItem.textColor"
												label="Cor do texto (#XXXXXX)"
											/>
										</v-row>
										<v-row>
											<div class="d-flex align-center">
												<hr>
												Pré-visualização:
												<label-item
													class="mx-2"
													:label="selectedItem"
												/>
											</div>
										</v-row>
									</v-container>
								</v-card-text>

								<v-card-actions>
									<v-spacer />
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
				<template v-slot:item.name="{ item }">
					<label-item
						:label="item"
					/>
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
		</div>
	</v-container>
</template>

<script>
import { mapActions, mapMutations, mapState } from 'vuex';
import convertKeysToSnakeCase from '../../../core/utils/convertKeysToSnakeCase';
import LabelItem from '../../board/components/LabelItem.vue';

export default {
	components: { LabelItem },
	data() {
		return {
			dialog: false,
			dialogDelete: false,
			headers: [
				{
					text: 'Categoria',
					align: 'start',
					sortable: true,
					value: 'name',
				},
				{ text: 'Ações', value: 'actions', sortable: false },
			],
			editMode: false,
			selectedItem: {},
		};
	},

	computed: {
		formTitle() {
			return !this.editMode ? 'Nova categoria' : 'Editar item';
		},

		formIsValid() {
			return !!this.selectedItem.name && this.selectedItem.name.trim()
			&& !!this.selectedItem.color && this.selectedItem.color.trim()
			&& !!this.selectedItem.textColor && this.selectedItem.textColor.trim();
		},

		selectedWorkspaceId: {
			get() {
				return this.storeSelectedWorkspaceId;
			},
			set(value) {
				this.setSelectedWorkspaceId(value);
			},
		},

		...mapState('workspaces', {
			workspaces: 'items',
		}),

		...mapState('labels', {
			storeSelectedWorkspaceId: ({ selectedWorkspaceId }) => selectedWorkspaceId,
			labels: ({ items }) => items,
			isGetting: ({ getLabels }) => getLabels.isFetching,
			isCreating: ({ createLabel }) => createLabel.isFetching,
			isUpdating: ({ updateLabel }) => updateLabel.isFetching,
			isRemoving: ({ deleteLabel }) => deleteLabel.isFetching,
		}),

		loading() {
			return this.isGetting
				|| this.isCreating
				|| this.isUpdating
				|| this.isRemoving
				|| this.isResending;
		},
	},

	watch: {
		dialog(val) {
			val || this.close();
		},
		dialogDelete(val) {
			val || this.closeDelete();
		},

		selectedWorkspaceId() {
			this.fetchLabels();
		},
	},

	beforeDestroy() {
		this.setSelectedWorkspaceId(null);
	},

	methods: {
		...mapActions('labels', [
			'getLabelsByWorkspaceId',
			'deleteLabel',
			'updateLabel',
			'createLabel',
		]),

		...mapMutations('labels', [
			'setItems',
			'setSelectedWorkspaceId',
		]),

		editItem(item) {
			this.editMode = true;
			this.selectedItem = {
				...item,
			};
			this.dialog = true;
		},

		deleteItem(item) {
			this.selectedItem = {
				...item,
			};
			this.dialogDelete = true;
		},

		deleteItemConfirm() {
			this.deleteLabel(this.selectedItem.id)
				.then(() => {
					this.fetchLabels();
				});
			this.closeDelete();
		},

		close() {
			this.dialog = false;
			this.$nextTick(() => {
				this.selectedItem = {};
				this.editMode = false;
			});
		},

		closeDelete() {
			this.dialogDelete = false;
			this.$nextTick(() => {
				this.selectedItem = {};
				this.editMode = false;
			});
		},

		save() {
			const label = {
				...this.selectedItem,
				workspaceId: this.selectedWorkspaceId,
			};
			if (this.editMode) {
				this.updateLabel(convertKeysToSnakeCase(label))
					.then(() => {
						this.fetchLabels();
					});
			} else {
				this.createLabel(convertKeysToSnakeCase(label))
					.then(() => {
						this.fetchLabels();
					});
			}
			this.close();
		},

		fetchLabels() {
			this.getLabelsByWorkspaceId(this.selectedWorkspaceId).then((items) => {
				this.setItems(items);
			}).finally(() => {
				this.selectedItem = {};
			});
		},
	},
};
</script>
