<template>
	<v-container
		fluid
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
				<v-dialog
					v-model="createMode"
					width="500"
				>
					<template v-slot:activator="{}">
						<div
							class="d-flex flex-grow-1 justify-end mb-3"
						>
							<v-btn
								color="primary"
								:disabled="loading"
								@click="createMode = true"
							>
								Novo impedimento
							</v-btn>
						</div>
					</template>
					<v-card
						class="px-2 py-2"
					>
						<v-card-title>
							Novo impedimento
						</v-card-title>
						<v-img
							src="https://2.bp.blogspot.com/-3QrUHMBUoAI/U_Z-kO9ONoI/AAAAAAAAAPM/b2u8pHgaZ_8/s1600/Bandeirinha-se-viu-no-telao.gif"
							width="258"
							height="278"
						/>
						<v-card-text>
							<div>
								<v-text-field
									v-model="newItem.title"
									dense
									outlined
									autofocus
									placeholder="Descrição do impedimento"
								/>
							</div>
							<div>
								<member-select
									v-model="newItem.members"
								/>
							</div>
						</v-card-text>
						<v-card-actions>
							<v-spacer/>
							<v-btn
								text
								@click="createMode = false"
							>
								Cancelar
							</v-btn>
							<v-btn
								color="primary"
								@click="handleAdd"
							>
								Salvar
							</v-btn>
						</v-card-actions>
					</v-card>
				</v-dialog>
				<v-data-table
					:loading="loading"
					:headers="headers"
					:items="items"
					:items-per-page="items.length"
				>
					<template v-slot:item.members="{ item }">
						<member-list
							:members="item.members"
						/>
					</template>

					<template v-slot:item.actions="{ item }">
						<v-btn
							outlined
							color="red"
							small
							:disabled="loading"
							@click="handleRemove(item)"
						>
							Excluir
						</v-btn>
					</template>

					<template v-slot:footer="{}">
						<v-divider
							v-if="createMode"
						/>
						
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
