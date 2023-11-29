<template>
	<v-chip
		:key="label.id"
		:text-color="muted ? 'gray' : (label.textColor || 'black')"
		:color="muted ? 'gray' : (label.color || 'white')"
		:title="label.name"
		:small="$attrs.small"
		:label="!secondary"
		class="mr-1 px-2 py-1 label-item"
		:class="{ 'muted': muted }"
	>
		<small class="text-uppercase font-weight-medium">
			{{ label.name }}
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
		},
		secondary: {
			type: Boolean,
			default: false,
		},
	},
};
</script>
<style scoped>
	.muted {
		opacity: 0.5;
	}
	.muted:hover {
		opacity: 1;
	}
</style>
