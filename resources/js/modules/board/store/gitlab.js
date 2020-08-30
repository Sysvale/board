import makeRequestStore from '../../../core/utils/makeRequestStore';

import {
	getIssues,
	getIssuesAmount
} from '../services/gitlab';

const modules = [
	{ getIssues },
	{ getIssuesAmount }
];

export default {
	namespaced: true,
	modules: {
		...modules.reduce((acc, module) => ({
			...acc,
			...makeRequestStore(module),
		}), {}),
	},
}
