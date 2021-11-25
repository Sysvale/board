<template>
	<div
		v-if="userStories && userStories.length > 0"
	>
		<section
			v-for="story in userStories"
			:key="story.id"
		>
			<div class="d-flex">
				<div class="mr-1">
					<v-card
						style="max-width: 275px"
						class="px-3 py-3"
						outlined
						flat
					>
						<div
							v-if="story.hasMetric || story.isRecurrent || story.estimated || story.number"
							class="py-3 d-flex justify-end"
						>
							<v-chip
								v-if="story.number"
								color="#efefef"
								text-color="#555"
								small
								label
							>
								#{{ story.number }}
							</v-chip>
							<v-spacer/>
							<v-tooltip
								v-if="story.hasMetric"
								bottom
							>
								<template v-slot:activator="{ on, attrs }">
										<v-icon
											v-bind="attrs"
											v-on="on"
											class="mr-2"
										>
											insights
										</v-icon>
								</template>
								Possui métrica
							</v-tooltip>
							<v-tooltip
								v-if="story.isRecurrent"
								bottom
							>
								<template v-slot:activator="{ on, attrs }">
										<v-icon
											v-bind="attrs"
											v-on="on"
											class="mr-2"
										>
											restore
										</v-icon>
								</template>
								É recorrente
							</v-tooltip>
							<strong
								v-if="story.estimated"
							>
								{{ story.estimated }} pt(s)
							</strong>
						</div>
						<lottie-player
								src="https://assets8.lottiefiles.com/packages/lf20_s3rmq1ty.json"
								background="white"
								speed="1"
								style="width: 250px; height: 250px;margin: 0 auto; background: white"
								autoplay
							>
						</lottie-player>
						<div>
							{{ story.title }}
						</div>
						<div
							class="pt-3"
						>
							<user-story-pipeline
								v-model="story.status"
								:pipeline-mode="pipelineMode(story.status)"
								style="cursor:pointer"
								@mouseover.native="pipelineHovered = true"
								@mouseleave.native="pipelineHovered = false"
								@status-changed="handleStatusChange(story, $event)"
							/>
						</div>
						<v-expansion-panels
							v-if="story.acceptanceCriteria && story.acceptanceCriteria.length > 0"
							flat
						>
							<v-expansion-panel
								class="px-0 py-0"
							>
								<v-expansion-panel-header
									class="px-0 py-0"
								>
									Critérios de aceitação
								</v-expansion-panel-header>
								<v-expansion-panel-content
									class="px-0 py-0"
								>
									<ul>
										<li
											v-for="criteria in story.acceptanceCriteria"
											:key="criteria"
										>
											{{ criteria }}
										</li>
									</ul>
								</v-expansion-panel-content>
							</v-expansion-panel>
						</v-expansion-panels>
					</v-card>
				</div>
				<div
					id="board-outside-wrapper"
					class="ml-2"
				>
					<board
						:namespace="story.id"
						:getLists="{
							resolver: getDefaultLists,
							params: {
								teamId,
							}
						}"
						:getCards="{
							resolver: getCardsByListsIds,
							params: {
								userStoryId: story.id,
							}
						}"
						:card-middleware="{
							userStoryId: story.id,
						}"
					/>
				</div>
			</div>
			<div class="py-5">
				<v-divider/>
			</div>
		</section>
	</div>
</template>

<script>
import { createNamespacedHelpers, mapActions } from 'vuex';
import makeRequestStore from '../../../core/utils/makeRequestStore';
import convertKeysToCamelCase from '../../../core/utils/convertKeysToCamelCase';

import Board from '../components/Board.vue';
import BoardContainer from '../components/BoardContainer.vue';
import ListContainer from '../components/ListContainer.vue';

import {
	getDefaultLists,
} from '../services/sprint';

import {
	getUserStoriesByTeam,
} from '../services/userStories';

import {
	getCardsByListsIds,
} from '../services/cards';
import UserStoryPipeline from './UserStoryPipeline.vue';
import convertKeysToSnakeCase from '../../../core/utils/convertKeysToSnakeCase';

export default {
	components: {
		ListContainer,
		BoardContainer,
		Board,
		UserStoryPipeline,
	},

	beforeCreate() {
		let teamId = this.$options.propsData.teamId;

		if(teamId) {
			let modules = [
				{ getUserStoriesByTeam },
			];

			let namespace = `userStories-${teamId}`;

			if(!this.$store.hasModule(namespace)) {
				this.$store.registerModule(namespace, {
					namespaced: true,
					modules: {
						...modules.reduce((acc, module) => ({
							...acc,
							...makeRequestStore(module),
						}), {}),
					},
					state: {
						items: [],
					},
					mutations: {
						setItems(state, payload) {
							state.items = convertKeysToCamelCase(payload);
						},
					},
				});
			}

			const {
				mapActions,
				mapMutations,
				mapState,
			} = createNamespacedHelpers(namespace);
	
			this.$options.computed = {
				...mapState({
					userStories: 'items',
				}),
				...this.$options.computed,
			};
	
			this.$options.methods = {
				...mapActions([
					'getUserStoriesByTeam',
				]),
				...mapMutations([
					'setItems',
				]),
				...this.$options.methods,
			};
		}
	},

	props: {
		teamId: {
			type: String,
			default: null,
		},
	},

	data() {
		return{
			pipelineHovered: false,
		};
	},

	computed: {
		pipelineMode() {
			return status => {
				if(!!status && status !== 'development') return true;
				if(this.pipelineHovered) return true;
			}
		}
	},

	mounted() {
		this.getUserStoriesByTeam(this.teamId).then((data) => {
			this.setItems(data);
		});
	},

	methods: {
		getDefaultLists,
		getCardsByListsIds,
		...mapActions('cards', [
			'updateCard',
		]),
		handleStatusChange(story, status) {
			this.updateCard(convertKeysToSnakeCase({
				...story,
				status,
			}));
		}
	}
}
</script>
<style scoped>
.v-expansion-panel-content__wrap {
	padding: 0!important;
}
#board-outside-wrapper {
	overflow-x: scroll;
	max-width: 100%;
}
/* width */
#board-outside-wrapper::-webkit-scrollbar {
	width: 0px;
	height: 0px;
	border-radius: 50px;
}

/* Track */
#board-outside-wrapper::-webkit-scrollbar-track {
	background: transparent;
}
</style>
