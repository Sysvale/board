<template>
	<div>
		<div
			v-if="value && value.length === 0"
			class="pb-2 grey--text caption text-center"
		>
			Ainda não há nenhum item na sua checklist.
		</div>

		<v-list flat>
			<v-list-item-group>
				<v-list-item
					v-for="(item, i) in value"
					:key="i"
				>
					<template #default="{ toggle }">
						<v-list-item-action v-if="!editMode || selectedIndex !== i">
							<v-checkbox
								v-model="item.done"
								@click="toggle"
							/>
						</v-list-item-action>

						<v-list-item-content>
							<v-list-item-title :class="{ 'done': item.done }">
								<span v-if="editMode && selectedIndex === i">
									<v-text-field
										v-model="editItem"
										autofocus
										outlined
										flat
										dense
										class="mt-0 mb-0 pt-0 pb-0"
										info
										hide-details
										color="secondary"
										@keydown.enter="finishEdit(i)"
										@blur="finishEdit(i)"
									/>
								</span>

								<span v-else>
									{{ item.description }}
								</span>
							</v-list-item-title>
						</v-list-item-content>

						<v-btn
							v-if="!item.done && (!editMode || selectedIndex !== i)"
							icon
							@click="handleEdit(i)"
						>
							<v-icon>edit</v-icon>
						</v-btn>

						<v-btn
							v-if="!editMode || selectedIndex !== i"
							icon
							@click="handleRemove(i)"
						>
							<v-icon>delete</v-icon>
						</v-btn>
					</template>
				</v-list-item>
			</v-list-item-group>
		</v-list>

		<div class="d-flex mt-2">
			<v-text-field
				v-model="newItem"
				outlined
				flat
				dense
				class="mr-2"
				@keydown.enter="handleAdd"
			/>

			<v-btn
				@click="handleAdd"
			>
				{{ addButtonText }}
			</v-btn>
		</div>
	</div>
</template>

<script>
export default {
	props: {
		value: {
			type: Array,
			default: () => [],
		},
	},

	data() {
		return {
			newItem: null,
			editItem: null,
			editMode: false,
			selectedIndex: -1,
		};
	},

	computed: {
		addButtonText() {
			return 'Adicionar';
		},
	},

	methods: {
		handleAdd() {
			if (this.newItem === null || this.newItem.trim() === '') return;
			this.$emit('input', [...(this.value || []), {
				description: this.newItem,
			}]);
			this.newItem = null;
		},

		handleRemove(index) {
			this.$emit('input', [
				...this.value.filter((_, i) => index !== i),
			]);
		},

		handleToggle(index) {
			this.$emit('input', [
				...this.value.map((item, i) => {
					if (index === i) {
						item.done = !item.done;
					}

					return item;
				}),
			]);
		},

		handleEdit(index) {
			this.editItem = `${this.value[index].description}`;
			this.editMode = true;
			this.selectedIndex = index;
		},

		finishEdit(index) {
			this.$emit('input', [
				...this.value.map((item, i) => {
					if (index === i) {
						item.description = this.editItem;
					}
					return item;
				}),
			]);
			this.editMode = false;
			this.selectedIndex = -1;
			this.editItem = null;
		},
	},
};
</script>

<style scoped>
.done {
	text-decoration: line-through;
	opacity: 0.3;
}

.v-list-item__subtitle, .v-list-item__title {
	flex: 1 1 100%;
	white-space: normal !important;
}
</style>
