<template>
	<div>
		<div
			v-if="value && value.length === 0"
			class="pb-2 grey--text caption text-center"
		>
			Ainda não há nenhum item na sua checklist.
		</div>

		<v-list flat>
			<v-list-item-group>
				<div
					v-for="(item, index) in internalValue"
					:key="index"
				>
					<checklist-item
						v-model="internalValue[index]"
						:readonly="readonly"
						:no-actions="noActions"
						@remove="handleRemove(index)"
					/>
				</div>
			</v-list-item-group>
		</v-list>

		<div
			v-if="!hideCreation"
			class="d-flex mt-2"
		>
			<v-text-field
				v-model="newItem"
				outlined
				flat
				dense
				class="mr-2"
				@keydown.enter="handleAdd"
			/>

			<v-btn
				elevation="0"
				outlined
				color="#333"
				@click="handleAdd"
			>
				{{ addButtonText }}
			</v-btn>
		</div>
	</div>
</template>

<script>
import ChecklistItem from './ChecklistItem.vue';

export default {
	components: {
		ChecklistItem,
	},

	props: {
		value: {
			type: Array,
			default: () => [],
		},
		readonly: {
			type: Boolean,
			default: false,
		},
		noActions: {
			type: Boolean,
			default: false,
		},
		hideCreation: {
			type: Boolean,
			default: false,
		},
	},

	data() {
		return {
			newItem: null,
			internalValue: [],
		};
	},

	computed: {
		addButtonText() {
			return 'Adicionar item';
		},
	},

	watch: {
		value(newValue, oldValue) {
			if (newValue !== oldValue) {
				this.internalValue = newValue;
			}
		},

		internalValue(newValue) {
			if (newValue !== this.value) {
				this.$emit('input', newValue);
			}
		},
	},

	mounted() {
		this.internalValue = this.value;
	},

	methods: {
		handleAdd() {
			if (this.newItem === null || this.newItem.trim() === '') return;
			this.$emit('input', [...(this.value || []), { description: this.newItem }]);
			this.newItem = null;
		},

		handleRemove(index) {
			this.$emit('input', [
				...this.internalValue.filter((_, i) => index !== i),
			]);
		},
	},
};
</script>
