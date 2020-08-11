<template>
	<v-container
		fluid
	>
		<list-skeleton-loader
			v-if="loading"
		/>
		<board-content
			v-else
			:namespace="namespace"
			:lists="lists"
			:cards="cards"
		/>
	</v-container>
</template>

<script>
import makeRequestStore from '../../../core/utils/makeRequestStore';
import BoardContent from '../components/BoardContent.vue';
import ListSkeletonLoader from '../components/ListSkeletonLoader.vue';
import { createNamespacedHelpers } from 'vuex';

export default {
	components: {
		BoardContent,
		ListSkeletonLoader,
	},

	props: {
		namespace: {
			type: String,
			default: null,
		},
		getLists: {
			props: Function,
			default: null,
		},
		getCards: {
			props: Function,
			default: null,
		},
	},

	beforeCreate() {
		let namespace = this.$options.propsData.namespace;
		let getLists = this.$options.propsData.getLists;
		let getCards = this.$options.propsData.getCards;

		const modules = [
			{ getLists },
			{ getCards },
		];

		this.$store.registerModule(namespace, {
			namespaced: true,
			modules: {
				...modules.reduce((acc, module) => ({
					...acc,
					...makeRequestStore(module),
				}), {}),
			},
		});

		const {
			mapActions,
			mapState,
		} = createNamespacedHelpers(namespace);

		this.$options.computed = {
			...mapState({
				loadingLists: ({ getLists }) => getLists.isFetching,
				loadingCards: ({ getCards }) => getCards.isFetching,
			}),
			...this.$options.computed,
		};

		this.$options.methods = {
		...mapActions({
			fetchCards: 'getCards',
			fetchLists: 'getLists',
		}),
			...this.$options.methods,
		};
	},

	data() {
		return {
			lists: [],
			cards: {},
		};
	},

	created() {
		this.fetchLists().then((data) => {
			this.lists = data;
			this.fetchCards(
				this.lists.map(({ id }) => id)
			).then((cards) => {
				this.cards = cards;
			});
		});
	},

	computed: {

		loading() {
			return !this.successfulFetched;
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
}
</script>
