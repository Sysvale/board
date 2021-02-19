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
				v-if="currentWorkspace && !currentWorkspace.settings.noPlanningProblems"
				:key="PlanningGroups.PROBLEMS"
				@change="handleProblemsPanelChange"
			>
				<v-expansion-panel-header>
					<div class="d-flex align-center">
						<v-icon class="mr-2">
							bug_report
						</v-icon>
						<h3 class="mb-0">Suporte, issues e débitos técnicos</h3>
					</div>
				</v-expansion-panel-header>
				<v-expansion-panel-content>
					<v-btn
						:disabled="syncing"
						:dark="!syncing"
						color="#fc6d26"
						class="mb-3"
						depressed
						small
						outlined
						@click.stop="syncing = true"
					>
						{{ syncing ? 'Sincronizando...' : 'Sincronizar com GitLab' }}
					</v-btn>
					<gitlab-synchronizer
						v-if="syncing"
						@finished="finishedHandler"
					/>
					<board
						v-if="wasSynced"
						:namespace="`${currentWorkspace.name}problems`"
						:getLists="getIssuesLists"
						:getCards="getCardsByListsIds"
					/>
				</v-expansion-panel-content>
			</v-expansion-panel>
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
						:getLists="{
							resolver: getPlanningLists,
							params: {
								workspaceId: currentWorkspace.id,
							}
						}"
						:getCards="{
							resolver: getCardsByListsIds,
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
import Board from '../components/Board.vue';
import GitlabSynchronizer from '../components/GitlabSynchronizer';
import PlanningGroups from '../constants/PlanningGroups';

import {
	getPlanningLists,
} from '../services/planning';

import {
	getIssuesLists,
} from '../services/issues';

import {
	getCardsByListsIds,
} from '../services/cards';
import { mapGetters } from 'vuex';

export default {
	components: {
		Board,
		GitlabSynchronizer,
	},
	data() {
		return {
			panels: [this.currentWorkspace && !this.currentWorkspace.settings.noPlanningProblems ? 1 : 0],
			syncing: false,
			wasSynced: true,
			PlanningGroups
		}
	},

	computed: {
		...mapGetters('workspaces', ['currentWorkspace'])
	},

	methods: {
		getPlanningLists,
		getIssuesLists,
		getCardsByListsIds,

		finishedHandler() {
			this.syncing = false;
			this.wasSynced = false;
			this.$nextTick().then(() => {
				this.wasSynced = true;
			})
		},

		handleProblemsPanelChange(value) {
			if(this.panels.indexOf(0) > -1) {
				this.showSyncButton = true;
				return;
			}
			this.showSyncButton = false;
		}
	},
}
</script>
