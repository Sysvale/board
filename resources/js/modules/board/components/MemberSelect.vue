<template>
	<v-select
		:value="value"
		:items="members"
		chips
		placeholder="Membros"
		multiple
		outlined
		dense
		return-object
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
		...mapState('members', {
			members: 'items',
		}),
	},

	methods: {
		getFirstLetters(name) {
			const computed = name[0] + name.split(' ')[1][0] || name[1];
			return computed.toUpperCase();
		},
	}
}
</script>
