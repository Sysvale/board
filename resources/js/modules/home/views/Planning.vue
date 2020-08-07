<template>
	<v-container
		grid-list-md
		fluid
	>
		<h2 class="mb-3 text-center font-weight-light">Planning Board</h2>
		<v-divider class="py-3"/>
		<v-layout
			colum
		>
			<list-container>
				<list
					:title="getListName(DEV_LOG)"
					:list="getList(DEV_LOG)"
					:group="boardContext"
				/>
				<list
					:title="getListName(BUGS)"
					:list="getList(BUGS)"
					:group="boardContext"
					class="mt-10"
				/>
			</list-container>
			<list-container>
				<list
					:title="getListName(BACKLOG)"
					:list="getList(BACKLOG)"
					:group="boardContext"
				/>
			</list-container>
			<list-container>
				<list
					:title="getListName(TEAM_ONE)"
					:list="getList(TEAM_ONE)"
					:group="boardContext"
				/>
			</list-container>
			<list-container>
				<list
					:title="getListName(TEAM_TWO)"
					:list="getList(TEAM_TWO)"
					:group="boardContext"
				/>
			</list-container>
		</v-layout>
	</v-container>
</template>

<script>
import { mapState } from 'vuex';
import makeFormFields from '../../../core/utils/makeFormFields';
import List from '../components/List.vue';
import ListContainer from '../components/ListContainer.vue';
import { PLANNING_BOARD } from '../constants/defaultBoards';
import {
	BACKLOG,
	TEAM_ONE,
	TEAM_TWO,
	DEV_LOG,
	BUGS,
} from '../constants/defaultLists';

export default {
	components: {
		List,
		ListContainer,
	},

	data() {
		return {
			BACKLOG,
			TEAM_ONE,
			TEAM_TWO,
			DEV_LOG,
			BUGS,
		};
	},

	computed: {
		...makeFormFields(
			'planning',
			PLANNING_BOARD.map(({key}) => key)
		),
		boardContext() {
			return 'planning';
		},
	},

	methods: {
		getListName(boardKey) {
			return PLANNING_BOARD.filter(({ key }) => key === boardKey)[0].name;
		},
		getList(boardKey) {
			return this[boardKey];
		},
	}
}
</script>
