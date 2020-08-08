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
							<card
								color="#ffff99"
							>
								{{ story.title }}
							</card>
						</div>
					</list-container>
					<v-layout
						colum
					>
						<list
							v-for="list in TASK_BOARD"
							:key="list.key"
							:id="list.key"
							:title="getListName(list.key)"
							:list="getTasksByUserStoryIdAndStatus(story.id, list.key)"
							:group="story.id"
							:move="onMoveHandler"
							@change="handleChange"
							@save="handleSave($event, story.id)"
						/>
					</v-layout>
				</v-layout>
			</board>
		</section>
	</div>
</template>

<script>
import { mapState, mapActions, mapMutations, mapGetters } from 'vuex';
import makeFormFields from '../../../core/utils/makeFormFields';
import List from '../components/List.vue';
import Card from '../components/Card.vue';
import Board from '../components/Board.vue';
import ListContainer from '../components/ListContainer.vue';
import { TASK_BOARD } from '../constants/defaultBoards';
import {
	TODO,
	DEVELOPMENT,
	CODE_REVIEW,
	DONE,
	DEPLOY,
} from '../constants/defaultLists';

export default {
	components: {
		List,
		ListContainer,
		Card,
		Board,
	},

	data() {
		return {
			TASK_BOARD,
		};
	},

	computed: {
		...mapGetters('userStories', {
			userStories: 'getItems',
			getTasksByUserStoryIdAndStatus: 'getTasksByUserStoryIdAndStatus',
		})
	},

	mounted() {
		this.getUserStories().then((data) => {
			this.setItems(data);
		});
	},

	methods: {
		...mapActions('userStories', [
			'getUserStories',
		]),
		...mapMutations('userStories', [
			'setItems',
			'setTasksByUserStoryId',
			'setTaskStatus',
			'addNewTask',
		]),

		getListName(boardKey) {
			return TASK_BOARD
				.filter(({ key }) => key === boardKey)[0].name;
		},
		onMoveHandler({ from, to, draggedContext }) {
			const taskId = draggedContext.element.id;
			const storyId = draggedContext.element.cardId;
			if(to.id != from.id) {
				this.setTaskStatus({
					storyId,
					taskId,
					newStatus: to.id,
				});
			}
		},
		handleChange(event) {
			console.log("handleCjange", event)
		},
		handleSave({ status, title }, storyId) {
			this.addNewTask({
				status,
				title,
				storyId,
			});
		},
	}
}
</script>
