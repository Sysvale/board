<template>
	<v-container
		grid-list-md
		fluid
	>
		<h2 class="mb-3 text-center font-weight-light">Sprint Board</h2>
		<v-divider class="py-3"/>
		<section>
			<h1>Não planejados</h1>
			<default-tasks-board
				namespace="notPlanned"
			/>
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
			<default-tasks-board
				namespace="dev"
			/>
		</section>
	</v-container>
</template>

<script>
import UserStoriesBoard from '../components/UserStoriesBoard.vue';
import DefaultTasksBoard from '../components/DefaultTasksBoard.vue';

export default {
	components: {
		UserStoriesBoard,
		DefaultTasksBoard,
	},
}
</script>
