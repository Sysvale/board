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
				@click="syncWithGitlab"
				class="mr-3"
				:disabled="loadingSync"
			>
				Sincronizar com o GitLab
			</v-btn>
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

export default {
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
		...mapGetters('labels', [
			'lowercaseLabels',
		]),
		...mapState('gitlab', {
			loadingSync: ({ getIssues }) => getIssues.isFetching,
		}),
		...mapState('cards', {
			loadingCreate: ({ createCards }) => createCards.isFetching,
		}),

		loading() {
			return this.loadingMembers
				|| this.loadingLabels
				|| this.loadingTeams
				|| this.loadingBoards
				|| this.loadingSync
				|| this.loadingCreate;
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
		...mapActions('gitlab', [
			'getIssues',
			'getIssuesAmount'
		]),
		...mapActions('cards', [
			'createCards',
		]),
		syncWithGitlab() {
			const maxPerPage = 100;
			this.getIssuesAmount().then(({ statistics: { counts } }) => {
				const opened = counts.opened;

				const requestsAmount = Math.ceil(opened/maxPerPage);
				const lastPerPage = opened - (requestsAmount - 1) * maxPerPage;

				let currentPerPage;
				for (const i of Array(requestsAmount).keys()) {
					currentPerPage = (i == requestsAmount - 1) ? lastPerPage : maxPerPage;

					this.getIssues({ per_page: currentPerPage }).then((data) => {
						const cards = data.map((issue) => {
							let { labels, title, web_url } = issue;
							labels = this.mapLabels(labels);

							return {
								title,
								labels,
								board_list_id: '5f4912bd7eca1b02ed62f462',
								link: web_url,
							};
						})
						this.createCards({ list: cards });
					})
					.finally(() => {
						this.getBoards().then((data) => {
							this.setBoards(data);
						});
					});
				}
			});
		},
		mapLabels(labels) {
			const dict = {
				backend: 'backend',
				front: 'frontend',
				mockup: 'mockup',
				BUG: 'bug',
				Suporte: 'helpDesk',
				app: 'app',
				UX: 'ux',
				Exportação: 'export'
			};
			const mappedLabels = labels.map(label => dict[label]);

			return _.filter(this.lowercaseLabels, item => _.includes(mappedLabels, item.key))
				.map(label => label.id);
		}
	}
}
</script>
<style scoped>
.v-application {
	background-color: rgb(250, 250, 250); /*#F4F4F6;*/
	font-family: 'Poppins', sans-serif;
}
</style>
