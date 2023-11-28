import NotFoundPage from '../pages/NotFoundPage.vue';
import SettingsRoutes from '../../features/settings/routes';

const routes = {
	...SettingsRoutes,
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
