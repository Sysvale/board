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
				<div
					class="d-flex align-center"
				>
					<div
						class="pb-2 flex-grow-1 d-flex align-items-center"
					>
						<v-chip
							v-if="item.number"
							color="gray"
							text-color="black"
							small
							label
							class="mr-2"
						>
							#{{ item.number }}
						</v-chip>
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
						class="d-flex pt-2"
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
						@click="titleInEditMode = true"
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
import LinkChip from './LinkChip';
import TeamChip from './TeamChip';
import convertKeysToSnakeCase from '../../../core/utils/convertKeysToSnakeCase';

export default {
	components: {
		MemberList,
		LabelList,
		MemberSelect,
		LabelSelect,
		AcceptanceCriteriaForm,
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
		}
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
			this.item.title = _.clone(this.cloneTitle);
			this.$emit('save');
		},
	},
}
</script>
