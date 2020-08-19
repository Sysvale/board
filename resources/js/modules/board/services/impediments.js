import generateUUID from '../../../core/utils/generateUUID';

const id = () => generateUUID().split('-')[0];

export const getImpedimentsByTeam = (teamId) => new Promise((resolve, reject) => {
	setTimeout(() => {
		const data = [
			{
				id: id(),
				title: 'Reunião com o BIOS',
				members: [
					{
						id: id(),
						name: 'Rafa Dias',
					},
				]
			},
			{
				id: id(),
				title: 'Outra Reunião com o BIOS',
				members: [
					{
						id: id(),
						name: 'Rafa Dias',
					},
				]
			},
		];

		resolve({ data });
	}, 1000);
})

export const createImpediment = (payload) => new Promise((resolve, reject) => {
	setTimeout(() => {
		let data = {
			id: id(),
			...payload,
		};
		resolve({data});
	}, 1000);
});

export const deleteImpediment = ({ id, ...payload }) => new Promise((resolve, reject) => {
	setTimeout(() => {
		let data = {
			id,
			...payload,
		};
		console.log('deleteImpediment');
		resolve({data});
	}, 1000);
});
