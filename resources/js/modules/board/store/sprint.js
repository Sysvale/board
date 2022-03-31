import makeRequestStore from '../../../core/utils/makeRequestStore';

import {
	getCurrentSprintSummaryByTeam,
	getCurrentSprintOverviewByTeam,
} from '../services/sprint';

const modules = [
	{ getCurrentSprintSummaryByTeam },
	{ getCurrentSprintOverviewByTeam },
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
