export default [
	{
		path: '/home',
		name: 'home',
		component: () => import('../views/Home'),
		meta: {
			title: 'Home',
		},
	},
	{
		path: '/',
		name: 'planning',
		component: () => import('../views/Planning'),
		meta: {
			title: 'Planning',
		},
	},
	{
		path: '/sprint/:teamId',
		name: 'sprint',
		component: () => import('../views/Sprint'),
		meta: {
			title: 'Sprint',
		},
		props: true,
	},
];
