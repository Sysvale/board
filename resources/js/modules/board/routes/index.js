export default [
	{
		path: '/',
		name: 'planning',
		component: () => import('../views/Planning'),
	},
	{
		path: '/sprint',
		name: 'sprint',
		component: () => import('../views/Sprint'),
	},
];
