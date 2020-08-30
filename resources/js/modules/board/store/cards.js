import makeRequestStore from '../../../core/utils/makeRequestStore';

import {
	deleteCard,
	createCard,
	updateCard,
	updateCardsPositions,
	createCards
} from '../services/cards';

const modules = [
	{ deleteCard },
	{ createCard },
	{ updateCard },
	{ updateCardsPositions },
	{ createCards },
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
