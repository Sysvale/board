<template>
	<v-container
		class="px-2 py-5"
	>
			<v-row>
				<v-col
					v-for="workspace in workspaces"
					:key="workspace.id"
				>
					<v-card
						@click="goTo(workspace.id)"
					>
						<v-img
							height="250"
							src="https://cdn.vuetifyjs.com/images/cards/cooking.png"
						></v-img>
						<v-card-title>
							{{ workspace.name }}
						</v-card-title>
					</v-card>
				</v-col>
			</v-row>
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
