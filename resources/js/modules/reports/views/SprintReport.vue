<template>
	<v-container>
		<v-select
			v-model="selectedTeamId"
			:items="teams"
			placeholder="Time"
			return
			item-text="name"
			item-value="id"
			label="Selecione um time"
		/>
		<div v-if="selectedTeamId !== null">
			<v-skeleton-loader
				v-if="loading"
				class="mx-auto"
				type="table"
			/>
			<v-data-table
				v-else
				:headers="headers"
				:items="sprints"
				sort-by="name"
				class="elevation-1"
			>
				<template v-slot:top>
					<v-toolbar
						flat
					>
						<v-toolbar-title>Sprints</v-toolbar-title>
						<v-spacer />
					</v-toolbar>

					<div class="mb-4">
						<small class="pl-4">Velocidade do time: </small>
						<small class="pl-2 font-weight-bold">{{ getTeamVelocity() }} pts</small>
					</div>
				</template>
				<template v-slot:item.period="{ item }">
					<div>
						{{ getSprintPeriod(item) }}
					</div>
				</template>
				<template v-slot:item.deliveredPoints="{ item }">
					<div>
						{{ getDeliveredPoints(item) }} pts
					</div>
				</template>
				<template v-slot:item.actions="{ item }">
					<sprint-report-dialog
						:data="item"
						readonly
					>
						<template v-slot:activator="{on, attrs}">
							<v-btn
								text
								v-bind="attrs"
								v-on="on"
								@click.stop
							>
								<v-icon
									small
									class="mr-2"
								>
									visibility
								</v-icon>
							</v-btn>
						</template>
					</sprint-report-dialog>
				</template>
			</v-data-table>
		</div>
	</v-container>
</template>

<script>
import { mapActions, mapMutations, mapState } from 'vuex';
import SprintReportDialog from '../components/SprintReportDialog.vue';

export default {
	components: {
		SprintReportDialog,
	},
	data() {
		return {
			dialog: false,
			teamVelocity: 0,
			headers: [
				{
					text: 'Data',
					align: 'start',
					sortable: true,
					value: 'period',
				},
				{
					text: 'Pontos entregues',
					align: 'start',
					sortable: true,
					value: 'deliveredPoints',
				},
				{ text: 'Ações', value: 'actions', sortable: false },
			],
			selectedItem: {},
		};
	},

	computed: {
		selectedTeamId: {
			get() {
				return this.storeSelectedTeamId;
			},
			set(value) {
				this.setSelectedTeamId(value);
			},
		},

		...mapState('teams', {
			teams: 'items',
		}),

		...mapState('sprintReports', {
			storeSelectedTeamId: ({ selectedTeamId }) => selectedTeamId,
			sprints: ({ items }) => items,
			loading: ({ getSprintReportByTeamId }) => getSprintReportByTeamId.isFetching,
		}),
	},

	watch: {
		selectedTeamId() {
			this.fetchSprintReports();
		},
	},

	beforeDestroy() {
		this.setSelectedTeamId(null);
	},

	methods: {
		...mapActions('sprintReports', [
			'getSprintReportByTeamId',
		]),

		...mapMutations('sprintReports', [
			'setItems',
			'setSelectedTeamId',
		]),

		showDetails(item) {
			this.selectedItem = {
				...item,
			};
			this.dialog = true;
		},

		fetchSprintReports() {
			this.getSprintReportByTeamId(this.selectedTeamId).then((items) => {
				this.setItems(items);
			}).finally(() => {
				this.selectedItem = {};
			});
		},

		getSprintPeriod(item) {
			return `${moment(item.startedAt).format('DD/MM/yy')} à ${moment(item.finishedAt).format('DD/MM/yy')}`;
		},

		getDeliveredPoints(item) {
			const deliveredPoints = item.cards.reduce((acc, card) => {
				if (card.status !== 'done') {
					return acc + 0;
				}

				return acc + Number(card.estimated);
			}, 0);

			return deliveredPoints;
		},

		getTeamVelocity() {
			const numberOfSprints = this.sprints.length;
			const velocity = this.sprints.reduce((acc, curr) => acc + this.getDeliveredPoints(curr), 0);

			// eslint-disable-next-line no-bitwise
			return ~~(velocity / numberOfSprints);
		},
	},
};
</script>
