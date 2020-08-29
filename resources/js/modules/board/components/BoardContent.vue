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
	
			if(!this.$store.hasModule([
				namespace, 'board'
			])) {
				this.$store.registerModule([
					namespace, 'board'
				], makeBoardStore(lists));
			}

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

	data() {
		return {
			snackbar: false,
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
			const fromSizeBefore = this[ev.from.id].length + 1 - 1; // force var to lose the reference
			const toSizeBefore = this[ev.to.id].length + 1 - 1;
			if(ev.from !== ev.to) {
				// a little trick/gamb here rsrsrsrs
				setTimeout(() => {
					if(
						fromSizeBefore != this[ev.from.id].length
						&& toSizeBefore != this[ev.to.id].length
					) {
						this.updateCard(
							convertKeysToSnakeCase({
								...{
									...element,
									...this.cardMiddleware,
								},
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
					} else {
						this.snackbar = true;
					}
				}, 1000);
			}
		},

		reload() {
			window.location.reload();
			this.snackbar = false;
		}
	}
}
</script>
