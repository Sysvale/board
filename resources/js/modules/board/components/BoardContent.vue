<template>
	<board-container>
		<v-layout
			v-if="lists.length > 0 && Object.keys(cards).length > 0"
			colum
		>
			<list
				v-for="list in lists"
				:id="list.id"
				:key="list.id"
				:title="list.name"
				:list="getList(list.id)"
				:group="namespace"
				:move="moveCallback"
				@add="handleAdd"
				@delete="handleDelete"
				@change="handleChange(list.id)"
			/>
		</v-layout>
	</board-container>
</template>

<script>
import { createNamespacedHelpers, mapActions } from 'vuex';
import makeFormFields from '../../../core/utils/makeFormFields';
import convertKeysToSnakeCase from '../../../core/utils/convertKeysToSnakeCase';
import List from '../components/List.vue';
import BoardContainer from '../components/BoardContainer.vue';
import makeBoardStore from '../../../core/utils/makeBoardStore';
import generateUUID from '../../../core/utils/generateUUID';

export default {
	components: {
		List,
		BoardContainer,
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
		cards: {
			type: Object,
			default: () => {},
		},
		cardMiddleware: {
			type: Object,
			default: () => {},
		},
	},

	beforeCreate() {
		let namespace = this.$options.propsData.namespace;
		let lists = this.$options.propsData.lists;
		let cards = this.$options.propsData.cards;
		if(
			lists && lists.length > 0
			&& cards && !_.isEmpty(cards)
		) {
	
			this.$store.registerModule([
				namespace, 'board'
			], makeBoardStore(lists));

			let nestedNamespace = `${namespace}/board`;
	
			const {
				mapActions,
				mapMutations,
				mapGetters,
				mapState,
			} = createNamespacedHelpers(nestedNamespace);
	
			this.$options.computed = {
				...makeFormFields(
					nestedNamespace,
					[...lists.map(({ id }) => id)]
				),
				...this.$options.computed,
			};
	
			this.$options.methods = {
				...mapMutations([
					...lists.map(({ id }) => `set${id}`),
					'addNewTask',
					'removeTask',
					'setCards',
					'setCard',
				]),
				...this.$options.methods,
			};
			this.$store.commit(`${nestedNamespace}/setCards`, cards);
		}
	},

	methods: {
		...mapActions('cards', [
			'deleteCard',
			'createCard',
			'updateCard',
			'updateCardsPositions',
			'updateCardsLists',
		]),

		getList(boardListId) {
			return this[boardListId];
		},

		handleAdd({ boardListId, title }) {
			this.createCard(convertKeysToSnakeCase({
				boardListId,
				title,
				...this.cardMiddleware,
			})).then((data) => {
				this.addNewTask({ ...data });
				this.updateCardsPositions(
						this[boardListId].map((item, position) => ({
						id: item.id,
						position,
					}))
				);
			});
		},

		handleDelete({ boardListId, id }) {
			this.deleteCard(id).then(() => {
				this.removeTask({ boardListId, id });
			});
		},

		// Chamada para atualizar as posições no backend
		handleChange(boardListId) {
			this.updateCardsPositions(
					this[boardListId].map((item, position) => ({
					id: item.id,
					position,
				}))
			);
		},

		moveCallback(ev) {
			const element = ev.draggedContext.element;
			const fromSizeBefore = this[ev.from.id].length + 0; // force var to lose the reference
			const toSizeBefore = this[ev.to.id].length + 0;
			if(ev.from !== ev.to) {
				// a little trick/gamb here rsrsrsrs
				setTimeout(() => {
					if(
						fromSizeBefore != this[ev.from.id].length
						&& toSizeBefore != this[ev.to.id].length
					) {
						this.updateCard(
							convertKeysToSnakeCase({
								...element,
								boardListId: ev.to.id,
							})
						).then((data) => {
							this.setCard(data);
							this.updateCardsPositions(
									this[ev.from.id].map((item, position) => ({
									id: item.id,
									position,
								}))
							);
							this.updateCardsPositions(
									this[ev.to.id].map((item, position) => ({
									id: item.id,
									position,
								}))
							);
						});
					}
				}, 500);
			}
		}
	}
}
</script>
