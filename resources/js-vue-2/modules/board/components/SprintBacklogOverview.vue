<template>
	<div class="px-5 py-5">
		<v-data-table
			:headers="headers"
			:items="data"
			:loading="loading"
			item-class="text-center"
			loading-text="Carregando informações..."
			class="elevation-1"
			hide-default-footer
			:items-per-page="-1"
		>
			<template
				v-slot:item.name="{ item }"
			>
				<v-tooltip
					bottom
					max-width="250px"
					color="rgba(0, 0, 0, 0.9)"
				>
					<template v-slot:activator="{ on, attrs }">
						<div
							v-bind="attrs"
							class="ellipsis text-center"
							v-on="on"
						>
							{{ item.name }}
						</div>
					</template>
					<span>{{ item.name }}</span>
				</v-tooltip>
			</template>

			<template
				v-for="header in headers"
				v-slot:[`header.${header.value}`]="{ header }"
			>
				<div class="text-center">
					<div v-if="header.value === 'name'">
						{{ header.text }}
					</div>
					<div
						v-else
						class="px-5 py-5 text-center"
						:style="{
							minWidth: `40px`,
							minHeight: `40px`,
						}"
					>
						<div
							class="counter"
							:style="{
								width: `${10 * sumByHeader(header.value)}px`,
								height: `${10 * sumByHeader(header.value)}px`,
								minWidth: `40px`,
								minHeight: `40px`,
							}"
						>
							<div>{{ sumByHeader(header.value) }}</div>
						</div>
						<div class="mt-3">
							{{ header.text }}
						</div>
					</div>
				</div>
			</template>
		</v-data-table>
	</div>
</template>
<script>
import { mapActions, mapState } from 'vuex';

export default {
	props: {
		teamId: {
			type: String,
			default: null,
		},
	},

	data() {
		return {
			headers: [],
			data: [],
		};
	},

	computed: {
		...mapState('sprint', {
			loading: ({ getCurrentSprintOverviewByTeam }) => getCurrentSprintOverviewByTeam.isFetching,
		}),
	},

	mounted() {
		this.getCurrentSprintOverviewByTeam(this.teamId)
			.then(({ headers, data }) => {
				this.headers = headers;
				this.data = data;
			});
	},

	methods: {
		...mapActions('sprint', ['getCurrentSprintOverviewByTeam']),

		sumByHeader(header) {
			return this.data.reduce((acc, curr) => {
				acc += curr[header];
				return acc;
			}, 0);
		},
	},
};
</script>
<style scoped>
	.ellipsis {
		width: 250px;
		white-space: nowrap;
		overflow: hidden;
		text-overflow: ellipsis;
	}

	.counter{
		background: #E2EAF3;
		color: #1E252C;
		border-radius: 100%;
		display: flex;
		align-items: center;
		justify-content: center;
		font-size: 16px;
		margin: 0 auto;
	}

	.item-class {
		text-align: 'center'!important;
	}
</style>
