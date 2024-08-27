export const routes = {
	INDEX_Settings: {
		label: 'Membros',
		name: 'index-members',
		path: 'index/members',
		components: {
			default: () => import('../pages/MembersPage.vue')
		},
		meta: {
			description: 'Membros',
		},
	},
};

export default routes;