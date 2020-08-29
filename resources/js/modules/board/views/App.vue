<template>
	<v-app>
		<v-app-bar
			app
			color="#333"
			dense
			dark
		>
			<v-toolbar-title>Trelássio</v-toolbar-title>
			<v-spacer/>
			<v-btn
				@click="syncing = true"
				class="mr-3"
			>
				Sincronizar
			</v-btn>
			<gitlab-synchronizer
				v-if="syncing"
				@finished="finishedSync"
			/>

			<v-btn
				text
				to="/"
				class="mr-3"
			>
				Planning
			</v-btn>
			<v-btn
				text
				to="sprint"
			>
				Sprint
			</v-btn>
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
import { mapActions, mapMutations, mapState, mapGetters } from 'vuex';
import GitlabSynchronizer from '../components/GitlabSynchronizer';

export default {
	components: {
		GitlabSynchronizer,
	},
	data() {
		return {
			syncing: false,
		};
	},
	created() {
		this.getMembers().then((data) => {
			this.setMembers(data);
		});
		this.getLabels().then((data) => {
			this.setLabels(data);
		});
		this.getTeams().then((data) => {
			this.setTeams(data);
		});
		this.getBoards().then((data) => {
			this.setBoards(data);
		});
	},
	computed: {
		...mapState('members', {
			loadingMembers: ({ getMembers }) => getMembers.isFetching,
		}),
		...mapState('labels', {
			loadingLabels: ({ getLabels }) => getLabels.isFetching,
		}),
		...mapState('teams', {
			loadingTeams: ({ getTeams }) => getTeams.isFetching,
		}),
		...mapState('boards', {
			loadingBoards: ({ getBoards }) => getBoards.isFetching,
		}),

		loading() {
			return this.loadingMembers
				|| this.loadingLabels
				|| this.loadingTeams
				|| this.loadingBoards
				|| this.syncing
		},
	},
	watch: {
		'$route'(to, from) {
			if(to.meta && to.meta.title) {
				document.title = `${to.meta.title} | Trelássio`
				return;
			}
			to.from.title = 'Trelássio';
		},
	},
	methods: {
		...mapActions('members', [
			'getMembers',
		]),
		...mapActions('labels', [
			'getLabels',
		]),
		...mapActions('teams', [
			'getTeams',
		]),
		...mapActions('boards', [
			'getBoards',
		]),
		...mapMutations('members', {
			setMembers: 'setItems',
		}),
		...mapMutations('labels', {
			setLabels: 'setItems',
		}),
		...mapMutations('teams', {
			setTeams: 'setItems',
		}),
		...mapMutations('boards', {
			setBoards: 'setItems',
		}),
		finishedSync() {
			this.syncing = false;
		},
	}
}
</script>
<style scoped>
.v-application {
	background-color: rgb(250, 250, 250); /*#F4F4F6;*/
	font-family: 'Poppins', sans-serif;
}
</style>
