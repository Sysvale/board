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
			'updateCards',
		]),

		getList(listId) {
			return this[listId];
		},

		handleAdd({ listId, title }) {
			this.createCard({ listId, title }).then((data) => {
				this.addNewTask({ ...data });
			});
		},

		handleDelete({ listId, id }) {
			this.deleteCard(id).then(() => {
				this.removeTask({ listId, id });
			});
		},

		// Chamada para atualizar as posições no backend
		handleChange(listId) {
			this.updateCards(
					this[listId].map((item, position) => ({
					...item,
					position,
				}))
			);
		},
	}
}
</script>
