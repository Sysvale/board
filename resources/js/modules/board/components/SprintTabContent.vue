<template>
	<v-expansion-panels
		v-model="panels"
		flat
		multiple
		tile
		class="px-2"
	>
		<v-expansion-panel
			:key="`impediment-${teamId}`"
		>
			<v-expansion-panel-header>
				<div class="d-flex align-center">
					<v-icon class="mr-2">
						block
					</v-icon>
					<h3 class="mb-0 mr-2">Impedimentos</h3>
				</div>
			</v-expansion-panel-header>
			<v-expansion-panel-content>
				<events-board
					:team-id="teamId"
					@changed="impedimentsAmount = $event.length"
				/>
			</v-expansion-panel-content>
		</v-expansion-panel>
		<v-expansion-panel
			:key="`notPlanned-${teamId}`"
		>
			<v-expansion-panel-header>
				<div class="d-flex align-center">
					<v-icon class="mr-2">
						local_fire_department
					</v-icon>
					<h3 class="mb-0">Não planejados</h3>
				</div>
			</v-expansion-panel-header>
			<v-expansion-panel-content>
				<board
					:namespace="`${teamId}-notPlanned`"
					:getLists="{
						resolver: getDefaultLists,
						params: {
							teamId,
						},
					}"
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
				<div class="d-flex align-center">
					<v-icon class="mr-2">
						settings_backup_restore
					</v-icon>
					<h3 class="mb-0 mr-2">Sprint Backlog</h3>
					<span class="text--secondary mb-0">
						{{ computedEstimatedAmout }}
					</span>
				</div>
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
				<div class="d-flex align-center">
					<v-icon class="mr-2">
						code
					</v-icon>
					<h3 class="mb-0">Sprint Devlog</h3>
				</div>
			</v-expansion-panel-header>
			<v-expansion-panel-content>
				<board
					:namespace="`${teamId}-dev`"
					:getLists="{
						resolver: getDevlogLists,
						params: {
							teamId,
						}
					}"
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
import EventsBoard from './EventsBoard.vue';
import {
	getCardsByListsIds,
} from '../services/cards';

import {
	getDefaultLists,
	getDevlogLists,
} from '../services/sprint'
import {  mapActions, mapState } from 'vuex';
import {
	NOT_PLANNED,
	IMPEDIMENTS,
	SPRINT_DEVLOG,
} from '../constants/BoardKeys';

import convertKeysToCamelCase from '../../../core/utils/convertKeysToCamelCase';

export default {
	components: {
		UserStoriesBoard,
		Board,
		EventsBoard,
	},

	props: {
		teamId: {
			type: String,
			default: null,
		},
	},

	mounted() {
		this.getCurrentSprintSummaryByTeam(this.teamId)
			.then((data) => {
				const { impedimentsAmount, estimatedAmount } = convertKeysToCamelCase(data);
				this.impedimentsAmount = impedimentsAmount;
				this.estimatedAmount = estimatedAmount;
			});
	},

	data() {
		return {
			panels: [
				2
			],
			NOT_PLANNED,
			IMPEDIMENTS,
			SPRINT_DEVLOG,
			impedimentsAmount: 0,
			estimatedAmount: 0,
		};
	},

	computed: {
		...mapState('boards', {
			boards: 'items',
		}),

		computedEstimatedAmout() {
			if(this.estimatedAmount === 1) {
				return `${this.estimatedAmount} pt`;
			}
			return `${this.estimatedAmount} pts`;
		}
	},

	methods: {
		getDefaultLists,
		getDevlogLists,
		getCardsByListsIds,

		...mapActions('sprint', [
			'getCurrentSprintSummaryByTeam',
		]),

		getBoardId(key) {
			return this.boards.filter(item => item.key === key)[0].id;
		}
	},
}
</script>
