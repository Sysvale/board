<template>
	<v-dialog
		v-model="dialog"
		width="700"
	>
		<v-card v-if="process">
			<v-card-title>
				<h2>{{ process.name }}</h2>
			</v-card-title>

			<v-card-text>
				<div>
					<v-expansion-panels
						flat
					>
						<v-expansion-panel
							v-for="(item, i) in process.checklists"
							:key="i"
						>
							<v-expansion-panel-header>
								<h3 style="font-weight: 500;">{{ item.title }}</h3>
							</v-expansion-panel-header>
							<v-expansion-panel-content>
								<checklist-form
									v-model="item.items"
									no-actions
									hide-creation
								/>
							</v-expansion-panel-content>
						</v-expansion-panel>
					</v-expansion-panels>
				</div>
			</v-card-text>
		</v-card>
	</v-dialog>
</template>
<script>
import ChecklistForm from '../../board/components/ChecklistForm.vue';
export default {
  components: { ChecklistForm },
	props: {
		value: {
			type: Boolean,
			default: false,
		},
		process: {
			type: Object,
			default: () => ({}),
		},
	},
	data() {
		return {
			dialog: this.value,
		};
	},
	watch: {
		dialog(value) {
			this.$emit('input', value)
		},
		value(value) {
			this.dialog = value;
		},
	},
}
</script>
