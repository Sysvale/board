<template>
	<v-container
		fluid
		class="px-0 py-0 d-flex"
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
			props: [Object, Function],
			default: null,
		},
		getCards: {
			props: [Object, Function],
			default: null,
		},
	},

	beforeCreate() {
		let namespace = this.$options.propsData.namespace;
		let getLists = this.$options.propsData.getLists;
		let getCards = this.$options.propsData.getCards;

		let requestParams = {
			getCards: _.isFunction(getCards) ? {} : (getCards.params || {}),
			getLists: _.isFunction(getLists) ? {} : (getLists.params || {}),
		};

		const modules = [
			{ getCards: _.isFunction(getCards) ? getCards : getCards.resolver },
			{ getLists: _.isFunction(getLists) ? getLists : getLists.resolver }
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
			requestParams: {
				get() {
					return requestParams;
				}
			},
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
		this.fetchLists({...this.requestParams.getLists}).then((data) => {
			this.lists = data;
			this.fetchCards(
				{ listsIds: this.lists.map(({ id }) => id), ...this.requestParams.getCards }
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
