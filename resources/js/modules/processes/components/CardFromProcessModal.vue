<template>
	<v-dialog
		v-model="dialog"
		width="600"
	>
		<template v-slot:activator="{ on, attrs }">
			<v-btn
				small
				depressed
				:disabled="$attrs.loading"
				v-bind="attrs"
				v-on="on"
				icon
			>
				<v-icon
					color="rgba(0, 0, 0, 0.5)"
				>
					post_add
				</v-icon>
			</v-btn>
		</template>

		<v-card>
			<v-card-title class="text-h5">
				{{ modalTitle }}
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
					<div
						v-if="!processOnly && selectedChecklist"
					>
					  <div>Items da checklist</div>
						<div class="text--grey caption mb-3">Essa checklist será adicionada automaticamente ao seu card</div>
						<div
							v-for="(checklistItem, i) in selectedChecklist.items"
							:key="i"
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
					</div>
					<div
						v-else-if="!!selectedProcess && selectedProcess.checklists.length > 0 && processOnly"
					>
						<div>Checklists</div>
						<div class="text--grey caption mb-3">Cada uma das checklists abaixo virará uma tarefa para este item de backlog</div>
						<v-expansion-panels
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
				</div>
			</v-card-text>

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
</template>
<script>
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
		};
	},
	computed: {
		modalTitle() {
			if(this.processOnly) {
				return 'Criar item de backlog a partir de um processo';
			}
			return 'Criar card a partir de uma checklist';
		}
	},

	watch: {
		dialog(value) {
			if(!value) {
				this.selectedProcess = null;
				this.selectedChecklist = null;
			}
		},
	},
	methods: {
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
