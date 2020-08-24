export default [
	{
		path: '/',
		name: 'planning',
		component: () => import('../views/Planning'),
		meta: {
			title: 'Planning',
		},
	},
	{
		path: '/sprint',
		name: 'sprint',
		component: () => import('../views/Sprint'),
		meta: {
			title: 'Sprint',
		},
	},
];
