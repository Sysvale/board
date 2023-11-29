import makeRequestStore from '../../../core/utils/makeRequestStore';

import {
	createSprintReport,
	getSprintReports,
} from '../services/sprintReport';

const modules = [
	{ createSprintReport },
	{ getSprintReports },
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
