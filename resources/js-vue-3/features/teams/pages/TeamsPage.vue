
<template>
	<CuidsPage
		:page-settings="pageSettings"
		:service="TeamService"
		@add-item="showCreateTeamModal = true"
	>
		<template v-slot:create="{ createRequestProps }">
			<create-team-modal
				v-model="showCreateTeamModal"
				size="lg"
				:requestProps="createRequestProps"
			/>
		</template>
		<template v-slot:update="{ updateRequestProps }">
			<update-team-modal
				v-model="showUpdateTeamModal"
				:selected-item="selectedItem"
				:requestProps="updateRequestProps"
				size="lg"
			/>
		</template>
		<template v-slot:index="{ items, deleteRequestProps }">
			<cds-dialog-modal
				v-model="showDeleteTeamModal"
				:title="pageSettings.deleteConfirmationTitle"
				:description="pageSettings.deleteConfirmationText"
				action-button-variant="red"
				ok-button-text="Sim, excluir"
				@close="showDeleteTeamModal = false"
				@ok="() => deleteRequestProps.action(selectedItem.id)"
			/>
			<teams-table
				:items="items"
				@edit-item-click="handleUpdateItemClick"
				@delete-item-click="handleDeleteItemClick($event, deleteRequestProps.action)"
			/>
		</template>
	</CuidsPage>
</template>
<script>
import { TeamService, Team, TeamsPageSettings } from '../../../shared/domain/team/index';
import CuidsPage from '../../../core/components/CuidsPage.vue';
import TeamsTable from '../components/TeamsTable.vue';
import CreateTeamModal from '../components/CreateTeamModal.vue';
import UpdateTeamModal from '../components/UpdateTeamModal.vue';
export default {
	components: {
		CuidsPage,
		TeamsTable,
		CreateTeamModal,
		UpdateTeamModal,
	},
	data() {
		return {
			TeamService,
			selectedItem: new Team(),
			showCreateTeamModal: false,
			showUpdateTeamModal: false,
			showDeleteTeamModal: false,
			pageSettings: new TeamsPageSettings(),
		};
	},
	methods: {
		handleUpdateItemClick(item) {
			this.selectedItem = item;
			this.showUpdateTeamModal = true;
		},
		handleDeleteItemClick(item) {
			this.showDeleteTeamModal = true;
			this.selectedItem = item;
		},
	}
}
</script>