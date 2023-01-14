<template>
	<div class="mb-5">
		<label-list
			:labels="labels.map(({ id }) => id)"
			:raw-labels="labels"
			selectable
			:secondary="secondary"
			:selected-labels="value"
			@itemClick="selectHandler"
		/>
	</div>
</template>
<script>
import LabelList from './LabelList.vue';

export default {
	components: {
		LabelList,
	},

	props: {
		value: {
			type: [Object, String, Array],
			default: null,
		},
		small: {
			type: Boolean,
			default: false,
		},
		secondary: {
			type: Boolean,
			default: false,
		},
		labels: {
			type: Array,
			default: () => [],
		},
	},

	methods: {
		selectHandler(selectedId) {
			if (!!this.value && this.value.indexOf(selectedId) > -1) {
				this.$emit('input', this.value.filter((item) => item !== selectedId));
				return;
			}
			this.$emit('input', [...(this.value || []), selectedId]);
		},
	},
};
</script>
