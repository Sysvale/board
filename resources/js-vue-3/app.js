import routes from './core/routes';
import { createApp } from 'vue';
import { createRouter, createWebHistory } from 'vue-router';
import Cuida from '@sysvale/cuida';
import SHOW from '@sysvale/show';

import App from './App.vue';

import PageWrapper from './core/components/PageWrapper.vue';

import '@sysvale/cuida/dist/style.css';


const vueApp = createApp(App);

const router = createRouter({
	history: createWebHistory(),
	routes,
});

vueApp.component('PageWrapper', PageWrapper);

vueApp.use(Cuida);
vueApp.use(SHOW);
vueApp.use(router);

router.isReady().then(() => {
	vueApp.mount("#app-vue-3");
})