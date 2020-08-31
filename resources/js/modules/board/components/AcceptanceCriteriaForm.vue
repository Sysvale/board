<template>
	<div>
		<ul>
			<li
				v-for="(criteria, i) in value"
				:key="i"
				class="py-2"
			>
				<div class="d-flex">
					<div class="flex-grow-1">
						<div>
							{{ criteria }}
						</div>
					</div>
					<div>
						<v-btn
							v-if="false"
							text
							x-small
							disabled
						>
							Editar
						</v-btn>
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
		<div class="d-flex mt-2">
			<v-text-field
				v-model="newCriteria"
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
			newCriteria: null,
		}
	},

	computed: {
		addButtonText() {
			if(this.editMode) {
				return 'Salvar';
			}
			return 'Adicionar';
		}
	},

	methods: {
		handleAdd() {
			if(this.newCriteria === null || this.newCriteria.trim() === '' ) return;
			this.$emit('input', [...(this.value || []), this.newCriteria]);
			this.newCriteria = null;
		},

		handleRemove(index) {
			this.$emit('input', [
				...this.value.filter((_, i) => index !== i)
			]);
		},
	}
}
</script>
