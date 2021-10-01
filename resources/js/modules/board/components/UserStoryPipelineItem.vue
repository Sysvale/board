<template>
	<v-tooltip
		bottom
	>
		<template v-slot:activator="{ on, attrs }">
			<div
				v-bind="attrs"
				v-on="on"
				class="item"
				:class="{'active': active}"
				@click="$emit('click', value)"
			></div>
		</template>
		{{ status }}
	</v-tooltip>
</template>
<script>
export default {
	props: {
		value: {
			type: String,
			default: 'development',
		},
		active: {
			type: Boolean,
			default: false,
		},
		status: {
			type: String,
			default: 'Em desenvolvimento',
		}
	},
};
</script>
<style lang="scss" scoped>
.item {
	width: 10px;
	height: 10px;
	border-radius:100%;
	background: rgba(0,0,0,.12);
	cursor: pointer;
	&.active {
		background:#7BD0F4;
	}
}
.item:hover {
	background: #7BD0F4;
}
</style>
