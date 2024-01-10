export const routes = {
	INDEX_Settings: {
		label: 'Configurações',
		name: 'index-settings',
		path: 'index/settings',
		components: {
			default: () => import('../pages/MembersPage.vue')
		},
		meta: {
			description: 'Configurações',
		},
	},
};

export default routes;