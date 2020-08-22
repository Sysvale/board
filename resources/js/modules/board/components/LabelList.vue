<template>
	<div
		v-if="labels && labels.length > 0"
		class="d-flex"
	>
		<div style="line-height:28px">
			<v-chip
				v-for="label in computedLabels"
				:key="label.id"
				:text-color="label.textColor || 'black'"
				:color="label.color"
				:title="label.name"
				small
				label
				class="mr-1 px-1 py-1"
				:style="{
					borderBottom: `2px solid rgba(0, 0, 0, 0.35)`,
				}"
			>
				<small class="text-uppercase font-weight-medium">
					{{label.name }}
				</small>
			</v-chip>
		</div>
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
