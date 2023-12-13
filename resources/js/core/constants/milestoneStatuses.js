export const dictionary = {
	INCOMPLETE: 'incomplete',
	IN_PROGRESS: 'in-progress',
	DONE: 'done',
	CANCELED: 'canceled',
	NOT_STARTED: 'not-started',
};

export const options = [
	{
		text: 'Em andamento',
		value: dictionary.IN_PROGRESS,
	},
	{
		text: 'Concluído',
		value: dictionary.DONE,
	},
	{
		text: 'Incompleto',
		value: dictionary.INCOMPLETE,
	},
	{
		text: 'Cancelado',
		value: dictionary.CANCELED,
	},
	{
		text: 'Não iniciado',
		value: dictionary.NOT_STARTED,
	},
];
