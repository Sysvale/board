<template>
	<v-container
		fluid
		justify-start
		align-content-start
		class="list-container"
	>
		<slot />
	</v-container>
</template>

<script>
export default {

}
</script>

<style>
.list-container {
	max-width: 275px;
	margin-right: 0px!important;
	margin-left: 0px!important;
}
</style>
