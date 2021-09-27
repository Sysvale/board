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
			:items="processes"
			sort-by="name"
			class="elevation-1"
		>
			<template v-slot:top>
				<v-toolbar
					flat
				>
					<v-toolbar-title>
						Central de Processos
					</v-toolbar-title>

					<v-spacer></v-spacer>

					<v-btn
						color="primary"
						dark
						class="mb-2"
						to="/process/new"
					>
						Novo processo
					</v-btn>
				</v-toolbar>
			</template>

			<template v-slot:item.actions="{ item }">
				<v-btn
					icon
					@click="showProcess(item)"
				>
					<v-icon
						small
						class="mr-2"
					>
						visibility
					</v-icon>
				</v-btn>
				<v-btn
					icon
					:to="`/process/edit/${item.id}`"
				>
					<v-icon
						small
						class="mr-2"
					>
						edit
					</v-icon>
				</v-btn>
			</template>
		</v-data-table>
		<view-process-modal
			v-model="showViewProcessModal"
			:process="selectedProcess"
		/>
	</v-container>
</template>

<script>
import { mapActions, mapMutations, mapState } from 'vuex';
import ViewProcessModal from '../components/ViewProcessModal.vue';

export default {
  components: { ViewProcessModal },
	data() {
		return {
			showViewProcessModal: false,
			selectedProcess: null,
			headers: [
				{
					text: 'Nome',
					align: 'start',
					sortable: true,
					value: 'name',
				},
				{ text: 'Ações', value: 'actions', sortable: false },
			],
		}
	},

	mounted() {
		this.fetchProcesses();
	},

	computed: {
		...mapState('processes', {
			processes: state => state.items,
			loading: ({ getProcesses }) => getProcesses.isFetching,
		}),
	},

	watch: {
		showViewProcessModal(value) {
			value || (() => this.selectedProcess = null)();
		},
	},

	methods: {
		isEmpty: (arg) => _.isEmpty(arg),
		...mapActions('processes', [
			'getProcesses',
		]),

		...mapMutations('processes', {
			setProcesses: 'setItems',
		}),

		reloadData() {
			this.fetchProcesses();
			this.fetchTeams();
			this.fetchLabels();
		},

		fetchProcesses() {
			this.getProcesses().then((items) => {
				this.setProcesses(items);
			});
		},

		showProcess(item) {
			this.selectedProcess = item;
			this.showViewProcessModal = true;
		},
	},
}
</script>
