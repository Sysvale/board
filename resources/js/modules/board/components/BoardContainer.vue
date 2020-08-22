<template>
	<v-container
		id="board"
		fluid
		class="px-0 pb-5 pt-0"
	>
		<slot/>
	</v-container>
</template>

<script>
export default {

}
</script>

<style scoped>
#board {
	overflow-x: scroll;
	max-width: 100%;
}
/* width */
#board::-webkit-scrollbar {
	width: 5px;
	height: 8px;
	border-radius: 50px;
}

/* Track */
#board::-webkit-scrollbar-track {
	background: #f1f1f1;
}

/* Handle */
#board::-webkit-scrollbar-thumb {
	background: rgba(0, 0, 0, 0.15);
	border-radius: 5px;
}

/* Handle on hover */
#board::-webkit-scrollbar-thumb:hover {
	background: rgba(0, 0, 0, 0.20);
}
</style>
