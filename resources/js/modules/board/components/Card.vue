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
					outlined
					flat
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
						class="pb-2 flex-grow-1"
					>
						<v-chip
							v-if="item.number"
							color="gray"
							text-color="black"
							x-small
							label
						>
							#{{ item.number }}
						</v-chip>
						<v-chip
							v-for="(label, i) in (item.labels || [])"
							:key="label.id"
							:color="label.color || 'gray'"
							:text-color="label.textColor || 'black'"
							x-small
							:class="{'ml-1': i > 0}"
						>
							<strong>{{ label.name }}</strong>
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
				<v-layout>
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
						solo
						auto-grow
						autofocus
						@blur="handleSave"
					/>
				</v-layout>
			</v-container>
			<v-divider/>
			<v-container>
						<v-select
							v-model="item.labels"
							:items="labels"
							chips
							label="Labels"
							multiple
							return-object
							item-text="name"
							item-value="id"
						/>
						<member-select
							v-model="item.members"
						/>
				<v-text-field
					v-model="item.link"
					label="Link"
				/>
			</v-container>
			<v-card-actions>
				<v-btn
					block
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
import MemberSelect from './MemberSelect';

export default {
	components: {
		MemberList,
		MemberSelect,
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
