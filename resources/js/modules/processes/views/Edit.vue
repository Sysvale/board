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
		}
	},


	computed: {
		...mapState('processes', {
			processes: state => state.items,
			isUpdating: ({ updateProcess }) => updateProcess.isFetching,
			isFetching: ({ getProcess }) => getProcess.isFetching,
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
		]),

		fetchProcess() {
			this.getProcess(this.id)
				.then((item) => {
					this.form = convertKeysToCamelCase(item);
				})
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
