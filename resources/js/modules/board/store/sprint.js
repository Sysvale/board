import makeRequestStore from '../../../core/utils/makeRequestStore';

import {
	getCurrentSprintSummaryByTeam,
	getCurrentSprintOverviewByTeam,
	getDefaultLists,
	getDevlogLists,
} from '../services/sprint';

const modules = [
	{ getCurrentSprintSummaryByTeam },
	{ getCurrentSprintOverviewByTeam },
	{ getDefaultLists },
	{ getDevlogLists },
	{ getDefaultLists },
];

export default {
	namespaced: true,
	modules: {
		...modules.reduce((acc, module) => ({
			...acc,
			...makeRequestStore(module),
		}), {}),
	},
};
