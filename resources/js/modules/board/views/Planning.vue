<template>
	<v-container
		fluid
		class="px-2 py-5"
	>
		<v-expansion-panels
			v-model="panels"
			flat
			multiple
			tile
			class="px-2"
		>
			<v-expansion-panel
				key="buglogson"
			>
				<v-expansion-panel-header>
					<div class="d-flex align-center">
						<v-icon class="mr-2">
							bug_report
						</v-icon>
						<h3 class="mb-0">Suporte, issues e outros problemas</h3>
					</div>
				</v-expansion-panel-header>
				<v-expansion-panel-content>
					<board
						namespace="buglog"
						:getLists="getPlanningLists"
						:getCards="getCardsByListsIds"
					/>
				</v-expansion-panel-content>
			</v-expansion-panel>
			<v-expansion-panel
				key="buglog"
			>
				<v-expansion-panel-header>
					<div class="d-flex align-center">
						<v-icon class="mr-2">
							list
						</v-icon>
						<h3 class="mb-0">Planning</h3>
					</div>
				</v-expansion-panel-header>
				<v-expansion-panel-content>
					<board
						namespace="planning"
						:getLists="getPlanningLists"
						:getCards="getCardsByListsIds"
					/>
				</v-expansion-panel-content>
			</v-expansion-panel>
		</v-expansion-panels>
	</v-container>
</template>

<script>
import Board from '../components/Board.vue';
import {
	getPlanningLists,
} from '../services/planning';

import {
	getCardsByListsIds,
} from '../services/cards';

export default {
	components: {
		Board,
	},
	data() {
		return {
			panels: [1],
		}
	},
	methods: {
		getPlanningLists,
		getCardsByListsIds,
	},
}
</script>
