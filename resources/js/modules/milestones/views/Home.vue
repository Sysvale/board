<template>
	<v-container>
		<v-skeleton-loader
			v-if="loading"
			class="mx-auto"
			type="table"
		/>
		<v-data-table
			v-else
			:headers="headers"
			:items="filteredMilestones"
			sort-by="name"
			class="elevation-1"
			show-expand
			single-expand
		>
			<template v-slot:top>
				<v-toolbar
					flat
				>
					<v-toolbar-title>Milestones</v-toolbar-title>
					<v-divider
						class="mx-4"
						inset
						vertical
					/>
					<v-spacer />
					<v-dialog
						v-model="dialog"
						max-width="500px"
					>
						<template v-slot:activator="{ on, attrs }">
							<v-btn
								color="primary"
								dark
								class="mb-2"
								v-bind="attrs"
								v-on="on"
							>
								Novo milestone
							</v-btn>
						</template>
						<v-card>
							<v-card-title>
								<span class="headline">{{ formTitle }}</span>
							</v-card-title>

							<v-card-text>
								<v-container>
									<v-row>
										<v-text-field
											v-model="selectedItem.title"
											label="Título"
										/>
									</v-row>
									<v-row>
										<v-col>
											<div>
												Início
											</div>
											<v-menu
												v-model="startDateMenu"
												:close-on-content-click="false"
												:nudge-right="40"
												transition="scale-transition"
												offset-y
												min-width="290px"
											>
												<template v-slot:activator="{ on, attrs }">
													<v-text-field
														:value="getSelectedEventFormattedDate(selectedItem.startDate)"
														prepend-icon="insert_invitation"
														readonly
														outlined
														flat
														dense
														v-bind="attrs"
														v-on="on"
													/>
												</template>
												<v-date-picker
													v-model="selectedItem.startDate"
													locale="pt-BR"
													no-title
													@input="startDateMenu = false"
												/>
											</v-menu>
										</v-col>
										<v-col>
											<div>
												Fim
											</div>
											<v-menu
												v-model="endDateMenu"
												:close-on-content-click="false"
												:nudge-right="40"
												transition="scale-transition"
												offset-y
												min-width="290px"
											>
												<template v-slot:activator="{ on, attrs }">
													<v-text-field
														:value="getSelectedEventFormattedDate(selectedItem.endDate)"
														prepend-icon="insert_invitation"
														readonly
														outlined
														flat
														dense
														:disabled="!selectedItem.startDate"
														v-bind="attrs"
														v-on="on"
													/>
												</template>
												<v-date-picker
													v-model="selectedItem.endDate"
													locale="pt-BR"
													no-title
													:min="selectedItem.startDate"
													@input="endDateMenu = false"
												/>
											</v-menu>
										</v-col>
									</v-row>
									<v-row>
										<v-textarea
											v-model="selectedItem.description"
											label="Descrição"
											flat
											outlined
											auto-grow
										/>
									</v-row>
									<v-row>
										<div class="mb-2">
											<strong>Times</strong>
										</div>
										<teams-select
											v-model="selectedItem.teamIds"
										/>
									</v-row>
									<v-row>
										<div class="mt-5">
											<strong>Critérios de aceitação</strong>
										</div>
										<checklist-form
											v-model="selectedItem.acceptanceCriteria"
										/>
									</v-row>
								</v-container>
							</v-card-text>

							<v-card-actions>
								<v-spacer />
								<v-btn
									color="blue darken-1"
									text
									@click="close"
								>
									Cancelar
								</v-btn>
								<v-btn
									color="blue darken-1"
									text
									:disabled="!formIsValid"
									@click="save"
								>
									Salvar
								</v-btn>
							</v-card-actions>
						</v-card>
					</v-dialog>
					<v-dialog
						v-model="dialogDelete"
						max-width="500px"
					>
						<v-card>
							<v-card-title
								class="headline"
							>
								Deseja mesmo deletar {{ selectedItem.name }}?
							</v-card-title>
							<v-card-actions>
								<v-spacer />
								<v-btn
									color="blue
									darken-1"
									text
									@click="closeDelete"
								>
									Cancelar
								</v-btn>
								<v-btn
									color="blue darken-1"
									text
									@click="deleteItemConfirm"
								>
									Sim
								</v-btn>
								<v-spacer />
							</v-card-actions>
						</v-card>
					</v-dialog>
				</v-toolbar>
				<v-container>
					<strong>Times</strong>
					<teams-select
						v-model="selectedTeams"
					/>
				</v-container>
			</template>
			<template v-slot:item.title="{ item }">
				<a href="javascript:void(0)" @click="goTo(item.id)">
					{{ item.title }}
				</a>
			</template>
			<template v-slot:item.acceptanceCriteriaRatio="{ item }">
				{{ getAcceptanceCriteriaRatio(item.acceptanceCriteria) }}
			</template>
			<template v-slot:item.actions="{ item }">
				<v-btn
					icon
					@click="editItem(item)"
				>
					<v-icon
						small
						class="mr-2"
					>
						edit
					</v-icon>
				</v-btn>
				<v-btn
					icon
					@click="deleteItem(item)"
				>
					<v-icon
						small
					>
						delete
					</v-icon>
				</v-btn>
			</template>
			<template v-slot:expanded-item="{ item }">
				<v-container>
					<div>
						{{ item.description }}
					</div>
					<div class="py-2">
						<strong>Times</strong>
						<div>{{ getTeamsNames(item.teamIds) }}</div>
					</div>
					<div class="py-2">
						<strong>
							<small>Critérios de aceitação {{ getAcceptanceCriteriaRatio(item.acceptanceCriteria) }}</small>
						</strong>
						<ul>
							<li
								v-for="criteria in item.acceptanceCriteria"
								:key="criteria.description"
								:class="{'done' : criteria.done }"
							>
								{{ criteria.description }}
							</li>
						</ul>
					</div>
				</v-container>
			</template>
		</v-data-table>
	</v-container>
</template>

<script>
import moment from 'moment';
import intersection from 'lodash.intersection';
import { mapActions, mapMutations, mapState } from 'vuex';
import convertKeysToSnakeCase from '../../../core/utils/convertKeysToSnakeCase';
import TeamsSelect from '../components/TeamsSelect.vue';
import ChecklistForm from '../../board/components/ChecklistForm.vue';

export default {
	components: {
		TeamsSelect,
		ChecklistForm,
	},
	data() {
		return {
			dialog: false,
			dialogDelete: false,
			headers: [
				{
					text: 'Título',
					align: 'start',
					sortable: true,
					value: 'title',
				},
				{
					text: 'Critérios de aceitação',
					align: 'start',
					sortable: true,
					value: 'acceptanceCriteriaRatio',
				},
				{
					text: 'Início',
					align: 'start',
					sortable: true,
					value: 'startDate',
				},
				{
					text: 'Fim',
					align: 'start',
					sortable: true,
					value: 'endDate',
				},
				{ text: 'Ações', value: 'actions', sortable: false },
			],
			editMode: false,
			selectedItem: {},
			startDateMenu: false,
			endDateMenu: false,
			selectedTeams: [],
		};
	},

	computed: {
		...mapState('teams', {
			teams: 'items',
		}),

		formTitle() {
			return !this.editMode ? 'Novo milestone' : 'Editar item';
		},

		formIsValid() {
			return !!this.selectedItem.title && this.selectedItem.title.trim();
		},

		...mapState('milestones', {
			milestones: ({ items }) => items,
			isGetting: ({ getMilestones }) => getMilestones.isFetching,
			isCreating: ({ createMilestone }) => createMilestone.isFetching,
			isUpdating: ({ updateMilestone }) => updateMilestone.isFetching,
			isRemoving: ({ deleteMilestone }) => deleteMilestone.isFetching,
		}),

		loading() {
			return this.isGetting
				|| this.isCreating
				|| this.isUpdating
				|| this.isRemoving;
		},

		filteredMilestones() {
			const selectedTeamsIds = this.selectedTeams.map((item) => item.id || item);
			return this.milestones
				.filter((item) => intersection(selectedTeamsIds, item.teamIds).length > 0);
		},
	},

	watch: {
		dialog(val) {
			// eslint-disable-next-line no-unused-expressions
			val || this.close();
		},
		dialogDelete(val) {
			// eslint-disable-next-line no-unused-expressions
			val || this.closeDelete();
		},

		teams: {
			handler(newValue) {
				this.selectedTeams = newValue?.filter(({ id }) => id);
			},
			immediate: true,
		},

	},

	beforeMount() {
		this.fetchMilestones();
	},

	methods: {
		...mapActions('milestones', [
			'getMilestones',
			'deleteMilestone',
			'updateMilestone',
			'createMilestone',
		]),

		...mapMutations('milestones', [
			'setItems',
		]),

		editItem(item) {
			this.editMode = true;
			this.selectedItem = {
				...item,
			};
			this.dialog = true;
		},

		deleteItem(item) {
			this.selectedItem = {
				...item,
			};
			this.dialogDelete = true;
		},

		deleteItemConfirm() {
			this.deleteMilestone(this.selectedItem.id)
				.then(() => {
					this.fetchMilestones();
				});
			this.closeDelete();
		},

		close() {
			this.dialog = false;
			this.$nextTick(() => {
				this.selectedItem = {};
				this.editMode = false;
			});
		},

		closeDelete() {
			this.dialogDelete = false;
			this.$nextTick(() => {
				this.selectedItem = {};
				this.editMode = false;
			});
		},

		save() {
			const milestone = {
				...this.selectedItem,
			};
			if (this.editMode) {
				this.updateMilestone(convertKeysToSnakeCase(milestone))
					.then(() => {
						this.fetchMilestones();
					});
			} else {
				this.createMilestone(convertKeysToSnakeCase(milestone))
					.then(() => {
						this.fetchMilestones();
					});
			}
			this.close();
		},

		fetchMilestones() {
			this.getMilestones().then((items) => {
				this.setItems(items);
			}).finally(() => {
				this.selectedItem = {};
			});
		},

		getSelectedEventFormattedDate(date) {
			return date ? moment(date).locale('pt-BR').format('DD/MM/YY') : '';
		},
		goTo(id) {
			this.$router.push({
				name: 'milestones.board',
				params: {
					id,
				},
			});
		},
		getAcceptanceCriteriaRatio(acceptanceCriteria) {
			return `${acceptanceCriteria.filter(({ done }) => done)?.length || 0}/${(acceptanceCriteria?.length || 0)}`;
		},

		getTeamsNames(teamIds) {
			return this.teams
				.filter((team) => teamIds.indexOf(team.id) > -1)
				.map(({ name }) => name)
				.reduce((acc, curr, index, arr) => {
					if (index === arr.length - 1) {
						acc += curr;
					} else {
						acc += `${curr}, `;
					}
					return acc;
				}, '');
		},
	},
};
</script>
