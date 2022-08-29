<template>
	<v-list-item>
		<v-list-item-action v-if="!editMode">
			<v-checkbox
				v-model="internalItem.done"
				:readonly="readonly"
				@click="$emit('input', internalItem)"
			/>
		</v-list-item-action>

		<v-list-item-content>
			<v-list-item-title :class="{ 'done': internalItem.done }">
				<span v-if="editMode">
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
						@keydown.enter="finishEdit"
						@blur="finishEdit"
					/>
				</span>

				<span v-else>
					<a
						v-if="internalItem.hasUrlDescription"
						target="_blank"
						:href="internalItem.formattedUrlDescription.url"
					>
						{{ internalItem.formattedUrlDescription.text }}
					</a>
					<div v-else>
						{{ internalItem.description }}
					</div>
				</span>
			</v-list-item-title>
		</v-list-item-content>
		<div v-if="!noActions">
			<v-btn
				v-if="!internalItem.done && !editMode"
				icon
				@click="handleEdit"
			>
				<v-icon>edit</v-icon>
			</v-btn>

			<v-btn
				v-if="!editMode"
				icon
				@click="$emit('remove')"
			>
				<v-icon>delete</v-icon>
			</v-btn>
		</div>
	</v-list-item>
</template>

<script>
import ChecklistItem from '../entities/ChecklistItem';

export default {
	props: {
		value: {
			type: Object,
			required: true,
		},
		noActions: {
			type: Boolean,
			default: false,
		},
		readonly: {
			type: Boolean,
			default: false,
		},
	},

	data() {
		return {
			internalItem: {},
			editMode: false,
			editItem: '',
		};
	},

	mounted() {
		this.internalItem = new ChecklistItem(this.value);
	},

	methods: {
		handleEdit() {
			this.editMode = true;
			this.editItem = `${this.internalItem.description}`;
		},

		finishEdit() {
			this.internalItem.description = this.editItem;
			this.$emit('input', this.internalItem);
			this.editItem = '';
			this.editMode = false;
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
