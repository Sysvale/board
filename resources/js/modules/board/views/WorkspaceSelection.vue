<template>
	<v-container
		class="px-2 py-5"
		fill-height
	>
		<div class="justify-center align-center text-center w-100" style="margin: 0 auto">
			<div class="mb-5">
				<img
					src="/images/logo-natal.png"
					height="118px"
					title="Trelássio"
				>
			</div>
			<h1 style="color: #8B99A7">Onde vamos trabalhar agora?</h1>
			<v-row class="pt-10">
				<v-col
					v-for="workspace in workspaces"
					:key="workspace.id"
				>
					<div
						class="workspace-card text-center px-5 py-5"
						@click="goTo(workspace.id)"
					>
						<lottie-player
							v-if="workspace.lottieFile"
							:src="workspace.lottieFile"
							background="white"
							speed="1"
							style="width: 250px; height: 250px;margin: 0 auto; background: white"
							loop
							autoplay
						/>
						<div
							v-else
							class="workspace-card-fallback"
						>
							{{ getWorkspaceAlias(workspace.name) }}
						</div>
						<div class="mt-5">
							<h3>{{ workspace.name }}</h3>
						</div>
					</div>
				</v-col>
			</v-row>
			<v-container
				class="text-center pb-10"
			>
				<v-btn
					text
					depressed
					outlined
					@click="goToCompanyPlanning"
				>
					Workspace geral
				</v-btn>
			</v-container>
			<div class="mt-5">
				<img src="/images/sysvale-logo.svg">
			</div>
		</div>
	</v-container>
</template>

<script>
import { mapState } from 'vuex';

export default {
	computed: {
		...mapState('workspaces', {
			workspaces: 'items',
		}),
	},
	methods: {
		goTo(workspaceId) {
			this.$router.push({
				name: 'planning',
				params: {
					workspaceId,
				},
			});
		},
		goToCompanyPlanning() {
			this.$router.push({
				name: 'workspace.company',
			});
		},
		getWorkspaceAlias(name) {
			if (!name) return 'WS';
			if (name.split(' ').length > 1) {
				return `${name.split(' ')[0][0]}${name.split(' ')[1][0]}`
					.toUpperCase();
			}
			return `${name[0]}${name[1] || ''}`.toUpperCase();
		},
	},
};
</script>
<style scoped>
.workspace-card:hover {
	border: 1px solid #ccc;
	cursor: pointer;
	border-radius: 20px;
	background: white;
}

.workspace-card-fallback {
	background: #EFF5FB;
	color: #8B99A7;
	font-size: 100px;
	display: flex;
	justify-content: center;
	justify-items: center;
	align-items: center;
	width: 250px;
	height: 250px;
	margin: 0 auto;
}
</style>
