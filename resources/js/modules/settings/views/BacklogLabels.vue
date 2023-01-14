<template>
	<v-container>
		<v-skeleton-loader
			v-if="loading"
			class="mx-auto"
			type="table"
		/>
		<v-data-table
			v-else
			:headers="headers"
			:items="backlogLabels"
			sort-by="name"
			class="elevation-1"
		>
			<template v-slot:top>
				<v-toolbar
					flat
				>
					<v-toolbar-title>Categorias de Backlog</v-toolbar-title>
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
								Nova categoria de backlog
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
												secondary
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
					<v-dialog
						v-model="dialogDelete"
						max-width="500px"
					>
						<v-card>
							<v-card-title
								class="headline"
							>
								Deseja mesmo deletar {{ selectedItem.name }}?
							</v-card-title>
							<v-card-actions>
								<v-spacer />
								<v-btn
									color="blue
									darken-1"
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
			<template v-slot:item.name="{ item }">
				<label-item
					:label="item"
					secondary
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
					text: 'Categoria de Backlog',
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
			return !this.editMode ? 'Nova categoria de backlog' : 'Editar item';
		},

		formIsValid() {
			return !!this.selectedItem.name && this.selectedItem.name.trim()
			&& !!this.selectedItem.color && this.selectedItem.color.trim()
			&& !!this.selectedItem.textColor && this.selectedItem.textColor.trim();
		},

		...mapState('backlogLabels', {
			backlogLabels: ({ items }) => items,
			isGetting: ({ getBacklogLabels }) => getBacklogLabels.isFetching,
			isCreating: ({ createBacklogLabel }) => createBacklogLabel.isFetching,
			isUpdating: ({ updateBacklogLabel }) => updateBacklogLabel.isFetching,
			isRemoving: ({ deleteBacklogLabel }) => deleteBacklogLabel.isFetching,
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
			// eslint-disable-next-line no-unused-expressions
			val || this.close();
		},
		dialogDelete(val) {
			// eslint-disable-next-line no-unused-expressions
			val || this.closeDelete();
		},
	},

	methods: {
		...mapActions('backlogLabels', [
			'getBacklogLabels',
			'deleteBacklogLabel',
			'updateBacklogLabel',
			'createBacklogLabel',
		]),

		...mapMutations('backlogLabels', [
			'setItems',
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
			this.deleteBacklogLabel(this.selectedItem.id)
				.then(() => {
					this.fetchBacklogLabels();
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
			};
			if (this.editMode) {
				this.updateBacklogLabel(convertKeysToSnakeCase(label))
					.then(() => {
						this.fetchBacklogLabels();
					});
			} else {
				this.createBacklogLabel(convertKeysToSnakeCase(label))
					.then(() => {
						this.fetchBacklogLabels();
					});
			}
			this.close();
		},

		fetchBacklogLabels() {
			this.getBacklogLabels().then((items) => {
				this.setItems(items);
			}).finally(() => {
				this.selectedItem = {};
			});
		},
	},
};
</script>
