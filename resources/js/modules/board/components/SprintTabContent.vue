<template>
	<div>
		<section>
			<h1>Impedimentos</h1>
			<impediment-table
				:team-id="teamId"
			/>
		</section>
		<section>
			<h1>Não planejados</h1>
			<board
				:namespace="`${teamId}-notPlanned`"
				:getLists="getDefaultLists"
				:getCards="{
					resolver: getNotPlannedTasksByTeam,
					params: {
						teamId,
					}
				}"
			/>
		</section>
		<v-divider/>
		<section class="mt-10">
			<h1>Sprint Backlog</h1>
			<user-stories-board
				:team-id="teamId"
			/>
		</section>
		<div class="mt-10">
			<v-divider/>
		</div>
		<section class="mt-10">
			<h1>Sprint Devlog</h1>
			<board
				:namespace="`${teamId}-dev`"
				:getLists="getSprintDevlogLists"
				:getCards="{
					resolver: getSprintDevlogTasksByTeam,
					params: {
						teamId,
					}
				}"
			/>
		</section>
	</div>
</template>

<script>
import UserStoriesBoard from './UserStoriesBoard.vue';
import Board from './Board.vue';
import ImpedimentTable from './ImpedimentTable.vue';
import {
	getCardsByListsIds,
} from '../services'

import {
	getDefaultLists,
	getSprintDevlogLists,
	getSprintDevlogTasksByTeam,
	getNotPlannedTasksByTeam,
} from '../services/sprint'

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

	methods: {
		getDefaultLists,
		getCardsByListsIds,
		getSprintDevlogLists,
		getSprintDevlogTasksByTeam,
		getNotPlannedTasksByTeam,
	},
}
</script>
