<template>
	<div
		class="d-flex"
	>
		<v-autocomplete
			ref="milestoneSelect"
			:value="value"
			:items="milestones"
			placeholder="Milestone"
			outlined
			dense
			return
			item-text="title"
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
		...mapState('milestones', {
			milestones: 'items',
		}),
	},

	watch: {
		select() {
			setTimeout(() => {
				if (!this.$refs.milestoneSelect) return;
				this.$refs.milestoneSelect.isMenuActive = true;
			}, 100);
		},
	},
};
</script>
