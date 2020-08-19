<template>
	<div
		v-if="userStories && userStories.length > 0"
	>
		<section
			v-for="story in userStories"
			:key="story.id"
		>
			<v-layout>
				<div class="mr-3 mt-4">
					<v-card
						class="task-card px-3 py-3"
						outlined
						flat
					>
						<div class="py-3 text-right">
							<v-chip
								color="gray"
								text-color="black"
								label
							>
								<strong>{{ story.estimated }} pt(s)</strong>
							</v-chip>
						</div>
						<v-img
							src="https://i.pinimg.com/originals/77/75/5e/77755e565ef7ddbff2546231cd8732bf.png"
							width="250px"
						/>
						<div>
							{{ story.title }}
						</div>
						<div
							class="pt-3"
						>
							<v-divider/>
						</div>
						<v-expansion-panels
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
				<div class="mr-3">
					<v-divider
						vertical
					/>
				</div>
				<div>
					<board
						:namespace="story.id"
						:getLists="getDefaultLists"
						:getCards="{
							resolver: getUserStoriesTasks,
							params: {
								userStoryId: story.id,
							}
						}"
					/>
				</div>
			</v-layout>
			<div class="py-5">
				<v-divider/>
			</div>
		</section>
	</div>
</template>

<script>
import { createNamespacedHelpers } from 'vuex';
import makeRequestStore from '../../../core/utils/makeRequestStore';

import Board from '../components/Board.vue';
import BoardContainer from '../components/BoardContainer.vue';
import ListContainer from '../components/ListContainer.vue';

import {
	getDefaultLists,
} from '../services/sprint';

import {
	getUserStoriesByTeam,
	getUserStoriesTasks,
} from '../services/userStories';

export default {
	components: {
		ListContainer,
		BoardContainer,
		Board,
	},

	beforeCreate() {
		let teamId = this.$options.propsData.teamId;

		if(teamId) {
			let modules = [
				{ getUserStoriesByTeam },
			];

			let namespace = `userStories-${teamId}`;

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
						state.items = payload;
					},
				},
			});
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

	mounted() {
		this.getUserStoriesByTeam(this.teamId).then((data) => {
			this.setItems(data);
		});
	},

	methods: {
		getDefaultLists,
		getUserStoriesTasks,
	}
}
</script>
<style scoped>
.v-expansion-panel-content__wrap {
	padding: 0!important;
}
</style>
