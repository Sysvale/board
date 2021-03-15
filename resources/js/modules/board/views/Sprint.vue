<template>
	<v-container
		fluid
		class="px-0 py-0"
	>
		<v-tabs
			grow
			background-color="transparent"
			:value="teamId"
		>
			<v-tab
				v-for="team in teams"
				:key="team.id"
				:to="team.id"
				replace
			>
				{{ team.name }}
			</v-tab>
			<v-tab-item
				v-for="team in teams"
				:key="team.id"
				:value="team.id"
			>
				<div class="mx-3 my-3">
					<v-alert
						v-if="getGoalByTeamId(team.id) && getGoalByTeamId(team.id).title"
						border="left"
						color="secondary"
						icon="flag"
						text
					>
						{{ getGoalByTeamId(team.id).title }}
					</v-alert>
				</div>
				<sprint-tab-content
					:team-id="team.id"
				/>
			</v-tab-item>
		</v-tabs>
	</v-container>
</template>

<script>
import SprintTabContent from '../components/SprintTabContent.vue';
import { mapGetters } from 'vuex';

export default {
	components: {
		SprintTabContent,
	},

	props: {
		teamId: {
			type: String,
			default: null,
		}
	},

	computed: {
		...mapGetters('teams', {
			teams: 'itemsByWorkspace',
		}),
		...mapGetters('goals', ['getGoalByTeamId']),
	},
}
</script>
