<template>
	<v-container
		class="px-2 py-5"
		fill-height
	>
			<div class="justify-center align-center text-center w-100" style="margin: 0 auto">
				<div class="mb-5">
					<img
						src="/images/logo-dark.svg"
						height="50px"
						title="aka Trelindo"
					/>
				</div>
				<h1 style="color: #888">Bão? Então tá bão!</h1>
				<h2 style="color: #888">Onde vamos trabalhar agora?</h2>
				<v-row class="py-10">
					<v-col
						v-for="workspace in workspaces"
						:key="workspace.id"
					>
						<div
							class="workspace-card text-center px-5 py-5"
							@click="goTo(workspace.id)"
						>
							<lottie-player
								:src="workspace.lottieFile"
								background="white"
								speed="1"
								style="width: 250px; height: 250px;margin: 0 auto; background: white"
								loop
								autoplay
							>
							</lottie-player>
							<div class="mt-5">
								<h3>{{ workspace.name }}</h3>
							</div>
						</div>
					</v-col>
				</v-row>
				<div class="mt-5">
					<img src="/images/sysvale-logo.svg" />
				</div>
			</div>
	</v-container>
</template>

<script>
import { mapState } from 'vuex'

export default {
	data() {
		return {
		}
	},
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
	},
}
</script>
<style scoped>
.workspace-avatar {
	border-radius: 50%;
	width: 200px;
	height: 200px;
	margin: 0 auto;
}

.workspace-card:hover {
	border: 1px solid #ccc;
	cursor: pointer;
	border-radius: 20px;
	background: white;
}
</style>
