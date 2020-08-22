<template>
	<v-select
		:value="value"
		:items="members"
		chips
		placeholder="Membros"
		multiple
		outlined
		dense
		return
		item-text="name"
		item-value="id"
		hide-details
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
		...mapState('members', {
			members: 'items',
		}),
	},
}
</script>
