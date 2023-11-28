<template>
	<div>
		<v-btn
			v-if="!showDeleteConfirmation"
			text
			color="red"
			small
			@click="showDeleteConfirmation = true"
		>
			Excluir
		</v-btn>
		<div
			v-else
		>
			<div>
				{{ confirmMessage }}
				<div class="mb-3">
					<div class="grey--text caption">
						Essa ação não poderá ser desfeita
					</div>
				</div>
			</div>
			<v-btn
				outlined
				color="red"
				small
				@click="$emit('delete')"
			>
				Sim
			</v-btn>
			<v-btn
				outlined
				color="secondary"
				small
				@click="showDeleteConfirmation = false"
			>
				Não
			</v-btn>
		</div>
	</div>
</template>
<script>
export default {
	props: {
		confirmMessage: {
			type: String,
			default: 'Tem certeza que deseja excluir este item?'
		},
	},
	data() {
		return {
			showDeleteConfirmation: false,
		};
	}
}
</script>
