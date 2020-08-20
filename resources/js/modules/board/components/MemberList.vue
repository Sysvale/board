<template>
	<div
		v-if="members.length > 0"
		class="d-flex"
	>
		<div
			v-for="(member, i) in computedMembers"
			:key="member.id"
			class="member-item"
			:text-color="member.textColor || 'black'"
			size="28"
			:class="{'ml-1': i > 0}"
			:title="member.name"
		>
			<small>
				<strong>{{ getFirstLetters(member.name) }}</strong>
			</small>
		</div>
	</div>
</template>
<script>
import { mapState } from 'vuex';
export default {
	props: {
		members: {
			type: Array,
			default: () => [],
		},
	},

	computed: {
		...mapState('members', {
			rawMembers: 'items',
		}),
		computedMembers() {
			return this.rawMembers.filter(item => _.includes(this.members, item.id));
		},
	},

	methods: {
		getFirstLetters(name) {
			const computed = name[0] + name.split(' ')[1][0] || name[1];
			return computed.toUpperCase();
		},
	}
}
</script>
<style>
.member-item {
	background: #dce6ff;
	width: 28px;
	height: 28px;
	border-radius: 3px;
	text-align: center;
	color: #3a7efd;
	border: 1px solid #3a7efd;
}
</style>
