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
					:group="namespace"
					:loading="loadingFetchItems"
					:move="onMoveHandler"
					@change="handleChange"
					@save="handleSave"
					@delete="handleDelete"
				/>
			</v-layout>
		</v-layout>
	</board>
</template>

<script>
import { createNamespacedHelpers } from 'vuex';
import makeFormFields from '../../../core/utils/makeFormFields';
import List from '../components/List.vue';
import Board from '../components/Board.vue';
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
		Board,
	},

	props: {
		namespace: {
			type: String,
			default: '',
			required: true,
		},
	},

	beforeCreate() {
		let namespace = this.$options.propsData.namespace;
		const {
			mapActions,
			mapMutations,
			mapGetters,
			mapState,
		} = createNamespacedHelpers(namespace);

		this.$options.computed = {
			...mapState({
				loadingFetchItems: ({ fetchItems }) => fetchItems.isFetching,
			}),
			...mapGetters({
				getTasksByStatus: 'getTasksByStatus',
			}),
			...this.$options.computed,
		};

		this.$options.methods = {
			...mapActions([
				'fetchItems',
			]),
			...mapMutations([
				'setItems',
				'setTaskStatus',
				'addNewTask',
				'removeTask',
			]),
			...this.$options.methods,
		};
	},

	data() {
		return {
			TASK_BOARD,
		};
	},

	mounted() {
		this.fetchItems().then((data) => {
			this.setItems(data);
		})
	},

	methods: {
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

		handleSave({ listId, title }) {
			this.addNewTask({
				listId,
				title,
			});
		},

		handleDelete({ id, listId }) {
			this.removeTask({ id, listId });
		}
	}
}
</script>
