import defaultTasksStore from '../../../core/utils/defaultTasksStore';

import {
	getDevBacklog,
} from '../services';

const modules = [
	{ fetchItems: getDevBacklog },
];

export default {
	...defaultTasksStore(modules),
}
