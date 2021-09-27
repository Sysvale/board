<template>
	<v-container>
		<v-skeleton-loader
			v-if="isFetching"
			class="mx-auto"
			type="form"
		></v-skeleton-loader>
		<process-form
			v-else
			:key="form ? form.id : 'key'"
			v-model="form"
			editing
			@save="save"
		/>
		<div class="mt-5">
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
					Tem certeza que deseja excluir este processo?
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
					@click="removeProcess"
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
	</v-container>
</template>

<script>
import { mapActions, mapState } from 'vuex';
import convertKeysToCamelCase from '../../../core/utils/convertKeysToCamelCase';
import convertKeysToSnakeCase from '../../../core/utils/convertKeysToSnakeCase';
import ProcessForm from '../components/ProcessForm.vue';

export default {
  components: { ProcessForm },
	props: {
		id: {
			type: String,
			default: null,
		}
	},

	data() {
		return {
			form: this.selectedProcess,
			showDeleteConfirmation: false,
		}
	},


	computed: {
		...mapState('processes', {
			processes: state => state.items,
			isUpdating: ({ updateProcess }) => updateProcess.isFetching,
			isFetching: ({ getProcess }) => getProcess.isFetching,
			isDeleting: ({ deleteProcess }) => deleteProcess.isFetching,
		}),

		selectedProcess() {
			return this.processes.filter(({ id }) => id === this.id)[0] || {};
		}
	},
	
	mounted() {
		this.fetchProcess();
	},

	methods: {
		isEmpty: (arg) => _.isEmpty(arg),
		...mapActions('processes', [
			'getProcess',
			'updateProcess',
			'deleteProcess',
		]),

		fetchProcess() {
			this.getProcess(this.id)
				.then((item) => {
					this.form = convertKeysToCamelCase(item);
				})
		},

		removeProcess() {
			this.deleteProcess(this.id)
				.then(() => {
					this.$router.push('/processesy');
				});
		},

		save () {
			this.updateProcess(convertKeysToSnakeCase(this.form))
				.then(() => {
					this.$router.push('/processesy');
				});
		},
	},

}
</script>
