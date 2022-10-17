import http from '../../../http';

export const createCard = (payload) => http.post('/cards', payload);

export const createCards = (payload) => http.post('/cards/store-many', payload);

export const getTaskCardsFromUserStory = (params) => http.get('/cards/from-user-story', {
	params,
});

export const getTaskCardsFromDevlog = (params) => http.get('/cards/from-devlog', {
	params,
});

export const getTaskCardsFromNotPlanned = (params) => http.get('/cards/from-not-planned', {
	params,
});

export const getTaskCardsFromKaizen = (params) => http.get('/cards/from-kaizen', {
	params,
});

export const getCompanyPlanningCards = (params) => http.get('/cards/from-company-planning', {
	params,
});

export const getPlanningCards = (params) => http.get('/cards/from-planning', {
	params,
});

export const updateCard = ({ id, ...params }) => http.put(`/cards/${id}`, params);

export const deleteCard = (id) => http.delete(`/cards/${id}`);

export const deleteManyCards = (payload) => http.delete('/cards/delete-many', { data: payload });

export const updateCardsPositions = (cards = []) => http.post('/cards/update-positions', { cards });
