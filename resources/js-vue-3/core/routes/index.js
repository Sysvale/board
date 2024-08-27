import NotFoundPage from '../pages/NotFoundPage.vue';
import MembersRoute from '../../features/members/routes';
import WorkspacesRoute from '../../features/workspaces/routes';
import TeamsRoute from '../../features/teams/routes';

const routes = {
	...MembersRoute,
	...WorkspacesRoute,
	...TeamsRoute,
};

const buildRoutes = () => {
	let xRoutes = [];
	Object.keys(routes).forEach((key) => {
		xRoutes = [
			...xRoutes,
			{
				...routes[key],
				path: `/v2/${routes[key].path}`
			},
		];
	});

	return [
		...xRoutes,
		{
			path: "/:catchAll(.*)",
			component: NotFoundPage,
		}
	];
};

console.log(buildRoutes());

export default buildRoutes();
