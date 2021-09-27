<template>
	<v-container>
		<process-form 
			v-model="form"
			@save="save"
		/>
	</v-container>
</template>

<script>
import { mapActions, mapState } from 'vuex';
import convertKeysToSnakeCase from '../../../core/utils/convertKeysToSnakeCase';
import ProcessForm from '../components/ProcessForm.vue';

export default {
  components: { ProcessForm },
	props: {
		teamId: {
			type: String,
			default: null,
		}
	},

	data() {
		return {
			form: {
				name: null,
				teamIds: [],
				checklists: [],
			},
		}
	},

	computed: {
		...mapState('processes', {
			loading: ({ createProcess }) => createProcess.isFetching,
		}),
	},

	methods: {
		isEmpty: (arg) => _.isEmpty(arg),
		...mapActions('processes', [
			'createProcess',
		]),

		save () {
			this.createProcess(convertKeysToSnakeCase(this.form))
				.then((item) => {
					this.$router.push('/processesy');
				});
		},
	},

}
</script>
