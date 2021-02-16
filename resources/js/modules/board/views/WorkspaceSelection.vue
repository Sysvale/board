<template>
	<v-container
		class="px-2 py-5"
		fill-height
	>
			<div class="justify-center align-center text-center w-100" style="margin: 0 auto">
				<h1 class="text-center">Hello, baby!</h1>
				<h2 class="text-center">Onde vamos trabalhar agora?</h2>
				<v-row>
					<v-col
						v-for="workspace in workspaces"
						:key="workspace.id"
					>
						<div
							class="workspace-card text-center px-5 py-5"
							@click="goTo(workspace.id)"
						>
							<div
								class="workspace-avatar"
								style="background-image:url('https://cdn.vuetifyjs.com/images/cards/cooking.png')"
							>
							</div>
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
