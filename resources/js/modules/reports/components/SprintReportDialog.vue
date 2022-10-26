<template>
	<v-dialog
		v-model="dialog"
		width="750px"
		height="100%"
		@click:outside="loading ? dialog = false : null"
	>
		<template v-slot:activator="{ on, attrs }">
			<slot
				name="activator"
				:on="on"
				:attrs="attrs"
			/>
		</template>
		<v-card
			class="px-3 py-3"
		>
			<v-card-title>
				{{ readonly ? 'Resumo da sprint' : 'Fechar sprint' }}
			</v-card-title>
			<sprint-report-dialog-content
				v-if="dialog"
				v-model="sprintReport"
				:team-id="teamId"
				:readonly="readonly"
			/>
			<v-card-actions
				v-if="!readonly"
				class="d-flex justify-end"
			>
				<v-btn
					v-if="!showFinishConfirmation"
					:disabled="loading || !isValid"
					color="primary"
					@click="showFinishConfirmation = true"
				>
					Finalizar sprint
				</v-btn>
				<div
					v-else
				>
					<div>
						Tem certeza que deseja finalizar esta sprint?
						<div class="mb-3">
							<div class="grey--text caption">
								Essa ação não poderá ser desfeita
							</div>
						</div>
					</div>
					<v-btn
						color="primary"
						:disabled="loading"
						@click="closeSprint"
					>
						Sim, finalizar
					</v-btn>
					<v-btn
						outlined
						color="secondary"
						:disabled="loading"
						@click="showFinishConfirmation = false"
					>
						Não
					</v-btn>
				</div>
			</v-card-actions>
		</v-card>
	</v-dialog>
</template>

<script>
import { mapActions, mapState } from 'vuex';
import convertKeysToSnakeCase from '../../../core/utils/convertKeysToSnakeCase';
import SprintReportDialogContent from './SprintReportDialogContent.vue';

export default {
	components: {
		SprintReportDialogContent,
	},
	props: {
		teamId: {
			type: String,
			default: null,
		},
		data: {
			type: Object,
			default: null,
		},
		readonly: {
			type: Boolean,
			default: false,
		},
	},

	data() {
		return {
			dialog: false,
			sprintReport: this.data || {},
			showFinishConfirmation: false,
		};
	},

	computed: {
		...mapState('sprintReport', {
			loading: ({ createSprintReport }) => createSprintReport.isFetching,
		}),

		isValid() {
			const {
				startedAt,
				finishedAt,
				members,
				cards,
				impedimentsQuantity,
			} = this.sprintReport;
			return !!startedAt && !!finishedAt
				&& (!!members && members.length > 0)
				&& (!!cards && cards.length > 0)
				&& !!impedimentsQuantity;
		},
	},

	methods: {
		...mapActions('sprintReport', ['createSprintReport']),

		closeSprint() {
			const payload = convertKeysToSnakeCase(this.sprintReport);
			this.createSprintReport(payload)
				.then(() => {
					this.sprintReport = {};
					this.$emit('close');
					this.dialog = false;
				});
		},
	},
};
</script>
