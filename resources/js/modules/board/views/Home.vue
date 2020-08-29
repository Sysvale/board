<template>
	<v-container
		fluid
		class="px-2 py-5"
	>
		Home
	</v-container>
</template>

<script>
export default {
	mounted() {
		this.$router.replace({
			name: 'planning',
		});
	}
}
</script>
