<template>
	<div>
		<section
			v-for="story in userStories"
			:key="story.id"
		>
			<board-container>
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
				</v-layout>
			</board-container>
		</section>
	</div>
</template>

<script>
import { mapState, mapActions, mapMutations, mapGetters } from 'vuex';
import Board from '../components/Board.vue';
import BoardContainer from '../components/BoardContainer.vue';
import ListContainer from '../components/ListContainer.vue';

import {
	getDefaultLists,
	getUserStoriesTasks,
} from '../services'

export default {
	components: {
		ListContainer,
		BoardContainer,
		Board,
	},

	computed: {
		...mapGetters('userStories', {
			userStories: 'getItems',
		})
	},

	mounted() {
		this.getUserStories().then((data) => {
			this.setItems(data);
		});
	},

	methods: {
		getDefaultLists,
		getUserStoriesTasks,

		...mapMutations('userStories', [
			'setItems',
		]),

		...mapActions('userStories', [
			'getUserStories',
		]),
	}
}
</script>
