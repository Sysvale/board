<template>
	<div>
		<section
			v-for="story in userStories"
			:key="story.id"
		>
			<board>
				<v-layout>
					<list-container>
						<div class="mt-16 pt-7">
							<v-card
								class="task-card"
								color="#ffff99"
							>
								{{ story.title }}
							</v-card>
						</div>
					</list-container>
					<default-board
						:namespace="story.id"
						:getLists="getDefaultLists"
						:getCards="getCardsByListsIds"
					/>
				</v-layout>
			</board>
		</section>
	</div>
</template>

<script>
import { mapState, mapActions, mapMutations, mapGetters } from 'vuex';
import Board from '../components/Board.vue';
import DefaultBoard from '../components/DefaultBoard.vue';
import ListContainer from '../components/ListContainer.vue';

import {
	getDefaultLists,
	getCardsByListsIds,
} from '../services'

export default {
	components: {
		ListContainer,
		DefaultBoard,
	},

	computed: {
		...mapGetters('userStories', {
			userStories: 'getItems',
		})
	},

	mounted() {
		this.setItems(
			[
				{
					id: '1',
					title: 'User Story 1',
					teamId: 'x',
				},
				{
					id: '2',
					title: 'User Story 2',
					teamId: 'x',
				},
			],
		);
		//this.getUserStories().then((data) => {
		//});
	},

	methods: {
		getDefaultLists,
		getCardsByListsIds,
		...mapMutations('userStories', [
			'setItems',
		]),
	}
}
</script>
