<template>
	<div
		class="d-flex align-end"
	>
		<div
			v-if="members && members.length > 0"
			class="d-flex"
		>
				<member-item
					v-for="member in slicedMembers"
					:key="member.id"
					:member="member"
					:deletable="deletable"
					class="mr-1 mt-1"
					@delete="deletable ? $emit('deleteItem', member.id) : null"
				/>
		</div>
		<slot name="append"></slot>
	</div>
</template>
<script>
import { mapState } from 'vuex';
import MemberItem from './MemberItem.vue';
export default {
  components: { MemberItem },
	props: {
		members: {
			type: Array,
			default: () => [],
		},
		full: {
			type: Boolean,
			default: false,
		},
		deletable: {
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
}
</script>
