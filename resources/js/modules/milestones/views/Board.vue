<template>
	<v-container v-if="milestone">
		<h2>{{ milestone.title }}</h2>
		<div>
			<small>
				<strong>Início:</strong> {{ milestone.startDate }} |
				<strong>Fim:</strong> {{ milestone.endDate }}
			</small>
		</div>
		<div>
			{{ milestone.description }}
		</div>
		<div class="py-2">
			<strong>Times</strong>
			<div>{{ teamsNames }}</div>
		</div>
		<div class="py-2">
			<strong>
				Critérios de aceitação ({{ acceptanceCriteriaDoneCount }}
				/{{ acceptanceCriteriaTotalCount }})
			</strong>
			<ul>
				<li
					v-for="criteria in milestone.acceptanceCriteria"
					:key="criteria.description"
					:class="{'done' : criteria.done }"
				>
					{{ criteria.description }}
				</li>
			</ul>
		</div>
		<v-row>
			<v-col>
				<h4>Não iniciado</h4>
				<hr class="mb-3">
				<v-card
					v-for="item in notStartedItems"
					:key="item.id"
					class="mb-2"
					elevation="1"
					color="#efefef"
				>
					<v-card-text>
						<div>
							<small>
								<strong>Workspace:</strong> {{ item.workspaceName }}
							</small>
						</div>
						{{ item.title }}
					</v-card-text>
				</v-card>
			</v-col>
			<v-col>
				<h4>Em andamento</h4>
				<hr class="mb-3">
				<v-card
					v-for="item in onGoingItems"
					:key="item.id"
					class="mb-2"
					elevation="1"
					color="#efefef"
				>
					<v-card-text>
						<div>
							<small>
								<strong>Time:</strong> {{ item.teamName }}
							</small>
						</div>
						{{ item.title }}
					</v-card-text>
				</v-card>
			</v-col>
			<v-col>
				<h4>Finalizados</h4>
				<hr class="mb-3">
				<v-card
					v-for="item in finishedItems"
					:key="item.id"
					class="mb-2"
					elevation="1"
					color="#efefef"
				>
					<v-card-text>
						<div>
							<small>
								<strong>Time:</strong> {{ item.teamName }}
							</small>
						</div>
						{{ item.title }}
					</v-card-text>
				</v-card>
			</v-col>
		</v-row>
	</v-container>
</template>
<script>
import { mapActions, mapState } from 'vuex';
import convertKeysToCamelCase from '../../../core/utils/convertKeysToCamelCase';

export default {
	data() {
		return {
			milestone: null,
			finishedItems: [],
			notStartedItems: [],
			onGoingItems: [],
		};
	},
	computed: {
		...mapState('teams', {
			teams: 'items',
		}),

		acceptanceCriteriaDoneCount() {
			return this.milestone.acceptanceCriteria ? this.milestone.acceptanceCriteria
				.filter(({ done }) => done).length : 0;
		},

		acceptanceCriteriaTotalCount() {
			return this.milestone.acceptanceCriteria ? this.milestone.acceptanceCriteria.length : 0;
		},

		teamsNames() {
			return this.teams
				.filter((team) => this.milestone.teamIds.indexOf(team.id) > -1)
				.map(({ name }) => name)
				.reduce((acc, curr, index, arr) => {
					if (index === arr.length - 1) {
						acc += curr;
					} else {
						acc += `${curr}, `;
					}
					return acc;
				}, '');
		},
	},
	mounted() {
		this.getMilestone(this.$route.params.id)
			.then((data) => {
				this.milestone = convertKeysToCamelCase(data);
				this.getFinishedItems(this.milestone.id)
					.then((finishedData) => {
						this.finishedItems = convertKeysToCamelCase(finishedData);
					});
				this.getOnGoingItems(this.milestone.id)
					.then((onGoingData) => {
						this.onGoingItems = convertKeysToCamelCase(onGoingData);
					});
				this.getNotStartedItems(this.milestone.id)
					.then((notStartedData) => {
						this.notStartedItems = convertKeysToCamelCase(notStartedData);
					});
			});
	},
	methods: {
		...mapActions('milestones', [
			'getMilestone',
			'getFinishedItems',
			'getOnGoingItems',
			'getNotStartedItems',
		]),
	},
};
</script>
<style scoped>
.done {
	text-decoration: line-through;
	opacity: 0.3;
}
</style>
