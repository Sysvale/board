<template>
	<v-select
		:value="value"
		:items="labels"
		chips
		placeholder="Categorias"
		multiple
		outlined
		dense
		item-text="name"
		item-value="id"
		@input="$emit('input', $event)"
	/>
</template>
<script>
import { mapState } from 'vuex';
export default {
	props: {
		value: {
			type: [Object, String, Array],
			default: null,
		},
	},

	computed: {
		...mapState('labels', {
			labels: 'items',
		}),
	},
}
</script>
