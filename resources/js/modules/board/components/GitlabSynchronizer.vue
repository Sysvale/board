<template></template>

<script>
import {
	getCardsByListsIds,
} from '../services/cards';
import { mapActions, mapMutations, mapState, mapGetters } from 'vuex';

export default {
	computed: {
		...mapState('cards', {
			finishedSynchronize: ({ synchronize }) => !synchronize.isFetching && synchronize.hasSucceeded,
		}),
		...mapState('issues', {
			issuesLists: 'items',
		}),
		issuesListsIds() {
			return this.issuesLists.map(list => list.id);
		},
	},

	watch: {
		finishedSynchronize: {
			handler(newValue, oldValue) {
				if (!oldValue && newValue) {
					this.$emit('finished', true);
				}
			},
		}
	},

	created() {
		this.getIssuesLists().then((data) => {
			this.setIssuesLists(data);
		});

		this.synchronize()
			.finally(() => {
				this.getCardsByListsIds({ lists_ids: this.issuesListsIds });
			});
	},

	methods: {
		getCardsByListsIds,

		...mapActions('cards', ['synchronize']),

		...mapActions('issues', [
			'getIssuesLists',
		]),
		...mapMutations('issues', {
			setIssuesLists: 'setItems'
		}),
	},
};
</script>
