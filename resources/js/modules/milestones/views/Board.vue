<template>
	<v-container v-if="milestone">
		<h2>{{ milestone.title }}</h2>
		<div>Início: {{ milestone.startDate }} | Fim: {{ milestone.endDate }}</div>
		<div>
			{{ milestone.description }}
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
						{{ item.title }}
					</v-card-text>
				</v-card>
			</v-col>
		</v-row>
	</v-container>
</template>
<script>
import { mapActions } from 'vuex';
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
