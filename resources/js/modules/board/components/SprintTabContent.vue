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
					:lists="defaultListsToTeam"
					:get-cards="{
						resolver: getTaskCardsFromNotPlanned,
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
			:key="`kaizen-${teamId}`"
		>
			<v-expansion-panel-header>
				<div class="d-flex align-center">
					<span
						class="d-flex align-center"
					>
						<v-icon class="mr-2">
							moving
						</v-icon>
						<h3 class="mb-0">Kaizen (item de melhoria)</h3>
					</span>
				</div>
			</v-expansion-panel-header>

			<v-expansion-panel-content>
				<board
					:namespace="`${teamId}-kaizen`"
					:lists="defaultListsToTeam"
					:get-cards="{
						resolver: getTaskCardsFromKaizen,
						params: {
							teamId,
							boardId: getBoardId(KAIZEN),
						}
					}"
					:card-middleware="{
						teamId,
						boardId: getBoardId(KAIZEN),
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
						{{ computedEstimatedAmount }}
					</span>
					<v-dialog
						v-model="dialog"
						width="100%"
						height="100%"
						scrollable
						@click:outside="dialog = false"
						@keydown="dialog = false"
					>
						<template v-slot:activator="{ on, attrs }">
							<v-btn
								text
								class="ml-auto"
								v-bind="attrs"
								v-on="on"
								@click.stop
							>
								Visão geral
							</v-btn>
						</template>
						<v-card>
							<sprint-backlog-overview
								v-if="dialog"
								:team-id="teamId"
							/>
						</v-card>
					</v-dialog>
				</div>
			</v-expansion-panel-header>

			<v-expansion-panel-content>
				<user-stories-board
					:team-id="teamId"
				/>
			</v-expansion-panel-content>
		</v-expansion-panel>

		<v-expansion-panel
			v-if="!currentWorkspace.settings.noSprintDevlog"
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
					:lists="defaultDevlogListsToTeam"
					:get-cards="{
						resolver: getTaskCardsFromDevlog,
						params: {
							teamId,
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
import { mapActions, mapGetters, mapState } from 'vuex';
import UserStoriesBoard from './UserStoriesBoard.vue';
import Board from './Board.vue';
import EventsBoard from './EventsBoard.vue';
import SprintBacklogOverview from './SprintBacklogOverview.vue';
import {
	getTaskCardsFromDevlog,
	getTaskCardsFromNotPlanned,
	getTaskCardsFromKaizen,
} from '../services/cards';

import {
	NOT_PLANNED,
	IMPEDIMENTS,
	SPRINT_DEVLOG,
	KAIZEN,
} from '../constants/BoardKeys';

import convertKeysToCamelCase from '../../../core/utils/convertKeysToCamelCase';
import convertKeysToSnakeCase from '../../../core/utils/convertKeysToSnakeCase';

export default {
	components: {
		UserStoriesBoard,
		Board,
		EventsBoard,
		SprintBacklogOverview,
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
				3,
			],
			NOT_PLANNED,
			IMPEDIMENTS,
			SPRINT_DEVLOG,
			KAIZEN,
			impedimentsAmount: 0,
			estimatedAmount: 0,
			dialog: false,
			defaultListsToTeam: [],
			defaultDevlogListsToTeam: [],
		};
	},

	computed: {
		...mapState('boards', {
			boards: 'items',
		}),

		...mapGetters('workspaces', ['currentWorkspace']),

		computedEstimatedAmount() {
			if (this.estimatedAmount === 1) {
				return `${this.estimatedAmount} pt`;
			}
			return `${this.estimatedAmount} pts`;
		},
	},

	mounted() {
		this.getCurrentSprintSummaryByTeam(this.teamId)
			.then((data) => {
				const { impedimentsAmount, estimatedAmount } = convertKeysToCamelCase(data);
				this.impedimentsAmount = impedimentsAmount;
				this.estimatedAmount = estimatedAmount;
			});

		this.getDefaultLists(convertKeysToSnakeCase({ teamId: this.teamId })).then((data) => {
			this.defaultListsToTeam = convertKeysToCamelCase(data);
		});

		this.getDevlogLists(convertKeysToSnakeCase({ teamId: this.teamId })).then((data) => {
			this.defaultDevlogListsToTeam = convertKeysToCamelCase(data);
		});
	},

	methods: {
		getTaskCardsFromDevlog,
		getTaskCardsFromNotPlanned,
		getTaskCardsFromKaizen,

		...mapActions('sprint', [
			'getCurrentSprintSummaryByTeam',
			'getDefaultLists',
			'getDevlogLists',
		]),

		getBoardId(key) {
			return this.boards.filter((item) => item.key === key)[0].id;
		},
	},
};
</script>
