<template>
	<v-dialog
		v-if="item"
		v-model="dialog"
		width="500"
	>
		<template v-slot:activator="{}">
				<v-card
					class="task-card"
					v-bind="$attrs"
					v-on="$listeners"
					hover
					@click="showModal"
					@mouseover="hover = true"
					@mouseleave="hover = false"
				>
					<v-card-title>
						<v-chip
							v-for="(label, i) in (item.labels || [])"
							:key="label.id"
							:color="label.color || 'gray'"
							:text-color="label.textColor || 'black'"
							label
							x-small
							:class="{'ml-1': i > 0}"
						>
							{{ label.name }}
						</v-chip>
					</v-card-title>
					<v-card-text
						class="text--black"
					>
						<slot />
					</v-card-text>
					<v-container>
						<v-row
							class="px-0"
						>
							<v-col>
								<v-chip
									v-if="hover"
									avatar
									small
									outlined
									text-color="#ccc"
								>
									Link
								</v-chip>
							</v-col>
							<v-col
								class="text-right"
							>
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
							</v-col>
						</v-row>
					</v-container>
				</v-card>
		</template>
		<v-card>
			<v-container>
				<v-layout>
					<v-card-text
						v-if="!titleInEditMode"
						@click="titleInEditMode = true"
					>
						{{ item.title }}
					</v-card-text>
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
				<v-layout
					row
				>
					<v-container>
						 <v-select
							v-model="item.labels"
							:items="labels"
							filled
							chips
							label="Labels"
							multiple
							return-object
							item-text="name"
							item-value="id"
						/>
					</v-container>
					<v-container>
						 <v-select
							v-model="item.members"
							:items="members"
							filled
							chips
							label="Membros"
							multiple
							return-object
							item-text="name"
							item-value="id"
						/>
					</v-container>
				</v-layout>
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
		};
	},

	computed: {
		...mapState('members', {
			members: 'items',
		}),
		...mapState('labels', {
			labels: 'items',
		}),
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
			console.log(computed);
			return computed.toUpperCase();
		},
	},
}
</script>
