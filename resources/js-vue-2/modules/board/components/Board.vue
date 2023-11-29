<template>
	<v-container
		fluid
		class="px-0 py-0 d-flex"
	>
		<list-skeleton-loader
			v-if="loadingCards || !shouldShowBoardContent"
		/>
		<board-content
			v-else
			:namespace="namespace"
			:lists="lists"
			:cards="cards"
			:card-middleware="cardMiddleware"
		/>
	</v-container>
</template>

<script>
import { createNamespacedHelpers } from 'vuex';
import makeRequestStore from '../../../core/utils/makeRequestStore';
import convertKeysToSnakeCase from '../../../core/utils/convertKeysToSnakeCase';
import convertKeysToCamelCase from '../../../core/utils/convertKeysToCamelCase';

import BoardContent from './BoardContent.vue';
import ListSkeletonLoader from './ListSkeletonLoader.vue';

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
		lists: {
			type: Array,
			default: () => [],
		},
		getCards: {
			type: [Object, Function],
			default: null,
		},
		cardMiddleware: {
			type: Object,
			default: () => {},
		},
	},

	data() {
		return {
			cards: {},
			shouldShowBoardContent: false,
		};
	},

	computed: {
		loading() {
			return !this.successfulFetched;
		},

		successfulFetched() {
			return !this.loadingCards
				&& ((this.lists && this.lists.length > 0)
				&& Object.keys(this.cards).length > 0);
		},
	},

	watch: {
		lists: {
			handler(newValue) {
				this.shouldShowBoardContent = false;
				if (newValue && newValue.length > 0) {
					this.fetchCards(
						convertKeysToSnakeCase({
							listsIds: this.lists.map(({ id }) => id),
							...this.requestParams.getCards,
						}),
					).then((cards) => {
						let computed = {};
						_.each(cards, (value, key) => {
							computed = {
								[key]: convertKeysToCamelCase(value),
								...computed,
							};
						});
						this.cards = computed;
						this.shouldShowBoardContent = true;
					});
				}
			},
			immediate: true,
		},
	},

	beforeCreate() {
		const { namespace, getCards } = this.$options.propsData;

		const requestParams = {
			getCards: _.isFunction(getCards) ? {} : (getCards.params || {}),
		};

		const modules = [
			{ getCards: _.isFunction(getCards) ? getCards : getCards.resolver },
		];

		if (!this.$store.hasModule(namespace)) {
			this.$store.registerModule(namespace, {
				namespaced: true,
				modules: {
					...modules.reduce((acc, module) => ({
						...acc,
						...makeRequestStore(module),
					}), {}),
				},
			});
		}

		const {
			mapActions,
			mapState,
		} = createNamespacedHelpers(namespace);

		this.$options.computed = {
			...mapState({
				loadingCards: ({ getCards: getCardStore }) => getCardStore.isFetching,
			}),
			requestParams: {
				get() {
					return requestParams;
				},
			},
			...this.$options.computed,
		};

		this.$options.methods = {
			...mapActions({
				fetchCards: 'getCards',
			}),
			...this.$options.methods,
		};
	},
};
</script>
