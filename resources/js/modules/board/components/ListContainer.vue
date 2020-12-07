<template>
	<v-container
		fluid
		class="list-container mr-1 ml-1"
	>
		<v-card
			class="px-1 py-2 christmas-background"
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

.christmas-background {
	background-image: url('https://i.pinimg.com/originals/35/8c/4f/358c4f536c3e1165a112197b146d56da.png');
	background-size: 100%;
	background-position: top center;
	background-repeat: no-repeat;
	border: none;
}
</style>
