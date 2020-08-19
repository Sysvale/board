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
							class="d-flex flex-grow-1 justify-end"
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
import { createNamespacedHelpers } from 'vuex';
import MemberList from './MemberList';
import MemberSelect from './MemberSelect';

import {
	getImpedimentsByTeam,
	createImpediment,
	deleteImpediment,
} from '../services/impediments';
import makeRequestStore from '../../../core/utils/makeRequestStore';

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
				{ createImpediment },
				{ deleteImpediment },
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
						state.items = payload;
					},
					addItem(state, payload) {
						state.items = [
							payload,
							...state.items,
						];
					},
					removeItem(state, payload) {
						state.items = state.items.filter(({ id }) => id !== payload.id);
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
					isCreating: ({ createImpediment }) => createImpediment.isFetching,
					isRemoving: ({ deleteImpediment }) => deleteImpediment.isFetching,
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
		this.getImpedimentsByTeam().then((data) => {
			this.setItems(data);
		});
	},

	computed: {
		loading() {
			return this.isCreating || this.isRemoving;
		}
	},

	methods: {
		handleAdd() {
			this.createImpediment({
				...this.newItem, teamId: this.teamId
			}).then((data) => {
				this.addItem(data);
				this.createMode = false;
				this.newItem = {};
			});
		},

		handleRemove(item) {
			this.deleteImpediment(item).then((data) => {
				this.removeItem(data);
			});
		},
	},
}
</script>
