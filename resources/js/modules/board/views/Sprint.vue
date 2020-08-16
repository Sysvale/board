<template>
	<v-container
		grid-list-md
		fluid
	>
		<h2 class="mb-3 text-center font-weight-light">Sprint Board</h2>
		<v-divider class="py-3"/>
		<section
			v-if="false"
		>
			<h1>Impedimentos</h1>
			<impediment-table/>
		</section>
		<section
			v-if="false"
		>
			<h1>Não planejados</h1>
			<board
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
			<board
				namespace="dev"
				:getLists="getDefaultLists"
				:getCards="getCardsByListsIds"
			/>
		</section>
	</v-container>
</template>

<script>
import UserStoriesBoard from '../components/UserStoriesBoard.vue';
import Board from '../components/Board.vue';
import ImpedimentTable from '../components/ImpedimentTable.vue';
import {
	getDefaultLists,
	getCardsByListsIds,
} from '../services'

export default {
	components: {
		UserStoriesBoard,
		Board,
		ImpedimentTable,
	},

	methods: {
		getDefaultLists,
		getCardsByListsIds,
	},
}
</script>
