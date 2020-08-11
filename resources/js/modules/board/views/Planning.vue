<template>
	<v-container
		grid-list-md
		fluid
	>
		<h2 class="mb-3 text-center font-weight-light">Planning Board</h2>
		<v-divider class="py-3"/>
		<default-board
			namespace="planning"
			:getLists="getLists"
			:getCards="getCardsByListsIds"
		/>
	</v-container>
</template>

<script>
import DefaultBoard from '../components/DefaultBoard.vue';
import ListSkeletonLoader from '../components/ListSkeletonLoader.vue';
import {
	getLists,
	getCardsByListsIds,
} from '../services';

export default {
	components: {
		DefaultBoard,
		ListSkeletonLoader,
	},
	methods: {
		getLists,
		getCardsByListsIds,
	}
}
</script>
