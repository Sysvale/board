export default [
	{
		path: '/settings/workspaces',
		name: 'settings.workspaces',
		component: () => import('../views/Workspaces'),
		meta: {
			title: 'Workspaces',
		},
	},
	{
		path: '/settings/members',
		name: 'settings.members',
		component: () => import('../views/Members'),
		meta: {
			title: 'Membros',
		},
	},
	{
		path: '/settings',
		name: 'settings',
		component: () => import('../views/Home'),
		meta: {
			title: 'Configurações',
		},
	},
];
