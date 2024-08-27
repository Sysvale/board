
<template>
	<CuidsPage
		:page-settings="pageSettings"
		:service="WorkspaceService"
		@add-item="showCreateWorkspaceModal = true"
	>
		<template v-slot:create="{ createRequestProps }">
			<create-workspace-modal
				v-model="showCreateWorkspaceModal"
				:requestProps="createRequestProps"
			/>
		</template>
		<template v-slot:update="{ updateRequestProps }">
			<update-workspace-modal
				v-model="showUpdateWorkspaceModal"
				:selected-item="selectedItem"
				:requestProps="updateRequestProps"
			/>
		</template>
		<template v-slot:index="{ items, deleteRequestProps }">
			<cds-dialog-modal
				v-model="showDeleteWorkspaceModal"
				:title="pageSettings.deleteConfirmationTitle"
				:description="pageSettings.deleteConfirmationText"
				action-button-variant="red"
				ok-button-text="Sim, excluir"
				@close="showDeleteWorkspaceModal = false"
				@ok="() => deleteRequestProps.action(selectedItem.id)"
			/>
			<workspaces-table
				:items="items"
				@edit-item-click="handleUpdateItemClick"
				@delete-item-click="handleDeleteItemClick($event, deleteRequestProps.action)"
			/>
		</template>
	</CuidsPage>
</template>
<script>
import { WorkspaceService, Workspace, WorkspacesPageSettings } from '../../../shared/domain/workspace/index';
import CuidsPage from '../../../core/components/CuidsPage.vue';
import WorkspacesTable from '../components/WorkspacesTable.vue';
import CreateWorkspaceModal from '../components/CreateWorkspaceModal.vue';
import UpdateWorkspaceModal from '../components/UpdateWorkspaceModal.vue';
export default {
	components: {
		CuidsPage,
		WorkspacesTable,
		CreateWorkspaceModal,
		UpdateWorkspaceModal,
	},
	data() {
		return {
			WorkspaceService,
			selectedItem: new Workspace(),
			showCreateWorkspaceModal: false,
			showUpdateWorkspaceModal: false,
			showDeleteWorkspaceModal: false,
			pageSettings: new WorkspacesPageSettings(),
		};
	},
	methods: {
		handleUpdateItemClick(item) {
			this.selectedItem = item;
			this.showUpdateWorkspaceModal = true;
		},
		handleDeleteItemClick(item) {
			this.showDeleteWorkspaceModal = true;
			this.selectedItem = item;
		},
	}
}
</script>