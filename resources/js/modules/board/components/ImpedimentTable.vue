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
							class="mb-n6 d-flex flex-grow-1 justify-center"
						>
							<v-btn
								dark
								fab
								top
								right
								color="pink"
								@click="createMode = true"
							>
								<v-icon>mdi-plus</v-icon>
							</v-btn>
						</div>
					</template>
					<v-card
						class="px-5 py-5"
					>
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
					class="elevation-1"
				>
					<template v-slot:item.members="{ item }">
						<member-list
							:members="item.members"
						/>
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
import { mapActions, mapMutations, mapState } from 'vuex';
import MemberList from './MemberList';
import MemberSelect from './MemberSelect';
export default {
	components: {
		MemberList,
		MemberSelect,
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
			],
		};
	},

	created() {
		this.getImpediments().then((data) => {
			this.setItems(data);
		});
	},

	computed: {
		...mapState('impediments', {
			items: 'items',
			isFetching: ({ getImpediments }) => getImpediments.isFetching,
			isCreating: ({ createImpediment }) => createImpediment.isFetching,
		}),

		loading() {
			return this.isCreating;
		}
	},

	methods: {
		...mapActions('impediments', [
			'getImpediments',
			'createImpediment',
		]),

		...mapMutations('impediments', [
			'setItems',
			'addItem',
		]),

		handleAdd() {
			this.createImpediment(this.newItem).then((data) => {
				this.addItem(data);
				this.createMode = false;
				this.newItem = {};
			});
		},
	},
}
</script>
