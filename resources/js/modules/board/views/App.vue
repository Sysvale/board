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
			<div
				v-if="loading"
			>
				Carregando...
			</div>
			<router-view v-else />
		</v-main>
	</v-app>
</template>

<script>
import { mapActions, mapMutations, mapState } from 'vuex'
export default {
	created() {
		this.getMembers().then((data) => {
			this.setMembers(data);
		});
		this.getLabels().then((data) => {
			this.setLabels(data);
		});
	},
	computed: {
		...mapState('members', {
			loadingMembers: ({ getMembers }) => getMembers.isFetching,
		}),
		...mapState('labels', {
			loadingLabels: ({ getLabels }) => getLabels.isFetching,
		}),

		loading() {
			return this.loadingMembers || this.loadingLabels;
		},
	},
	methods: {
		...mapActions('members', [
			'getMembers',
		]),
		...mapActions('labels', [
			'getLabels',
		]),
		...mapMutations('members', {
			setMembers: 'setItems',
		}),
		...mapMutations('labels', {
			setLabels: 'setItems',
		}),
	}
}
</script>
<style scoped>
.v-application {
	background-color: #F4F4F6;
	font-family: 'Quicksand', sans-serif;
	font-weight: 500;
}
</style>
