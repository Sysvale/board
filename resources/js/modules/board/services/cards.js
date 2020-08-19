import generateUUID from '../../../core/utils/generateUUID';

const id = () => generateUUID().split('-')[0];

export const createCard = (payload) => new Promise((resolve, reject) => {
	setTimeout(() => {
		let data = {
			id: id(),
			...payload,
		};
		console.log('createCard');
		resolve({data});
	}, 1000);
});

export const updateCard = ({ id, ...payload }) => new Promise((resolve, reject) => {
	setTimeout(() => {
		let data = {
			id,
			...payload,
		};
		console.log('updateCard');
		resolve({data});
	}, 1000);
});

// Update card and his positions
export const updateCards = (cards = []) => new Promise((resolve, reject) => {
	setTimeout(() => {
		console.log('updateCards', '(positions updated)');
		resolve(cards);
	}, 1000);
});

// Update card and his positions when is a list change
export const updateCardsLists = ({ listOne, listTwo }) => new Promise((resolve, reject) => {
	setTimeout(() => {
		console.log('updateCardsLists', '(the card list changed)');
		resolve({data:{ listOne, listTwo} });
	}, 1000);
});

export const deleteCard = ({ id, ...payload }) => new Promise((resolve, reject) => {
	setTimeout(() => {
		let data = {
			id,
			...payload,
		};
		console.log('deleteCard');
		resolve({data});
	}, 1000);
});
