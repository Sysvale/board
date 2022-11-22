<template>
	<v-app>
		<v-app-bar
			app
			color="#145C45"
			dense
			dark
			@dblclick="goToCompanyPlanning"
		>
			<a
				href="/workspace/select"
				class="mr-3"
			>
				<img
					src="/images/logo.svg"
					height="35px"
					title="Trelássio"
				>
			</a>
			<v-icon
				v-if="workspaces && currentWorkspace"
				color="white"
				class="mr-5"
			>
				keyboard_arrow_right
			</v-icon>
			<v-menu
				v-if="workspaces && currentWorkspace"
			>
				<template v-slot:activator="{ on, attrs }">
					<v-btn
						color="primary"
						dark
						v-bind="attrs"
						v-on="on"
					>
						{{ currentWorkspace.name }}
						<v-icon>
							keyboard_arrow_down
						</v-icon>
					</v-btn>
				</template>
				<v-list>
					<v-list-item
						v-for="(workspace, index) in workspaces"
						:key="index"
					>
						<v-btn
							link
							text
							@click="getToFromWorkspace(workspace.id)"
						>
							{{ workspace.name }}
						</v-btn>
					</v-list-item>
				</v-list>
			</v-menu>
			<v-icon
				v-if="workspaces && currentWorkspace"
				color="white"
				class="mx-5"
			>
				keyboard_arrow_right
			</v-icon>
			<v-menu
				v-if="workspaces && currentWorkspace"
			>
				<template v-slot:activator="{ on, attrs }">
					<v-btn
						color="yellow"
						dark
						style="color:black!important"
						v-bind="attrs"
						v-on="on"
					>
						{{ currentPage }}
						<v-icon>
							keyboard_arrow_down
						</v-icon>
					</v-btn>
				</template>
				<v-list>
					<v-list-item>
						<v-btn
							text
							color="primary"
							:to="`/workspace/${currentWorkspace.id}/planning`"
						>
							Planning
						</v-btn>
					</v-list-item>
					<v-list-item>
						<v-btn
							text
							:to="sprintRoute"
						>
							Sprint
						</v-btn>
					</v-list-item>
				</v-list>
			</v-menu>
			<v-spacer />
			<v-spacer />
			<v-btn
				class="mr-3"
				color="yellow"
				style="color:black!important"
				to="/process/"
			>
				Central de Processos
			</v-btn>
			<v-btn
				icon
				@click="goToSettings()"
			>
				<v-icon>
					settings
				</v-icon>
			</v-btn>
			<v-btn
				icon
				@click="logout()"
			>
				<v-icon>
					logout
				</v-icon>
			</v-btn>
		</v-app-bar>
		<v-main>
			<v-container
				v-if="loading"
				fluid
				fill-height
				class="px-2 py-5 text-center"
			>
				<div
					class="flex-grow-1"
				>
					Abastecendo o poçante...
				</div>
			</v-container>
			<router-view v-else />
		</v-main>
	</v-app>
</template>

<script>
import {
	mapActions,
	mapGetters,
	mapMutations,
	mapState,
} from 'vuex';

export default {

	data() {
		return {
			currentPage: 'Planning',
		};
	},

	computed: {
		...mapState('workspaces', {
			loadingWorkspaces: ({ getWorkspaces }) => getWorkspaces.isFetching,
			workspaces: 'items',
		}),
		...mapState('members', {
			loadingMembers: ({ getMembers }) => getMembers.isFetching,
		}),
		...mapState('labels', {
			loadingLabels: ({ getLabels }) => getLabels.isFetching,
		}),
		...mapState('teams', {
			teams: 'items',
			loadingTeams: ({ getTeams }) => getTeams.isFetching,
		}),
		...mapState('boards', {
			loadingBoards: ({ getBoards }) => getBoards.isFetching,
		}),
		...mapState('workspaces', {
			loadingWorkspaces: ({ getWorkspaces }) => getWorkspaces.isFetching,
		}),
		...mapState('goals', {
			loadingGoals: ({ getGoals }) => getGoals.isFetching,
		}),

		...mapGetters('workspaces', ['currentWorkspace']),
		...mapGetters('teams', {
			teamByWorkspace: 'itemsByWorkspace',
		}),

		loading() {
			return this.loadingMembers
				|| this.loadingLabels
				|| this.loadingTeams
				|| this.loadingBoards
				|| this.loadingWorkspaces
				|| this.loadingGoals;
		},

		sprintRoute() {
			if (this.$route && this.$route.name === 'sprint') {
				return `${this.$route.params.teamId}`;
			}
			if (this.teams && this.teams.length) {
				return `/workspace/${this.currentWorkspace.id}/sprint/${this.teams[0].id}`;
			}
			return 'sprint';
		},
	},

	watch: {
		$route(to) {
			if (to.params && to.params.workspaceId) {
				this.setSelectedWorkspace(this.workspaces
					.filter(({ id }) => id === to.params.workspaceId)[0]);
			} else {
				this.setSelectedWorkspace(null);
			}

			if (to.meta && to.meta.title) {
				this.currentPage = to.meta.title;
				document.title = `${to.meta.title} | Trelássio`;
			}
		},
	},

	mounted() {
		document.title = this.$route && this.$route.meta
			? `${this.$route.meta.title} | Trelássio` : 'Trelássio';

		this.getWorkspaces().then((data) => {
			this.setWorkspaces(data);
			if (this.$route.params && this.$route.params.workspaceId) {
				this.setSelectedWorkspace(this.workspaces.filter(({
					id,
				}) => id === this.$route.params.workspaceId)[0]);
			} else {
				this.setSelectedWorkspace(null);
			}
		});
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
		this.getWorkspaces().then((data) => {
			this.setWorkspaces(data);
		});
		this.getGoals().then((data) => {
			this.setGoals(data);
		});
	},

	methods: {
		...mapActions('workspaces', [
			'getWorkspaces',
		]),
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
		...mapActions('workspaces', [
			'getWorkspaces',
		]),
		...mapActions('goals', [
			'getGoals',
		]),
		...mapMutations('workspaces', {
			setWorkspaces: 'setItems',
			setSelectedWorkspace: 'setSelectedWorkspace',
		}),
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
		...mapMutations('workspaces', {
			setWorkspaces: 'setItems',
		}),
		...mapMutations('goals', {
			setGoals: 'setItems',
		}),
		logout() {
			window.location.href = '/logout';
		},
		getToFromWorkspace(workspaceId) {
			if (workspaceId === this.currentWorkspace.id) return;
			const { name } = this.$route;
			const params = {
				workspaceId,
				teamId: name === 'sprint' ? this.teamByWorkspace[0].id : null,
			};
			this.$router.push({
				name,
				params: {
					...Object
						.keys(params)
						.filter((key) => !!params[key])
						.reduce((acc, curr) => {
							acc = {
								[curr]: params[curr],
								...acc,
							};
							return acc;
						}, {}),
				},
			});
		},

		goToCompanyPlanning() {
			this.$router.push({
				name: 'workspace.company',
			});
		},

		goToSettings() {
			this.$router.push({ name: 'settings' });
		},
	},
};
</script>
<style scoped>
.v-application {
	background-color: rgb(250, 250, 250); /*#F4F4F6;*/
	font-family: 'Poppins', sans-serif;
}
</style>
