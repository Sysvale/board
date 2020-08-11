<template>
	<board>
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
				@save="handleSave"
				@delete="handleDelete"
			/>
		</v-layout>
	</board>
</template>

<script>
import { createNamespacedHelpers } from 'vuex';
import makeFormFields from '../../../core/utils/makeFormFields';
import List from '../components/List.vue';
import Board from '../components/Board.vue';
import defaultListsBoardStore from '../../../core/utils/defaultListsBoardStore';
import generateUUID from '../../../core/utils/generateUUID';

export default {
	components: {
		List,
		Board,
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
	
			this.$store.registerModule(namespace, defaultListsBoardStore(lists));
	
			const {
				mapActions,
				mapMutations,
				mapGetters,
				mapState,
			} = createNamespacedHelpers(namespace);
	
			this.$options.computed = {
				...makeFormFields(
					namespace,
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
			this.$store.commit(`${namespace}/setCards`, cards);
		}
	},

	methods: {
		getList(listId) {
			return this[listId];
		},
		handleSave({ listId, title }) {
			this.addNewTask({ listId, title });
		},
		handleDelete({ listId, id }) {
			this.removeTask({ listId, id });
		},
	}
}
</script>
