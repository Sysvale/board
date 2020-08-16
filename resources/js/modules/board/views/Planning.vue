<template>
	<v-container
		fluid
		class="px-0 py-0"
	>
		<board
			namespace="planning"
			:getLists="{
				resolver: getLists,
				params: {
					boardId: 'planning',
				},
			}"
			:getCards="getCardsByListsIds"
		/>
	</v-container>
</template>

<script>
import Board from '../components/Board.vue';
import ListSkeletonLoader from '../components/ListSkeletonLoader.vue';
import {
	getLists,
	getCardsByListsIds,
} from '../services';

export default {
	components: {
		Board,
		ListSkeletonLoader,
	},
	methods: {
		getLists,
		getCardsByListsIds,
	},
}
</script>
