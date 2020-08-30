<template></template>

<script>
import {
	getCardsByListsIds,
} from '../services/cards';
import LabelKeys from '../constants/LabelKeys';
import GitlabLabelKeys from '../constants/GitlabLabelKeys';
import BoardListKeys from '../constants/BoardListKeys';
import { mapActions, mapMutations, mapState, mapGetters } from 'vuex';

export default {
	data() {
		return {
			LabelKeys,
			GitlabLabelKeys,
			BoardListKeys
		};
	},
	computed: {
		...mapState('labels', {
			labels: 'items'
		}),
		...mapState('issues', {
			issuesLists: 'items',
		}),
		...mapState('problems', ['board']),
		...mapState('gitlab', {
			finishedGetIssuesAmount: ({ getIssuesAmount }) => !getIssuesAmount.isFetching && getIssuesAmount.hasSucceeded,
			finishedGetIssues: ({ getIssues }) => !getIssues.isFetching && getIssues.hasSucceeded,
		}),
		...mapState('cards', {
			finishedCreateCards: ({ createCards }) => !createCards.isFetching && createCards.hasSucceeded,
		}),

		existingIssues() {
			return _.flatten(Object.values(this.board))
				.filter(card => card.fromGitlab)
				.map(card => card.link);
		},

		finished() {
			return this.finishedCreateCards
				&& this.finishedGetIssues
				&& this.finishedGetIssuesAmount;
		}
	},

	watch: {
		finished: {
			handler(oldValue, newValue) {
				if (!oldValue && newValue) {
					setTimeout(() => {
						this.$emit('finished', true);
					}, 500);
				}
			}
		}
	},

	created() {
		this.getIssuesLists().then((data) => {
			this.setIssuesLists(data);
		});

		const maxPerPage = 100;
			this.getIssuesAmount()
				.then(({ statistics: { counts } }) => {
					const { opened } = counts;

					const requestsAmount = Math.ceil(opened/maxPerPage);
					const lastPerPage = opened - (requestsAmount - 1) * maxPerPage;

					for (const i of Array(requestsAmount).keys()) {
						let currentPerPage = (i == requestsAmount - 1) ? lastPerPage : maxPerPage;

						this.getIssues({ per_page: currentPerPage }).then((data) => {
							const cards = data.map(issue => this.mapIssueToCard(issue));

							const cardsToCreate = cards.filter(card => !this.existingIssues.includes(card.link));

							this.createCards({ list: cardsToCreate })
								.finally(() => {
									if (i == requestsAmount - 1) {
										let issuesListIds = this.issuesLists.map(list => list.id);
										this.getCardsByListsIds({ lists_ids: issuesListIds });
									}
								});
						});
					}
				}
			);
	},

	methods: {
		...mapActions('gitlab', [
			'getIssues',
			'getIssuesAmount'
		]),
		...mapActions('cards', [
			'createCards',
		]),
		...mapActions('issues', [
			'getIssuesLists',
		]),
		...mapMutations('issues', {
			setIssuesLists: 'setItems'
		}),
		getCardsByListsIds,

		mapIssueToCard(issue) {
			let { labels, title, web_url } = issue;
			const labelIds = this.mapIssueLabels(labels);

			return {
				title,
				labels: labelIds,
				board_list_id: this.getBoardListId(labels),
				link: web_url,
				from_gitlab: true,
			};
		},

		mapIssueLabels(labels) {
			const dict = {
				[GitlabLabelKeys.BACKEND]: LabelKeys.BACKEND,
				[GitlabLabelKeys.FRONTEND]: LabelKeys.FRONTEND,
				[GitlabLabelKeys.MOCKUP]: LabelKeys.MOCKUP,
				[GitlabLabelKeys.BUG]: LabelKeys.BUG,
				[GitlabLabelKeys.HELP_DESK]: LabelKeys.HELP_DESK,
				[GitlabLabelKeys.APP]: LabelKeys.APP,
				[GitlabLabelKeys.UX]: LabelKeys.UX,
				[GitlabLabelKeys.EXPORT]: LabelKeys.EXPORT
			};
			const mappedLabels = labels.map(label => dict[label]);

			return _.filter(this.labels, item => _.includes(mappedLabels, item.key))
				.map(label => label.id);
		},

		getBoardListId(labels) {
			let key;

			key = BoardListKeys.DEVTASK;
			if (labels.includes(GitlabLabelKeys.HELP_DESK)) {
				key = BoardListKeys.HELPDESK;
			} else if (labels.includes(GitlabLabelKeys.BUG)) {
				key = BoardListKeys.BUGS;
			}

			return this.issuesLists.filter(list => list.key === key)[0].id;
		},
	},
};
</script>
