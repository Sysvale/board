<template>
	<div
		v-if="labels.length > 0"
		class="d-flex"
	>
		<v-chip
			v-for="(label, i) in labels"
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
export default {
	props: {
		labels: {
			type: Array,
			default: () => [],
		},
	},
}
</script>
