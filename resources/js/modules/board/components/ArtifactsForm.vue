<template>
	<div>
		<ul>
			<li
				v-for="(artifact, i) in value"
				:key="i"
				class="py-2"
			>
				<div class="d-flex">
					<div class="flex-grow-1">
						<artifact-item :artifact="artifact" />
					</div>
					<div>
						<v-btn
							text
							x-small
							color="red"
							@click="handleRemove(i)"
						>
							Excluir
						</v-btn>
					</div>
				</div>
			</li>
		</ul>
		<div class="mt-2">
			<v-text-field
				v-model="newArtifact.description"
				placeholder="Descrição"
				:maxlength="maxDescriptionLength"
				outlined
				flat
				dense
				hide-details
				class="mb-2"
				@keydown.enter="handleAdd"
			/>
			<v-text-field
				v-model="newArtifact.url"
				placeholder="URL do artefato"
				outlined
				flat
				dense
				hide-details
				class="mb-2"
				@keydown.enter="handleAdd"
			/>
			<div class="d-flex justify-end">
				<v-btn
					@click="handleAdd"
				>
					Adicionar
				</v-btn>
			</div>
		</div>
	</div>
</template>

<script>
import ArtifactItem from './ArtifactItem.vue';

export default {
	components: {
		ArtifactItem,
	},

	props: {
		value: {
			type: Array,
			default: () => [],
		},
	},

	data() {
		return {
			newArtifact: {
				description: '',
				url: '',
			},
			maxDescriptionLength: 30,
		};
	},

	methods: {
		handleAdd() {
			if (
				this.newArtifact === null
				|| this.newArtifact.description.trim() === ''
				|| this.newArtifact.url.trim() === ''
			) {
				return;
			}
			this.$emit('input', [...(this.value || []), this.newArtifact]);
			this.reset();
		},

		reset() {
			this.newArtifact = {
				description: '',
				url: '',
			};
		},

		handleRemove(index) {
			this.$emit('input', [
				...this.value.filter((_, i) => index !== i),
			]);
		},
	},
};
</script>
