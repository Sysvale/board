import makeRequestStore from '../../../core/utils/makeRequestStore';

import {
	getCurrentSprintSummaryByTeam,
} from '../services/sprint';

const modules = [
	{ getCurrentSprintSummaryByTeam },
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
