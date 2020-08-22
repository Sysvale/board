<template>
	<v-container
		fluid
		class="px-0 py-0"
	>
		<v-fade-transition
			hide-on-leave
		>
			<v-skeleton-loader
				v-if="isFetching"
				type="table"
			/>
			<div
				v-else
			>
				<v-data-table
					:loading="loading"
					:headers="headers"
					:items="items"
					hide-default-footer
					class="impediments-table"
				>
					<template v-slot:item.members="{ item }">
						<member-list
							:members="item.members"
						/>
					</template>

					<template v-slot:item.actions="{ item }">
						<v-btn
							text
							color="red"
							x-small
							:disabled="loading"
							@click="handleRemove(item)"
						>
							Excluir
						</v-btn>
					</template>

					<template v-slot:body.append="{ headers }">
						<tr>
							<td :colspan="headers.length" class="text-center py-2">
								<v-btn
									v-if="!createMode"
									text
									small
									color="primary"
									@click="createMode = true"
								>
									<v-icon class="mr-2">
										add_circle_outline
									</v-icon>
									Novo impedimento
								</v-btn>
								<div
									v-else
									class="d-flex align-center"
								>
									<v-text-field
										v-model="newItem.title"
										dense
										outlined
										autofocus
										placeholder="Descrição do impedimento"
										hide-details
										class="mr-2"
									/>
									<member-select
										v-model="newItem.members"
									/>
									<v-btn
										text
										@click="createMode = false"
									>
										Cancelar
									</v-btn>
									<v-btn
										color="primary"
										outlined
										@click="handleAdd"
									>
										Salvar
									</v-btn>
								</div>
							</td>
						</tr>
					</template>
				</v-data-table>
			</div>
		</v-fade-transition>
	</v-container>
</template>
<script>
import { createNamespacedHelpers, mapActions, mapState } from 'vuex';
import MemberList from './MemberList';
import MemberSelect from './MemberSelect';

import {
	getImpedimentsByTeam,
} from '../services/cards';

import makeRequestStore from '../../../core/utils/makeRequestStore';
import convertKeysToSnakeCase from '../../../core/utils/convertKeysToSnakeCase';
import convertKeysToCamelCase from '../../../core/utils/convertKeysToCamelCase';
import { IMPEDIMENTS } from '../constants/BoardKeys';

export default {
	components: {
		MemberList,
		MemberSelect,
	},

	beforeCreate() {
		let teamId = this.$options.propsData.teamId;

		if(teamId) {
			const modules = [
				{ getImpedimentsByTeam },
			];

			let namespace = `impediments-${teamId}`;

			this.$store.registerModule(namespace, {
				namespaced: true,
				modules: {
					...modules.reduce((acc, module) => ({
						...acc,
						...makeRequestStore(module),
					}), {}),
				},
				state: {
					items: [],
				},
				mutations: {
					setItems(state, payload) {
						state.items = convertKeysToCamelCase(payload);
					},
					addItem(state, payload) {
						state.items = [
							convertKeysToCamelCase(payload),
							...state.items,
						];
					},
					removeItem(state, id) {
						state.items = state.items.filter(item => item.id !== id);
					},
				},
			});
			const {
				mapActions,
				mapMutations,
				mapState,
			} = createNamespacedHelpers(namespace);
	
			this.$options.computed = {
				...mapState({
					items: 'items',
					isFetching: ({ getImpedimentsByTeam }) => getImpedimentsByTeam.isFetching,
				}),
				...this.$options.computed,
			};
	
			this.$options.methods = {
				...mapActions([
					'getImpedimentsByTeam',
					'createImpediment',
					'deleteImpediment',
				]),

				...mapMutations([
					'setItems',
					'addItem',
					'removeItem',
				]),
				...this.$options.methods,
			};
		}
	},

	props: {
		teamId: {
			type: String,
			default: null,
		},
	},

	data() {
		return {
			createMode: false,
			newItem: {},
			headers: [
				{
					text: 'Descrição',
					align: 'start',
					sortable: false,
					value: 'title',
				},
				{
					text: 'Membros',
					align: 'start',
					sortable: false,
					value: 'members',
				},
				{
					text: '-',
					align: 'end',
					sortable: false,
					value: 'actions',
				},
			],
		};
	},

	created() {
		this.getImpedimentsByTeam(this.teamId)
			.then((data) => {
				this.setItems(data);
			});
	},

	computed: {
		...mapState('cards', {
			isCreating: ({ createCard }) => createCard.isFetching,
			isRemoving: ({ deleteCard }) => deleteCard.isFetching,
		}),

		...mapState('boards', {
			boards: 'items',
		}),

		loading() {
			return this.isCreating || this.isRemoving;
		},

		boardId() {
			return this.boards.filter(item => item.key === IMPEDIMENTS )[0].id;
		},
	},

	methods: {
		...mapActions('cards', [
			'createCard',
			'deleteCard',
		]),

		handleAdd() {
			this.createCard(convertKeysToSnakeCase({
				...this.newItem,
				teamId: this.teamId,
				boardId: this.boardId,
			})).then((data) => {
				this.addItem(data);
				this.createMode = false;
				this.newItem = {};
			});
		},

		handleRemove({ id }) {
			this.deleteCard(id).then(() => {
				this.removeItem(id);
			});
		},
	},
}
</script>
<style scoped>
.impediments-table {
	border: thin solid rgba(0, 0, 0, .12);
}
</style>
