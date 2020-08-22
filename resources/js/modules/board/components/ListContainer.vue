<template>
	<v-container
		fluid
		class="list-container mr-1 ml-1"
	>
		<v-card
			class="px-2 py-2"
			flat
			outlined
			color="#efefef"
		>
			<slot />
		</v-card>
	</v-container>
</template>

<script>
export default {

}
</script>

<style scoped>
.list-container {
	max-width: 280px;
	min-width: 280px;
	padding: 0px!important;
}
</style>
