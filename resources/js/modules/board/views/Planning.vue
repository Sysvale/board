<template>
	<v-container
		grid-list-md
		fluid
	>
		<h2 class="mb-3 text-center font-weight-light">Planning Board</h2>
		<v-divider class="py-3"/>
		<board>
			<v-layout
				colum
			>
				<list
					v-for="list in PLANNING_BOARD"
					:id="list.key"
					:key="list.key"
					:title="getListName(list.key)"
					:list="getList(list.key)"
					group="plannig"
					@save="handleSave"
					@delete="handleDelete"
				/>
			</v-layout>
		</board>
	</v-container>
</template>

<script>
import { mapState, mapMutations } from 'vuex';
import makeFormFields from '../../../core/utils/makeFormFields';
import List from '../components/List.vue';
import Board from '../components/Board.vue';
import { PLANNING_BOARD } from '../constants/defaultBoards';

export default {
	components: {
		List,
		Board,
	},

	data() {
		return {
			PLANNING_BOARD,
		};
	},

	computed: {
		...makeFormFields(
			'planning',
			PLANNING_BOARD.map(({key}) => key)
		),
	},

	methods: {
		...mapMutations(
			'planning',
			[
				'addNewTask',
				'removeTask',
			],
		),
		getListName(boardKey) {
			return PLANNING_BOARD.filter(({ key }) => key === boardKey)[0].name;
		},
		getList(boardKey) {
			return this[boardKey];
		},

		handleSave({ listId, title }) {
			this.addNewTask({ listId, title });
		},
		handleDelete({ listId, id }) {
			this.removeTask({ listId, id });
		},
	}
}
</script>
