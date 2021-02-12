<template>
	<v-dialog
		v-if="item"
		v-model="dialog"
		width="500"
	>
		<template v-slot:activator="{}">
				<v-card
					class="task-card px-3 py-3"
					v-bind="$attrs"
					v-on="$listeners"
					hover
					:ripple="false"
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
						class="mb-2"
					>
						#{{ item.number }}
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
						@click="handleEditMode"
						class="black--text"
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
			<v-divider/>
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
					outlined
					color="red"
					small
					@click="$emit('delete')"
				>
					Excluir
				</v-btn>
			</v-card-actions>
		</v-card>
	</v-dialog>
</template>
<script>
import { mapActions, mapState } from 'vuex';
import MemberList from './MemberList';
import LabelList from './LabelList';
import MemberSelect from './MemberSelect';
import LabelSelect from './LabelSelect';
import AcceptanceCriteriaForm from './AcceptanceCriteriaForm';
import ChecklistForm from './ChecklistForm';
import LinkChip from './LinkChip';
import TeamChip from './TeamChip';
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
		};
	},

	computed: {
		...mapState('labels', {
			labels: 'items',
		}),
		...mapState('teams', {
			teams: 'items',
		}),
		
		isTask() {
			return !!!this.item.isUserStory;
		},
		estimated() {
			if(+this.item.estimated === 1) {
				return `${this.item.estimated} pt`
			}
			return `${this.item.estimated} pts`;
		},

		hasChecklist() {
			return this.item.checklist && this.item.checklist.length > 0;
		},

		checklistLabel() {
			const doneLenght = this.item.checklist.filter((item) => {
				return !!item.done;
			}).length;
			const { length } = this.item.checklist;
			return `${doneLenght}/${length}`;
		},

		allDoneInChecklist() {
			return this.item.checklist.filter((item) => {
				return !!item.done;
			}).length === this.item.checklist.length;
		},

		tabTitle() {
			return tab => {
				switch(tab) {
					case CHECKLIST_TAB:
						if(this.hasChecklist) {
							return `${tab} (${this.checklistLabel})`;
						}
						return tab;
					default:
						return tab;
				}
			}
		},
	},

	watch: {
		item: {
			handler(newValue, oldValue) {
				this.updateCard(convertKeysToSnakeCase(newValue));
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
			if(!this.cloneTitle || this.cloneTitle.trim().length === 0 ) {
				return;
			}
			this.item.title = _.clone(this.cloneTitle);
			this.$emit('save');
		},

		handleEditMode() {
			this.cloneTitle = _.clone(this.item.title);
			this.titleInEditMode = true;
		}
	},
}
</script>
