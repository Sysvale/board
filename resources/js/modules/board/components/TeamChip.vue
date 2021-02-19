<template>
	<span
		class="team-chip px-2"
		:class="{
			'avengers': isAvengers(),
			'stepper': isStepperLabs(),
		}"
		:title="getName()"
	>
		<small class="text-uppercase">
			{{ getName() }}
		</small>
	</span>
</template>
<script>
import { mapGetters } from 'vuex'
export default {
	props: {
		teamId: {
			type: String,
			default: null,
		},
	},

	computed: {
		...mapGetters('teams', {
			teams: 'itemsByWorkspace',
		}),
	},

	methods: {
		getName() {
			return this.teams.filter(({ id }) => id === this.teamId)[0].name;
		},
		isAvengers() {
			return this.getName().toLowerCase().indexOf('avengers') > -1;
		},
		isStepperLabs() {
			return this.getName().toLowerCase().indexOf('stepper') > -1;
		}
	}
}
</script>
<style scoped>
.team-chip {
	background: #dce6ff;
	border-radius: 3px;
	text-align: center;
	color: #3a7efd;
	border: 1px solid #3a7efd;
}

.avengers {
	background: #ce2a2d;
	border: 1px solid #741819;
	color: #fff;
}

.stepper {
	background: #285AB9;
	border: 1px solid #143570;
	color: #fff;
}
</style>
