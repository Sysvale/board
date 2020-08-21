<template>
	<v-expansion-panels
		v-model="panels"
		flat
		multiple
		tile
	>
		<v-expansion-panel
			:key="`impediment-${teamId}`"
		>
			<v-expansion-panel-header>
				<h3>Impedimentos</h3>
			</v-expansion-panel-header>
			<v-expansion-panel-content>
				<impediment-table
					:team-id="teamId"
				/>
			</v-expansion-panel-content>
		</v-expansion-panel>
		<v-expansion-panel
			:key="`notPlanned-${teamId}`"
		>
			<v-expansion-panel-header>
				<h3>Não planejados</h3>
			</v-expansion-panel-header>
			<v-expansion-panel-content>
				<board
					:namespace="`${teamId}-notPlanned`"
					:getLists="getDefaultLists"
					:getCards="{
						resolver: getCardsByListsIds,
						params: {
							teamId,
							boardId: getBoardId(NOT_PLANNED),
						}
					}"
					:card-middleware="{
						teamId,
						boardId: getBoardId(NOT_PLANNED),
					}"
				/>
			</v-expansion-panel-content>
		</v-expansion-panel>
		<v-expansion-panel
			:key="`userStories-${teamId}`"
		>
			<v-expansion-panel-header>
				<h3>Sprint Backlog</h3>
			</v-expansion-panel-header>
			<v-expansion-panel-content>
				<user-stories-board
					:team-id="teamId"
				/>
			</v-expansion-panel-content>
		</v-expansion-panel>
		<v-expansion-panel
			:key="`sprintDevlog-${teamId}`"
		>
			<v-expansion-panel-header>
				<h3>Sprint Devlog</h3>
			</v-expansion-panel-header>
			<v-expansion-panel-content>
				<board
					:namespace="`${teamId}-dev`"
					:getLists="getDefaultLists"
					:getCards="{
						resolver: getCardsByListsIds,
						params: {
							teamId,
							boardId: getBoardId(SPRINT_DEVLOG),
						}
					}"
					:card-middleware="{
						teamId,
						boardId: getBoardId(SPRINT_DEVLOG),
					}"
				/>
			</v-expansion-panel-content>
		</v-expansion-panel>
	</v-expansion-panels>
</template>

<script>
import UserStoriesBoard from './UserStoriesBoard.vue';
import Board from './Board.vue';
import ImpedimentTable from './ImpedimentTable.vue';
import {
	getCardsByListsIds,
} from '../services/cards';

import {
	getDefaultLists,
	getSprintDevlogLists,
	getSprintDevlogTasksByTeam,
	getNotPlannedTasksByTeam,
} from '../services/sprint'
import { mapState } from 'vuex';
import {
	NOT_PLANNED,
	IMPEDIMENTS,
	SPRINT_DEVLOG,
} from '../constants/BoardKeys';

export default {
	components: {
		UserStoriesBoard,
		Board,
		ImpedimentTable,
	},

	props: {
		teamId: {
			type: String,
			default: null,
		},
	},

	data() {
		return {
			panels: [
				2
			],
			NOT_PLANNED,
			IMPEDIMENTS,
			SPRINT_DEVLOG,
		};
	},

	computed: {
		...mapState('boards', {
			boards: 'items',
		}),
	},

	methods: {
		getDefaultLists,
		getCardsByListsIds,
		getSprintDevlogLists,
		getSprintDevlogTasksByTeam,
		getNotPlannedTasksByTeam,

		getBoardId(key) {
			return this.boards.filter(item => item.key === key)[0].id;
		}
	},
}
</script>
<style scoped>
.v-expansion-panel-content__wrap {
	padding: 0!important;
}
.v-expansion-panel {
	background: transparent!important;
}
</style>
