<template>
	<div>
		<v-row
			v-if="!createMode"
		>
			<v-btn
				color="blue darken-1"
				text
				@click="createMode = true"
			>
				Criar checklist a partir de um processo
			</v-btn>
		</v-row>
		<div
			v-else
		>
			<v-card
				outlined
				elevation="0"
			>
				<v-card-text>
					<v-row>
						<v-select
							v-model="selectedProcess"
							:items="processes"
							placeholder="Processo"
							return-object
							item-text="name"
							:disabled="loading"
						/>
					</v-row>
					<v-row>
						<v-select
							v-if="selectedProcess"
							v-model="selectedChecklist"
							:items="selectedProcess.checklists"
							placeholder="Checklist"
							return-object
							item-text="title"
							item-value="items"
							:disabled="loading"
						/>
					</v-row>
					<v-row>
						<v-alert
							v-if="withAlert"
							text
							type="info"
							dense
							outlined
						>
							Ao criar uma checklist a partir de um processo, os itens atuais serão substituídos.
						</v-alert>
					</v-row>
					<v-row>
						<v-spacer></v-spacer>
						<v-btn
							color="blue darken-1"
							text
							@click="createMode = false"
						>
							Cancelar
						</v-btn>

						<v-btn
							color="blue darken-1"
							text
							:disabled="!selectedChecklist"
							@click="handleCreateClick"
						>
							Criar
						</v-btn>
					</v-row>
				</v-card-text>
			</v-card>
		</div>
	</div>
</template>
<script>
import { mapActions, mapMutations, mapState } from 'vuex';
export default {
	props: {
		withAlert: {
			type: Boolean,
			default: false,
		}
	},
	data() {
		return {
			selectedProcess: null,
			selectedChecklist: null,
			createMode: false,
		};
	},
	computed: {
		...mapState('processes', {
			processes: state => state.items,
			loading: ({ getProcesses }) => getProcesses.isFetching,
		}),
	},

	watch: {
		createMode(value) {
			if(value) {
				this.fetchProcesses();
			}
		}
	},
	methods: {
		isEmpty: (arg) => _.isEmpty(arg),
		...mapActions('processes', [
			'getProcesses',
		]),

		...mapMutations('processes', {
			setProcesses: 'setItems',
		}),

		fetchProcesses() {
			this.getProcesses().then((items) => {
				this.setProcesses(items);
			});
		},

		handleCreateClick() {
			this.$emit('create', this.selectedChecklist.items);
			this.$nextTick().then(() => {
				this.createMode = false;
			})
		}
	},
}
</script>
