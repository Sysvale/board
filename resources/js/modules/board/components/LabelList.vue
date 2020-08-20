<template>
	<div
		v-if="labels.length > 0"
		class="d-flex"
	>
		<v-chip
			v-for="(label, i) in computedLabels"
			:key="label.id"
			:text-color="label.textColor || 'black'"
			:color="label.color"
			:class="{'ml-1': i > 0}"
			:title="label.name"
			small
		>
			<small>
				<strong>{{label.name }}</strong>
			</small>
		</v-chip>
	</div>
</template>
<script>
import { mapState } from 'vuex'
export default {
	props: {
		labels: {
			type: Array,
			default: () => [],
		},
	},

	computed: {
		...mapState('labels', {
			rawLabels: 'items',
		}),
		computedLabels() {
			return this.rawLabels.filter(item => _.includes(this.labels, item.id));
		},
	}
}
</script>
