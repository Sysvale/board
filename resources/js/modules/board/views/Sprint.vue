<template>
	<v-container
		grid-list-md
		fluid
	>
		<h2 class="mb-3 text-center font-weight-light">Sprint Board</h2>
		<v-divider class="py-3"/>
		<section>
			<h1>Histórias</h1>
			<user-stories-board />
		</section>
	</v-container>
</template>

<script>
import UserStoriesBoard from '../components/UserStoriesBoard.vue';

export default {
	components: {
		UserStoriesBoard,
	},
}
</script>
