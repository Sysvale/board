export default [
	{
		path: '/settings/workspaces',
		name: 'settings.workspaces',
		component: () => import('../views/Workspaces'),
		meta: {
			title: 'Workspaces',
		},
	},
];
