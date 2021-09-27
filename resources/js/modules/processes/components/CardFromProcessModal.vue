<template>
	<v-menu
		top
		close-on-content-click
	>
		<template v-slot:activator="{ on, attrs }">
			<v-btn
				block
				small
				depressed
				:disabled="$attrs.loading"
				color="white"
				class="ml-2"
				v-bind="attrs"
				v-on="on"
			>
				<v-icon
					color="rgba(0, 0, 0, 0.5)"
				>
					more_vert
				</v-icon>
			</v-btn>
		</template>

		<v-list>
			<v-list-item>
				<v-dialog
					v-model="dialog"
					width="500"
				>
					<template v-slot:activator="{ on, attrs }">
						<v-list-item-title
							v-bind="attrs"
							v-on="on"
						>
							Criar a partir de um processo
						</v-list-item-title>
					</template>

					<v-card>
						<v-card-title class="text-h5 grey lighten-2">
							Criação a partir de processos
						</v-card-title>

						<v-card-text>
							<checklist-from-process-select
								:process-only="processOnly"
								external-creation
								form-first
								@process-selected="selectedProcess = $event"
								@checklist-selected="selectedChecklist = $event"
							/>
							<div>
								<v-expansion-panels
									v-if="!!selectedProcess && selectedProcess.checklists.length > 0"
									flat
								>
									<v-expansion-panel
										v-for="(item, i) in selectedProcess.checklists"
										:key="i"
									>
										<v-expansion-panel-header>
											{{ item.title }}
										</v-expansion-panel-header>
										<v-expansion-panel-content>
											<div
												v-for="(checklistItem, j) in item.items"
												:key="j"
												class="px-2"
											>
												<div
													class="d-flex"
												>
													<v-icon
														small
														class="mr-2"
													>
														check_box_outline_blank
													</v-icon>
													{{ checklistItem.description }}
												</div>
											</div>
										</v-expansion-panel-content>
									</v-expansion-panel>
								</v-expansion-panels>
							</div>
						</v-card-text>

						<v-divider></v-divider>

						<v-card-actions>
							<v-spacer></v-spacer>
							<v-btn
								color="primary"
								text
								@click="handleCreateClick"
							>
								Criar
							</v-btn>
						</v-card-actions>
					</v-card>
				</v-dialog>
			</v-list-item>
		</v-list>
	</v-menu>
</template>
<script>
import { mapActions, mapMutations, mapState } from 'vuex';
import ChecklistFromProcessSelect from './ChecklistFromProcessSelect.vue';
export default {
  components: { ChecklistFromProcessSelect },
	props: {
		processOnly: {
			type: Boolean,
			default: false,
		}
	},
	data() {
		return {
			dialog: false,
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
			if(this.processOnly) {
				this.$emit('create', this.selectedProcess);
			} else {
				this.$emit('create', this.selectedChecklist);
			}
			this.$nextTick().then(() => {
				this.dialog = false;
			})
		},
	},
}
</script>
