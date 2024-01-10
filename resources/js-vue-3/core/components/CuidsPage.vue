<template>
	<PageWrapper
		:title="pageSettings.pageTitle"
		:subtitle="pageSettings.pageSubtitle"
	>
		<template #page-title-action>
			<cds-button
				class="d-flex align-items-center ml-4"
				variant="amber"
				:text="pageSettings.addItemButtonText"
				@click="$emit('add-item')"
			/>
		</template>
		<div>
			<show-request-provider
				v-slot="createRequestProps"
				ref="createProvider"
				:service="service.create"
				hide-error-feedback
				show-success-feedback
				:success-swal-config="createSuccessSwalConfig"
			>
				<slot
					name="create"
					:createRequestProps="createRequestProps"
				/>
			</show-request-provider>
			<show-request-provider
				v-slot="updateRequestProps"
				ref="updateProvider"
				:service="service.update"
				hide-error-feedback
				show-success-feedback
				:success-swal-config="updateSuccessSwalConfig"
			>
				<slot
					name="update"
					:updateRequestProps="updateRequestProps"
				/>
			</show-request-provider>
			<show-request-provider
				ref="indexProvider"
				v-slot="indexRequestProps"
				immediate
				:service="service.index"
				:initial-data="{ data: [] }"
			>
				<show-request-provider
					v-slot="deleteRequestProps"
					ref="deleteProvider"
					:service="service.delete"
					show-success-feedback
					:success-swal-config="deleteSuccessSwalConfig"
					@success="handleSuccessDelete"
				>
					<div class="container">
						<cds-spinner
							v-if="indexRequestProps.loading"
							variant="indigo"
							size="lg"
							class="container__spinner"
						/>
						<cds-empty-state
							v-else-if="!indexRequestProps.loading && indexRequestProps.data.length === 0"
							:image="pageSettings.emptyStateImage"
							:image-description="pageSettings.emptyStateImageDescription"
							:title="pageSettings.emptyStateTitle"
							:text="pageSettings.emptyStateText"
							:action-button-text="pageSettings.emptyStateActionButtonText"
							@action-button-click="handleEmptyStateActionClick"
						/>
						<div v-else class="container__content">
							<slot
								name="index"
								:items="indexRequestProps.data"
								:deleteRequestProps="getDeleteRequestProps(deleteRequestProps)"
								:indexRequestProps="indexRequestProps"
							/>
						</div>
					</div>
				</show-request-provider>
			</show-request-provider>
		</div>
	</PageWrapper>
</template>
<script>
export default {
	props: {
		service: {
			required: true,
		},
		pageSettings: {
			type: Object,
			default: () => ({}),
		},
	},
	computed: {
		createSuccessSwalConfig() {
			return this.$senswal.defaultConfig.toast({
					icon: 'success',
					title: this.pageSettings.createSuccessFeedbackTitle,
					text: this.pageSettings.createSuccessFeedbackText
				});
		},

		updateSuccessSwalConfig() {
			return this.$senswal.defaultConfig.toast({
					icon: 'success',
					title: this.pageSettings.updateSuccessFeedbackTitle,
					text: this.pageSettings.updateSuccessFeedbackText
				});
		},

		deleteSuccessSwalConfig() {
			return this.$senswal.defaultConfig.toast({
					icon: 'success',
					title: this.pageSettings.deleteSuccessFeedbackTitle,
					text: this.pageSettings.deleteSuccessFeedbackText
				});
		},
	},

	mounted() {
		this.$watch(
			() => this.$refs.updateProvider.succeeded,
			(val) => {
				if(val) {
					this.$refs.indexProvider.action();
				}
			},
		);
		this.$watch(
			() => this.$refs.createProvider.succeeded,
			(val) => {
				if(val) {
					this.$refs.indexProvider.action();
				}
			},
		);
	},
	methods: {
		handleSuccessDelete() {
			this.$refs.indexProvider.action();
		},
		handleEmptyStateActionClick() {
			this.$emit('add-item');
		},
		getDeleteRequestProps(deleteRequestProps) {
			return {
				...deleteRequestProps,
				action: (args) => {
					this.$senswal.confirmation(
						this.pageSettings.deleteConfirmationTitle,
						this.pageSettings.deleteConfirmationText
					).then((result) => {
							if(result.value) {
								deleteRequestProps
									.action({ id: args });
							}
						});
				},
			};
		},
	}
}
</script>
<style lang="scss">
@import 'node_modules/@sysvale/cuida/dist/@sysvale/tokens.scss';

.container {
	background-color: $n-0;
	border: 1px solid $n-20;
	border-radius: 12px;
	color: $n-700 !important;
	margin-top: 40px;

	&__spinner {
		display: flex;
		align-self: center;
		margin: 90px auto;
	}
}
</style>