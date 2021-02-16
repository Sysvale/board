<template>
	<div class="mb-5">
		<label-list
			:labels="labels.map(({ id }) => id)"
			selectable
			:selectedLabels="value"
			@itemClick="selectHandler"
		/>
	</div>
</template>
<script>
import { mapGetters } from 'vuex';
import LabelItem from './LabelItem';
import LabelList from './LabelList.vue';

export default {
	props: {
		value: {
			type: [Object, String, Array],
			default: null,
		},
		small: {
			type: Boolean,
			default: false,
		}
	},

	components: {
		LabelItem,
		LabelList,
	},

	computed: {
		...mapGetters('labels', {
			labels: 'itemsByWorkspace',
		}),
	},

	methods: {
		selectHandler(selectedId) {
			if(!!this.value && this.value.indexOf(selectedId) > -1) {
				this.$emit('input', this.value.filter((item) => item !== selectedId));
				return;
			}
			this.$emit('input', [...(this.value || []), selectedId]);
		}
	}
}
</script>
