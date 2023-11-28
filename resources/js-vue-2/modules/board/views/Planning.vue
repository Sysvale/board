<template>
	<v-container
		v-if="!!currentWorkspace"
		:key="currentWorkspace ? currentWorkspace.name : 'planning'"
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
				:key="PlanningGroups.PLANNING"
			>
				<v-expansion-panel-header>
					<div class="d-flex align-center">
						<v-icon class="mr-2">
							list
						</v-icon>
						<h3 class="mb-0">Planejamento de Sprints</h3>
					</div>
				</v-expansion-panel-header>
				<v-expansion-panel-content>
					<board
						:namespace="`${currentWorkspace.name}planning`"
						:lists="planningLists"
						:getCards="{
							resolver: getPlanningCards,
							params: {
								workspaceId: currentWorkspace.id,
							}
						}"
						:cardMiddleware="{
							workspaceId: currentWorkspace.id,
						}"
					/>
				</v-expansion-panel-content>
			</v-expansion-panel>
		</v-expansion-panels>
	</v-container>
</template>

<script>
import { mapActions, mapGetters } from 'vuex';
import Board from '../components/Board.vue';
import PlanningGroups from '../constants/PlanningGroups';

import {
	getPlanningCards,
} from '../services/cards';

import convertKeysToCamelCase from '../../../core/utils/convertKeysToCamelCase';

export default {
	components: {
		Board,
	},
	data() {
		return {
			panels: [0],
			PlanningGroups,
			planningLists: [],
		};
	},

	computed: {
		...mapGetters('workspaces', ['currentWorkspace']),
	},

	watch: {
		currentWorkspace: {
			handler(newValue) {
				if (newValue) {
					this.getPlanningLists(newValue.id)
						.then((data) => {
							this.planningLists = convertKeysToCamelCase(data);
						});
				}
			},
			immediate: true,
		},
	},

	methods: {
		getPlanningCards,

		...mapActions('planning', [
			'getPlanningLists',
		]),
	},
};
</script>
