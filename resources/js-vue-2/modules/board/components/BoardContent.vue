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
				:collapsed="list.collapsed"
				:accepts-card-type="list.acceptsCardType"
				:is-goalable="list.isGoalable"
				:key-value="list.key"
				:name="list.name"
				:list="getList(list.id)"
				:group="namespace"
				@add="handleAdd"
				@delete="handleDelete"
				@deleteAllCards="handleDeleteAllCards"
			/>
			<v-snackbar
				v-model="snackbar"
				:timeout="-1"
			>
				Opa! Temos um probleminha na atualização das listas.
				Estamos trabalhado para resolvê-lo.
				Enquanto isso, a solução é recarregar a página e tentar efetuar a ação novamente.

				<template v-slot:action="{ attrs }">
					<v-btn
						color="blue"
						text
						v-bind="attrs"
						@click="reload()"
					>
						Recarregar
					</v-btn>
				</template>
			</v-snackbar>
		</v-layout>
	</board-container>
</template>

<script>
import { createNamespacedHelpers, mapActions } from 'vuex';
import makeFormFields from '../../../core/utils/makeFormFields';
import convertKeysToSnakeCase from '../../../core/utils/convertKeysToSnakeCase';
import convertKeysToCamelCase from '../../../core/utils/convertKeysToCamelCase';
import List from './List.vue';
import BoardContainer from './BoardContainer.vue';
import makeBoardStore from '../../../core/utils/makeBoardStore';

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

	data() {
		return {
			snackbar: false,
		};
	},

	beforeCreate() {
		const { namespace, lists, cards } = this.$options.propsData;

		if (lists && lists.length > 0
			&& cards && !_.isEmpty(cards)
		) {
			if (!this.$store.hasModule([namespace, 'board'])) {
				this.$store.registerModule([namespace, 'board'], makeBoardStore(lists));
			}

			const nestedNamespace = `${namespace}/board`;
			const {	mapMutations } = createNamespacedHelpers(nestedNamespace);

			this.$options.computed = {
				...makeFormFields(
					nestedNamespace,
					[...lists.map(({ id }) => id)],
				),
				...this.$options.computed,
			};

			this.$options.watch = {
				...lists.reduce((acc, list) => ({
					...acc,
					[list.id]: {
						handler: (newValue) => {
							if (newValue !== null && newValue.length > 0) {
								const cardsToUpdate = newValue.map((item, position) => (convertKeysToSnakeCase({
									id: item.id,
									position,
									boardListId: list.id,
									type: list.acceptsCardType,
								})));

								this.updateCardsPositions(cardsToUpdate);
							}
						},
					},
				}), {}),
			};

			this.$options.methods = {
				...mapMutations([
					...lists.map(({ id }) => `set${id}`),
					'addNewTask',
					'removeTask',
					'removeAllTasks',
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
			'deleteManyCards',
			'createCard',
			'updateCard',
			'updateCardsPositions',
			'updateCardsLists',
		]),

		getList(boardListId) {
			return this[boardListId];
		},

		handleAdd({ subTasks, teamKey, ...newCardData }) {
			this.createCard(convertKeysToSnakeCase({
				...newCardData,
				...this.cardMiddleware,
				teamKey: subTasks ? teamKey : null,
			})).then((data) => {
				this.addNewTask({ ...data });
				const { firstDefaultBoardListId } = convertKeysToCamelCase(data);
				if (subTasks) {
					subTasks.forEach((task) => {
						this.createCard(convertKeysToSnakeCase({
							title: task.title,
							checklist: task.items,
							userStoryId: data.id,
							type: 'task',
							boardListId: firstDefaultBoardListId,
						}));
					});
				}
			});
		},

		handleDelete({ boardListId, id }) {
			this.deleteCard(id).then(() => {
				this.removeTask({ boardListId, id });
			});
		},

		handleDeleteAllCards(payload) {
			this.deleteManyCards(payload).then(() => {
				this.removeAllTasks(payload);
			});
		},

		reload() {
			window.location.reload();
			this.snackbar = false;
		},
	},
};
</script>
