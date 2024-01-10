import routes from './core/routes';
import { createApp } from 'vue';
import { createRouter, createWebHistory } from 'vue-router';
import Cuida from '@sysvale/cuida';
import SHOW from '@sysvale/show';
import * as VeeValidate from 'vee-validate';
import validationConfig from './shared/validation';

import senswal from './shared/utils/senswal';

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

vueApp.use(VeeValidate, {
	inject: true,
	fieldsBagName: 'veeFields',
});

validationConfig(VeeValidate);

vueApp.config.globalProperties.$senswal = senswal;


router.isReady().then(() => {
	vueApp.mount("#app-vue-3");
})