<template>
	<v-container
		grid-list-md
		fluid
	>
		<h2 class="mb-3 text-center font-weight-light">Sprint Board</h2>
		<v-divider class="py-3"/>
		<section>
			<h1>Não planejados</h1>
			<default-board
				namespace="notPlanned"
				:getLists="getDefaultLists"
				:getCards="getCardsByListsIds"
			/>
		</section>
		<v-divider/>
		<section class="mt-10">
			<h1>Sprint Backlog</h1>
			<user-stories-board />
		</section>
		<div class="mt-10">
			<v-divider/>
		</div>
		<section class="mt-10">
			<h1>Sprint Devlog</h1>
			<default-board
				namespace="dev"
				:getLists="getDefaultLists"
				:getCards="getCardsByListsIds"
			/>
		</section>
	</v-container>
</template>

<script>
import UserStoriesBoard from '../components/UserStoriesBoard.vue';
import DefaultBoard from '../components/DefaultBoard.vue';
import {
	getDefaultLists,
	getCardsByListsIds,
} from '../services'

export default {
	components: {
		UserStoriesBoard,
		DefaultBoard,
	},

	methods: {
		getDefaultLists,
		getCardsByListsIds,
	},
}
</script>
