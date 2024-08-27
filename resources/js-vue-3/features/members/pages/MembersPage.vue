
<template>
	<CuidsPage
		:page-settings="pageSettings"
		:service="MemberService"
		@add-item="showCreateMemberModal = true"
	>
		<template v-slot:create="{ createRequestProps }">
			<create-member-modal
				v-model="showCreateMemberModal"
				:requestProps="createRequestProps"
			/>
		</template>
		<template v-slot:update="{ updateRequestProps }">
			<update-member-modal
				v-model="showUpdateMemberModal"
				:selected-item="selectedItem"
				:requestProps="updateRequestProps"
			/>
		</template>
		<template v-slot:index="{ items, deleteRequestProps }">
			<cds-dialog-modal
				v-model="showDeleteMemberModal"
				:title="pageSettings.deleteConfirmationTitle"
				:description="pageSettings.deleteConfirmationText"
				action-button-variant="red"
				ok-button-text="Sim, excluir"
				@close="showDeleteMemberModal = false"
				@ok="() => deleteRequestProps.action(selectedItem.id)"
			/>
			<members-table
				:items="items"
				@edit-item-click="handleUpdateItemClick"
				@delete-item-click="handleDeleteItemClick($event, deleteRequestProps.action)"
			/>
		</template>
	</CuidsPage>
</template>
<script>
import { MemberService, Member, MembersPageSettings } from '../../../shared/domain/member/index';
import CuidsPage from '../../../core/components/CuidsPage.vue';
import MembersTable from '../components/MembersTable.vue';
import CreateMemberModal from '../components/CreateMemberModal.vue';
import UpdateMemberModal from '../components/UpdateMemberModal.vue';
export default {
	components: {
		CuidsPage,
		MembersTable,
		CreateMemberModal,
		UpdateMemberModal,
	},
	data() {
		return {
			MemberService,
			selectedItem: new Member(),
			showCreateMemberModal: false,
			showUpdateMemberModal: false,
			showDeleteMemberModal: false,
			pageSettings: new MembersPageSettings(),
		};
	},
	methods: {
		handleUpdateItemClick(item) {
			this.selectedItem = item;
			this.showUpdateMemberModal = true;
		},
		handleDeleteItemClick(item) {
			this.showDeleteMemberModal = true;
			this.selectedItem = item;
		},
	}
}
</script>