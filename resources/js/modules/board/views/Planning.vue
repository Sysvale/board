<template>
	<v-container
		fluid
		class="px-0 py-0"
	>
		<board
			namespace="planning"
			:getLists="getPlanningLists"
			:getCards="getCardsByListsIds"
		/>
	</v-container>
</template>

<script>
import Board from '../components/Board.vue';
import {
	getPlanningLists,
} from '../services/planning';

import {
	getCardsByListsIds,
} from '../services/cards';

export default {
	components: {
		Board,
	},
	methods: {
		getPlanningLists,
		getCardsByListsIds,
	},
}
</script>
