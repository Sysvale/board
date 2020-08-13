<template>
	<v-dialog
		v-if="item"
		v-model="dialog"
		width="500"
	>
		<template v-slot:activator="{}">
				<v-card
					class="task-card px-3"
					v-bind="$attrs"
					v-on="$listeners"
					hover
					@click="showModal"
					@mouseover="hover = true"
					@mouseleave="hover = false"
				>
					<div
						class="py-2"
					>
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
					<div
						class="gray--text py-0"
					>
						<slot />
					</div>
					<div class="d-flex py-2">
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
						<div class="d-flex justify-end flex-grow-1">
							<v-avatar
								v-for="(member, i) in (item.members || [])"
								:key="member.id"
								color="indigo"
								:text-color="member.textColor || 'black'"
								size="28"
								:class="{'ml-1': i > 0}"
							>
								<small class="white--text">
									{{ getFirstLetters(member.name) }}
								</small>
							</v-avatar>
						</div>
					</div>
				</v-card>
		</template>
		<v-card>
			<v-container>
				<v-layout>
					<div
						v-if="!titleInEditMode"
						@click="titleInEditMode = true"
						class="black--text"
					>
						{{ item.title }}
					</div>
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
						 <v-select
							v-model="item.members"
							:items="members"
							chips
							label="Membros"
							multiple
							return-object
							item-text="name"
							item-value="id"
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
export default {
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
		...mapState('members', {
			members: 'items',
		}),
		...mapState('labels', {
			labels: 'items',
		}),
		link() {
			if(this.item.link) {
				return {
					label: `!132`,
					color: 'orange',
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

		getFirstLetters(name) {
			const computed = name[0] + name.split(' ')[1][0] || name[1];
			return computed.toUpperCase();
		},
	},
}
</script>
