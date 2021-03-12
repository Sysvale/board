<template>
	<v-dialog
		v-if="item"
		v-model="dialog"
		width="500"
		@click:outside="showDeleteConfirmation = false"
	>
		<template v-slot:activator="{}">
			<v-card
				class="task-card px-3 py-3"
				v-bind="$attrs"
				hover
				:ripple="false"
				v-on="$listeners"
				@click="showModal"
				@mouseover="hover = true"
				@mouseleave="hover = false"
			>
				<div class="d-flex align-center">
					<v-chip
						v-if="item.number"
						color="#efefef"
						text-color="#555"
						small
						label
					>
						#{{ item.number }}
					</v-chip>
					<v-tooltip
						v-if="!isTask && item.hasMetric"
						bottom
					>
						<template v-slot:activator="{ on, attrs }">
							<v-icon
								v-bind="attrs"
								class="mx-2"
								v-on="on"
							>
								assessment
							</v-icon>
						</template>
						Possui métrica
					</v-tooltip>
					<v-tooltip
						v-if="!isTask && item.isRecurrent"
						bottom
					>
						<template v-slot:activator="{ on, attrs }">
							<v-icon
								v-bind="attrs"
								v-on="on"
							>
								restore
							</v-icon>
						</template>
						É recorrente
					</v-tooltip>
					<v-spacer
						v-if="!isTask && item.estimated"
					/>
					<v-chip
						v-if="!isTask && item.estimated"
						color="gray"
						text-color="black"
						label
						small
					>
						<strong>{{ estimated }}</strong>
					</v-chip>
					<v-chip
						v-if="hasChecklist"
						color="transparent"
						text-color="#a3a3a3"
						small
						label
						class="mb-2 justify-end"
					>
						<span
							class="d-flex align-items-center"
							:class="{
								'font-weight-bold': allDoneInChecklist
							}"
						>
							<v-icon
								x-small
								class="mr-1"
							>
								check_box
							</v-icon>
							{{ checklistLabel }}
						</span>
					</v-chip>
				</div>
				<div
					class="d-flex align-center"
				>
					<div
						class="pb-2 flex-grow-1 d-flex align-items-center"
					>
						<label-list
							v-if="isTask && item.labels && item.labels.length"
							:labels="item.labels"
							small
						/>
					</div>
				</div>
				<div
					class="gray--text py-0"
				>
					<slot />
				</div>
				<div
					v-if="isTask"
					class="d-flex align-center pt-2"
				>
					<member-list
						:members="item.members"
					/>
					<div class="d-flex flex-grow-1 justify-end">
						<link-chip
							v-if="item.link"
							:link="item.link"
						/>
					</div>
				</div>
				<div
					v-else-if="item.teamId"
					class="mt-2"
				>
					<team-chip
						:team-id="item.teamId"
					/>
				</div>
			</v-card>
		</template>
		<v-card
			class="px-5 py-5"
		>
			<v-container>
				<div
					class="d-flex"
				>
					<v-chip
						v-if="item.number"
						color="gray"
						text-color="black"
						label
						small
						class="mr-2"
					>
						#{{ item.number }}
					</v-chip>
				</div>
				<v-layout class="py-5">
					<h3
						v-if="!titleInEditMode"
						class="black--text"
						@click="handleEditMode"
					>
						{{ item.title }}
					</h3>
					<v-textarea
						v-else
						v-model="cloneTitle"
						flat
						outlined
						auto-grow
						autofocus
						@blur="handleSave"
					/>
				</v-layout>
			</v-container>

			<v-divider />

			<v-container
				v-if="isTask"
			>
				<v-tabs
					v-model="selectedTab"
					grow
					background-color="transparent"
					color="secondary"
				>
					<v-tab
						v-for="(tab, i) in tabs"
						:key="i"
					>
						{{ tabTitle(tab) }}
					</v-tab>
				</v-tabs>
				<v-tabs-items
					v-model="selectedTab"
				>
					<v-tab-item
						:key="MAIN_TAB"
					>
						<div
							class="pt-5"
						>
							<div class="pb-2 grey--text caption">
								Categorias:
							</div>
							<label-select
								v-model="item.labels"
								small
							/>
						</div>
						<div
							class="py-5"
						>
							<div class="pb-2 grey--text caption">
								Membros:
							</div>
							<member-select
								v-model="item.members"
							/>
						</div>
						<div class="pb-2 grey--text caption">
							Link:
						</div>
						<div
							class="d-flex align-center"
						>
							<v-text-field
								v-model="item.link"
								placeholder="Link"
								flat
								outlined
								dense
							/>
							<div
								v-if="item.link"
								class="ml-2 mt-n6"
							>
								<link-chip
									:link="item.link"
								/>
							</div>
						</div>
					</v-tab-item>
					<v-tab-item
						:key="CHECKLIST_TAB"
					>
						<div
							class="py-5"
						>
							<div class="pb-2 grey--text caption">
								Items:
							</div>
							<checklist-form
								v-model="item.checklist"
							/>
						</div>
					</v-tab-item>
				</v-tabs-items>
			</v-container>
			<v-container
				v-else
			>
				<div class="mt-3 mb-3">
					<switch-button
						v-model="item.hasMetric"
						active-background-color="#23B1C7"
						active-text-color="black"
						class="mr-3"
					>
						<v-icon left>
							assessment
						</v-icon>
						Possui métrica
					</switch-button>
					<switch-button
						v-model="item.isRecurrent"
						active-background-color="#FCBB5A"
						active-text-color="black"
						class="mr-3"
					>
						<v-icon left>
							restore
						</v-icon>
						É recorrente
					</switch-button>
				</div>
				<div class="mb-2">
					<strong>Esforço estimado</strong>
				</div>
				<v-text-field
					v-model="item.estimated"
					outlined
					flat
					dense
					type="number"
				/>
				<div
					v-if="false"
				>
					<strong>Time</strong>
				</div>
				<v-radio-group
					v-if="false"
					v-model="item.teamId"
					class="mt-1"
				>
					<v-radio
						v-for="team in teams"
						:key="team.id"
						:label="team.name"
						:value="team.id"
					/>
				</v-radio-group>

				<div
					class="mb-2"
				>
					<strong>Critérios de aceitação</strong>
				</div>
				<acceptance-criteria-form
					v-model="item.acceptanceCriteria"
				/>
			</v-container>
			<v-card-actions
				class="d-flex justify-start"
			>
				<v-btn
					v-if="!showDeleteConfirmation"
					outlined
					color="red"
					small
					@click="showDeleteConfirmation = true"
				>
					Excluir
				</v-btn>
				<div
					v-else
				>
					<div>
						Tem certeza que deseja excluir este card?
						<div class="mb-3">
							<div class="grey--text caption">
								Essa ação não poderá ser desfeita
							</div>
						</div>
					</div>
					<v-btn
						outlined
						color="red"
						small
						@click="$emit('delete')"
					>
						Sim
					</v-btn>
					<v-btn
						outlined
						color="secondary"
						small
						@click="showDeleteConfirmation = false"
					>
						Não
					</v-btn>
				</div>
			</v-card-actions>
		</v-card>
	</v-dialog>
</template>

<script>
import { mapActions, mapGetters } from 'vuex';
import MemberList from './MemberList.vue';
import LabelList from './LabelList.vue';
import MemberSelect from './MemberSelect.vue';
import LabelSelect from './LabelSelect.vue';
import AcceptanceCriteriaForm from './AcceptanceCriteriaForm.vue';
import ChecklistForm from './ChecklistForm.vue';
import LinkChip from './LinkChip.vue';
import TeamChip from './TeamChip.vue';
import SwitchButton from './SwitchButton.vue';
import convertKeysToSnakeCase from '../../../core/utils/convertKeysToSnakeCase';

const MAIN_TAB = 'Informações gerais';
const CHECKLIST_TAB = 'Checklist';

export default {
	components: {
		MemberList,
		LabelList,
		MemberSelect,
		LabelSelect,
		AcceptanceCriteriaForm,
		ChecklistForm,
		LinkChip,
		TeamChip,
		SwitchButton,
	},

	props: {
		item: {
			type: Object,
			default: () => {},
		},
	},

	data() {
		return {
			hover: false,
			dialog: false,
			titleInEditMode: false,
			// evitar que enquanto digita dispare o watch
			cloneTitle: _.clone(this.item.title),
			showLinkField: false,
			MAIN_TAB,
			CHECKLIST_TAB,
			tabs: [MAIN_TAB, CHECKLIST_TAB],
			selectedTab: MAIN_TAB,
			showDeleteConfirmation: false,
		};
	},

	computed: {
		...mapGetters('labels', {
			labels: 'itemsByWorkspace',
		}),
		...mapGetters('teams', {
			teams: 'itemsByWorkspace',
		}),

		isTask() {
			return !this.item.isUserStory;
		},
		estimated() {
			if (+this.item.estimated === 1) {
				return `${this.item.estimated} pt`;
			}
			return `${this.item.estimated} pts`;
		},

		hasChecklist() {
			return this.item.checklist && this.item.checklist.length > 0;
		},

		checklistLabel() {
			const doneLenght = this.item.checklist.filter((item) => !!item.done).length;
			const { length } = this.item.checklist;

			return `${doneLenght}/${length}`;
		},

		allDoneInChecklist() {
			return this.item.checklist.filter(
				(item) => !!item.done,
			).length === this.item.checklist.length;
		},

		tabTitle() {
			return (tab) => {
				switch (tab) {
				case CHECKLIST_TAB:
					if (this.hasChecklist) {
						return `${tab} (${this.checklistLabel})`;
					}
					return tab;

				default:
					return tab;
				}
			};
		},
	},

	watch: {
		item: {
			handler(newValue) {
				if (this.dialog) {
					this.updateCard(convertKeysToSnakeCase(newValue));
				}
			},
			deep: true,
		},
	},

	methods: {
		...mapActions('cards', [
			'updateCard',
		]),

		showModal() {
			this.dialog = true;
		},

		handleSave() {
			this.titleInEditMode = false;

			if (!this.cloneTitle || this.cloneTitle.trim().length === 0) {
				return;
			}

			this.item.title = _.clone(this.cloneTitle);

			this.$emit('save');
		},

		handleEditMode() {
			this.cloneTitle = _.clone(this.item.title);
			this.titleInEditMode = true;
		},
	},
};
</script>
