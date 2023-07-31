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
			<div
				v-else
			>
				<v-row>
					<v-col cols="3">
						<v-card>
							<v-card-text>
								<div class="text-center mb-4">
									Velocidade do time *
								</div>
								<div class="text-center">
									<h1>
										{{ getTeamVelocity() }} pts
										<span>
											<v-icon>
												{{ getTeamVelocityIcon() }}
											</v-icon>
										</span>
									</h1>
								</div>
							</v-card-text>
						</v-card>
						<v-card class="mt-5">
							<v-card-text>
								<div class="text-center mb-4">
									Percentual médio de entrega de pontos *
								</div>
								<div class="text-center">
									<h1>
										{{ getDeliveryAveragePercentage().points }}%
									</h1>
								</div>
							</v-card-text>
						</v-card>

						<v-card class="mt-5">
							<v-card-text>
								<div class="text-center mb-4">
									Percentual médio de entrega de itens de backlog *
								</div>
								<div class="text-center">
									<h1>
										{{ getDeliveryAveragePercentage().quantity }}%
									</h1>
								</div>
							</v-card-text>
						</v-card>
						<div class="mt-5">
							<small>* Todos os valores são calculados baseados nas últimas 8 sprints</small>
						</div>
					</v-col>
					<v-col cols="9">
						<v-card>
							<v-card-text>
								<canvas
									id="velocity-chart"
									height="150px"
								/>
							</v-card-text>
						</v-card>
					</v-col>
				</v-row>
				<v-data-table
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
					<template v-slot:item.deliveredRelativeCount="{ item }">
						<div>
							{{ deliveredRelativeCountLabel(item) }}
						</div>
					</template>
					<template v-slot:item.deliveredRelativePoints="{ item }">
						<div>
							{{ deliveredRelativePointsLabel(item) }}
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
		</div>
	</v-container>
</template>

<script>
import { mapActions, mapMutations, mapState } from 'vuex';
import Chart from 'chart.js';
import { dictionary as userStoryStatuses } from '../../../core/constants/userStoryStatuses';
import SprintReportDialog from '../components/SprintReportDialog.vue';

const SPRINT_RANGE = 8;

export default {
	components: {
		SprintReportDialog,
	},
	data() {
		return {
			userStoryStatuses,
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
				{
					text: 'Feito/Planejado (Quantidade)',
					align: 'start',
					sortable: true,
					value: 'deliveredRelativeCount',
				},
				{
					text: 'Feito/Planejado (Pontos)',
					align: 'start',
					sortable: true,
					value: 'deliveredRelativePoints',
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

		deliveredRelativePointsLabel() {
			return (item) => {
				const { percentage, plannedPoints, donePoints } = this.getDeliveredRelativePoints(item);
				return `${percentage}% (${donePoints}/${plannedPoints})`;
			};
		},

		deliveredRelativeCountLabel() {
			return (item) => {
				const { percentage, doneCount, total } = this.getDeliveredRelativeCount(item);
				return `${percentage}% (${doneCount}/${total})`;
			};
		},
	},

	watch: {
		selectedTeamId() {
			this.fetchSprintReports();
		},
		sprints(newValue) {
			this.$nextTick().then(() => {
				const numberOfSprintsToConsider = newValue.length <= SPRINT_RANGE
					? newValue.length : SPRINT_RANGE;
				const sorted = newValue.slice(0, numberOfSprintsToConsider).reverse();
				// eslint-disable-next-line no-unused-vars
				const chart = new Chart(
					document.getElementById('velocity-chart').getContext('2d'),
					{
						type: 'bar',
						data: {
							labels: sorted.map((row) => this.getSprintPeriod(row, true)),
							datasets: [
								{
									label: 'Pontos entregues',
									data: sorted.map((row) => this.getDeliveredPoints(row)),
									backgroundColor: '#1a55a85a',
									borderColor: '#1a55a8',
									type: 'line',
								},
								{
									label: 'Pontos planejados',
									data: sorted.map((row) => this.getPlannedPoints(row)),
									backgroundColor: '#FDCD8750',
									borderColor: '#FDCD87',
									type: 'line',
								},
							],
						},
						options: {
							scales: {
								yAxes: [{
									ticks: {
										beginAtZero: true,
									},
								}],
							},
						},
					},
				);
			});
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

		getSprintPeriod(item, short = false) {
			if (short) return `${moment(item.startedAt).format('DD/MM')}`;
			return `${moment(item.startedAt).format('DD/MM/yy')} à ${moment(item.finishedAt).format('DD/MM/yy')}`;
		},

		getDeliveredPoints(item) {
			const deliveredPoints = item.cards.reduce((acc, card) => {
				if (card.status !== 'done') {
					return acc + 0;
				}

				return acc + Number(card.estimated || 0);
			}, 0);

			return deliveredPoints;
		},

		getPlannedPoints(item) {
			const plannedPoints = item.cards.reduce((acc, card) => acc + Number(card.estimated || 0), 0);
			return plannedPoints;
		},

		getTeamVelocity(oldVelocity = false) {
			let xSprints = this.sprints;
			if (oldVelocity) {
				xSprints = xSprints.slice(0, xSprints.length - 1);
			}
			const numberOfSprintsToConsider = xSprints.length <= SPRINT_RANGE ? xSprints.length : SPRINT_RANGE;
			const sprintsToConsider = xSprints.slice(0, numberOfSprintsToConsider);
			const velocity = sprintsToConsider.reduce(
				(acc, curr) => acc + this.getDeliveredPoints(curr),
				0,
			);

			// eslint-disable-next-line no-bitwise
			return ~~(velocity / numberOfSprintsToConsider);
		},

		getTeamVelocityIcon() {
			// -1 caiu, 0 manteve, 1 subiu
			const oldVelocity = this.getTeamVelocity(true);
			const newVelocity = this.getTeamVelocity();

			if (newVelocity < oldVelocity) return 'arrow_downward';
			if (newVelocity > oldVelocity) return 'arrow_upward';
			return 'unknown_med';
		},

		getDeliveryAveragePercentage(oldVelocity = false) {
			let xSprints = this.sprints;
			if (oldVelocity) {
				xSprints = xSprints.slice(0, xSprints.length - 1);
			}
			const numberOfSprintsToConsider = xSprints.length <= SPRINT_RANGE ? xSprints.length : SPRINT_RANGE;
			const sprintsToConsider = xSprints.slice(0, numberOfSprintsToConsider);
			const pointsPercentageAvg = sprintsToConsider.reduce(
				(acc, curr) => acc + this.getDeliveredRelativePoints(curr).percentage,
				0,
			);

			const quantityPercentageAvg = sprintsToConsider.reduce(
				(acc, curr) => acc + this.getDeliveredRelativeCount(curr).percentage,
				0,
			);

			return {
				// eslint-disable-next-line no-bitwise
				points: ~~(pointsPercentageAvg / numberOfSprintsToConsider),
				// eslint-disable-next-line no-bitwise
				quantity: ~~(quantityPercentageAvg / numberOfSprintsToConsider),
			};
		},

		getDeliveredRelativeCount(item) {
			const doneCount = item.cards.filter(({ status }) => status === userStoryStatuses.DONE).length;
			// eslint-disable-next-line no-bitwise
			const percentage = ~~((doneCount / item.cards.length) * 100);
			return { percentage, doneCount, total: item.cards.length };
		},

		getDeliveredRelativePoints(item) {
			const donePoints = item.cards
				.filter(({ status }) => status === userStoryStatuses.DONE)
				.reduce((acc, curr) => acc + Number(curr.estimated || 0), 0);

			const plannedPoints = item.cards
				.reduce((acc, curr) => acc + Number(curr.estimated || 0), 0);

			// eslint-disable-next-line no-bitwise
			const percentage = ~~((donePoints / plannedPoints) * 100);
			return { percentage, donePoints, plannedPoints };
		},
	},
};
</script>
