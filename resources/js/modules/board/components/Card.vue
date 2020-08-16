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
					v-if="isTask"
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
					Breakout Room
				</div>
				<v-radio-group
					v-model="item.breakout"
					class="mt-1"
				>
					<v-radio label="Breakout 1" value="radio-1"></v-radio>
					<v-radio label="Breakout 2" value="radio-2"></v-radio>
					<v-radio label="Breakout 3" value="radio-3"></v-radio>
					<v-radio label="Breakout 4" value="radio-4"></v-radio>
					<v-radio label="Breakout 5" value="radio-5"></v-radio>
				</v-radio-group>
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

export default {
	components: {
		MemberList,
		LabelList,
		MemberSelect,
		LabelSelect,
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
