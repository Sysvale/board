<template>
	<v-card
		class="task-card"
		v-bind="$attrs"
		v-on="$listeners"
		hover
		@mouseover="hover = true"
		@mouseleave="hover = false"
	>
		<v-card-text
			class="text--black"
		>
			<slot />
		</v-card-text>
		<v-card-actions
			v-if="hover"
		>
			<v-btn
				block
				outlined
				color="red"
				small
				@click="$emit('delete')"
			>
				Excluir
			</v-btn>
		</v-card-actions>
	</v-card>
</template>
<script>
export default {
	data() {
		return {
			hover: false,
		};
	},
}
</script>
<style scoped>
.task-card {
	box-shadow: 0 3px 1px -2px rgba(0,0,0,.05),
		0 2px 2px 0 rgba(0,0,0,.05),
		0 1px 5px 0 rgba(0,0,0,.05) !important;
	max-width: 250px;
	min-width: 250px;
}
</style>
