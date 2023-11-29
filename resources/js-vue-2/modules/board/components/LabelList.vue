<template>
	<div
		v-if="labels && labels.length > 0"
		class="d-flex"
	>
		<div>
			<label-item
				v-for="label in computedLabels"
				:key="label.id"
				:label="label"
				:muted="shouldBeMuted(label.id)"
				:small="small"
				:secondary="secondary"
				style="cursor:pointer"
				class="mt-1"
				@click.native="handleItemClick($event, label.id)"
			/>
		</div>
	</div>
</template>
<script>
import LabelItem from './LabelItem.vue';

export default {
	components: {
		LabelItem,
	},
	props: {
		labels: {
			type: Array,
			default: () => [],
		},
		selectable: {
			type: Boolean,
			default: false,
		},
		selectedLabels: {
			type: Array,
			default: () => [],
		},
		small: {
			type: Boolean,
			default: false,
		},
		secondary: {
			type: Boolean,
			default: false,
		},
		rawLabels: {
			type: Array,
			default: () => [],
		},
	},

	computed: {
		computedLabels() {
			return this.rawLabels.filter((item) => _.includes(this.labels, item.id));
		},
		shouldBeMuted() {
			return (itemId) => {
				if (!this.selectable) return false;
				if (!this.selectedLabels || this.selectedLabels.length === 0) return true;
				return !!this.selectedLabels && this.selectedLabels.indexOf(itemId) === -1;
			};
		},
	},

	methods: {
		handleItemClick(event, id) {
			if (this.selectable) {
				event.stopPropagation();
				this.$emit('itemClick', id);
			}
		},
	},
};
</script>
