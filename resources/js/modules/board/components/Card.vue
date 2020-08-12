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
							label
							x-small
						>
							Label
						</v-chip>
						<v-chip
							v-if="hover"
							label
							x-small
							class="ml-1"
						>
							+
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
								 <v-avatar color="indigo" size="28">
									<small class="white--text">TL</small>
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
import { mapActions } from 'vuex';
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
		}
	},
}
</script>
