<template>
	<v-container
		fluid
		class="list-container mx-2"
	>
		<slot />
	</v-container>
</template>

<script>
export default {

}
</script>

<style scoped>
.list-container {
	max-width: 250px;
	min-width: 250px;
	padding: 0px!important;
}
</style>
