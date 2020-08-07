<template>
	<v-card
		class="task-card"
		v-bind="$attrs"
		v-on="$listeners"
		hover
	>
		<slot />
	</v-card>
</template>
<style scoped>
.task-card {
	box-shadow: 0 3px 1px -2px rgba(0,0,0,.05),
		0 2px 2px 0 rgba(0,0,0,.05),
		0 1px 5px 0 rgba(0,0,0,.05) !important;
}
</style>
