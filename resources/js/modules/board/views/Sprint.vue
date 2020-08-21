<template>
	<v-container
		fluid
	>
		<v-tabs
			grow
		>
			<v-tab
				v-for="team in teams"
				:key="team.id"
			>
				{{ team.name }}
			</v-tab>
			<v-tab-item
				v-for="team in teams"
				:key="team.id"
			>
				<sprint-tab-content
					:team-id="team.id"
				/>
			</v-tab-item>
		</v-tabs>
	</v-container>
</template>

<script>
import SprintTabContent from '../components/SprintTabContent.vue';
import { mapState } from 'vuex';

export default {
	components: {
		SprintTabContent,
	},

	computed: {
		...mapState('teams', {
			teams: 'items',
		}),
	},
}
</script>
