import settingsRoutes from '../../settings/routes';

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
		path: '/workspace/:workspaceId/planning',
		name: 'planning',
		component: () => import('../views/Planning'),
		meta: {
			title: 'Planning',
		},
	},
	{
		path: '/workspace/:workspaceId/sprint/:teamId',
		name: 'sprint',
		component: () => import('../views/Sprint'),
		meta: {
			title: 'Sprint',
		},
		props: true,
	},
	{
		path: '/workspace/select',
		name: 'workspace.select',
		component: () => import('../views/WorkspaceSelection'),
		meta: {
			title: 'Selecione seu Workspace',
		},
	},
	...settingsRoutes,
];
