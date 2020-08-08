<template>
	<board>
		<v-layout>
			<v-layout
				colum
			>
				<list
					v-for="list in TASK_BOARD"
					:key="list.key"
					:id="list.key"
					:title="getListName(list.key)"
					:list="getTasksByStatus(list.key)"
					group="dev"
					:move="onMoveHandler"
					@change="handleChange"
					@save="handleSave"
				/>
			</v-layout>
		</v-layout>
	</board>
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
		...mapGetters('dev', {
			userStories: 'getItems',
			getTasksByStatus: 'getTasksByStatus',
		})
	},

	mounted() {
		this.getDevBacklog().then((data) => {
			this.setItems(data);
		});
	},

	methods: {
		...mapActions('dev', [
			'getDevBacklog',
		]),
		...mapMutations('dev', [
			'setItems',
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
					taskId,
					newStatus: to.id,
				});
			}
		},
		handleChange(event) {
			console.log("handleCjange", event)
		},
		handleSave({ status, title }) {
			this.addNewTask({
				status,
				title,
			});
		},
	}
}
</script>
