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
							class="py-3 d-flex justify-end align-center"
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
							<v-spacer />
							<v-tooltip
								v-if="story.hasMetric"
								bottom
							>
								<template v-slot:activator="{ on, attrs }">
									<v-icon
										class="mr-2"
										v-bind="attrs"
										v-on="on"
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
										class="mr-2"
										v-bind="attrs"
										v-on="on"
									>
										restore
									</v-icon>
								</template>
								É recorrente
							</v-tooltip>
							<v-tooltip
								v-if="story.isTechnicalWork"
								bottom
							>
								<template v-slot:activator="{ on, attrs }">
									<v-chip
										text-color="black"
										color="#7BD0F4"
										title="Trabalho técnico"
										label
										class="mr-2 px-1 py-1 label-item"
										v-bind="attrs"
										v-on="on"
									>
										<div class="text-uppercase font-weight-medium">
											<v-icon>
												construction
											</v-icon>
										</div>
									</v-chip>
								</template>
								Trabalho técnico
							</v-tooltip>
							<strong
								v-if="story.estimated"
							>
								{{ story.estimated }} pt(s)
							</strong>
						</div>
						<lottie-player
							:src="getLottieFile(story)"
							background="white"
							speed="1"
							style="width: 250px; height: 250px;margin: 0 auto; background: white"
							autoplay
						/>
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
							flat
						>
							<v-expansion-panel
								v-if="story.acceptanceCriteria && story.acceptanceCriteria.length > 0"
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
							<v-expansion-panel
								v-if="story.artifacts && story.artifacts.length > 0"
								class="px-0 py-0"
							>
								<v-expansion-panel-header
									class="px-0 py-0"
								>
									Artefatos
								</v-expansion-panel-header>
								<v-expansion-panel-content
									class="px-0 py-0"
								>
									<ul>
										<li
											v-for="artifact in story.artifacts"
											:key="artifact"
										>
											<artifact-item :artifact="artifact" />
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
						:lists="defaultListsToTeam"
						:get-cards="{
							resolver: getTaskCardsFromUserStory,
							params: {
								userStoryId: story.id,
								teamId,
							}
						}"
						:card-middleware="{
							userStoryId: story.id,
						}"
					/>
				</div>
			</div>
			<div class="py-5">
				<v-divider />
			</div>
		</section>
	</div>
</template>

<script>
import { createNamespacedHelpers, mapActions } from 'vuex';
import makeRequestStore from '../../../core/utils/makeRequestStore';
import convertKeysToCamelCase from '../../../core/utils/convertKeysToCamelCase';

import Board from './Board.vue';
import ArtifactItem from './ArtifactItem.vue';

import {
	getUserStoriesByTeam,
} from '../services/userStories';

import {
	getTaskCardsFromUserStory,
} from '../services/cards';
import UserStoryPipeline from './UserStoryPipeline.vue';
import convertKeysToSnakeCase from '../../../core/utils/convertKeysToSnakeCase';

export default {
	components: {
		ArtifactItem,
		Board,
		UserStoryPipeline,
	},

	props: {
		teamId: {
			type: String,
			default: null,
		},
	},

	data() {
		return {
			pipelineHovered: false,
			defaultListsToTeam: [],
		};
	},

	computed: {
		pipelineMode() {
			return (status) => {
				if (!!status && status !== 'development') return true;
				if (this.pipelineHovered) return true;
				return false;
			};
		},
	},

	beforeCreate() {
		const { teamId } = this.$options.propsData;

		if (teamId) {
			const modules = [
				{ getUserStoriesByTeam },
			];

			const namespace = `userStories-${teamId}`;

			if (!this.$store.hasModule(namespace)) {
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
				// eslint-disable-next-line no-shadow
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

	mounted() {
		this.getUserStoriesByTeam(this.teamId).then((data) => {
			this.setItems(data);
		});
		this.getDefaultLists(convertKeysToSnakeCase({ teamId: this.teamId })).then((data) => {
			this.defaultListsToTeam = convertKeysToCamelCase(data);
		});
	},

	methods: {
		getTaskCardsFromUserStory,
		...mapActions('cards', [
			'updateCard',
		]),
		...mapActions('sprint', [
			'getDefaultLists',
		]),
		handleStatusChange(story, status) {
			this.updateCard(convertKeysToSnakeCase({
				...story,
				status,
			}));
		},
		getLottieFile({ isTechnicalWork }) {
			if (isTechnicalWork) {
				return 'https://assets4.lottiefiles.com/packages/lf20_sy9zodcx.json';
			}
			return 'https://assets8.lottiefiles.com/packages/lf20_n3jsukvi.json';
		},
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
