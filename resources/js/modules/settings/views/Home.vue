<template>
	<v-container>
		<v-btn
			to="/settings/members"
		>
			Membros
		</v-btn>

		<v-btn
			to="/settings/workspaces"
		>
			Workspaces
		</v-btn>
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
		...mapActions('workspaces', [
			'getWorkspaces',
			'deleteWorkspace',
			'updateWorkspace',
			'createWorkspace',
		]),

		...mapMutations('workspaces', [
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
			this.deleteWorkspace(this.selectedItem.id)
				.then(() => {
					this.fetchWorkspaces();
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
						this.fetchWorkspaces();
					});
			} else {
				this.createWorkspace(convertKeysToSnakeCase(this.selectedItem))
					.then((item) => {
						this.fetchWorkspaces();
					});
			}
			this.close();
		},

		fetchWorkspaces() {
			this.getWorkspaces().then((items) => {
				this.setItems(items);
			})
			.finally(() => {
				this.selectedItem = {};
			});
		},
	},
}
</script>
