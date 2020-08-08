import defaultTasksStore from '../../../core/utils/defaultTasksStore';

import {
	getNotPlannedBacklog,
} from '../services';

const modules = [
	{ fetchItems: getNotPlannedBacklog },
];

export default {
	...defaultTasksStore(modules),
}
