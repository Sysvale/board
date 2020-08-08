<template>
	<v-container
		grid-list-md
		fluid
	>
		<h2 class="mb-3 text-center font-weight-light">Sprint Board</h2>
		<v-divider class="py-3"/>
		<section>
			<h1>Não planejados</h1>
			<not-planned-board />
		</section>
		<v-divider/>
		<section class="mt-10">
			<h1>Sprint Backlog</h1>
			<user-stories-board />
		</section>
		<div class="mt-10">
			<v-divider/>
		</div>
		<section class="mt-10">
			<h1>Sprint Devlog</h1>
			<dev-board />
		</section>
	</v-container>
</template>

<script>
import UserStoriesBoard from '../components/UserStoriesBoard.vue';
import DevBoard from '../components/DevBoard.vue';
import NotPlannedBoard from '../components/NotPlannedBoard.vue';

export default {
	components: {
		UserStoriesBoard,
		DevBoard,
		NotPlannedBoard,
	},
}
</script>
