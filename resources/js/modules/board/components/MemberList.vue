<template>
	<div
		v-if="members && members.length > 0"
		class="d-flex"
	>
		<div
			v-for="(member, i) in slicedMembers"
			:key="member.id"
			class="member-item"
			:class="{'ml-1': i > 0}"
			:title="member.title || member.name"
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
		full: {
			type: Boolean,
			default: false,
		}
	},

	computed: {
		...mapState('members', {
			rawMembers: 'items',
		}),
		computedMembers() {
			return this.rawMembers.filter(item => _.includes(this.members, item.id));
		},
		slicedMembers() {
			if(this.full) {
				return this.computedMembers;
			}
			const { length } = this.computedMembers;
			if(length > 4) {
				const remain = _.slice(this.computedMembers, 4, length);
				if (remain.length === 1) return this.computedMembers;
				let title = '';
				remain.map(({ name }) => name)
					.forEach((name, i) => {
						title += `${name}${ i !== remain.length - 1 ? ', ' : ''}`
					});
				return [
					..._.slice(this.computedMembers, 0, 4),
					{
						id: 'anyId',
						name: `+${remain.length}`,
						title,
					},
				];
			}
			return this.computedMembers;
		}
	},

	methods: {
		getFirstLetters(name) {
			const computed = name[0] + ((name.split(' ')[1] ? name.split(' ')[1][0] : false) || name[1]);
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
