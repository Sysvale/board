<template>
	<v-container
		key="company-planning"
		fluid
		class="px-2 py-5"
	>
		<v-expansion-panels
			v-model="panels"
			flat
			tile
			class="px-2"
		>
			<v-expansion-panel
				:key="PlanningGroups.PLANNING"
			>
				<v-expansion-panel-header>
					<div class="d-flex align-center">
						<v-icon class="mr-2">
							list
						</v-icon>
						<h3 class="mb-0">Planejamento de Backlogs</h3>
					</div>
				</v-expansion-panel-header>
				<v-expansion-panel-content>
					<board
						namespace="company-planning"
						:lists="lists"
						:getCards="{
							resolver: getCardsByListsIds,
						}"
						:cardMiddleware="{
							type: 'user-story',
						}"
					/>
				</v-expansion-panel-content>
			</v-expansion-panel>
		</v-expansion-panels>
	</v-container>
</template>

<script>
import { mapActions } from 'vuex';
import convertKeysToCamelCase from '../../../core/utils/convertKeysToCamelCase';

import Board from '../components/Board.vue';
import PlanningGroups from '../constants/PlanningGroups';

import {
	getCardsByListsIds,
} from '../services/cards';

export default {
	components: {
		Board,
	},
	data() {
		return {
			panels: 0,
			PlanningGroups,
			lists: [],
		};
	},

	mounted() {
		this.getCompanyPlanningLists()
			.then((data) => {
				this.lists = convertKeysToCamelCase(data);
			});
	},

	methods: {
		...mapActions('planning', [
			'getCompanyPlanningLists',
		]),

		getCardsByListsIds,
	},
};
</script>
