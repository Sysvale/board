<template>
	<cds-table
		:fields="fields"
		:items="items"
		class="table"
	>
		<template
			#table-item="{ data, field }"
		>
			<div
				v-if="field === 'actions'"
				class="table__actions"
			>
				<cds-icon-button
					id="edit-button"
					size="sm"
					icon="edit-outline"
					:tooltip-text="'Editar'"
					@click="editItemClick(data)"
				/>
				<cds-icon-button
					id="delete-button"
					variant="blue"
					size="sm"
					icon="trash-outline"
					:tooltip-text="'Excluir'"
					@click="deleteItemClick(data)"
				/>
			</div>
			<div v-else>
				{{ data[field] }}
			</div>
		</template>
	</cds-table>
</template>
<script>
export default {
	props: {
		items: {
			type: Array,
			default: () => [],
		},
	},
	data() {
		return {
			fields: [
				{
					key: 'name',
					label: 'Nome'
				},
				{
					key: 'teamsNames',
					label: 'Times'
				},
				{
					key: 'actions',
					label: 'Ações'
				},
			],
		}
	},
	methods: {
		editItemClick(item) {
			this.$emit('edit-item-click', item);
		},
		deleteItemClick(item) {
			this.$emit('delete-item-click', item);
		},
	},
};
</script>
<style lang="scss">
@import 'node_modules/@sysvale/cuida/dist/@sysvale/tokens.scss';

.table {
	&__actions {
		display: flex;
		justify-content: flex-start;
		gap: spacer(2);
	}
}
</style>