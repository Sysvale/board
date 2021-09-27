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
					<v-toolbar-title>Central de Processos</v-toolbar-title>

					<v-spacer></v-spacer>

					<v-btn
						color="primary"
						dark
						class="mb-2"
						to="/processesy/new"
					>
						Novo processo
					</v-btn>
				</v-toolbar>
			</template>

			<template v-slot:item.actions="{ item }">
				<v-btn
					icon
					:to="`/processesy/edit/${item.id}`"
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
	</v-container>
</template>

<script>
import { mapActions, mapMutations, mapState } from 'vuex';

export default {
	data() {
		return {
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
	},
}
</script>
