<template>
	<v-container
		fluid
		class="px-0 py-0"
	>
		<board
			namespace="planning"
			:getLists="getLists"
			:getCards="getCardsByListsIds"
		/>
	</v-container>
</template>

<script>
import Board from '../components/Board.vue';
import {
	getLists,
	getCardsByListsIds,
} from '../services/planning';

export default {
	components: {
		Board,
	},
	methods: {
		getLists,
		getCardsByListsIds,
	},
}
</script>
