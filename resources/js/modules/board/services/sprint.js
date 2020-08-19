import generateUUID from '../../../core/utils/generateUUID';

const id = () => generateUUID().split('-')[0];

export const getDefaultLists = () => new Promise((resolve, reject) => {
	setTimeout(() => {
		const data = [
			{
				id: id(),
				name: 'To Do',
				position: 1,
			},
			{
				id: id(),
				name: 'Em desenvolvimento',
				position: 2,
			},
			{
				id: id(),
				name: 'Code Review/Test',
				position: 3,
			},
			{
				id: id(),
				name: 'Done/To Release',
				position: 4,
			},
			{
				id: id(),
				name: 'Deploy',
				position: 5,
			},
		];
		resolve({ data });
	})
});
