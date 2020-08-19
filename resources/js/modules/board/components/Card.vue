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
						v-if="item.labels && item.labels.length"
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
							v-if="isTask"
							:labels="item.labels"
						/>
						<v-chip
							v-else-if="item.teamId"
							:text-color="'#fff'"
							:color="'#333'"
							class="ml-1"
							:title="teams.filter(({ id }) => id === item.teamId)[0].name"
							small
						>
							<small>
								<strong>{{ teams.filter(({ id }) => id === item.teamId)[0].name }}</strong>
							</small>
						</v-chip>
					</div>
				</div>
					<div
						class="gray--text py-0"
					>
						<slot />
					</div>
					<div class="d-flex pt-3">
						<div class="d-flex justify-start flex-grow-1">
							<member-list
								v-if="isTask"
								:members="item.members"
							/>
						</div>
						<div>
							<v-chip
								v-if="item.link"
								:href="item.link"
								target="blank"
								avatar
								small
								:color="link.color"
								:text-color="link.textColor"
								@click.native.stop
							>
								<strong>{{ link.label }}</strong>
							</v-chip>
						</div>
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
					<label-list
						v-if="isTask"
						:labels="item.labels"
					/>
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
				<div
					v-if="isTask"
					class="d-flex pt-3"
				>
					<div class="d-flex justify-start flex-grow-1">
						<member-list
							:members="item.members"
						/>
					</div>
					<div>
						<v-chip
							v-if="item.link"
							:href="item.link"
							target="blank"
							avatar
							small
							:color="link.color"
							:text-color="link.textColor"
							@click.native.stop
						>
							<strong>{{ link.label }}</strong>
						</v-chip>
					</div>
				</div>
			</v-container>
			<v-divider/>
			<v-container
				v-if="isTask"
			>
				<div class="pb-2">
					Categorias:
				</div>
				<label-select
					v-model="item.labels"
				/>
				<div class="pb-2">
					Membros:
				</div>
				<member-select
					v-model="item.members"
				/>
				<div class="pb-2">
					Link:
				</div>
				<v-text-field
					v-model="item.link"
					placeholder="Link"
					flat
					outlined
					dense
				/>
			</v-container>
			<v-container
				v-else
			>
				<div>
					Time
				</div>
				<v-radio-group
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
					Critérios de aceitação
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

export default {
	components: {
		MemberList,
		LabelList,
		MemberSelect,
		LabelSelect,
		AcceptanceCriteriaForm,
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
		link() {
			if(this.item.link) {
				return {
					label: `!132`,
					color: '#fc6d26',
					textColor: 'white',
				};
			}
			return null;
		},
		isTask() {
			return false;
		}
	},

	watch: {
		item: {
			handler(newValue, oldValue) {
				this.updateCard(newValue);
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
