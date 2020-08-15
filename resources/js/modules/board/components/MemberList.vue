<template>
	<div
		v-if="members.length > 0"
	>
		<v-avatar
			v-for="(member, i) in members"
			:key="member.id"
			color="indigo"
			:text-color="member.textColor || 'black'"
			size="28"
			:class="{'ml-1': i > 0}"
			:title="member.name"
		>
			<small class="white--text">
				{{ getFirstLetters(member.name) }}
			</small>
		</v-avatar>
	</div>
</template>
<script>
export default {
	props: {
		members: {
			type: Array,
			default: () => [],
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
