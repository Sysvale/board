<template>
	<v-container
		grid-list-md
		fluid
	>
		<h2 class="mb-3 text-center font-weight-light">Planning Board</h2>
		<v-divider class="py-3"/>
		<div
			v-if="loading"
		>
			Carregando...
		</div>
		<default-board
			v-else
			namespace="planning"
			:lists="lists"
			:cards="cards"
		/>
	</v-container>
</template>

<script>
import { mapState, mapMutations, mapActions } from 'vuex';
import makeFormFields from '../../../core/utils/makeFormFields';
import List from '../components/List.vue';
import DefaultBoard from '../components/DefaultBoard.vue';
import { PLANNING_BOARD } from '../constants/defaultBoards';

export default {
	components: {
		DefaultBoard,
	},

	data() {
		return {
			lists: [],
			cards: {},
		};
	},

	created() {
		this.getLists().then((data) => {
			this.lists = data;
			this.getCardsByListsIds(
				this.lists.map(({ id }) => id)
			).then((cards) => {
				this.cards = cards;
			});
		});
	},

	computed: {
		...mapState('getCardsByListsIds', {
			loadingCards: ({ isFetching }) => isFetching,
		}),
		...mapState('getLists', {
			loadingLists: ({ isFetching}) => isFetching,
		}),

		loading() {
			return ;
		},

		successfulFetched() {
			return !(this.loadingLists
				|| this.loadingCards)
				&& (this.lists.length > 0
					&& Object.keys(this.cards).length > 0)
		},

		loading() {
			return !this.successfulFetched;
		},
	},

	methods: {
		...mapActions('getCardsByListsIds', [
			'getCardsByListsIds'
		]),
		...mapActions('getLists', [
			'getLists'
		]),
	}
}
</script>
