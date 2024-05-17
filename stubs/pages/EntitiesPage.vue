
<template>
	<CuidsPage
		:page-settings="pageSettings"
		:service="EntityService"
		@add-item="showCreateEntityModal = true"
	>
		<template v-slot:create="{ createRequestProps }">
			<create-entity-modal
				v-model="showCreateEntityModal"
				:requestProps="createRequestProps"
			/>
		</template>
		<template v-slot:update="{ updateRequestProps }">
			<update-entity-modal
				v-model="showUpdateEntityModal"
				:selected-item="selectedItem"
				:requestProps="updateRequestProps"
			/>
		</template>
		<template v-slot:index="{ items, deleteRequestProps }">
			<entities-table
				:items="items"
				@edit-item-click="handleUpdateItemClick"
				@delete-item-click="handleDeleteItemClick($event, deleteRequestProps.action)"
			/>
		</template>
	</CuidsPage>
</template>
<script>
import { EntityService, Entity, EntitiesPageSettings } from '../../../shared/domain/entity/index';
import CuidsPage from '../../../core/components/CuidsPage.vue';
import EntitiesTable from '../components/EntitiesTable.vue';
import CreateEntityModal from '../components/CreateEntityModal.vue';
import UpdateEntityModal from '../components/UpdateEntityModal.vue';
export default {
	components: {
		CuidsPage,
		EntitiesTable,
		CreateEntityModal,
		UpdateEntityModal,
	},
	data() {
		return {
			EntityService,
			selectedItem: new Entity(),
			showCreateEntityModal: false,
			showUpdateEntityModal: false,
			pageSettings: new EntitiesPageSettings(),
		};
	},
	methods: {
		handleUpdateItemClick(item) {
			this.selectedItem = item;
			this.showUpdateEntityModal = true;
		},
		handleDeleteItemClick(item, deleteAction) {
			this.selectedItem = item;
			deleteAction(item.id);
		},
	}
}
</script>