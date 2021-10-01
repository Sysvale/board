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
				elevation="0"
			>
				<v-card-text>
					<v-row>
						<v-autocomplete
							v-model="selectedProcess"
							:items="processes"
							placeholder="Processo"
							return-object
							item-text="name"
							outlined
							dense
							:disabled="loading"
						/>
					</v-row>
					<v-row
						v-if="!processOnly"
					>
						<v-autocomplete
							v-if="selectedProcess"
							v-model="selectedChecklist"
							:items="selectedProcess.checklists"
							placeholder="Checklist"
							return-object
							outlined
							dense
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
					<v-row
						v-if="!externalCreation"
					>
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
		},
		processOnly: {
			type: Boolean,
			default: false,
		},
		externalCreation: {
			type: Boolean,
			default: false,
		},
		formFirst: {
			type: Boolean,
			default: false,
		},
	},
	data() {
		return {
			selectedProcess: null,
			selectedChecklist: null,
			createMode: this.formFirst,
		};
	},
	computed: {
		...mapState('processes', {
			processes: state => state.items,
			loading: ({ getProcesses }) => getProcesses.isFetching,
		}),
	},

	watch: {
		createMode: {
			handler(value) {
				if(value) {
					this.fetchProcesses();
				} else {
					this.selectedProcess = null;
					this.selectedChecklist = null;
				}
			},
			immediate: true,
		},
		selectedProcess: {
			handler(newValue) {
				this.$emit('process-selected', newValue);
			},
			deep: true,
		},
		selectedChecklist: {
			handler(newValue) {
				this.$emit('checklist-selected', newValue);
			},
			deep: true,
		},
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
