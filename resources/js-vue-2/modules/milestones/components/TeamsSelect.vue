<template>
	<div
		class="d-flex"
	>
		<v-autocomplete
			ref="teamSelect"
			:value="value"
			:items="teams"
			placeholder="Times"
			multiple
			deletable-chips
			chips
			outlined
			dense
			return
			item-text="name"
			item-value="id"
			hide-details
			full-width
			@input="$emit('input', $event)"
			@blur="select = false"
		/>
	</div>
</template>
<script>
import { mapState } from 'vuex';

export default {

	props: {
		value: {
			type: [Object, String, Array],
			default: null,
		},
		disabled: {
			type: Boolean,
			default: false,
		},
	},

	data() {
		return {
			select: false,
			autofocus: false,
		};
	},

	computed: {
		...mapState('teams', {
			teams: 'items',
		}),
	},

	watch: {
		select() {
			setTimeout(() => {
				if (!this.$refs.teamSelect) return;
				this.$refs.teamSelect.isMenuActive = true;
			}, 100);
		},
	},
};
</script>
