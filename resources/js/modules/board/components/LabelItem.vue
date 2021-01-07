<template>
	<v-chip
		:key="label.id"
		:text-color="muted ? 'gray' : (label.textColor || 'black')"
		:color="muted ? 'gray' : label.color"
		:title="label.name"
		:small="$attrs.small"
		label
		class="mr-1 px-1 py-1 label-item"
		:class="{ 'muted': muted }"
	>
		<small class="text-uppercase font-weight-medium">
			{{label.name }}
		</small>
	</v-chip>
</template>
<script>
export default {
	props: {
		label: {
			type: Object,
			default: () => {},
		},
		muted: {
			type: Boolean,
			default: false,
		}
	},
}
</script>
<style scoped>
	.label-item {
		border-bottom: 2px solid rgba(0, 0, 0, 0.35)!important;
	}
	.muted {
		opacity: 0.5;
	}
	.muted:hover {
		opacity: 1;
	}
</style>
