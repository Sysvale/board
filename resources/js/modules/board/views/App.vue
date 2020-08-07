<template>
	<v-app>
		<v-app-bar
			app
			color="#333"
			dense
			dark
		>
			<v-toolbar-title>Trelássio</v-toolbar-title>
		</v-app-bar>
		<v-main>
			<router-view />
		</v-main>
	</v-app>
</template>

<script>
export default {
}
</script>
<style scoped>
.v-application {
	background-color: #F4F4F6;
	font-family: 'Quicksand', sans-serif;
	font-weight: 500;
}
</style>
